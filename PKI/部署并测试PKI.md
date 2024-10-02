# 在CentOS 7上部署PKI、RA、CA、OCSP、CRL和SCEP的实战案例

## 引言

公钥基础设施（PKI）是网络安全的核心，用于管理数字证书和公钥加密。本文将详细介绍如何在CentOS 7上部署PKI相关组件，包括CA（证书颁发机构）、RA（注册机构）、OCSP（在线证书状态协议）、CRL（证书吊销列表）和SCEP（简单证书注册协议）。

## 前提条件

- 运行CentOS 7的服务器
- 具有`root`权限的用户
- 基本的Linux命令行知识
- 已配置的网络连接

## 1. 安装必要的软件包

首先，更新系统并安装所需的软件包。

```
bash复制代码sudo yum update -y
sudo yum install -y openssl httpd mod_ssl perl
```

## 2. 配置CA（证书颁发机构）

### 2.1 创建目录结构

```
bash复制代码mkdir -p /root/ca/{certs,crl,newcerts,private}
chmod 700 /root/ca/private
touch /root/ca/index.txt
echo 1000 > /root/ca/serial
```

### 2.2 生成CA私钥

```
bash复制代码openssl genrsa -aes256 -out /root/ca/private/ca.key.pem 4096
chmod 400 /root/ca/private/ca.key.pem
```

### 2.3 创建根证书

```
bash复制代码openssl req -config /etc/pki/tls/openssl.cnf \
      -key /root/ca/private/ca.key.pem \
      -new -x509 -days 7300 -sha256 -extensions v3_ca \
      -out /root/ca/certs/ca.cert.pem
chmod 444 /root/ca/certs/ca.cert.pem
```

填写提示的证书信息，如国家、省份、组织等。

## 3. 配置RA（注册机构）

### 3.1 生成RA私钥和证书签名请求（CSR）

```
bash复制代码openssl genrsa -out /root/ca/private/ra.key.pem 4096
chmod 400 /root/ca/private/ra.key.pem

openssl req -config /etc/pki/tls/openssl.cnf \
      -new -key /root/ca/private/ra.key.pem \
      -out /root/ca/csr/ra.csr.pem
```

### 3.2 使用CA签署RA的证书

```
bash复制代码openssl ca -config /etc/pki/tls/openssl.cnf \
      -extensions v3_intermediate_ca -days 3650 -notext -md sha256 \
      -in /root/ca/csr/ra.csr.pem \
      -out /root/ca/certs/ra.cert.pem
chmod 444 /root/ca/certs/ra.cert.pem
```

## 4. 配置OCSP（在线证书状态协议）

### 4.1 生成OCSP密钥和证书

```
bash复制代码openssl genrsa -out /root/ca/private/ocsp.key.pem 4096
chmod 400 /root/ca/private/ocsp.key.pem

openssl req -config /etc/pki/tls/openssl.cnf \
      -new -key /root/ca/private/ocsp.key.pem \
      -out /root/ca/csr/ocsp.csr.pem

openssl ca -config /etc/pki/tls/openssl.cnf \
      -extensions ocsp -days 3650 -notext -md sha256 \
      -in /root/ca/csr/ocsp.csr.pem \
      -out /root/ca/certs/ocsp.cert.pem
chmod 444 /root/ca/certs/ocsp.cert.pem
```

### 4.2 启动OCSP响应服务

```
bash复制代码openssl ocsp -port 2560 \
      -text -sha256 \
      -index /root/ca/index.txt \
      -CA /root/ca/certs/ca.cert.pem \
      -rkey /root/ca/private/ocsp.key.pem \
      -rsigner /root/ca/certs/ocsp.cert.pem
```

## 5. 配置CRL（证书吊销列表）

### 5.1 生成初始CRL

```
bash复制代码openssl ca -config /etc/pki/tls/openssl.cnf \
      -gencrl -out /root/ca/crl/ca.crl.pem
chmod 444 /root/ca/crl/ca.crl.pem
```

