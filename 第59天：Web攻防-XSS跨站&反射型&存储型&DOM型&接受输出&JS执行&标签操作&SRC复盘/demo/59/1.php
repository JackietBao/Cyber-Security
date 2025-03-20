<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入 Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>反射型 XSS 漏洞演示</title>
</head>

<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">反射型 XSS 漏洞演示</h3>
        </div>
        <div class="card-body">
            <?php
            // 获取用户输入的参数
            if (isset($_GET['name'])) {
                $name = $_GET['name'];
                // 直接输出用户输入，未进行任何过滤或转义
                echo '<p class="lead">你输入的名字是：' . $name . '</p>';
            }
            ?>
            <form action="" method="get" class="row g-3">
                <div class="col-md-12">
                    <label for="name" class="form-label">请输入你的名字：</label>
                    <input type="text" id="name" name="name" class="form-control">
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