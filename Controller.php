<?php

include "Database.php";

class Controller {
    
    public $uri;
    
    function __construct() {
        
        $this->db = new Database;
        
        $this->uri = trim($_SERVER['REQUEST_URI'] , '/');
        
        $this->allowed_actions = array(
        
            "go_to" => "go_to"
        
        );
        
       
        
       if( $this->getUriSegment() == "" ) {
           $this->index();
           
       } else {
           
           if(method_exists( "Controller" , $this->getUriSegment()) && array_key_exists($this->getUriSegment() , $this->allowed_actions)) {
               
               $this->run($this->getUriSegment());
               
           }
           
           else $this->index();
           
       }
        
        
    }
    
    public function index() {
        if($this->getUriSegment() != "" ) {
            $this->goToUrl();
        } else {
            
            include "view/index.php";
        }    
    
    }
    
    public function go_to() {
        echo "go_to";
        
    }
    
    public function getUriSegment( $int = 0 ) {
        
        $explode = explode('/' , $this->uri);
        
        return $explode[$int];
        
    }
    
    public function goToUrl() {
        $id = (int) $this->getUriSegment();
        if($details = $this->db->pdo_read_where('url','id',$id)) {
            $url = $details[0]['url'];
            header("Location: $url");
            
            
            
        } else {
            header("Location: /");
        }
        
    }
    
    public function run( $method  , $params = array() ) {
        
        if(method_exists( "Controller" , $method ) && array_key_exists($method,$this->allowed_actions)) {
            
            call_user_func_array( array("Controller" , $method) , $params );
        } else {
            //Page not found
        }
        
    }
    
    
    
}