### 5.2 设置CRL定期更新

编辑`/etc/crontab`，添加以下行：

```
cron


复制代码
0 0 * * * root openssl ca -config /etc/pki/tls/openssl.cnf -gencrl -out /root/ca/crl/ca.crl.pem
```

这将每日更新CRL。

## 6. 配置SCEP（简单证书注册协议）

### 6.1 安装SCEP服务器

SCEP服务器可以使用`sscep`或`OpenXPKI`。这里我们使用`sscep`。

```
bash复制代码sudo yum install -y git gcc make
git clone https://github.com/certnanny/sscep.git
cd sscep
make
make install
```

### 6.2 配置SCEP服务器

创建SCEP配置文件`/etc/sscep/sscep.conf`：

```
ini复制代码[sscep]
CA_CERT = /root/ca/certs/ca.cert.pem
RA_CERT = /root/ca/certs/ra.cert.pem
RA_KEY = /root/ca/private/ra.key.pem
```

### 6.3 启动SCEP服务

由于`sscep`没有自带的服务器实现，需要使用第三方的SCEP服务器，如`pki`或`OpenXPKI`。为了简化，我们可以使用`nginx`或`Apache`来托管SCEP服务。

**示例：使用Apache托管SCEP**

编辑`/etc/httpd/conf.d/scep.conf`，添加以下内容：

```
apache复制代码Alias /scep /var/www/scep
<Directory /var/www/scep>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
```

创建`/var/www/scep`目录，并将必要的SCEP文件放入其中。

```
bash复制代码mkdir /var/www/scep
cp /root/ca/certs/ca.cert.pem /var/www/scep/ca.cert
```

启动Apache服务：

```
bash复制代码sudo systemctl start httpd
sudo systemctl enable httpd
```

## 7. 测试与验证

### 7.1 测试OCSP

```
bash复制代码openssl ocsp -CAfile /root/ca/certs/ca.cert.pem \
      -issuer /root/ca/certs/ca.cert.pem \
      -cert /root/ca/certs/ra.cert.pem \
      -url http://localhost:2560 \
      -resp_text
```

### 7.2 测试SCEP

使用`sscep`客户端测试SCEP服务：

```
bash


复制代码
sscep getca -u http://localhost/scep -c /root/ca/certs/ca.cert.pem
```

## 结论

通过以上步骤，我们在CentOS 7上成功部署了PKI环境，包含CA、RA、OCSP、CRL和SCEP组件。这为在实际环境中管理和部署证书提供了基础，增强了系统的安全性。

## 注意事项

- 在生产环境中，务必保护好私钥，设置适当的权限和访问控制。
- 定期备份CA目录，以防数据丢失。
- 根据需要调整配置文件，确保符合组织的安全策略。

# 问题解决

怎么解决[root@localhost CA]# openssl ca -config /etc/pki/tls/openssl.cnf       -extensions v3_intermediate_ca -days 3650 -notext -md sha256       -in /root/ca/csr/ra.csr.pem       -out /root/ca/certs/ra.cert.pem Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: Error Loading extension section v3_intermediate_ca 139936031401872:error:02001002:system library:fopen:No such file or directory:bss_file.c:175:fopen('/etc/pki/CA/index.txt.attr','rb') 139936031401872:error:2006D080:BIO routines:BIO_new_file:no such file:bss_file.c:182: 139936031401872:error:0E078072:configuration file routines:DEF_LOAD:no such file:conf_def.c:195: 139936031401872:error:0E06D06C:configuration file routines:NCONF_get_string:no value:conf_lib.c:324:group=CA_default name=email_in_dn [root@localhost CA]# 



###### ChatGPT 说：

ChatGPT

根据你提供的错误信息，问题主要与 OpenSSL 配置文件和相关文件缺失有关。以下是一些步骤来解决这个问题：

