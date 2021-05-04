<style>

.viewQuizInfbody{

    padding : 20px;
}

.like{

color:blue;

}

.dislike{

color:red;

}

</style>

<div class="viewQuizInfbody">
<a href="index.php"><h4>< tillbaks</h4></a>
<?php
//filen visar information om ett quiz, antal likes och hur måpnga gånger det körts + kommentarer

include('dbconnection.php');
$quizId = intval($_GET['viewQuiz']);

$sql = "SELECT * FROM quiz WHERE id = " . $quizId;

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res = $stmt->fetchAll();

//hämtar alla likes 
$sql = ' SELECT 
    COUNT(*) likes
    FROM likesdislikes 
    WHERE likeStatus = "LIKE" AND parent_id = ' . $quizId . ' AND parent_db = "quiz"';

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res2 = $stmt->fetchAll();

//hämtar alla dislikes 
$sql = ' SELECT 
    COUNT(*) dislikes
    FROM likesdislikes 
    WHERE likeStatus = "DISLIKE" AND parent_id = ' . $quizId . ' AND parent_db = "quiz"';

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res3 = $stmt->fetchAll();

//hämtar info om skaparen av quizet
$sql = 'SELECT id
FROM users WHERE username = "' . $res[0]['creator'] .'"';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$resUserId = $stmtl->fetchAll();

$userId = $resUserId[0][0];

echo('<h2>' . $res[0]['namn'] .  '</h2>');
echo('<i>Skapad av <a href="index.php?viewkonto=' . $userId . '">' . $res[0]['creator']. '</a>&nbsp;&nbsp;&nbsp;&nbsp;' . substr($res[0]['date'], 0, -9) . '</i>');
echo('<p>Spelats totalt ' . $res[0]['gamesPlayed'] . ' gånger</p>');
echo('<p class="like">' . $res2[0][0] . ' gillningar</p><p class="dislike">' . $res3[0][0] . ' ogillningar</p>');

if(isset($_POST['startquiz'])){

    $_SESSION['körquiz' . $quizId] = 1;
 //  header('Location : görquiz.php?id="'. $quizId . '"');
    header('Location: görquiz.php?id=' . $quizId);
   echo('test') ;
};

//om inloggad visas gillaknappar
if(isset($_SESSION['username'])){
    include 'gillaknappar2.php'; //inkluderar gillaknappar till quizet
    gillaknappar(strval($_GET['viewQuiz']), 'quiz');
    echo('<br><br>');
}else{
    echo('Logga in för att gilla quizet<br><br>');
}

?>

<form method="post" action="<?php echo($_SERVER["PHP_SELF"] . "?viewQuiz=" . $quizId) ?>">
    <input type="hidden" name="startquiz" value="1"></input>
  <button type="submit">Starta quiz</button>
  
</form>
<h3>Kommentarer</h3>
<?php

//inkluderar kommentarsfält om inloggad
if(isset($_SESSION['username'])){
    include 'komentarsfält2.php';
}else{
    echo('logga in för att se kommentarer och kommentera');
}
  

?>

</div>


