<?php
 
//den här filen är till för att kolla om användare har behörighet till vissa sidor eller adminbehörighet till admin sidan
if(!isset($_SESSION)){
    session_start();
}

function Auth(){//kollar om panvändare är inloggad eller har blivit bannad
    include('dbconnection.php');

    if(isset($_SESSION['username'])){
        
    }else{
        return(false);
    }
    $sql = 'SELECT * FROM users WHERE username = "' . $_SESSION['username'] . '"';
 
    $stmt = $dbconn->prepare($sql);
    $data = array();  
    $stmt->execute($data);
    $res = $stmt->fetchAll();

    if($res[0]['bannad'] == 0){

        return(true);
    
    }else{
        return(false);
    }
}

function Admin_Auth(){//kollar om perssonen är en admin
    include('dbconnection.php');
    if(Auth()){
        $sql = 'SELECT * FROM users WHERE username = "' . $_SESSION['username'] . '"';

        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();
      
        if($res[0]['admin'] != 1){
    
            return(false);
        }else{
            return(true);
        }
    }
}



?>