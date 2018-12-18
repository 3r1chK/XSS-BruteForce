<?php #PROCEDURA ESTRAZIONE DOM


if (isset($_POST['urlXSS'])) {
  $link = $_POST['urlXSS'];
  //Abbiamo messo @ davanti alla funzione in modo da evitare che essa dia errore
  //Cosi l'errore lo gestisco io
  $dom = @file_get_contents($link);
  if ($dom == false) {
    echo ("Errore nella lettura del DOM...");
  }
  else{
    $dom2 = htmlspecialchars(file_get_contents($link));//Stampa il contenuto del file con i suoi tag
    echo "<pre><code>".$dom2."</code></pre>";
  }
}
 ?>
