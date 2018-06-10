<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=isines','root','');

if (isset($_SESSION['id'])) {
	$requser = $bdd->prepare("SELECT * FROM users WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if (isset($_POST['newpseudo']) and !empty($_POST['newpseudo']) and $_POST['newpseudo'] != $user['pseudo']) {
		$newpseudo = htmlspecialchars($_POST['newpseudo']);
		$insertpseudo = $bdd->prepare("UPDATE users SET pseudo = ? WHERE id = ?");
		$insertpseudo->execute(array($newpseudo,$_SESSION['id']));
		header("Location: profil.php?id=".$_SESSION['id']);
	}

	if (isset($_POST['newemail']) and !empty($_POST['newemail']) and $_POST['newemail'] != $user['pseudo']) {
		$newemail = htmlspecialchars($_POST['newemail']);
		$insertpseudo = $bdd->prepare("UPDATE users SET email = ? WHERE id = ?");
		$insertpseudo->execute(array($newemail,$_SESSION['id']));
		header("Location: profil.php?id=".$_SESSION['id']);
	}

	if (isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) and isset($_POST['newmdp2']) and !empty($_POST['newmdp2'])) {
		
		$mdp1 = sha1($_POST['newmdp1']);
		$mdp2 = sha1($_POST['newmdp2']);

		if ($mdp1 == $mdp2) {
			$insertmdp = $bdd->prepare("UPDATE users SET password = ? WHERE id = ?");
			$insertmdp->execute(array($mdp2,$_SESSION['id']));
			header("Location: profil.php?id=".$_SESSION['id']);
		}else{
			$msg = "Vos deux mot de passe ne sont pas les mêmes !!";
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Volts</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/fond.css">
	<link rel="shorcut icon" href="logo/logo.png">
</head>
<body>
	<div align="center">
		<h1>Edition du profil de : <?php echo $_SESSION['pseudo']; ?></h1><br/><br/>
		<form method="POST" action="" enctype="multipart/form-data">
			<table>
				<tr>
					<td align="right">
						<label>Pseudo :</label>
					</td>
					<td>
						<input type="text" name="newpseudo" placeholder="Nouveau Pseudo" value="<?php echo $user['pseudo']; ?>"><br/><br/>
					</td>
				</tr>
				<tr>
					<td align="right">
						<label>Email :</label>
					</td>
					<td>
						<input type="email" name="newemail" placeholder="Nouvelle email" value="<?php echo $user['email']; ?>"><br/><br/>
					</td>						
				</tr>
				<tr>
					<td align="right">
						<label>Mot de passe :</label>
					</td>
					<td>
						<input type="password" name="newmdp1" placeholder="Nouveau mot de passe"><br/><br/>
					</td>
				</tr>
				<tr>
					<td>
						<label>Confirmation du mot de passe :</label>
					</td>
					<td>
						<input type="password" name="newmdp2" placeholder="confirmation du nouveau mot de passe"><br/><br/>
					</td>						
				</tr>
				<tr>
					<td align="center">
						<input type="submit" class="envoie" value="Mettre à jour">
					</td>
				</tr>		
			</table>

		</form>
		<?php
			if (isset($msg)) {
				echo '<font color="green">'.$msg.'</font>';
			}
		?>		
	</div>
</body>
</html>
<?php
}else{
	header("Location: connexion.php");
}
?>