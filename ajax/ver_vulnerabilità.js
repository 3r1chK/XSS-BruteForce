$(document).ready(function() {

  //al click sul bottone
  $("#btnVUL").click(function(){
    //Scrivo nei log cosa sto facendo
    document.getElementById("log").innerHTML = "Sto cercando possibili vulnerabilità.."+"\n\n" + document.getElementById("log").innerHTML;

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
      url: 'php/vulnerabilità_dom.php',
      //Dati da inviare
      data: 'urlXSS=' + urlXSS,
      dataType: 'html',

      //Inizio visualizzazione successo o errore
      success: function(msg)
      {

        if(msg == "XSS non possibile..."){
          $("#log").html(msg+"\n"+$("#log").val()); // Messaggio di errore esecuzione script
        }
        else {
          $("#log").html(msg+"\n"+$("#log").val()); // Messaggio di avvenuta esecuzione script
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
