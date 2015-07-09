<?php


//var_dump(readdir(opendir('images'))));
//
//$image = imagecreatefromjpeg("1.jpg");
//$nm = imagecreatetruecolor(100,75);
//imagecopyresized($nm, $image, 0,0,0,0,100,75,$ox,$oy);
//imagejpeg($nm, "images/1-thumb.jpg");

class ImageManipulation{

  protected $thumbnails;
  protected $downgrade;
  protected $dir;

   public function __construct(CreateThumbnails $thumbnails,  DowngradImage $downgrade,  $dir){

      $this->thumbnails = $thumbnails;
      $this->downgrade = $downgrade;
      $this->dir = $dir;

   }

  public function make($xlength, $quality){

    $this->createPath();

    foreach(scandir($this->dir) as $file){

      if( $file != "." && $file != ".." && preg_match('/[.](jpg)|(gif)|(png)$/', $file)){

        $this->thumbnails->make($file, $xlength, $this->dir, $this->getThumnailsPath());
        $this->downgrade->make($file, $quality, $this->dir, $this->getDowngradePath());
        print($file . " operation completed \n");
      }

    }
  }

  protected function createPath(){

    foreach($this->getPathByArray() as $path){
      if(!file_exists($path)){
        if(!mkdir($path)){
          die("can't create " . $path);
        }
      }
    }
  }

  protected function createThumbnailsPath(){

    $this->thumbnails->returnDestination();

  }

  protected function getThumnailsPath(){

    return $this->dir . "/" . $this->thumbnails->returnDestination();

  }

  protected function getDowngradePath(){

    return $this->dir . "/" . $this->downgrade->returnDestination();

  }

  protected function getPathByArray(){

    return [ $this->getThumnailsPath(),
      $this->getDowngradePath()
      ];
  }

};

Class DowngradImage{

  protected function createPath(){

    if(!file_exists($this->getPath())){
      if(!mkdir($this->getPath())){
        die("can't create thumbnails file");
      }
    }
  }
  public function returnDestination(){

    return "downgrad";

  }

  public function make($file, $quality, $source, $destination){

    $image = imagecreatefromjpeg($source . "/" . $file);
    $dimage = imagejpeg($image, $destination . "/" . $file , $quality);

  }



}

Class CreateThumbnails{

  public function make($file, $nx, $source, $destination){

    $image = imagecreatefromjpeg($source . "/" . $file);
    list($mx, $my) = $this->getXY($image);
    $ny = floor( $my * ( $nx / $mx));
    $nm = imagecreatetruecolor($nx, $ny);
    imagecopyresized($nm, $image, 0, 0, 0, 0, $nx, $ny, $mx, $my);
    imagejpeg($nm, $destination  . "/" . $file, 30);

  }

  public function returnDestination(){

    return "thumbnails";

  }

  public function getXY($image){

    $mx  = imagesx($image);
    $my = imagesy($image);
    return [$mx, $my];

  }



}

/* echo "Please give the path your want to create thumbnails ? \n";
$dir = readline();
$thumnail = new Thumbnails($dir);
$thumnail->make(200);
 */
print "Please give the directory you want to operate. \n";
$dir = readline();
$image = new ImageManipulation(new CreateThumbnails, new DowngradImage, $dir);
$image->make(100, 30);
