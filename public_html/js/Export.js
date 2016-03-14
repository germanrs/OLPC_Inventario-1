var columns = [];
var rows = [];

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

$( "#submitlaptop" ).click(function() {
    GetData(this, 'laptopsForm');      
});

$( "#submitpeople" ).click(function() {
    GetData(this, 'peopleForm');  
});

$( "#submitplaces" ).click(function() {
    GetData(this, 'placesForm');  
});

function GetData(datainput, formname){

    columns = [];
    rows = [];
    var boxlist = '';
    var total = $("#"+formname+" input:checkbox:checked").length;
    $("#"+formname+" input:checkbox:checked").each(function(index) {
        if (index === total - 1) {
            boxlist += this.value;
        }
        else{
            boxlist += this.value + ', ';
        }
        
    });
    $("#exportTable tr").remove();
    var OrderByTerm = $(datainput).closest("form")   
                       .find("#orderByTerm")
                       .val();                          
    var orderList = $(datainput).closest("form")   
                       .find("#orderList")
                       .val();  
    var GroupByTerm = $(datainput).closest("form")   
                       .find("#GroupByTerm")
                       .val();    
    var inputfield = $(datainput).closest("form")   
                       .find("#inputfield")
                       .val();
    var Departamento = document.getElementById('Departamento').value;
    var Ciudad = document.getElementById('Ciudad').value;
    var Escuela = document.getElementById('Escuela').value;
    var Turno = document.getElementById('Turno').value;
    var grado = document.getElementById('grado').value;

    if(0<boxlist.length){
        var postData = 
                    {
                        "formname": formname,
                        "coloms": boxlist,
                        "OrderByTerm":OrderByTerm,
                        "orderList":orderList,
                        "GroupByTerm":GroupByTerm,
                        "inputfield":inputfield,
                        "Ciudad":Ciudad,
                        "Escuela":Escuela,
                        "Turno":Turno,
                        "grado":grado,
                        "Departamento":Departamento
                    }

        var dataString = JSON.stringify(postData);
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../ajax/getList/",
                success: function(data){
                    var table = document.getElementById("exportTable");
                    var jsonOptions = JSON.parse(data);
                    // Loop over the JSON array.
                    var row = table.insertRow(0);
                    
                    var i = 0;
                    for (var k in jsonOptions[0]){
                        if (jsonOptions[0].hasOwnProperty(k)) {
                             var cell1 = row.insertCell(i);
                             cell1.innerHTML = k;
                             i++;
                             columns.push({title: k, dataKey:k});
                        }
                    }
                    
                    rows = jsonOptions.slice(0,200); 
                    $width = [0,0,0,0,0,0,0,0];
                    var j = 1;
                    for(var i = 0; i < jsonOptions.length; ++i) {
                        var l=0;
                        var row = table.insertRow();
                        for (var k in jsonOptions[i]){
                            var cell1 = row.insertCell(l);
                            cell1.innerHTML =jsonOptions[i][k];
                            l++;
                        }
                        j++;
                        if(j===20){
                            break;
                        }
                    };
                    document.getElementById("pdfcontent").style.display = "inherit";

                },
                error: function(e){
                    console.log(e);
                }
        });
    }
}



$('#DownloadFile').click(function () {
    var doc = new jsPDF('p', 'pt');
    doc.autoTable(columns, rows, {
        styles: {
            fillStyle: 'DF',
            overflow: 'linebreak',
        },
        headerStyles: {
            fillColor: [22,127,146],
            textColor: 255,
            fontSize: 15,
            rowHeight: 30
        },
        bodyStyles: {
            fillColor: [255, 255, 255],
            textColor: 000
        },
        alternateRowStyles: {
            fillColor:[234,243,243]
        },
        columnStyles: {
            email: {
                fontStyle: 'bold'
            }
        },
        margin: {top: 60},
        beforePageContent: function(data) {
            doc.text("Laptop Table", 40, 30);
        }
    });
    doc.save('table.pdf');
});

$('#Pais').on('input', function(){
    var options = document.getElementById("json-datalistPais").options
    console.log(options);
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistDepartamento', 'Departamento', 'Departamentohidden', "../ajax/placesstates/", this,  'Departamento');
    }
});

$('#Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistCiudad', 'ciudad', 'Ciudadhidden', "../ajax/placescitys/", this,  'Ciudad');
    }
});

$('#Ciudad').on('input', function(){
    var options = document.getElementById("json-datalistCiudad").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistEscuela', 'Escuela', 'Escuelahidden', "../ajax/placesschools/", this,  'Escuela');
    }
});

$('#Escuela').on('input', function(){
    var options = document.getElementById("json-datalistEscuela").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistTurno', 'Turno', 'Turnohidden', "../ajax/placesturnos/", this,  'Turno');
    }
});

$('#Turno').on('input', function(){
    var options = document.getElementById("json-datalistTurno").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
          FillDataInDropdowon('json-datalistgrado', 'grado', 'gradohidden', "../ajax/placesgrados/", this,  'grado');
    }
});

$('#grado').on('input', function(){
    var options = document.getElementById("json-datalistgrado").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistSeccion', 'Seccion', 'Seccionhidden', "../ajax/placesseccions/", this,  'Seccion');
    }
});



SetData('Pais','json-datalistPais');

function ShowCountry(){
    if($('.Paishidden').css('display')=='none'){
        $('.Paishidden').css('display', 'inherit');
    }
    else{
        
        $('.Ciudadhidden').css('display', 'none');
        $('.Departamentohidden').css('display', 'none');
        $('.Paishidden').css('display', 'none');
        $('.Seccionhidden').css('display', 'none');
        $('.gradohidden').css('display', 'none');
        $('.Turnohidden').css('display', 'none');
        $('.Escuelahidden').css('display', 'none');
        
        clearChildren('json-datalistCiudad');
        clearChildren('json-datalistDepartamento');
        clearChildren('json-datalistEscuela');
        clearChildren('json-datalistTurno');
        clearChildren('json-datalistgrado');
        clearChildren('json-datalistSeccion');

        $('.Pais').val('');
        $('.Departamento').val('');
        $('.Escuela').val('');
        $('.ciudad').val('');
        $('.grado').val('');
        $('.Turno').val('');
        $('.Seccion').val('');
    }    
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


function clearChildren( parent_id ) {
    var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren( parent_id );
    }
}

function FillDataInDropdowon(datalist, item2, displayitem, ajax, element, item3){
    $value = element.value;
    var Departamento = document.getElementById('Departamento').value;
    var Ciudad = document.getElementById('Ciudad').value;
    var Escuela = document.getElementById('Escuela').value;
    var Turno = document.getElementById('Turno').value;
    var grado = document.getElementById('grado').value;

    
    clearChildren(datalist);
    $('.'+item2).val('');
    $('.'+displayitem).css('display', 'inherit');
    var postData = 
        {
            "name": $value,
            "Ciudad":Ciudad,
            "Escuela":Escuela,
            "Turno":Turno,
            "grado":grado,
            "Departamento":Departamento
        }
    var dataString = JSON.stringify(postData);
    $.ajax({
        method: "POST",
        data: {action:dataString},
        url: ajax,
        success: function(data){
            console.log(data);
            var dataList = document.getElementById(datalist);
            var input = document.getElementById(item3);
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