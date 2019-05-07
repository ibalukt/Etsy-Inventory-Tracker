<?php
include_once 'DbConfig.php';

class Crud extends DbConfig
{

    private $query;

    public function performOperation($operation,$table)
    {
            //get the array of column names from the table
            $column_name = $this->getCols($table);
            //cols is the array that will contain the simplified column names EXAMPLE: ItemID, ItemName, UnitPrice, etc.
            $columns = array();
            //vals is the array that will contain the values for each of the column names. EXAMPLE: 1, Journal, 20.00, etc.
            $values = array();

            $posted_columns = array();
        
        
            foreach ($column_name as $key => $col)
            {
                //parse through the array and append the column names into the $cols array
                array_push($columns,$col);
                //for each column add the corresponding value to the vals array
                //If the column name is in the post and is set then
                if (isset($_POST[$columns[$key]]))
                {
                    //array_push($columns,$col);
                    //Append the value to the vals array
                    array_push($values,$this->escape_string($_POST[$columns[$key]]));
                }
                else
                {
                    //If the column name in the post is not set then append "N/A" to the array
                    array_push($values,null);
                }
                //DEBUG: echo $cols[$key] . ":" . $vals[$key] . "<br>" ;
        
            }

            //Build the query that we are going to use to insert the new transaction into the TAction table
            $query = $this->buildQuery($table,$operation,$columns,$values);
            //Execute the query that we just built.
            echo $query;
            $result = $this->execute($query);
    }


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
        echo mysqli_stmt_affected_rows($stmt);
        if (mysqli_stmt_affected_rows($stmt)==0) {
            echo 'Error: cannot execute the command <br/>';
            return false;
        }
        else
        {
            echo 'Success! <br/>';
            return ;
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
        $query ="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table' ORDER BY ORDINAL_POSITION";

        $result = $this->connection->query($query);

        $rows = array();

        while($row = $result->fetch_assoc()) {
            $rows[] = $row['COLUMN_NAME'];
        }

        return $rows;

        
    }

    public function buildQuery($targetTable,$operation,$columns,$values)
    {
        $beginning =$operation . " " . $targetTable;
        $middle = "";
        $end = "";
        foreach($columns as $key => $column)
        {
            if ($operation == "INSERT INTO")
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
            elseif ($operation == "UPDATE")
            {
                if ($key == 0 )
                {
                    $middle = " SET $column='$values[$key]',";
                }
                elseif ($key == (sizeof($columns)-1))
                {
                    $end = "$column='$values[$key]' WHERE $columns[0] = $values[0]";
                }
                else
                {
                    $middle = $middle . "$column='$values[$key]', ";
                }
            }
        }
        $query = $beginning . $middle . $end;
        
        return $query;

    }

    public function close()
    {
        mysqli_close($connection);
    }

    public function today() {
        $date = getdate();
        $day = $date['mday'];
        $month = $date['mon'];
        $year = $date['year'];
        $today = "$year-$month-$day";
        return $today;
    }
}