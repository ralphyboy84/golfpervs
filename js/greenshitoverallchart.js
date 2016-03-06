greensHitOverallChart = function(startDate, endDate, course, competition) {
    $.getJSON("ajax/stats/getoverallgreenshit.php?startdate="+startDate+"&enddate="+endDate+"&course="+course+"&competition="+competition, function( json ) {
        
        if (!json) {
            $("#greenshitoveralltable").find('tbody').append($('<tr><td colspan="5">No Stats for this period.</td></tr>'));
            return;
        }
        
        if (json.hit) {
            hit = json.hit;   
        } else {
            hit = 0;   
        }
        
        if (json.left) {
            left = json.left;   
        } else {
            left = 0;   
        }
        
        if (json.right) {
            right = json.right;   
        } else {
            right = 0;   
        }
        
        if (json.long) {
            long = json.long;   
        } else {
            long = 0;   
        }
        
        if (json.short) {
            short = json.short;   
        } else {
            short = 0;   
        }
        
        if (json.na) {
            na = json.na;   
        } else {
            na = 0;   
        }
        
        total = hit+left+right+long+short+na;
    
        hitptg = Math.round((hit/total)*100*100)/100;
        leftptg = Math.round((left/total)*100*100)/100;
        rightptg = Math.round((right/total)*100*100)/100;
        longptg = Math.round((long/total)*100*100)/100;
        shortptg = Math.round((short/total)*100*100)/100;
        naptg = Math.round((na/total)*100*100)/100;
    
        $("#greenshitoveralltable").find('tbody').append($('<tr><td>Hit</td><td>'+hitptg+'%</td></tr><tr><td>Miss Left</td><td>'+leftptg+'%</td></tr><tr><td>Miss Right</td><td>'+rightptg+'%</td></tr><tr><td>Miss Long</td><td>'+longptg+'%</td></tr><tr><td>Miss Short</td><td>'+shortptg+'%</td></tr><tr><td>N/A</td><td>'+naptg+'%</td></tr>'));
    
   // Colour variables
	var red = "#bf616a",
		blue = "#5B90BF",
		orange = "#d08770",
		yellow = "#ebcb8b",
		green = "#a3be8c",
		teal = "#96b5b4",
		pale_blue = "#8fa1b3",
		purple = "#b48ead",
		brown = "#ab7967";


		var baseDataset = {
			fill: 'rgba(222,225,232,0.4)',
			stroke: 'rgba(222,225,232,1)',
			highlight: 'rgba(222,225,232,0.8)',
			highlightStroke: 'rgba(222,225,232,1)'
		},
		overlayDataset = {
			fill: 'rgba(91,144,191,0.4)',
			stroke: 'rgba(91,144,191,1)',
			highlight: 'rgba(91,144,191,0.8)',
			highlightStroke: 'rgba(91,144,191,1)'
		};

	var data = [],
		barsCount = 50,
		labels = new Array(barsCount),
		updateDelayMax = 500,
		$id = function(id){
			return document.getElementById(id);
		},
		random = function(max){ return Math.round(Math.random()*100)},
		helpers = Chart.helpers;


	Chart.defaults.global.responsive = true;


	

myfirstdohnut = function() {


		var canvas = $id('greenshit'),
			colours = {
				"Core": blue,
				"Line": orange,
				"Bar": teal,
				"Polar Area": purple,
				"Radar": brown,
				"Doughnut": green
			};

		var moduleData = [
		
			{
				value: json.hit,
				color: colours["Core"],
				highlight: Colour(colours["Core"], 10),
				label: "Greens Hit"
			},
		
			{
				value: json.left,
				color: colours["Bar"],
				highlight: Colour(colours["Bar"], 10),
				label: "Miss Left"
			},
		
			{
				value: json.right,
				color: colours["Doughnut"],
				highlight: Colour(colours["Doughnut"], 10),
				label: "Miss Right"
			},
		
			{
				value: json.long,
				color: colours["Line"],
				highlight: Colour(colours["Line"], 10),
				label: "Miss Long"
			},
		
			{
				value: json.short,
				color: colours["Radar"],
				highlight: Colour(colours["Radar"], 10),
				label: "Miss Short"
			},
		
			{
				value: json.na,
				color: colours["Polar Area"],
				highlight: Colour(colours["Polar Area"], 10),
				label: "NA"
			}		
		];
		// 
		var moduleDoughnut = new Chart(canvas.getContext('2d')).Doughnut(moduleData, { tooltipTemplate : "<%if (label){%><%=label%>: <%}%><%= value %>", animation: false });
		// 
		var legendHolder = document.createElement('div');
		legendHolder.innerHTML = moduleDoughnut.generateLegend();
		// Include a html legend template after the module doughnut itself
		helpers.each(legendHolder.firstChild.childNodes, function(legendNode, index){
			helpers.addEvent(legendNode, 'mouseover', function(){
				var activeSegment = moduleDoughnut.segments[index];
				activeSegment.save();
				activeSegment.fillColor = activeSegment.highlightColor;
				moduleDoughnut.showTooltip([activeSegment]);
				activeSegment.restore();
			});
		});
		helpers.addEvent(legendHolder.firstChild, 'mouseout', function(){
			moduleDoughnut.draw();
		});
		canvas.parentNode.parentNode.appendChild(legendHolder.firstChild);

	};
    
    myfirstdohnut();

	
	
        });
};


function Colour(col, amt) 
{
    var usePound = false;

    if (col[0] == "#") {
        col = col.slice(1);
        usePound = true;
    }

    var num = parseInt(col,16);

    var r = (num >> 16) + amt;

    if (r > 255) r = 255;
    else if  (r < 0) r = 0;

    var b = ((num >> 8) & 0x00FF) + amt;

    if (b > 255) b = 255;
    else if  (b < 0) b = 0;

    var g = (num & 0x0000FF) + amt;

    if (g > 255) g = 255;
    else if (g < 0) g = 0;

    return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
}