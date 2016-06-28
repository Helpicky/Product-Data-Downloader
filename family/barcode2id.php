<?php
$output="";
$input=file_get_contents("input.csv");
$input=explode("\r\n", $input);
$count=0;
foreach ($input as $id) {
	echo $id." (".(++$count).")";
	$output.=$id.",";
	// if (strlen($id)!=13) {
	// 	echo "length error\n";
	// 	$output.="not found\n";
	// 	continue;
	// }
	$res=@file_get_contents("http://foodsafety.family.com.tw/index.php/resume/food_form?product_id=".$id);
	if ($res===false) {
		echo "404\n";
		$output.="not found\n";
		continue;
	}
	if (preg_match("/_gaq.push\(\['_trackEvent','品類查詢頁','查詢商品','.*?'\]\);\" href='http:\/\/foodsafety.family.com.tw\/index.php\/resume\/food\?product_id=(\d+?)' class='dialog'/", $res, $m)) {
		echo "ok\n";
		$output.=$m[1]."\n";
	} else {
		echo "not found\n";
		$output.="not found\n";
	}
}
file_put_contents("out.csv", $output);
?>