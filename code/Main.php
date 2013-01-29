<pre><?php

class Main {

    private $db;
    private $log;

    public function __construct(){
        spl_autoload_register(array("Main","autoload"));
        $this->db = new Database_Model_Log();
        $this->log = new Log_Model_Log();
        var_dump($this);

        //$this->log->init();

    }

    public function autoload($classname){
        $_path = explode('_', $classname);

        $file = getcwd(). "/code/" . implode('/', $_path) . ".php";
        if(file_exists($file)){
            include($file);
        }
        else {
            die('Attempted to load function unsuccessfully.');
        }
    }







}