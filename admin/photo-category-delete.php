<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_category_photo WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
	// Delete from Maleda_News_category_photo
	$statement = $pdo->prepare("DELETE FROM Maleda_News_category_photo WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Unlink all photos
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_photo WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		unlink('../assets/uploads/'.$row['photo_name']);
	}

	// Delete from Maleda_News_photo
	$statement = $pdo->prepare("DELETE FROM Maleda_News_photo WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: photo-category.php');
?>
