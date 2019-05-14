<?php
include_once 'DbConfig.php';

class Crud extends DbConfig
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getData($query)
    {
        $result = $this->connection->query($query);

        if ($result == false)
        {
            return false;
        }

        $rows = array();

        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function prep_getData($query,$types,$params)
    {
        $stmt = $this->connection->prepare($query);
        if ($stmt == false)
        {
            echo "it didn't work";
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;

    }

    public function execute($query)
    {
        $result = $this->connection->query($query);
        
        
        if ($result == false) {
            echo 'Error: cannot execute the command <br/>';
            return false;
        }
        else
        {
            return true;
        }
    }

    public function prep_execute($query,$types,$params)
    {
        $stmt = $this->connection->prepare($query);

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
       // $result = (mysqli_fetch_array($result));
        if (mysqli_stmt_affected_rows($stmt)==0) {
            echo 'Error: cannot execute the command <br/>';
            return false;
        }
        else
        {
            echo 'Success! <br/>';
            return $result;
        }
    }

    public function last_insert_id()
    {
        return mysqli_insert_id($this->connection);
    }

    public function escape_string($value)
    {
        return $this->connection->real_escape_string($value);
    }

    public function close()
    {
        mysqli_close($connection);
    }

}