1. **检查配置文件**： 确保 `/etc/pki/tls/openssl.cnf` 文件存在，并且内容正确。特别是要确保 `v3_intermediate_ca` 扩展部分被正确定义。

2. **创建缺失的文件**： 错误信息显示缺少 `index.txt.attr` 文件。可以通过以下步骤创建它：

   ```
   bash复制代码touch /etc/pki/CA/index.txt
   touch /etc/pki/CA/index.txt.attr
   ```

   `index.txt` 文件用于记录签名的证书，`index.txt.attr` 文件通常可以留空。

3. **检查 `email_in_dn`**： 根据错误信息，配置文件中 `CA_default` 部分的 `email_in_dn` 值未定义。确保在 `/etc/pki/tls/openssl.cnf` 的 `CA_default` 部分添加以下行：

   ```
   plaintext
   
   
   复制代码
   email_in_dn = no
   ```

4. **确认私钥路径**： 确保 `/etc/pki/CA/private/cakey.pem` 文件存在且可访问。

5. **重新执行命令**： 在确保上述问题解决后，尝试再次执行你的 `openssl ca` 命令。

如果在执行这些步骤后仍然遇到问题，请检查相关文件的权限和所有者，确保 OpenSSL 有权访问它们。





4o

##### 您说：

怎么解决[root@localhost CA]# openssl ca -config /etc/pki/tls/openssl.cnf \ >       -extensions v3_intermediate_ca -days 3650 -notext -md sha256 \ >       -in /root/ca/csr/ra.csr.pem \ >       -out /root/ca/certs/ra.cert.pem Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: Error Loading extension section v3_intermediate_ca 139687141529488:error:0E06D06C:configuration file routines:NCONF_get_string:no value:conf_lib.c:324:group= name=unique_subject [root@localhost CA]# 



###### ChatGPT 说：

ChatGPT

这个错误表明 OpenSSL 在加载配置文件中的 `v3_intermediate_ca` 扩展部分时遇到了问题。以下是一些解决步骤：

1. **检查扩展部分定义**： 打开 `/etc/pki/tls/openssl.cnf` 文件，确保 `v3_intermediate_ca` 部分存在并正确定义。它应该类似于：

   ```
   plaintext复制代码[ v3_intermediate_ca ]
   subjectKeyIdentifier = hash
   authorityKeyIdentifier = keyid,issuer
   basicConstraints = CA:true, pathlen:0
   ```

2. **添加 `unique_subject` 设置**： 根据错误提示，`unique_subject` 可能未定义。在 `[ CA_default ]` 部分添加以下行：

   ```
   plaintext
   
   
   复制代码
   unique_subject = no
   ```

3. **检查文件权限**： 确保 OpenSSL 有权限访问 `/etc/pki/CA/private/cakey.pem` 文件。可以使用以下命令查看权限：

   ```
   bash
   
   
   复制代码
   ls -l /etc/pki/CA/private/cakey.pem
   ```

4. **重启 OpenSSL 服务（如果适用）**： 如果 OpenSSL 在服务中运行，重启服务以应用更改。

5. **再次执行命令**： 在确保上述问题解决后，尝试再次执行 `openssl ca` 命令。

如果问题依然存在，请仔细检查配置文件的其他部分，确保没有其他未定义的项。





4o

##### 您说：

怎么解决？[root@localhost csr]# cd /root/ca/certs/ [root@localhost certs]# openssl ca -config /etc/pki/tls/openssl.cnf \ >       -extensions ocsp -days 3650 -notext -md sha256 \ >       -in /root/ca/csr/ocsp.csr.pem \ >       -out /root/ca/certs/ocsp.cert.pem Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: Error Loading extension section ocsp [root@localhost certs]# 



###### ChatGPT 说：

ChatGPT

这个错误表明 OpenSSL 在加载配置文件中的 `ocsp` 扩展部分时遇到了问题。请按以下步骤解决：

