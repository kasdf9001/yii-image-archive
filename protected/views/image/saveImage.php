<?php
/*
 * Based on tutorial from www.boutell.com/newfaq/creating/forcedownload.html
 * Most of the items in this .php file are related to naming the output file.
 * 
 * DO NOT ADD BLANK ROWS AT TOP OR BOTTOM: THEY WILL BE INCLUDED IN THE OUTPUT FILE
 * TURNING ON LOGGING WILL ALSO CAUSE LOGGING OUTPUT TO APPEAR AT THE END OF THE JPG
 */
$fileDate = $this->explodeDate($image->pvm);
$filename = $fileDate['year'] .'-'. $fileDate['month'] .'-'. $fileDate['day'];
$kuvateksti = $image->kuvateksti;
$strippedKuvateksti = preg_replace("/[^a-zA-Z0-9\s]/", "", $kuvateksti);
$strippedKuvateksti = preg_replace("/[ ]/", "_", $strippedKuvateksti);
// also removing line breaks
$strippedKuvateksti = preg_replace("/[\n\r\t]/", "_", $strippedKuvateksti);
$strippedKuvateksti = mb_substr($strippedKuvateksti,0,50,'UTF-8');
$filename .= '-[' . $image->cd . '-' . $image->id . ']';
$filename .= '-' . $strippedKuvateksti;
$filename .= '.' . $image->tiedostotyyppi;
header('Content-disposition: attachment; filename="' . $filename . '"');
header('Content-type: image/' . $image->tiedostotyyppi);
readfile(Image::FULLIMAGEPATH . $image->cd . '/img' . $image->id . '.' . $image->tiedostotyyppi);
?>