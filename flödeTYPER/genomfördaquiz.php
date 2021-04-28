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
<a href="../quiz2.0/index.php?flöde=view&likesdislikes=view">likesdislikes</a>
<a href="../quiz2.0/index.php?flöde=view&quiz=view"><strong>quiz</strong></a>
<br><br>
<?php

//detta script hämtar genomförda quiz från konton man följer

include 'dbconnection.php';
$sql = "SELECT följare.följd_user_id, users.username, users.id FROM följare INNER JOIN users ON users.id =
 följare.följd_user_id WHERE följare.följare_id = " . $_SESSION['userId'];

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$resFÖLJARE = $stmt->fetchAll();//Id och username på alla konton man följer

for ($i=0; $i < count($resFÖLJARE); $i++) {//loopar igenom alla quiz alla konton har genomfört och hämtar detaljer

   $sql = "SELECT * FROM quizhistorik WHERE userid = " . $resFÖLJARE[$i]['följd_user_id'] . " ORDER BY date ASC";
   $stmt = $dbconn->prepare($sql);
   $data = array();  
   $stmt->execute($data);
   $resResultat = $stmt->fetchAll(); //alla resultat

    for ($j=0; $j < count($resResultat); $j++) { //Loopar igenom alla quiz personen har gjort och skriver ut dem

        $sql = "SELECT * FROM quiz WHERE id = " . $resResultat[$j]['quizid'];
       
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();
        echo('<a href="../quiz2.0/index.php?viewkonto=' . $resFÖLJARE[$i]['id'] . '">' . $resFÖLJARE[$i]['username'] . '</a> spelade quizet "' . $res[0]['namn'] . 
        '" <a href="../quiz2.0/gammaltQuizResultat.php?id=' . $resResultat[$j]['id'] . '">se resultatet</a> '.
        
        $resResultat[$j]['date'] . '<hr>');
    }
}

?>
</div>