<style type="text/css"> 
.center { margin: 0 auto; } 
.div1 { width: 23%; float:left; } 
.div2 { width: 44%; float:left; } 
.div3 { width: 33%; float:left; } 
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<title>Open Video</title>
<h1><center>Open Video</center></h1>
<div class="center">
<div class="div1">
<!-- the search form -->
<form method="get" action="results.php">
	<input type="text" name="keyword" id="keyword" style="width:200px; height:35px;"
	placeholder="Search for videos">
	<input type="submit" value="Search" style="width:60px;height:30px;">
</form>
<!-- suggestion words -->
<p id="sug"></p>
<div id="sugwords"></div>
</div>
<?php
	require"./dongdong_p4_dbconnect.php";
	require"./check_magic_quotes.php";
	if (isset($_GET['keyword'])) {
		// sanitize user input
		$keyword = htmlspecialchars(strip_tags($_GET['keyword']));
		$keyword = check_magic_quotes($db,$keyword);
		echo "<div class='div2'>";
		echo "Showing results for: <b>" . $keyword . "</b>";
		// construct result table
		echo "<table border='0'>";
		$sql = "select videoid, title, substring(description, 1, 200), creationyear, 
		keyframeurl from p4records where match (title, description, keywords) against 
		('$keyword')";
		if ($stmt = mysqli_prepare($db, $sql)) {
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$vid,$title,$desc,$year,$url);
			while (mysqli_stmt_fetch($stmt)) {
				echo "<tr class='result' videoid='" . $vid . "'><td>";
				// provide a link to Open Video website
				echo "<a href='http://www.open-video.org/details.php?videoid=" . $vid;
				echo "'>" . "<img src='http://www.open-video.org/surrogates/keyframes/";
				echo $vid . "/" . $url . "'></a></td><td>";
				echo "<a href='http://www.open-video.org/details.php?videoid=" . $vid;
				echo "'><b>" . $title . " (" . $year . ") " . "</b></a><br />";
				echo $desc. "</td></tr>";
			}
			// if there is no matching record
			if (empty($vid)) {
				echo "<script>alert('Oops! No Records.');</script>";
			}
		}
		else {
			echo "<script>alert('Oops! Failed to load.');</script>";
		}
		mysql_close($db);
		echo "</table></div>";
	}
?>
<!-- result details div -->
<div id="details" class="div3"></div></div>
<script>
	$(document).ready(function() {
		<!-- when a key is up, show suggestions -->
		$("#keyword").keyup(function() {
			var kw = $("#keyword").val();
			if (kw != '') {
				$("#sug").text("Suggestions: ");
				<!-- pass keyword -->
				$.post("keyword-suggestions.php", {
					keyword: kw
				},
				function(data, status) {
					<!-- display the page in html format -->
					$("#sugwords").html(data);
				});
			}
			else {
				$("#sug").text("");
				$("#sugwords").html("");
			}
		});
		<!-- when mouse is over, display details -->
		$(".result").mouseenter(function() {
			<!-- get videoid of the row which mouse is over-->
			var vid = $(this).attr('videoid');
			$.post("result-details.php", {
				videoid: vid
			},
			function(data, status) {
				$("#details").html(data);
			});
		});
		<!-- when mouse leaves, no details -->
		$(".result").mouseleave(function() {
			$("#details").text("");
		});
	});
</script>
