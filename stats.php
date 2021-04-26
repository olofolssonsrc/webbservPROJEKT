<?php 

include 'Auth.php';
if(!Auth()){

    header('Location: login.php');
}




include('dbconnection.php');

     $sql = "SELECT id FROM users WHERE username = ".$_SESSION['username'];


    $stmt = $dbconn->prepare($sql);
    $data = array();  
    $stmt->execute($data);
    $res = $stmt->fetchAll();

       
    $userid = $res[0][0];

    
    $sql = "SELECT * FROM quizsvarhistorik WHERE userid =" . $userid . " ORDER BY date";

    $stmt = $dbconn->prepare($sql);
    $data = array();  
    $stmt->execute($data);
    $res = $stmt->fetchAll();

    $lastDate = '';
    ?><form method="post" action="<?php echo ("quizresultat.php?id=" . $quizId); ?>"> <?php

    for ($i=0; $i < count($res); $i++) { 
        if($res[$i]['date'] != $lastDate){

            
                
            $lastDate = $res[$i]['date'];
            echo('<a href="">'      $res[$i]['date'] . '<br>');
        }
      //  echo($res[$i]['date'] . '<br>');
    }

   







?>

