<?php 
//den här filen innehåller admin funktioner
include 'Auth.php';
if(Admin_Auth() == false){ //kollar om man är behörig till sidan
    echo('Du är inte inloggad eller så saknar ditt konto admin behörigheter<br>');
    echo('<a href="index.php">Startsida<a/><br>');
    echo('<a href="login.php">Logga in<a/>');
}else{
    
    if(isset($_POST['action']) && isset($_POST['username'])){
    
        include('dbconnection.php');

        $sql = "SELECT * FROM users WHERE username = " . $_POST['username'];

        $stmt = $dbconn->prepare($sql);
        $data = array();  
        $stmt->execute($data);
        $res = $stmt->fetchAll();

        if(!$stmt->rowcount() > 0){ //kollar om det finns en användare med användarnamnet som angavs
            echo('Det finns inget konto med det användarnamnet');
        }else{
            if($_POST['action'] == 'ban'){
    
                if($res[0]['bannad'] == 0){ //ändrar bannad till 1 
        
                    include('dbconnection.php');
                    $sql = "UPDATE users SET bannad = 1 WHERE username = " . $_POST['username'];
                    $stmt = $dbconn->prepare($sql);
                    $stmt->execute();
                    echo('Kontot med användarnamn' . $_POST['username'] . ' är nu avstängt<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                    
                }else{
                    echo('Kontot du försöker deaktivera är redan deaktiverat!<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                }
            }else if($_POST['action'] == 'admin'){//ändrar adminstatus till 1 
            
                if($res[0]['admin'] == 0){
                    include('dbconnection.php');
                    $sql = "UPDATE users SET admin = 1 WHERE username = " . $_POST['username'];
                    $stmt = $dbconn->prepare($sql);
                    $stmt->execute();
                    echo('Kontot med användarnamn' . $_POST['username'] . ' är nu ett adminkonto<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                }else{
                    echo('Kontot du försöker ändra är redan ett admin konto!<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                }
            }else if($_POST['action'] == 'unban'){//ändrar  bannad status till 0
            
                if($res[0]['bannad'] == 1){
                    include('dbconnection.php');
                    $sql = "UPDATE users SET bannad = 0 WHERE username = " . $_POST['username'];
                    $stmt = $dbconn->prepare($sql);
                    $stmt->execute();
                    echo('Kontot med användarnamn' . $_POST['username'] . ' är inte längre avstängt<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                }else{
                    echo('Kontot du försöker aktivera är redan aktiverat!<br>');
                    echo('<a href="admin.php">tillbaks till admin funktioner</a><br><a href="index.html">Startsida</a>');
                }
            }
        }
    }else{//om ingen request skickats visas form 
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    
        <p>Gällande konto : <input type="text" name="username" value="" required></p>
        
        <input type="radio" name="action" value="admin" required>Gör om kontot till adminkonto<br>
        <hr>
        <input type="radio" name="action" value="ban" required>Stäng av konto<br>
        <input type="radio" name="action" value="unban" required>Återaktivera avstängt konto<br><br>
        
        <button type="submit">Genomför</button>
        </form>
        
        <?php
    }
}

?>


