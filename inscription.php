<?php 
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=isines','root','');

if(isset($_POST['subinsc'])){

	$pseudo = htmlspecialchars($_POST['pseudo']);
	$email = htmlspecialchars($_POST['email']);
	$password = sha1($_POST['password']);
	$password2 = sha1($_POST['password2']);
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$age = htmlspecialchars($_POST['age']);
	
	
	

	if (!empty($nom) AND !empty($prenom) AND !empty($pseudo) AND !empty($email) AND !empty($password) AND !empty($password2) AND !empty($nom) AND !empty($age) ) {
		$pseudolengtn = strlen($pseudo);
		if ($pseudolengtn <= 255) {
			
			$reqmail = $bdd->prepare("SELECT * FROM users WHERE email = ?");
			$reqmail->execute(array($email));
			$emailexist = $reqmail->rowCount();
			$reqpseudo = $bdd->prepare("SELECT * FROM users WHERE pseudo = ?");
			$reqpseudo->execute(array($pseudo));
			$pseudoexist = $reqpseudo->rowCount();

			if ($emailexist == 0 AND $pseudoexist == 0) {
				if ($password == $password2) {

					$longueurKey = 15;
					$key = "";
					for ($i=0; $i < $longueurKey; $i++) { 
						$key .= mt_rand(0,9);
					}

					$insert = $bdd->prepare("INSERT INTO users(pseudo,email,password,nom,prenom,age,date_time_creation,comfirmkey,confirme)VALUES(?,?,?,?,?,?,NOW(),?,?)");
					$insert->execute(array($pseudo,$email,$password,$nom,$prenom,$age,$key,"0"));

					$header="MIME-Version: 1.0\r\n";
					$header.='From:"Isines.com"<help.isines@gmail.com>'."\n";
					$header.='Content-Type:text/html; charset="uft-8"'."\n";
					$header.='Content-Transfer-Encoding: 8bit';

					$message='
					<html>
						<body>
							<div align="center">
								<br />
									<a href="http://localhost/isines/confirmation.php?pseudo='.urlencode($pseudo).'&key='.$key.'">Confirmez votre compte</a>
								<br />
							</div>
						</body>
					</html>
					';
					mail("$email", "Confirmez votre compte", $message, $header);

					$msg = "Votre compte a été crée.";

				}else{
					$erreur = "Les deux mot de passes ne correspondent pas";
				}
			}else{
				$erreur = "Cette email ou pseudo est déjà utilisé";
			}

		}else{
			$erreur = "trop de caractères dans votre pseudo";
		}
	}


}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dynamics Games</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/fond.css">
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
		<h1>INSCRIPTION</h1>
		<br><br>

		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<label for="nom">Votre nom :</label>
					</td>
					<td>
						<input type="text" placeholder="Votre nom" id="nom" name="nom" value="<?php if(isset($nom)) {echo $nom;} ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="prenom">Votre prénom : </label>
					</td>
					<td>
						<input type="text" placeholder="Votre prénom" id="prenom" name="prenom" value="<?php if(isset($prenom)) {echo $prenom;} ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="age">Votre âge : </label>
					</td>
					<td>
						<input type="text" placeholder="Votre age" id="age" name="age" value="<?php if(isset($age)) {echo $age;} ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="pseudo">Votre pseudo : </label>
					</td>
					<td>
						<input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) {echo $pseudo;} ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="email">Votre email : </label>
					</td>
					<td>
						<input type="email" placeholder="Votre email" id="email" name="email" value="<?php if(isset($email)) {echo $email;} ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">Votre mot de passe : </label>
					</td>
					<td>
						<input type="password" placeholder="Votre mot de passe" id="password" name="password">
					</td>
				</tr>
				<tr>
					<td>
						<label for="password2">Confirmez votre mot de passe : </label>
					</td>
					<td>
						<input type="password" placeholder="Confirmez votre mot de passe" id="password2" name="password2">
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="submit" class="envoie" value="S'inscrire" name="subinsc">
					</td>
				</tr>
				<tr>
					<td align="">
						<a href="connexion.php">Se connecter</a>
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