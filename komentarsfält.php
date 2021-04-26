<style>

.kommentar{
    width: 85%;
}

.kommenteraRuta{


position: absolute;
  width: 20%;
  height: 10%;
  z-index : 1;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align:center;
  padding : 10px;
  background-color: lightgray;
}

</style>

<div id="kommentarFält">

<script>

function skapaKomenteraKnapp(parent_id, parent_db){

var knapp = document.createElement('BUTTON');
knapp.innerHTML = "kommentera";
knapp.addEventListener('click', () => {

    nyKommentar(parent_id, parent_db);
});
return knapp;
}
document.getElementById('kommentarFält').prepend(skapaKomenteraKnapp(<?php echo($_GET['viewQuiz']);?>, "quiz"));
function nyKommentar(parent_id, parent_db){
    if(document.getElementById('kommenteraRuta')){
        document.getElementById('kommenteraRuta').remove();
    }
    var ruta = document.createElement('DIV');
    ruta.classList = "kommenteraRuta"
    ruta.id = 'kommenteraRuta';
    var textbox = document.createElement('INPUT');
    var kommenteraKnapp = document.createElement('BUTTON');
    kommenteraKnapp.innerHTML = "Kommentera";
    ruta.appendChild(textbox);
    ruta.appendChild(kommenteraKnapp);
    document.body.appendChild(ruta);

    kommenteraKnapp.addEventListener('click', () => {

        var text = textbox.value;
        var user = <?php echo('"' . $_SESSION['username'] . '"');?>

        get('text=' + text + '&parent_id=' + parent_id + '&parent_db=' + parent_db, 'insertKommentar.php').then(() => {
         
        //   document.getElementById('kommenteraRuta').remove();
          //  kommentar(text,  user);
        });

        window.location.replace(window.location.pathname + window.location.search + window.location.hash);
    })
}

function kommentar(text, user){
var date = new Date();
var dateString = date.getFullYear() + '-' +  (((date.getMonth() + 1) > 9) ? (date.getMonth() + 1) : ("0" + (date.getMonth() + 1))) + '-' + date.getDate();
var kommentar = document.createElement('DIV');
kommentar.innerHTML = '<strong>' + user + '</strong> ' + dateString + ' <br>' + text + '<br><br>';
document.getElementById('kommentarFält').prepend(kommentar);

}
</script>


<?php

if(isset($_GET['parent_id'])){
    $parent_id = intval($_GET['parent_id']);
    $parent_db = "kommentarer";
}else{
   
    $parent_id = intval($_GET['viewQuiz']);
    $parent_db = "quiz";
}

include 'dbconnection.php';

$sql = 'SELECT kommentar, userid, date, id
FROM kommentarer WHERE parent_id = '. $parent_id . ' AND parent_db = "' . $parent_db . '" ORDER BY date DESC';
$stmtl = $dbconn->prepare($sql);
$data = array();
$stmtl->execute($data);
$res = $stmtl->fetchAll();

for ($i=0; $i < count($res); $i++) { 
   
    $sql = 'SELECT username
    FROM users WHERE id = ' . $res[$i]['userid'];
    $stmtl = $dbconn->prepare($sql);
    $data = array();
    $stmtl->execute($data);
    $resUsername = $stmtl->fetchAll();

    echo('<div><strong>' . $resUsername[0]['username'] . '</strong> ' . $res[$i]['date'] . '<br>' . htmlentities($res[$i]['kommentar']));
    gillaknappar($res[$i]['id'], 'kommentarer');
    $HämtaSvarId = uniqid();
    echo('<div style="color:blue" id="' . $HämtaSvarId . '">Svar</div></div>');

    echo('<br>');
}

?>

</div>
<style>

.kommentar{
    width: 85%;
}

</style>
<script>
console.log('test');

 function get(data, location){
            return new Promise((resolve) => {

               var xmlhttp = new XMLHttpRequest();
               xmlhttp.onreadystatechange = function() {
               if(this.readyState == 4 && this.status == 200){

                    resolve(this.responseText);

                }
            }
          
           xmlhttp.open("GET", location + '?' + data, true);
        
           xmlhttp.send();
            })
       } 

</script>

