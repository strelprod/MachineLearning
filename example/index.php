<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	define('ROOT', dirname(__FILE__));
	define('FILES_DIR', '/files/');
	define('INPUT_FILE_NAME', 'upfile');
	define('MAX_FILE_SIZE', 30000);
	define('AJAX_LINEAR_REGRESSION', 'index.php');

	require_once ('autoload.php');
	
	function jsonReturn($status, $msg = null, $data = null){
		$result['status'] = $status;
		$result['msg'] = $msg;
		$result['data'] = $data;
		echo json_encode($result);
		die();
	}
	
	if ($_POST){
		if (isset($_POST['upload'])){
			try{
				$fileUpload = new FileUpload();
				$fileParser = new FileParser($fileUpload->getFileURL());
				
				$linearRegression = new LinearRegression($fileParser->getTrainingDataArray(), 
					   abs(floatval($_POST['alpha'])), abs(intval($_POST['iter'])));
				$gradient = $linearRegression->gradientDescent();
				
				$resultArray['j_history'] = $gradient[0];
				$resultArray['theta'] = $gradient[1];
				$resultArray['sigma'] = $linearRegression->getTrainingData()->getSigma();
				$resultArray['avg'] = $linearRegression->getTrainingData()->getAvg();
				
				jsonReturn("success", null, $resultArray);
			}
			catch (RuntimeException $e){
				jsonReturn("error", $e->getMessage(), null);
			}
		}
		else
			jsonReturn("error", "Incorrect post data");
	}
	else
		include 'exampleLinearRegression.php';




