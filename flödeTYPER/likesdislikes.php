<style>

.FlödeInfbody{

    padding : 20px;
}


.MainHeader{

    text-decoration: underline;
}

</style>
<div class="FlödeInfbody">
<h2 class="MainHeader">flöde</h2><br>
<a href="../quiz2.0/index.php?flöde=view&kommentarer=view">kommentarer</a>
<a href="../quiz2.0/index.php?flöde=view&likesdislikes=view"><strong>likesdislikes</strong></a>
<a href="../quiz2.0/index.php?flöde=view&quiz=view">quiz</a>
<br><br>

<?php
include '../Auth.php';
if(!Auth()){
    header('Location: login.php');
}

//den här filen hämtar alla gillningar/ogillningar av perssoner man följer
include 'dbconnection.php';
$sql = "SELECT följare.följd_user_id, users.username, users.id FROM följare INNER JOIN users ON users.id = 
följare.följd_user_id WHERE följare.följare_id = " . $_SESSION['userId'];

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$resFÖLJARE = $stmt->fetchAll();

for ($i=0; $i < count($resFÖLJARE); $i++) { //loopar igenom alla följare

   $sql = "SELECT * FROM likesdislikes WHERE userid = " . $resFÖLJARE[$i]['följd_user_id'] . " ORDER BY date ASC";
   $stmt = $dbconn->prepare($sql);
   $data = array();  
   $stmt->execute($data);
   $resALLALIKES = $stmt->fetchAll();

    for ($j=0; $j < count($resALLALIKES); $j++) { //loopar igenom alla gillningar/ogillningar
        if($resALLALIKES[$j]['parent_db'] == "quiz"){ //kollar om det är en gillning på en quiz eller kommentar
        
            $sql = "SELECT * FROM quiz WHERE id = " . $resALLALIKES[$j]['parent_id'];
       
            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();

           if($resALLALIKES[$j]['likeStatus'] == 'LIKE'){//kollar om det är en like eller dislike
            echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> gillade quizet "' . $res[0]['namn']  . '" ' . $resALLALIKES[$j]['date'] .
            '<a href="../quiz2.0/index.php?viewkonto=' .$res[0]['id'] . '"> Gå till quizet</a><hr>');
           }else{
            echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> ogillade quizet "' . $res[0]['namn']  . '" ' . $resALLALIKES[$j]['date'] .
            '<a href="../quiz2.0/index.php?viewkonto=' .$res[0]['id'] . '"> Gå till quizet</a><hr>');
           }
    
        }else if($resALLALIKES[$j]['parent_db'] == "kommentarer"){
    
            $sql = "SELECT * FROM kommentarer WHERE id = " . $resALLALIKES[$j]['parent_id'];
       
            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();
                  
            $sql = 'SELECT username
            FROM users WHERE id = '. $resALLALIKES[$j]['userid'];
            $stmtl = $dbconn->prepare($sql);
            $data = array();
            $stmtl->execute($data);
            $resKOMMENTARSKAPARE = $stmtl->fetchAll();

            $username = $resKOMMENTARSKAPARE[0]['username'];

            if($resALLALIKES[$j]['likeStatus'] == 'LIKE'){
                echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> gillade en kommentar "' . $res[0]['kommentar'] .
                ' skriven av <a href="../quiz2.0/index.php?viewkonto=' .$resALLALIKES[$j]['userid'] . '">' . $username . ' </a>' . $resALLALIKES[$j]['date'] . '<hr>');
            }else{
                echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> ogillade en kommentar "' . $res[0]['kommentar'] .
                ' skriven av <a href="../quiz2.0/index.php?viewkonto=' .$resALLALIKES[$j]['userid'] . '">' . $username . ' </a>' . $resALLALIKES[$j]['date'] . '<hr>');
            }
           
        }
    }
}

?></div>