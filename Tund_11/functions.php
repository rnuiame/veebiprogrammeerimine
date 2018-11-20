<?php
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	$database = "if18_revor_nu_1";

	//võtan kasutusele sessiooni
	session_start();
	
	function listpublicphotos($privacy){
		$html = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			//<img src="kataloog/fail" alt="tekst">
			$html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		}
		if(empty($html)){
			$html = "<p>Kahjuks avalikke pilte pole</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	function listpublicphotospage($privacy){
		$html = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL LIMIT 3,6");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			//<img src="kataloog/fail" alt="tekst">
			$html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		}
		if(empty($html)){
			$html = "<p>Kahjuks avalikke pilte pole</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	function latestPicture($privacy){
		$html = "";
		//<img src="kataloog/fail" alt="tekst">
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE id=(SELECT MAX(id) FROM vpphotos WHERE privacy=? AND deleted IS NULL)");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["picDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		} else {
			$html = "<p>Kahjuks avalikke pilte pole</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $html;
	}

	function addPhotoData($fileName, $altText, $privacy){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		if (empty($privacy)){
			$privacy = 3;
		}
		$stmt->bind_param("issi", $_SESSION["userId"], $fileName, $altText, $privacy);
		if($stmt->execute()){
			echo "Andmebaasiga on ka korras!";
		} else{
			echo "Andmebaasiga läks kehvasti!";
		}
		$stmt->close();
		$mysqli->close();
	}

	//kõigi valideeritud sõnumite lugemine kasutajate kaupa
  function readallvalidatedmessagesbyuser(){
	$msghtml = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers1");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);

	$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
	echo $mysqli->error;
	$stmt2->bind_param("i", $idFromDb);
	$stmt2->bind_result($msgFromDb, $acceptedFromDb);

	$stmt->execute();
	//et hoida andmebaasisit loetud andmeid pisut kauem mälus, et saas edasi kasutada
	$stmt->store_result();
	while($stmt->fetch()){
	  $msghtml .= "<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
	  $stmt2->execute();
	  while($stmt2->fetch()){
		$msghtml .= "<p><b>";
		if($acceptedFromDb == 1){
		  $msghtml .= "Lubatud: ";
		} else {
		  $msghtml .= "Keelatud: ";
		}
		$msghtml .= "</b>" .$msgFromDb ."</p> \n";
	  }
	}
	$stmt2->close();
	$stmt->close();
	$mysqli->close();
	return $msghtml;
  }

	//valideerimata sõnumite lugemine
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();

	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$notice .= "</ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

  //loen sõnumi valideerimiseks
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id=?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

	//sisselogimine
	function signin ($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id,firstname, lastname, password FROM vpusers1 WHERE email=?");
		$mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
		if($stmt->execute()){
			//kui õnnestus andmebaasist lugemine
			if($stmt->fetch()){
				//leiti selline kasutaja
				if(password_verify($password, $passwordFromDb)){
					//parool õige
					$notice = "Logisite õnnelikult sisse!";
					$_SESSION["userId"] = $idFromDb;
					$_SESSION["firstName"] = $firstnameFromDb;
					$_SESSION["lastName"] = $lastnameFromDb;
					readprofilecolors();
					$stmt ->close();
					$mysqli->close();
					header("Location: main.php");
					exit();
				} else {
					$notice = "Sisestasite vale salasõna!";
				}
			} else {
				$notice = "Sellist kasutajat (" .$email .") ei leitud!";
			}
		} else {
			$notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
		}

		$stmt ->close();
		$mysqli->close();
		return $notice;
	}

	function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Viga: ";
	}
	$stmt->close();
	$mysqli->close();
  }

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

	//anonüümse sõnumi salvestamine
  function saveamsg($msg){
	$notice = "";
	//serveri ühendus (server, kasutaja, parool, andmebaas
	$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistan ette SQL käsu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
	echo $mysqli->error;
	//asendame SQL käsus küsimargi päris infoga (andmetüüp, andmed ise)
	//s - string; i - integer; d - decimal
	$stmt->bind_param("s", $msg);
	if ($stmt->execute()){
	  $notice = 'Sõnum: "' .$msg .'" on salvestatud.';
	} else {
	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

	//kasutajaprofiili salvestamine
  function storeuserprofile($desc, $bgcol, $txtcol){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM userprofile WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $mysqli->prepare("UPDATE userprofile SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userId"]);
		if($stmt->execute()){
			$notice = "Profiil edukalt uuendatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
		}
	} else {
		//profiili pole, salvestame
		$stmt->close();
		//INSERT INTO vpusers1 (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"
		$stmt = $mysqli->prepare("INSERT INTO userprofile (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $_SESSION["userId"], $desc, $bgcol, $txtcol);
		if($stmt->execute()){
			$notice = "Profiil edukalt salvestatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

	//kasutajaprofiili väljastamine
  function showmyprofile(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM userprofile WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$profile->description = $description;
		$profile->bgcolor = $bgcolor;
		$profile->txtcolor = $txtcolor;
	} else {
		$profile->description = "";
		$profile->bgcolor = "";
		$profile->txtcolor = "";
	}
	$stmt->close();
	$mysqli->close();
	return $profile;
  }

	function readprofilecolors(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT bgcolor, txtcolor FROM userprofile WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$_SESSION["bgColor"] = $bgcolor;
		$_SESSION["txtColor"] = $txtcolor;
	} else {
		$_SESSION["bgColor"] = "#FFFFFF";
		$_SESSION["txtColor"] = "#000000";
	}
	$stmt->close();
	$mysqli->close();
  }
  
  //kasutajate nimekiri
  function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers1 WHERE id !=?");
	
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
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
