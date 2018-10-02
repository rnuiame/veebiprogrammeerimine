<?php
require("functions.php");
$kiisulist = addcat($catname, $catcolor, $cattaillength);

?>

<!DOCTYPE html>

<html>
<head>

<meta charset="utf-8">
<title>
kiisud
</title>

</head>
<body>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Kassi nimi:</label>
    <input type="text" name="catname">
    <label>Kassi värvus:</label>
    <input type="text" name="catcolor">
	<label>Saba pikkus: </label>
	<input type="number" name="cattaillenght">
    <input type="submit" name="submitUserData" value="Saada andmed">
  </form>
<p><?php echo $kiisulist; ?></p>
</body>
</html>