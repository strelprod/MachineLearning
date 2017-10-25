<?php

class LinearRegression {
    protected $alpha = 0.1;
    protected $iterations = 100;
    protected $theta = array();
    protected $trainingData;
    
    public function getTrainingData(){
        return $this->trainingData;
    }
    
    public function __construct($data, $alpha = null, $iterations = null) {
        $this->trainingData = new TrainingDataLinearRegression($data);

        for ($i = 0; $i < $this->trainingData->getNumberOfFeatures() - 1; $i++)
            $this->theta[$i] = array(0.0);
        if (isset($alpha))
            $this->alpha = $alpha;
        if (isset($iterations))
            $this->iterations = $iterations;
    }
    
    protected function hypothesysFunction ($trainingDataNumber){
        $hypothesys = 0;
        for ($i = 0; $i < count($this->theta); $i++)
           $hypothesys +=  $this->theta[$i][0] * 
                $this->trainingData->getTrainingDataNormalizeArray()[$i][$trainingDataNumber];
        return $hypothesys;
    }
    
    public function costFunction (){
        $J = 0;
        for ($i = 0; $i < $this->trainingData->getNumberOfTrainingData(); $i++)
           $J += pow($this->hypothesysFunction($i) - 
                   $this->trainingData->getTrainingDataNormalizeArray()[$this->trainingData->getNumberOfFeatures() - 1][$i], 2);
        $J /= 2 * $this->trainingData->getNumberOfTrainingData();
        return $J;
    }
    
    public function gradientDescent (){
        $J_history = array();
        $currentSum = array();
        $hypotesys = 0;
        for ($i = 0; $i < $this->iterations; $i++) {
            for ($k = 0; $k < count($this->theta); $k++){
                $currentSum[$k] = 0;
                for ($j = 0; $j < $this->trainingData->getNumberOfTrainingData(); $j++){
                    $hypotesys = $this->hypothesysFunction($j);
                    $currentSum[$k] += ($hypotesys - 
                            $this->trainingData->getTrainingDataNormalizeArray()[$this->trainingData->getNumberOfFeatures() - 1][$j]) *
                        $this->trainingData->getTrainingDataNormalizeArray()[$k][$j];
                }
                $this->theta[$k][0] = $this->theta[$k][0] - 
                        ($this->alpha / $this->trainingData->getNumberOfTrainingData()) 
                        * $currentSum[$k];
            }
            $J_history[$i][0] = $this->costFunction();
        }
        return [$J_history, $this->theta];
    }
    
    public function prediction($arrayValues, $theta = null, $sigma = null, $avg = null){
		$numberOfFeatures = count($arrayValues);
		if ($numberOfFeatures != count($avg))
				throw new RuntimeException('Incorrect values for prediction');
		$predictionValue = $theta[0][0];
		
		if (isset($numberOfFeatures) && isset($theta) && isset($sigma) && isset($avg)){
			
			
			$predictionValue = $theta[0][0];
			for ($j = 0; $j < $numberOfFeatures; $j++)
				 $predictionValue += ($theta[$j + 1][0] * (($arrayValues[$j] 
					- $avg[$j + 1]) / $sigma[$j + 1]));
			return $predictionValue;
		}
		else {
			$numberOfFeatures = $this->trainingData->getNumberOfFeatures();
			$theta = $this->theta;
			$sigma = $this->trainingData->getSigma();
			$avg = $this->trainingData->getAvg();
			
			for ($i = 1, $j = 0; $i < $numberOfFeatures - 1; $i++, $j++)
				 $predictionValue += ($theta[$i][0] * (($arrayValues[$j] 
					- $avg[$i]) / $sigma[$i]));
			return $predictionValue;
		}
    }
}