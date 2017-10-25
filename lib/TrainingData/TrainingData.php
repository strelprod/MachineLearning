<?php

class TrainingData{
    protected $numberOfFeatures = 0;
    protected $numberOfTrainingData = 0;
    protected $trainingDataArray = array();

    public function __construct($data) {
        if (!isset($data))
            throw new RuntimeException('There is no training data');
        
        $this->trainingDataArray = $data;
        
        //$fileParse = new Parser($file);
        //$this->trainingDataArray = $fileParse->getTrainingDataArray();
    }
    
    public function getNumberOfFeatures (){
        return $this->numberOfFeatures;
    }
    
    public function getNumberOfTrainingData (){
        return $this->numberOfTrainingData;
    }
    
    public function getTrainingDataArray (){
        return $this->trainingDataArray;
    }
}
