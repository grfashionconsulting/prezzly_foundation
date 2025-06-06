<?php
  require("includes/common.php");

  $header["title"] = "Example Page";

  $header["meta"]["description"] = "Example page meta description";

  $header["meta"]["keywords"] = "Example, Page, Meta, Keywords";

  require("html/header.php");

  require("html/menu.php");

  require("html/searchform.php");

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>"Example Page","href"=>$config_baseHREF."example.php");

  require("html/banner.php");
?>

<div class='row'>

  <div class='small-12 columns'>

    <p>This is an example page that you can copy to create additional pages on your site such as an About Us page that use the standard header and footer with customisable title and meta tags as required.</p>

  </div>

</div>

<?php
  require("html/footer.php");
?>