<?php

class FileUpload{
    private $uploadDir = "";
    private $uploadFileName = "";
    private $inputFileName = "";
    private $maxFileSize = 0;
    
    public function __construct() {
        $this->uploadDir = ROOT . FILES_DIR;
        $this->uploadFileName = "";
        $this->inputFileName = INPUT_FILE_NAME;
        $this->maxFileSize = MAX_FILE_SIZE;

        if (!isset($_FILES[$this->inputFileName]['error']) || 
                is_array($_FILES[$this->inputFileName]['error'])) 
            throw new RuntimeException('File error.');
        
        switch ($_FILES[$this->inputFileName]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
        
        if ($_FILES[$this->inputFileName]['size'] > $this->maxFileSize) 
            throw new RuntimeException('Exceeded filesize limit.');
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        //var_dump($finfo->file($_FILES[$this->inputFileName]['tmp_name']));
        //die();
        if (false === $ext = array_search(
                $finfo->file($_FILES[$this->inputFileName]['tmp_name']),
                array(
                    'txt' => 'text/plain'
                ),
                true
            )
        ) 
            throw new RuntimeException('Invalid file format.');
        
        $this->uploadFileName = sprintf('%s.%s', 
                sha1_file($_FILES[$this->inputFileName]['tmp_name']), $ext);
        
        ini_set('date.timezone', 'Europe/Moscow');

        if (!move_uploaded_file($_FILES[$this->inputFileName]['tmp_name'], 
                sprintf($this->uploadDir . '%s.%s', 
                    sha1_file($_FILES[$this->inputFileName]['tmp_name']), $ext))) 
            throw new RuntimeException('Failed file processing.');
    }
	
	public function __destruct() {
		unlink($this->getFileURL());
		unset($this->uploadDir, $this->uploadFileName, $this->inputFileName, $this->maxFileSize);
	}
    
    public function getFileURL(){
        return $this->uploadDir . $this->uploadFileName;
    }
}