<?php 
session_start();
//loginsida

include 'Auth.php';
if(Auth() == true){//om användaren redan är inlåggad skickas den till index.php
  
    echo('Du redan inloggad!<br>');
    echo('<a href="index.php">Startsida</a>');
}else{

    ?>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <p>username</p><input type="text" name="username" value="" required>
    <p>lösenord</p><input type="text" name="password" value="" required>
    <button type="submit">Logga in</button>
    </form>
    <?php 
     
    include('dbconnection.php');
    
    if(isset($_POST['username'])&&isset($_POST['password'])){
    
        $inputUsername = htmlspecialchars($_POST['username']);
        $inputPassword = htmlspecialchars($_POST['password']);
    
      //  $hashinputPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    

        $sql = "SELECT * FROM users WHERE username = '$inputUsername'";
    
        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();
    
        if(!$res){
    
            echo('Det finns inget konto med det användarnamnet<br>');
            echo('<a href="signup.php">Skapa konto</a>');
        }else{
            $password = $res[0]['password'];
            $bannad = $res[0]['bannad'];
            if(password_verify($inputPassword, $password)){
        
                if($bannad == 1){
                    echo('Ditt konto har blivit avstängt av en administratör<br>');
                    echo('<a href="index.php">Startsida</a>');
                }else{
                    $_SESSION['username'] =  $inputUsername;
                    $_SESSION['userId'] =  $res[0]['id'];
                    header('Location: index.php');
                }
            }else{
        
            echo('fel lösenord ');  
            }   
        }  
    }
}
    ?>
    
    
