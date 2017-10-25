<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Highcharts Demo</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML"></script>
  <script type="text/x-mathjax-config">MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});</script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <style type="text/css">
    #cont {
	min-width: 310px;
	max-width: 800px;
	height: auto;
	margin: 0 auto;
        margin-bottom: 5%;
    }
  </style>

    <script>
        var upFile = true, sigma, avg, h, hypLength = 0, fileSize = parseInt(<?=MAX_FILE_SIZE?>);

        function showError(error, hide = null){
            if (hide)
                $('#' + hide).hide();
            $('#error').empty();
            $('#error').append(error);
            $('#errors').show();
        }

        function hideError(show = null){
            if (show)
               $('#' + show).show();
            $('#errors').hide();
            $('#error').empty();
        }

        function showHypotesis(hyp){
            $('#cont').append("<br><b>Hypothesis:</b>");
            hyp.forEach(function(item, i, arr) {
                $('#cont').append("<br>[" + i + "] " + item);
            });

            //$('#cont').append("<b>Hypothesis:</b> $h_\theta(x)$ = ");
            //var j = 0;
            //console.log(hyp.length);
           // hyp.forEach(function(item, i, arr) {
               // if (arr.length == i + 1)
                  //  $('#cont').append(item + " $x_" + (j++) + "$");
             //   else
                   // $('#cont').append(item + " $x_" + (j++) + "$ + ");
          //  });

        }

        //check file's size and extension
        function checkFile() {cont
            $('#cont').empty();
            $('#prediction').hide();
            $('#formPrediction').empty();
            var file = document.getElementById("uploadFile").files[0], ext = "";
            if (file){
                var parts = file.name.split('.');
                if (parts.length > 1) ext = parts.pop();
                if (ext != 'txt' && file.type != 'text/plain' || ext == ""){
                    showError("Incorrect file extension", "Upload");
                    upFile = false;
                }
                else if (file.size > fileSize){
                    showError("File size should be < <?=MAX_FILE_SIZE?> Bytes", "Upload");
                    upFile = false;
                }
                else{
                    upFile = true;
                    hideError("Upload");
                }
            }
        }

        //get data from json array
        function getData(stats) {
            var result = [];
            for (var j = 0; j < stats.length; j++) 
                result.push(stats[j]);   
            return result;
        }

        //Make a plot with number of iterations on the x-axix and 
        //plot the cost function, J(θ) over the number of iterations of gradient descent. 
        function graphTheFunction(dataStringForGraphic){
            Highcharts.chart('cont', {
                title: {
                    text: 'J(θ) over the number of iterations of gradient descent'
                },
                yAxis: {
                    title: {
                        text: 'min(J(θ))'
                    }
                },
                 xAxis: {
                    title: {
                        text: 'No. of iterations'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    series: {
                        pointStart: 0,
                        pointInterval: 1
                    }
                },
                series: [{
                        type: "line",
                    name: 'J(θ)',
                    data: getData(dataStringForGraphic)
                }]
            });
        }

        function prediction(values){
            var pred = parseFloat(h[0]);
            for (var i = 1; i <= hypLength - 1; i++)
                pred += ((parseFloat(values[i]) - parseFloat(avg[i])) / parseFloat(sigma[i])) * parseFloat(h[i]);
            $('#cont').append("<br><b>Prediction value:</b> " + pred);
        }

        function addFieldsForPrediction(count){
            $('#formPrediction').append("<b>Values for prediction:</b><br>");
            for (var i = 1; i < count; i++){
                $('#formPrediction').append("x" + i + ":<br><input id=\"x" + i + "\" name=\"x[]\" type=\"text\"/><br>"); 
            }
            $('#formPrediction').append("<br><input type=\"submit\" value=\"Predict\"  id=\"Predict\"/>");
        }

        $(function(){
            $('#prediction').hide(); 
            $('#errors').hide();

            $('#formPrediction').on('submit', function(e){
                e.preventDefault();
                var values = [];
                for (var i = 1; i < hypLength; i++)
                    values[i] = $('#x' + i).val();
                console.log(values);
                prediction(values);
            });
            
            //Upload file listener (checking size and extension)
            document.getElementById('uploadFile').addEventListener('change', checkFile);

            //form processing 
            $('#formUpload').on('submit', function(e){
                e.preventDefault();
                $('#prediction').hide();
                $('#formPrediction').empty();
                var $that = $(this),
                formData = new FormData($that.get(0));
                if (upFile) {
                    $.ajax({
                      url: $that.attr('action'),
                      type: $that.attr('method'),
                      contentType: false,
                      processData: false,
                      data: formData,
                      dataType: 'json',
                      success: function(response){
                        if(response.status == "success"){;
                            $('#prediction').show();
                            graphTheFunction(response.data.j_history);
                            avg = response.data.avg;
                            sigma = response.data.sigma;
                            h = response.data.theta;
                            showHypotesis(h);
                            console.log(avg);
                            hypLength = h.length;
                            addFieldsForPrediction(hypLength);
                        }
                        if (response.status == "error") showError(response.msg);
                      },
                      error: function(request, status, error) {
                          showError("<b>Error text: </b>" + request.responseText + "<br><b>Status:</b> " + status + "<br><b>Error:</b> " + error);
                        }
                    });
                }
                else
                    showError("Please, select a new file to upload.");
            });
        });
    </script>
</head>
<body>
    
<div class="container">
    <h1>Multivariate Linear Regression</h1>
    <div class="row jumbotron">
        <div class="col-sm-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Some theory</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body" style="padding: 15px;background-color: #dde2e6;border-radius: 5px;">
                            <b>Hypothesis:</b> $h_\theta(x) = \Theta^Tx = \Theta_0x_0 + \Theta_nx_n + ... + \Theta_nx_n$, where $n$ - number of features and $x_0 = 1$<br>
                            <b>Parameters:</b> $\Theta_0, \Theta_1, ..., \Theta_n$<br>
                            <b>Cost function:</b> $J(\Theta_0, \Theta_1, ..., \Theta_n) = \frac{1}{2m} \sum_{i=1}^m (h_\theta(x^{(i)}) - y^{(i)})^2$, where $m$ - number of training examples<br>
                            <b>Gradient descent:</b><br>
                            <b>Repeat</b> {<br> 
                            $\Theta_j := \Theta_j - \alpha \frac{1}{m} \sum_{i=1}^m (h_\theta(x^{(i)}) - y^{(i)})^2x_j^{(i)}$<br>
                            } <i>(simultaneously update for every</i> $j = 0, ..., n$<i>)</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12" style="margin-bottom:1%;">
            <div class="panel-group" id="accordion_1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion_1" href="#collapseTwo">Example</a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body" style="padding: 15px;background-color: #dde2e6;border-radius: 5px;">
                            <b>Input file should look like:</b><br>
                            <img src="exmpl.png"><br>
                            Where the first columns are input features and the last column is always the "output" or target variable that we are trying to predict.<br>
                            Coloumns are seperated by comma, values could be a float type (e.g. 1.245).<br>
                            <b>File size:</b> < 30000 Bytes<br>
                            <b>File extension:</b> '.txt'<br>
                            <b>You can  <a href="data.txt" target="_blank">download the file</a> with training set to check how Multivariate Linear Regression works.</b><br>
                            Using <b>alpha = 0.01</b> and number of <b>iterations = 100</b> you will get this reuslts:<br>
                            <b>Hypothesis:</b> $h_\theta(x)$ = 215810.61679138$x_0$ + 61504.659737116$x_1$ + 19861.114481301$x_2$
                        </div>
                    </div>
                </div> 
            </div>
        </div>   
        <div class="col-sm-4">
            <h2>Training data</h2><br>
            <form enctype="multipart/form-data" method="POST" id="formUpload" action="<?=AJAX_LINEAR_REGRESSION?>" >
                <input type="hidden" name="MAX_FILE_SIZE" value="<?=MAX_FILE_SIZE?>" />
                <b>Upload your training data:</b><br>
                <input  id="uploadFile" name="upfile" type="file" /><br><br>
                <b>Alpha:</b><br>
                <input name="alpha" type="text" value="0.01"/>  <br> <br>
                <b>Number of iterations:</b><br>
                <input name="iter" type="text" value="100"/> <br> <br> 
                <input type="hidden" name="upload" value="1" />
                <input type="submit" value="Upload" id="Upload"/>
            </form><br>
            <div id="errors" class="alert alert-danger" role="alert" style="display:none;">
                <strong>ERROR!</strong> <span id="error"></span>
            </div>
            <div id="prediction" style="display: none;">  
                <form id="formPrediction"></form>
            </div>   
        </div>
        <div class="col-sm-8">
            <h2>Results</h2><br>
            <div id="cont"></div>
            <div class="col-sm-12">
                <div class="row"></div> 
            </div>
        </div>
    </div> 
</div>
</body>
</html>