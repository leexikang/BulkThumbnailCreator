<?php


//var_dump(readdir(opendir('images'))));
//
//$image = imagecreatefromjpeg("1.jpg");
//$nm = imagecreatetruecolor(100,75);
//imagecopyresized($nm, $image, 0,0,0,0,100,75,$ox,$oy);
//imagejpeg($nm, "images/1-thumb.jpg");

function createThumbnail($file, $nx, $dir){

  $thumnail_path = $dir . "/thumbnails";
  $image = imagecreatefromjpeg($dir . "/" . $file);
  $mx  = imagesx($image);
  $my = imagesy($image);
  $ny = floor( $my * ( $nx / $mx));
  $nm = imagecreatetruecolor($nx, $ny);
  imagecopyresized($nm, $image, 0, 0, 0, 0, $nx, $ny, $mx, $my);

  if(!file_exists($thumnail_path)){
    if(!mkdir($thumnail_path)){
      die("can't create thumbnails file");
    }
  }

  imagejpeg($nm, $thumnail_path . "/" . $file);
}

$files = scandir('images');
foreach($files as $file){
  if( $file != "." && $file != ".." && preg_match('/[.](jpg)|(gif)|(png)$/', $file)){
    print ($file . "\n");
    createThumbnail($file, 200, "images");
    print($file . "has been created");
  }

}

