<?php

class SQL {
    public static function checkQuery($con, $op){
        if (!$op){
            $_SESSION['mssg'] = 'Error Try Again '.mysqli_error($con);
            exit();
        };
    }
    public static function doQuery($con, $sql){
        $result = mysqli_query($con, $sql);
        self::checkQuery($con, $result);
        return $result;
    }
    public static function insert($con, $table, $columns, $values, $successMssg = null){
        $sql = "INSERT INTO $table $columns VALUES $values"; 
        $op =  mysqli_query($con,$sql);
        self::checkQuery($con, $op);
        if($successMssg) $_SESSION['mssg'] = $successMssg;
    }
    public static function delete($con, $table, $condition, $successMssg = null){
        $sql = "DELETE FROM $table WHERE $condition;";
        $op =  mysqli_query($con,$sql);
        self::checkQuery($con, $op);
        if($successMssg) $_SESSION['mssg'] = $successMssg;
    }
    public static function update($con, $table, $setValues, $condition, $successMssg = null){
        $sql = 
        "UPDATE $table SET $setValues WHERE $condition;";
        $op =  mysqli_query($con,$sql);
        self::checkQuery($con, $op);
        if($successMssg) $_SESSION['mssg'] = $successMssg;
    }
    public static function read($con, $columns, $table, $condition){
        $sql = 
        "SELECT $columns FROM $table WHERE $condition;";
        $op =  mysqli_query($con,$sql);
        self::checkQuery($con, $op);
        $no_of_rows = mysqli_num_rows($op);
        return [$no_of_rows, $op];
    }
}

?>