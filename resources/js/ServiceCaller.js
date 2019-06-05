var ServiceCaller = {
		
	callService : function(serviceName,data,callback){
			$.ajax({
			    url: "services/"+serviceName+".service.php",
			    type: "POST",
			    cache: false,
			    data: data,
			    success: function(result) {
			    	if(callback){
			    		callback(result);
			    	} 	    			
			    	
			    },
			    error: function(result) { 
			    	
			    },
			    complete: function(result) {   }
			});
	}
}