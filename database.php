<?php


include("config.php");

class DB
{
    protected $servername;
    protected $username;
    protected $password;
    public $dbname;
    public $dbTabel;
    protected $debug;

    public function __construct()
    {
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
        mysqli_report(MYSQLI_REPORT_STRICT);
        $this->debug = DISPLAY_DEBUG;
        $this->servername = DBADDRESS;
        $this->username = DBUSERNAME;
        $this->password = DBPASSWORD;
        $this->dbname = DBNAME;
        $this->dbTabel = TABLENAME;
    }

    function log($data)
    {
        if ($this->debug) {
            print_r($data);
        }
    }

    // (MySQLi Object-oriented)
    private function connectDB()
    {
        if ($this->isDBexist()) {
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            $conn->set_charset("utf8");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                return $conn;
            }
        } else {
            die("DataBase not exist ");
        }
    }

    // (MySQLi Object-oriented)
    private function connectCreateDB()
    {
        $conn = new mysqli($this->servername, $this->username, $this->password);
        $conn->set_charset("utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            return $conn;
        }
    }

    // (MySQLi Object-oriented)
    function CreateDB()
    {
        $link = $this->connectCreateDB();
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->dbname . " DEFAULT CHARACTER SET utf8 ";
        if ($link->query($sql) === TRUE) {
            echo "Database " . $this->dbname . " created successfully";
        } else {
            echo "Error creating database: " . $link->error;
        }
        $link->close();
    }

    // (MySQLi Object-oriented)
    function CreateTable()
    {
        $conn = $this->connectDB();
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->dbTabel . " (
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
            fee INT(50)) DEFAULT CHARSET=utf8";

        if ($conn->query($sql) === TRUE) {
            echo "\n" . $this->dbTabel . " table created successfully";
        } else {
            echo "\nError creating table: " . $conn->error;
        }

        $conn->close();
    }

    // (MySQLi Object-oriented)
    function insertTable($data)
    {
        $connect = $this->connectDB();
        $content = $data;
        // isset atau !empty
        if (!empty($content["id"])) {
            $cekId = $this->isIDexist($content["id"], $this->dbTabel);
            if ($cekId) {
                $this->log("\n *** transaction id telah ada dan tidak bisa diduplikat ***");
                $connect->close();
            } else {
                $ids = $content['id'];
                $amount = $content['amount'];
                $statuss = $content['status'];
                $timestamp = $content['timestamp'];
                $bank_code = $content['bank_code'];
                $account_number = $content['account_number'];
                $beneficiary_name = $content['beneficiary_name'];
                $remark = $content['remark'];
                $receipt = $content['receipt'];
                $time_served = $content['time_served'];
                $fee = $content['fee'];
                $query = "INSERT INTO " . $this->dbTabel . "(id, amount, status, timestamp, bank_code, account_number, beneficiary_name, remark, receipt, time_served, fee) 
            VALUES('$ids', '$amount', '$statuss', '$timestamp', '$bank_code', '$account_number', '$beneficiary_name', '$remark', '$receipt', '$time_served', '$fee')";

                if ($connect->query($query) === TRUE) {
                    $this->log("\n *** record baru database sudah dimasukan ***");
                } else {
                    $this->log("\n *** Error: " . $query . "\n " . $connect->error . " ***");
                }

                $connect->close();
            }
        } else {
            $this->log("\n *** transaction ID tidak ditemukan ***");
            $connect->close();
        }
    }

    //  (MySQLi Procedural)
    function updateSuccessTable($data)
    {
        $connect = $this->connectDB();
        foreach ($data as $datas) {
            $this->log("\nupdateSuccessTable");
            $timestamp = strtotime($datas["time_served"]);
            $time_served = date("Y-m-d H:i:s",$timestamp);
            if (!empty($datas["id"])) {
                $sql = "UPDATE " . $this->dbTabel . " SET status= '" . $datas["status"] . "', receipt= '" . $datas["receipt"] . "' , time_served = '" . $time_served . "' WHERE id= " . $datas["id"];
                $this->log($sql);
                if (mysqli_query($connect, $sql)) {
                    echo "Record updated successfully";
                    $connect->close();
                } else {
                    echo "Error updating record: " . mysqli_error($connect);
                    $connect->close();
                }
                $this->log("\n *** transaction ID ditemukan ***");
            } else {
                $this->log("\n *** transaction ID tidak ditemukan ***");
                $connect->close();
            }
        }
    }

    //  (MySQLi Procedural)
    function disbursementStatusDB()
    {
        $Arrayrow = [];
        $status = 'PENDING';
        $receipt = "null";
        $connect = $this->connectDB();
        if ($this->isTableexist()) {
            $query = mysqli_query($connect, "select * from " . $this->dbTabel . " where status = '" . $status . "' AND receipt ='" . $receipt . "'");
            $this->log($query);
            while ($row = mysqli_fetch_array($query)) {
                array_push($Arrayrow, $row['id']);
            }
            $this->log($Arrayrow);
            return $Arrayrow;
        } else {
            $this->log("\n *** Table tidak ada. cek kembali koneksi database ***");
        }
    }

    //  (MySQLi Procedural)
    function isIDexist($id, $table)
    {
        $connect = $this->connectDB();
        $query = mysqli_query($connect, "select * from " . $table . " where id=" . $id);
        if (mysqli_num_rows($query) > 0) {
            $this->log("\n -- cek transactionID " . $table . " : ID Duplikat --");
            return true;
        } else {
            $this->log("\n -- cek transactionID " . $table . " : ID tidak ada dan siap diinput--");
            return false;
        };
    }

    //  (MySQLi Procedural)
    function isDBexist()
    {
        $connect = $this->connectCreateDB();
        $query = mysqli_query($connect, 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->dbname . '"');
        if (mysqli_num_rows($query) > 0) {
            $connect->close();
            $this->log("\n -- cek database " . $this->dbname . " : ADA --");
            return true;
        } else {
            $connect->close();
            $this->log("\n -- cek database " . $this->dbname . " : TIDAK ADA --");
            return false;
        };
    }

    //  (MySQLi Procedural)
    function isTableexist()
    {
        $connect = $this->connectDB();
        $query = mysqli_query($connect, 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "' . $this->dbTabel . '"');
        $this->log($query);
        if (mysqli_num_rows($query) > 0) {
            $connect->close();
            $this->log("\n -- cek table " . $this->dbTabel . " : ADA --");
            return true;
        } else {
            $connect->close();
            $this->log("\n -- cek table " . $this->dbTabel . " : TIDAK ADA --");
            return false;
        };
    }
}


