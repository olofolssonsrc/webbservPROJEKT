<style>

.likedislike{
    background-Color : lightgray: 
   border:2px solid gray;
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
    background-Color : #9999FF;
}
.ogilla{
    background-Color : #FF9999;
}

</style>
<?php

//filen används inte

if(isset($_SESSION['username'])){
    if(isset($_GET['viewQuiz'])){
        $quizId =  $_GET['viewQuiz'];
    }else if(isset($_GET['id'])){
        $quizId =  $_GET['id'];
    }
   
                    
$sql = 'SELECT * FROM likesdislikes WHERE userid = ' . $_SESSION['userId'] .' AND quizid = ' . $quizId;

$stmt = $dbconn->prepare($sql);
$data = array();  
$stmt->execute($data);
$res = $stmt->fetchAll();

if($stmt->rowcount() > 0){

        if($res[0]['likeStatus'] == 'LIKE'){
            ?>
            <button type="button" class="likedislike gilla" id="gillaBtn">Gilla quiz<span class="emoji">&#x1F44D;</span></button>
            <button type="button" class="likedislike" id="ogillaBtn">Ogilla quiz<span class="emoji">&#x1F44E;<span></button>
        <?php
        }else{
            ?>
            <button type="button" class="likedislike" id="gillaBtn">Gilla quiz<span class="emoji">&#x1F44D;</span></button>
            <button type="button" class="likedislike ogilla" id="ogillaBtn">Ogilla quiz<span class="emoji">&#x1F44E;<span></button>
        <?php
        }
}else{
    ?>
        <button type="button" class="likedislike" id="gillaBtn">Gilla quiz<span class="emoji">&#x1F44D;</span></button>
        <button type="button" class="likedislike" id="ogillaBtn">Ogilla quiz<span class="emoji">&#x1F44E;<span></button>
    <?php
}
   ?>
    <script>
      
        document.getElementById('gillaBtn').addEventListener('click', () => {      
                       
            get('LIKESTATUS=LIKE&quizid=<?php echo("$quizId"); ?>', 'insertlike.php').then(() => {
                document.getElementById('gillaBtn').classList += ' gilla'
                document.getElementById('ogillaBtn').classList = 'likedislike';
           })             
        });

        document.getElementById('ogillaBtn').addEventListener('click', () => {      
           
            get('LIKESTATUS=DISLIKE&quizid=<?php echo("$quizId"); ?>' , 'insertlike.php').then(() => {

                document.getElementById('ogillaBtn').classList += ' ogilla'
                document.getElementById('gillaBtn').classList = 'likedislike';
            })            
          })

       function get(data, location){
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

?>
    <button type="button" class="likedislike" id="gillaBtn">Gilla quiz<span class="emoji">&#x1F44D;</span></button>
    <button type="button" class="likedislike" id="ogillaBtn">Ogilla quiz<span class="emoji">&#x1F44E;<span></button>

    <script> 
     
     document.getElementById('gillaBtn').addEventListener('click', () => {      
                       
          alert('du måste skapa ett konto för att kunna gilla/ogilla!');    
    });
    document.getElementById('ogillaBtn').addEventListener('click', () => {      
                      
               alert('du måste skapa ett konto för att kunna gilla/ogilla!');                       
    })
    
    </script>
<?php
}
