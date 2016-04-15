//create the variables coloms, rows and total for the export page.
//set them to default
var columns = [];
var rows = [];
var total=0;

//the function to let the accordion work
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




//when the previeuw button of laptops is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitlaptop" ).click(function() {
    GetData(this, 'laptopsForm'); 
    changeHref(this, 'laptopsForm');
});

//when the previeuw button of people is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitpeople" ).click(function() {
    GetData(this, 'peopleForm');
    changeHref(this, 'peopleForm');    
});

//when the previeuw button of places is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitplaces" ).click(function() {
    GetData(this, 'placesForm');  
    changeHref(this, 'placesForm');  
});

//change the href of the download excel button, in this way, the correct parameters are send to the php page.
function changeHref(datainput, formname){

    var boxlist = '';
    
    //get the selected boxes
    var total = $("#"+formname+" input:checkbox:checked").length;
    $("#"+formname+" input:checkbox:checked").each(function(index) {
        if (index === total - 1) {
            boxlist += this.value;
        }
        else{
            boxlist += this.value + ', ';
        }
        
    });

    //get the sort items
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

    //get the place items
    var Departamento = document.getElementById('Departamento').value;
    var Ciudad = document.getElementById('Ciudad').value;
    var Escuela = document.getElementById('Escuela').value;
    var Turno = document.getElementById('Turno').value;
    var grado = document.getElementById('grado').value;
    var Seccion = document.getElementById('Seccion').value;

    document.getElementById("DownloadBarcodes").href='barcodes?Departamento='+Departamento+
                                                                '&Ciudad='+Ciudad+
                                                                '&Escuela='+Escuela+
                                                                '&Turno='+Turno+
                                                                '&grado='+grado+
                                                                '&Seccion='+Seccion;

    //set the data in the excel file
    document.getElementById("DownloadFileasExcel").href='excel?coloms='+boxlist+
                                                                '&OrderByTerm='+OrderByTerm+
                                                                '&orderList='+orderList+
                                                                '&GroupByTerm='+GroupByTerm+
                                                                '&inputfield='+inputfield+
                                                                '&Departamento='+Departamento+
                                                                '&Ciudad='+Ciudad+
                                                                '&Escuela='+Escuela+
                                                                '&Turno='+Turno+
                                                                '&grado='+grado+
                                                                '&Seccion='+Seccion+
                                                                "&formname="+formname;

    //hide or show the buttons if a school is selected or not 
    if(document.getElementById('Escuela').value!=''){
         document.getElementById("DownloadFileasExcel").style.display = "inherit"; 
         document.getElementById("DownloadBarcodes").style.display = "inherit";   
    }
    else{
        document.getElementById("DownloadFileasExcel").style.display = "none";  
        document.getElementById("DownloadBarcodes").style.display = "none";  
    }
}

