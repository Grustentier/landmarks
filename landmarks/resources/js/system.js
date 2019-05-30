//GLOBAL VARS
var CHART_LABELS = ["b1-b2/a6-a11","b1-b5/a6-a11","a12-a14/a15-a16","a12-a14/b1-a13","a12-a14/b1-b5","a15-a16/a6-a11","a15-a16/a7-a10","a8-a9/a15-a16","a8-a9/b1-a13","a8-a9/b1-b5","a7-a10/b1-b5","b1-a13/a7-a10","b1-a13/b1-b5","b1-a13/a6-a11","a12-a14/a18-a20","a15-a16/a18-a20","a6-a11/a18-a20","a8-a9/a18-a20","a7-a10/a18-a20"];
var RESULT_JSON = [];
var TAB_COUNT = 0;

function createLandmarkMenu(tabCount){
	jQuery("#draggableLandmarks-"+tabCount).empty();
	as = ["a6","a7","a8","a9","a10","a11","a12","a13","a14","a15","a16","a18","a20"]
	bs = ["b1","b2","b5"]
	as.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div class='landmark la' title='"+element+"' style='' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	}); 
	bs.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div class='landmark lb' title='"+element+"' style='' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	});
	jQuery("#draggableLandmarks-"+tabCount).find( ".landmark" ).draggable({ 
		revert: "invalid",
		stop: function( event, ui ) {
			//console.log(ui);
		}
	});
} 

function createNewTab(){
	TAB_COUNT = TAB_COUNT + 1;
	var tabs = $( "#tabs" ).tabs();
	var ul = tabs.find( "ul" );
	$("#tabs").append("<div id='tabs-"+TAB_COUNT+"'></div>"); 
	$( "<li style='background:"+colors[TAB_COUNT]+"'><a href='#tabs-"+TAB_COUNT+"'>Person "+TAB_COUNT+"</a></li>" ).appendTo( ul );

	appendContentToTab(TAB_COUNT);
	
	tabs.tabs( "refresh" );  

	$("#tabs ul li a[href='#tabs-"+TAB_COUNT+"']").trigger("click"); 
}

function appendContentToTab(tabCount){
	$("#tabs-"+tabCount+"").append("<table><tr><td><img src='resources/images/template.png'/></td><td><div id='droppable-"+tabCount+"' class='ui-widget-header droppable'> </div></td><td><div id='draggableLandmarks-"+tabCount+"' class='ui-widget-content draggableLandmarks'> </div><br><button id='resetLandmarks-"+tabCount+"' class='resetLandmarks myButtonSmall'>Reset</button></td></tr><tr><td></td><td><div id='dropzone-"+tabCount+"' class='myDropzone' ><div class='dz-message needsclick'>Drop your file with a frontal face here or click to upload.</div></div><div id='previewContainer-"+tabCount+"'> </div></td><td></td></tr></table>");
	//initDropable4Tab(tabCount);
	initDropzone4Tab(tabCount);	
	createLandmarkMenu(tabCount);
	$("#resetLandmarks-"+tabCount).click(function(){ 
		createLandmarkMenu(tabCount);
	});
}

function initCropping4Tab(tabCount){ 
	$("#droppable-"+tabCount+ " img").first().croppie({
	    viewport: {
	        width: 800,
	        height: 800
	    },
	    minZoom:0.0,
	    maxZoom:15.0
	});

	initDropable4Tab(tabCount);  
}

function initDropable4Tab(tabCount){
	
	//jQuery("#droppable-"+tabCount).droppable({
	jQuery("#droppable-"+tabCount+ " .croppie-container").droppable({
    	classes: {
        	"ui-droppable-active": "ui-state-active",
            "ui-droppable-hover": "ui-state-hover"
        },
	    drop: function( event, ui ) {
		    $( this ).addClass( "ui-state-highlight" );
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
			initCropping4Tab(tabCount); 
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
			labels: CHART_LABELS,			
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
