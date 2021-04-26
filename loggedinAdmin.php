<?php 


if(!isset($_SESSION['userstatus']) && $_SESSION['userstatus'] == 'admin'){

    ?>
<h2>adminFunktioner</h2>

<h3>Förfrågningar</h3>

<?php 
//in med values här
$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res = $stmt->fetchAll();

echo('garage :' . $res[0]['garage'].', user : '. $res[0]['username']);

echo('
<label for="godkänn">Godkän förfrågan</label><br>
<button type="radio"id="godkänn" name="svar" value="godkänn">
<label for="neka">Neka förfrågan</label><br>
<button type="radio"id="neka" name="svar" value="neka">
');




}
 ?>


