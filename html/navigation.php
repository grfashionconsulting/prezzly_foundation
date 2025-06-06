<?php
  function navigation_display($navigation,$class)
  {
    global $config_baseHREF;

    global $config_resultsPerPage;

    global $rewrite;

    global $page;

    global $sort;

    global $q;

    $totalPages = ceil($navigation["resultCount"] / $config_resultsPerPage);

    print "<div class='pagination-centered ".$class."'>";

    print "<ul class='pagination'>";

    if ($page > 1)
    {
      $prevPage = ($page-1);

      if ($rewrite)
      {
        if ($prevPage == 1)
        {
          $prevHREF = "./";
        }
        else
        {
          $prevHREF = $prevPage.".html";
        }
      }
      else
      {
        $prevHREF = $config_baseHREF."search.php?q=".urlencode($q)."&amp;page=".$prevPage."&amp;sort=".$sort;
      }

      print "<li class='arrow'><a href='".$prevHREF."'>&laquo;</a></li>";
    }
    else
    {
      print "<li class='arrow unavailable'><a href=''>&laquo;</a></li>";
    }

    if ($page < 5)
    {
      $pageFrom = 1;

      $pageTo = 9;
    }
    else
    {
      $pageFrom = ($page - 4);

      $pageTo = ($page + 4);
    }

    if ($pageTo > $totalPages)
    {
      $pageTo = $totalPages;

      $pageFrom = $totalPages - 8;
    }

    if ($pageFrom <= 1)
    {
      $pageFrom = 1;
    }
    else
    {
      if ($rewrite)
      {
        $pageOneHREF = "./";
      }
      else
      {
        $pageOneHREF = $config_baseHREF."search.php?q=".urlencode($q)."&amp;page=1&amp;sort=".$sort;
      }

      print "<li><a href='".$pageOneHREF."'>1</a></li>";

      print "<li class='unavailable'><a href=''>&hellip;</a></li>";
    }

    for($i=$pageFrom;$i<=$pageTo;$i++)
    {
      if ($rewrite)
      {
        if ($i==1)
        {
          $pageHREF = "./";
        }
        else
        {
          $pageHREF = $i.".html";
        }
      }
      else
      {
        $pageHREF = $config_baseHREF."search.php?q=".urlencode($q)."&amp;page=".$i."&amp;sort=".$sort;
      }
      if ($page <> $i)
      {
        if ($class=="show-for-medium-up")
        {
          print "<li><a href='".$pageHREF."'>".$i."</a></li>";
        }
      }
      else
      {
        print "<li class='unavailable current'><a href='".$pageHREF."'>".$i."</a></li>";
      }
    }

    if ($page < $totalPages)
    {
      $nextPage = ($page+1);

      if ($rewrite)
      {
        $nextHREF = $nextPage.".html";
      }
      else
      {
        $nextHREF = $config_baseHREF."search.php?q=".urlencode($q)."&amp;page=".$nextPage."&amp;sort=".$sort;
      }

      print "<li class='arrow'><a href='".$nextHREF."'>&raquo;</a></li>";
    }
    else
    {
      print "<li class='arrow unavailable'><a href=''>&raquo;</a></li>";
    }

    print "</ul>";

    print "</div>";
  }

  if ($navigation["resultCount"] > $config_resultsPerPage)
  {
    navigation_display($navigation,"show-for-small-only");

    navigation_display($navigation,"show-for-medium-up");
  }
  
?>