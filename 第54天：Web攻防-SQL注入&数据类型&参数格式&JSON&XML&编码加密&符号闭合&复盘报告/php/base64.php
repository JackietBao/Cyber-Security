<?php
// 数据库连接信息
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "news_db";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取新闻ID（Base64 解码）
$id = isset($_GET['id']) ? base64_decode($_GET['id']) : 0;

// 获取搜索关键字（Base64 解码）
$keyword = isset($_GET['keyword']) ? base64_decode($_GET['keyword']) : '';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新闻页面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .news-list {
            list-style: none;
            padding: 0;
        }
        .news-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .news-list li:last-child {
            border-bottom: none;
        }
        .news-list a {
            text-decoration: none;
            color: #007BFF;
            font-size: 18px;
        }
        .news-list a:hover {
            text-decoration: underline;
        }
        .news-detail {
            padding: 20px;
        }
        .news-detail h2 {
            color: #333;
        }
        .news-detail p {
            color: #555;
            line-height: 1.6;
        }
        .news-detail .meta {
            color: #888;
            font-size: 14px;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- 搜索表单 -->
    <form class="search-form" action="" method="GET">
        <input type="text" name="keyword" placeholder="输入关键字搜索新闻" value="<?php echo htmlspecialchars($keyword); ?>">
        <input type="submit" value="搜索">
    </form>
    <script>
        document.querySelector('.search-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const keyword = document.querySelector('input[name="keyword"]').value;
            const encodedKeyword = btoa(keyword);
            const url = `?keyword=${encodedKeyword}`;
            window.location.href = url;
        });
    </script>

    <?php
    if ($id > 0) {
        // 查询具体的新闻内容
        $sql = "SELECT * FROM news WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='news-detail'>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<p>" . $row['content'] . "</p>";
            echo "<p class='meta'>发布时间: " . $row['created_at'] . "</p>";
            echo "<a href='index.php' class='back-link'>返回新闻列表</a>";
            echo "</div>";
        } else {
            echo "<p>新闻不存在。</p>";
        }
    } else {
        $keyword=base64_decode($keyword);
        // 查询新闻列表（支持关键字搜索）
        $sql = "SELECT id, title, created_at FROM news";
        if (!empty($keyword)) {
            $sql .= " WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY created_at DESC";
        //echo $sql;

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h1>新闻列表</h1>";
            echo "<ul class='news-list'>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='?id=" . base64_encode($row['id']) . "'>" . $row['title'] . "</a> <span class='meta'>(" . $row['created_at'] . ")</span></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>暂无新闻。</p>";
        }
    }

    // 关闭连接
    $conn->close();
    ?>
</div>
</body>
</html>