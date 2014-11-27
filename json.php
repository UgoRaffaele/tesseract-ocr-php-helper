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
    $data['raw'][] = $firstRow;
  	
  $secondRow = $imageOCR->secondRow();
  if (strlen($secondRow) > 0)
    $data['raw'][] = $secondRow;
  	
  function splitData($string) {
    $result = array();
    $tok = strtok($string, " ");
    while ($tok !== false) {
      $result[] = $tok;
      $tok = strtok(" ");
    }
    /*
    * Diiiiiiirty hack remove all non-numeric characters (wrong read)
    * only from the last value: % is not always correctly recognized
    */
    if ($result[4])
      $result[4] = number_format(preg_replace("/[^0-9.]/", "", $result[4]), 1);
    return $result;
  }	
  
  foreach ($data['raw'] as $dato) {
    if (preg_match('/^BPD /', $dato) || preg_match('/^HC /', $dato) || preg_match('/^AC /', $dato) || preg_match('/^FL /', $dato))
      $data['values'][] = splitData($dato);
    else if (preg_match('/^BPD/', $dato))
      $data['values'][] = splitData(preg_replace("/^(BPD)/", "BPD ", $dato));
    else if (preg_match('/^HC/', $dato))
      $data['values'][] = splitData(preg_replace("/^(HC)/", "HC ", $dato));
    else if (preg_match('/^AC/', $dato))
      $data['values'][] = splitData(preg_replace("/^(AC)/", "AC ", $dato));
    else if (preg_match('/^FL/', $dato))
      $data['values'][] = splitData(preg_replace("/^(FL)/", "FL ", $dato));
  }	

  /* Remove raw data unless debugging */
  if (DEBUG == 0)
    unset($data['raw']);
  
  echo json_encode($data);

?>
