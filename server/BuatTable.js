var mysql = require('mysql');

var db = mysql.createConnection({
    host: "localhost",
    user: "server",
    password: "server",
    database: "flipserver"
});

db.connect(function(err) {
    if (err) throw err;
    
    let sql = `CREATE TABLE transaction 
    (
        db_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id BIGINT(20) NOT NULL,
        amount INT(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        bank_code VARCHAR(50) NOT NULL,
        account_number VARCHAR(50) NOT NULL,
        beneficiary_name VARCHAR(50) NOT NULL,
        remark VARCHAR(50) NOT NULL,
        receipt VARCHAR(300)  DEFAULT NULL,
        time_served datetime,
        fee INT(50)
    ) DEFAULT CHARSET=utf8`;
    db.query(sql, function (err, result) {
        if (err) throw err;
        console.log("Table created");
    });
});