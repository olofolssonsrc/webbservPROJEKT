<style>

.FlödeInfbody{

    padding : 20px;
}


.MainHeader{

    text-decoration: underline;
}

</style>
<div class="FlödeInfbody">
<h2 class="MainHeader">Aktuella händelser från konton du följer</h2><br>
<a href="../quiz2.0/index.php?flöde=view&kommentarer=view"><strong>kommentarer</strong></a>
<a href="../quiz2.0/index.php?flöde=view&likesdislikes=view">likesdislikes</a>
<a href="../quiz2.0/index.php?flöde=view&quiz=view">quiz</a>
<?php




if(isset($_GET['kommentarer']))
{
  include 'flödeTYPER/kommentarer.php';
}else if(isset($_GET['likesdislikes'])){

  include 'flödeTYPER/likesdislikes.php';
}else if(isset($_GET['quiz'])){
    include 'flödeTYPER/genomfördaquiz.php';
}

?>

