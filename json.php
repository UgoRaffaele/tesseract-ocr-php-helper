<?php

  require_once('cfg.php');
  require_once('TesseractOCR/TesseractOCR.php');
  require_once('TesseractOCR/TesseractOCRHelper.php');
  
  $sourcePath = $_FILES['file']['tmp_name'];
  $targetPath = $basepath . "upload/images/" . $_FILES['file']['name'];
  move_uploaded_file($sourcePath, $targetPath);
  
  $imageOCR = new TesseractOCRHelper($targetPath);
  
  $data = array();
  
  $name = $imageOCR->nameRow();
  if (strlen($name) > 0)
  	$data['name'] = $name;
  
  $firstRow = $imageOCR->firstRow();
  if (strlen($firstRow) > 0)
  	$data['values'][] = $firstRow;
  	
  $secondRow = $imageOCR->secondRow();
  if (strlen($secondRow) > 0)
  	$data['values'][] = $secondRow;
  
  echo json_encode($data);

?>
