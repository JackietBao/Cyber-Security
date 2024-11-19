基础入门-Web应用&架构搭建&域名源码&站库分离&MVC模型&解析受限&对应路径

![image-20241119164754091](assets/%E7%AC%AC1%E5%A4%A9%EF%BC%9A%E5%9F%BA%E7%A1%80%E5%85%A5%E9%97%A8-Web%E5%BA%94%E7%94%A8&%E6%9E%B6%E6%9E%84%E6%90%AD%E5%BB%BA&%E5%9F%9F%E5%90%8D%E6%BA%90%E7%A0%81&%E7%AB%99%E5%BA%93%E5%88%86%E7%A6%BB&MVC%E6%A8%A1%E5%9E%8B&%E8%A7%A3%E6%9E%90%E5%8F%97%E9%99%90&%E5%AF%B9%E5%BA%94%E8%B7%AF%E5%BE%84/image-20241119164754091.png)

```
#知识点：
1、基础入门-Web应用-域名上的技术要点
2、基础入门-Web应用-源码上的技术要点
3、基础入门-Web应用-数据上的技术要点
4、基础入门-Web应用-解析上的技术要点

#章节点：(待补充)
Web架构，App架构，小程序架构，前后端分离，容器技术，云产品服务，
数据加解密算法，数据包抓取，数据包解析，正反向网络通讯，内外网通讯，
防火墙出入站，Windows&Linux渗透命令，WAF产品，负载均衡，加壳保护等

#具体点：(待补充)
架构：WEB,APP,小程序,前后端,容器化等
服务：OSS存储,CDN加速,云数据库,负载均衡等
网络：不回显,反向代理,防火墙出入站,内外网,正反向连接等
算法：MD5,Base64,AES,DES,Salt,自定义,代码加密算法等
命令：Windows&Linux,文件下载,网络查看,反弹权限,用户等
防护：WAF防护,蜜罐系统,CDN加速,权限设置,加壳加密反调试等
```

OSS（Object Storage Service）

CDN（Content Delivery Network）

MD5（Message Digest Algorithm 5）

AES（Advanced Encryption Standard）

DES（Data Encryption Standard）

WAF（Web Application Firewall）

| 算法   | 类别     | 可逆性               | 主要用途                       | 安全性                 |
| ------ | -------- | -------------------- | ------------------------------ | ---------------------- |
| MD5    | 哈希算法 | 不可逆               | 数据完整性校验、简单密码存储   | 安全性低，易受碰撞攻击 |
| Base64 | 编码方案 | 可逆                 | 数据转换为可传输的文本格式     | 无安全性，仅用于编码   |
| AES    | 加密算法 | 可逆（对称密钥）     | 数据加密、文件保护             | 安全性高，推荐使用     |
| DES    | 加密算法 | 可逆（对称密钥）     | 数据加密（已淘汰）             | 安全性低，已被破解     |
| Salt   | 安全机制 | 不适用（与哈希结合） | 提高哈希安全性（防彩虹表攻击） | 增强密码存储安全       |



演示案例：

​                ➢ 域名差异-主站&分站&端口站&子站&目录站

​                ➢ 源码差异-结构&语言&框架&闭源&加密

​                ➢ 数据差异-本地数据&分离数据&云数据库

​                ➢ 解析差异-路由访问&绝对相对路径&格式权限



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







涉及资源：[涉及录像课件资源软件包资料等下载地址](https://docs.qq.com/doc/DQ3Z6RkNpaUtMcEFr)