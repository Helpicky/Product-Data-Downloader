<?php
$output="ID,品名,重量,熱量,蛋白質,脂肪,飽和脂肪,反式脂肪,碳水化合物,鈉\n";
$input=file_get_contents("in.txt");
$input=explode("\r\n", $input);
$count=0;
foreach ($input as $id) {
	echo $id." (".(++$count).")";
	$res=@file_get_contents("http://www.mos.com.tw/menu/set_detail.aspx?id=".$id);
	if ($res===false) {
		echo "not found\n";
		continue;
	}
	$res=str_replace("\r\n", "", $res);
	$res=preg_replace("/\s\s+/", " ", $res);

	$output.='"'.$id.'",';

	preg_match("/<span id=\"mainContent_lblName\">(.+?)<\/span>/", $res, $m);
	$output.='"'.$m[1].'",';

	preg_match("/<Tr><td>重量<\/td><td>(.+?)<\/td><\/tr><Tr><td>熱量<\/td><td>(.+?)<\/td><\/tr><Tr><td>蛋白質<\/td><td>(.+?)<\/td><\/tr><Tr><td>脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>　飽和脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>　反式脂肪<\/td><td>(.+?)<\/td><\/tr><Tr><td>碳水化合物<\/td><td>(.+?)<\/td><\/tr><Tr><td>鈉<\/td><td>(.+?)<\/td><\/tr><\/table>/", $res, $m);
	$output.='"'.$m[1].'","'.$m[2].'","'.$m[3].'","'.$m[4].'","'.$m[5].'","'.$m[6].'","'.$m[7].'","'.$m[8].'"';

	$output.="\n";
	echo "ok\n";
}
file_put_contents("out.csv", $output);
?>