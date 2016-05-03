//if there is an value entered in .barcode, call the function Fbarcode
$( ".barcode" ).last().keyup(function() {
	Fbarcode();
});

//if there is an value entered in .serial, call the function Fbarcode
$( ".serial" ).last().keyup(function() {
	Fserial();
});

//if the length of the last barcode is 10 add a new row with a new box serial and barcode
//after this swap to the next field
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

//swap to the next field if the length of .serial is 10
function Fserial(){
	if($( ".serial" ).last().val().length==11){
		console.log($( ".serial" ).last().val());
		$( ".barcode" ).last().focus();
	}
}



//when assignall is clicked, send all the data to the server and do the assignments
$( "#assignall" ).click(function() {

  //get the barcodes
  var Allbarcode = document.getElementsByName("barcode");
  
  //get the serials
  var Allserials = document.getElementsByName("serial");


  var serials ='';

  //set the serials in the field serials
  for (serial in Allserials) {
  	if(Allserials[serial].value!=null){
  		serials = serials +  Allserials[serial].value + ', ' ;
  	}
  }

  var barcodes ='';

  //set the barcodes in the field barcode
  for (barcode in Allbarcode) {
  	if(Allbarcode[barcode].value!=null){
  		barcodes = barcodes +  Allbarcode[barcode].value + ', ' ;
  	}
    
  }

  //gather the data in a field
  var postData = 
		{
			"serials":serials,
			"barcodes":barcodes
		}

  // and again we are making a beauty of a stringify 
  var dataString = JSON.stringify(postData);

  //do the ajax request to the server to assign all the laptops to the correct people. Power to the people wooop woop 
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


