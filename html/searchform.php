<script type='text/JavaScript'>
  $(function() {
    $("#q").autocomplete({
      source: function(request, response) {
        $.ajax({
          url: "<?php print $config_baseHREF; ?>searchJSON.php",
          dataType:"json",
          data:{ q: request.term },
          success: function(data) {
            response($.map(data.products, function(item) { 
              return { label: item.name, value: item.name }; 
            }));
          }
        });
      },
      minLength: 2,
      open: function() { $(this).removeClass("ui-corner-all").addClass("ui-corner-top"); },
      close: function() { $(this).removeClass("ui-corner-top").addClass("ui-corner-all"); }
    }).keydown(function(e){ 
      if (e.keyCode === 13) { $("#search").trigger('submit'); }
    });
  });
</script>

<style>
 /* Arrotonda solo il lato sinistro del campo di ricerca */
form#search .row.collapse .small-10.columns input#q {
    border-top-left-radius: 24px;
    border-bottom-left-radius: 24px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

/* Stili per il logo nella versione mobile e riduzione degli spazi */
@media only screen and (max-width: 640px) {
  .logo1 {
    text-align: center;
    margin: 0 auto;
    margin-bottom: 0px !important;  /* margine negativo per avvicinare il logo al form */
  }
  .logo1 img {
    max-width: 60%;
    height: auto;
    margin: 0 auto;
  }
  
  /* Riduce lo spazio sotto il form di ricerca */
  .pt_se {
    margin-bottom: -5px !important;
	
  }
  
  /* Riduce lo spazio sopra il menu mobile */
  .mobile-menu {
    margin-top: -5px !important;
  }
  
  /* Riduci il padding del contenitore di ricerca se necessario */
  .search-container {
    padding: 0.5rem 0.5rem !important;
  }
}

</style>

<div class="row searchform-row">

  <!-- Logo Desktop -->
  <div class='logo hide-for-small-only medium-4 columns'>  
    <div>
      <a href='<?php print $config_baseHREF; ?>'>
        <img src='/images/logo.png' alt="Logo Prezzly">
      </a>
    </div>
  </div>

  <!-- Logo Mobile -->
  <div class='hide-for-medium-up small-12 columns'> 
    <div class='logo1'>
      <a href='<?php print $config_baseHREF; ?>'>
        <img class='logo1' src='/images/logo.png' alt="Logo Prezzly">
      </a>
    </div>
  </div>

  <!-- Form di ricerca -->
  <div class='small-12 medium-8 columns'>
    <div class='pt_se'>
      
        <form id='search' name='search' action='<?php print $config_baseHREF ?>search.php'>
          <div class='row collapse postfix-round'>
            <div class='small-10 columns ui-widget'>
              <input id='q' name='q' required='required' type='text' placeholder='<?php print translate("Start typing"); ?>...' value='<?php print ((isset($q) && !isset($parts[1]) && !isset($product["products"])) ? $q : ""); ?>'>
            </div>
            <div class='small-2 columns'>
              <button class='button round tiny postfix'><?php print translate("Search"); ?></button>
            </div>
          </div>
        </form>
      
    </div> 	
  </div>

</div>
