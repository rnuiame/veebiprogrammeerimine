<?php
//laen andmebaasi info
require("../../../config.php");
//echo $GLOBALS["serverUsername"];
$database = "if18_revor_nu_1";
$privacy = 2;
$limit = 10;
$photolist = [];
$html = NULL;
$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy <= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
echo $mysqli->error;
$stmt->bind_param("ii", $privacy, $limit);
$stmt->bind_result($fileNameFromDb, $alttextFromDb);
$stmt->execute();
while($stmt->fetch()){
	$pyPhoto = new StdClass();
	$myPhoto->filename = $fileNameFromDb;
	$myPhoto->attext = $alttextFromDb;
	array_push($photolist, $myPhoto);
}
$picCount = count($photolist);
$picNum = mt_rand(0, $picCount - 1);
$html = '<img src="' .$picDir .$photolist[$picNum]->filename .'" alt="'
 .$photolist[$picNum]->alttext .'">' ."\n";
$stmt->close();
$mysqli->close();
echo $html;
?>