<?php
require_once('database.php');
require_once('cert.php');

class service
{
    protected $serverData;
    protected $cert;
    protected $db;
    protected $Certificates;
    protected $debug;
    protected $loopTime;
    protected $loopEnable;
    public $generateTrans;

    function __construct()
    {
        $this->cert = new cert();
        $this->db = new DB();
        $this->Certificates = $this->getCert();
        $this->debug = DISPLAY_DEBUG;
        $this->loopTime = LOOPTIME;
        $this->loopEnable = LOOPENABLE;
        $this->generateTrans = GENERATETRANS;
        $this->serverData = array(
            'serverhostname' => SERVERHOST,
            'get_schema' => GET_schema,
            'post_schema' => POST_schema,
            'secretKey' => PRIVATKEY
        );
    }


    function log($data)
    {
        if ($this->debug) {
            print_r("\n");
            print_r($data);
        }
    }


    function getCert()
    {
        $resultCert = $this->cert->getCertificates();
        return $resultCert;
    }


    // GetHostname : ...
    function GetHostname(array $params)
    {
        $hostname = $params['serverhostname'];
        if (ip2long($hostname) !== false) $hostname = 'http://' . $hostname;

        if (substr($hostname, -1) === '/') return substr($hostname, 0, strlen($hostname) - 1);
        $this->hostname = $hostname;
        return $hostname;
    }

    function postFlipApi($data)
    {
        $queryData = http_build_query($data);
        $encoded_auth = base64_encode(PRIVATKEY);
        $url = $this->GetHostname($this->serverData) . $this->serverData['post_schema'];

        // init
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);

        // authorization
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: " . CONTENTTYPE]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $encoded_auth]);

        // POST
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $queryData);

        // Set up ssl
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, $this->Certificates);


        if ($this->debug) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
        }

        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);

        $response = curl_exec($curl);
        $dataDecode = json_decode($response, true);
        $resetRes = reset($dataDecode);
        $this->log($resetRes);
        $this->log($dataDecode);

        // isset atau !empty
        if (!empty($resetRes['account_number'])) {
            $this->log("\nmasuk database");
            $this->db->insertTable($resetRes);
            curl_close($curl);
        } else {
            $this->log("\ntidak masuk database");
            curl_close($curl);
        }
    }


    function getFlipApi($Id)
    {

        $encoded_auth = base64_encode(PRIVATKEY);
        $url = $this->GetHostname($this->serverData) . $this->serverData['get_schema'] . $Id;

        // init
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);

        // authorization
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: " . CONTENTTYPE]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $encoded_auth]);

        // Set up ssl
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, $this->Certificates);

        if ($this->debug) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
        }

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 1); //timeout in seconds

        $response = curl_exec($curl);
        curl_close($curl);
        $dataDecode = json_decode($response, true);
        $this->log($dataDecode);
        return $dataDecode;
    }


    function getMultipleFlipApi()
    {
        $ArrayID = $this->db->disbursementStatusDB();
        foreach ($ArrayID as $id) {
            $response = $this->getFlipApi($id);
            $this->db->updateSuccessTable($response);
        }
    }

    
    function getMultipleFlipApiLoop()
    {
        while ($this->loopEnable) {

            $ArrayID = $this->db->disbursementStatusDB();
            foreach ($ArrayID as $id) {
                $response = $this->getFlipApi($id);
                $this->db->updateSuccessTable($response);
            }
            sleep($this->loopTime); // sleep config *config.php
        }
    }
}


