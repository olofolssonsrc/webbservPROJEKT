<?php 
session_start();
include 'Auth.php';
if(!Auth()){
  LoggaUt();
}
//startsida som kan inkludera olika sidor 
?>
<body class="body">
<style>
a{

text-decoration:none;

}
.grid-container {
    width:60%;
   height:80%;

  display: grid;
  grid-template-columns: 15% 30% 30% 15%;
  grid-template-rows: 20% auto auto;
  gap: 2px 2px;
  grid-template-areas:
    "header header header header"
    "left main main right"
    "footer footer footer footer";
   position:absolute;
   margin:0%;
   left: 50%; 
   transform: translate(-50%, 0);
}
.header { 
    grid-area: header; background-color:skyblue; 
    text-align:center;
    border:blue 2px solid;
    border-radius: 10px;
}
.left {
    padding-top:20px;
    text-align:center;
    border:gray 2px solid;
    border-radius: 10px;
    grid-area: left; background-color:lightgray;
 }
.right {
    padding-top:20px;
    text-align:center;
    border:gray 2px solid;
    border-radius: 10px;
    grid-area: right; background-color:lightgray;
 }
.main { 
    grid-area: main;  background-color:white;
    border:gray 2px solid;
    border-radius: 10px;
    overflow : scroll;
}
.footer { 
    grid-area: footer; background-color:lightgray;
    padding: 40px;
    border:gray 2px solid;
    border-radius: 10px;
 }

.body{
    font-family:'arial';
}

</style>

<div class="grid-container">
  <div class="header"><h1>QUIZ.SE</h1><a href="https://github.com/olofolssonsrc/webbservPROJEKT">källkod och README fil</a> </div>
  <div class="left">
  <a href="index.php?flöde=view">Flöde</a><br>
<a href="index.php?kontosida=view">Din sida</a><br>
<a href="index.php">Hitta quiz</a><br>

<a href="skapaquiz.php">Skapa quiz</a><br>
</div>
  <div class="right"> 
  
  <?php 

if(!Auth()){
    ?>
    <a href="login.php">Logga in</a> eller <a href="signup.php">Skapa konto </a>för att kunna skapa quiz, spara statestik och gilla/ogilla quiz.<br><br>
    
    <?php 
}else{
    ?>        
       <a href="logout.php">Logga ut</a>
        <div>inloggad som <?php echo($_SESSION['username']); ?></div>

      <?php

        if(Admin_Auth()){
          echo('<a href="admin.php">Admin funktioner</a>');
        }
        
      ?>
    <?php
}
?>
  </div>
  
  <div class="main">


<?php

  if(isset($_GET['viewQuiz']))
  {
    include('viewquiz.php');
  }else if(isset($_GET['kontosida'])){
    include('kontosida.php');
  }else if(isset($_GET['flöde'])){
      if(isset($_GET['kommentarer']))
      {
        include 'flödeTYPER/kommentarer.php';
      }else if(isset($_GET['likesdislikes'])){

        include 'flödeTYPER/likesdislikes.php';
      }else if(isset($_GET['quiz'])){
          include 'flödeTYPER/genomfördaquiz.php';
      }else{
          include 'flödeTYPER/genomfördaquiz.php';
      }
  }else if(isset($_GET['viewkonto'])){
    include('viewkonto.php');
  }else{
    include('recomendedQuiz.php');
  }
   
?>
  </div>
  <div class="footer">skapad av olof 18te</div>
</div>


</body>
<?php


function LoggaUt(){
  $_SESSION = array();
          
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }
  
  session_destroy();

}



?>
<?php
?>
