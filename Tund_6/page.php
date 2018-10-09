<?php
  $firstName = "Kodanik";
  $lastName = "Tundmatu";
  $currentMonth = date("n");
  //var_dump ($currentMonth);
  
  //kontrollime, kas kasutaja on midagi kirjutanud
  //var_dump ($_POST);
  if (isset($_POST["firstName"])){
	  //$firstName = $_POST["firstName"];
	  $firstName = test_input($_POST["firstName"]);
  }
  if (isset($_POST["lastName"])){
	  $lastName = test_input($_POST["lastName"]);
  }
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  
  //harjutamiseks funktsioon
  function fullname(){
	  $GLOBALS["fullName"] = $GLOBALS["$firstName"] ." " .$GLOBALS["lastName"];
  }
  
  $fullname = "";
  fullname();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>
  <?php
    echo $firstName;
	echo " ";
	echo $lastName;
  ?>
  , õppetöö</title>
</head>
<body>
  <h1><?php 
    echo $firstName ." " .$lastName;
  ?>, IF18</h1>
  <p>See leht on loodud <a href="https://www.tlu.ee/" target="_blank">TLÜ<a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsiseltvõetavat sisu.</p>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi:</label>
    <input type="text" name="firstName">
    <label>Perekonnanimi:</label>
    <input type="text" name="lastName">
	<label>Sünniaasta: </label>
	<input type="number" min="1914" max="2000" value="1999" name="birthYear">
	<label>Sünnikuu: </label>
	<select name="birthMonth">
  <option value="1" <?php if ($currentMonth == 1) echo 'selected'; ?>>jaanuar</option>
  <option value="2" <?php if ($currentMonth == 2) echo 'selected'; ?>>veebruar</option>
  <option value="3" <?php if ($currentMonth == 3) echo 'selected'; ?>>märts</option>
  <option value="4" <?php if ($currentMonth == 4) echo 'selected'; ?>>aprill</option>
  <option value="5" <?php if ($currentMonth == 5) echo 'selected'; ?>>mai</option>
  <option value="6" <?php if ($currentMonth == 6) echo 'selected'; ?>>juuni</option>
  <option value="7" <?php if ($currentMonth == 7) echo 'selected'; ?>>juuli</option>
  <option value="8" <?php if ($currentMonth == 8) echo 'selected'; ?>>august</option>
  <option value="9" <?php if ($currentMonth == 9) echo 'selected'; ?>>september</option>
  <option value="10" <?php if ($currentMonth == 10) echo 'selected'; ?>>oktoober</option>
  <option value="11" <?php if ($currentMonth == 11) echo 'selected'; ?>>november</option>
  <option value="12" <?php if ($currentMonth == 12) echo 'selected'; ?>>detsember</option>
</select>
    <br>
    <input type="submit" name="submitUserData" value="Saada andmed">
  </form>
  <hr>
  <?php
  if (isset($_POST["firstName"])){
	  echo "<p> .$fullname .", olete elanud järgnevatel aastatel: </p> \n";
	  echo "<ol> \n";
	    for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++){
		  echo "<li>" .$i ."</li> \n";
		}
	  echo "</ol> \n";
  }
  
  ?>
  
</body>
</html>
