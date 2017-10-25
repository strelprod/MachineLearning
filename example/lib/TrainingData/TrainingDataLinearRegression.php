<?php

class TrainingDataLinearRegression extends TrainingData{
	private $trainingDataNormalizeArray = array();
    private $trainingDataStringForGraphic = "";
    private $sigma = array();
    private $avg = array();
	
	public function __construct($data) {
		parent::__construct($data);
        $this->trainingDataArray = $data;
        
        $this->numberOfTrainingData = count($this->trainingDataArray[0]);
        $this->numberOfFeatures = count($this->trainingDataArray);
        
        $this->featureNormalize();
    }
	
	private function featureNormalize(){
        $sum = array();
        for ($i = 1; $i < $this->numberOfFeatures - 1; $i++){
            $sum[$i] = array_sum($this->trainingDataArray[$i]);
            $this->avg[$i] = $sum[$i] / $this->numberOfTrainingData;
            $this->sigma[$i] = MathFunctions::stats_standard_deviation($this->trainingDataArray[$i]);
        }

        for ($i = 1; $i < $this->numberOfFeatures - 1; $i++)
            for ($j = 0; $j < $this->numberOfTrainingData; $j++){
                $this->trainingDataNormalizeArray[0][$j] = $this->trainingDataArray[0][$j];
                $this->trainingDataNormalizeArray[$i][$j] = 
                    ($this->trainingDataArray[$i][$j] - $this->avg[$i]) / $this->sigma[$i];
                $this->trainingDataNormalizeArray[$this->numberOfFeatures - 1][$j] 
                        = $this->trainingDataArray[$this->numberOfFeatures - 1][$j];
            }
    }
	
	public static function getTrainingDataStringForGraphic($J_history){
        $tmp = "";
        foreach ($J_history as $line_num => $line){
                $tmp .= "{$line[0]},";
        }
        $tmp = substr($tmp, 0, strlen($tmp) - 1);
        return $tmp;
    }
	
	public function getTrainingDataNormalizeArray (){
        return $this->trainingDataNormalizeArray;
    }
    
    public function getSigma (){
        return $this->sigma;
    }
    
    public function getAvg (){
        return $this->avg;
    }
}