1. **检查扩展部分定义**： 打开 `/etc/pki/tls/openssl.cnf` 文件，确保 `ocsp` 扩展部分存在并正确定义。应类似于以下内容：

   ```
   plaintext复制代码[ ocsp ]
   basicConstraints = CA:FALSE
   keyUsage = critical, digitalSignature
   extendedKeyUsage = critical, OCSPSigning
   ```

2. **确认没有拼写错误**： 检查配置文件，确保 `ocsp` 扩展的名称没有拼写错误。

3. **验证其他相关设置**： 确保在 `[ CA_default ]` 部分中没有其他未定义或错误的配置项，例如 `unique_subject`。

4. **检查文件权限**： 确保 OpenSSL 有权限访问 `/etc/pki/CA/private/cakey.pem` 文件。使用以下命令查看权限：

   ```
   bash
   
   
   复制代码
   ls -l /etc/pki/CA/private/cakey.pem
   ```

5. **重新执行命令**： 确保以上问题得到解决后，再次执行 `openssl ca` 命令。

如果问题依旧，请仔细检查配置文件的其他部分，确保所有相关项都已正确定义。





4o

##### 您说：

怎么解决[root@localhost crl]# openssl ca -config /etc/pki/tls/openssl.cnf \ >       -gencrl -out /root/ca/crl/ca.crl.pem Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: /etc/pki/CA/crlnumber: No such file or directory error while loading CRL number 140286205667216:error:02001002:system library:fopen:No such file or directory:bss_file.c:402:fopen('/etc/pki/CA/crlnumber','r') 140286205667216:error:20074002:BIO routines:FILE_CTRL:system lib:bss_file.c:404: [root@localhost crl]# 



###### ChatGPT 说：

ChatGPT

这个错误表明 OpenSSL 无法找到 `crlnumber` 文件。这个文件用于跟踪证书撤销列表（CRL）的版本号。以下是解决步骤：

1. **创建缺失的文件**： 你可以通过以下命令创建 `crlnumber` 文件：

   ```
   bash
   
   
   复制代码
   echo 1000 > /etc/pki/CA/crlnumber
   ```

   这个数字可以是任何正整数，表示当前的 CRL 版本。

2. **检查其他相关文件**： 确保 `/etc/pki/CA` 目录下有其他必要的文件，如 `index.txt` 和 `index.txt.attr`。如果缺失，可以通过以下命令创建它们：

   ```
   bash复制代码touch /etc/pki/CA/index.txt
   touch /etc/pki/CA/index.txt.attr
   ```

3. **确认文件权限**： 确保 OpenSSL 有权限访问 `/etc/pki/CA/private/cakey.pem` 文件及相关文件。

4. **重新执行命令**： 在确保上述问题得到解决后，尝试再次执行生成 CRL 的命令：

   ```
   bash
   
   
   复制代码
   openssl ca -config /etc/pki/tls/openssl.cnf -gencrl -out /root/ca/crl/ca.crl.pem
   ```

如果问题依然存在，请检查其他可能的配置文件问题。





4o

##### 您说：

怎么解决[root@localhost sscep]# ls AIX      bootstrap.sh  CMakeLists.txt  COPYING  Imakefile  Makefile.am  README.md  Solaris  sscep.conf  VERSION AUTHORS  ChangeLog     configure.ac    doc      INSTALL    mkrequest    scripts    src      TODO [root@localhost sscep]# ./bootstrap.sh  ./bootstrap.sh: line 2: libtoolize: command not found ./bootstrap.sh: line 3: aclocal: command not found ./bootstrap.sh: line 4: autoheader: command not found ./bootstrap.sh: line 5: automake: command not found ./bootstrap.sh: line 6: autoconf: command not found [root@localhost sscep]# cat bootstrap.sh  #!/bin/sh libtoolize aclocal autoheader automake --add-missing autoconf [root@localhost sscep]# 



###### ChatGPT 说：

ChatGPT

你遇到的错误表明缺少一些必要的开发工具，导致 `bootstrap.sh` 脚本无法运行。你需要安装 `libtool`, `automake`, `autoconf`, 和其他相关工具。可以按照以下步骤进行安装：

