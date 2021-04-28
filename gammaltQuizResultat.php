<?php

session_start();
//Filen visar upp ett gammalt quiz resultat
//filen är lik filen som visar quizresultat direkt efter man rättar ett nyligen genomfört quiz
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

include('dbconnection.php');

$quizHistorikId = $_GET['id']; //id för de gamla svaren 

$sql = "SELECT * FROM quizhistorik WHERE id = $quizHistorikId";

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res = $stmt->fetchAll();
echo("Det här quizet genomfördes " . $res[0]['date']);
$quizId = $res[0]['quizid']; //quizid på quizet som kördes


$sql = 'SELECT id FROM bilder WHERE parent_db="quizBild" AND parent_id = ' . $quizId;//hämtar bakrundsbild
$stmt = $dbconn->prepare($sql);
$data = array(); 
$stmt->execute($data);
$res = $stmt->fetchAll();
if(count($res) > 0){//om det finns uppladdad bakrundsbild annars visas standard bakrund

    $quizBildId = $res[0]['id'];
}else{
    $quizBildId = "standard";
}



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
            //hämtar quiznamn, frågor
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
        //hämtar gamla svaren på frågorna
        $sql = "SELECT * FROM frågahistorik WHERE quiz_historik_id = $quizHistorikId";
       
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $svar = $stmt->fetchAll();

       // $antalfrågor = 0;
        while(true){//loppar igenom alla frågor
            
        if(isset($res[$i])){
            echo('<div class="fråga_grid_container">');
        
            $frågaTxt = $res[$i]['fråga'];
            echo('<div class="fråga"><h4>' . $frågaTxt . '</h4> </div>');

            $frågaId = $res[$i]['id'];
            $sql = "SELECT * FROM svar WHERE frågaId = " . $frågaId;//hämtar alla svaren till quizet

            $stmt = $dbconn->prepare($sql);
            $data = array();  
            $stmt->execute($data);
            $ressvar = $stmt->fetchAll();

            echo('<div class="svar"><ol>');
            $antalRätt = 0;
           // $antalfrågor++;
            
            $j = 0;
            while(true){//loopar igenom alla svar till frågan och kollar om användaren svarade korrekt
               
            if(isset($ressvar[$j])){
                
                if($ressvar[$j]['rättsvar'] == 1){//rättsvar har rättsvarStatus = 1 i databasen
                    if($svar[$i]['svarindex'] == $ressvar[$j]['id']){//om gamla svaret ör lika me facit
                  //      $antalRätt++;  
                    }
                    echo('<li><label class="rätt">' . $ressvar[$j]['svar'] . '</label></li><br>');           
                }else if($svar[$i]['svarindex'] == $ressvar[$j]['id']){
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
            //------hämtar bilderna till alla frågor
            $sql = 'SELECT id FROM bilder WHERE parent_db="frågaBild" AND parent_id = ' . $frågaId;
            $stmtFRÅGABILD = $dbconn->prepare($sql);
            $data = array(); 
            $stmtFRÅGABILD->execute($data);
            $resFRÅGABILD = $stmtFRÅGABILD->fetchAll();
            if(count($resFRÅGABILD) > 0){
                $frågaBildId = $resFRÅGABILD[0]['id'];

                echo('<div class="frågabild"> <img class="frågaBild" src="assets/quizBilder/bild' .  $frågaBildId . '.jpg"></div>');
            
            }//-----hämtar bilderna till alla frågor
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
        //besök skaparen av quizets kontosida
    echo('<a href="index.php?viewkonto=' . $res[0]['creator'] . '">besök skaparen av quizet "' . $res[0]['creator'] . '" </a>');
    ?>
    <br><br>
    </div>
</div>
</body>
</html>