//get the data from the server with a ajax request to set the data for the download pdf file.
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
    var Seccion = document.getElementById('Seccion').value;
   
    var places='Nicaragua';
    if(Departamento != ''){
        places += ' : '+Departamento;
        if(Ciudad != ''){
            places += ' : '+Ciudad;
            if(Escuela != ''){
                places += ' : '+Escuela;
                if(Turno != ''){
                    places += ' : '+Turno;
                    if(grado != ''){
                        places += ' : '+grado;
                        if(Seccion != ''){
                            places += ' : '+Seccion;
                        }
                    }
                }
            }
        }
    }
    
    document.getElementById("place").innerHTML= "" + places;

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
                        "Seccion":Seccion,
                        "Departamento":Departamento
                    }
        var dataString = JSON.stringify(postData);
        if(formname == 'laptopsForm'){
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
                        for (var k in jsonOptions[0]['data'][0]){
                            if (jsonOptions[0]['data'][0].hasOwnProperty(k)) {
                                 var cell1 = row.insertCell(i);
                                 cell1.innerHTML = k;
                                 i++;
                                 columns.push({title: k, dataKey:k});
                            }
                        }
                        console.log(jsonOptions);
                        for (var i = 0; i < jsonOptions.length; i++) {
                            total = total + jsonOptions[i]['data'].length;
                        }
                        document.getElementById("total").innerHTML = "total laptops :"+total; 
                        rows = jsonOptions; 
                        $width = [0,0,0,0,0,0,0,0];
                        var j = 1;
                        for(var i = 0; i < jsonOptions[0]['data'].length; ++i) {
                            var l=0;
                            var row = table.insertRow();
                            for (var k in jsonOptions[0]['data'][i]){
                                var cell1 = row.insertCell(l);
                                cell1.innerHTML =jsonOptions[0]['data'][i][k];
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
        else{
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
                        document.getElementById("total").innerHTML = ""; 
                        document.getElementById("place").innerHTML = "";
                        rows = jsonOptions; 
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
}

$('#DownloadFile').click(function () {
    var Departamento = document.getElementById('Departamento').value;
    var Ciudad = document.getElementById('Ciudad').value;
    var Escuela = document.getElementById('Escuela').value;
    var Turno = document.getElementById('Turno').value;
    var grado = document.getElementById('grado').value;
    var Seccion = document.getElementById('Seccion').value;
    var places='Nicaragua';
    if(Departamento != ''){
        places += ' : '+ Departamento;
        if(Ciudad != ''){
            places += ' : '+Ciudad;
            if(Escuela != ''){
                places += ' : '+Escuela;
                if(Turno != ''){
                    places += ' : '+Turno;
                    if(grado != ''){
                        places += ' : '+grado;
                        if(Seccion != ''){
                            places += ' : '+Seccion;
                        }
                    }
                }
            }
        }
    }
    total = 0;
    for (var i = 0; i < rows.length; i++) {
        total = total + rows[i]['data'].length;
    }
    var doc = new jsPDF('l', 'pt');
    doc.setFontSize(14);
    doc.text("place name: "+places + ' -- Total laptops:'+total , 40, 30);
    for (var i = 0; i < rows.length; i++) {
        if(i == 0){
            doc.text(''+rows[i]['name'], 40, 50);
            doc.autoTable(columns, rows[i]['data'], {
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
            margin: {top: 80}/*,
            beforePageContent: function(data) {
                doc.text("place name: "+places , 40, 30);
            }*/
            });
        }
        else{
            doc.text(''+rows[i]['name'], 40, doc.autoTableEndPosY() + 30);
            doc.autoTable(columns, rows[i]['data'], {
            startY: doc.autoTableEndPosY() + 40,
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
            margin: {top: 80}/*,
            beforePageContent: function(data) {
                doc.text("place name: "+places , 40, 30);
            }*/
            });
        }
        
    }
    
    
    doc.save('table.pdf');
});

$('#Pais').on('input', function(){
    var options = document.getElementById("json-datalistPais").options
    console.log(options);
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistDepartamento', 'Departamento', 'Departamentohidden', "../ajax/placesstates/", this,  'Departamento');
    }
    showdropdowns(1);
   
});

$('#Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistCiudad', 'ciudad', 'Ciudadhidden', "../ajax/placescitys/", this,  'Ciudad');
    }
    showdropdowns(2);
});

$('#Ciudad').on('input', function(){
    var options = document.getElementById("json-datalistCiudad").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistEscuela', 'Escuela', 'Escuelahidden', "../ajax/placesschools/", this,  'Escuela');
    }
    showdropdowns(3);
});

$('#Escuela').on('input', function(){
    var options = document.getElementById("json-datalistEscuela").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistTurno', 'Turno', 'Turnohidden', "../ajax/placesturnos/", this,  'Turno');
    }
    showdropdowns(4);
});

$('#Turno').on('input', function(){
    var options = document.getElementById("json-datalistTurno").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
          FillDataInDropdowon('json-datalistgrado', 'grado', 'gradohidden', "../ajax/placesgrados/", this,  'grado');
    }
    showdropdowns(5);
});

$('#grado').on('input', function(){
    var options = document.getElementById("json-datalistgrado").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistSeccion', 'Seccion', 'Seccionhidden', "../ajax/placesseccions/", this,  'Seccion');
    }
});

//show or hide a few dropdowns deppeding on the number
function showdropdowns(placetype){
    if(placetype<=5){
        if(placetype<=4){
            if(placetype<=3){
                if(placetype<=2){
                    if(placetype<=1){
                        clearChildren('json-datalistCiudad');
                        $('.Ciudadhidden').css('display', 'none');
                        $('.ciudad').val('');
                    }
                    $('.Escuelahidden').css('display', 'none');
                    clearChildren('json-datalistEscuela');
                    $('.Escuela').val('');   
                }
                $('.Turnohidden').css('display', 'none');
                clearChildren('json-datalistTurno');
                $('.Turno').val('');   
            }
            $('.gradohidden').css('display', 'none');
            clearChildren('json-datalistgrado');
            $('.grado').val('');   
        }
        clearChildren('json-datalistSeccion');
        $('.Seccionhidden').css('display', 'none');
        $('.Seccion').val('');   
    }
    document.getElementById("pdfcontent").style.display = "none";  
}


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
  request.open('GET', '../ajax/placescountries/', true);
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