在CentOS 7上部署并测试PKI (Public Key Infrastructure) 相关的组件（RA、CA、OCSP、CRL、SCEP），有助于理解其工作原理和各个组件之间的关系。以下是一个实战案例，详细描述操作步骤及其背后的原理，旨在帮助你理解这些概念：

### 案例目标：

1. 建立一个简单的PKI架构，包括一个根证书颁发机构（Root CA）、注册机构（RA）、在线证书状态协议（OCSP）服务器、证书吊销列表（CRL）、简单证书注册协议（SCEP）服务器。
2. 使用自签名证书和分发证书。
3. 测试证书颁发、吊销、验证等操作。

------

### 1. **安装必要的软件包**

首先，确保CentOS 7上安装了以下工具：

```
bash


复制代码
yum install -y openssl httpd mod_ssl
```

这些工具将用于管理证书、启动Web服务器，以及创建PKI架构所需的服务。

------

### 2. **配置并创建CA（证书颁发机构）**

CA 是整个PKI架构的核心，它负责签发、验证、吊销证书。

#### a) 生成CA的私钥：

```
bash


复制代码
openssl genpkey -algorithm RSA -out /etc/pki/CA/private/ca.key -aes256
```

#### b) 创建自签名根证书：

```
bash复制代码openssl req -x509 -new -key /etc/pki/CA/private/ca.key -days 3650 -out /etc/pki/CA/certs/ca.crt \
-subj "/C=CN/ST=Shanghai/L=Shanghai/O=MyCompany/OU=IT Department/CN=MyRootCA"
```

此步骤生成了CA的私钥和根证书，用于之后签发其他证书。

------

### 3. **创建RA（注册机构）**

RA负责处理证书申请，验证申请者的身份，并将申请提交给CA进行签发。

#### a) 创建RA私钥和证书签名请求（CSR）：

```
bash复制代码openssl genpkey -algorithm RSA -out /etc/pki/RA/private/ra.key -aes256
openssl req -new -key /etc/pki/RA/private/ra.key -out /etc/pki/RA/ra.csr \
-subj "/C=CN/ST=Shanghai/L=Shanghai/O=MyCompany/OU=RA Department/CN=MyRA"
```

#### b) 使用CA签发RA证书：

```
bash


复制代码
openssl ca -in /etc/pki/RA/ra.csr -out /etc/pki/RA/certs/ra.crt -days 3650 -extensions v3_ca
```

------

### 4. **配置并启用OCSP**

OCSP用于实时检查证书是否被吊销。

#### a) 生成OCSP服务器的私钥和证书：

```
bash复制代码openssl genpkey -algorithm RSA -out /etc/pki/OCSP/private/ocsp.key -aes256
openssl req -new -key /etc/pki/OCSP/private/ocsp.key -out /etc/pki/OCSP/ocsp.csr \
-subj "/C=CN/ST=Shanghai/L=Shanghai/O=MyCompany/OU=OCSP Department/CN=OCSPResponder"
```

#### b) 使用CA签发OCSP响应者的证书：

```
bash


复制代码
openssl ca -in /etc/pki/OCSP/ocsp.csr -out /etc/pki/OCSP/ocsp.crt -extensions v3_ocsp
```

#### c) 启动OCSP响应服务器：

```
bash


复制代码
openssl ocsp -port 2560 -index /etc/pki/CA/index.txt -CA /etc/pki/CA/certs/ca.crt -rkey /etc/pki/OCSP/private/ocsp.key -rsigner /etc/pki/OCSP/ocsp.crt
```

------

### 5. **创建CRL（证书吊销列表）**

CRL记录了已吊销的证书列表。

#### a) 生成CRL：

```
bash


复制代码
openssl ca -gencrl -out /etc/pki/CA/crl/ca.crl
```

#### b) 配置Apache服务器以发布CRL：

```
bash复制代码echo "Alias /crl /etc/pki/CA/crl" >> /etc/httpd/conf/httpd.conf
systemctl restart httpd
```

通过HTTP服务器将CRL公开供验证方下载。

------

### 6. **部署SCEP服务器**

SCEP允许设备通过自动化方式向CA请求证书。

#### a) 安装并配置 `dogtag-pki` 或类似工具：

```
bash


复制代码
yum install -y pki-scep-server
```

#### b) 启动并配置SCEP服务：

```
bash复制代码pki-server instance-create
pki-server scep-enable
```

详细配置步骤可以参考 `dogtag-pki` 的官方文档，SCEP用于批量处理设备的证书请求。

------

### 7. **测试**

#### a) 创建客户端证书：

```
bash复制代码openssl genpkey -algorithm RSA -out client.key
openssl req -new -key client.key -out client.csr -subj "/C=CN/ST=Shanghai/L=Shanghai/O=MyCompany/OU=Client Department/CN=Client1"
```

#### b) 由CA签发客户端证书：

```
bash


复制代码
openssl ca -in client.csr -out client.crt
```

#### c) 吊销证书并生成新CRL：

