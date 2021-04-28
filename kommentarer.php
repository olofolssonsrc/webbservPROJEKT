<?php
//denna fil hämtar komentarer på ett quiz eller en kommentar

include 'dbconnection.php';
include 'gillaknappar2.php';
session_start();
$parent_id = intval($_GET['parent_id']);//id på objektet som kommentaren hör till tex ett quiz
$parent_db = $_GET['parent_db'];//databas som objekter finns i (quiz eller kommentarer)

//hämtar kommentaren (text sträng) och id på kontot som skrev kommentaren
$sql = 'SELECT kommentarer.kommentar, kommentarer.userid, users.username, kommentarer.date, kommentarer.id 
FROM kommentarer INNER JOIN users ON users.id = kommentarer.userid WHERE kommentarer.parent_id = '. $parent_id . ' AND kommentarer.parent_db = "' . $parent_db . '" ORDER BY kommentarer.date DESC';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

for ($i=0; $i < count($res); $i++) { //loopar igenom alla kommentarer
   
    //skapar en div för kommentaren, div har klass som anger att det är en kommentar 
    echo('<div class="kommentarOBJECT" ><a href="index.php?viewkonto=' .
     $res[$i]['userid'] . '"><strong>' . $res[$i]['username'] . '</strong><a> ' . $res[$i]['date'] . '<br>' . $res[$i]['kommentar']);
    gillaknappar($res[$i]['id'], 'kommentarer');//hämtar gillaknappar till kommentaren

    //sesvarknapp och kommentera knapp
    echo('<button style="color:blue" class="kommentarObjektSeSvar" id="' . $res[$i]['id'] . '">visa svar</button>
   
    <button style="color:blue" class="kommentarObjektKommentera" id="' . $res[$i]['id'] . '">Komentera</button><br><br></div>
    ');  
}

?>