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

    .quizRad{

        padding : 3px;
    }

</style>

<div class="viewQuizInfbody">
<a href="index.php"><h4>< tillbaks</h4></a>
<?php

include('dbconnection.php');
//filen visar informaiton om ett konto

$userid = intval($_GET['viewkonto']);

//hämtar vilket användarnamn kontot har från id
$sql = 'SELECT username
FROM users WHERE id = '. $userid;
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

$username = $res[0]['username'];

if($username == $_SESSION['username']){
    header('Location: index.php?kontosida=view');
}

//hämtar alla quiz kontot har skapat
$sql = 'SELECT  q.namn, q.id, q.gamesPlayed
    FROM users AS u 
    INNER JOIN quiz AS q ON q.creator = u.username 
    WHERE u.username = "' . $username . '"';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

//knapp för att följa kontot
echo('<h1>' . $username . '<h1>');

include('följ_avfölj.php');

getFöljBtn($userid);

echo('<h3> quiz skapade av ' . $username . '</h3>');
?>
<script>
document.getElementById('följtoggleparent').addEventListener('click', () => {//om man klickar på knappen toggla foljstatus i "följ_avfölj.php" skripten

get('id=<?php echo($userid); ?>' , 'följ_avfölj.php').then((newButton) => {
  
    var gammalKnapp = document.getElementById('följtoggle');
    var gammalKnappParent = gammalKnapp.parentNode;
    gammalKnappParent.removeChild(gammalKnapp);
    gammalKnappParent.innerHTML = newButton + gammalKnappParent.innerHTML;
})            
})

function get(data, location){//get request funktion
return new Promise((resolve) => {

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if(this.readyState == 4 && this.status == 200){
//om inte error
console.log(this.responseText)
resolve(this.responseText);
}
}

xmlhttp.open("GET", location + '?' + data, true);
xmlhttp.send();
})
} 
</script>
<?php


//quiz som kontot har skapat +  hur många gånger quizen körts och likes/dislikes
if($stmtl->rowcount() > 0){

    for ($i=0; $i < $stmtl->rowcount(); $i++) { 
   
        $sql = ' SELECT 
        COUNT(*) dislikes
        FROM likesdislikes 
        WHERE likeStatus = "DISLIKE" AND parent_db = "quiz" AND parent_id = "' . $res[$i]['id'] . '"';
        
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $dislikeRes = $stmt->fetchAll();

        $sql = ' SELECT 
        COUNT(*) dislikes
        FROM likesdislikes 
        WHERE likeStatus = "LIKE" AND parent_db = "quiz" AND parent_id = "' . $res[$i]['id'] . '"';
        
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $likeRes = $stmt->fetchAll();

        $dislikes = $dislikeRes[0][0];
        $likes = $likeRes[0][0];

        echo('<div class="quizRad"><a href="index.php?viewQuiz=' . $res[$i]['id'] . '">' . $res[$i]['namn'] . '</a> | spelats ' . $res[$i]['gamesPlayed'] . ' gånger | ' . $likes . '&#x1F44D; 
        '.$dislikes .'&#x1F44E;
        </div>');
    }

}else{
    echo('Den här personen har inte skapat några quiz');
}


//quiz som kontot gillar och ogillar
$sql = 'SELECT ls.likeStatus, q.namn, q.id FROM likesdislikes as ls 
INNER JOIN quiz AS q ON q.id = ls.parent_id AND ls.parent_db = "quiz"
WHERE userid = "' . $userid . '"';

$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();
//print_r($res[0]);
$gillar = "<h3>Quiz som " . $username. " gillar</h3>";
$ogillar = "<h3>Quiz som " . $username. " ogillar</h3>";

for ($i=0; $i < $stmtl->rowcount(); $i++){
 //   echo($res[$i]['likeStatus']);
    if($res[$i]['likeStatus'] == "LIKE"){
        $gillar .= '<a href=index.php?viewQuiz=' . $res[$i]['id'] . '>' . $res[$i]['namn'] . '</a><br>';
    }else{
        $ogillar .= '<a href=index.php?viewQuiz=' . $res[$i]['id'] . '>' . $res[$i]['namn'] . '</a><br>';
    }
}
echo($gillar . $ogillar);

//tidigare resultat kontot har fått vid olika quiz

echo("<h3>Statestik från $username's tidigare resultat</h3>"); //COUNT(likeStatus) 
echo("<p><strong>Klicka för detaljer</strong></p>");

$sql = 'SELECT quizhistorik.id, quiz.id, quiz.namn, quizhistorik.date FROM quizhistorik INNER JOIN quiz ON quiz.id = quizhistorik.quizid WHERE quizhistorik.userid = '. $userid. ' ORDER BY quizhistorik.date DESC';

$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

for ($i=0; $i < count($res); $i++) { 

    echo('<a href="../quiz2.0/gammaltQuizResultat.php?id=' . $res[$i][0] .'" >' . $res[$i]['namn'] . " " .  $res[$i]['date'] . '</a><br><br>');

}

 /*   $stmtl = $dbconn->prepare($sql);
    $data = array();
    $stmtl->execute($data);
    $res = $stmtl->fetchAll();

}
/*
   $historikId = $res[$i]['id'];
    $quizid = $res[$i]['quizid'];

    $sql = "SELECT COUNT(frågahistorik.id) FROM frågahistorik INNER JOIN svar ON svar.id = frågahistorik.svarindex
     WHERE quiz_historik_id = $historikId AND svar.rättsvar = 1"; /* / COUNT(fråga) FROM frågor WHERE quizid = ";**/

//$sql = "SELECT COUNT(id) from frågahistorik WHERE quiz_historik_id = "

//$sql = "SELECT COUNT(id) FROM svar INNER JOIN frågahistorik ON frågahistorik.frågaid = svar.frågaid WHERE svar.rättsvar = 1 AND svar.frågaid = "

//$sql = "SELECT COUNT(frågahistorik.id) FROM frågahistorik INNER JOIN svar WHERE svar.id = frågahistorik.svarindex AND svar.rättsvar = 1";
//$sql = 'SELECT * FROM quizhistorik INNER JOIN  WHERE parent_db = "quiz"';
/*
$sql = "SELECT * FROM quizhistorik ";

$sql = "SELECT * FROM quizhistorik AS qh INNER JOIN frågahistorik AS fh ON fh.quiz_historik_id = qh.id 
WHERE userid = $userid AND ";
*/


?>

</div>


