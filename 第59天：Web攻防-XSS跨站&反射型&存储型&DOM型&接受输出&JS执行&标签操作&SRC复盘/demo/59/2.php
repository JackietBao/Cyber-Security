<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入 Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>存储型 XSS 漏洞演示</title>
</head>

<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">存储型 XSS 漏洞演示</h3>
        </div>
        <div class="card-body">
            <?php
            // 连接数据库
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "xss_demo";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }

            // 处理用户提交的消息
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $message = $_POST["message"];
                $sql = "INSERT INTO messages (message) VALUES ('$message')";
                if ($conn->query($sql) === TRUE) {
                    echo '<div class="alert alert-success" role="alert">消息提交成功！</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">提交失败: ' . $conn->error . '</div>';
                }
            }

            // 查询并显示所有消息
            $sql = "SELECT * FROM messages";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="alert alert-info" role="alert">' . $row["message"] . '</div>';
                }
            } else {
                echo '<div class="alert alert-warning" role="alert">暂无消息。</div>';
            }

            $conn->close();
            ?>
            <form action="" method="post" class="row g-3">
                <div class="col-md-12">
                    <label for="message" class="form-label">请输入消息：</label>
                    <textarea id="message" name="message" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 引入 Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>