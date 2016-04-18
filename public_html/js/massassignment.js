
$( ".barcode" ).last().keyup(function() {
	Fbarcode();
});

$( ".serial" ).last().keyup(function() {
	Fserial();
});

function Fbarcode(){
	if($( ".barcode" ).last().val().length==10){
		console.log($( ".barcode" ).last().val());
		var div = document.createElement('div');
		div.innerHTML = '<input type="text" class="serial" id="serial" name="serial"  onkeyup="Fserial()" value="" placeholder="Serial..."><input type="text" onkeyup="Fbarcode()"  class="barcode" name="barcode"  id="barcode" value="" placeholder="Código de bar..."><p id="alert" name="error"></p><div class="clear rows"></div>';	
		div.setAttribute('class', 'input'); 
		$( ".form-group" ).last().append(div);
		$( ".serial" ).last().focus();
	}
}

function Fserial(){
	if($( ".serial" ).last().val().length==11){
		console.log($( ".serial" ).last().val());
		$( ".barcode" ).last().focus();
	}
}

$( "#assignall" ).click(function() {
  console.log("fff");
  var Allbarcode = document.getElementsByName("barcode");
  var Allserials = document.getElementsByName("serial");
  var serials ='';
  console.log(Allserials);
  for (serial in Allserials) {
  	if(Allserials[serial].value!=null){
  		serials = serials +  Allserials[serial].value + ', ' ;
  	}
  }

  var barcodes ='';
  for (barcode in Allbarcode) {
  	if(Allbarcode[barcode].value!=null){
  		barcodes = barcodes +  Allbarcode[barcode].value + ', ' ;
  	}
    
  }
  var postData = 
		{
			"serials":serials,
			"barcodes":barcodes
		}

      var dataString = JSON.stringify(postData);

   $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../Ajax/massassignment/",
          success: function(data){
              $("#alert").css("display", "initial");
              var res = data.split("$");
              var AllErrors = document.getElementsByName("error");
              for(var i =0; i<AllErrors.length;i++){
              	console.log(res);
              	console.log(res[i]);
              	console.log(AllErrors[i]);
              	AllErrors[i].innerHTML  = res[i];
              }
              console.log(data);
          },
          error: function(e){
              $("#alert").html(e);
              console.log(e);
          }
  });
});


