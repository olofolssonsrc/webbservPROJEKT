<style>

.kommentar{
    width: 85%;
}

.deleteBtn{

color:red;
width: 20px;
display:inline;
}

.kommenteraRuta{

border-radius: 15px;
border : 3px solid blue;
position: absolute;
  width: 20%;
  height: auto;
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
/**
Den här filen ansvarar för att hämta och skicka kommentarer och sortera dem på rätt sätt i kommentarsfältet
*/


//funktion som skapar en knapp för att öppna ett kommentera fönster 
function skapaKomenteraKnapp(parent_id, parent_db){

var knapp = document.createElement('BUTTON');
knapp.innerHTML = " kommentera ";
knapp.addEventListener('click', () => {

    nyKommentar(parent_id, parent_db);
});
return knapp;
}

//funktion som öppnar ett fönster där man kan skriva in en ny kommentar och skicka
function nyKommentar(parent_id, parent_db){
    if(document.getElementById('kommenteraRuta')){ //tar bort om det redan finns en kommentera ruta
        document.getElementById('kommenteraRuta').remove();
    }
    var ruta = document.createElement('DIV');
    ruta.classList = "kommenteraRuta";
    ruta.id = 'kommenteraRuta';
    var textbox = document.createElement('INPUT');
    var kommenteraKnapp = document.createElement('BUTTON');
    kommenteraKnapp.innerHTML = "Kommentera";
    ruta.appendChild(textbox);
    ruta.appendChild(kommenteraKnapp);
    document.body.appendChild(ruta);

    deleteBtn = document.createElement('DIV'); //lägger till stäng ner knapp
    deleteBtn.classList = 'deleteBtn ';
    deleteBtn.innerHTML = ' X ';

    ruta.appendChild(deleteBtn);

    deleteBtn.addEventListener('click', () => {

        ruta.remove();
    });

    kommenteraKnapp.addEventListener('click', () => {

        var text = textbox.value;
        var user = <?php echo('"' . $_SESSION['username'] . '"');?>

        //skickar en get request med komenterade objektets tablename och id och kommentarinnehåpllet
        get('text=' + text + '&parent_id=' + parent_id + '&parent_db=' + parent_db, 'insertKommentar.php').then(() => {
            
            document.getElementById('kommenteraRuta').remove();

            if(parent_db == "quiz"){//skriver ner nya kommentaren + alla tidigare kommentarer på objektet(ifall de inte visades inann)
            
                getKommentarer(document.getElementById('kommentarFält'), parent_id, parent_db);
            }else{


                visaKommentarer(document.getElementById(parent_id));
            }
        });
    })
}
//get request funktion
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
            });
       } 


//funktion för att appenda ett HTML direkt efter ett annat html element. (Kommentaren som gjordes på en annan kommentar borde hamna direkt under den) 
function insertAfter(el, referenceObj) {
          referenceObj.parentNode.insertBefore(el, referenceObj.nextSibling);  
    }

//hämtar alla kommentarer på ett objekt och skriver in dem under htmlTAG
function getKommentarer(htmlTAG, parent_id, parent_db){

    var underKommentarer = document.getElementsByClassName('parentid' + parent_id);
            for (let i = 0; i < underKommentarer.length; i++) {
                        
            underKommentarer[i].remove();        
    }     

    get('parent_id=' + parent_id + '&parent_db=' + parent_db, 'kommentarer.php').then((kommentarer) => {
       
    var newEl = document.createElement('div');
    newEl.innerHTML = kommentarer;
    newEl.style.marginLeft = 30 + "px";
    newEl.classList += ("kommentarOBJECT parentid"+parent_id);
    var ref = htmlTAG.parentNode;
    insertAfter(newEl, ref);
    });
}

//hämtar alla kommentarer till ett objekt och togglar kommentarer obejektets visa/dölja status
function visaKommentarer(kommentarObjektButtonId){
    kommentarObjektButton  = document.getElementById(kommentarObjektButtonId);
    getKommentarer( kommentarObjektButton, kommentarObjektButton.id, "kommentarer");
    var index = kommentarObjektButton.innerHTML.indexOf('(');
    var antal = kommentarObjektButton.innerHTML.substring(index + 1, index + 2);
    kommentarObjektButton.innerHTML = "dölj svar (" + antal + ")";

    kommentarObjektButton.status = "dölj svar";

}
//raderar underkomentarerna till ett objekt och togglar kommentarer obejektets visa/dölja status
function döljKommentarer(kommentarObjektButtonId){

    kommentarObjektButton  = document.getElementById(kommentarObjektButtonId);

    var index = kommentarObjektButton.innerHTML.indexOf('(');
    var antal = kommentarObjektButton.innerHTML.substring(index + 1, index + 2);
    kommentarObjektButton.innerHTML = "visa svar (" + antal + ")";

    kommentarObjektButton.status = "visa svar";

    var underKommentarer = document.getElementsByClassName('parentid' + kommentarObjektButton.id);
    for (let i = 0; i < underKommentarer.length; i++) {
                        
        underKommentarer[i].remove();        
    }
}


//hämtar quizkommentarer och lägger till kommentera knapp för quizet.

function initiera(){
    document.getElementById('kommentarFält').appendChild( skapaKomenteraKnapp(<?php echo($_GET['viewQuiz']);?>, "quiz"));
    getKommentarer( document.getElementById('kommentarFält'), <?php echo($_GET['viewQuiz']);?>, "quiz");
   
}
initiera();

</script>
<br>
<?php


?>

</div>

<script>


</script>

