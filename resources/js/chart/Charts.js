var Charts = {

		 createDoughnutChart : function(canvasElement, mixedDoughnutChartData,title){ 
				var myChart = new Chart(canvasElement, {
					
				    type: 'doughnut',
				    data: mixedDoughnutChartData,
				    options: {
						responsive: true,
						legend: {
							position: 'top',
						},
						title: {
							display: true,
							text: title
						},
						animation: {
							animateScale: true,
							animateRotate: true
						}
					}
				}); 

				return myChart;
			},
			
			createPiChart : function(canvasElement, mixedPieChartData,title){ 
				var myChart = new Chart(canvasElement, {
					
				    type: 'pie',
				    data: mixedPieChartData,
				    options: {
						responsive: true,
						legend: {
							position: 'top',
						},
						title: {
							display: true,
							text: title
						},
						animation: {
							animateScale: true,
							animateRotate: true
						}
					}
				}); 

				return myChart;
			},
		
			createStackedBarChart : function(canvasElement, mixedBarChartData,title){ 
				var myChart = new Chart(canvasElement, {
					
				    type: 'bar',
				    data: mixedBarChartData,
				    options: {
						title: {
							display: true,
							text: title
						},
						tooltips: {
							mode: 'index',
							intersect: false
						},
						responsive: true,
						scales: {
							xAxes: [{
								stacked: true,
							}],
							yAxes: [{
								stacked: true
							}]
						}
					}
				}); 

				return myChart;
			}
}