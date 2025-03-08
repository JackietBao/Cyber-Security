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

// 获取 JSON 数据
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

// 获取新闻 ID
$id = isset($input['id']) ? $input['id'] : 0;

// 获取搜索关键字
$keyword = isset($input['keyword']) ? $input['keyword'] : '';

if ($id > 0) {
    // 查询具体的新闻内容
    $sql = "SELECT * FROM news WHERE id = $id";
    echo $sql;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='news-detail'>";
        echo "<h2>" . $row['title'] . "</h2>";
        echo "<p>" . $row['content'] . "</p>";
        echo "<p class='meta'>发布时间: " . $row['created_at'] . "</p>";
        echo "<a href='javascript:history.back()' class='back-link'>返回新闻列表</a>";
        echo "</div>";
    } else {
        echo "<p>新闻不存在。</p>";
    }
} else {
    // 查询新闻列表（支持关键字搜索）
    $sql = "SELECT id, title, created_at FROM news";
    if (!empty($keyword)) {
        $sql .= " WHERE title LIKE '%$keyword%' OR content LIKE '%$keyword%'";
    }
    $sql .= " ORDER BY created_at DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>新闻列表</h1>";
        echo "<ul class='news-list'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='javascript:getNewsDetail(" . $row['id'] . ")'>" . $row['title'] . "</a> <span class='meta'>(" . $row['created_at'] . ")</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>暂无新闻。</p>";
    }
}

// 关闭连接
$conn->close();
?>