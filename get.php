<?php

include 'functions.php';

$link = $_GET['link'];
$s = $_GET['s'];
$e = $_GET['e'];

$HOME = 'https://' . $_SERVER["SERVER_NAME"];

for ($i = $s; $i <= $e; $i++) { 
	$urls[] = $HOME . '/getTCV.php?link=' . $link . 'chuong-' . $i . '/';
}

$content = multi_curl($urls);
$content = str_replace('⊙⊙', '…<br>…<br>…<br><br>', $content);

echo $content;
echo "s=$s&e=$e";
