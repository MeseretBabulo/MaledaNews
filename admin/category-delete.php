<?php require_once('header.php'); ?>

<?php
// Preventing the direct access of this page.
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_category WHERE category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php

	// Delete from Maleda_News_news for that category and unlink all photos under those news
	$statement = $pdo->prepare("SELECT * FROM Maleda_News_news_category WHERE category_id=? AND access=1");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row)  {
		$temp_arr[] = $row['news_id'];
	}
	for($i=0;$i<count($temp_arr);$i++) {
		$statement = $pdo->prepare("SELECT * FROM Maleda_News_news WHERE news_id=?");
		$statement->execute(array($temp_arr[$i]));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		foreach ($result as $row) {
			unlink('../assets/uploads/'.$row['photo']);
		}
		$statement = $pdo->prepare("DELETE FROM Maleda_News_news WHERE news_id=?");
		$statement->execute(array($temp_arr[$i]));

		$statement = $pdo->prepare("DELETE FROM Maleda_News_news_scheduled WHERE news_id=?");
		$statement->execute(array($temp_arr[$i]));
	}

	// Delete from Maleda_News_category
	$statement = $pdo->prepare("DELETE FROM Maleda_News_category WHERE category_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from Maleda_News_home_category
	$statement = $pdo->prepare("DELETE FROM Maleda_News_home_category WHERE category_id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from Maleda_News_news_category
	$statement = $pdo->prepare("DELETE FROM Maleda_News_news_category WHERE category_id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: category.php');
?>
