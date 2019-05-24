<html>

<head><title></title>
<meta charset="utf-8">
<link rel="stylesheet" href="resources/css/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="resources/css/dropzone.css">
<script type="text/javascript" src="resources/js/jquery/jquery-3.3.1.js"></script>
<script src="resources/js/jquery-ui/jquery-ui.js"></script>
<script src="resources/js/dropzone/dropzone.js"></script>
<script src="resources/js/chart/Charts.js"></script>
<script src="resources/js/chart/Chart.bundle.js"></script>
<script src="resources/js/chart/Chart.utils.js"></script>
<script src="resources/js/colors.js"></script>

 

<style>
body{margin:0px;padding:0px}
.draggableLandmarks { position:inherit;float:right;padding: 1em;}
.resetLandmarks { position:inherit;float:right;padding: 1em;}
.droppable { width: 800px; height: 800px;  float: left; border:1px solid blue }
.landmark { width:18px; height:18px; cursor: move;text-align: center;font-size:9px;-moz-border-radius: 15px;-khtml-border-radius: 15px;-webkit-border-radius: 15px;-o-border-radius: 15px;border-radius: 15px; }
.landmark span{top: 3px;position: relative;}
.myDropzone{min-height: 150px;overflow: auto;border: 2px solid rgba(0, 0, 0, 0.3);background: #b3e8da;padding: 20px 20px;}
.myDropzone:hover{border:2px solid green;color:green;}
.myButton{background-color: rgb(54, 162, 235);color: white;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;border-top-right-radius: 5px;border-top-left-radius: 5px;cursor: pointer;padding: 10px;border: none;}
.myButtonSmall{background-color: rgb(54, 162, 235);color: white;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;border-top-right-radius: 5px;border-top-left-radius: 5px;cursor: pointer;padding: 5px;border: none;}
#resultContainer{display: none;}
.dz-message{color: rgba(0, 0, 0, 0.3);font-size: 30px;text-align: center;margin-top: 57px;}
</style>
  
</head>

<body>
<button id="newMeasurement" class="ui-button myButton" style="top:10px;left:10px;">New Landmark Positioning</button> 
<br><br><br>
<div id="tabs"> <ul> </ul> </div>

 





<script>
var chartLabels = ["b1-b2/a6-a11","b1-b5/a6-a11","a12-a14/a15-a16","a12-a14/b1-a13","a12-a14/b1-b5","a15-a16/a6-a11","a15-a16/a7-a10","a8-a9/a15-a16","a8-a9/b1-a13","a8-a9/b1-b5","a7-a10/b1-b5","b1-a13/a7-a10","b1-a13/b1-b5","b1-a13/a6-a11","a12-a14/a18-a20","a15-a16/a18-a20","a6-a11/a18-a20","a8-a9/a18-a20","a7-a10/a18-a20"];
var tabCount = 0;

function createLandmarkMenu(tabCount){
	jQuery("#draggableLandmarks-"+tabCount).empty();
	as = ["a6","a7","a8","a9","a10","a11","a12","a13","a14","a15","a16","a18","a20"]
	bs = ["b1","b2","b5"]
	as.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div class='landmark' style='background:red;' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	}); 
	bs.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div class='landmark' style='background:green;' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	});
	jQuery("#draggableLandmarks-"+tabCount).find( ".landmark" ).draggable({ 
		revert: "invalid",
		stop: function( event, ui ) {
			//console.log(ui);
		}
	});
} 

function createNewTab(){
	tabCount = tabCount + 1;
	var tabs = $( "#tabs" ).tabs();
	var ul = tabs.find( "ul" );
	$("#tabs").append("<div id='tabs-"+tabCount+"'></div>"); 
	$( "<li style='background:"+colors[tabCount]+"'><a href='#tabs-"+tabCount+"'>Person "+tabCount+"</a></li>" ).appendTo( ul );

	appendContentToTab(tabCount);
	
	tabs.tabs( "refresh" );  

	$("#tabs ul li a[href='#tabs-"+tabCount+"']").trigger("click"); 
	$("#resultContainer").show("slow",function(){});
}

