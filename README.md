# Machine Learning
***
## Linear Regression
1. To find the parameters for the hypotesis (for linear regression) firstly you should to create an object of the class.

Required parameter - the array of the training set.

Optional parameters - alpha and the number of iteration. (By default: alpha = 0.1, number of iterations = 100)

```
$linearRegression = new LinearRegression($TrainingDataArray);
```

2. Secondly you should to call the ```getTrainingDataArray()``` method to compute theta

```
$gradient = $linearRegression->gradientDescent();
```

This method returns:
* Array of **Cost function history** (its uses to find optimal number of iterations and alpha parameter)
* Array of **theta** parameter
* Array of **sigma** values for normalized training set for next prediction
* Array of **avg** values for normalized training set for next prediction

3. Using computed parameter **theta** we can predict values uses ```prediction()``` method

```
$predictionValue = $linearRegression->prediction($valuesForPrediction);
```
