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
<a href="../quiz2.0/index.php?flöde=view&kommentarer=view"><strong>kommentarer</strong></a>
<a href="../quiz2.0/index.php?flöde=view&likesdislikes=view">likesdislikes</a>
<a href="../quiz2.0/index.php?flöde=view&quiz=view">quiz</a>
<br><br><?php

//den här filen hämtar kommentarer från konton man följer
include '../Auth.php';
if(!Auth()){
    header('Location: login.php');
}
include 'dbconnection.php';
$sql = "SELECT följare.följd_user_id, users.username, users.id FROM följare INNER JOIN users ON users.id = 
följare.följd_user_id WHERE följare.följare_id = " . $_SESSION['userId'];

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$resFÖLJARE = $stmt->fetchAll(); //hämtar alla följares användarnamnn och id

for ($i=0; $i < count($resFÖLJARE); $i++) { 

   $sql = "SELECT * FROM kommentarer WHERE userid = " . $resFÖLJARE[$i]['följd_user_id'] . " ORDER BY date ASC";
   $stmt = $dbconn->prepare($sql);
   $data = array();  
   $stmt->execute($data);
   $resALLALIKES = $stmt->fetchAll(); //hämtar alla kommentarer

    for ($j=0; $j < count($resALLALIKES) - 1; $j++) { //loopar igenom alla kommentarer och skrver ut dem
        if($resALLALIKES[$j]['parent_db'] == "quiz"){//kollar om det är en kommentar på en quiz eller kommentar
        
            $sql = "SELECT * FROM quiz WHERE id = " . $resALLALIKES[$j]['parent_id'];
       
            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();
           // print_r($res);
            echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> lämnade en kommentar på quizet ' . $res[0]['namn'] . '
             ' . $resALLALIKES[$j]['date'] . ' "' . $resALLALIKES[$j]['kommentar'] . '"
            <a href="../quiz2.0/index.php?viewQuiz' .$res[0]['id'] . '">Undersök mer</a><hr>');
        }else if($resALLALIKES[$j]['parent_db'] == "kommentar"){
            $sql = "SELECT * FROM quiz WHERE id = " . $resALLALIKES[$j]['parent_id'];
       
            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();
           // print_r($res);
            echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> lämnade en kommentar på kommentaren " ' . $res[0]['namn'] .
             ' "'. $resALLALIKES[$j]['date'] . ' "' . $resALLALIKES[$j]['kommentar'] . '"
            <a href="../quiz2.0/index.php?viewQuiz=' .$res[0]['id'] . '">Undersök mer</a><hr>');
        }
    }
}

?></div>