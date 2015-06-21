$(function() {
  $( "#orders_fromdate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#orders_todate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#orderdate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#requireddate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#update_orderdate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#update_requireddate" ).datepicker({ dateFormat: "yy-mm-dd" });
  $( "#telephone" ).mask( "999-999-9999" );
  $( "#cellphone" ).mask( "999-999-9999" );
  $( "#fax" ).mask( "999-999-9999" );
  $( "#frontlogocolor" ).jec({ maxLength: 20 });
  $( "#frontlogotrimcolor" ).jec({ maxLength: 20 });
  $( "#frontnumcolor" ).jec({ maxLength: 20 });
  $( "#frontnumtrimcolor" ).jec({ maxLength: 20 });
  $( "#rearlogocolor" ).jec({ maxLength: 20 });
  $( "#rearlogotrimcolor" ).jec({ maxLength: 20 });
  $( "#rearnumcolor" ).jec({ maxLength: 20 });
  $( "#rearnumtrimcolor" ).jec({ maxLength: 20 });
  $( "#shortlogocolor" ).jec({ maxLength: 20 });
  $( "#shortlogotrimcolor" ).jec({ maxLength: 20 });
  $( "#shortnumcolor" ).jec({ maxLength: 20 });
  $( "#shortnumtrimcolor" ).jec({ maxLength: 20 });

});


function drawFrontStyle(stockid,logoPos,numPos,numSize) {
	//logoPos: 0 none/1 center/2 left chest/3 right chest/4 top
	//numPos: 0 none/1 center/2 left chest/3 right chest/4 top
	//numSize: 0/2/4/6/8/10
	if (logoPos == "1") {
		$('#'+stockid+'f22').text("LG");
		$('#'+stockid+'f22').css("text-align", "center");
		$('#'+stockid+'f22').css("vertical-align", "bottom");
	} else if (logoPos == "2") {
		$('#'+stockid+'f22').text("LG");
		$('#'+stockid+'f22').css("text-align", "right");
		$('#'+stockid+'f22').css("vertical-align", "bottom");
	} else if (logoPos == "3") {
		$('#'+stockid+'f22').text("LG");
		$('#'+stockid+'f22').css("text-align", "left");
		$('#'+stockid+'f22').css("vertical-align", "bottom");
	} else if (logoPos == "4") {
		$('#'+stockid+'f22').text("LG");
		$('#'+stockid+'f22').css("text-align", "center");
		$('#'+stockid+'f22').css("vertical-align", "middle");
	} else {
		$('#'+stockid+'f22').text("");
		$('#'+stockid+'f22').css("text-align", "center");
		$('#'+stockid+'f22').css("vertical-align", "bottom");
	}
	
	if (numPos == "1") {
		$('#'+stockid+'f32').text(numSize+"\"");
		$('#'+stockid+'f32').css("text-align", "center");
		$('#'+stockid+'f32').css("vertical-align", "middle");
	} else if (numPos == "2") {
		$('#'+stockid+'f32').text(numSize+"\"");
		$('#'+stockid+'f32').css("text-align", "right");
		$('#'+stockid+'f32').css("vertical-align", "middle");
	} else if (numPos == "3") {
		$('#'+stockid+'f32').text(numSize+"\"");
		$('#'+stockid+'f32').css("text-align", "left");
		$('#'+stockid+'f32').css("vertical-align", "middle");
	} else if (numPos == "4") {
		$('#'+stockid+'f32').text(numSize+"\"");
		$('#'+stockid+'f32').css("text-align", "center");
		$('#'+stockid+'f32').css("vertical-align", "top");
	} else {
		$('#'+stockid+'f32').text("");
		$('#'+stockid+'f32').css("text-align", "center");
		$('#'+stockid+'f32').css("vertical-align", "middle");
	}
	
}

