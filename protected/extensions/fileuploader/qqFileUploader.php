<?php

class qqFileUploader {
	private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       
		
        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
        
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     * Added the $filename and $ext.
     */
    function handleUpload($uploadDirectory, $filename, $ext, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
        	return array('error' => "Virhe palvelimella. Hakemistoon ei voi kirjoittaa.");
           // return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
        	return array('error' => 'Tiedoston lähetys epäonnistui.');
            // return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
        	return array('error' => 'Tyhjä tiedosto.');
            // return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
        	return array('error' => 'Liian suuri tiedosto.');
            // return array('error' => 'File is too large');
        }
        
        // Things on the original script, filename
        //$pathinfo = pathinfo($this->file->getName());
        //$filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        //$ext = $pathinfo['extension'];
        
        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Virheellinen tiedostopääte, vain nämä kelpaavat; '. $these . '.');
        }
        
        /**
         * There should never be a need to replace a file.
         */
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            if (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                return array('error'=> 'Palvelimella on tiedosto samalla cd/id arvolla.' . 
                	'Tietokannassa on virhe, tai palvelimella tapahtui virhe.');
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true);
        } else {
            return array('error'=> 'Tiedostoa ei voitu tallentaa.' .
                'Lähetys katkaistiin, tai palvelimella tapahtui virhe.');
        }
        
    }    
}

?>