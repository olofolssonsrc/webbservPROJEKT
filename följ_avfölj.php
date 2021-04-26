<?php
//Filen innehåller bakrunds funktioner relaterade till följ funktionen

if(isset($_GET['id'])){//om det skickas en request med en userid kommer följstatusen togglas. 
    include 'dbconnection.php';
    session_start();
    $sql = 'SELECT id FROM följare WHERE följare_id = ' . $_SESSION['userId'] . ' AND följd_user_id = ' . $_GET['id'];
    
    $stmt = $dbconn->prepare($sql);
    $data = array();  
    $stmt->execute($data);
    $res = $stmt->fetchAll();

    if($stmt->rowcount() > 0){//om användaren redan följer kontot, raderas raden och man slutar följa personen

        $sql = 'DELETE FROM följare WHERE följare_id = ' . $_SESSION['userId'] . ' AND följd_user_id = ' . $_GET['id'];
      
        $stmt = $dbconn->prepare($sql);
        $data = array();
        $stmt->execute($data);
        $res = $stmt->execute();
        echo('<button id ="följtoggle" style="">Följ</button>');

    }else{//om man inte följer personen skapas en rad med information om vilket konto följer vilket konto  databasen

      $sql = "INSERT INTO följare (följare_id, följd_user_id, date) 
      VALUES (?, ?, now())";
    
      $stmt = $dbconn->prepare($sql);
      $data = array($_SESSION['userId'], $_GET['id']);
      $stmt->execute($data);
      echo('<button id ="följtoggle" style="border: 2px solid lime;">Avfölj</button>');
    }
}

//funktion som skapar en följ-knapp med färg beroende på följstatur
function getFöljBtn($userid){

    include 'dbconnection.php';

    $sql = 'SELECT id FROM följare WHERE följare_id = ' . $_SESSION['userId'] . ' AND följd_user_id = ' . $userid;
    
    $stmt = $dbconn->prepare($sql);
    $data = array();  
    $stmt->execute($data);
    $res = $stmt->fetchAll();

    if($stmt->rowcount() > 0){

        echo('<div id ="följtoggleparent"><button id ="följtoggle" style="border: 2px solid lime;">Avfölj</button></div>');

    }else{
        echo('<div id ="följtoggleparent"><button id ="följtoggle" style="">Följ</button></div>');
    }   
}

?>