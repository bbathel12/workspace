<?php
ini_set('display_errors',1);
$db = new Mysqli('localhost',getenv("MYSQL_USER"),getenv('MYSQL_PASS'),'workspace');
include('./head.php');
/*
 *imagecreatefromgif
 *imagecreatefromjpeg
 *imagecreatefrompng
*/
function create_thumbnail($filename){
	$percent = .1;
	list($width, $height) = getimagesize("/var/www/html/workspace/images/".$filename);
	$newwidth = $width * $percent;
	$newheight = $height * $percent;
	
	$thumb  = imagecreatetruecolor($newwidth,$newheight);
	
	$ext = pathinfo($filename)['extension'];
	echo "<h1>$ext</h1>";
	switch($ext){
		case 'jpg':
		case 'jpeg':
		case 'JPG':	$source = imagecreatefromjpeg("images/".$filename);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					imagejpeg($thumb,"./thumbs/".$filename);
					break;
		case 'gif': $source = imagecreatefromgif("images/".$filename);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					imagejpeg($thumb,"./thumbs/".$filename);
					break;
		case 'png': $source = imagecreatefrompng("images/".$filename);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					imagejpeg($thumb,"./thumbs/".$filename);
					break;
		default: die("image extension cannot be determined");
		
	}
	
}
