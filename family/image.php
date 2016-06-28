<?php
$input=file_get_contents("input.csv");
$input=explode("\r\n", $input);
$count=0;
foreach ($input as $id) {
	echo $id." (".(++$count).")";
	$res=@file_get_contents("http://foodsafety.family.com.tw/product_img/003004.".$id.".jpg");
	if ($res===false) {
		echo "not found\n";
		continue;
	}
	file_put_contents("photo/".$id.".jpg", $res);
	echo "ok\n";
}
?>