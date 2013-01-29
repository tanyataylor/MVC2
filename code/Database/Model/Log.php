<?php

class Database_Model_Log extends Database_Model_Abstract
{

    public function __construct() {

        $xml = $this->createXmlElement();
        $credentials = $this->dbCredentials($xml);
        $dbConnect = $this->dbConnect($credentials);
        $dbSelect = $this->dbSelect($credentials,$dbConnect);
    }


}
