var mysql      = require('mysql');
var express = require('express');
var app = express();


var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '123456',
    database : 'phpstudy'
});
connection.connect();

app.get("/sql", function (req, res) {
    const id=req.query.id;
    const sql='select * from admin where id='+id;
    connection.query(sql,function (err, result) {
        if (err) {
            console.log('[SELECT ERROR] - ', err.message);
            return;
        }
        console.log(result);
        res.send(result);
    });
})


var server = app.listen(8082, function () {
    var host = server.address().address
    var port = server.address().port
    console.log("应用实例，访问地址为 http://%s:%s", host, port)
})