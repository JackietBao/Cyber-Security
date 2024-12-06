# Content-Type

> 在响应中，Content-Type 标头告诉客户端实际返回的内容的内容类型。浏览器会在某些情况下进行 MIME 查找，并不一定遵循此标题的值; 为了防止这种行为，可以将标题 X-Content-Type-Options 设置为 nosniff。

## 1 Content-Type的格式

> Content-Type的格式 由三部分组成，主类型type, 子类型subtype, 可选参数parameter

```
Content-Type：type/subtype ;parameter
```

- type：主类型，任意的字符串，如text，如果是*号代表所有；
- subtype：子类型，任意的字符串，如html，如果是*号代表所有，用“/”与主类型隔开；
- parameter：可选参数，如charset，boundary等。

示例



```js
// 通常application 格式会另外标注字符集
Content-Type: text/html;
Content-Type: application/json;charset:utf-8;
Content-Type: application/x-www-form-urlencoded;charset:utf-8;
```



### 1.1 media-type（type和subtype合并称为media-type）

> text/html，是指请求的media-type，他分为两个部分type和subtype，以“/”进行分割；也就是上面的type和subtype组合起来叫media-type

常见的 **type** 有，如text，如果是*号代表所有；

- Text：用于标准化地表示的文本信息，文本消息可以是多种字符集和或者多种格式的；
- Multipart：用于连接消息体的多个部分构成一个消息，这些部分可以是不同类型的数据；流的形式，常用于上传大型文件
- Application：用于传输应用程序数据或者二进制数据；日常前后端传输数据常用。
- Message：用于包装一个E-mail消息；
- Image：用于传输静态图片数据；
- Audio：用于传输音频或者音声数据；
- Video：用于传输动态影像数据，可以是与音频编辑在一起的视频数据格式。

常见的 **subtype** 有，如果是*号代表所有，用“/”与主类型 **type** 隔开；

- text/html
- application/x-www-form-urlencoded
- application/json
- multipart/form-data
- application/xml
- text/plain
- text/css
- text/javascript



### 1.2 charset

> 是指定字符编码的标准

常见的有

- "ISO-8859-1"
- "UTF-8"
- "GB2312“
- ”ASCII“等;



### 1.3 boundary

> 多用于上传文件时使用，用于分割数据；后端也不用自己加，

当 **content-type** 为 **multipart/form-data** 类型时，需要用 **boundary** 指定分隔符。所以 **boundary** 后面跟的随机数，就是分隔符，后端就是通过解析到 **boundary** 的值作为分隔符来分隔参数的。但是无论前后端都不要手动设置分隔符，浏览器和postman会主动加的。

------





## 2 常见的Content-Type

### 2.1 application/x-www-form-urlencoded

**content-type** 缺省时的默认格式，键值对并用`&`连接，键值对KV都进行了URL转码。

- 如果是get请求时，会将参数拼接url后面，用`?`分割。且对长度有限制。安全性打折扣。`url?a=value1&b=value2`
- 如果是post请求时，参数会被封装到请求body中



### 2.2 application/json

数据格式以 **json字符串** 的形式传递，使用越来越多的 **Content-Type** ,也是平时见得最多的。

```
{"q": "","p": false,"bs": "","csor": "0","err_no": 0,"errmsg": "","g": [],"queryid": "0xa7fa6"}
```

axios大部分版本默认的请求头 `application/json;charset=utf-8`



### 2.3 multipart/form-data

