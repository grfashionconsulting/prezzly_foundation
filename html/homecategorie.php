<div class="row">
  <div class="small-12 columns text-center">
    
  </div>
  <div class="small-12 columns text-center">
    <ul class="small-block-grid-4 medium-block-grid-8">
    <?php 
      $categories = [
        ['name' => 'Moda', 'image' => '/images/Moda_categoria.png', 'link' => '/categoria/moda.php'],
        ['name' => 'Enogastronomia', 'image' => '/images/Enogastronomia_categoria.png', 'link' => '/categoria/enogastronomia.php'],
        ['name' => 'Salute e Bellezza', 'image' => '/images/Salute e Bellezza_categoria.png', 'link' => '/categoria/salaute-bellezza.php'],
        ['name' => 'Casa e Giardino', 'image' => '/images/Casa e Giardino_categoria.png', 'link' => '/categoria/casa-giardino.php'],
        ['name' => 'Auto Moto Accessori', 'image' => '/images/Auto Moto Accessori_categoria.png', 'link' => '#'],
        ['name' => 'Sport e Tempo libero', 'image' => '/images/Sport e Tempo libero_categoria.png', 'link' => '#'],
        ['name' => 'Prodotti per Infanzia', 'image' => '/images/Prodotti per Infanzia_categoria.png', 'link' => '#'],
        ['name' => 'Elettronica e Informatica', 'image' => '/images/Elettronica e Informatica_categoria.png', 'link' => '#'],
        ['name' => 'Elettrodomestici', 'image' => '/images/Elettrodomestici_categoria.png', 'link' => '#'],
        ['name' => 'Gioielli', 'image' => '/images/Gioielli_categoria.png', 'link' => '#'],
        ['name' => 'Viaggiare', 'image' => '/images/Viaggiare_categoria.png', 'link' => '#'],
        ['name' => 'Telefonia', 'image' => '/images/Telefonia_categoria.png', 'link' => '#'],
        ['name' => 'Articoli erotici', 'image' => '/images/Articoli erotici_categoria.png', 'link' => '#'],
        ['name' => 'Prodotti per Animali', 'image' => '/images/Prodotti per Animali_categoria.png', 'link' => '#'],
        ['name' => 'Attrezzature da lavoro', 'image' => '/images/Attrezzature da lavoro_categoria.png', 'link' => '#'],
        ['name' => 'Servizi alle imprese', 'image' => '/images/Servizi alle imprese_categoria.png', 'link' => '#']
      ];

      foreach ($categories as $category) {
        echo "<li class='category-item'>
                <a href='{$category['link']}'>
                  <img src='{$category['image']}' alt='{$category['name']}'>
                  <span>{$category['name']}</span>
                </a>
              </li>";
      }
    ?>
  </ul>
  </div>
</div>