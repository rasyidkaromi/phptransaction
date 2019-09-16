<?php
include("database.php");
include("service.php");


function getMultipleFlipApiLoop()
{
    $runTest = new service();
    $runTest->getMultipleFlipApiLoop();
}

function getMultipleFlipApi()
{
    $runTest = new service();
    $runTest->getMultipleFlipApi();
}

function postFlipApi()
{
    $data = array(
        'bank_code' => "bni",
        'account_number' => "2451536765",
        'amount' => 10000,
        'remark' => "sample remark"
    );
    $runTest = new service();
    $runTest->postFlipApi($data);
}

function generateTrans()
{
    $count = 0;
    $runTest = new service();
    while (true) {
        if ($count <= $runTest->generateTrans) {
            $data = array(
                'bank_code' => "bni",
                'account_number' => "2451536765",
                'amount' => 10000,
                'remark' => "sample remark"
            );
            
            $runTest->postFlipApi($data);
            $count++;
        }
    }
}

function CreateDB()
{
    $db = new DB();
    $db->CreateDB();
}

function CreateTable()
{
    $db = new DB();
    $db->CreateTable();
}

function insertTableJSON()
{
    $jsonData = '{
        "id": 553515245372,
        "amount": 10000,
        "status": "PENDING",
        "timestamp": "2019-05-21 09:12:42",
        "bank_code": "bni",
        "account_number": "1234567890",
        "beneficiary_name": "PT FLIP",
        "remark": "sample remark",
        "receipt": "null",
        "time_served": "0000-00-00 00:00:00",
        "fee": 4000
    }';
    $dataDecode = json_decode($jsonData, true);
    $db = new DB();
    $db->insertTable($dataDecode);
}

function isIDexist()
{
    $id = 5535152564;
    $db = new DB();
    $table = $db->dbTabel;
    $db->isIDexist($id, $table);
}

function isTableexist()
{
    $db = new DB();
    $db->isTableexist();
}

function disbursementStatusDB()
{
    $db = new DB();
    $db->disbursementStatusDB();
}


// command
array_shift($argv);

$array1 = array_shift($argv);
$array2 = array_shift($argv);
print_r(array_shift($argv));
if ($array1 && $array2 == "") {
    call_user_func_array($array1, $argv);
};
if ($array1 && $array2 != "") {
    call_user_func_array(array($array1, $array2), $argv);
}