🚩 [详见上传需要的知识点，站内跳转](https://www.cnblogs.com/wanglei1900/p/17172488.html)

**multipart/form-data** 就诞生了，专门用于有效的传输文件。
**multipart/form-data** 既可以上传文件，也可以上传键值对，它采用了键值对的方式，所以可以上传多个文件。

使用 **multipart/form-data** 时，请求体参数可来自于 `new FormData()` 生成的实例，或 **enctype** 为 **multipart/form-data** 的表单数据。

------





## 3 MIME 类型列表

| 扩展名     | 文档类型                                          | MIME 类型                                                    | 描述                                                         |
| :--------- | :------------------------------------------------ | :----------------------------------------------------------- | :----------------------------------------------------------- |
| .aac       | AAC audio                                         | audio/aac                                                    | AAC (Advanced Audio Coding) 是一种音频编码格式，通常用于数字音频广播和流媒体服务。 |
| .abw       | AbiWord document                                  | application/x-abiword                                        | AbiWord 是一个开源的文字处理软件，而 .abw 是其默认的文档格式。 |
| .arc       | Archive document (multiple files embedded)        | application/x-freearc                                        | 是一种归档文件格式，可以包含多个文件和目录。                 |
| .avi       | AVI:Audio Video Interleave                        | video/x-msvideo                                              | AVI 是 Microsoft 的一种音频视频交错格式。                    |
| .azw       | Amazon Kindle eBook format                        | application/vnd.amazon.ebook                                 | AVI 是 Microsoft 的一种音频视频交错格式。                    |
| .bin       | Any kind of binary data                           | application/octet-stream                                     | 二进制文件，可以包含任何数据，没有特定的文档类型。           |
| .bmp       | Windows OS/2 Bitmap Graphics                      | image/bmp                                                    | BMP 是 Microsoft Windows 的位图图像格式。                    |
| .bz        | BZip archive                                      | application/x-bzip                                           | 使用 Bzip 压缩算法的归档文件格式。                           |
| .bz2       | BZip2 archive                                     | application/x-bzip2                                          | 使用 Bzip2 压缩算法的归档文件格式。                          |
| .csh       | C-Shell script                                    | application/x-csh                                            | C Shell 脚本文件。                                           |
| .css       | Cascading Style Sheets (CSS)                      | text/css                                                     | CSS 用于描述 HTML 或 XML（包括如 SVG, MathML 等衍生技术）文档的样式。 |
| .csv       | Comma-separated values (CSV)                      | text/csv                                                     | CSV（Comma-Separated Values）是一种简单的数据存储格式，它将数据以逗号分隔的表格形式存储。 |
| .doc       | Microsoft Word                                    | application/msword                                           | 这是Microsoft Word的早期版本的文件格式，主要用于保存文档。   |
| .docx      | Microsoft Word (OpenXML)                          | application/vnd.openxmlformats-officedocument.wordprocessingml.document | 这是Microsoft Word的现代版本的文件格式，使用OpenXML技术。    |
| .eot       | MS Embedded OpenType fonts                        | application/vnd.ms-fontobject                                | EOT（Embedded OpenType）是一种专为Internet Explorer设计的字体格式。 |
| .epub      | Electronic publication (EPUB)                     | application/epub+zip                                         | EPUB是一种电子书格式，用于发布和分发电子文档。               |
| .gif       | Graphics Interchange Format (GIF)                 | image/gif                                                    | GIF是一种位图图像格式，广泛用于网络上的图像传输。            |
| .htm       | .html HyperText Markup Language (HTML)            | text/html                                                    | HTML是用于创建网页的标准标记语言。                           |
| .ico       | Icon format                                       | image/vnd.microsoft.icon                                     | ICO是用于表示图标或图标的文件格式。                          |
| .ics       | iCalendar format                                  | text/calendar                                                | ICS（iCalendar）是一种用于存储和交换日历信息的格式。         |
| .jar       | Java Archive (JAR)                                | application/java-archive                                     | JAR文件是Java的归档文件，用于分发Java类文件、库和资源。      |
| .jpeg      | .jpg JPEG images                                  | image/jpeg                                                   | JPEG是一种常见的图像压缩格式，广泛用于数字图像和照片。       |
| .js        | JavaScript                                        | text/javascript                                              | JavaScript是一种用于网页交互的脚本语言。                     |
| .json      | JSON format                                       | application/json                                             | JSON（JavaScript Object Notation）是一种轻量级的数据交换格式，易于阅读和写入。 |
| .jsonld    | JSON-LD format                                    | application/ld+json                                          | JSON-LD是一种用于链接数据的JSON格式。                        |
| .mid       | .midi Musical Instrument Digital Interface (MIDI) | audio/midi audio/x-midi                                      | MIDI是一种用于数字音乐的标准，可以用于存储乐器信息和音乐数据。 |
| .mjs       | JavaScript module                                 | text/javascript                                              | 当JavaScript文件用于ES6模块时，通常使用.mjs扩展名。          |
| .mp3       | MP3 audio                                         | audio/mpeg                                                   | MP3是一种广泛使用的音频压缩格式。                            |
| .mpeg      | MPEG Video                                        | video/mpeg                                                   | MPEG是运动图像专家组的缩写，它是一种视频压缩标准。           |
| .mpkg      | Apple Installer Package                           | application/vnd.apple.installer+xml                          | 这是Apple的软件包格式，用于分发macOS的软件。                 |
| .odp       | OpenDocument presentation document                | application/vnd.oasis.opendocument.presentation              | OpenDocument是开放标准的文档格式，.odp是其演示文稿格式。     |
| .ods       | OpenDocument spreadsheet document                 | application/vnd.oasis. opendocument.spreadsheet              | OpenDocument的电子表格格式。                                 |
| .odt       | OpenDocument text document                        | application/vnd.oasis. opendocument.text                     | OpenDocument的文本文档格式，类似于Microsoft Word的.docx。    |
| .oga       | OGG audio                                         | audio/ogg                                                    | OGG是一种开放、免费的音频压缩格式。                          |
| .ogv       | OGG video                                         | video/ogg                                                    | 使用OGG容器格式的视频文件。                                  |
| .ogx       | OGG                                               | application/ogg                                              | OGG的应用程序文件格式。                                      |
| .otf       | OpenType font                                     | font/otf                                                     | OpenType是一种字体格式，支持广泛的字符集和布局功能。         |
| .png       | Portable Network Graphics                         | image/png                                                    | PNG是一种无损压缩的位图图像格式，常用于网页图形。            |
| .pdf       | Adobe Portable Document Format (PDF)              | application/pdf                                              | PDF是一种由Adobe Systems开发的电子文档格式，用于表示和交换固定布局的文档。 |
| .ppt       | Microsoft PowerPoint                              | application/vnd.ms-powerpoint                                | 这是早期的PowerPoint文件格式。                               |
| .pptx      | Microsoft PowerPoint (OpenXML)                    | application/vnd.openxmlformats-officedocument.presentationml.presentation | 这是现代的PowerPoint文件格式，使用OpenXML标准。              |
| .rar       | RAR archive                                       | application/x-rar-compressed                                 | RAR是一种流行的压缩文件格式，用于存储多个文件和目录。        |
| .rtf       | Rich Text Format (RTF)                            | application/rtf                                              | RTF是一种用于存储和交换文本格式的格式，支持多种格式和样式。  |
| .sh        | Bourne shell script                               | application/x-sh                                             | Bourne shell脚本是用于UNIX和Linux系统的脚本语言。            |
| .svg       | Scalable Vector Graphics (SVG)                    | image/svg+xml                                                | SVG是一种基于XML的矢量图形格式。                             |
| .swf       | Small web format (SWF) or Adobe Flash document    | application/x-shockwave-flash                                | SWF是Adobe Flash的前身，用于创建交互式动画和应用程序。       |
| .tar       | Tape Archive (TAR)                                | application/x-tar                                            | TAR是一种用于归档文件的格式，常用于备份和传输。              |
| .tif .tiff | Tagged Image File Format (TIFF)                   | image/tiff                                                   | TIFF是一种用于存储图像的格式，支持多种分辨率和颜色深度。     |
| .ttf       | TrueType Font                                     | font/ttf                                                     | TrueType字体格式，用于存储数字字体。                         |
| .txt       | Text, (generally ASCII or ISO 8859-n)             | text/plain                                                   | 纯文本文件，通常只包含基本的ASCII字符。                      |
| .vsd       | Microsoft Visio                                   | application/vnd.visio                                        | 这是Microsoft Visio的矢量绘图文件格式。                      |
| .wav       | Waveform Audio Format                             | audio/wav                                                    | WAV是一种音频文件格式，用于存储未压缩的音频数据。            |
| .weba      | WEBM audio                                        | audio/webm                                                   | WEBM是一种开放的音频式，主要用于Web上的音频内容。            |
| .webm      | WEBM video                                        | video/webm                                                   | WEBM是一种开放的视频格式，主要用于Web上的视频内容。          |
| .webp      | WEBP image                                        | image/webp                                                   | WEBP是一种图片格式，支持有损和无损压缩。                     |
| .woff      | Web Open Font Format (WOFF)                       | font/woff                                                    | WOFF是一种字体格式，专为Web而设计，旨在提供跨浏览器的字体支持。 |
| .woff2     | Web Open Font Format (WOFF)                       | font/woff2                                                   | WOFF2是一种更现代的字体格式，旨在提供更好的压缩和兼容性。    |
| .xhtml     | XHTML                                             | application/xhtml+xml                                        | XHTML是一种结合了HTML和XML的文件格式，旨在提高Web页面的标准化和兼容性。 |
| .xls       | Microsoft Excel                                   | application/vnd.ms-excel                                     | 这是早期的Excel文件格式。                                    |
| .xlsx      | Microsoft Excel (OpenXML)                         | application/vnd.openxmlformats-officedocument.spreadsheetml.sheet | 这是现代的Excel文件格式，使用OpenXML标准。                   |
| .xml       | XML                                               | application/xml                                              | XML (eXtensible Markup Language) 是一种标记语言，用于存储和传输数据。 |
| .xul       | XUL                                               | application/vnd.mozilla.xul+xml                              | XUL是一种基于XML的用户界面语言，主要用于开发Firefox和Mozilla应用程序的界面。 |
| .zip       | ZIP archive                                       | application/zip                                              | ZIP是一种流行的压缩文件格式，用于存储多个文件和目录。        |
| .3gp       | 3GPP audio/video container                        | video/3gpp audio/3gpp（若不含视频）                          | 3GP是一种用于移动设备的多媒体容器格式，由3GPP（第三代合作伙伴计划）开发。它主要用于在移动电话和设备上存储和传输音频和视频内容。 |
| .3g2       | 3GPP2 audio/video container                       | video/3gpp2 audio/3gpp2（若不含视频）                        | 3G2是3GPP2（第三代合作伙伴计划2）开发的另一种用于移动设备的多媒体容器格式。它与3GP格式相似，但支持更多的编解码器和功能。 |
| .7z        | 7-zip                                             | archive application/x-7z-compressed                          | .7z 文件扩展名通常与7-Zip压缩文件格式相关联。7-Zip是一种流行的压缩工具，用于创建、解压缩和管理7z格式的文件。 |

[Content-Type](https://www.cnblogs.com/wanglei1900/p/17177303.html#content-type)[  1 Content-Type的格式](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-jF75Ft)[    1.1 media-type（type和subtype合并称为media-type）](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-BQEYiX)[    1.2 charset](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-3N28hD)[    1.3 boundary](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-c2hCPf)[  2 常见的Content-Type](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-wMFcKP)[    2.1 application/x-www-form-urlencoded](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-JjcjyS)[    2.2 application/json](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-AitQXG)[    2.3 multipart/form-data](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-J3FSTh)[  3 MIME 类型列表](https://www.cnblogs.com/wanglei1900/p/17177303.html#tid-zZBpkX)


__EOF__[![img](assets/【HTTP】文件常见传输数据格式 Content-Type 与 MIME/20220208140424.png)](https://pic.cnblogs.com/avatar/2746340/20220208140424.png)**本文作者：** [wanglei1900](https://www.cnblogs.com/wanglei1900)**本文链接：** https://www.cnblogs.com/wanglei1900/p/17177303.html**关于博主：** 评论和私信会在第一时间回复。或者[直接私信](https://msg.cnblogs.com/msg/send/wanglei1900)我。**版权声明：** 本博客所有文章除特别声明外，均采用 [BY-NC-SA](https://creativecommons.org/licenses/by-nc-sa/4.0/) 许可协议。转载请注明出处！**声援博主：** 如果您觉得文章对您有帮助，可以点击文章右下角**【[推荐](javascript:void(0);)】**一下。

洗尽铅华始见金，褪去浮华归本真