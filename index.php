<?php

session_start();

/***
*@ Url Shortner
***/


$controllerObj = new Controller;




function __autoload($classname) {
    
if(file_exists("$classname.php")) {
    include "$classname.php";
}    
    
}




