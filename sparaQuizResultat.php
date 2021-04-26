<?php // fil används inte
     if(isset($_POST['svarSerialised'])){

        if(isset($_SESSION['username'])){

            $sql = "SELECT id FROM users WHERE username = " . $_SESSION['username'];

            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();
            $userid = $res[0]['id'];

            $sql = "INSERT INTO quizhistorik (userid, quizid, svar, date) 
            VALUES (?, ?, ?, now())";
            $stmt = $dbconn->prepare($sql);
            $data = array($userid, $quizId, $_POST['svarSerialised']);
            $stmt->execute($data);
        }
       }
?>