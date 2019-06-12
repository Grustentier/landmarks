//GLOBAL VARS
var CHART_LABELS = ["b1-b2/a6-a11","b1-b5/a6-a11","a12-a14/a15-a16","a12-a14/b1-a13","a12-a14/b1-b5","a15-a16/a6-a11","a15-a16/a7-a10","a8-a9/a15-a16","a8-a9/b1-a13","a8-a9/b1-b5","a7-a10/b1-b5","b1-a13/a7-a10","b1-a13/b1-b5","b1-a13/a6-a11","a12-a14/a18-a20","a15-a16/a18-a20","a6-a11/a18-a20","a12-a14/a19-a21","a15-a16/a19-a21","a6-a11/a19-a21","a6-a11/a8-a9","a7-a10/a8-a9"];
var RESULT_JSON = [];
var TAB_COUNT = 0;
var DATA_CHANGED = false;

function createLandmarkMenu(tabCount){
	jQuery("#draggableLandmarks-"+tabCount).empty();
	as = ["a1","a2","a3","a4","a5","a6","a7","a8","a9","a10","a11","a12","a13","a14","a15","a16","a17","a18","a19","a20","a21"];
	bs = ["b1","b2","b3","b4","b5"];
	cs = ["c4","c5"];
	as.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div dropped='false' class='landmark la' title='"+element+"' style='' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	}); 
	bs.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div dropped='false' class='landmark lb' title='"+element+"' style='' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	});
	
	cs.forEach(function(element) {
		jQuery("#draggableLandmarks-"+tabCount).append("<div dropped='false' class='landmark lc' title='"+element+"' style='' id='"+element+"-"+tabCount+"'><span>"+element+"</span></div><br>");
	});
	
	jQuery("#draggableLandmarks-"+tabCount).find( ".landmark" ).draggable({ 
		revert: "invalid",
		start: function(event, ui) {
	        ui.helper.data('dropped', false);
	    },
		stop: function( event, ui ) {
			//console.log(ui);
		},
		drag: function(event, ui) {
	        ui.helper.data('dropped', false);
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
	
	$("#saveData").hide();
}

function appendContentToTab(tabCount){
	$("#tabs-"+tabCount+"").append("<table><tr><td><img src='resources/images/template.png'/></td><td><div id='droppable-"+tabCount+"' class='ui-widget-header droppable'> </div></td><td><div id='draggableLandmarks-"+tabCount+"' class='ui-widget-content draggableLandmarks' style='position:absolute;top:75px'> </div><br><button id='resetLandmarks-"+tabCount+"' class='resetLandmarks myButtonSmall'>Reset</button></td></tr><tr><td></td><td><div id='dropzone-"+tabCount+"' class='myDropzone' ><div class='dz-message needsclick'>Drop your file with a frontal face here or click to upload.</div></div><div id='previewContainer-"+tabCount+"'> </div></td><td></td></tr></table><br><br><br>");
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
		    ui.helper.data('dropped', true);
		    $(ui.draggable).attr("dropped",true);
		    $("#saveData").hide();
		    DATA_CHANGED = true;
		} 
    }); 
}

