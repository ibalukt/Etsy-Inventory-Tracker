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

    public function execute($query)
    {
        $result = $this->connection->query($query);
        
        if ($result == false) {
            echo 'Error: cannot execute the command';
            return false;
        }
        else
        {
            return true;
        }
    }

    public function escape_string($value)
    {
        return $this->connection->real_escape_string($value);
    }

    /*The delete function takes the id of the row to be deleted, the name of the id column(EXAMPLE, ItemID vs OrderID), and the $table
    This function will not work if there is another item in the db that is dependent upon what you are trying to delete.*/
    public function delete($id,$id_name,$table)
    {          
        $query = "DELETE FROM $table WHERE $id_name =$id;";
        echo $query . "<br>";

        $result = $this->connection->query($query);

        if ($result == false)
        {
            echo 'Error: cannot delete id: ' . $id . ' from table: ' . $table;
            return false;
        }
        else
        {
            return true;
        }
    }    

    //GETS THE COLUMN NAMES FOR THE TABLES
    public function getCols($table)
    {
        $query ="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table'";

        $result = $this->connection->query($query);

        $rows = array();

        while($row = $result->fetch_assoc()) {
            $rows[] = $row['COLUMN_NAME'];
        }

        return $rows;

        
    }

    public function buildQuery($targetTable,$columns,$values)
    {
        $beginning ="INSERT INTO $targetTable";
        $middle = "";
        $end = "";
        foreach($columns as $key => $column)
        {
            if ($key == 0)
            {
                $middle = "(".$column.",";
                $end = "VALUES ('$values[$key]',";
            }
            elseif($key == (sizeof($columns)-1))
            {
                $middle = $middle . $column . ")";
                $end = $end ."'$values[$key]')";
            }
            else
            {
                $middle = $middle . $column . ",";
                $end = $end . "'$values[$key]',";
            }
        }
        $query = $beginning . $middle . $end;
        
        return $query;

    }
}