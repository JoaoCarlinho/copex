<!doctype>
<html>
<head>
	        <!-- Title -->
        <title>REIQ Dashboard</title>
        <!-- Meta Tags -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <!-- style -->
        <link rel="stylesheet" type="text/css" href="barGraph.css"/>
</head>
<body>
<?php

include'copexConnect.php';
function locateMSA($msa){
	$db = copexConnect();
	$query = $db->prepare("SELECT msa FROM us_msareas WHERE msa=:msa LIMIT 1") or die("could not check users");
    $query->bindParam(':msa', $msa);
    $query->execute();
    $row = $query->fetchAll(PDO::FETCH_ASSOC);
    $count = count($row);
    $db = null;
    
    return $count;
}
//homeownership rates by MSA

		//inlcude phpexcel function to pull docment into array
        require_once "../copexData/Classes/PHPExcel.php";
		//$tmpfname = "test.xlsx";
		$url = "https://www.census.gov/housing/hvs/data/rates/tab6_msa_15_16_hmr.xlsx";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$sheetArray = $worksheet->toArray(null, false,true, false);
/**************************Need to read two excel sheets since first one only has data from 2014 on*********/
		$url = "https://www.census.gov/housing/hvs/data/rates/tab6a_msa_05_2014_hmr.xlsx";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheetAddition = $excelObj->getSheet(0);
		$additionArray = $worksheetAddition->toArray(null, false, true, false);
		$sheetArray[] = $additionArray;

//		$lastRow = count($sheetArray);
//		echo '<script type="text/javascript">alert("'.$lastRow.' rows");</script>';
		
		//echo "<center><pre>";
        //	  print_r($sheetArray);
        //echo "</pre></center>";
        
        
        //print selected array indexes to page
        //write selected indexes to file
