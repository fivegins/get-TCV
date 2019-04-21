<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php

// sao chep ve local
copy('https://www.dl.dropboxusercontent.com/s/mkjxvuszc8kt9yx/data.txt', 'data.txt');

include 'functions.php';

if (isset($_POST['submit'])) {
	
	$link = $_POST['link'];
	$s = $_POST['s'];
	$e = $_POST['e'];
	$loc = isset($_POST['loc']) ? 'yes' : 'no';
	$p = isset($_POST['p']) ? 'yes' : 'no';

	if (empty($link) && empty($s) && empty($e)) {
		exit('die');
	}

	for ($i = $s; $i <= $e; $i++) { 
		$urls[] = $link . 'chuong-' . $i . '/';
	}

	$content = multi_curl($urls);
	preg_match_all('#<title>(.*?)</title>#is', $content, $tit);

	echo '<p><a style="background-color: yellow" href="get.php?link=' . $link . '&s=' . $s . '&e=' . $e . '">
		get.php?link=' . $link . '&s=' . $s . '&e=' . $e . '
	</a></p>';
	echo '<div style="white-space: nowrap;overflow: auto;">';
	foreach ($tit[1] as $key => $value) {
		$value = preg_replace('/.*?-\s*(.*)/', '$1', $value);
		echo "<pre>$key => $value</pre>\n";
	}
	echo '</div>';
	
	$configs = array(
		'link' => $link,
		'start' => $s,
		'end' => $e,
		'config' => array(
			'loc' => $loc,
			'p' => $p
		)
	);
	file_put_contents('config.txt', json_encode($configs));
	// luu len dropbox
	upload_dropbox('config.txt', 'overwrite', 'wkDt6TmyCgAAAAAAAAAB1Tp6TyGgcHivthPG7WD8Ka3aNkQmys95x-7dKSh51nCu');


}

$cfig = json_decode(file_get_contents("https://www.dl.dropboxusercontent.com/s/rnf2fnjad36yh7j/config.txt"), true);

?>

<div style="background-color: #ccc; padding: 10px">
<form method="post" style="margin: 0">
	<input type="text" name="link" placeholder="link" value="<?php echo (isset($_POST['link'])) ? $_POST['link'] : $cfig['link'] ?>" style="width: 100%; margin-bottom: 10px"><br>
	<input type="number" name="s" placeholder="s" value="<?php echo (isset($_POST['s'])) ? $_POST['s'] : $cfig['start'] ?>" style="margin-bottom: 10px"><br>
	<input type="number" name="e" placeholder="e" value="<?php echo (isset($_POST['e'])) ? $_POST['e'] : $cfig['end'] ?>" style="margin-bottom: 10px"><br>
	<div style="margin-bottom: 10px">
	<input type="checkbox" name="loc" <?php echo ($cfig['config']['loc'] == 'yes') ? 'checked' : null; ?>> lọc
	<input type="checkbox" name="p" <?php echo ($cfig['config']['p'] == 'yes') ? 'checked' : null; ?>> p
	</div>
	<input type="submit" name="submit" value="GET TCV">
	<a href="regex.php">Regex</a>
</form>
</div>
