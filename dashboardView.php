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
        <div id="div0">REIQ Dashboard</div>
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
        <script type="text/javascript" src="javascriptBarGraph.js"></script>
    </body>
</html>