```
bash复制代码openssl ca -revoke /etc/pki/CA/newcerts/$(cat /etc/pki/CA/index.txt | grep "Client1" | awk '{print $3}').pem
openssl ca -gencrl -out /etc/pki/CA/crl/ca.crl
```

#### d) 测试OCSP：

```
bash


复制代码
openssl ocsp -CAfile /etc/pki/CA/certs/ca.crt -issuer /etc/pki/CA/certs/ca.crt -cert client.crt -url http://localhost:2560 -resp_text
```

------

### 8. **各组件之间的关系**

- **CA（证书颁发机构）**：负责颁发证书，是PKI系统的核心。RA 作为代理，为CA处理证书请求。
- **RA（注册机构）**：在处理证书申请时，确保申请者的身份真实，防止假冒伪造。
- **OCSP**：实时查询证书状态是否被吊销，避免频繁下载CRL。
- **CRL**：一个包含已吊销证书的列表，通常与OCSP配合使用。
- **SCEP**：主要用于自动化设备向CA请求证书的流程，通常用于大规模设备或网络中。

### 结论

通过以上步骤，你可以搭建一个简化的PKI环境，并且理解各个组件之间的关系。这个案例能够帮助你在实际生产环境中更好地应用PKI的相关技术。你可以根据需要进一步扩展这些功能，比如加入更多安全策略或优化性能配置。





# 报错解决

> [root@localhost RA]# openssl ca -in /etc/pki/RA/ra.csr -out /etc/pki/RA/certs/ra.crt -days 3650 -extensions v3_ca Using configuration from /etc/pki/tls/openssl.cnf Error opening CA private key /etc/pki/CA/private/cakey.pem 140476405385120:error:02001002:system library:fopen:No such file or directory:bss_file.c:402:fopen('/etc/pki/CA/private/cakey.pem','r') 140476405385120:error:20074002:BIO routines:FILE_CTRL:system lib:bss_file.c:404: unable to load CA private key [root@localhost RA]# 

这个错误的提示是 OpenSSL 无法找到或读取 CA 的私钥文件 `/etc/pki/CA/private/cakey.pem`。它表示 OpenSSL 试图使用此文件进行证书签署时，无法找到指定的私钥文件。解决这个问题有以下几个步骤：

### 1. **检查私钥文件是否存在**：

运行以下命令，检查 `/etc/pki/CA/private/cakey.pem` 文件是否存在：

```
bash


复制代码
ls -l /etc/pki/CA/private/cakey.pem
```

- 如果该文件不存在，可能是没有生成过 CA 的私钥。你需要生成 CA 的私钥。

### 2. **生成 CA 私钥和证书**：

如果你还没有生成过 CA 的私钥和自签名证书，按照以下步骤生成：

- **生成私钥**：

  ```
  bash
  
  
  复制代码
  openssl genpkey -algorithm RSA -out /etc/pki/CA/private/cakey.pem -aes256
  ```

  （`-aes256` 表示加密私钥，生成时会要求你输入一个密码。）

- **生成自签名的CA证书**：

  ```
  bash
  
  
  复制代码
  openssl req -new -x509 -key /etc/pki/CA/private/cakey.pem -out /etc/pki/CA/certs/ca.crt -days 3650
  ```

  这个命令会生成一个自签名证书，供 CA 使用。

### 3. **检查文件权限**：

如果私钥文件存在，但仍然无法读取，可能是权限问题。确保私钥文件的权限设置正确：

```
bash复制代码chmod 600 /etc/pki/CA/private/cakey.pem
chown root:root /etc/pki/CA/private/cakey.pem
```

上述命令确保只有 `root` 用户可以读取和写入这个文件。

### 4. **检查 openssl.cnf 文件的配置**：

确认你的 OpenSSL 配置文件 `/etc/pki/tls/openssl.cnf` 中，指向 CA 私钥的路径是否正确。查找类似于 `private_key` 或 `certificate` 的设置项，确保其路径与私钥文件的位置一致。

查找命令：

```
bash


复制代码
grep -i 'private_key' /etc/pki/tls/openssl.cnf
```

### 5. **重新运行命令**：

确保以上步骤完成后，重新尝试签署 RA 的证书：

```
bash


复制代码
openssl ca -in /etc/pki/RA/ra.csr -out /etc/pki/RA/certs/ra.crt -days 3650 -extensions v3_ca
```

这些步骤应能帮助你解决私钥文件丢失或权限问题。如果文件不存在或路径错误，可以通过重新生成私钥和证书来解决。

> [root@localhost CA]# openssl ca -in /etc/pki/RA/ra.csr -out /etc/pki/RA/certs/ra.crt -days 3650 -extensions v3_ca Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: /etc/pki/CA/index.txt: No such file or directory unable to open '/etc/pki/CA/index.txt' 140084768290720:error:02001002:system library:fopen:No such file or directory:bss_file.c:402:fopen('/etc/pki/CA/index.txt','r') 140084768290720:error:20074002:BIO routines:FILE_CTRL:system lib:bss_file.c:404: [root@localhost CA]# 

这个错误是因为 OpenSSL 期望的 `index.txt` 文件不存在，该文件是 CA 管理证书颁发的数据库。在 CA 签署证书时，OpenSSL 需要一个 `index.txt` 文件来记录已经签发的证书信息。可以按照以下步骤解决该问题：

