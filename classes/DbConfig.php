<?php

class DbConfig
{
    protected $connection;
    
    public function __construct()
    {
    $ini = parse_ini_file("etsytracker.ini",true);
        $_host  = $ini['LOCAL_SERVER']['HOST']; 
        $_username = $ini['LOCAL_SERVER']['USER_NAME'];
        $_password  = $ini['LOCAL_SERVER']['USER_PASSWORD'];
        $_database  = $ini['LOCAL_SERVER']['DATABASE']; 



        if (!isset($this->connection)) {
            $this->connection = new mysqli($_host,
                                           $_username,
                                           $_password,
                                           $_database);

        }

        if (!$this->connection) {
            echo 'Cannot connect to database server';
            exit;
        }

        return $this->connection;
    }

}
?>