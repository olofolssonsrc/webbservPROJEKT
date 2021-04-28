<style>

.likedislike{

   border:2px solid lightgray;
   border-radius:10%;
}
.likedislike:focus{
    outline:none;
}
.emoji{

    vertical-align: middle;
    line-height: 0;
}
.gilla{
    border:2px solid #9999FF;

}
.ogilla{
    border:2px solid #FF9999;
}

</style>
<?php 
//filen gör gilla/ogilla knappar till kommentarer/quiz
//färgen på knapparna är olika beroende på användarens gilla status.
//knapparna innehåller siffra som anger hur många gillningar och ogillningar

//lägger till onclick kod för varje gillaknapp. Om <script></script> kod hämtas med get request körs inte koden och inga Eventlisteners kan sättas på
//men scripten i onClick i html fungerar
function onClickString($parent_id, $parent_db, $gillaId, $ogillaId, $likeStatus){

if($likeStatus == 'DISLIKE'){
    
$str = "get('LIKESTATUS=" . $likeStatus . "&parent_id=" . $parent_id . "&parent_db=" . $parent_db . "','insertlike2.php').then(() => {

    document.getElementById('" . $ogillaId . "').classList += ' ogilla';
    document.getElementById('" . $gillaId . "').classList = 'likedislike';

  });";
}else{
    
$str = "get('LIKESTATUS=" . $likeStatus . "&parent_id=" . $parent_id . "&parent_db=" . $parent_db . "','insertlike2.php').then(() => {

    document.getElementById('" . $ogillaId . "').classList = 'likedislike';
    document.getElementById('" . $gillaId . "').classList += ' gilla';

  });";
}
 
    return $str;  
}


function gillaknappar($parent_id,  $parent_db, $html = ""){//parent_id är id på objektet som användaren gillar, tex id på ett specifikt quiz, $parent_db 
    //är en sträng som anger vilken databas objektet finns i.
if(isset($_SESSION['userId'])){

include 'dbconnection.php';//räknar alla likes
$sql = ' SELECT 
COUNT(*) likes
FROM likesdislikes 
WHERE likeStatus = "LIKE" AND parent_id = ' . $parent_id . ' AND parent_db = "' . $parent_db . '"';

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$resGillnmingar = $stmt->fetchAll();

 //räknar alla dislikes
$sql = ' SELECT
    COUNT(*) dislikes
    FROM likesdislikes 
    WHERE likeStatus = "DISLIKE" AND parent_id = ' . $parent_id . ' AND parent_db = "' . $parent_db . '"';

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$resOgillnmingar = $stmt->fetchAll();

$gillaId = uniqid();
$ogillaId = uniqid();//ger varje knapp ett unikt id för html elementet så att eventlisteners lyssnar på rätt knapp

//kollar om användaren har gillat/ogillat objeket innan
$sql = 'SELECT * FROM likesdislikes WHERE parent_id = ' . $parent_id .' AND parent_db = "' . $parent_db . '" AND userid = ' . $_SESSION['userId'];

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res = $stmt->fetchAll();

if($stmt->rowcount() > 0){//style om användaren har gillat/ogillat

        if($res[0]['likeStatus'] == 'LIKE'){//style om användaren har gillat
            ?>
           
<button type="button" onClick= "<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "LIKE"));?>" class="likedislike gilla" id="<?php echo($gillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44D;</span>');} echo($resGillnmingar[0][0]);?></button>
<button type="button" onClick= "<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "DISLIKE"));?>" class="likedislike" id="<?php echo($ogillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44E;</span>');} echo($resOgillnmingar[0][0]);?></button><br>

        <?php
        }else{//style om användaren har ogillat
            ?>
            
<button type="button" onClick= "<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "LIKE"));?>" class="likedislike" id="<?php echo($gillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44D;</span>');} echo($resGillnmingar[0][0]);?></button>
<button type="button" onClick= "<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "DISLIKE"));?>" class="likedislike ogilla" id="<?php echo($ogillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44E;</span>');} echo($resOgillnmingar[0][0]);?></button><br>

        <?php
        }
}else{//style om användaren inte har  gillat eller ogillat
    ?>
        
<button type="button" onClick= "<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "LIKE"));?>" class="likedislike" id="<?php echo($gillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44D;</span>');} echo($resGillnmingar[0][0]);?></button>
<button type="button" onClick="<?php echo(onClickString($parent_id, $parent_db, $gillaId, $ogillaId, "DISLIKE"));?>" class="likedislike" id="<?php echo($ogillaId);?>"><?php if(strlen($html)>0){echo($html);}else{echo('<span class="emoji">&#x1F44E;</span>');} echo($resOgillnmingar[0][0]);?></button><br>

    <?php
}
   ?>
<script>

function get(data, location){//get request funktion
            return new Promise((resolve) => {

               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
               if(this.readyState == 4 && this.status == 200){
                //om inte error
                    console.log(this.responseText)
                    resolve();
                }
            }

           xmlhttp.open("GET", location + '?' + data, true);
           xmlhttp.send();
            })
       } 
</script>
<?php
}else{
    echo(' <strong> Logga in för att gilla/ogilla </strong>');
}
}
?>