$(document).ready(function() {

  //al click sul bottone
  $("#verifica").click(function(){
    document.getElementById("log").innerHTML = "Verifico la pagina..."+"\n";
    //associo variabili
    var urlXSS = $("#urlXSS").val();
    var ulr_login = $("#url_login").val();
    var username_login = $("#username_login").val();
    var password_login = $("#password_login").val();
    var check_login = $('#check_login').prop('checked'); //True o False in base al valore della checkbox


    //Serve per verificare se il browser supporta gli storage
    if (typeof(Storage) !== "undefined") {
      sessionStorage.setItem('url',urlXSS);
    }
    else {
      alert("Il tuo browser non supporta gli storage...");
    }

    //chiamata ajax
    $.ajax({
      //imposto il tipo di invio dati (GET O POST)
      type: 'POST',
      //Dove devo inviare i dati recuperati
      url: 'php/verifica_url.php',
      //Dati da inviare
      data: 'urlXSS=' + urlXSS,
      dataType: 'html',

      //Inizio visualizzazione errori
      success: function(msg)
      {
        $("#log").html(msg+"\n"+$("#log").val()); // Messaggio di avvenuto controllo del URL
        if(msg=="La pagina è accessibile..."){
          document.getElementById("btnDOM").disabled=false;
          document.getElementById("btnvisDOM").disabled=true;
          document.getElementById("btnVUL").disabled=true;
        }
        else {
          document.getElementById("btnDOM").disabled=true;
          document.getElementById("btnvisDOM").disabled=true;
          document.getElementById("btnVUL").disabled=true;

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
