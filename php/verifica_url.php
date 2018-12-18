<?php #PROCEDURA VERIFICA PRESENZA PAGINA DA ATTACCARE

if (isset($_POST['urlXSS'])) {

  # Definisco il link da controllare
  $link = $_POST['urlXSS'];

  flush();

  # Uso fopen per aprire il file specificato nella variabile $link
  $fp = @fopen($link, "r");

  # Se il file NON esiste stampo il messaggio di errore...
  if (!$fp) {
    echo "La pagina non è accessibile...";
  }
  # ...altrimenti do conferma!
  else {
    fclose($fp);
    echo "La pagina è accessibile...";
  }
}
?>