/**
        $handle = fopen('msaHomeownershipRates.txt', 'w');
        $year = 2016;
        
        for($row = 8; $row < 82; $row++){    //foreach($sheetArray as $line){
        
	        
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        	if(is_array($sheetArray[$row])){
                //foreach($sheetArray[$row] as $key => $value)
                $msaInfoArray = $sheetArray[$row];
                $replace = array(".","/", "0","1","2","3","4","5","6","7","8","9");
                $msa = str_replace($replace, "", $msaInfoArray[1]);
                $msaYear = $msa.'_'.$year;
                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
                	$value = $msaInfoArray[$valueIndex];
                	if(is_null($value)){
                		
                	}else{
                			if($valueIndex == 1){
                				echo("<br/>");
	                			fwrite($handle, '_'."\n");
                			}
                			
                			$mod = $valueIndex % 2;
                			$quarter =  $valueIndex /2;
                			if($mod==0){
        						$data = 'Q'.$quarter.'_'.$value." ";
	                			fwrite($handle, $data);
	                			$dataArray[$quarter] = $value;
                			}
                			
                			if($quarter == 4){
                				if(locateMSA($msa) == 1){
							$db = copexConnect();
							$query = $db->prepare("INSERT INTO msa_homeownership_rates (msa_year, Q1, Q2, Q3, Q4, year, msa) VALUES(?, ?, ?, ?, ?, ?, ?)") or die("Could not set up Account");
							$query->bindParam(1, $msaYear);
							$query->bindParam(2, $dataArray[1]);
							$query->bindParam(3, $dataArray[2]);
							$query->bindParam(4, $dataArray[3]);
							$query->bindParam(5, $dataArray[4]);
							$query->bindParam(6, $year);
							$query->bindParam(7, $msa);
							$query->execute();
							$db = null;
	                			}else{	
	                				$msAreas[] = $msa;
	                				
	                			}
                			}
                	}
                }
			}else{
			 	echo $sheetArray[$row]."<br/>";
			}
        }
        echo"<br/><br/><br/><br/>";
        $year -= 1;
        for($row = 93; $row < 167; $row++){    //foreach($sheetArray as $line){
        
	        
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        	if(is_array($sheetArray[$row])){
                //foreach($sheetArray[$row] as $key => $value)
                $msaInfoArray = $sheetArray[$row];
                $replace = array(".","/", "0","1","2","3","4","5","6","7","8","9");
                $msa = str_replace($replace, "", $msaInfoArray[1]);
                $msaYear = $msa.'_'.$year;
                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
                	$value = $msaInfoArray[$valueIndex];
                	if(is_null($value)){
                		
                	}else{
                			if($valueIndex == 1){
                				echo("<br/>");
	                			fwrite($handle, '_'."\n");
                			}
                			
                			$mod = $valueIndex % 2;
                			$quarter =  $valueIndex /2;
                			if($mod==0){
        						$data = 'Q'.$quarter.'_'.$value." ";
	                			fwrite($handle, $data);
	                			$dataArray[$quarter] = $value;
                			}
                			
                			if($quarter == 4){
                				if(locateMSA($msa) == 1){
						$db = copexConnect();
						$registrationDate = date("Y-m-d");
						$query = $db->prepare("INSERT INTO msa_homeownership_rates (msa_year, Q1, Q2, Q3, Q4, year, msa) VALUES(?, ?, ?, ?, ?, ?, ?)") or die("Could not set up Account");
							$query->bindParam(1, $msaYear);
							$query->bindParam(2, $dataArray[1]);
							$query->bindParam(3, $dataArray[2]);
							$query->bindParam(4, $dataArray[3]);
							$query->bindParam(5, $dataArray[4]);
							$query->bindParam(6, $year);
							$query->bindParam(7, $msa);
						$query->execute();
						$db = null;
	                			}
                			}
                	}
                }
			}else{
			 	echo $sheetArray[$row]."<br/>";
			}
        }
        
        $secondArray = $sheetArray[220];
        
        //echo "<center><pre>";
        //	  print_r($secondArray);
        //echo "</pre></center>";
        $stopPoint = count($secondArray);
        $row = 8;
        $msaIndex = 1;
        $year-=1;
        while($row < $stopPoint){    //foreach($sheetArray as $line){
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        	if(is_array($secondArray[$row])){
		                //foreach($sheetArray[$row] as $key => $value)
		                $msaInfoArray = $secondArray[$row];
		                $replace = array(".","/", "0","1","2","3","4","5","6","7","8","9");
		                $msa = str_replace($replace, "", $msaInfoArray[1]);
		                $msaYear = $msa.'_'.$year;
		                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
		                	$value = $msaInfoArray[$valueIndex];
		                	if(is_null($value)){
		                		
		                	}else{
		                			if($valueIndex == 1){
			                			fwrite($handle, '_'."\n");
		                			}
		                			
		                			$mod = $valueIndex % 2;
		                			$quarter =  $valueIndex /2;
		                			if($mod==0){
		        					$data = 'Q'.$quarter.'_'.$value." ";
			                			fwrite($handle, $data);
			                			$dataArray[$quarter] = $value;
		                			}
		                			
		                			if($quarter == 4){
		                				if(locateMSA($msa) == 1){
									$db = copexConnect();
									$query = $db->prepare("INSERT INTO msa_homeownership_rates (msa_year, Q1, Q2, Q3, Q4, year, msa) VALUES(?, ?, ?, ?, ?, ?, ?)") or die("Could not set up Account");
        							$query->bindParam(1, $msaYear);
        							$query->bindParam(2, $dataArray[1]);
        							$query->bindParam(3, $dataArray[2]);
        							$query->bindParam(4, $dataArray[3]);
        							$query->bindParam(5, $dataArray[4]);
        							$query->bindParam(6, $year);
        							$query->bindParam(7, $msa);
									$query->execute();
									$db = null;
			                			}
		                			}
		                	}
		                }
			}else{
			 	echo $secondArray[$row]."<br/>";
			}
			
			
			if($msaIndex == 75  && $quarter == 4){
				$row+=11;
				$msaIndex = 1;
				$year-=1;
				
				echo"<br/><br/><br/><br/>";
				
//				echo '<script type="text/javascript">alert("final :'.$row.'");</script>';
				
			}else{
				$row++;
				$msaIndex++;
			}
        }
        
        fclose($handle);
**/
        
        $msaTest = Columbus;
        $graphArray = array();
