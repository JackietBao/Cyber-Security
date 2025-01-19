var fs = require("fs");
var express = require('express');
var app = express();






app.get("/file", function (req, res) {
    var name= req.query.file;
    //res.send(name);
    fs.readFile(name, 'utf8', function (err, data) {
        if(err) throw err;
        console.log(data);
        res.send(data);
    })
})





app.post("/dir", function (req,res) {
    var name= req.query.dir;
    //res.send(name);
    fs.readdir(name, 'utf8', function (err, data) {
        if(err) throw err;
        console.log(data);
        res.send(data);
    })
})




















var server = app.listen(8081, function () {
    var host = server.address().address
    var port = server.address().port
    console.log("应用实例，访问地址为 http://%s:%s", host, port)
})