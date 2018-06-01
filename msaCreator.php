<!doctype>
<html>
<head>
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

		$lastRow = count($sheetArray);
		echo '<script type="text/javascript">alert("'.$lastRow.' rows");</script>';
		
		//echo "<center><pre>";
        //	  print_r($sheetArray);
        //echo "</pre></center>";
        
        
        //print selected array indexes to page
        //write selected indexes to file
        $year = 2016;
        for($row = 8; $row < 82; $row++){    //foreach($sheetArray as $line){
        
	        
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        	if(is_array($sheetArray[$row])){
                //foreach($sheetArray[$row] as $key => $value)
                $msaInfoArray = $sheetArray[$row];
                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
                	$value = $msaInfoArray[$valueIndex];
                	if(is_null($value)){
                		
                	}else{
                		if($valueIndex == 1){
                			echo "<br/>";
                			$replace = array(".","/", "0","1","2","3","4","5","6","7","8","9");
                			$msa = str_replace($replace, "", $value);
                			echo "MSA_YEAR=".$msa.'_'.$year;
                			
                			if(locateMSA($msa) == 1){
                				// do nothing
                			}else{
                				$db = copexConnect();
                    
			                    $registrationDate = date("Y-m-d");
			                    
			                    $query = $db->prepare("INSERT INTO us_msareas (msa) VALUES(?)") or die("Could not set up Account");
			                    $query->bindParam(1, $msa);
			                    $query->execute();
			                    $db = null;
                			}
                			//$year = substr($value, 0, 4);
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
                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
                	$value = $msaInfoArray[$valueIndex];
                	if(is_null($value)){
                		
                	}else{
                		if($valueIndex == 1){
                			echo("<br/>");
                			$replace = array(".","/", "0","1","2","3","4","5","6","7","8","9");
                			$msa = str_replace($replace, "", $value);
                			echo "MSA_YEAR=".$msa.'_'.$year;
                			
                			if(locateMSA($msa) == 1){
                				// do nothing
                			}else{
                				$db = copexConnect();
                    
			                    $registrationDate = date("Y-m-d");
			                    
			                    $query = $db->prepare("INSERT INTO us_msareas (msa) VALUES(?)") or die("Could not set up Account");
			                    $query->bindParam(1, $msa);
			                    $query->execute();
			                    $db = null;
                			}
                			//$year = substr($value, 0, 4);
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
        $year -=1;
        $stopPoint = count($secondArray);
        $row = 8;
        $msaIndex = 1;
        while($row < $stopPoint){    //foreach($sheetArray as $line){

	        
	        //if($row == 59 || $row == 120 || $row == 181 || $row == 242 || $row == 303 || $row == 364){
	        	//year decreases after 61 rows pass starting at 59
	       //	$year--;
	       // }
        	if(is_array($secondArray[$row])){
                //foreach($sheetArray[$row] as $key => $value)
                $msaInfoArray = $secondArray[$row];
                for($valueIndex = 1; $valueIndex < 9; $valueIndex++){
                	$value = $msaInfoArray[$valueIndex];
                	if(is_null($value)){
                		
                	}else{
                		if($valueIndex == 1){
                			echo("<br/>");
                			$replace = array(".","\\", "0","1","2","3","4","5","6","7","8","9");
                			$msa = str_replace($replace, "", $value);
                			echo "MSA_YEAR=".$msa.'_'.$year;
                			
                			if(locateMSA($msa) == 1){
                				// do nothing
                			}else{
                				$db = copexConnect();
                    
			                    $registrationDate = date("Y-m-d");
			                    
			                    $query = $db->prepare("INSERT INTO us_msareas (msa) VALUES(?)") or die("Could not set up Account");
			                    $query->bindParam(1, $msa);
			                    $query->execute();
			                    $db = null;
                			}
                			//$year = substr($value, 0, 4);
                		}
                	}
                }
			}else{
			 	echo $secondArray[$row]."<br/>";
			}
			
			
			if($msaIndex == 75){
				$row+=11;
				$msaIndex = 1;
				$year-=1;
				
				echo"<br/><br/><br/><br/>";
				
			}else{
				$row++;
				$msaIndex++;
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