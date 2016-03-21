fairwaysHitOverallChart = function(startDate, endDate, course, competition) {
    $.getJSON("ajax/stats/getoverallfairwayshit.php?startdate="+startDate+"&enddate="+endDate+"&course="+course+"&competition="+competition, function( json ) {
        
        if (!json) {
            $("#fairwayshitoveralltable").find('tbody').append($('<tr><td colspan="5">No Stats for this period.</td></tr>'));
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
        
        if (json.center) {
            center = json.center;   
        } else {
            center = 0;   
        }
        
        total = hit+left+right+center;
    
        hitptg = Math.round((hit/total)*100*100)/100;
        leftptg = Math.round((left/total)*100*100)/100;
        rightptg = Math.round((right/total)*100*100)/100;
        centerptg = Math.round((center/total)*100*100)/100;
    
        $("#fairwayshitoveralltable").find('tbody').append($('<tr><td>Hit</td><td>'+hitptg+'%</td></tr><tr><td>Miss Left</td><td>'+leftptg+'%</td></tr><tr><td>Miss Right</td><td>'+rightptg+'%</td></tr><tr><td>Miss Long</td><td>'+centerptg+'%</td></tr>'));
    
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
		random = function(max){ return Math.round(Math.random()*100);},
		helpers = Chart.helpers;


	Chart.defaults.global.responsive = true;


	

myfirstdohnut = function() {


		var canvas = $id('fairwayshit'),
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
				label: "Fairways Hit"
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
				value: json.center,
				color: colours["Line"],
				highlight: Colour(colours["Line"], 10),
				label: "Miss Center"
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