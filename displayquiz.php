<?php
//Denna fil används inte
session_start();

?>
<html>
<body>

<?php 

$quizId = $_GET['id']; 
include('dbconnection.php');

$sql = "SELECT id FROM bilder WHERE parent_id = " . $quizId;
$stmt = $dbconn->prepare($sql);
$data = array(); 
$stmt->execute($data);
$res = $stmt->fetchAll();
$quizBildId = $res[0]['id'];

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

        $i = 0;

        while(true){
            
        if(isset($res[$i])){
            echo('<div class="fråga_grid_container">');
        
            $frågaTxt = $res[$i]['fråga'];
            echo('<div class="fråga"><h4>' . $frågaTxt . '</h4> </div>');

            $frågaId = $res[$i]['id'];
            $sql = "SELECT * FROM svar WHERE frågaId = " . $frågaId;

            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $ressvar = $stmt->fetchAll();

            echo('<div class="svar"><ol>');
            $antalRätt = 0;
            $j = 0;
            while(true){
            if(isset($ressvar[$j])){

                if($ressvar[$j]['rättsvar'] == 1){
                    if($_POST['svar'][$i][0] == $ressvar[$j]['id']){
                        $antalRätt++;  
                    }
                    echo('<li><label class="rätt">' . $ressvar[$j]['svar'] . '</label></li><br>');         
                    
                }else if($_POST['svar'][$i][0] == $ressvar[$j]['id']){
                    echo('<li><label class="fel">' . $ressvar[$j]['svar'] . '</label></li><br>');            
                }else{
                    echo('<li><label >' . $ressvar[$j]['svar'] . '</label></li><br>');     
                }

                    //echo('<li>' . $ressvar[$j]['svar'] . '<input type="radio" name="svar[' . $i . '][]" value="' . $ressvar[$j]['id'] . '" required></li><br>');
                $j++;
            }else{
                echo('</ol></div>');
                break;
            }
            }
            echo('<div class="frågabild"> <img class="frågaBild" src="assets/quizBilder/bild' .  $frågaId . '.jpg"></div>');
        
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
    
    
    <br><br>
              
    </div>

</div>
</body>
</html>