for($year = 2005; $year<= 2016; $year++){
	// code...
	$db = copexConnect();
    $searchQuery = ("SELECT * 
                    FROM msa_homeownership_rates
                    WHERE msa LIKE ? AND year = ?");
    $params = array("%$msaTest%", $year);    
                     
    $stmt = $db->prepare($searchQuery);
    $stmt->execute($params);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($row);

    if($count > 0){
        foreach($row as $info){
            $msa = $info['msa'];
            $q1 = $info['Q1'];
            $q2 = $info['Q2'];
            $q3 = $info['Q3'];
            $q4 = $info['Q4'];
        }
        //echo '<script type="text/javascript">alert("found '.$msa.'for year :'.$year.'");</script>';
        $graphArray[$year][1] = $q1;
        $graphArray[$year][2] = $q2;
        $graphArray[$year][3] = $q3;
        $graphArray[$year][4] = $q4;
        
    }

}

//echo "<center><pre>";
// 	  print_r($graphArray);
//echo "</pre></center>";

$php_array = array ('Key'=>'a', 'Value'=>'asite.com');
 
?>
       <div id="div0">REIQ Dashboard</div>
       <div id="displayValues"></div>
        <div id="chartArea">
            <div id="yAxis">
                <div id="yIntervalBox"></div>
            </div>
            <div id="div1">
            </div>
            <div id="xAxis">
                 <div id="xIntervalBox">
                     
                 </div>  
            </div>
        </div>
        <div id="div3">Quarter</div><br/>
        
        <!--  Need to create date begin selector and date end selector, will list by quarter of year -->
        <div id="chartOptions">
            <div class="dateSelectBox">
                <label>begin date</label><br/>
                <input type="date" id="beginDate"/>
            </div><br/>
        
            <div class="dateSelectBox">
                <label>end date</label><br/>
                <input type="date" id="endDate"/>
            </div><br/>
        
        
            <!-- Need to create length selector -->
            <label>Data Source</label>
            <select>
                <option>value Estimate</option>
                <option>Property Taxes</option>
            </select>
        </div>
        <script type="text/javascript" src="main.js"></script>

</body>
</html>

<script>

function countInObject(obj) {
    var count = 0;
    // iterate over properties, increment if a non-prototype property
    for(var key in obj) if(obj.hasOwnProperty(key)) count++;
    return count;
}
//sample dataSet
var graphObject = <?php echo json_encode($graphArray); ?>;
var tempArray = <?php echo json_encode($php_array); ?>;
//console.log(tempArray);
//console.log(graphObject);
//alert(graphObject[2006][1]+','+graphObject[2006][2]+','+graphObject[2006][3]+','+graphObject[2006][4]);
var firstYear = 2005;
var firstQuarter = 1;
var lastYear = 2016;
var lastQuarter = 4;

var dataString = '';
var sampleData = new Array();
for(var year = firstYear; year<= lastYear; year++){
    sampleData.push(graphObject[year][1]+year.toString()+'1');
    sampleData.push(graphObject[year][2]+year.toString()+'2');
    sampleData.push(graphObject[year][3]+year.toString()+'3');
    sampleData.push(graphObject[year][4]+year.toString()+'4');
}

//console.log(sampleData);

//function displayValues(divElement){
//    _('displayValues').innerHTML = 'HomeownershipRate: '+divElement.yVal+'% in '+divElement.xVal;
//}

//function to drawBarchart
function drawBarChart(dataSet, idOfContainer){
    
    
    //Make sure the dataSet is an array
    if(typeof(dataSet) != "object"){
        return;
    }
    //get max value on chart
    var maxValue = Math.max.apply(null, dataSet);
    
    //get min value on chart
    var minValue = Math.min.apply(null, dataSet);
    
    //add 10% of maxValue to plot area
    var chartMax = parseInt( maxValue / 10 + maxValue);
    
    
    //set interval for markers
    var yInterval = +10;
    //alert('y interval set at: '+yInterval);
    
    //get yAxis measure area
    var yAxis = _('yAxis');
    
    //get box for interval markings
    var yIntervalBox = _("yIntervalBox");
    
    //create loop for setting ten y-axis markers
    for(var yI=0; yI < 10; yI+=1){
        //create  div for y-axis marker
        var axisMarker = document.createElement("div");
        //set class of axisMarker
        axisMarker.setAttribute("class", "intervalMarker");
        
        //create div for y-axis indicator
        var axisNumber = document.createElement("div");
        //set class of axisNumber
        axisNumber.setAttribute("class", "intervalNumber");
        
        //place axisMarker down the page based on set interval
        axisMarker.style.top = parseInt(yI*yInterval*5) + "px";
        //set axisNumber down page based on set interval
        axisNumber.style.top = parseInt(yI*yInterval*5 - 5) + "px";
        
        //write value for axis number
        axisNumber.innerHTML = parseInt(100 - yI*yInterval); 
        
        
        //add axisMarker to document
        yIntervalBox.appendChild(axisMarker);
        
        //add axisNumber to document
        yIntervalBox.appendChild(axisNumber);
        
    }
    
    
    //get container ID
    var chartContainer = _(idOfContainer);
    //get box for independent  variable indicator
    var xIntervalBox = _("xIntervalBox");
    
    //grab the width of the container
    var widhtOfContainer = chartContainer.scrollWidth;
    
    // Grab the height of the container
    var heightOfContainer = chartContainer.scrollHeight;
    
    // Determine the width of each bar based on the number of data in the dataSet
    var dataPointGap = parseInt((widhtOfContainer / dataSet.length)-1);
    //alert('dataPointGap:'+dataPointGap);
    var widthOfBar = "3px";
    var boxQuarters = 1;
    var quarterCycles = 0;
    var dataPointXValues = new Array();
    var dataPairs = new Array();
    for(var i=0; i < dataSet.length; i+=1){
        var dataString = dataSet[i];
        //determine year represented for each datapoint
        var year = dataSet[i].substr(dataString.length - 5, dataString.length - 1);
        var yearValue = year.substr(0, 4);
        //alert('dataString:'+dataString);
        
        //determine quarter represent for each datapoint
        var quarter = dataString[dataString.length - 1];
        
        var dataPoint = dataString.substr(0, dataString.length - 5);
        var chartXCoordinate = parseInt(i*dataPointGap + i);
        dataPointXValues.push(chartXCoordinate);
        dataPairs.push('('+chartXCoordinate+','+dataPoint+')');
        
        // create our chart element
        var divElement = document.createElement("div");
        //static attributes of chart element
        divElement.setAttribute("class", "div2");
        
        //create independent variable indicator
        var xValue = document.createElement("div");
        //static attributes of independent variable indicator
        //xValue.setAttribute("class", "independentVariable");
        //xValue.style.marginLeft = chartXCoordinate + "px";
        //.innerHTML = 'Q'+quarter;
        //xIntervalBox.appendChild(xValue);
        
        if(quarter == 4 || i == dataSet.length){
            //create display for year anytime quarter reaches 4 or last dataPoint in array
            var quarterBox = document.createElement("div");
            quarterBox.setAttribute("class", "quarterBox");
            if(quarterCycles % 2 == 0){
                quarterBox.style.backgroundColor = '#D3D3D3';
            }
            quarterBox.style.width = parseInt(boxQuarters * (dataPointGap) + boxQuarters)+ "px";
            quarterBox.style.marginLeft = parseInt(quarterCycles*(boxQuarters * dataPointGap + boxQuarters))+"px";
            quarterBox.innerHTML = yearValue;
            xIntervalBox.appendChild(quarterBox);
            quarterCycles +=1;
            boxQuarters =1;
        }else{
            boxQuarters +=1;
        }
        
        // Dynamic attributes of the element
        divElement.style.marginLeft = parseInt(dataPointGap*i + i) + "px";
        divElement.style.height = dataPoint*5+"px";
        divElement.setAttribute("xVal", yearValue+'_Q'+quarter);
        divElement.setAttribute("yVal", dataPoint);
        divElement.addEventListener("click", _('displayValues').innerHTML = 'HomeownershipRate: '+divElement["yVal"]+'% in '+divElement["xVal"]);

        divElement.style.width = parseInt(widthOfBar) + "px";
        divElement.style.top = (heightOfContainer - parseInt(dataPoint)*5 - 1) + "px";
        chartContainer.appendChild(divElement);
    }
    
    //Fill in spaces between datapoints to create continuous line graph
    console.log(dataPairs);
    var iNew = 0;
    while(iNew < dataPointXValues.length - 1){
        //console.log('iNew='+iNew);
        //set range for xvalues to find values of
        var leftPoint = dataPointXValues[iNew];
        //console.log('leftPoint:'+leftPoint);
        var rightPoint = dataPointXValues[iNew + 1];
        //console.log('rightPoint:'+rightPoint);
        //iterate through x values between data points
        for(var xI = leftPoint + 1; xI < rightPoint; xI+=1){
        	var leftString = dataSet[iNew];
        	var leftPointYValue = parseInt(leftString.substr(0, leftString.length - 5));
            //alert('Left Y='+leftPointYValue);
            var rightString = dataSet[iNew + 1];
        	var rightPointYValue = parseInt(rightString.substr(0, rightString.length - 5));
        	//alert('Right Y='+rightPointYValue);
            var distance = parseInt(rightPoint - leftPoint);
            var difference = parseInt(rightPointYValue - leftPointYValue);
            console.log('dataPoint'+dataSet[iNew]);
            var year = dataSet[iNew].substr(dataString.length - 7, dataString.length - 2);
            console.log('yearString:'+year);
            var yearValue = year.substr(0, 4);
            
            var gapPercentage = parseInt(((xI - leftPoint)/distance)*100);
            var gapAdditionFloat = parseFloat((gapPercentage*difference)/100);
            var gapAdditionInt = parseInt(Math.round(gapAdditionFloat));
            
            var marginX = parseInt(iNew*dataPointGap);
            var marginXSinI = parseInt(marginX + xI - leftPoint);
            var xCoord = parseInt(marginXSinI + iNew);
            var xIYValue = parseInt(leftPointYValue + gapAdditionInt);
            //aggregateXValues.push('year'+yearValue+'_xcoord:'+xCoord+'_ycoord:'+xIYValue+'_Percentage:'+gapPercentage+'_Difference:'+difference+'_AdditionInt:'+gapAdditionInt);
            console.log('iNew'+iNew+'year'+yearValue+'_ycoord:'+xIYValue+'_Percentage:'+gapPercentage+'_Difference:'+difference+'_AdditionInt:'+gapAdditionInt)
            //console.log('xI'+xI+'left='+leftPoint+'right='+rightPoint+'_xcoord:'+xCoord+'margin='+marginX);
            // create our chart element
            var divElement = document.createElement("div");
            //static attributes of chart element
            divElement.setAttribute("class", "div2");
            divElement.setAttribute("year", yearValue);
            divElement.setAttribute("xVal", xCoord);
            divElement.setAttribute("yVal", xIYValue);
            divElement.style.marginLeft = xCoord + "px";
            divElement.style.height = xIYValue*5+"px";
            divElement.style.width = parseInt(widthOfBar) + "px";
            divElement.style.top = (heightOfContainer - parseInt(xIYValue)*5 - 1) + "px";
            chartContainer.appendChild(divElement);
            var aggregate = parseInt(xI - leftPoint);
            //if(aggregate == 1 ){
                //alert('aggregate '+aggregate);
                //alert('moved '+gapPercentage+'% away from dataPoint');
                //alert('adding '+gapAdditionInt+'to data point');
           // }
        }
        iNew+=1;
    }
    
    //console.log(aggregateXValues);
    return false;
    
    
}

drawBarChart(sampleData, "div1");

</script>