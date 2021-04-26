<?php


if(!Auth()){

    header('Location: login.php');
}
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

?>