1. **使用包管理器安装所需工具**： 如果你使用的是基于 RPM 的 Linux 发行版（如 CentOS 或 Fedora），可以运行以下命令：

   ```
   bash
   
   
   复制代码
   yum install libtool automake autoconf
   ```

   如果你使用的是基于 Debian 的系统（如 Ubuntu），可以使用以下命令：

   ```
   bash
   
   
   复制代码
   apt-get install libtool automake autoconf
   ```

2. **确认工具已正确安装**： 安装完成后，可以通过以下命令检查工具是否安装成功：

   ```
   bash复制代码which libtoolize
   which aclocal
   which autoheader
   which automake
   which autoconf
   ```

   每个命令应该返回对应工具的路径。

3. **重新运行 `bootstrap.sh`**： 安装完成后，返回到 `sscep` 目录并再次运行 `./bootstrap.sh`：

   ```
   bash
   
   
   复制代码
   ./bootstrap.sh
   ```

如果仍然遇到问题，请检查安装过程中的错误信息，并确保你有足够的权限执行这些操作。





4o

##### 您说：

怎么解决checking for openssl... no configure: error: Package requirements (openssl >= 1.1.0) were not met: No package 'openssl' found Consider adjusting the PKG_CONFIG_PATH environment variable if you installed software in a non-standard prefix. Alternatively, you may set the environment variables openssl_CFLAGS and openssl_LIBS to avoid the need to call pkg-config. See the pkg-config man page for more details.



###### ChatGPT 说：

ChatGPT

这个错误表明系统找不到 OpenSSL 开发库。为了解决这个问题，你需要安装 OpenSSL 及其开发包。可以按照以下步骤进行操作：

1. **安装 OpenSSL 和开发包**：

   - 对于 CentOS 或其他基于 RPM 的系统，使用以下命令：

     ```
     bash
     
     
     复制代码
     yum install openssl openssl-devel
     ```

   - 对于基于 Debian 的系统（如 Ubuntu），使用以下命令：

     ```
     bash
     
     
     复制代码
     apt-get install libssl-dev
     ```

2. **确认安装成功**： 安装完成后，你可以运行以下命令来确认 OpenSSL 是否已安装：

   ```
   bash
   
   
   复制代码
   openssl version
   ```

3. **重新运行 `configure` 脚本**： 安装完成后，返回到你的项目目录，重新运行 `./configure` 脚本：

   ```
   bash
   
   
   复制代码
   ./configure
   ```

4. **设置环境变量（如果需要）**： 如果你在非标准路径下安装了 OpenSSL，可能需要设置 `PKG_CONFIG_PATH` 环境变量。例如：

   ```
   bash
   
   
   复制代码
   export PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:$PKG_CONFIG_PATH
   ```

   或者设置 `openssl_CFLAGS` 和 `openssl_LIBS`：

   ```
   bash复制代码export openssl_CFLAGS="-I/usr/local/include"
   export openssl_LIBS="-L/usr/local/lib -lssl -lcrypto"
   ```

5. **继续编译**： 如果 `configure` 成功完成，你可以继续执行 `make` 和 `make install` 来编译和安装你的软件。

如果还有其他问题，请提供更多错误信息以便进一步帮助。





4o

##### 您说：

怎么解决checking for openssl... no configure: error: Package requirements (openssl >= 1.1.0) were not met: Requested 'openssl >= 1.1.0' but version of OpenSSL is 1.0.2k Consider adjusting the PKG_CONFIG_PATH environment variable if you installed software in a non-standard prefix. Alternatively, you may set the environment variables openssl_CFLAGS and openssl_LIBS to avoid the need to call pkg-config. See the pkg-config man page for more details. [root@localhost sscep]# 



###### ChatGPT 说：

ChatGPT

这个错误表明你系统上的 OpenSSL 版本低于要求的 1.1.0 版本。你可以通过以下步骤解决这个问题：

