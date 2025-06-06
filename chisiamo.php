<?php
  require("includes/common.php");

  $header["title"] = "Chi Siamo";

  $header["meta"]["description"] = "Vuoi acquistare online in modo semplice, veloce e soprattutto al miglior prezzo? EShoppingItaly.com è la soluzione giusta per te!";

  $header["meta"]["keywords"] = "eShopping Italy, Offerte Online ";

  require("html/header.php"); 

  require("html/searchform.php");
  
  require("html/menu.php");

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>"Chi Siamo","href"=>$config_baseHREF."chisiamo.php");

  require("html/banner.php");
?>

<div class='row'>

  <div class='small-12 columns'>

    <p>Vuoi acquistare online in modo semplice, veloce e soprattutto al miglior prezzo? EShoppingItaly.com è la soluzione giusta per te!
Ogni giorno per te migliaia di offerte imperdibili dei i migliori brand di Moda, Prodotti tipici, elettronica, telefonia, abbigliamento e cosmetica.
eShoppingItaly.com è uno dei principali comparatori di prezzi in Italia e ha lo scopo di accompagnarti nella ricerca del prodotto che desideri al miglior prezzo di sempre.</p>
<p>Con eShoppingItaly.com potrai cercare il tuo prodotto, confrontare i prezzi e acquistare in maniera facile e veloce, scegliendo tra migliaia di offerte quella più adatta a te.</p>
<p>L’obiettivo è quello di garantire la migliore esperienza d’acquisto in totale sicurezza e ai prezzi più vantaggiosi del web.
Qualità e risparmio saranno gli unici criteri di ordinamento dei prodotti, nonché il valore aggiunto che fa di EShoppingItaly.com uno dei i comparatori più efficienti per i tuoi acquisti online.</p>
<p>Insomma, cosa aspetti? Consulta subito le nostre offerte!
</p>

  </div>

</div>

<?php
  require("html/footer.php");
?>