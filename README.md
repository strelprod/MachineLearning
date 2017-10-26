# Machine Learning

Unfortunately, implementation of my algorithm works whitout linear algebra. So...

1. The file with training set should look like:

x1,x2,y

x1,x2,y

*If values are float type then: 1.23,4.35,5*

Where x1, x2, ..., xn - input variables and y is output or target variable

*You can download [**data.txt**](https://github.com/strelprod/MachineLearning/blob/master/example/data.txt) from **/example** folder to see how it look like*

2. It is neccessary to upload (POST-request) the file using class **FileUpload**. *If you know full path (file already has been uploaded), go to the next step*
```
$fileUpload = new FileUpload();
```
3. Strings from the file should be converted  to data array of float type
```
$fileParser = new FileParser($fileUpload->getFileURL());
```

This method returns:
* Array of input variables and output variable

## Linear Regression

It is working without regoualization and automatically alpha choosing. So you need to input your values (alpha and number of iterations) or to use default values.

1. To find the parameters for the hypotesis (for linear regression) firstly you should to create an object of the class.

Required parameter - the array of the training set.

Optional parameters - alpha and the number of iteration. (By default: alpha = 0.1, number of iterations = 100)

```
$linearRegression = new LinearRegression($fileParser->getTrainingDataArray());
```

Constructor uses **TrainingDataLinearRegression** class to convert training data to desired data (adding x0 = 1 and mean normalization)

2. Secondly you should to call the ```getTrainingDataArray()``` method to compute theta

```
$gradient = $linearRegression->gradientDescent();
```

This method returns:
* Array of **Cost function history** (its uses to find optimal number of iterations and alpha parameter)
* Array of **theta** parameter


3. Using computed parameter **theta** we can predict values uses ```prediction()``` method

Required parameter - the array of values for prediction.

```
$predictionValue = $linearRegression->prediction($valuesForPrediction);
```

This method returns:
* Scalar value of the hypothesis function

4. You can run **index.php** from **/example** folder to see how it work on practice

## Logistic Regression