function appendContentToTab(tabCount){
	//$("#tabs-"+tabCount+"").append("<div id='draggableLandmarks-"+tabCount+"' class='ui-widget-content draggableLandmarks'> </div>");
	//createLandmarkMenu(tabCount);

	//$("#tabs-"+tabCount+"").append("<button id='resetLandmarks-"+tabCount+"' class='ui-button resetLandmarks'>Reset</button>")
	

	$("#tabs-"+tabCount+"").append("<table><tr><td><img src='resources/images/template.png'/></td><td><div id='droppable-"+tabCount+"' class='ui-widget-header droppable'> </div></td><td><div id='draggableLandmarks-"+tabCount+"' class='ui-widget-content draggableLandmarks'> </div><br><button id='resetLandmarks-"+tabCount+"' class='resetLandmarks myButtonSmall'>Reset</button></td></tr><tr><td></td><td><div id='dropzone-"+tabCount+"' class='myDropzone' ><div class='dz-message needsclick'>Drop your file with a frontal face here or click to upload.</div></div><div id='previewContainer-"+tabCount+"'> </div></td><td></td></tr></table>");
	initDropable4Tab(tabCount);
	initDropzone4Tab(tabCount);
	createLandmarkMenu(tabCount);
	$("#resetLandmarks-"+tabCount).click(function(){ 
		createLandmarkMenu(tabCount);
		//jQuery( "#droppable-"+tabCount).empty();
	});

	
}

function initDropable4Tab(tabCount){
	jQuery( "#droppable-"+tabCount).droppable({
    	classes: {
        	"ui-droppable-active": "ui-state-active",
            "ui-droppable-hover": "ui-state-hover"
        },
	    drop: function( event, ui ) {
		    $( this ).addClass( "ui-state-highlight" );

		     /*var draggable = ui.draggable;
		     var dragged = draggable.clone();      
		     dragged.appendTo("#droppable-"+tabCount);
		     dragged.resizable({containment: '#droppable-'+tabCount})
		     */
		} 
    }); 
}

function initDropzone4Tab(tabCount){
	jQuery("#dropzone-"+tabCount).dropzone({ 		 
		url: "/",   
		createImageThumbnails: true,
		acceptedFiles: "image/*",
 		addRemoveLinks: true,
 		previewContainer: "#previewContainer-"+tabCount, 
		 
		success: function(file, response){
			jQuery("#droppable-"+tabCount).empty(); 
			jQuery("#droppable-"+tabCount).append("<img src='"+file.dataURL+"' style='width: 800px;'/>");
			this.removeFile(file);  
		},

		queuecomplete: function(file, response){ },
		
		error: function(file, response){
			var message = "error: " + response + " ("+file.name+")"; 
			this.removeFile(file); 
		} 
	});
}

function euklidDistance(positionA, positionB){
	return Math.sqrt( Math.pow((positionA.left-positionB.left), 2) + Math.pow((positionA.top-positionB.top), 2) );
}

function scrollTo(element){
	$('html, body').animate({ scrollTop: ($(element).offset().top)}, 'slow');
};

function writeToFile(sText){
	var fso = new ActiveXObject("Scripting.FileSystemObject");
	var s = fso.CreateTextFile("test.txt", true);
	s.WriteLine(sText);
	s.Close();
} 




var config = {
		type: 'line',
		data: {
			labels: chartLabels,			
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Landmark Result'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Month'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Value'
					}
				}]
			}
		}
	};


 

