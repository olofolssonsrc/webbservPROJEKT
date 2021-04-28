<?php

session_start();

?>
<html>

<style>

.rätt{
    background-color:lime;
}
.fel{

    background-color : orange;
}
</style>

<body>

<?php 

if(!$_POST){
    header('Location: index.php');
}


$quizId = $_GET['id']; //hämtar bakrundsbild om det finns annars standardbild
include('dbconnection.php');

$sql = 'SELECT id FROM bilder WHERE parent_db="quizBild" AND parent_id = ' . $quizId;
$stmt = $dbconn->prepare($sql);
$data = array(); 
$stmt->execute($data);
$res = $stmt->fetchAll();
if(count($res) > 0){

    $quizBildId = $res[0]['id'];
}else{
    $quizBildId = "standard";
}

//lägger till en gamesplayed i quiz table
$sql = "UPDATE quiz SET gamesPlayed = gamesPlayed + 1 WHERE id = " . $quizId;
$stmt = $dbconn->prepare($sql);
$data = array();
$stmt->execute($data);

?>

<style src="assets/style.css"></style>
<style>

body{
    background-attachment: fixed;
    font-family: Arial, Helvetica, sans-serif;
    background-image: url("assets/quizBilder/<?php echo('bild' . $quizBildId);?>.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;  
}

.fråga_grid_container {
    width:100%;

    display: grid;
    grid-template-columns: auto auto;
    grid-template-rows: auto auto;
    gap: 0px 0px;
    grid-template-areas:

        "fråga fråga"
        "svar frågabild";
}

.fråga { grid-area: fråga; border-radius: 15px;}
.svar { grid-area: svar; }
.frågabild { grid-area: frågabild;}

.frågaBild{
    height:200px;
    margin:5px;
}

.quiz_container {
    background-color:rgba(255,255,255,0.8);
    border-radius: 15px;
    text-align: center;
    width:30%;
    margin-left:50%;
    transform: translateX(-50%);
    display: grid;
    grid-template-columns:  auto;
    grid-template-rows: auto auto auto;
    gap: 0px 0px;
    grid-template-areas:

        "quizHeader"
        "quizMain"
        "quizFooter";
}

.quizHeader { grid-area: quizHeader;}
.quizMain { grid-area: quizMain;}
.quizFooter { grid-area: quizFooter;}

</style>
<div class="quiz_container">
    <div class="quizHeader">
        <h1>
        <?php  
            
            $sql = "SELECT q.namn, f.id, f.fråga  
            FROM frågor AS f 
            INNER JOIN quiz AS q ON q.id = f.quizId WHERE q.id = $quizId";

            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $res = $stmt->fetchAll();
            echo($res[0]['namn']);
        ?>
        </h1>
    </div>

    <div class="quizMain">

        <?php

        include 'Auth.php';
        if($_SESSION['username']){
            //--sparar historik
            $sql = 'SELECT id FROM users WHERE username = "' . $_SESSION['username'] . '"';
             
            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $resUSERID = $stmt->fetchAll();
         
            $sql = "INSERT INTO quizhistorik (quizid, userid, date) 
            VALUES (?, ?, now())";
            $stmt = $dbconn->prepare($sql);
            $data = array($quizId, $resUSERID[0]['id']);
            $stmt->execute($data);
            $quizHistorikId = $dbconn->lastInsertId();
            //--sparar historik 
        }
        
        $i = 0;
       //  $antalfrågor = 0;
        while(true){//loopar igenom alla frågor
           
        if(isset($res[$i])){
            echo('<div class="fråga_grid_container">');
        
            $frågaTxt = $res[$i]['fråga'];
            echo('<div class="fråga"><h4>' . $frågaTxt . '</h4> </div>');
           // $antalfrågor++;
            $frågaId = $res[$i]['id'];
            $sql = "SELECT * FROM svar WHERE frågaId = " . $frågaId;

            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $ressvar = $stmt->fetchAll();

            echo('<div class="svar"><ol>');
            $antalRätt = 0;
            $j = 0;

                 //--sparar svaren
                 if($_SESSION['username']){
                    $sql = "INSERT INTO frågahistorik (frågaid, svarindex,	quiz_historik_id) 
                    VALUES (?, ?, ?)";
                    $stmt = $dbconn->prepare($sql);
                    $data = array($i, $_POST['svar'][$i][0], $quizHistorikId);
                    $stmt->execute($data);
                 }
               
                 //--sparar historik

            while(true){//skriver ut alla svarsalternativ och highlighter det rätta svaret samt svaret användaren angav
            if(isset($ressvar[$j])){

                if($ressvar[$j]['rättsvar'] == 1){
                    if($_POST['svar'][$i][0] == $ressvar[$j]['id']){
                     //   $antalRätt++;  
                    }
                    echo('<li><label class="rätt">' . $ressvar[$j]['svar'] . '</label></li><br>');         
                    
                }else if($_POST['svar'][$i][0] == $ressvar[$j]['id']){
                    echo('<li><label class="fel">' . $ressvar[$j]['svar'] . '</label></li><br>');            
                }else{
                    echo('<li><label >' . $ressvar[$j]['svar'] . '</label></li><br>');     
                }
                $j++;
            }else{
                echo('</ol></div>');
                break;
            }
            }
            //-----bild till fråga
            $sql = 'SELECT id FROM bilder WHERE parent_db="frågaBild" AND parent_id = ' . $frågaId;
            $stmtFRÅGABILD = $dbconn->prepare($sql);
            $data = array(); 
            $stmtFRÅGABILD->execute($data);
            $resFRÅGABILD = $stmtFRÅGABILD->fetchAll();
            if(count($resFRÅGABILD) > 0){
                $frågaBildId = $resFRÅGABILD[0]['id'];

                echo('<div class="frågabild"> <img class="frågaBild" src="assets/quizBilder/bild' .  $frågaBildId . '.jpg"></div>');
            
            }
            //bild till fråga
            
            echo('</div>');

        $i++;
        }else{
           
            break;
        }
        echo('<hr>');
        }
        ?>
       
        </div>
    
        <div class="quizFooter"> 
       <div><?php /*echo($antalRätt . '/' . $antalfrågor);*/?></div><br>
        <a href="index.php">hem</a><br>
    
        <?php
    
        $sql = "SELECT * FROM quiz WHERE id = " . $quizId;
    
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();
      
        $sql = 'SELECT id FROM users WHERE username = "' . $res[0]['creator'] . '"'; //skriver länk till skaparen av quizet
    
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $resUSERID = $stmt->fetchAll();

        echo('<a href="index.php?viewkonto=' . $resUSERID[0][0] . '">besök skaparen av quizet "' . $res[0]['creator'] . '" </a>');
        ?>
    <br><br>
              
    </div>

</div>
</body>
</html>