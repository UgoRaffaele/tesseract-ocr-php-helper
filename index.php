<?php

  require_once('TesseractOCR/TesseractOCR.php');
  require_once('TesseractOCR/TesseractOCRHelper.php');
  
  
  $imageOCR = new TesseractOCRHelper("images/Image296.jpg");
  echo $imageOCR->firstRow();
  echo "<br/>";
  echo $imageOCR->secondRow();

?>
