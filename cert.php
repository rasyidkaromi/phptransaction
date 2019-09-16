<?php


class cert{
    protected $Certificates;
    function __construct()
    {
        $this->setCertificates($this->fileCert('\Certificates.pem'));
    }
    public function setCertificates($cert)
    {
        $this->Certificates = $cert;

        return $this;
    }
    protected function fileCert($path = null) {
        return realpath(__DIR__ . './') . $path;
    }
    public function getCertificates()
    {
        return $this->Certificates;
    }
}

