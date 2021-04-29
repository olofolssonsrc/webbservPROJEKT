

<?php
//denna fil hämtar komentarer på ett quiz eller en kommentar

include 'dbconnection.php';
include 'gillaknappar2.php';
session_start();
$parent_id = intval($_GET['parent_id']);//id på objektet som kommentaren hör till tex ett quiz
$parent_db = $_GET['parent_db'];//databas som objekter finns i (quiz eller kommentarer)

//hämtar kommentaren (text sträng) och id på kontot som skrev kommentaren
$sql = 'SELECT kommentarer.kommentar, kommentarer.userid, users.username, kommentarer.date, kommentarer.id 
FROM kommentarer INNER JOIN users ON users.id = kommentarer.userid WHERE kommentarer.parent_id = '. $parent_id . ' AND kommentarer.parent_db = "' . $parent_db . '" ORDER BY kommentarer.date asc';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

for ($i=0; $i < count($res); $i++) { //loopar igenom alla kommentarer
   
    //skapar en div för kommentaren, div har klass som anger att det är en kommentar 
    echo('<br><div><a href="index.php?viewkonto=' .
     $res[$i]['userid'] . '"><strong>' . $res[$i]['username'] . '</strong></a> ' . $res[$i]['date'] . '<br>' . $res[$i]['kommentar']);
    gillaknappar($res[$i]['id'], 'kommentarer');//hämtar gillaknappar till kommentaren

    //räkna kommentarens svar
    $sql = 'SELECT COUNT(kommentar) FROM kommentarer WHERE kommentarer.parent_id = '. $res[$i]['id'] . ' AND kommentarer.parent_db = "kommentarer"';
    $stmtl = $dbconn->prepare($sql);
    $data = array();
    $stmtl->execute($data);
    $svarsKnappInf = $stmtl->fetchAll();

    //Inga onClick istället för Eventlisteners i <string> tag efterssom om det importeras i stingformat körs inte koden automatiskt.
     echo('<button style="color:blue" onClick=" if(document.getElementById(' . "'" . $res[$i]['id'] . "'". ').status == ' . "'" . "dölj svar" . "'" .'){
        döljKommentarer(' .  $res[$i]['id']. ');
    }else{
        visaKommentarer(' . $res[$i]['id']. ');
    }" class="kommentarObjektSeSvar" id="' . $res[$i]['id'] . '">visa svar (' . $svarsKnappInf[0][0] . ')</button>');
   
    //kommentera se svar knapp med anatlet tidigare svar
    echo('   
    <button style="color:blue" onClick="nyKommentar( ' . $res[$i]["id"] . ',' . "'kommentarer'" . ');"  class="kommentarObjektKommentera" id="' . $res[$i]['id'] . '">Komentera</button>
    ');
  
    echo('</div><br>');
}


?>