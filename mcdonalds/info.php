<?php
$output="ID,品名,熱量,蛋白質,脂肪,飽和脂肪,反式脂肪,碳水化合物,鈉\n";
$input=file_get_contents("in.txt");
$input=explode("\r\n", $input);
$count=0;
foreach ($input as $id) {
	echo $id." (".(++$count).")";
	$output.='"'.$id.'",';
	$res=@file_get_contents("http://gnia.mcdonalds.com/gnia/ws/item/getItemDetails/jsonp?ctry=tw&lang=ch&item=".$id."&callback=getItemNutritionDetail");
	if ($res===false) {
		echo "not found\n";
		continue;
	}
	preg_match("/getItemNutritionDetail\((.+)\)/", $res, $m);
	$res=$m[1];
	$res=json_decode($res);
	$output.='"'.$res->item->marketing_name.'",';
	$output.='"';
	foreach ($res->item->nutrient_facts->nutrient as $temp) {
		$output.=$temp->name;
		if(is_numeric($temp->value))$output.=$temp->value;
		$output.=",";
	}
	$output.'",';
	// $res=str_replace("\r\n", "", $res);
	// $res=preg_replace("/\s\s+/", " ", $res);

	// $output.='"'.$id.'",';

	// preg_match("/marketing_name: \"(.+?)\",/", $res, $m);
	// $output.='"'.$m[1].'",';

	// preg_match("/<Tr><td>重量<\/td><td>(.+?)<\/td><\/tr><Tr><td>熱量<\/td><td>(.+?)<\/td><\/tr><Tr><td>蛋白質<\/td><td>(.+?)<\/td><\/tr><Tr><td>脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>　飽和脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>　反式脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>碳水化合物<\/td><td>(.+?)<\/td><\/tr><Tr><td>鈉<\/td><td>(.+?)<\/td><\/tr><\/table>/", $res, $m);
	// $output.='"'.$m[1].'","'.$m[2].'","'.$m[3].'","'.$m[4].'","'.$m[5].'","'.$m[6].'","'.$m[7].'","'.$m[8].'"';

	$output.="\n";
	echo "ok\n";
}
file_put_contents("out.csv", $output);
?>