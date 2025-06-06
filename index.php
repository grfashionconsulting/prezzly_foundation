<?php
  require("includes/common.php");

  require("html/header.php");

  require("html/searchform.php");
  
  require("html/menu.php"); 
  
  require("html/homeslider.php");
  require("html/homecategorie.php");

 

 ?>
 
  
  <div class="row">
  <div class="large-12 columns">
  <div id="home">
  <h1>Profumeria</h1>
  <hr>
 <?php 
  $featuredSection["section"] = "profumi";
  require("featuredSection.php");  
  ?> 
  </div>
  </div>
  </div>
  <div class="row">
  <div class="large-12 columns">
  <div id="home">
  <h1>Auto e Moto</h1>
  <hr>
  <?php
  $featuredSection["section"] = "auto";
  require("featuredSection.php");
  ?>
  </div>
  </div>
  </div>
   <div class="row">
  <div class="large-12 columns">
  <div id="home">
  <h1>Alimenti e Bevande</h1>
  <hr>
 <?php 
  $featuredSection["section"] = "alimenti";
  require("featuredSection.php");  
  ?> 
  </div>
  </div>
  </div>
  <div class="row">
  <div class="large-12 columns">
  <div id="home">
  <h1>Salute e Benessere</h1>
  <hr>
  <?php
  $featuredSection["section"] = "salute";
  require("featuredSection.php");
  ?>
  </div>
  </div>
  </div>
   <div class="row">
  <div class="large-12 columns">
  <div id="home">
  <h1>Caffè</h1>
  <hr>
  <?php
  $featuredSection["section"] = "caffe";
  require("featuredSection.php");
  ?>
  </div>
  </div>
  </div>
  
  <div class="row">

      <div class="large-12 columns text-center">
        <div id="home"><h1>Acquista e Risparmia con eShoppingItaly.com</h1></div>
        <p>Confronta i Prezzi dei prodotti italiani e risparmia con eShopping Italy, la tua guida per gli acquisti.
        
		Prima di effettuare i tuoi acquisti online utilizza il nostro servizio di comparazione prezzi per trovare il prodotto più conveniente.
           Migliaia di offerte dei migliori negozi e-commerce italiani: cerca il nome di un prodotto nel motore di ricerca oppure sfoglia le categorie e scegli l'offerta che preferisci.
             </p>
        
      </div>
      
    </div>
<?php
  require("html/footer.php");
?>