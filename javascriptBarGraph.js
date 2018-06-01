//sample dataset
var sampleData = [23,43,54,94,23,54,36,96,100,250,45,48,29,94, 39, 20, 73, 150, 200, 85];

//function to drawBarchart
function drawBarChart(dataset, idOfContainer){
    
    
    //Make sure the dataset is an array
    if(typeof(dataset) != "object"){
        return;
    }
    //get max value on chart
    var maxValue = Math.max.apply(null, dataset);
    
    //get min value on chart
    var minValue = Math.min.apply(null, dataset);
    
    //add 10% of maxValue to plot area
    var chartMax = parseInt( maxValue / 10 + maxValue);
    
    
    //set interval for markers
    var yInterval = +50;
    alert('y interval set at: '+yInterval);
    
    //get yAxis measure area
    var yAxis = _('yAxis');
    
    //get box for interval markings
    var yIntervalBox = _("yIntervalBox");
    
    //create loop for setting ten y-axis markers
    for(var yI=0; yI < 10; yI++){
        //create  div for y-axis marker
        var axisMarker = document.createElement("div");
        //set class of axisMarker
        axisMarker.setAttribute("class", "intervalMarker");
        
        //create div for y-axis indicator
        var axisNumber = document.createElement("div");
        //set class of axisNumber
        axisNumber.setAttribute("class", "intervalNumber");
        
        //place axisMarker down the page based on set interval
        axisMarker.style.top = parseInt(yI*yInterval) + "px";
        //set axisNumber down page based on set interval
        axisNumber.style.top = parseInt(yI*yInterval - 5) + "px";
        
        //write value for axis number
        axisNumber.innerHTML = parseInt(500 - yI*yInterval); 
        
        
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
    
    // Determine the width of each bar based on the number of data in the dataset
    var widthOfBar = parseInt(widhtOfContainer / dataset.length) - 2;
    //var widthOfBar = "1px"
    // Determine how many datapoint are listed
    var dataPointCount = dataset.length;
    
    
    for(var i=0; i < dataset.length; i++){
        
        //determine quarter represent for each datapoint
        var quarter ='';
        switch(i % 4) {
            case 0:
                quarter = 'Q1';
                break;
            case 1:
                quarter = 'Q2';
                break;
            case 2:
                quarter = 'Q3';
                break;
            default:
                quarter = 'Q4';
        }
        
        // create our chart element
        var divElement = document.createElement("div");
        //static attributes of chart element
        divElement.setAttribute("class", "div2");
        
        //create independent variable indicator
        var xValue = document.createElement("div");
        //static attributes of independent variable indicator
        xValue.setAttribute("class", "independentVariable");
        xValue.style.marginLeft = parseInt(i*2 + i * widthOfBar + 3) + "px";
        xValue.innerHTML = quarter;
        xIntervalBox.appendChild(xValue);
        
        // Dynamic attributes of the element
        divElement.style.marginLeft = parseInt(i*2 + i * widthOfBar) + "px";
        divElement.style.height = parseInt(dataset[i]) + "px";
        divElement.style.width = parseInt(widthOfBar) + "px";
        divElement.style.top = (heightOfContainer - parseInt(dataset[i]) - 1) + "px";
        chartContainer.appendChild(divElement);
    }
    return false;
    
}

drawBarChart(sampleData, "div1");