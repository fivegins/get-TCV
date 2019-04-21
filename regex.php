<?php

include 'functions.php';
// ghi du lieu
if (isset($_POST['s']) && isset($_POST['r'])) {
	// s khong rong
	if (!empty($_POST['s'])) {

		$flag = isset($_POST['flag']) ? $_POST['flag'] : 'g';

		$data_file = json_decode(file_get_contents("https://www.dl.dropboxusercontent.com/s/mkjxvuszc8kt9yx/data.txt"), true);
		$data_array = array(
			'search' => $_POST['s'],
			'replace' => $_POST['r'],
			'flag' => $flag
		);
		$data_merge = array_merge($data_file, array($data_array)); //$data_array phan tu con
		file_put_contents("data.txt", json_encode($data_merge));
		// luu len dropbox
		upload_dropbox('data.txt', 'overwrite', 'wkDt6TmyCgAAAAAAAAAB1Tp6TyGgcHivthPG7WD8Ka3aNkQmys95x-7dKSh51nCu');
	}
}

// in du lieu
$data = json_decode(file_get_contents("https://www.dl.dropboxusercontent.com/s/mkjxvuszc8kt9yx/data.txt"), true);

if (isset($_GET['xoa'])) {
	foreach ($data as $key => $value) {
		// check dieu kien
		if ($key == $_GET['xoa'] && $key != 0) {
			// xoa ban ghi tu mang
			unset($data[$key]);
		}
	}
	if ($_GET['xoa'] == 'all') {
		$data_new = array('search' => 'Sin chào', 'replace' => 'Xin chào', 'flag' => 'g');
		$data = array($data_new);
	}
	// luu du lieu moi
	file_put_contents("data.txt", json_encode($data));
	// luu len dropbox
	upload_dropbox('data.txt', 'overwrite', 'wkDt6TmyCgAAAAAAAAAB1Tp6TyGgcHivthPG7WD8Ka3aNkQmys95x-7dKSh51nCu');
	header('Location: regex.php');
	exit();
}

?>
<title>Regex</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	a { text-decoration: none; }
	input[type=submit], textarea {
		display: block;
		margin: 5px 0;
	}
</style>
<form method="post">
	<textarea name="s" style="width: 100%;"></textarea>
	<textarea name="r" style="width: 100%;"></textarea>
	<input type="radio" name="flag" value="u"> <b>/u</b>
	<input type="radio" name="flag" value="i"> <b>/i</b>
	<input type="radio" name="flag" value="is"> <b>#is</b>
	<input type="radio" name="flag" value="iu"> <b>/iu</b>
	<input type="radio" name="flag" value="td"> <b>/tđ</b>
	<input type="submit" name="submit" value="Replace">
</form>
<p><a href="?xoa=all" onclick = "if (! confirm('Xoá?')) { return false; }">Xoá code</a></p>
<hr>
<div style="white-space: nowrap;overflow: auto;">
<?php

foreach ($data as $key => $value) {
	//echo "[$key] " . $value['search'] . " => " . $value['replace'] . "<hr>";
	$s = str_replace(' ', '▂', $value['search']);
	$r = str_replace(' ', '▂', $value['replace']);
	if ($value['flag'] == 'u') {
		$flag = '/u';
	} elseif ($value['flag'] == 'i') {
		$flag = '/i';
	} elseif ($value['flag'] == 'is') {
		$flag = '#is';
	} elseif ($value['flag'] == 'iu') {
		$flag = '/iu';
	} elseif ($value['flag'] == 'td') {
		$flag = '/td';
	} else {
		$flag = '/g';
	}

	?>
	<pre>[<b><?php echo sprintf("%02d", $key) ?></b>] <span style="background-color: yellow"><?php echo htmlspecialchars($s) ?> <font color="red"><?php echo $flag ?></font> <?php echo (($r != null) ? htmlspecialchars($r) : '<font color="gray"><i>null</i></font>') ?></span> [<a href="?xoa=<?php echo $key ?>" onclick = "if (! confirm('Xoá?')) { return false; }">xóa</a>]</pre>
	<?php
}

?>
</div>