$(document).ready(function() {

  //al click sul bottone
  $("#btnDOM").click(function(){
    //Scrivo nei log cosa sto facendo
    document.getElementById("log").innerHTML = "Sto estraendo il DOM..."+"\n\n" + document.getElementById("log").innerHTML;

    //Verifichiamo che il browser support gli storage
    if (typeof(Storage) !== "undefined") {
      var urlXSS = sessionStorage.getItem('url');
    }
    else {
      alert("Il tuo browser non supporta gli storage...");
      var urlXSS = $("#urlXSS").val();
    }


    //chiamata ajax
    $.ajax({
      //imposto il tipo di invio dati (GET O POST)
      type: 'POST',
      //Dove devo inviare i dati recuperati
      url: 'php/estrazione_dom.php',
      //Dati da inviare
      data: 'urlXSS=' + urlXSS,
      dataType: 'html',

      //Inizio visualizzazione successo o errore
      success: function(msg)
      {

        if(msg == "Errore nella lettura del DOM..."){
          document.getElementById("btnvisDOM").disabled=true;
          document.getElementById("btnVUL").disabled=true;
          $("#log").html(msg+"\n"+$("#log").val()); // Messaggio di errore lettura DOM
        }
        else {
          $("#log").html("Estrazione DOM avvenuta con successo..."+"\n"+$("#log").val()); // Messaggio di avvenuto controllo del URL
          $("#modaltext").html(msg);
          document.getElementById("btnvisDOM").disabled=false;
          document.getElementById("btnVUL").disabled=false;

        }
      },
      error: function()
      {
        $("#log").html("Errore nella funzione php"); //Caso in cui la funzione da errore
      }
    });
    return false;
  });
});
