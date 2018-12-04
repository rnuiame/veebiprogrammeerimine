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
  $pageTitle = "Privaatsed fotod";
  require("header.php");
  
  //$thumbslist = listpublicphotos(2);
  $privatelist = listprivatephotos(3);
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
<?php echo $privatelist ?>
  </body>
</html> 