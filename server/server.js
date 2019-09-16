const express = require("express")
const bodyParser = require("body-parser")
let app = express();
var mysql = require('mysql');
const biguint = require('biguint-format')
var crypto = require('crypto')
const PRIVATKEY = 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41';

var db = mysql.createConnection({
    host: "localhost",
    user: "server",
    password: "server",
    database: "flipserver"
});

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(ignoreFavicon);
app.use(basicAuth);


app.get("/disburse/:id", async (req, res) => {
    var id = req.params.id;
    let response = await disburseDBid(id)
    console.log(response)
    res.json(response)
});

app.post("/disburse", async (req, res) => {
    console.log(req.body)
    let ress = await disbursePostQuery(req.body)
    res.json(ress)
});


app.listen(8080);
console.log('Server started! At http://localhost: :8080');

function randomiz(qty) {
    var x = crypto.randomBytes(qty);
    return biguint(x, 'dec');
}

let disburseDBid = (id) => {
    return new Promise((resolve, reject) => {
        let status = "SUCCESS"
        let Query = "SELECT * FROM transaction WHERE id =" + id + " AND status = '" + status+"'";
        db.query(Query, (err, res) => {
            if (err) {
                throw err
            } else {
                resolve(res)
            }
        })
    })
}

let disbursePostQuery = (data) => {
    return new Promise((resolve, reject) => {
        let uuid = randomiz(4)
        let status = "PENDING"
        let beneficiary_name = 'PT FLIP'
        let receipt = null;
        let fee = 4000;
        let Query = "INSERT INTO transaction (id, amount, status, bank_code, account_number, beneficiary_name, remark, receipt,time_served, fee) VALUES(" + uuid + ", " + data.amount + ", '" + status + "', '" + data.bank_code + "', " + data.account_number + ", '" + beneficiary_name + "', '" + data.remark + "','" + receipt + "' ,''," + fee + " )";
        let getQuery = "SELECT * FROM transaction WHERE id = " + uuid;
        db.query(Query, (err) => {
            if (err) {
                throw err
            } else {
                db.query(getQuery, function (err, getQueryresult) {
                    if (err) {
                        reject(err)
                    } else {
                        resolve(getQueryresult)
                    }
                })
            }
        })
    })
}

function ignoreFavicon(req, res, next) {
    if (req.originalUrl === '/favicon.ico') {
        res.status(204).json({ nope: true });
    } else {
        next();
    }
}

function basicAuth(req, res, next) {
    if (!req.headers.authorization || req.headers.authorization.indexOf('Basic ') === -1) {
        return res.status(401).send('ilegal acces');
    }
    const base64Credentials = req.headers.authorization.split(' ')[1];
    const credentials = Buffer.from(base64Credentials, 'base64').toString('ascii');
    if (credentials === PRIVATKEY) {
        return next();
    } else {
        let response = {
            log: "Kesalahan Authorization",
            message: "Data Authorization Tidak Benar"
        }
        res.status(401).json(response)
        console.log(" **** Kesalahan Authorization ***")
    }
}


