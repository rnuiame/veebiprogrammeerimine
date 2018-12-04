<?php
  require("functions.php");
  
  //kui pole sisse loginud siis logimise lehele
  if (!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  }
  //logime välja
  if (isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index_1.php");
	  exit();
  }
  
  $page = 1;
  $totalImages = findTotalPublicImages();
  //echo $totalImages;
  $limit = 10;
  if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
  } elseif (round(($_GET["page"] - 1) * $limit) > $totalImages){
	  $page = round($totalImages / $limit) - 1;
  } else {
	  $page = $_GET["page"];
  }
  
  $pageTitle = "Avalikud fotod";
  $scripts = '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $scripts .= '<script type="text/javascript" src="javascript/modal.js" defer></script>' ."\n";
  require("header.php");
  
  //$thumbslist = listpublicphotos(2);
  $thumbslist = listpublicphotospage($page, $limit);
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	
<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- The Close Button -->
    <span class="close">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="modalImg">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
	<div id="ratingbox" class="caption">
		<label><input type="radio" id="rate1" value="1">1</label>
		<label><input type="radio" id="rate1" value="1">2</label>
		<label><input type="radio" id="rate1" value="1">3</label>
		<label><input type="radio" id="rate1" value="1">4</label>
		<label><input type="radio" id="rate1" value="1">5</label>
		<input type="button" value="Salvesta hinnang!" id="storeRating">
		<span id="avgRating">
</div>
	
	<div id="gallery">
	<?php
	echo "<p>";
		if ($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmised pildid</a> ';
		} else {
			echo "<span>Eelmised pildid</span> ";
		}
		if ($page * $limit < $totalImages){
			echo '| <a href="?page=' .($page + 1) .'">Järgmised pildid</a>';
		} else {
			echo "| <span>Järgmised pildid</span>";
		}
		echo "</p> \n";
		echo $thumbslist;
	?>
	</div>

  </body>
</html> 