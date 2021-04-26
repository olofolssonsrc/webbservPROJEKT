<?php
session_start();
//fil som ritar upp quizet och svarsalternativ
?>
<html>
<body>

<?php 

$quizId = $_GET['id']; 
include('dbconnection.php');

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
        <?php  //skriver quizets namn och hämtar frågor
            
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
        <form method="post" id="form" action="<?php echo ("../quiz2.0/quizresultat.php?id=" . $quizId); ?>">

        <?php
        //svaren postas till quizresultat sidan 
        $i = 0;

        while(true){
            
        if(isset($res[$i])){//loopar igenom alla frågor
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
            
            $j = 0;
            while(true){//loopar igenom alla svarsalternativ
            if(isset($ressvar[$j])){

                    echo('<li>' . $ressvar[$j]['svar'] . '<input type="radio" name="svar[' . $i . '][]" value="' . $ressvar[$j]['id'] . '" required></li><br>');
                $j++;
            }else{
                echo('</ol></div>');
                break;
            }
            }
            ///-------fråga bild om det finns till frågan
            $sql = 'SELECT id FROM bilder WHERE parent_db="frågaBild" AND parent_id = ' . $frågaId;
            $stmtFRÅGABILD = $dbconn->prepare($sql);
            $data = array(); 
            $stmtFRÅGABILD->execute($data);
            $resFRÅGABILD = $stmtFRÅGABILD->fetchAll();
            if(count($resFRÅGABILD) > 0){
                $frågaBildId = $resFRÅGABILD[0]['id'];

                echo('<div class="frågabild"> <img class="frågaBild" src="assets/quizBilder/bild' .  $frågaBildId . '.jpg"></div>');
            
            }
           ///-------fråga bild om det finns till frågan
           
            echo('</div>');

        $i++;
        }else{
           
            break;
        }
        echo('<hr>');
        }
      
        ?>
       
    </div>

    <div class="quizFooter"> <button id="form" type="submit">Rätta quiz</button><br><br>
                </form>
    </div>

</div>
</body>
</html>