function initDropzone4Tab(tabCount){
	jQuery("#dropzone-"+tabCount).dropzone({ 		 
		url: "/",
		//url: "/~fosil/landmarks/",   
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

function cleanChart(){
	while (window.myLine.data.datasets.length && window.myLine.data.datasets.length > 0) { window.myLine.data.datasets.pop();}
}

function cleanTabs(){
	var tabCount = $('#tabs >ul >li').length;		
	for(i = 1; i < tabCount + 1; i++){
		var tabIdStr = "#tabs-" + i

		// Remove the panel
		$( tabIdStr ).remove();
		// Refresh the tabs widget
		$("#tabs").tabs( "refresh" );

		// Remove the tab
		var hrefStr = "a[href='" + tabIdStr + "']"
		$( hrefStr ).closest("li").remove()
	}
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


function handleLandmarkData(tabCount){
	var i = tabCount;
	var a1 = $("#a1-"+i);
	var a1Position = (a1.attr("dropped")=="true")?{top:a1.offset().top,left:a1.offset().left}:{top:"undefined",left:"undefined"};

	var a2 = $("#a2-"+i);
	var a2Position = (a2.attr("dropped")=="true")?{top:a2.offset().top,left:a2.offset().left}:{top:"undefined",left:"undefined"};

	var a3 = $("#a3-"+i);
	var a3Position = (a3.attr("dropped")=="true")?{top:a3.offset().top,left:a3.offset().left}:{top:"undefined",left:"undefined"};

	var a4 = $("#a4-"+i);
	var a4Position = (a4.attr("dropped")=="true")?{top:a4.offset().top,left:a4.offset().left}:{top:"undefined",left:"undefined"};

	var a5 = $("#a5-"+i);
	var a5Position = (a5.attr("dropped")=="true")?{top:a5.offset().top,left:a5.offset().left}:{top:"undefined",left:"undefined"};
	
	var a6 = $("#a6-"+i);
	var a6Position = (a6.attr("dropped")=="true")?{top:a6.offset().top,left:a6.offset().left}:{top:"undefined",left:"undefined"};
	
	var a7 = $("#a7-"+i);
	var a7Position = (a7.attr("dropped")=="true")?{top:a7.offset().top,left:a7.offset().left}:{top:"undefined",left:"undefined"};
	
	var a8 = $("#a8-"+i);
	var a8Position = (a8.attr("dropped")=="true")?{top:a8.offset().top,left:a8.offset().left}:{top:"undefined",left:"undefined"};
	
	var a9 = $("#a9-"+i);
	var a9Position = (a9.attr("dropped")=="true")?{top:a9.offset().top,left:a9.offset().left}:{top:"undefined",left:"undefined"};
	
	var a10 = $("#a10-"+i);
	var a10Position = (a10.attr("dropped")=="true")?{top:a10.offset().top,left:a10.offset().left}:{top:"undefined",left:"undefined"};
	
	var a11 = $("#a11-"+i);
	var a11Position = (a11.attr("dropped")=="true")?{top:a11.offset().top,left:a11.offset().left}:{top:"undefined",left:"undefined"};
	
	var a12 = $("#a12-"+i);
	var a12Position = (a12.attr("dropped")=="true")?{top:a12.offset().top,left:a12.offset().left}:{top:"undefined",left:"undefined"};
	
	var a13 = $("#a13-"+i);
	var a13Position = (a13.attr("dropped")=="true")?{top:a13.offset().top,left:a13.offset().left}:{top:"undefined",left:"undefined"};
	
	var a14 = $("#a14-"+i);
	var a14Position = (a14.attr("dropped")=="true")?{top:a14.offset().top,left:a14.offset().left}:{top:"undefined",left:"undefined"};
	
	var a15 = $("#a15-"+i);
	var a15Position = (a15.attr("dropped")=="true")?{top:a15.offset().top,left:a15.offset().left}:{top:"undefined",left:"undefined"};
	
	var a16 = $("#a16-"+i);
	var a16Position = (a16.attr("dropped")=="true")?{top:a16.offset().top,left:a16.offset().left}:{top:"undefined",left:"undefined"};

	var a17 = $("#a17-"+i);
	var a17Position = (a17.attr("dropped")=="true")?{top:a17.offset().top,left:a17.offset().left}:{top:"undefined",left:"undefined"};
	
	var a18 = $("#a18-"+i);
	var a18Position = (a18.attr("dropped")=="true")?{top:a18.offset().top,left:a18.offset().left}:{top:"undefined",left:"undefined"};

	var a19 = $("#a19-"+i);
	var a19Position = (a19.attr("dropped")=="true")?{top:a19.offset().top,left:a19.offset().left}:{top:"undefined",left:"undefined"};
	
	var a20 = $("#a20-"+i);			
	var a20Position = (a20.attr("dropped")=="true")?{top:a20.offset().top,left:a20.offset().left}:{top:"undefined",left:"undefined"};

	var a21 = $("#a21-"+i);			
	var a21Position = (a21.attr("dropped")=="true")?{top:a21.offset().top,left:a21.offset().left}:{top:"undefined",left:"undefined"};

	var b1 = $("#b1-"+i);
	var b1Position = (b1.attr("dropped")=="true")?{top:b1.offset().top,left:b1.offset().left}:{top:"undefined",left:"undefined"};
	
	var b2 = $("#b2-"+i);
	var b2Position = (b2.attr("dropped")=="true")?{top:b2.offset().top,left:b2.offset().left}:{top:"undefined",left:"undefined"};

	var b3 = $("#b3-"+i);
	var b3Position = (b3.attr("dropped")=="true")?{top:b3.offset().top,left:b3.offset().left}:{top:"undefined",left:"undefined"};

	var b4 = $("#b4-"+i);
	var b4Position = (b4.attr("dropped")=="true")?{top:b4.offset().top,left:b4.offset().left}:{top:"undefined",left:"undefined"};
	
	var b5 = $("#b5-"+i);
	var b5Position = (b5.attr("dropped")=="true")?{top:b5.offset().top,left:b5.offset().left}:{top:"undefined",left:"undefined"};

	var c4 = $("#c4-"+i);
	var c4Position = (c4.attr("dropped")=="true")?{top:c4.offset().top,left:c4.offset().left}:{top:"undefined",left:"undefined"};

	var c5 = $("#c5-"+i);
	var c5Position = (c5.attr("dropped")=="true")?{top:c5.offset().top,left:c5.offset().left}:{top:"undefined",left:"undefined"};
	
	var a6_a11 = euklidDistance(a6Position,a11Position);
	var a7_a10 = euklidDistance(a7Position,a10Position);
	var a8_a9 = euklidDistance(a8Position,a9.offset());
	var a12_a14 = euklidDistance(a12Position,a14Position);
	var a15_a16 = euklidDistance(a15Position,a16Position);
	var a18_a20 = euklidDistance(a18Position,a20Position);
	var a19_a21 = euklidDistance(a19Position,a21Position);
	var b1_a13 = euklidDistance(b1Position,a13Position);			
	var b1_b2 = euklidDistance(b1Position,b2Position);
	var b1_b5 = euklidDistance(b1Position,b5Position);

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

	var a12_a14_a19_a21 = a12_a14 / a19_a21;
	var a15_a16_a19_a21 = a15_a16 / a19_a21; 
	var a6_a11_a19_a21 = a6_a11 / a19_a21;
	
	var a6_a11_a8_a9 = a6_a11 / a8_a9;
	var a7_a10_a8_a9 = a7_a10 / a8_a9;


	window.myLine.data.datasets.push({
		  label: 'Person-' + i, 
		  fill: false,
		  borderColor: colors[i],
		  data: [b1_b2_a6_a11,b1_b5_a6_a11,a12_a14_a15_a16,a12_a14_b1_a13,a12_a14_b1_b5,a15_a16_a6_a11,a15_a16_a7_a10,a8_a9_a15_a16,a8_a9_b1_a13,a8_a9_b1_b5,a7_a10_b1_b5,b1_a13_a7_a10,b1_a13_b1_b5,b1_a13_a6_a11,a12_a14_a18_a20,a15_a16_a18_a20,a6_a11_a18_a20,a12_a14_a19_a21,a15_a16_a19_a21,a6_a11_a19_a21,a6_a11_a8_a9,a7_a10_a8_a9]
	}); 

 
	var personJSON = {
						landmarks:{
							a1:[a1Position.left,a1Position.top],
							a2:[a2Position.left,a2Position.top],
							a3:[a3Position.left,a3Position.top],
							a4:[a4Position.left,a4Position.top],
							a5:[a5Position.left,a5Position.top],									
							a6:[a6Position.left,a6Position.top],
							a7:[a7Position.left,a7Position.top],
							a8:[a8Position.left,a8Position.top],
							a9:[a9Position.left,a9Position.top],
							a10:[a10Position.left,a10Position.top],
							a11:[a11Position.left,a11Position.top],
							a12:[a12Position.left,a12Position.top],
							a13:[a13Position.left,a13Position.top],
							a14:[a14Position.left,a14Position.top],
							a15:[a15Position.left,a15Position.top],
							a16:[a16Position.left,a16Position.top],
							a17:[a17Position.left,a17Position.top],
							a18:[a18Position.left,a18Position.top],
							a19:[a19Position.left,a19Position.top],
							a20:[a20Position.left,a20Position.top],
							a21:[a21Position.left,a21Position.top],
							b1:[b1Position.left,b1Position.top],
							b2:[b2Position.left,b2Position.top],
							b3:[b3Position.left,b3Position.top],
							b4:[b4Position.left,b4Position.top],
							b5:[b5Position.left,b5Position.top],
							c4:[c4Position.left,c4Position.top],
							c5:[c5Position.left,c5Position.top]
						} 
	} 
	
	return personJSON;
}