<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOM - XSS 漏洞演示</title>
    <!-- 引入 Bootstrap 进行页面美化 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h2>DOM - XSS 漏洞页面</h2>
        </div>
        <div class="card-body">
            <div id="output"></div>
            <form action="#" class="row g-3">
                <div class="col-md-12">
                    <label for="user_input" class="form-label">请输入内容</label>
                    <input type="text" id="user_input" class="form-control">
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger" onclick="updateOutput()">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--<img src="x" onerror="alert('XSS 攻击 via 事件属性')">-->
<!--<a href="javascript:alert('XSS 攻击 via javascript 伪协议')">点击我</a>-->

<script>
    function updateOutput() {
        const input = document.getElementById('user_input').value;
        // 直接将用户输入插入到 DOM 中，未做任何过滤
        document.getElementById('output').innerHTML = input;
    }

    // 处理 URL 参数中的攻击向量
    const urlParams = new URLSearchParams(window.location.search);
    const urlInput = urlParams.get('input');
    if (urlInput) {
        document.getElementById('output').innerHTML = urlInput;
    }
</script>

<!-- 引入 Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>