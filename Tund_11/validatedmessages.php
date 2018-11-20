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
  $messages = readallvalidatedmessagesbyuser();
  
  $pageTitle = "Sõnumid";
  require("header.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
</head>
<body>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <hr>
  <h2>Valideerimata sõnumid</h2>
  <?php 
  echo $messages;
  ?>
</body>
</html>