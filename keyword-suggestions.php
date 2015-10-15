<?php
	require"./dongdong_p4_dbconnect.php";
	require"./check_magic_quotes.php";
	if (isset($_POST['keyword'])) {
		// sanitize user input
		$keyword = htmlspecialchars(strip_tags($_POST['keyword']));
		$keyword = check_magic_quotes($db,$keyword);
		$sql = "select keyword from keyword where keyword like '$keyword" . "%' limit 10";
		if (!empty($keyword)) {
			if ($stmt = mysqli_prepare($db, $sql)) {
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt,$keywords);
				// show all matched keywords
				while (mysqli_stmt_fetch($stmt)) {
					echo $keywords . "<br />";
				}
			}
			mysql_close($db);
		}
	}
?>
