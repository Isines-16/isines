<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=isines','root','');

if (isset($_GET['id']) and $_GET['id'] > 0) {
	$getid = intval($_GET['id']);
	$requser = $bdd-> prepare('SELECT * FROM users WHERE id = ?');
	$requser->execute(array($getid));
	$userinfo = $requser->fetch();

$_SESSION['confirme'] = $userinfo['confirme'];

if ($_SESSION['confirme'] == 0) {
	header("Location: attconf.php");
}elseif ($_SESSION['confirme'] == 1) {


?>
<!DOCTYPE html>
<html>
<head>
	<title>Dynamics Games</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/fond.css">
	<link rel="stylesheet" type="text/css" href="css/img.css">
	<link rel="stylesheet" type="text/css" href="css/nav.css">
	<link rel="shorcut icon" href="logo/logo.png">
</head>
<body>
	<div align="center">
		<br>
		<?php 
		if (isset($_SESSION['id'])) {
			include 'nav_co.php'; 
		}else{
			include 'nav.php';
		}
		?>
		<h1><?php echo $userinfo['pseudo']; ?></h1>
		<br/><br/>
			
		<?php
		}
		?>

		<?php
		if (isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
		?>
		<br/><a href="editionprofil.php">Editer mon profil</a>
		<br><br><a href="supprimerok.php">Supprimer mon compte</a>
		<?php
		}
		/*echo $_SESSION['id'];*/
		}else{
		header('Location: connexion.php');
		}
		?>

	</div>
</body>
</html>
