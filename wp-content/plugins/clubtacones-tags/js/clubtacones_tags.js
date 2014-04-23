console.log("en el js!!!!");
jQuery( document ).ready(function(){

	jQuery("#url_imagen").on("keypress",function(){
		console.log("change");
		jQuery("#main_content_div").empty();
		jQuery("#main_content_div").append("<img src='"+ jQuery("#url_imagen").val() +"'>");
		console.log("se comienza a generar heatmap");
		var config = {
	        element: document.getElementById("main_content_div"),
	        radius: 10,
	        opacity: 50,
	    };
	    
	    //creates and initializes the heatmap
	    var heatmap = h337.create(config);
	 
	    document.getElementById("main_content_div").onclick = function(e){
	    	console.log("click!");
	        // layerX and layerY is deprecated and will be removed,
	        // use another way of determining the x and y position in an element
	        heatmap.store.addDataPoint(e.layerX, e.layerY, 1);
	    };
	    console.log("se termino de generar heatmap");

	})


}); 