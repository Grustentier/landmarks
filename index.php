<html>

<head><title></title>
<meta charset="utf-8">
<link rel="stylesheet" href="resources/css/style.css">
<link rel="stylesheet" href="resources/css/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="resources/css/dropzone.css">
<link rel="stylesheet" href="resources/css/croppie/croppie.css">
<script type="text/javascript" src="resources/js/jquery/jquery-3.3.1.js"></script>
<script src="resources/js/jquery-ui/jquery-ui.js"></script>
<script src="resources/js/dropzone/dropzone.js"></script>
<script src="resources/js/chart/Charts.js"></script>
<script src="resources/js/chart/Chart.bundle.js"></script>
<script src="resources/js/chart/Chart.utils.js"></script>
<script src="resources/js/croppie/croppie.js"></script>
<script src="resources/js/system.js"></script> 
<script src="resources/js/ServiceCaller.js"></script> 
<script src="resources/js/colors.js"></script> 
  
</head>

<body>
<div id="menu">
<div id="MenuEntry1" onclick="$('html, body').stop().animate({'scrollTop': $('#descriptionContainer').offset().top-50}, 500, 'swing');">About</div>
<div id="MenuEntry2" onclick="$('#measurementContainer').show('slow',function(){$('#resultContainer').show(); $('html, body').stop().animate({'scrollTop': $('#measurementContainer').offset().top-50}, 500, 'swing');});">Measure</div>
<div id="MenuEntry3" onclick="$('html, body').stop().animate({'scrollTop': $('#resultContainer').offset().top-50}, 500, 'swing');">Results</div>
</div>
<div id="descriptionContainer"><div>
<table>
<tr><td style="width: 100px;background: url('resources/images/face.png') no-repeat center center transparent;"></td><td>
<!-- Mithilfe dieser App K&ouml;nnen Sie prüfen, wie ähnlich zwei oder mehrere Gesichter zueinander sind. 
Die Grundlage daf&uuml;r bilden sogenannte Gesichtslandmarken. &Uuml;ber diese können korrespondierende Punkte spezifischer Gesichtsmerkmale markiert werden. 
Die Landmarken weisen dann untereinander unterschiedliche Distanzen zueinander auf. 
Die Darstellung unterschiedlicher Distanzverhältnisse kann abschließend einen Überblick darüber verschaffen, inwiefern
unterschiedliche Gesichter, geringe Distanzen und somit große Ähnlichkeiten in Ihren Gesichtsmerkmalen aufweisen.-->
Using this app, you can check how similar two or more faces are to each other. The basis for this form so-called face landmarks. These can be used to mark corresponding points of specific facial features. The landmarks then have mutually different distances to each other. Finally, the depiction of different distances can provide an overview of how different faces, small distances and thus great similarities in your facial features.

</td></tr>
<tr><td style="width: 100px;background: url('resources/images/privacy.png') no-repeat center center transparent;"></td><td>
<!-- Von viel größerer Bedeutung für den Anwender ist das Speichern der in dieser App verarbeiteten Daten.  
An dieser Stelle wird versichert, dass alle Bilder die über eine DropZone o.Ä. für die App zur Verfügung gestellt werden, 
lediglich für die Darstellung im Browser vorgesehen sind, um die Funktion der App zu ermöglichen. 
Eine Speicherung der Bilder im Backend erfolgt somit nicht.
Genau so verhält sich die App im Umgang mit personenspezifischen Positionen einzelner Landmarken.
Sie als Nutzer können frei entscheiden, inwiefern Sie Landmarkenpositionen der Wissenschaft zut Verfügung stellen möchten.
Dabei würden lediglich die Positionen der Landmarken gespeichert und somit keinerlei Informationen dar&uuml;ber, welche auf die Identit&auml;t einer
Person zurückschließen lässt.-->
Of much greater importance to the user is storing the data processed in this app. At this point, it is assured that all images via a DropZone o.Ä. provided for the app, are intended only for display in the browser to enable the function of the app. A storage of images in the backend is thus not. This is exactly how the app behaves when dealing with person-specific positions of individual landmarks. As a user, you are free to decide to what extent you want to provide landmarks to science. In doing so, only the positions of the landmarks would be stored and thus no information about it, which would indicate the identity of a person.

