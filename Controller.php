<?php

include "Database.php";

class Controller {
    
    public $uri;
    
    function __construct() {
        
        $this->db = new Database;
        
        $this->uri = trim($_SERVER['REQUEST_URI'] , '/');
        
        $this->allowed_actions = array(
        
            "go_to" => "go_to" ,
            "shortenUrl" => "shortenUrl" ,
            "captacha" => "captacha"
        
        );
        
       
        
       if( $this->getUriSegment() == "" ) {
           $this->index();
           
       } else {
           
           if(method_exists( "Controller" , $this->getUriSegment()) && array_key_exists($this->getUriSegment() , $this->allowed_actions)) {
              // $params = array($this->getUriSegment(1));
              // $this->run( $this->getUriSegment() , $params );
               
               $this->run( $this->getUriSegment() );
               
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
    
    public function go_to( $param1 = "" ) {
        echo "go_to: this is param1: $param1";
        
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
    
    public function shortenUrl() {
        
        $url = (isset($_POST['url'])) ? $_POST['url'] : null;
        
        $captacha = (isset($_POST['captacha'])) ? $_POST['captacha'] : null;
        
        $insert = array();
        if($url!=null && $captacha!=null) {
            
            if(isset($_SESSION['captacha'])) {
                
                if($captacha == $_SESSION['captacha']) {
                    
                    unset($_SESSION['captacha']);
                    
                     $insert['url'] = $url;
            
                    if($this->db->pdo_insert( 'url' , $insert )) {
                
                $your_link = $this->db->pdo_read_last('url','id');
                
                $this->view("head");
                
                echo '<div class="w3-card w3-khaki w3-panel">Copy Url:<textarea class="w3-input">http://'.$_SERVER['SERVER_NAME'].'/'.$your_link['id'].'</textarea><a href="http://'.$_SERVER['SERVER_NAME'].'/'.$your_link['id'].'">http://'.$_SERVER['SERVER_NAME'].'/'.$your_link['id'].'</a></div>';
                
                $this->view("foot");
                    
                } 
            }  else {
                    //Captacha was wrong
                        echo '<div class="w3-text-red">CAPTACHA entered was wrong. Please Try Again.</div>';
                } 
                
            
           
    
            } else {
                    //Captacha was wrong
                        echo '<div class="w3-text-red">CAPTACHA entered was wrong. Please Try Again.</div>';
                }
            
        } 
        
    }
    
    public function captacha() {
        
            $number = rand( 100000 , 999999 );
        
            $_SESSION['captacha'] = $number;
            $font = 'font.ttf';
        
            header("Content-Type: image/png");
            $im = @imagecreate(110, 40)
                    or die("Cannot Initialize new GD image stream");
            $background_color = imagecolorallocate($im, 255, 255, 255);
            $text_color = imagecolorallocate($im, 0, 0, 0);
        
            imagettftext($im, 30, 0, 0, 30, $text_color , $font, $number );
            //imagestring($im, 1000, 15, 15,  $number , $text_color);
            imagepng($im);
            imagedestroy($im);
        
    }
    
    public function view($page) {
        if(file_exists("view/$page.php")) {
            include "view/$page.php";
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