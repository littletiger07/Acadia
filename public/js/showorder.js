$(function(){
	$("#showshipment").click(function(){
	    if ($("#shipment").css('display')=='none')
	    {
	    	$("#showshipment a").text('The Following Shipments are Included in This Order:');
		    $("#shipment").css('display','block');
	    }
	    else
	    {
	        $("#showshipment a").text('Click Here to View Shipping Information:');
	    	$("#shipment").css('display','none');
	    }
		});
	$("#showcost").click(function(){
	    if ($("#cost").css('display')=='none')
	    {
	    	$("#showcost a").text('The Following Costs are Included in This Order:');
		    $("#cost").css('display','block');
	    }
	    else
	    {
	        $("#showcost a").text('Click Here to View Cost Information:');
	    	$("#cost").css('display','none');
	    }
		});
	$("#showinvoice").click(function(){
	    if ($("#invoice").css('display')=='none')
	    {
	    	$("#showinvoice a").text('The Following Invoice are Included in This Order:');
		    $("#invoice").css('display','block');
	    }
	    else
	    {
	        $("#showinvoice a").text('Click Here to View Invoice Information:');
	    	$("#invoice").css('display','none');
	    }
		});
});