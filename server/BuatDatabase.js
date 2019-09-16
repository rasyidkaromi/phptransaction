var mysql = require('mysql');

var db = mysql.createConnection({
    host: "localhost",
    user: "server",
    password: "server",
});

db.connect(function(err) {
    if (err) throw err;
    
    let sql = "CREATE DATABASE flipserver";
    db.query(sql, function (err, result) {
        if (err) throw err;
        console.log("Database created");
    });
});