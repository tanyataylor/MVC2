<?php


abstract class Database_Model_Abstract{

    public function __construct() {

    }

    public function createXmlElement(){
        $xml = json_decode(json_encode(simpleXML_load_file('/var/www/magento/app/etc/local.xml','SimpleXMLElement', LIBXML_NOCDATA)),true);
       // var_dump($xml);
        return $xml;
    }

    public function dbCredentials($xml){
        $contents = $xml['global']['resources']['default_setup']['connection'];
        $connectAttributes = array_slice($contents,0,4);
        $host = $connectAttributes['host'];
        $username = $connectAttributes['username'];
        $password = $connectAttributes['password'];
        $dbName = $connectAttributes['dbname'];
        $storeCredentials = array();
        $storeCredentials[] = $host;
        $storeCredentials[] = $username;
        $storeCredentials[] = $password;
        $storeCredentials[] = $dbName;
        var_dump($storeCredentials);
        return $storeCredentials;
    }

    public function dbConnect($storeCredentials){
        $link = mysql_connect($storeCredentials[0], $storeCredentials[1], $storeCredentials[2]);
        if (!$link){
            //$this->create_log_entry("Error connecting to database: " . mysql_error());
            die("Could not connect : " . mysql_error());
        }
        /* Uncomment below if needed for the test! */
        //else {echo "Link was established.<br/>";}
        //else {$this->create_log_entry("Successfully connected to database");}
        return $link;
    }

    public function dbSelect($storeCredentials, $link){
        $dbSelected = mysql_select_db($storeCredentials[3], $link);
        if(!$dbSelected){
            //$this->create_log_entry("Could not select a database: " . mysql_error());
            die('Can\'t use db : ' . mysql_error());
        }
        /* Uncomment below if needed for the test! */
        //else {echo "<br/>Database $storeCredentials[3] was selected.";}
        //else {$this->create_log_entry("Successfully selected database $storeCredentials[3]");}
        var_dump($dbSelected);
        return $dbSelected;
    }




    public function createTable($tableName = null, $arrayOfColumns = array(), $dataTypes = array(), $primaryKey = null){

        $sql = "CREATE TABLE IF NOT EXISTS $tableName (";

        foreach($arrayOfColumns as $key => $column){
            $dataType = $dataTypes[$key];
            $sql = $sql . "$column $dataType, ";
        }
        $sql = $sql . "PRIMARY KEY ($primaryKey)";
        $sql = $sql . ")";

        $result = mysql_query($sql);
        if (!$result) {
            die('Invalid query : ' . mysql_error());
        }
    }

    public function insertData($tableName = null, $arrayOfColumns = array(), $arrayOfValues = array()){
        array_shift($arrayOfColumns);
        $sqlInsert = "INSERT INTO $tableName (";
        foreach($arrayOfColumns as $column){
            $sqlInsert .= "$column, ";
        }
        $sqlInsert = substr($sqlInsert, 0, strlen($sqlInsert) - 2);
        $sqlInsert .= ") VALUES (";
        foreach($arrayOfValues as $value){
            $sqlInsert .= "'$value', ";
        }
        $sqlInsert = substr($sqlInsert, 0, strlen($sqlInsert) - 2);
        $sqlInsert = $sqlInsert . ")";
        $result = mysql_query($sqlInsert);
        if (!$result) {
            die("Invalid query : " . mysql_error());
        }
        //else{echo"executed";}
    }

    public function getCollection($tableName = null, $arrayOfColumns = null){
        $sql = "SELECT * FROM $tableName";
        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
            }
        echo "<table border=1>";
        while ($row = mysql_fetch_assoc($result)){
            foreach($arrayOfColumns as $key => $value){
                echo "<tr><td><b>" . $value . "</b></td></tr><tr><td>" . $row[$value] . "</td></tr>";
                }
            }
        echo "</table>";
        }

    public function filterByRow($tableName = null, $arrayOfColumns = null, $column = null, $columnFilter = null){
        $sql = "SELECT * FROM $tableName WHERE $column = $columnFilter";
        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
            }
        echo "<table border=1>";
        while($row = mysql_fetch_assoc($result)){
            foreach($arrayOfColumns as $key => $value){
                echo "<tr><td><b>" . $value . "</b></td></tr>" . "<tr><td>" . $row[$value] . "</td></tr>";
            }
        }
    }

    public function updateCollection($tableName = null, $colUpdateArray = null, $valUpdateArray = null, $predicateColumn = null, $predicate = null, $value = null){
        $sql = "UPDATE $tableName SET";
        foreach($colUpdateArray as $key => $column){
            $valueUpdate = $valUpdateArray[$key];
            $sql .= " $column = '$valueUpdate',";
            }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql = $sql . " WHERE $predicateColumn $predicate $value";
        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
            }
        }

    public function deleteCollectionAll($tableName = null){
        $sql = "DELETE FROM $tableName";
        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
        }
    }

    public function deleteByRow($tableName = null, $column = null, $predicate = null, $value = null){
        $sql = "DELETE FROM $tableName WHERE $column $predicate $value";
        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
        }

    }



}