function drawRearStyle(stockid,logoPos,numPos,numSize) {
	//logoPos: 10 none/11 center/12 bottom/13 Name/14 top
	//numPos: 30 none/31 center/32 bottom/33 top
	//numSize: 0/2/4/6/8/10
	if (logoPos == "11") {
		$('#'+stockid+'r22').text("LG");
		$('#'+stockid+'r22').css("text-align", "center");
		$('#'+stockid+'r22').css("vertical-align", "middle");
	} else if (logoPos == "12") {
		$('#'+stockid+'r22').text("LG");
		$('#'+stockid+'r22').css("text-align", "center");
		$('#'+stockid+'r22').css("vertical-align", "bottom");
	} else if (logoPos == "13") {
		$('#'+stockid+'r22').text("NAME");
		$('#'+stockid+'r22').css("text-align", "center");
		$('#'+stockid+'r22').css("vertical-align", "middle");
	} else if (logoPos == "14") {
		$('#'+stockid+'r22').text("LG");
		$('#'+stockid+'r22').css("text-align", "center");
		$('#'+stockid+'r22').css("vertical-align", "top");
	} else {
		$('#'+stockid+'r22').text("");
		$('#'+stockid+'r22').css("text-align", "center");
		$('#'+stockid+'r22').css("vertical-align", "middle");
	}
	
	if (numPos == "31") {
		$('#'+stockid+'r32').text(numSize+"\"");
		$('#'+stockid+'r32').css("text-align", "center");
		$('#'+stockid+'r32').css("vertical-align", "middle");
	} else if (numPos == "32") {
		$('#'+stockid+'r32').text(numSize+"\"");
		$('#'+stockid+'r32').css("text-align", "center");
		$('#'+stockid+'r32').css("vertical-align", "bottom");
	} else if (numPos == "33") {
		$('#'+stockid+'r32').text(numSize+"\"");
		$('#'+stockid+'r32').css("text-align", "center");
		$('#'+stockid+'r32').css("vertical-align", "top");
	} else {
		$('#'+stockid+'r32').text("");
		$('#'+stockid+'r32').css("text-align", "center");
		$('#'+stockid+'r32').css("vertical-align", "middle");
	}
	
}

function drawShortStyle(stockid,logoPos,numPos,numSize) {
	//logoPos: 20 none/21 left/22 right
	//numPos: 20 none/21 left/22 right
	//numSize: 0/2/4/6/8/10
	if (logoPos == "21") {
		$('#'+stockid+'s22').text("LG");
		$('#'+stockid+'s22').css("text-align", "right");
		$('#'+stockid+'s22').css("vertical-align", "middle");
	} else if (logoPos == "22") {
		$('#'+stockid+'s22').text("LG");
		$('#'+stockid+'s22').css("text-align", "left");
		$('#'+stockid+'s22').css("vertical-align", "middle");
	} else {
		$('#'+stockid+'s22').text("");
		$('#'+stockid+'s22').css("text-align", "center");
		$('#'+stockid+'s22').css("vertical-align", "middle");
	}
	
	if (numPos == "21") {
		$('#'+stockid+'s32').text(numSize+"\"");
		$('#'+stockid+'s32').css("text-align", "right");
		$('#'+stockid+'s32').css("vertical-align", "top");
	} else if (numPos == "22") {
		$('#'+stockid+'s32').text(numSize+"\"");
		$('#'+stockid+'s32').css("text-align", "left");
		$('#'+stockid+'s32').css("vertical-align", "top");
	} else {
		$('#'+stockid+'s32').text("");
		$('#'+stockid+'s32').css("text-align", "center");
		$('#'+stockid+'s32').css("vertical-align", "top");
	}
	
}

function updateFrontStyle(stockid) {
	var logoPosId = "frontlogopos";
	var numPosId = "frontnumpos";
	var numSizeId = "frontnumsize";
	
	var logoPos = $('#'+logoPosId+' option:selected').val();
	var numPos = $('#'+numPosId+' option:selected').val();
	var numSize = $("input[name='"+numSizeId+"']:checked").val();

	drawFrontStyle(stockid,logoPos,numPos,numSize);
}

function updateRearStyle(stockid) {
	var logoPosId = "rearlogopos";
	var numPosId = "rearnumpos";
	var numSizeId = "rearnumsize";
	
	var logoPos = $('#'+logoPosId+' option:selected').val();
	var numPos = $('#'+numPosId+' option:selected').val();
	var numSize = $("input[name='"+numSizeId+"']:checked").val();

	drawRearStyle(stockid,logoPos,numPos,numSize);
}

function updateShortStyle(stockid) {
	var logoPosId = "shortlogopos";
	var numPosId = "shortnumpos";
	var numSizeId = "shortnumsize";
	
	var logoPos = $('#'+logoPosId+' option:selected').val();
	var numPos = $('#'+numPosId+' option:selected').val();
	var numSize = $("input[name='"+numSizeId+"']:checked").val();
	
	drawShortStyle(stockid,logoPos,numPos,numSize);
}

