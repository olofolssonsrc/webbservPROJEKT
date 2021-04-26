<?php
//filen sparar komentarer. Komentarsfältfilen postar data till denna filen
session_start();
include 'Auth.php';
if(!Auth()){

  
}
include 'dbconnection.php';
if(isset($_GET['text'])){

$text = $_GET['text'];

$parentId = $_GET['parent_id'];//vilket objekt som kommentaren är till, kan vara ett quiz eller annan kommentar
$parentDB = $_GET['parent_db'];//vilken table i databasen som objektyet finns i

$userId = $_SESSION['userId'];

$sql = "INSERT INTO kommentarer (parent_db, parent_id, kommentar, userid , date) 
VALUES (?, ?, ?, ?, now())";
$stmt = $dbconn->prepare($sql);
$data = array($parentDB , $parentId, $text, $userId);
$stmt->execute($data);

}

?>