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

function skapaKomenteraKnapp(parent_id, parent_db){

var knapp = document.createElement('BUTTON');
knapp.innerHTML = " kommentera ";
knapp.addEventListener('click', () => {

    nyKommentar(parent_id, parent_db);
});
return knapp;
}

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

    deleteBtn = document.createElement('DIV');
    deleteBtn.classList = 'deleteBtn ';
    deleteBtn.innerHTML = ' X ';

    ruta.appendChild(deleteBtn);

    deleteBtn.addEventListener('click', () => {

        ruta.remove();
    });

    kommenteraKnapp.addEventListener('click', () => {

        var text = textbox.value;
        var user = <?php echo('"' . $_SESSION['username'] . '"');?>

        get('text=' + text + '&parent_id=' + parent_id + '&parent_db=' + parent_db, 'insertKommentar.php').then(() => {
            
            document.getElementById('kommenteraRuta').remove();
            if(parent_db == "quiz"){
                getKommentarer(document.getElementById('kommentarFält'), parent_id, parent_db)
            }else{
                getKommentarer(document.getElementById(parent_id), parent_id, parent_db)
            }
            
        });
    })
}

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
    
getKommentarer( document.getElementById('kommentarFält'), <?php echo($_GET['viewQuiz']);?>, "quiz");

function insertAfter(el, referenceNode) {
            referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);  
        }

function getKommentarer(htmlTAG, parent_id, parent_db){

    var underKommentarer = document.getElementsByClassName('parentid' + parent_id);
            for (let i = 0; i < underKommentarer.length; i++) {
                        
            underKommentarer[i].remove();        
    }     

    get('parent_id=' + parent_id + '&parent_db=' + parent_db, 'kommentarer.php').then((kommentarer) => {
       
    var newEl = document.createElement('div');
    newEl.innerHTML = kommentarer;
    newEl.style.marginLeft = 30 + "px";
    newEl.classList += (" parentid"+parent_id);
    var ref = htmlTAG.parentNode;
    insertAfter(newEl, ref);

        updateListeners();
    });
}
function visaKommentarer(kommentarObjekt){
  
    kommentarObjekt.status = "dölj svar";
    kommentarObjekt.innerHTML = "dölj svar";
    getKommentarer( kommentarObjekt, kommentarObjekt.id, "kommentarer");
}

function döljKommentarer(kommentarObjekt){

    kommentarObjekt.status = "visa svar"
    kommentarObjekt.innerHTML = "visa svar";

    var underKommentarer = document.getElementsByClassName('parentid' + kommentarObjekt.id);
    for (let i = 0; i < underKommentarer.length; i++) {
                        
        underKommentarer[i].remove();        
    }     
}

function updateListeners(){

var kommentarObjektse = document.getElementsByClassName('kommentarObjektSeSvar');
    for (let i = 0; i < kommentarObjektse.length; i++) {
        
        if(!kommentarObjektse[i].hasListener){

            kommentarObjektse[i].hasListener = true;
            kommentarObjektse[i].addEventListener('click', () => {
                
                if(kommentarObjektse[i].status == "dölj svar"){
                    döljKommentarer(kommentarObjektse[i]);
                }else{
                    visaKommentarer(kommentarObjektse[i]);
                }
            });
        }
    }  

var kommentarObjekt = document.getElementsByClassName('kommentarObjektKommentera');
    for (let i = 0; i < kommentarObjekt.length; i++) {
        
        kommentarObjekt[i].addEventListener('click', () => {

            nyKommentar( kommentarObjekt[i].id, "kommentarer");
        });
    }  
}

initiera();
function initiera(){
    document.getElementById('kommentarFält').appendChild( skapaKomenteraKnapp(<?php echo($_GET['viewQuiz']);?>, "quiz"));
    getKommentarer( document.getElementById('kommentarFält'), <?php echo($_GET['viewQuiz']);?>, "quiz");
   
}


</script>
<br>
<?php


?>

</div>

<script>


</script>