$( document ).ready(function() {
	 
	$("#tabs").tabs();
	
	var ctx = document.getElementById('canvas').getContext('2d');	
	window.myLine = new Chart(ctx, config);
	
	$("#newMeasurement").click(function(){ createNewTab();});

	$( "#tabs" ).tabs({ active: 0 });
	$("#process").click(function(){   
	
		while (window.myLine.data.datasets.length && window.myLine.data.datasets.length > 0) { window.myLine.data.datasets.pop();}		 
		
		tabCount = $('#tabs >ul >li').length;
		for(i = 1; i < tabCount + 1; i++){
			 
			$("#tabs ul li a[href='#tabs-"+i+"']").trigger("click"); 
			b1 = $("#b1-"+i);b2 = $("#b2-"+i);b5 = $("#b5-"+i);a6 = $("#a6-"+i);a7 = $("#a7-"+i);a8 = $("#a8-"+i);a9 = $("#a9-"+i);a10 = $("#a10-"+i);a11 = $("#a11-"+i);a12 = $("#a12-"+i);a13 = $("#a13-"+i);a14 = $("#a14-"+i);a15 = $("#a15-"+i);a16 = $("#a16-"+i);a18 = $("#a18-"+i);a20 = $("#a20-"+i);

			var a6_a11 = euklidDistance(a6.offset(),a11.offset()); 
			var a7_a10 = euklidDistance(a7.offset(),a10.offset()); 
			var a8_a9 = euklidDistance(a8.offset(),a9.offset());
			var a12_a14 = euklidDistance(a12.offset(),a14.offset());
			var a15_a16 = euklidDistance(a15.offset(),a16.offset());
			var a18_a20 = euklidDistance(a18.offset(),a20.offset());
			var b1_a13 = euklidDistance(b1.offset(),a13.offset());			
			var b1_b2 = euklidDistance(b1.offset(),b2.offset());
			var b1_b5 = euklidDistance(b1.offset(),b5.offset());

			var b1_b2_a6_a11 = b1_b2 / a6_a11;
			var b1_b5_a6_a11 = b1_b5 / a6_a11;
			var a12_a14_a15_a16 = a12_a14 / a15_a16;
			var a12_a14_b1_a13 = a12_a14 / b1_a13;
			var a12_a14_b1_b5 = a12_a14 / b1_b5;			
			var a15_a16_a6_a11 =  a15_a16 / a6_a11;
			var a15_a16_a7_a10 =  a15_a16 / a7_a10;			
			var a8_a9_a15_a16 = a8_a9 / a15_a16;
			var a8_a9_b1_a13 = a8_a9 / b1_a13;
			var a8_a9_b1_b5 = a8_a9 / b1_b5;			
			var a7_a10_b1_b5 = a7_a10 / b1_b5;			 
			var b1_a13_a7_a10 = b1_a13 / a7_a10;
			var b1_a13_b1_b5 = b1_a13 / b1_b5;
			var b1_a13_a6_a11 = b1_a13 / a6_a11;

			var a12_a14_a18_a20 = a12_a14 / a18_a20;
			var a15_a16_a18_a20 = a15_a16 / a18_a20; 
			var a6_a11_a18_a20 = a6_a11 / a18_a20; 
			var a8_a9_a18_a20 = a6_a11 / a8_a9;
			var a7_a10_a18_a20 = a7_a10 / a8_a9;


			window.myLine.data.datasets.push({
				  label: 'Person-' + i, 
				  fill: false,
				  borderColor: colors[i],
				  data: [b1_b2_a6_a11,b1_b5_a6_a11,a12_a14_a15_a16,a12_a14_b1_a13,a12_a14_b1_b5,a15_a16_a6_a11,a15_a16_a7_a10,a8_a9_a15_a16,a8_a9_b1_a13,a8_a9_b1_b5,a7_a10_b1_b5,b1_a13_a7_a10,b1_a13_b1_b5,b1_a13_a6_a11,a12_a14_a18_a20,a15_a16_a18_a20,a6_a11_a18_a20,a8_a9_a18_a20,a7_a10_a18_a20]
				});  
		}
		
		window.myLine.update(); 

		scrollTo($("#canvasContainer"));

	});

	
	$("#saveData").click(function(){
		alert("NOT IMPLEMENTED!");   
		/*
    	var json = jQuery.parseJSON( '{ "name": "John" }' );
    	var data = JSON.stringify(json);
    	var stream = IO.newOutputStream("test.txt", "text nocreate append");
    	*/
	});
	
	 
  });


</script>

<div id="resultContainer">

<center>
<img src="resources/images/arrow.png" style="width: 130px;padding: 20px;"/>
<br>
<button id='process' class='ui-button myButton'>Analyze</button>
</center>
<br><br>
<div id="canvasContainer" style="width:100%;"><canvas id='canvas'></canvas></div>
<center>
<button id='saveData' class='ui-button myButton'>Save Data</button>
</center>
</div>

</body>

</html>