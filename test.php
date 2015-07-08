<?php


//var_dump(readdir(opendir('images'))));
//
//$image = imagecreatefromjpeg("1.jpg");
//$nm = imagecreatetruecolor(100,75);
//imagecopyresized($nm, $image, 0,0,0,0,100,75,$ox,$oy);
//imagejpeg($nm, "images/1-thumb.jpg");

Class Thumbnails{

  public $dir;

  public function __construct($dir){
    $this->dir = $dir;
  }
  public function createThumbnail($file, $nx){

    $image = imagecreatefromjpeg($this->dir . "/" . $file);
    list($mx, $my) = $this->getXY($image);
    $ny = floor( $my * ( $nx / $mx));
    $nm = imagecreatetruecolor($nx, $ny);
    imagecopyresized($nm, $image, 0, 0, 0, 0, $nx, $ny, $mx, $my);
    imagejpeg($nm, $this->getPath() . "/" . $file);

  }

  public function getPath(){

  return $this->dir . "/thumbnails";

  }

  protected function createPath(){

    if(!file_exists($this->getPath())){
      if(!mkdir($this->getPath())){
        die("can't create thumbnails file");
      }
    }
  }



  public function getXY($image){

    $mx  = imagesx($image);
    $my = imagesy($image);
    return [$mx, $my];

  }

  public function make($xlength){

    $files = scandir($this->dir);
    $this->createPath();
  foreach($files as $file){
    if( $file != "." && $file != ".." && preg_match('/[.](jpg)|(gif)|(png)$/', $file)){
      $this->createThumbnail($file, $xlength);
      print($file . "has been created \n");
    }

  }
  }


}

$thumnail = new Thumbnails("images");
$thumnail->make(200);

