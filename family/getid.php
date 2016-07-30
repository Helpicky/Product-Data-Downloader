<?php
$output = "PRODNAME,CMNO,KIND_CODE,PACK,PicFileName,CATEGORY_ID,CATEGORY_NAME\n";
for ($i=1; $i <= 22; $i++) { 
	echo $i."\n";
	$res = file_get_contents("http://foodsafety.family.com.tw/index.php/resume/get_prod?cid=".str_pad($i, 2, "0", STR_PAD_LEFT));
	$res = substr($res, 6);
	$res = json_decode($res);
	foreach ($res->data as $temp) {
		echo $temp->PRODNAME."\n";
		$output.='"'.$temp->PRODNAME.'","'.$temp->CMNO.'","'.$temp->KIND_CODE.'","'.$temp->PACK.'","'.$temp->PicFileName.'","'.$temp->CATEGORY_ID.'","'.$temp->CATEGORY_NAME.'"'."\n";
	}
}
file_put_contents("out.csv", $output);
?>