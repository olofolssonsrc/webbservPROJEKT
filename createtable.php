<!-- createtable.php -->
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create</title>
</head>

<body>
<?php
include ('dbconnection.php');
try {

    // sql to create table
    $sql = "CREATE TABLE quizHistorik (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userid INT(6) NOT NULL,
    date DATETIME
    )";

    // use exec() because no results are returned
    $dbconn->exec($sql);
    echo "Table created successfully";
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
}

//Rensa kopplingen till databasen
$dbconn = null;

?>
</body>
</html>