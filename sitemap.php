<?php
  require("includes/common.php");

  header("Content-Type: text/xml");

  print "<?xml version='1.0' encoding='UTF-8'?>";

  if ($config_nicheMode)
  {
    print "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

    $sql = "SELECT normalised_name,`".$config_databaseTablePrefix."products`.filename AS filename,`".$config_databaseTablePrefix."feeds`.imported AS imported FROM `".$config_databaseTablePrefix."products` INNER JOIN `".$config_databaseTablePrefix."feeds` ON `".$config_databaseTablePrefix."products`.filename = `".$config_databaseTablePrefix."feeds`.filename ORDER BY normalised_name,imported DESC";

    $link = mysqli_connect($config_databaseServer,$config_databaseUsername,$config_databasePassword,$config_databaseName);

    mysqli_set_charset($link,"utf8");

    mysqli_real_query($link,$sql);

    $result = mysqli_use_result($link);

    $sitemapBaseHREF = "http".(isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]?"s":"")."://".$_SERVER["HTTP_HOST"];

    $normalised_nameLast = "";

    while($row = mysqli_fetch_assoc($result))
    {
      if ($normalised_nameLast == $row["normalised_name"]) continue;

      print "<url>";

      $sitemapHREF = tapestry_productHREF($row);

      print "<loc>".$sitemapBaseHREF.$sitemapHREF."</loc>";

      print "<lastmod>".date("Y-m-d",$row["imported"])."</lastmod>";

      print "</url>";

      $normalised_nameLast = $row["normalised_name"];
    }

    print "</urlset>";
  }
  else
  {
    $limit = 50000;

    if (isset($_GET["filename"]))
    {
      $start = (isset($_GET["start"])?intval($_GET["start"]):0);

      print "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

      $sql = "SELECT normalised_name FROM `".$config_databaseTablePrefix."products` WHERE filename='".database_safe($_GET["filename"])."' ORDER BY normalised_name LIMIT ".$start.",".$limit;

      $link = mysqli_connect($config_databaseServer,$config_databaseUsername,$config_databasePassword,$config_databaseName);

      mysqli_set_charset($link,"utf8");

      mysqli_real_query($link,$sql);

      $result = mysqli_use_result($link);

      $sitemapBaseHREF = "http".(isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]?"s":"")."://".$_SERVER["HTTP_HOST"];

      while($row = mysqli_fetch_assoc($result))
      {
        print "<url>";

        $sitemapHREF = tapestry_productHREF($row);

        print "<loc>".$sitemapBaseHREF.$sitemapHREF."</loc>";

        print "</url>";
      }

      print "</urlset>";
    }
    else
    {
      print "<sitemapindex xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

      $sql = "SELECT * FROM `".$config_databaseTablePrefix."feeds` WHERE imported > 0 AND products > 0 ORDER BY filename";

      if (database_querySelect($sql,$rows))
      {
        $sitemapBaseHREF = "http".(isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]?"s":"")."://".$_SERVER["HTTP_HOST"].$config_baseHREF;

        foreach($rows as $row)
        {
          $start = 0;

          while($start < $row["products"])
          {
            print "<sitemap>";

            $sitemapHREF = "sitemap.php?filename=".urlencode($row["filename"]);

            if ($start)
            {
              $sitemapHREF .= "&amp;start=".$start;
            }

            print "<loc>".$sitemapBaseHREF.$sitemapHREF."</loc>";

            print "<lastmod>".date("Y-m-d",$row["imported"])."</lastmod>";

            print "</sitemap>";

            $start += $limit;
          }
        }
      }

      print "</sitemapindex>";
    }
  }
?>