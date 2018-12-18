<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Cross Site Scripting Brute Force</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">

  </head>
  <body>
    <div class="container">
      <h1 class="text-center" id="title">
        Procedura HTML5 Per Cross Site Scripting
      </h1>
      <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Codice della pagina</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div id="modalDOM" class="modal-body">
              <p id ="modaltext"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <label class="lead" for="urlXSS">Inserire l'URL su cui si vorrebbe tentare l'attacco, preceduto dal protocollo : </label>
          <input type="text" class="form-control" id="urlXSS" name="urlXSS" placeholder="http://testphp.vulnweb.com/search.php">
          <hr>
          <input type="submit" id="verifica" name="verifica" class="btn btn-warning btn-lg btn-block" value="Verifica Accessibilità Url">
          <input  id="btnDOM" name="btnDOM" type="submit" disabled="disabled" value="Ottieni il DOM" class="btn btn-warning btn-lg btn-block">
          <input  id="btnvisDOM" name="btnvisDOM" type="submit" disabled="disabled" value="Visualizza il DOM" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#exampleModal">
          <input  id="btnVUL" name="btnVUL" type="submit" disabled="disabled" value="Ottieni possibili vulnerabilità" class="btn btn-warning btn-lg btn-block">
        </div>
        <div class="col-sm-6">
          <label class="lead" for="comment">Log :</label>
          <textarea  class="form-control" rows="20" id="log"></textarea>
        </div>
      </div>
    </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="ajax/ver_url.js"></script>
  <script src="ajax/estrai_dom.js"></script>
  <script src="ajax/ver_vulnerabilità.js"></script>
  </body>
</html>
