<?php
	require"./dongdong_p4_dbconnect.php";
	if (isset($_POST['videoid'])) {
		$videoid = $_POST['videoid'];
		$sql = "select title, genre, keywords, duration, color, sound, sponsorname
		from p4records where videoid = '$videoid'";
		if ($stmt = mysqli_prepare($db, $sql)) {
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$title,$genre,$keywords,$duration,$color,
			$sound,$sponsorname);
			while (mysqli_stmt_fetch($stmt)) {
				echo "<b>" . $title . "</b><br /><br />";
				echo "<b>Genre: </b>" . $genre . "<br />";
				echo "<b>Keywords: </b>" . $keywords . "<br />";
				echo "<b>Duration: </b>" . $duration . "<br />";
				echo "<b>Color: </b>" . $color . "<br />";
				echo "<b>Sound: </b>" . $sound . "<br />";
				echo "<b>Sponsor: </b>" . $sponsorname . "<br />";
			}
		}
		mysql_close($db);
	}
?>
