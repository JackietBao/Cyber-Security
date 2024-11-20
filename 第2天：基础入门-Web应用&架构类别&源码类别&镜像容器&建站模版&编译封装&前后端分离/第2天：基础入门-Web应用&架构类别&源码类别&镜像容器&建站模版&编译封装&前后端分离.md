基础入门-Web应用&架构类别&源码类别&镜像容器&建站模版&编译封装&前后端分离

![image-20241119164825512](assets/%E7%AC%AC2%E5%A4%A9%EF%BC%9A%E5%9F%BA%E7%A1%80%E5%85%A5%E9%97%A8-Web%E5%BA%94%E7%94%A8&%E6%9E%B6%E6%9E%84%E7%B1%BB%E5%88%AB&%E6%BA%90%E7%A0%81%E7%B1%BB%E5%88%AB&%E9%95%9C%E5%83%8F%E5%AE%B9%E5%99%A8&%E5%BB%BA%E7%AB%99%E6%A8%A1%E7%89%88&%E7%BC%96%E8%AF%91%E5%B0%81%E8%A3%85&%E5%89%8D%E5%90%8E%E7%AB%AF%E5%88%86%E7%A6%BB/image-20241119164825512.png)



```
#知识点：
1、基础入门-Web应用-域名上的技术要点
2、基础入门-Web应用-源码上的技术要点
3、基础入门-Web应用-数据上的技术要点
4、基础入门-Web应用-解析上的技术要点
-----------------------------------------------------
1、基础入门-Web应用-搭建架构上的技术要点
2、基础入门-Web应用-源码类别上的技术要点

#章节点：(待补充)
Web架构，App架构，小程序架构，前后端分离，容器技术，云产品服务，
数据加解密算法，数据包抓取，数据包解析，正反向网络通讯，内外网通讯，
防火墙出入站，Windows&Linux渗透命令，WAF产品，负载均衡，加壳保护等

#具体点：(待补充)
架构：WEB,APP,小程序,前后端,容器化等
服务：OSS存储,CDN加速,云数据库,负载均衡等
网络：不回显,反向代理,防火墙出入站,内外网,正反向连接等
算法：MD5,Base64,AES,DES,Salt,自定义,代码加密算法等
命令：Windows&Linux,文件下载,网络查看,反弹权限,用户等
防护：WAF防护,蜜罐系统,CDN加速,权限设置,加壳加密反调试等
```





演示案例：

​                ➢ 架构类别-模版&分离&集成&容器&镜像

​                ➢ 源码类别-MVC框架&编译封装&分离打包



```
#课程需明白：
1、有那几种Web域名上差异
2、有哪几种源码语言框架差异
3、网站数据存储有那几个方式
4、URL访问对应正确和错误原因
由此我们需要结合安全测试思考：
1、域名上架构了解后的思路意义
2、源码上架构了解后的思路意义
3、数据上架构了解后的思路意义
4、解析上架构了解后的思路意义

#域名差异-主站&分站&端口站&子站
1、主站
www.xiaodi8.com
2、分站
blog.xiaodi8.com
3、端口站
www.xiaodi8.com:88
4、目录站
www.xiaodi8.com/bbs/
5、子站
123.blog.xiaodi8.com

#源码差异-结构&语言&框架&闭源&加密
1、源码目录结构对应
后台目录，文件目录，逻辑目录，前端目录，数据目录，配置文件等

2、源码开发语言类型
ASP，ASPX，PHP，Java，Python，Go，Javascript等

3、语言开发框架组件
PHP：Thinkphp Laravel YII CodeIgniter CakePHP Zend等
JAVA：Spring MyBatis Hibernate Struts2 Springboot等
Python：Django Flask Bottle Turbobars Tornado Web2py等
Javascript：Vue.js Node.js Bootstrap JQuery Angular等

4、开源闭源加密类型
开源-如Zblog
闭源-如内部开发
加密-如通达OA

#数据差异-本地数据&分离数据&云数据库
1、数据库类型：
Access、MYSQL、SqlServer、Oracle、
Redis、DB2、Postgresql、MongoDB等
2、本地数据库：本地服务器搭建
3、分离数据库：另外的服务器搭建
4、云数据库：RDS等

#平台差异-中间件类型&系统类型&容器类型
1、系统：Windows、Linux、MacOS等
2、容器：Docker、K8s、Vmware、VirtualBox等
3、中间件：Apache、Nginx、IIS、lighttpd、Tomcat、Jboos、Weblogic、Websphere、Jetty等

#解析差异-URL路由&绝对相对路径&格式权限
1、URL路由：URL访问对应文件，MVC模型等
2、相对绝对：相对当前目录，完整的目录路径
3、格式权限：后门解析格式，代码正常执行，脚本执行权限等
```







```
#Web架构展示：
1、套用模版型
csdn / cnblog / github / 建站系统等
安全测试思路上的不同：
一般以模版套用，基本模版无漏洞，大部分都采用测试用户管理权限为主

2、前后端分离
例子：https://www.rxthink.cn/
思路：https://mp.weixin.qq.com/s/HtLU_EBXWcbq-lt10oPYwA
安全测试思路上的不同：前端以JS（Vue,NodeJS等）安全问题，主要以API接口测试，前端漏洞（如XSS）为主，后端隐蔽难度加大。

3、集成软件包
宝塔，Phpstudy，xamp等
安全测试思路上的不同：主要是防护体系，权限差异为主

4、自主环境镜像
云镜像打包，自行一个个搭建
安全测试思路上的不同：主要是防护体系，权限差异为主

5、容器拉取镜像
Docker
安全测试思路上的不同：虚拟化技术，在后期测试要进程逃逸提权

6、纯静态页面
纯HTML+CSS+JS的设计
安全测试思路上的不同：无后期讲到的Web漏洞
找线索：找资产，域名，客户端等

#Web源码形式：
旨在了解源码差异，后期代码审计和测试中对源码真实性的判断
1、单纯简易源码
2、MVC框架源码
3、编译调用源码
如：NET-DLL封装 Java-Jar打包
4、前后端分离源码
https://segmentfault.com/a/1190000045026063
5、加密型源码
```







涉及资源：[资源下载地址](https://docs.qq.com/doc/DQ3Z6RkNpaUtMcEFr)