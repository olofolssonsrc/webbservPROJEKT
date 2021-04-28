<?php 
include('dbconnection.php');

session_start();
//starta konto fil
if(isset($_SESSION['username'])){

    header('Location: index.php');
}

if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['epost'])){ //kollar om all data skickats

 $aktiveringKod = uniqid();//genererar aktiveringskod
 //sparat informationen i temporär databas
  $sql = "INSERT INTO inactusers (username, password, email, aktivering_kod, date) 
  VALUES (?, ?, ?, ?, now())";
  $stmt = $dbconn->prepare($sql);
  $data = array(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['epost']), $aktiveringKod);
  $stmt->execute($data);

  echo('Ditt konto är nu registrerat! För att aktivera ditt konto tryck på länken i epost medelandet skickat till ' . $_POST['epost']);

  $meddelande = '<a href=http://labb.vgy.se/~olofon/projekt/quiz2.0/signup.php?aktivera=' . $aktiveringKod .'">Klicka här för att aktivera konto</a>';
  $mottagare = $_POST["epost"];
  $rubrik="Quiz kontoaktivering";
  $mejlhuvud="From: ".$_POST["epost"]." \nReply-To: kalle@anka.se";

//skickar mail med aktiverings länk
  mail($mottagare, $rubrik, $meddelande, $mejlhuvud);

}else if(isset($_GET['aktivera'])){//om användare klickade på aktiveringeposten

  $sql = 'SELECT * FROM inactusers WHERE aktivering_kod = "' . $_GET['aktivera'] . '"';
  
  $stmt = $dbconn->prepare($sql);
  $data = array();  
  $stmt->execute($data);
  $res = $stmt->fetchAll();

  if (count($res) > 0){//kollar om det var en korrekt aktiveringskod och sparar kontot på den permanenta databasen så att kontot nu fungerar

    $sqlresKONTROL = 'SELECT * FROM users WHERE username = "' . $res[0]['username'] .'"';
  
    $stmtresKONTROL = $dbconn->prepare($sqlresKONTROL);
    $dataresKONTROL = array();  
    $stmtresKONTROL->execute($dataresKONTROL);
    $resKONTROL = $stmtresKONTROL->fetchAll();

    if(!isset($resKONTROL[0])){
      $sql = "INSERT INTO users (username, password, epost, admin, bannad, reg_date) 
      VALUES (?, ?, ?, ?, ?, now())";
      $stmt = $dbconn->prepare($sql);
      $data = array($res[0]['username'], password_hash($res[0]['password'], PASSWORD_DEFAULT), $res[0]['email'], 0, 0);
      $stmt->execute($data);      
  
        echo('<h1>Hej ' . $res[0]['username'] . '! välkommen till quiz.se!</h1>');
        echo('<a href="login.php">Start</a>');
    }else{
      header('Location: login.php');
    }
  }else{
    echo('Denna länken är inkorrekt.<br><a href="signup.php">Klicka här för att skapa ett nytt konto.</a>');
  }

}
else{
?>
  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

  <p>username</p><input type="text" name="username" value="" required>
  <p>email</p><input type="text" name="epost" value="" required>
  <p>password</p><input type="text" name="password" value="" required>
  <!--<p>admin true/false</p><input type="text" name="adminstatus" value="">-->
  <button type="submit">Skapa konto</button>
  
  </form>

  <a href="login.php">Logga in</a>
<?php
}

$dbconn = null;

?>
