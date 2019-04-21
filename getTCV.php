<?php

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

if ($cfig['config']['loc'] == 'yes') {
	$noidung = loc($noidung);
}

echo "$tieude<br>➥<br>➥<br><br>$noidung<br>⊙⊙";

function remove($nguon, $xoa) {
	foreach ($nguon->find($xoa) as $node) {
		$node->outertext = '';
	}
	return $nguon->innertext;
}

function loc($word)
{
	$word = preg_replace('/(\W)ria(\W)/i', '$1dia$2', $word);
	$word = preg_replace('/(\W)sum(\W)/i', '$1xum$2', $word);
	$word = preg_replace('/(\W)boa(\W)/i', '$1bo$2', $word);
	$word = preg_replace('/(\W)mu([^A-ZẮẰẲẴẶĂẤẦẨẪẬÂÁÀÃẢẠĐẾỀỂỄỆÊÉÈẺẼẸÍÌỈĨỊỐỒỔỖỘÔỚỜỞỠỢƠÓÒÕỎỌỨỪỬỮỰƯÚÙỦŨỤÝỲỶỸỴ])/i', '$1mư$2', $word);
	$word = preg_replace('/(\W)go(\W)/i', '$1gô$2', $word);
	$word = preg_replace('/(\W)ah(\W)/i', '$1a$2', $word);
	$word = preg_replace('/(\W)uh(\W)/i', '$1ư$2', $word);
	$word = preg_replace('/cmnd/i', 'chứng minh nhân dân', $word);
	$word = preg_replace(array('/đkm/i', '/dkm/i', '/đcm/i', '/dcm/i', '/cmn/i'), 'con mẹ nó', $word);
	$word = preg_replace('/([^A-Z])cm(\W)/i', '$1xen-ti-mét$2', $word);
	$word = preg_replace('/([^A-Z])km(\W)/i', '$1ki-lô-mét$2', $word);
	$word = preg_replace('/([^A-Z])kg(\W)/i', '$1ki-lô-gam$2', $word);
	$word = preg_replace('/…/', '...', $word);
	$word = preg_replace('/\.(?:\s*\.)+/', '... ', $word);
	$word = preg_replace('/,(?:\s*,)+/', ',', $word);
	$word = str_replace(array('&quot;', '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;'), '"', $word);
	$word = str_replace('"..."', '"Lặng!"', $word);
	$word = preg_replace('/_/', '-', $word);
	$word = preg_replace('/-+/', '-', $word);
	$word = preg_replace('/-*o\s*0\s*o-*/', '...', $word);
	$word = preg_replace('/~/', ' ', $word);
	$word = preg_replace('/\*/', '', $word);
	$word = preg_replace('/ (\.|\?|!|,)/', '$1', $word);
	$word = preg_replace('/(\d{1,2}),(\d{3})/', '$1$2', $word);
	return $word;
}