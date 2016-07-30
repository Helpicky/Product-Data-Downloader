<?php
$output="FamilyID,品名,咖啡因含量,產品特色,過敏原,認證標章,規格(每份),本包裝包含幾份,熱量,蛋白質,脂肪,飽和脂肪,反式脂肪,碳水化合物,鈉,糖,原料,添加物,包材,供應商名稱,供應商地址,供應商電話\n";
$input=file_get_contents("in.txt");
$input=explode("\r\n", $input);
$count=0;
foreach ($input as $id) {
	$id = str_pad($id, 7, "0", STR_PAD_LEFT);
	echo $id." (".(++$count).")";
	$res=@file_get_contents("http://foodsafety.family.com.tw/index.php/resume/food?product_id=".$id);
	if ($res===false) {
		echo "not found\n";
		continue;
	}

	$output.='"'.$id.'",';
	preg_match("/<span>品名：<\/span>(.*?)<\/h3>/", $res, $m);
	$output.='"'.$m[1].'",';
	preg_match("/<span>咖啡因含量：<\/span>(.*?)<\/h3>/", $res, $m);
	$output.='"'.$m[1].'",';
	preg_match("/<span>產品特色：<\/span><font style=\"line-height: 0.9em\">(.*?)<\/font><\/h3>/", $res, $m);
	$output.='"'.$m[1].'",';
	preg_match(
"/                            <h3><span>過敏原:<\/span><\/h3>
                            <div class=\"clear\"><\/div>
                            <h4>
                                (.*?)                             <\/h4>/", $res, $m);
	$output.='"'.strip_tags($m[1]).'",';
	preg_match("/                            <h3><span>認證標章:<\/span><\/h3>
                            <div class=\"clear\"><\/div><h4>
                                <div style=\"line-height:150%;\">
                                    (.*?)                                <\/div>/", $res, $m);
	$output.='"'.str_replace("&nbsp;", ",", strip_tags($m[1])).'",';

	preg_match(
"/                                <div class=\"p2_web\" style=\"display:none\">
                                    <h3><span>規格\(每份\)：<\/span>(.*?)<\/h3>
                                    <h3><span>本包裝包含幾份：<\/span>(.*?)<\/h3>
                                    <h3><span>脂肪：<\/span>(.*?)<\/h3>
                                    <h3><span>熱量：<\/span>(.*?)<\/h3>
                                    <h3><span>飽和脂肪：<\/span>(.*?)<\/h3>
                                    <h3><span>蛋白質：<\/span>(.*?)<\/h3>
                                    <h3><span>反式脂肪：<\/span>(.*?)<\/h3>
                                    <h3><span>碳水化合物：<\/span>(.*?)<\/h3>
                                    <h3><span>鈉：<\/span>(.*?)<\/h3>
                                    <h3><span>糖：<\/span>(.*?)<\/h3>/", $res, $m);
	$output.='"'.$m[1].'","'.$m[2].'","'.$m[4].'","'.$m[6].'","'.$m[3].'","'.$m[5].'","'.$m[7].'","'.$m[8].'","'.$m[9].'","'.$m[10].'",';

	$start=strpos($res, "原料");
	$end=strpos($res, "添加物");
	$m=substr($res, $start, $end-$start);
	preg_match_all("/<p style=\"color:#00561f;\">(.*?)<\/p>/", $m, $m2);
	preg_match_all("/<p style=\"color:#00561f;text-align:center;\">(.*?)<\/p>/", $m, $m3);
	$temp=array();
	foreach ($m2[1] as $key => $val) {
		$temp[]=$val."(".$m3[1][$key].")";
	}
	$output.='"'.implode(",", $temp).'",';

	$start=strpos($res, "添加物");
	$end=strpos($res, "包材");
	$m=substr($res, $start, $end-$start);
	preg_match_all("/<p style=\"color:#00561f;\">(.*?)<\/p>/", $m, $m2);
	preg_match_all("/<p style=\"color:#00561f;text-align:center;\">(.*?)<\/p>/", $m, $m3);
	$temp=array();
	foreach ($m2[1] as $key => $val) {
		$temp[]=$val."(".$m3[1][$key].")";
	}
	$output.='"'.implode(",", $temp).'",';

	$start=strpos($res, "包材");
	$end=strpos($res, "製程履歷</h1>");
	$m=substr($res, $start, $end-$start);
	preg_match_all("/<p style=\"color:#00561f;\">(.*?)<\/p>/", $m, $m2);
	preg_match_all("/<p style=\"color:#00561f;text-align:center;\">(.*?)<\/p>/", $m, $m3);
	$temp=array();
	foreach ($m2[1] as $key => $val) {
		$temp[]=$val."(".$m3[1][$key].")";
	}
	$output.='"'.implode(",", $temp).'",';

	preg_match(
"/                                      <td class='factory_data' id='.*?' >
                                           名稱：(.*?)
                                           <br \/>
                                           地址：(.*?)
                                           <br \/>
                                           電話：(.*?)
                                           <br \/>/", $res, $m);
	$output.='"'.$m[1].'","'.$m[2].'","'.$m[3].'"';
	$output.="\n";
	echo "ok\n";
}
file_put_contents("out.csv", $output);
?>