1. **卸载旧版本 OpenSSL**（可选）： 如果需要，你可以卸载旧版本，但在某些系统上可能会影响其他依赖 OpenSSL 的软件：

   ```
   bash
   
   
   复制代码
   yum remove openssl openssl-devel  # 对于 CentOS
   ```

2. **安装最新版本的 OpenSSL**： 你可以从源代码编译最新版本的 OpenSSL，或使用软件包管理器安装较新版本。

   **从源代码编译**：

   - 首先，下载 OpenSSL 的最新版本：

     ```
     bash复制代码wget https://www.openssl.org/source/openssl-1.1.1t.tar.gz
     tar -xzf openssl-1.1.1t.tar.gz
     cd openssl-1.1.1t
     ```

   - 然后编译和安装：

     ```
     bash复制代码./config
     make
     make test
     make install
     ```

   - 更新共享库缓存：

     ```
     bash
     
     
     复制代码
     ldconfig
     ```

3. **确认安装成功**： 安装完成后，检查 OpenSSL 版本：

   ```
   bash
   
   
   复制代码
   openssl version
   ```

   确保显示的版本为 1.1.0 或更高版本。

4. **重新运行 `configure`**： 返回到你的项目目录，重新运行 `./configure` 脚本：

   ```
   bash
   
   
   复制代码
   ./configure
   ```

5. **设置环境变量（如需）**： 如果你在非标准路径下安装了 OpenSSL，可能需要设置环境变量。例如：

   ```
   bash
   
   
   复制代码
   export PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:$PKG_CONFIG_PATH
   ```

6. **继续编译**： 如果 `configure` 成功完成，你可以继续执行 `make` 和 `make install` 来编译和安装你的软件。

如果你不想编译 OpenSSL，某些 Linux 发行版的官方仓库也可能有较新版本的 OpenSSL，可以考虑查找并安装这些版本。





4o

##### 您说：

这是成功了嘛[root@localhost sscep]# make install make[1]: Entering directory `/root/sscep' ` /usr/bin/mkdir -p '/usr/local/bin'  /bin/sh ./libtool   --mode=install /usr/bin/install -c sscep '/usr/local/bin' libtool: install: /usr/bin/install -c sscep /usr/local/bin/sscep /usr/bin/mkdir -p '/usr/local/share/doc/sscep' /usr/bin/install -c -m 644 COPYING README.md '/usr/local/share/doc/sscep' make[1]: Leaving directory `/root/sscep' `[root@localhost sscep]# echo $? 0 [root@localhost sscep]# 



###### ChatGPT 说：

ChatGPT

是的，你的 `make install` 命令成功执行了！输出信息显示文件已被安装到 `/usr/local/bin` 目录，并且返回的退出状态码为 `0`，这表示没有错误。

以下是一些后续步骤：

1. **验证安装**： 你可以通过运行以下命令来验证 `sscep` 是否已成功安装：

   ```
   bash
   
   
   复制代码
   sscep --version
   ```

   或者只是运行 `sscep`，查看是否能正确显示帮助信息。

2. **检查文件位置**： 确认 `sscep` 可执行文件是否在 `/usr/local/bin` 中：

   ```
   bash
   
   
   复制代码
   ls /usr/local/bin/sscep
   ```

3. **更新环境变量（如需）**： 如果你的 `$PATH` 环境变量未包含 `/usr/local/bin`，你可能需要将其添加到你的 `$PATH` 中，以便在任何位置都能运行 `sscep`。

如果一切正常，你就可以开始使用 `sscep` 了！如果还有其他问题，请随时告诉我。





4o

##### 您说：

