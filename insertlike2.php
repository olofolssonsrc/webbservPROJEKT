<?php
//filen sparar likes/dislikes i databasen
session_start();
    if(isset($_GET['LIKESTATUS'])){
        include('dbconnection.php');
        $likeStatus = $_GET['LIKESTATUS'];
        $parent_id = $_GET['parent_id'];//objektet som användaren har gillat/ogillat
        $parent_db = $_GET['parent_db'];//tablet i databasen som objektet finns i

        $userId = $_SESSION['userId'];

        //hämtar likedislikes och kollar om det redan finns en like/dislike
        $sql = 'SELECT * FROM likesdislikes WHERE userid = "' . $userId . '" AND parent_id = "' . $parent_id . '" AND parent_db = "' . $parent_db . '"';
    
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();

        if($stmt->rowcount() > 0){//om det redan finns en rad updateras den

             $sql = 'UPDATE likesdislikes SET likeStatus = "' . $likeStatus . '" WHERE userid = ' . $userId . ' AND parent_db = "' . $parent_db .'" AND parent_id = "' . $parent_id . '"';
             $stmt = $dbconn->prepare($sql);
             $stmt->execute(); 
            // echo('ändrat');
         }else{//annars skapas en ny rad
             $sql = "INSERT INTO likesdislikes (likeStatus, userid, parent_id, parent_db, date) 
             VALUES (?, ?, ?, ?, now())";
             $stmt = $dbconn->prepare($sql);
             $data = array($likeStatus , $userId, $parent_id, $parent_db);
             $stmt->execute($data);
         }       
    }
?>            
