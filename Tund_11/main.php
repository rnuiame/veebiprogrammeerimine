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
  $pageTitle = "Pealeht";
  require("header.php");
  
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"]; ?></p>
	<ul>
      <li><a href="?logout=1">Logi välja!</a></li>
	  <li><a href="addmsg.php">Sõnumi lisamine</a></li>
	  <li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid!</a></li>
	  <li>Näita valideeritud <a href="validatedmessages.php">sõnumeid</a> valideerijate kaupa!</li>
	  <li>Fotode <a href="photoupload.php">üleslaadimine</a>.</li>
	  <li><a href="userprofile.php">Profile</a></li>
	  <li>Avalike fotode <a href="gallery.php">galerii</a></li>
	</ul>

  </body>
</html> 