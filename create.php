<?php

upload_dropbox('config.txt', 'overwrite', 'wkDt6TmyCgAAAAAAAAAB1Tp6TyGgcHivthPG7WD8Ka3aNkQmys95x-7dKSh51nCu');

function upload_dropbox($path, $mode = 'add', $token, $show = false) {
	$fp = fopen($path, 'rb');
	$size = filesize($path);

	$cheaders = array('Authorization: Bearer ' . $token, 'Content-Type: application/octet-stream', 'Dropbox-API-Arg: {"path":"/test/' . $path . '", "mode":"' . $mode . '"}');

	$ch = curl_init('https://content.dropboxapi.com/2/files/upload');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
	curl_setopt($ch, CURLOPT_PUT, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_INFILE, $fp);
	curl_setopt($ch, CURLOPT_INFILESIZE, $size);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);

	if ($show) {
		echo $response;
	}
	curl_close($ch);
	fclose($fp);
}
