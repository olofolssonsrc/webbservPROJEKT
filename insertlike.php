<?php
//filen används ej
session_start();
    if(isset($_GET['LIKESTATUS'])){
        include('dbconnection.php');
        $likeStatus = $_GET['LIKESTATUS'];
        $quizId = $_GET['quizid'];
        echo($_GET['quizid']);

        $sql = 'SELECT id
        FROM users WHERE username = "' . $_SESSION['username'] .'"';
        $stmtl = $dbconn->prepare($sql);
        $data = array();
        $stmtl->execute($data);
        $resUserId = $stmtl->fetchAll();

        $userId = $resUserId[0][0];

        $sql = 'SELECT * FROM likesdislikes WHERE userid = "' . $userId . '" AND quizid = "' . $quizId . '"';
    
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();

        if($stmt->rowcount() > 0){

            // header('Location : index.php');
             $sql = 'UPDATE likesdislikes SET likeStatus = "' . $likeStatus . '" WHERE userid = ' . $userId . ' AND quizid = ' . $quizId;
             $stmt = $dbconn->prepare($sql);
             $stmt->execute(); 
         }else{
             $sql = "INSERT INTO likesdislikes (likeStatus, userid, quizid, date) 
             VALUES (?, ?, ?, now())";
             $stmt = $dbconn->prepare($sql);
             # the data we want to insert
             $data = array($likeStatus , $userId, $quizId);
             # execute width array-parameter
             $stmt->execute($data);
         }
    }else{
        echo "<script>location='index.php'</script>";
    }
?>            
