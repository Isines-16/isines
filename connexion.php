<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=isines','root','');

if (isset($_POST['subco'])) {
	$emailconnect = htmlspecialchars($_POST['emailconnect']);
	$pseudoconnect = htmlspecialchars($_POST['pseudo']);
	$mdpconnect = sha1($_POST['password']);
	if (!empty($emailconnect) and !empty($mdpconnect) and !empty($pseudoconnect)) {
		$requser = $bdd->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND pseudo = ?");
		$requser->execute(array($emailconnect,$mdpconnect,$pseudoconnect));
		$userexist = $requser->RowCount();
		if ($userexist == 1) {

			if ($_SESSION['confirme'] = 1) {
				$userinfo = $requser->fetch();
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['pseudo'] = $userinfo['pseudo'];
				$_SESSION['nom'] = $userinfo['nom'];
				$_SESSION['prenom'] = $userinfo['prenom'];
				$_SESSION['age'] = $userinfo['age'];
				$_SESSION['email'] = $userinfo['email'];
				$_SESSION['confirme'] = $userinfo['confirme'];
				header("Location: profil.php?id=".$_SESSION['id']);

			}

		}else{
			$erreur = "Le mot de passe, le pseudo ou l'email est incorrect !";
		}
	}else{
		$erreur = "Veuillez completer tous les champs !!";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Isines</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/fond.css">
	<link rel="stylesheet" type="text/css" href="css/nav.css">
	<link rel="shorcut icon" href="logo/logo.png">
</head>
<body background="logo/backgroundMain.png">
	<div align="center">
		<br>
		<?php 
		if (isset($_SESSION['id'])) {
			include 'nav_co.php'; 
		}else{
			include 'nav.php';
		}
		?>
		<h1>CONNEXION</h1>
		<br/><br/>
		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<label for="pseudo">Votre pseudo : </label>
					</td>
					<td>
						<input type="text" name="pseudo" placeholder="Votre pseudo">
					</td>
				</tr>
				<tr>
					<td>
						<label for="email">Votre email : </label>
					</td>
					<td>
						<input type="email" name="emailconnect" placeholder="Votre email">
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">Votre mot de passe : </label>
					</td>
					<td>
						<input type="password" name="password" placeholder="Votre mot de passe">
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" class="envoie" name="subco" value="Se connecter"><br/>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="inscription.php">Pas encore de compte ?</a>
					</td>
				</tr>			
			</table>
		</form>
		<?php
			if (isset($erreur)) {
				echo '<font color="red">'.$erreur.'</font>';
			}
			if (isset($msg)) {
				echo '<font color="green">'.$msg.'</font>';
			}

		?>		
	</div>
</body>
</html>