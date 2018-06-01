<!doctype>
<html>
<head>
</head>
<body>
<?php
//rental vacancy rates by state

		//inlcude phpexcel function to pull docment into array
        require_once "../copexData/Classes/PHPExcel.php";
		//$tmpfname = "test.xlsx";
		$url = "https://www.census.gov/housing/hvs/data/rates/tab4_msa_15_16_rvr.xlsx";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$sheetArray = $worksheet->toArray(null, false,true, false);
/**************************Need to read two excel sheets since first one only has data from 2014 on*********/
		$url = "https://www.census.gov/housing/hvs/data/rates/tab4a_msa_05_2014_rvr.xlsx";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheetAddition = $excelObj->getSheet(0);
		$additionArray = $worksheetAddition->toArray(null, false, true, false);
		$sheetArray[] = $additionArray;
		$lastRow = count($sheetArray);
		//echo "<center><pre>";
          //  print_r($sheetArray);
        //echo "</pre></center>";
        
        
        //print selected array indexes to page
        //write selected indexes to file
        $handle = fopen('rentalVacancyRatesMSA.txt', 'w');
        $year = 2016;
        
        
        for($row = 8; $row <= $lastRow; $row++){    //foreach($sheetArray as $line){
        
	        //decrement at appropriate breaks on page
		    $fiftyNineLess = $row - 59;
		    if($fiftyNineLess % 61 == 0 ){
		    	$year--;
		    	echo '<script type="text/javascript">alert(" year decreases when row ='.$row.'");</script>';
		    	
		    }
	        
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        $lineIndex = 1;
        	if(is_array($sheetArray[$row])){
                foreach($sheetArray[$row] as $key => $value){
                	if(is_null($value)){
                		
                	}else{
                		if($key == 0){
                			echo("<br/>");
                			fwrite($handle, '_'."\n");
                			$state = str_replace('.', "", $value);
                			echo "line=".$row;
                			echo " year =".$year;
                			echo " State =".$state;
                			//$year = substr($value, 0, 4);
                		}else{
                			$mod = $lineIndex % 2;
                			if($mod==1){
        						$data = $state.'_'.$lineIndex.': '.$value." ";
	                			echo $data;
	                			fwrite($handle, $data);
                			}
                			$lineIndex++;
                		}
                	}
                }
			}else{
			 	echo $sheetArray[$row]."<br/>";
			}
        }
        
       fclose($handle);
        
/**		
		echo "<table>";
		//make new row each time column is last column
		$repeat =0;
		for ($row = 0; $row <= $lastRow; $row++){
		    echo"<tr>";
			 for($column = 0; $column <= $lastColumn; $column++){
			     
			     if($column == 'A' && $repeat < 3){
			         echo '<script type="text/javascript">alert("at row:'.$row.' and column:'.$column.'");</script>';
			         $repeat++;
			     }
			     $value = $sheetArray[$row][$column];
    			 if(is_array($value)){
    			     echo "<td>";
                     print_r($value);
                     echo "</td>";
    			 }else{
    			     echo "<td>".$value."</td>";
    			 }
    			 
    			 if($column == $lastColumn){
    			     echo"</tr>";
    			     //echo '<script type="text/javascript">alert("creating new row");</script>';
    			 }
			 }
		}
		echo "</table>";
**/
?>

</body>
</html>