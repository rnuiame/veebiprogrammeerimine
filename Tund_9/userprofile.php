<?php
  require("functions.php");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>profile</title>
  </head>
  <body>
    <h1>Profile</h1>
	<hr>
	<form>
	<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
	<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	</form>
  <hr>
  <p>hello</p>
  </body>
</html>
