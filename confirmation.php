<?php

$bdd = new PDO('mysql:host=localhost;dbname=isines','root','');

if(isset($_GET['pseudo'],$_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['key'])){

	$pseudo = htmlspecialchars(urldecode($_GET['pseudo']));
	$key = htmlspecialchars($_GET['key']);

	$requser = $bdd->prepare("SELECT * FROM users WHERE pseudo = ? and confirmekey = ?");
	$requser->execute(array($pseudo,$key));

	$userexist = $requser->rowCount();
	if ($userexist = 1) {
		
		$user = $requser->fetch();
		if ($user['confirme'] == 0) {
			$updateuser = $bdd->prepare("UPDATE users SET confirme = 1 WHERE pseudo = ? AND comfirmkey = ?");
			$updateuser->execute(array($pseudo,$key));

			header("Location: connexion.php");
			
		}else{
			echo "Vous avez déjà confirmé votre compte";
		}

	}else{
		echo "ERROR";
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Isines Games</title>
	<link rel="shorcut icon" href="logo/logo.png">
	<link rel="stylesheet" type="text/css" href="css/fond.css">
</head>
<body>

</body>
</html>