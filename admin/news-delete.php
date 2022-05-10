<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_news WHERE news_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
	
	// Getting photo ID to unlink from folder
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_news WHERE news_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['photo'];
	}

	// Unlink the photo
	if($photo!='') {
		unlink('../assets/uploads/'.$photo);	
	}

	// Delete from Maleda_News_news
	$statement = $pdo->prepare("DELETE FROM Maleda_News_news WHERE news_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from Maleda_News_news_scheduled
	$statement = $pdo->prepare("DELETE FROM Maleda_News_news_scheduled WHERE news_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from Maleda_News_news_category
	$statement = $pdo->prepare("DELETE FROM Maleda_News_news_category WHERE news_id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: news.php');
?>
