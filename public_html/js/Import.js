

SetData('Pais','json-datalistPais');
/*SetData('Departamento','json-datalistDepartamento');
SetData('Ciudad','json-datalistCiudad');*/


$('.Pais').on('input', function(){
    var options = document.getElementById("json-datalistPais").options
    console.log(options);
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()){
        $value = this.value;
        $('.Departamento').empty();
        $(".Pais").val($value);
        changeForm('PaisID', $value, 'Departamentohidden');

        var postData = 
              {
                  "name": $value,
              }
        var dataString = JSON.stringify(postData);
        $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../ajax/placesstates/",
          success: function(data){
            console.log(data);
            var dataList = document.getElementById('json-datalistDepartamento');
              var input = document.getElementById('Departamento');
                  var jsonOptions = JSON.parse(data);
                  // Loop over the JSON array.
            jsonOptions.forEach(function(item) {
              // Create a new <option> element.
              var option = document.createElement('option');

              // Set the value using the item in the JSON array.
              option.text = item.id;
              option.value = item.name;

              // Add the <option> element to the <datalist>.
              dataList.appendChild(option);
            }); 
          },
          error: function(e){
            console.log(e);
            $("#alert").html(e);
          }
        }); 
       } 
         
    }
});

$('.Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()){
          $value = this.value;
          clearChildren('json-datalistCiudad');
          $('.ciudad').val('');
          $(".Departamento").val($value);
          changeForm('DepartamentoID', $value, 'Ciudadhidden');

          var postData = 
                {
                    "name": $value,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../ajax/placescitys/",
            success: function(data){
              console.log(data);
              var dataList = document.getElementById('json-datalistCiudad');
                var input = document.getElementById('Ciudad');
                    var jsonOptions = JSON.parse(data);
                    // Loop over the JSON array.
              jsonOptions.forEach(function(item) {
                // Create a new <option> element.
                var option = document.createElement('option');

                // Set the value using the item in the JSON array.
                option.text = item.id;
                option.value = item.name;

                // Add the <option> element to the <datalist>.
                dataList.appendChild(option);
              }); 
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});

$('.ciudad').on('input', function(){
    var options = document.getElementById("json-datalistCiudad").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()){
        $value = this.value;
        changeForm('CiudadID', $value, 'button');
        $(".ciudad").val($value);
       }
    }
});



$( "#ImportButtonEstudiantes" ).click(function() {
  var fileinput = document.getElementById("uploadformstudents_file")
  fileinput.click();
});

$( "#ImportButtonProfesores" ).click(function() {
  var fileinput = document.getElementById("uploadformteachers_file")
  fileinput.click();
});


$( "#ImportButtonLaptops" ).click(function() {
  var fileinput = document.getElementById("uploadformlaptops_file")
  fileinput.click();
});

$( "#ImportButtonescuelas" ).click(function() {
  var fileinput = document.getElementById("uploadformescuelas_file")
  fileinput.click();
});


$(document).ready(function() {
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
 
    $('.accordion-section-title').click(function(e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');
 
        if($(e.target).is('.active')) {
            close_accordion_section();
        }else {
            close_accordion_section();
 
            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
        }
        e.preventDefault();
    });
});

function clearChildren( parent_id ) {
    var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren( parent_id );
    }
}

function FillInForm(originalitem, newitem, showitem){
  element = document.getElementsByClassName(originalitem);
  if(element[0].value != ''){
    changeForm(newitem, element[0].value, showitem);
  }
}

function changeForm(classname, value, showitem) {
	elements = document.getElementsByClassName(classname);
    for (var i = 0; i < elements.length; i++) {
        elements[i].value=value;
    }
    console.log(showitem);
    console.log($('#'+showitem));
    $('.'+showitem).css('display', 'inherit');
}

function SetData(input, datalist){
  var value = input.toLowerCase();


  // Get the <datalist> and <input> elements.
  var dataList = document.getElementById(datalist);
  var input = document.getElementById(input);

  // Create a new XMLHttpRequest.
  var request = new XMLHttpRequest();
  // Handle state changes for the request.
  request.onreadystatechange = function(response) {
    
    if (request.readyState === 4) {
      if (request.status === 200) {

        // Parse the JSON
        var jsonOptions = JSON.parse(request.responseText);
      
        // Loop over the JSON array.
        jsonOptions.forEach(function(item) {
          // Create a new <option> element.
          var option = document.createElement('option');

          // Set the value using the item in the JSON array.
          option.text = item.id;
          option.value = item.name;


          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);
        }); 
        
        // Update the placeholder text.
        input.placeholder = value+"...";
      } else {

        // An error occured :(
        input.placeholder = "Couldn't load datalist options :(";
      }
    }
  };
    // Update the placeholder text.
  input.placeholder = "Loading options...";

  // Set up and make the request.
  request.open('GET', 'http://localhost:8080/rein.bauwens/site%20OLPC/website/source/public_html/ajax/placescountries/', true);
  request.send();
}
