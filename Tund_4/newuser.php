<?php
  //kutsume välja funktsioonide faili
  require("functions.php");
  
  $notice = "";
  $firstName = "";
  $lastName = "";
  $birthMonth = null;
  $birthDay = null;
  $birthYear = null;
  $birthDate = null;
  $gender = null;
  $email = null;
  
  $firstNameError = "";
  $lastNameError = "";
  $birthMonthError = "";
  $birthDayError = "";
  $birthYearError = "";
  $birthDateError = "";
  $genderError = "";
  $emailError = "";
  $passWordError = "";
  
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  
  //kontrollime, kas kasutaja on midagi kirjutanud
  //var_dump($_POST);
  if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
	  //$firstName = $_POST["firstName"];
	  $firstName = test_input($_POST["firstName"]);
  }else{
	$firstNameError = "	Palun sisesta oma eesnimi!";
  }
  if (isset($_POST["lastName"])){
	  $lastName = test_input($_POST["lastName"]);
  }
  
  if(isset($_POST["gender"]) and !empty($_POST["firstName"])){
	  $gender = intval($_POST["gender"]);
  } else {
	  $genderNameError = "Palun määra sugu!";
  }
  
  //kui päev ja kuu ja aasta on olemas, kontrollitud
  
  if(isset($_POST["birthDay"]) and isset($_POST["birthMonth"]) and isset($_POST["birthYear"])){
	//kas oodatav kuupäev on üldse võimalik
	//checkdate(kuu, päev, aasta) täisarvud
	if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){
		//kui on võimalik, teeme kuupäevaks
		$birthDate = date_create($_POST["birthMonth"] ."/" .$_POST["birthDay"] ."/" .$_POST["birthYear"]);
		$birthDate = date_format($birthDate, "Y-m-d");
		//echo $birthDate;
	} else {
		$birthDateError = "Palun vali võimalik kuupäev!";
	  }
    }
	//kui kõik on korras, siis salvestan kasutaja
	if(empty($firstNameError) and empty($lastNameError) and empty($birthMonthError) and empty($birthDayError) and empty($birthYearError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passWordError)){
	$notice = signup($firstName, $lastName, $birthDate, $gender, $_POST["email"], $_POST["password"]);
	}
		
	
		
  //kas vajutati nuppu - lõpp
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Uue kasutaja loomine</title>
</head>
<body>
	<h1>Kasutaja loomine</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim väljanäha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
	
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Eesnimi:</label>
	  <input type="text" name="firstName" value="<?php echo $firstName; ?>"><span><?php echo $firstNameError; ?></span><br>
	  <label>Perekonnanimi:</label>
	  <input type="text" name="lastName"><br>
	  <label>Sünnipäev: </label>
	  <?php
	    echo '<select name="birthDay">' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthDay){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <!--<input name="birthYear" type="number" min="1914" max="2003" value="1998">-->
	  <?php
	    echo '<select name="birthYear">' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	<br>
	<br>
	  
	  <input type="radio" name="gender" value="2" <?php if($gender == 2) {echo "checked";} ?>><label>Naine</label><br>
	  <input type="radio" name="gender" value="1" <?php if($gender == 1) {echo "checked";} ?>><label>Mees</label><br>
	  <span><?php echo $genderError; ?></span>
	  <br>
	  
	  <label>Eposti aadress(kasutajatunnuseks)</label> 
	  <input name="email" type="email"><br>
	  <label>Salasõna (min 8 märki)</label>
	  <input name="password" type="password"><br>
	  <input type="submit" name="submitUserData" value="Loo kasutaja">
    </form>
	<hr>
	<p><?php echo $notice ?></p>
</body>
</html>