<?php
$res=file_get_contents("http://www.mos.com.tw/menu/set.aspx");
preg_match_all("/<a href=\"set_detail\.aspx\?id=(.+?)\">/", $res, $m);
$out="";
foreach ($m[1] as $val) {
	$out.=$val."\n";
}
file_put_contents("out.txt", $out);
?>