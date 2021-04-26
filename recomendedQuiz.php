<style>
.viewQuizInfbody{

padding : 20px;
}

.parent {
display: grid;
grid-template-columns: repeat(2, 1fr);
grid-template-rows: repeat(3, 1fr);
grid-column-gap: 0px;
grid-row-gap: 0px;
}

.div1 { grid-area: 1 / 1 / 2 / 2; }
.div2 { grid-area: 2 / 1 / 3 / 2; }
.div3 { grid-area: 3 / 1 / 4 / 2; }
.div4 { grid-area: 1 / 2 / 4 / 3; }

</style>
<div class="viewQuizInfbody">
<div class="parent">
  <?php

//länkar till  quiz sorterat efter olika saker.

//sorterat efter popularitet
echo('
<div class="div1"> <h4>Populära quiz</h4> <ol> 
');

  include('dbconnection.php');
  $sql = "SELECT * FROM quiz
  ORDER BY gamesPlayed DESC LIMIT 5";
        
  $stmt = $dbconn->prepare($sql);
  $data = array();  
  $stmt->execute($data);
  $res = $stmt->fetchAll();

  for ($i=0; $i < 5; $i++) { 
    if(isset($res[$i])){
      echo('<li><a href="?viewQuiz=' .  $res[$i]['id'] . '">' . $res[$i]['namn'] . '</a>');
    }  
  }

  echo('
  </ol></div>
  ');


//sorterat efter gillningar
  echo('
<div class="div2"><h4>Quiz med flest gillningar</h4> <ol>
  ');
//sorterar efter likes
  $sql = ' SELECT parent_id, 
  COUNT(*) likes
  FROM likesdislikes 
  WHERE likeStatus = "LIKE" AND parent_db = "quiz"
  GROUP BY parent_id
  ORDER BY likes DESC, parent_id
  LIMIT 5';

  $stmt = $dbconn->prepare($sql);
  $data = array();  
  $stmt->execute($data);
  $res1 = $stmt->fetchAll();

  for ($i=0; $i < 5; $i++) { //skriver ut top 5 quiz
    if(isset($res1[$i])){

      $sql = "SELECT * FROM quiz WHERE id = " . $res1[$i]['parent_id'];

      $stmt = $dbconn->prepare($sql);
      $data = array();  
      $stmt->execute($data);
      $res2 = $stmt->fetchAll();

      echo('<li><a href="?viewQuiz=' .  $res1[$i]['parent_id'] . '">' . $res2[0]['namn'] . '</a>');
    }  
  }
  
    echo('
    </ol> </div>
    ');

//--


//sorterat efter nyast quiz 
echo('
<div class="div3"> <h4>Senast tillagda quiz</h4> <ol>
');

  include('dbconnection.php');
  $sql = "SELECT * FROM quiz
  ORDER BY date DESC LIMIT 5";
        
  $stmt = $dbconn->prepare($sql);
  $data = array();  
  $stmt->execute($data);
  $res = $stmt->fetchAll();

  for ($i=0; $i < 5; $i++) { 
    if(isset($res[$i])){
      echo('<li><a href="?viewQuiz=' .  $res[$i]['id'] . '">' . $res[$i]['namn'] . '</a>');
    }  
  }

  echo('
  </ol></div>
  ');



//alla quiz i bokstavsordning
  
echo('
<div class="div4"> <h4>Alla quiz</h4> <ol>
');

  include('dbconnection.php');
  $sql = "SELECT * FROM quiz ORDER BY namn ASC";
        
  $stmt = $dbconn->prepare($sql);
  $data = array();  
  $stmt->execute($data);
  $res = $stmt->fetchAll();

  for ($i=0; $i < count($res); $i++) { 
    if(isset($res[$i])){
      echo('<li><a href="?viewQuiz=' .  $res[$i]['id'] . '">' . $res[$i]['namn'] . '</a>');
    }  
  }

  echo('
  </ol></div>
  ');

?>
</div>

</div>


