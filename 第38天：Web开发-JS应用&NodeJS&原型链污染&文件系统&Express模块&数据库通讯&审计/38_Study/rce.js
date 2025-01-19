const child_process = require('child_process');
var express = require('express');
var app = express();
const shell =require('shelljs');

shell.exec('calc')

app.get('/rce', function (req, res) {
    const cmd = req.query.cmd;
    child_process.exec(cmd);
})

//命令执行
// child_process.exec('calc');
// child_process.spawnSync('calc');

//代码执行
// eval('child_process.exec(\'calc\');');


var server = app.listen(8081, function () {
    var host = server.address().address
    var port = server.address().port
    console.log("应用实例，访问地址为 http://%s:%s", host, port)
})