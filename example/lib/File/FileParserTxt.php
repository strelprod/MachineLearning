<?php

class FileParserTxt extends FileParser {
    
    public function getTrainingDataArray(){
        return $this->trainingDataArray;
    }
    
    public function __construct($file) {
        $trainingDataFile = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if(preg_match("/[^\d^.^,^\\n^-]/", iconv('windows-1251', 'utf-8', file_get_contents($file))))
            throw new RuntimeException('Incorrect data file (syntax error)');

        $prevNumOfFeatures = count(explode(',', $trainingDataFile[0]));
        foreach ($trainingDataFile as $lineNum => $lineValue) {
            $currentLine = explode(',', $lineValue);
            
            if ($prevNumOfFeatures != count($currentLine))
                throw new RuntimeException('Incorrect data file (syntax error, format: x1,x2,y. E. g. 10.1, 123.2, 1)');
            
            $this->trainingDataArray[0][$lineNum] = 1.0;
            
            foreach ($currentLine as $feature => $featureValue) 
                $this->trainingDataArray[$feature + 1][$lineNum] = floatval($featureValue);
           
            unset($currentLine); 
        }
    }
}