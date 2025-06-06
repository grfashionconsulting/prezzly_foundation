    <?php
      if (file_exists("html/user_footer_before.php")) require("html/user_footer_before.php");
    ?>
	
	
	
<!-- Pre-Footer con form per la newsletter -->
<div class='row pre-footer'>
  <div class='small-12 columns text-center'>
    <h4>Iscriviti alla nostra Newsletter</h4>
    <form>
      <div class='row collapse'>
        <div class='small-10 columns'>
          <input type='email' placeholder='Inserisci la tua email' required>
        </div>
        <div class='small-2 columns'>
          <button type='submit' class='button postfix'>Iscriviti</button>
        </div>
		<div>
		 <?php
  print "<p>Latest Searches</p>";
  $sql = "SELECT * FROM pt_querylog ORDER BY id DESC LIMIT 5";
  if (database_querySelect($sql,$rows))
  {
    foreach($rows as $row)
    {
      $url = $config_baseHREF."search.php?q=".urlencode($row["query"]);
      print "<a href='".$url."'>".$row["query"]."</a>&nbsp;&nbsp;";
    }
  }
?> 
</div>
      </div>
    </form>
  </div>
</div>

<!-- Footer con 4 colonne -->
<footer class='footer'>
  <div class='row'>
    <div class='small-12 medium-3 columns'>
      <h5>Chi Siamo</h5>
      <p>Informazioni su Prezzly.</p>
    </div>
    <div class='small-12 medium-3 columns'>
      <h5>Servizi</h5>
      <ul>
        <li><a href='#'>Servizio 1</a></li>
        <li><a href='#'>Servizio 2</a></li>
		<li><a href='#'>Servizio 2</a></li>
		<li><a href='#'>Servizio 2</a></li>
      </ul>
    </div>
    <div class='small-12 medium-3 columns'>
      <h5>Supporto</h5>
       <ul>
        <li><a href='#'>Servizio 1</a></li>
        <li><a href='#'>Servizio 2</a></li>
		<li><a href='#'>Servizio 2</a></li>
		<li><a href='#'>Servizio 2</a></li>
      </ul>
    </div>
    <div class='small-12 medium-3 columns'>
      <h5>Seguici</h5>
      <ul>
        <li><a href='#'>Facebook</a></li>
        <li><a href='#'>Instagram</a></li>
      </ul>
    </div>
  </div>
  <div class='row'>
    <div class='small-12 columns text-center'>
      <p>&copy; <?php echo date('Y'); ?> Prezzly. Tutti i diritti riservati.</p>
    </div>
  </div>
</footer>
    <script>
      $(document).foundation();
    </script>

  </body>

</html>