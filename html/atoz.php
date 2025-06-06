<?php
  $atoz["itemsByLetter"] = array();

  foreach($atoz["items"] as $item)
  {
    $atoz_letter = tapestry_mb_strtoupper(tapestry_mb_substr($item["name"],0,1));

    $atoz["itemsByLetter"][$atoz_letter][] = $item;
  }
?>

<div class='row pt_az'>

  <div class='small-12 columns'>

    <?php foreach($atoz["itemsByLetter"] as $atoz_letter => $atoz_items): ?>

      <h4><?php print $atoz_letter; ?></h4>

      <ul class="small-block-grid-1 medium-block-grid-6">

        <?php foreach($atoz_items as $atoz_item): ?>

          <li>

            <a href='<?php print $atoz_item["href"]; ?>'>

              <?php if (isset($atoz_item["logo"])): ?>

                <div class='pt_az_img'><img alt='<?php print htmlspecialchars($atoz_item["name"],ENT_QUOTES,$config_charset); ?>' src='<?php print $atoz_item["logo"]; ?>' /></div>

              <?php else: ?>

                <?php print $atoz_item["name"]; ?>

              <?php endif; ?>

            </a>

          </li>

        <?php endforeach; ?>

      </ul>

    <?php endforeach; ?>

  </div>

</div>
