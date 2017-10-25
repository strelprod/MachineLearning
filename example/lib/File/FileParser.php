<?php

class FileParser {
    protected $trainingDataArray = array();
    
    public function getTrainingDataArray(){
        return $this->trainingDataArray;
    }
    
    public function __construct($file) {
        if (!file_exists($file))
            throw new RuntimeException('File is not exist');
        
        $parserName = "";
        switch (substr(strrchr($file, '.'), 1)){
            case 'txt' :
                $parserName = 'Txt';
                break;
            default :
               throw new RuntimeException('Invalid file format (Should be: .txt).'); 
        }
        $parserName = 'FileParser' . $parserName;
        $fileParse = new $parserName($file);
        $this->trainingDataArray = $fileParse->getTrainingDataArray();
    }
    
    public function __destruct() {
        unset($this->trainingDataArray);
    }
}
