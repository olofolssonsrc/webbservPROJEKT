
<style>
.mainKontoSida{

padding : 20px;
}</style>

<div class="mainKontoSida">

<div class="viewQuizInfbody">
<a href="index.php"><h4>< tillbaks</h4></a>
<?php
//filen visar information om användarens konto. Lik filen som visar information om annat konto.
if(!Auth()){
    header('Location: login.php');
}

include 'dbconnection.php';
echo('<h3>Dina quiz</h3>');
//hämtar alla quiz användaren har skapat och sorterar dem efter antalet gånger de har körts
$sql = 'SELECT  q.namn, q.id, q.gamesPlayed 
    FROM users AS u 
    INNER JOIN quiz AS q ON q.creator = u.username 
    WHERE u.username = "' . $_SESSION['username'] .'" ORDER BY gamesPlayed DESC ';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();
echo('<strong>klicka för att se detaljer</strong><br><br>');
if($stmtl->rowcount() > 0){

    for ($i=0; $i < $stmtl->rowcount(); $i++) { 
   
        $sql = " SELECT 
        COUNT(*) 'dislikes'
        FROM likesdislikes 
        WHERE likeStatus = 'DISLIKE' AND parent_id = " . $res[$i]['id'] . ' AND parent_db = "quiz"'; //hämtar antalet dislikes för alla quiz
        
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $dislikeRes = $stmt->fetchAll();

        $sql = " SELECT 
        COUNT(*) 'dislikes'
        FROM likesdislikes 
        WHERE likeStatus = 'LIKE' AND parent_id = " . $res[$i]['id'] . ' AND parent_db = "quiz"'; //hämtar antalet likes för alla quiz
        
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $likeRes = $stmt->fetchAll();

        $dislikes = $dislikeRes[0][0];
        $likes = $likeRes[0][0];

        echo('<div class="quizRad"><a href="index.php?viewQuiz=' . $res[$i]['id'] . '">' . $res[$i]['namn'] . 
        '</a> | spelats ' . $res[$i]['gamesPlayed'] . ' gånger | ' . $likes . '&#x1F44D; 
        '.$dislikes .'&#x1F44E;
        </div>');
    }
}else{
    echo('Den här personen har inte skapat några quiz');
}

include('dbconnection.php');
echo('<br>');
//hämtar alla quyiz som användaren gillar och ogillar
$sql = 'SELECT ls.likeStatus, q.namn, q.id FROM likesdislikes as ls 
INNER JOIN quiz AS q ON q.id = ls.parent_id AND ls.parent_db = "quiz"
WHERE userid = ' . $_SESSION['userId'];

$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();
//print_r($res[0]);
$gillar = "<h3>Quiz som du gillar</h3>";
$ogillar = "<h3>Quiz som du ogillar</h3>";

for ($i=0; $i < $stmtl->rowcount(); $i++){

    if($res[$i]['likeStatus'] == "LIKE"){
        $gillar .= '<a href=index.php?viewQuiz=' . $res[$i]['id'] . '>' . $res[$i]['namn'] . '</a><br>';
    }else{
        $ogillar .= '<a href=index.php?viewQuiz=' . $res[$i]['id'] . '>' . $res[$i]['namn'] . '</a><br>';
    }
}
echo($gillar . $ogillar);

?>
</div>
<?php

echo("<h3>Resultat från tidigare genomförda quiz</h3>");//hämtar och länkar till quiz som användaren har genomfört tidigare
echo("<p><strong>Klicka för detaljer</strong></p>");

$sql = 'SELECT quizhistorik.id, quiz.id, quiz.namn, quizhistorik.date FROM quizhistorik INNER JOIN 
quiz ON quiz.id = quizhistorik.quizid WHERE quizhistorik.userid = '. $_SESSION['userId'] . ' ORDER BY quizhistorik.date desc';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

for ($i=0; $i < count($res); $i++) { 

    echo('<a href="../quiz2.0/gammaltQuizResultat.php?id=' . $res[$i][0] .'" >' . $res[$i]['namn'] . " " .  $res[$i]['date'] . '</a><br><br>');
}
?>

</div>