<?php 
//skapa quiz sida
session_start();
include 'Auth.php';
if(!Auth()){

    header('Location: login.php');
}

?>
<style>

.grå{

background-color : lightgray; 

}
.deleteBtn{

    color:red;
    width: 20px;
    display:inline;
}

.frågaContainer{

padding : 5px;

}
.kort{
    width: 50px;
    float: left;
}

</style>

<?php

if(isset($_POST['quiznamn'])){

    function sparaQuiz(){//funktion som sparar quiz info i databasen
        include('dbconnection.php');
        $sql = "INSERT INTO quiz (namn, creator, date) 
        VALUES (?, ?, now())";
        $stmt = $dbconn->prepare($sql);
        $data = array($_POST['quiznamn'], $_SESSION['username']);
        $stmt->execute($data);
        $last_id = $dbconn->lastInsertId();
        return $last_id;
    }
    
    function sparaFråga($quizId, $fråga){//funktion som sparar fråga  i databasen
        include('dbconnection.php');
        $sql = "INSERT INTO frågor ( quizId, fråga , date) 
        VALUES (?, ?, now())";
        $stmt = $dbconn->prepare($sql);
        $data = array($quizId, $fråga);
        $stmt->execute($data);
        $last_id = $dbconn->lastInsertId();
        return $last_id;
    }
    
    function sparaSvar($frågeId, $svar, $rättsvar){//funktion som sparar svarsalternativ  i databasen
        include('dbconnection.php');
        $sql = "INSERT INTO svar ( svar, rättsvar, frågaId , date) 
        VALUES (?, ?, ?, now())";
        $stmt = $dbconn->prepare($sql);
        $data = array($svar, $rättsvar, $frågeId);
        $stmt->execute($data);
    
    }

    $quizId = sparaQuiz();

    for ($i=0; $i < count($_POST['frågor']); $i++) { //loopar igenom alla frågor som postats

        $frågaId = sparaFråga($quizId, $_POST['frågor'][$i]);//sparar igenom alla frågor
  
        if(isset($_FILES['frågabild' . ($i + 1)])){//sparar fråga bild om det skickas in (ej obligatoriskt)

            $file = $_FILES['frågabild' . ($i + 1)];

            $fileTempName = $_FILES['frågabild' . ($i + 1)]['tmp_name'];
     //sparar bildfil och information om bilden i databas
            include('dbconnection.php');
            $sql = "INSERT INTO bilder ( parent_id , parent_db, date) 
            VALUES (?, ?, now())";
            $stmt = $dbconn->prepare($sql);
            $data = array($frågaId, 'frågaBild');
            $stmt->execute($data);
            $last_id = 'bild' .  $dbconn->lastInsertId();
            $fileDest = './assets/quizBilder/' . $last_id . '.jpg';
                
            if (move_uploaded_file( $_FILES['frågabild' . ($i + 1)]["tmp_name"], $fileDest)) {
    
              } else {
                echo "error";
              }
        }
     //sparar alla svar
        for ($j=0; $j < count($_POST['svar'][$i]); $j++) { 
            if($_POST['rättSvar'][$i][0] == $j){
                sparaSvar($frågaId, $_POST['svar'][$i][$j], 1);
            }else{
                sparaSvar($frågaId, $_POST['svar'][$i][$j], 0);
            } 
        }
    }   

    //sparar bakrubnd bild om det skickas in (ej obligatoriskt)
    if(isset($_FILES['bakrundsbild'])){

        $file = $_FILES['bakrundsbild'];

        $fileTempName = $_FILES['bakrundsbild']['tmp_name'];
 //sparar bildfil och information om bilden i databas
        include('dbconnection.php');
        $sql = "INSERT INTO bilder ( parent_id , parent_db, date) 
        VALUES (?, ?,now())";
        $stmt = $dbconn->prepare($sql);
        $data = array($quizId, 'quizBild');
        $stmt->execute($data);
        $last_id = 'bild' .  $dbconn->lastInsertId();
        $fileDest = './assets/quizBilder/' . $last_id . '.jpg';   
        if (move_uploaded_file($_FILES["bakrundsbild"]["tmp_name"], $fileDest)) {

          } else {
            echo "error";
          }
    }

    echo('quiz skapat!<br>    
        <a href="index.php">tillbaks till start</a>
    ');
}else{

?>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">

<p>quiznamn <input type="text" name="quiznamn" value="" required></p>

<div>bakrundsbild</div><input type="file" name="bakrundsbild">

<div id='frågor'></div>

<button type="button" id="läggtillfråga">Lägg till en fråga</button><br>
<button type="submit">Skapa quiz</button>

</form>

<script>

document.getElementById('läggtillfråga').addEventListener('click', () => {
    
nyFråga();

})

var frågeIdIndex = 0;
var antalfrågor = {};

function nyFråga(){//funktion skapar frågadatan 

    antalfrågor[frågeIdIndex] = 1;
    Fråga();
    frågeIdIndex++;
}

function Fråga(){//ritar ut UI för att skapa en fråga 

    var frågaHTML = document.createElement('DIV');
    frågaHTML.classList = "frågaContainer ";

    if(frågeIdIndex % 2 == 0){//varannan fråga har grå bakrund för tydlighet

        frågaHTML.classList += "grå ";
    }
    //fråga input ----
    frågaInput = document.createElement('P');
    frågaInput.innerHTML = 'fråga ' + (frågeIdIndex + 1)+ ' <input type="text" name="frågor[]" required>';
    frågaHTML.innerHTML += '<br>frågabild <input name="frågabild' + (frågeIdIndex + 1) + '"  type="file">';
    frågaHTML.appendChild(frågaInput);
    document.getElementById('frågor').appendChild(frågaHTML);
    //fråga input ----

    //knapp för att ta bort frågan
    deleteBtn = document.createElement('BUTTON');
    deleteBtn.innerHTML = ' ta bort fråga ';
    frågaHTML.appendChild(deleteBtn);
    deleteBtn.addEventListener('click', () => {

        frågaHTML.remove();
        frågeIdIndex--;
    });

    //lägg till svars alternmativ i fråga ui
    läggTillSvarAlt = document.createElement('BUTTON');
    läggTillSvarAlt.type = "button";
    läggTillSvarAlt.innerHTML = "Lägg till ett svarsalternativ";
    frågaHTML.appendChild(läggTillSvarAlt);

    var frågeIdIndexSec = frågeIdIndex;

    läggTillSvarAlt.addEventListener('click', () => {

        nySvarsRad(frågeIdIndexSec, 5, frågaHTML);
    });
  
  //lägger till 3 svarsrader som standard
    for (let i = 0; i < 3; i++) {
        
        nySvarsRad(frågeIdIndex, i, frågaHTML);
    }
}
//ritar ut en svarsalternativrad, med knapp för att ta bort den om användaren ändrar sig.
function nySvarsRad(frågeIndexIdc, svarsId, container){

    radContainer = document.createElement('P');

    deleteBtn = document.createElement('DIV');
    deleteBtn.classList = 'deleteBtn ';
    deleteBtn.innerHTML = ' X ';

    var radCont = radContainer;
    deleteBtn.addEventListener('click', () => {

        radCont.remove();
    });

    svarnr = document.createElement('DIV');
    svarnr.innerHTML = 'svar ' + antalfrågor[frågeIndexIdc];
    svarnr.classList += ' kort';
    radContainer.appendChild(svarnr);

    textInput = document.createElement('INPUT');
    textInput.type = 'text';
    textInput.innerHTML = 'svar ' + antalfrågor[frågeIndexIdc];
    textInput.name = 'svar[' + frågeIndexIdc + '][]';
    textInput.required = true;

    radContainer.appendChild(textInput);

//radio knapp för att anändaren ska kunna välja vilket svar som är  rätt
    rättSvarInput = document.createElement('INPUT');
    rättSvarInput.type = 'radio';
    rättSvarInput.name = "rättSvar[" + frågeIndexIdc + "][]";
    rättSvarInput.value = svarsId;
    rättSvarInput.required = true;

    radContainer.appendChild(rättSvarInput);    
    radContainer.appendChild(deleteBtn);
    container.appendChild(radContainer);   
    antalfrågor[frågeIndexIdc]++; 
}
//skapar en fråga (ui) automatiskt som standard
nyFråga();

</script>

<?php

}
?>