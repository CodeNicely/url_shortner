<?php


class Database {
    public $db;
    
    public function __construct() {
        try {
            $this->db = new PDO( 'mysql:host=localhost;dbname=urlshortner' , 'urlshortner' , '123456');
        
        }
        catch(PDOException $e) {
            die("Database Problem. Not connected.");
        }
        
    }
    
    
    ///Database Functions



    public function pdo_read_last($table,$id) {

$sql = "SELECT * FROM {$table} ORDER BY {$id} DESC LIMIT 1";
 $read = $this->db->prepare($sql);
        if($read->execute()) {
return $read->fetch(PDO::FETCH_ASSOC);
            
        } else return false;
        
}
    
   public function pdo_insert($table , $insert_array ) {
 
$fields = array_keys($insert_array);
$values = array_values($insert_array);

        $fields = '`' . implode ( '`,`', $fields ) . '`';
    $values = "'" . implode ( "','", $values ) . "'";
    $sql = "INSERT INTO {$table} ($fields) VALUES($values)";
        
        
        
        $read = $this->db->prepare($sql);
        if($read->execute()) {
            return true;
        } else return false;
        
        
    }



    
    public function pdo_delete($table, $condition) {
        $sql = "DELETE FROM {$table} WHERE {$condition}";
        
    }
    
    public function pdo_read($table) {
        $sql = "SELECT * FROM {$table}";
        if($read = $this->pdo_query($sql)) {
            $read->execute();
            return $read->fetchAll(PDO::FETCH_ASSOC);
        } else return false;
        
    }
     public function pdo_read_where($table,$where,$equal) {
        $sql = "SELECT * FROM {$table} WHERE {$where}='{$equal}'";
        if($read = $this->pdo_query($sql)) {
            $read->execute();
            return $read->fetchAll(PDO::FETCH_ASSOC);
        } else return false;
        
    }
    
    public function pdo_query($query) {
        try {
            $prepare = $this->db->prepare($query);
            return $prepare;
        }
        catch(PDOException $e) {
            return false;
        }
        
    } //pdo_query($query)
    
} //Database class