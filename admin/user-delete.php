<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_user WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 || $_REQUEST['id'] == 1 ) {
		header('location: logout.php');
		exit;
	}
}
	
// Getting photo ID to unlink from folder
$statement = $pdo->prepare("SELECT * FROM Maleda_News_user WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$photo = $row['photo'];
}

// Unlink the photo
if($photo!='') {
	unlink('../assets/uploads/'.$photo);	
}

// Delete from Maleda_News_user
$statement = $pdo->prepare("DELETE FROM Maleda_News_user WHERE id=?");
$statement->execute(array($_REQUEST['id']));

header('location: user.php');
?>