</td></tr>
</table>

</div>
<div style="text-align: center;margin-top: 60px;">
<button id="startMeasurement" class="myBigButton" style=" background:MediumSeaGreen">Start</button>
</div>
</div>
<div id="measurementContainer">
	<button id="newMeasurement" class="myButton" style="margin-top: -47px;position: absolute;background:MediumSeaGreen">New Landmark Positioning</button>
	<div id="tabs"> <ul> </ul> </div>
</div>
<div id="resultContainer">
 
<!--img src="resources/images/arrow.png" style="width: 130px;padding: 20px;"/-->
<!-- br>
<button id='process' class='myBigButton'>Update Results</button-->
 
<br><br>
<div id="canvasContainer"><canvas id='canvas'></canvas></div>
<div id="console"></div> 
<button id='saveData' class='myBigButton' style="margin-top: 40px">Save Data</button>

</div>





<script>


 

$( document ).ready(function() {
	 
	$("#tabs").tabs();
	createNewTab();
	$( document ).tooltip();
	
	var ctx = document.getElementById('canvas').getContext('2d');	
	window.myLine = new Chart(ctx, config);

	$("#startMeasurement").click(function(){
			$(this).hide("slow"); 
			$('#measurementContainer').show('slow',function(){
				$('#MenuEntry2').show("slow"); 
				$('#MenuEntry3').show("slow"); 
				$('#resultContainer').show(); 
				$('html, body').stop().animate({
					'scrollTop': $('#measurementContainer').offset().top-50}, 500, 'swing');
				
			});
	});
	
	$("#newMeasurement").click(function(){ createNewTab();});

	$( "#tabs" ).tabs({ active: 0 });
	//$("#process").click(function(){
	  $("#canvasContainer").mouseenter(function(){ 
		  console.log(DATA_CHANGED);
		if(DATA_CHANGED != true){
			return false;
		} 
		cleanChart(); 
		RESULT_JSON = [];
		
		var tabCount = $('#tabs >ul >li').length;		
		for(i = 1; i < tabCount + 1; i++){
			$("#tabs ul li a[href='#tabs-"+i+"']").trigger("click");
			var personJSON = handleLandmarkData(i);
			RESULT_JSON.push(personJSON);
		}
		
		window.myLine.update(); 

		//console.log(JSON.stringify(RESULT_JSON)); 
		$("#console").text(JSON.stringify(RESULT_JSON));
		$("#saveData").show();

		scrollTo($("#canvasContainer"));

		/*
		var showValidationError = false;
		$.each(RESULT_JSON, function (index, data) { 
		    $.each(data, function (index1, data1) {
		    	$.each(data1, function (index2, data2) {
		    		if(data2[0] == "undefined" || data2[1] == "undefined"){
		    			showValidationError = true;
		    		}
		    	});
		    });
		});
		if(showValidationError == true){alert("Not all landmarks has been set!");} 
		*/

	});

	
	$("#saveData").click(function(){
		if(confirm("Would you like to make a scientific contribution by saving your set of landmarks?")){
			$.each(RESULT_JSON, function (index, data) { 
				//console.log(JSON.stringify(data));
				ServiceCaller.callService("jsonToFile",{json:JSON.stringify([data])},function(data){
					if(data && data == 1){
						$("#saveData").hide("slow");
						DATA_CHANGED = false;
						TAB_COUNT = 0;
						cleanTabs(); 
						cleanChart(); 
						$("#console").text("");
						createNewTab();
						$('html, body').stop().animate({'scrollTop': $('#tabs').offset().top-50}, 500, 'swing');
					}else{
					  	alert("An unexpected error occurred while saving. Please try again later!");
					}
				});
			});
		}
		//$('html, body').stop().animate({'scrollTop': $('#console').offset().top-50}, 500, 'swing');
	});
	
	 
  });


</script>



</body>

</html>