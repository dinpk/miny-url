<?php
$domain = "https://website.com/";
$message = "";
$show_form = true;

// REDIRECT TO SHORT URL
$q = isset($_GET["q"]) ? $_GET["q"] : ""; 
if (!empty(trim($q))) {
	$connection = getConnection();
	$query = "SELECT url FROM urls WHERE hash = '$q'";
	$results = mysqli_query($connection, $query);
	if ($row = mysqli_fetch_assoc($results)) {
		$db_url = $row["url"];
		header("location: $db_url");
		exit;
	} else {
		$message = "<div class='top_bar error'>URL not found.</div>";
		$show_form = false;
	}
}

// CREATE NEW SHORT URL
if (isset($_POST["submit_button"])) {
	$url = trim($_POST["url"]);
	if (!empty($url)) {
		$connection = getConnection();
		$url = mysqli_real_escape_string($connection, $url);
		$hash = hash('crc32', $url, FALSE);
		$results = mysqli_query($connection, "INSERT INTO urls (hash, url) VALUES ('$hash', '$url')");
		if ($results) {
			$short_url = $domain . $hash;
			$short_url = "
				<br>
				<div>Short URL<br><input id='short_url' class='short_url' type='text' value='$short_url'></div>
				<div id='copy_link'><a href='#' onclick='copyURL();return false;'>Copy</a></div>
				";
		} else {
			$mysql_error = mysqli_error($connection);
			if (strpos($mysql_error, "Duplicate entry") > -1) {
				$message = "<div class='top_bar error'>The url already exists, please try a different url.</div>";
			} else {
				$message = "<div class='top_bar error'>Could not shorten the URL, please try again.</div>";
			}
		}
	} else {
		$message = "<div class='top_bar error'>Please provide a valid URL.</div>";
	}
}
?>
<!doctype html>
<html>
<head>
	<title>URL Shortening Service</title>
	<link type="text/css" rel="stylesheet" href="styles.css" media="all">
	<script>
	function copyURL() {
		var text = document.getElementById("short_url");
		text.select();
		document.execCommand("Copy");
		document.getElementById('copy_link').innerHTML = "<span>✓</span>";
	}
	</script>
</head>
<body>
	<?php if (isset($message)) print $message; ?>
	<?php if ($show_form) { ?>
	<main>
		<section id="title">
			<a href="index.php">miny-url</a>
		</section>
		<h1>Shorten your link</h1>
		<form method="post">
			<div>
				Paste in URL<br>
				<input id="url" name="url" type="url" autofocus required>
			</div>
			<div class="right">
				<input id="submit_button" name="submit_button" type="submit" value="SHORTEN"> 
			</div>
			<?php if (isset($short_url)) print $short_url; ?>
			<?php if (isset($url)) print "Original URL<br><textarea id='original_url'>" . $url . "</textarea>"; ?>
		</form>
	</main>
	<?php } // show form ?>
</body>
</html>

<?php
function getConnection() {
	$connection = mysqli_connect("localhost", "root", "asdf", "minyurl");
	if (mysqli_connect_errno()) die("Could not connect to the database.");
	return $connection;
}
?>
