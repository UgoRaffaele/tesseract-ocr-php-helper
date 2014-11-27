<?php
/**
  * A helper to work with TesseractOCR and its PHP wrapper from Thiago Alessio Pereira
  *
  * PHP version 5
  *
  * @category OCR
  * @package  TesseractOCRHelper
  * @author   Ugo Raffaele Piemontese <me@ugopiemontese.eu>
  * @license  http://opensource.org/licenses/MIT MIT
  * @link     https://github.com/ugoraffaele/tesseract-ocr-php-helper
  */

class TesseractOCRHelper
{

  /**
    * The original image to be analyzed
    * @var string
    */
    protected $image;
	
  /**
    * Path of the temp image
    * @var string
    */
    private $filepath;
    
  /**
    * Class constructor, loads the image to be recognized
    *
    * @param string $image Path to the image to be recognized
    */
    public function __construct($image)
    {
      $this->image = $image;
      $this->filepath = "/tmp/crop_" . basename($this->image);
    }

  /**
    * Performs the seqence of steps needed to recognize text inside image
    *
    * @return string
    */
    public function recognition()
    {
      $tesseract = new TesseractOCR($this->filepath);
      $tesseract->setLanguage('eng');
      $recognizedText =  $tesseract->recognize();
      return $recognizedText;
    }
	
  /**
    * Performs the seqence of steps needed to crop image
    *
    * @params int, int, int, int
    * @return void
    */
    public function cropImage($width, $height, $startx, $starty)
    {
      $src = imagecreatefromjpeg($this->image);
      $dest = imagecreatetruecolor($width, $height);
      
      imagecopy($dest, $src, 0, 0, $startx, $starty, $width, $height);
      imagefilter($dest, IMG_FILTER_GRAYSCALE);
      imagejpeg($dest, $this->filepath);
      imagedestroy($dest);
    }
	
  /**
    * Delete temp image
    *
    * @return void
    */
    public function deleteTmp()
    {
      unlink($this->filepath);
    }

  /**
   * Performs the sequence of steps needed to recognize the patient name
   *
   * @return string
   */
   public function nameRow()
   {
     $this->cropImage(350, 27, 360, 2);
     $recognizedText = $this->recognition();
     $this->deleteTmp();
     return $recognizedText;
   }

  /**
    * Performs the sequence of steps needed to recognize text inside the first row of the image
    *
    * @return string
    */
    public function firstRow()
    {
      $this->cropImage(300, 27, 28, 709);
      $recognizedText = $this->recognition();
      $this->deleteTmp();
      return $recognizedText;
    }
	
  /**
    * Performs the sequence of steps needed to recognize text inside the second row of the image
    *
    * @return string
    */
    public function secondRow()
    {
      $this->cropImage(300, 27, 28, 739);
      $recognizedText = $this->recognition();
      $this->deleteTmp();
      return $recognizedText;
    }

}
