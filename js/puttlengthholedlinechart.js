puttLengthHoled = function (roundid) {
    $.getJSON( "ajax/stats/puttlengthholed.php?id="+roundid, function( json ) {
        
        var areaChartOptions = areaChartOptionsFunction();
        
        comma = "";
        labelstring = "";
        
        for(x=1; x<=18; x++) {
            if (labelstring) {
                comma = ",";  
            }
            
            labelstring+= comma+x;   
        }

        dataval = json*1;
        
        var areaChartData = {
          labels: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],
          datasets: [
            {
              label: "Par",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: []
            }
          ]
        };
        
        totalPutts = 0;
        hole=1;
        front9 = 0;
        back9 = 0;
        
        $.each(json, function(key, val) {
            // add the totalBills and totalAmount to the dataset
            areaChartData.datasets[0].data.push(val);
            totalPutts = totalPutts + (val*1);
            
            if (hole<=9) {
                front9 = front9+val;   
            } else {
                back9 = back9+val;   
            }
            
            hole++;
        });
        
        avPutts = totalPutts / 18;
        avPutts = Math.round(avPutts * 100)/100;
        
        $("#puttlenghholedtable").find('tbody').append($('<tr><td>Total Length Holed</td><td>'+totalPutts+' feet</td></tr><tr><td>Front 9 Holed Length</td><td>'+front9+' feet</td></tr><tr><td>Back 9 Holed Length</td><td>'+back9+' feet</td></tr><tr><td>Av Length Holed</td><td>'+avPutts+' feet</td></tr>'));

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#puttlengthholedchart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);  
        
        
    });
};