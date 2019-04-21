<?php

include('functions.php');
include('simple_html_dom.php');

$cfig = json_decode(file_get_contents('config.txt'), true);
$data = json_decode(file_get_contents('data.txt'), true);

if ($cfig['config']['p'] == 'yes') {
	$p = 'p';
} else {
	$p = 'p:has(a[href*="truyencv.com"])';
}

$html = file_get_html($_GET['link']);

$tieude = $html->find('h2.title', 0)->plaintext;

$raw = $html->find('div#js-truyencv-content', 0);
$noidung = remove($raw, 'iframe, script, style, a, div, ' . $p . '');
$noidung = str_replace("<p>&nbsp;</p>", "", $noidung);

// nl2p
if ($cfig['config']['nl2p'] == 'yes') {
	$noidung = strip_tags($noidung, '<br><p>');
	$noidung = preg_replace('/((<br\s*\/?>|<\/?p>)\s*)+/', "\n", $noidung);
	$noidung = nl2p($noidung);
}

// loc
if ($cfig['config']['loc'] == 'yes') {
	$noidung = loc($noidung);
}

foreach ($data as $value) {
	if ($value['flag'] == 'g') {
		$noidung = preg_replace('/' . $value['search'] . '/', $value['replace'], $noidung);
	} elseif ($value['flag'] == 'u') {
		$noidung = preg_replace('/' . $value['search'] . '/u', $value['replace'], $noidung);
	} elseif ($value['flag'] == 'i') {
		$noidung = preg_replace('/' . $value['search'] . '/i', $value['replace'], $noidung);
	} elseif ($value['flag'] == 'is') {
		$noidung = preg_replace('#' . $value['search'] . '#is', $value['replace'], $noidung);
	} elseif ($value['flag'] == 'iu') {
		$noidung = preg_replace('/' . $value['search'] . '/iu', $value['replace'], $noidung);
	} elseif ($value['flag'] == 'td') {
		$tieude = preg_replace('/' . $value['search'] . '/', $value['replace'], $tieude);
	}
}

echo "$tieude<br>➥<br>➥<br><br>$noidung<br>⊙⊙";

function remove($nguon, $xoa) {
	foreach ($nguon->find($xoa) as $node) {
		$node->outertext = '';
	}
	return $nguon->innertext;
}