### 1. **创建 `index.txt` 文件**：

OpenSSL 需要一个 `index.txt` 文件作为证书的数据库，用来跟踪签发的证书。可以手动创建这个文件：

```
bash


复制代码
touch /etc/pki/CA/index.txt
```

### 2. **创建 `serial` 文件**：

除了 `index.txt` 文件外，OpenSSL 还需要一个 `serial` 文件来记录证书序列号。如果该文件也不存在，可以创建一个，并设置一个初始序列号：

```
bash


复制代码
echo 1000 > /etc/pki/CA/serial
```

这样，OpenSSL 将从序列号 `1000` 开始生成证书。

### 3. **确认路径正确**：

确保 `/etc/pki/CA` 目录下存在以下文件和目录：

- `index.txt`
- `serial`
- `certs/`
- `private/`

如果它们不存在，手动创建：

```
bash


复制代码
mkdir -p /etc/pki/CA/certs /etc/pki/CA/private
```

### 4. **再次运行命令**：

完成上述步骤后，再次运行命令来签署证书：

```
bash


复制代码
openssl ca -in /etc/pki/RA/ra.csr -out /etc/pki/RA/certs/ra.crt -days 3650 -extensions v3_ca
```

### 总结：

这次报错是由于 OpenSSL 需要的 `index.txt` 和 `serial` 文件缺失。通过手动创建这两个文件，并确保目录结构正确，你就可以继续签署证书了。

> [root@localhost OCSP]# openssl ca -in /etc/pki/OCSP/ocsp.csr -out /etc/pki/OCSP/ocsp.crt -extensions v3_ocsp Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: Error Loading extension section v3_ocsp 140090595624864:error:0E06D06C:configuration file routines:NCONF_get_string:no value:conf_lib.c:324:group=CA_default name=email_in_dn [root@localhost OCSP]# 

从你的 `openssl.cnf` 文件来看，可能的原因是 `[ v3_ocsp ]` 扩展部分没有定义，这正是错误中提到的内容 "Error Loading extension section v3_ocsp"。你需要在配置文件中添加一个 `[ v3_ocsp ]` 扩展部分，具体可以按照以下示例添加：

### 解决步骤：

1. **编辑 `openssl.cnf` 文件**，在文件末尾或合适的位置添加以下内容：

```
ini复制代码[ v3_ocsp ]
basicConstraints = CA:FALSE
keyUsage = critical, digitalSignature
extendedKeyUsage = critical, OCSPSigning
```

1. **保存并退出**配置文件。

### 解释：

- `basicConstraints = CA:FALSE`：表示这是一个终端证书，而不是一个 CA 证书。
- `keyUsage = critical, digitalSignature`：表示该证书可以用于数字签名。
- `extendedKeyUsage = critical, OCSPSigning`：表明该证书将用于 OCSP 签名。

1. **重新运行命令**生成证书：

```
bash


复制代码
openssl ca -in /etc/pki/OCSP/ocsp.csr -out /etc/pki/OCSP/ocsp.crt -extensions v3_ocsp
```

这应该可以解决你遇到的问题。如果还有其他问题，请告诉我！

> [root@localhost crl]# openssl ca -gencrl -out /etc/pki/CA/crl/ca.crl Using configuration from /etc/pki/tls/openssl.cnf Enter pass phrase for /etc/pki/CA/private/cakey.pem: /etc/pki/CA/crlnumber: No such file or directory error while loading CRL number 139972047038368:error:02001002:system library:fopen:No such file or directory:bss_file.c:402:fopen('/etc/pki/CA/crlnumber','r') 139972047038368:error:20074002:BIO routines:FILE_CTRL:system lib:bss_file.c:404: [root@localhost crl]# 

这个错误表明 OpenSSL 无法找到 `/etc/pki/CA/crlnumber` 文件，这是生成 CRL（证书吊销列表）所必需的文件。`crlnumber` 文件用于跟踪证书吊销列表的序列号，确保每个 CRL 都有一个唯一的编号。

要解决这个问题，你可以按照以下步骤操作：

### 解决步骤：

1. **创建 `crlnumber` 文件**： 由于 OpenSSL 需要此文件，但找不到它，你可以手动创建它并初始化一个序列号。

   ```
   bash
   
   
   复制代码
   echo '01' > /etc/pki/CA/crlnumber
   ```

   这会创建 `crlnumber` 文件，并将初始序列号设置为 `01`。

2. **检查文件权限**： 确保 OpenSSL 可以访问这个文件。你可以设置合适的权限来避免权限问题：

   ```
   bash
   
   
   复制代码
   chmod 644 /etc/pki/CA/crlnumber
   ```

3. **重新运行命令**： 在创建 `crlnumber` 文件后，重新运行生成 CRL 的命令：

   ```
   bash
   
   
   复制代码
   openssl ca -gencrl -out /etc/pki/CA/crl/ca.crl
   ```

这应该可以解决问题。如果有其他问题或错误提示，请告诉我。