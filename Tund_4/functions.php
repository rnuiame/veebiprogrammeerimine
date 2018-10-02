<?php
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	$database = "if18_revor_nu_1";


	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpusers1 (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//valmistame parooli ette salvestamiseks - krüpteerime, teeme räsi(hash)
		$options = [
		  "cost" => 12,
		  "salt" => substr(sha1(rand()), 0, 22),];
		$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
		$stmt->bind_param("sssiss", $firstName, $lastName, $birthDate, $gender, $email, $pwdhash);
		if($stmt->execute()){
		  $notice = "Uue kasutaja lisamine õnnestus!";
		} else {
		  $notice = "Kasutaja lisamisel tekkis viga: " .$stmt->error;
		}
		$stmt ->close();
		$mysqli->close();
		return $notice;
	}

	function addCat($catName, $catColor, $catTail){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],
		$GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO kass kassinimi, kassiv2rv, sabapikkus VALUES(?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ssi", $catName,$catColor,$catTail);
		if($stmt->execute()){
			$notice='Kass: "' .$catName. '"on salvestatud.';
		} else {
			$notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
		}
		$stmt ->close();
		$mysqli->close();
		return $notice; //returniga saab ainult ühe objekti välja saata!!!
		}



?>