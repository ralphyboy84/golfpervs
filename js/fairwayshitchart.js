fairwaysHitChart = function(roundid) {
    
    $.getJSON( "ajax/stats/getfairwayshit.php?id="+roundid, function( json ) {
        
        $("#fairwayshittable").find('tbody').append($('<tr><td>Hit</td><td>'+json.hit+'</td></tr><tr><td>Miss Left</td><td>'+json.left+'</td></tr><tr><td>Miss Right</td><td>'+json.right+'</td></tr><tr><td>Miss Center</td><td>'+json.center+'</td></tr>'));
    
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


		var canvas = $id('pieChart'),
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
				label: "Missed Left"
			},
		
			{
				value: json.right,
				color: colours["Doughnut"],
				highlight: Colour(colours["Doughnut"], 10),
				label: "Missed Right"
			},
		
			{
				value: json.center,
				color: colours["Radar"],
				highlight: Colour(colours["Radar"], 10),
				label: "Missed Center"
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