怎么解决[root@localhost certs]# openssl ocsp -CAfile /root/ca/certs/ca.cert.pem \ >       -issuer /root/ca/certs/ca.cert.pem \ >       -cert /root/ca/certs/ra.cert.pem \ >       -url http://localhost:2560 \ >       -resp_text Error connecting BIO Error querying OCSP responder 140128588035984:error:0200206F:system library:connect:Connection refused:bss_conn.c:246:host=localhost:2560 140128588035984:error:20073067:BIO routines:CONN_STATE:connect error:bss_conn.c:249: [root@localhost certs]# 



###### ChatGPT 说：

ChatGPT

这个错误表明 OpenSSL 尝试连接到 OCSP 响应者时遇到了问题，具体是因为连接被拒绝。以下是解决步骤：

1. **确认 OCSP 响应者正在运行**： 确保你的 OCSP 响应者服务正在运行，并监听在端口 2560。你可以使用以下命令检查：

   ```
   bash
   
   
   复制代码
   netstat -tuln | grep 2560
   ```

   如果没有输出，说明服务未启动。

2. **启动 OCSP 响应者**： 如果 OCSP 响应者未运行，你需要启动它。根据你使用的 OCSP 服务器软件，启动命令可能会有所不同。确保你已正确配置 OCSP 响应者。

3. **检查防火墙设置**： 确保防火墙未阻止端口 2560 的访问。你可以使用以下命令检查并允许访问：

   ```
   bash复制代码firewall-cmd --list-all   # 查看当前规则
   firewall-cmd --add-port=2560/tcp --permanent
   firewall-cmd --reload
   ```

4. **确认 URL 正确**： 检查 OCSP 响应者的 URL 是否正确，可以尝试在浏览器中访问 `http://localhost:2560`，看看是否能正常连接。

5. **再次运行命令**： 一旦确认 OCSP 响应者正在运行并且没有防火墙阻止访问，再次运行你的 OpenSSL OCSP 命令：

   ```
   bash复制代码openssl ocsp -CAfile /root/ca/certs/ca.cert.pem \
         -issuer /root/ca/certs/ca.cert.pem \
         -cert /root/ca/certs/ra.cert.pem \
         -url http://localhost:2560 \
         -resp_text
   ```

如果问题依然存在，请提供更多的错误信息，以便进一步帮助你解决问题。





4o

##### 您说：

怎么解决[root@localhost ~]# sscep getca -u http://localhost/scep -c /root/ca/certs/ca.cert.pem sscep: error while sending message [root@localhost ~]# 



###### ChatGPT 说：

ChatGPT

这个错误表明 `sscep` 在发送请求时遇到了问题，可能是由于连接问题、URL 错误或 SCEP 服务未启动。以下是一些解决步骤：

1. **确认 SCEP 服务正在运行**： 确保你的 SCEP 服务器正在运行并能够响应请求。你可以在浏览器中尝试访问 `http://localhost/scep`，看是否能正确访问。

2. **检查 URL 是否正确**： 确保你使用的 URL 是正确的，并且 SCEP 服务的路径配置正确。如果 SCEP 服务监听在不同的端口或路径，请相应地更新 URL。

3. **检查防火墙设置**： 确保防火墙未阻止访问 SCEP 服务的端口。可以使用以下命令检查并允许访问：

   ```
   bash复制代码firewall-cmd --list-all   # 查看当前规则
   firewall-cmd --add-port=80/tcp --permanent  # 如果使用 HTTP
   firewall-cmd --reload
   ```

4. **查看 SCEP 服务日志**： 检查 SCEP 服务的日志文件，查看是否有关于请求的错误信息。这有助于确定问题的根本原因。

5. **确认 SSL/TLS 设置（如需）**： 如果你的 SCEP 服务使用 HTTPS，确保 SSL/TLS 配置正确，并且你在 `sscep` 命令中使用了正确的证书。

6. **再次运行命令**： 在确保以上问题解决后，再次运行 `sscep getca` 命令：

   ```
   bash
   
   
   复制代码
   sscep getca -u http://localhost/scep -c /root/ca/certs/ca.cert.pem
   ```

如果问题依然存在，请提供更多的错误信息以便进一步帮助。