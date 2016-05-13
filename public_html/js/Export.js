//create the variables coloms, rows and total for the export page.
//set them to default
var columns = [];
var rows = [];
var total=0;




$( ".button" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

$( "#DownloadBarcodes" ).click(function() {
   $("#openModal").css("opacity", "1");
    $("#openModal").css("pointer-events", "auto");
}); 

$( "#CloseAddModal" ).click(function() {
    $("#openModal").css("opacity", "0");
    $("#openModal").css("pointer-events", "none");
});

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

    FillDataInDropdowon('json-datalistDepartamento', 'Departamento', 'Departamentohidden', "../Ajax/placesstates/", "Nicaragua",  'Departamento');
    showdropdowns(1);
});




//when the previeuw button of laptops is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitlaptop" ).click(function() {
    document.getElementById("DownloadFile").setAttribute("data", "");
    GetData(this, 'laptopsForm'); 
    changeHref(this, 'laptopsForm');
});

//when the previeuw button of people is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitpeople" ).click(function() {
    document.getElementById("DownloadFile").setAttribute("data", "");
    GetData(this, 'peopleForm');
    changeHref(this, 'peopleForm');    
});

//when the previeuw button of places is clicked set the proper data in the screen and change the ref in the download excel button
$( "#submitplaces" ).click(function() {
    document.getElementById("DownloadFile").setAttribute("data", "notclasses");
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

    //set the correct href in the button DownloadBarcodes
    
    document.getElementById("etiquetas").setAttribute('data','barcodes?Departamento='+Departamento+
                                                                '&Ciudad='+Ciudad+
                                                                '&Escuela='+Escuela+
                                                                '&Turno='+Turno+
                                                                '&grado='+grado+
                                                                '&Seccion='+Seccion+
                                                                '&selection=etiquetas');


    document.getElementById("barras").setAttribute('data','barcodes?Departamento='+Departamento+
                                                                '&Ciudad='+Ciudad+
                                                                '&Escuela='+Escuela+
                                                                '&Turno='+Turno+
                                                                '&grado='+grado+
                                                                '&Seccion='+Seccion+
                                                                '&selection=barras');

    document.getElementById("ambos").setAttribute('data','barcodes?Departamento='+Departamento+
                                                                '&Ciudad='+Ciudad+
                                                                '&Escuela='+Escuela+
                                                                '&Turno='+Turno+
                                                                '&grado='+grado+
                                                                '&Seccion='+Seccion+
                                                                '&selection=ambos');

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
    if(formname == 'WhereAreTheLaptops'){
        //set the postdata with all the data from the form
        var postData = 
        {
            "data": datainput
        }

        //stringify that shit
        var dataString = JSON.stringify(postData);

        $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../Ajax/LookUpLaptops/",
            success: function(data){
                var table = document.getElementById("exportTable");
                var jsonOptions = JSON.parse(data);
                // Loop over the JSON array.
                var row = table.insertRow(0);
                var i = 0;
                //set the table headers
                for (var k in jsonOptions[0][0]){
                    if (jsonOptions[0][0].hasOwnProperty(k)) {
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

                //set some example data
                for(var i = 0; i < jsonOptions.length; ++i) {
                    var l=0;
                    var row = table.insertRow();
                    for (var k in jsonOptions[i][0]){
                        var cell1 = row.insertCell(l);
                        cell1.innerHTML =jsonOptions[i][0][k];
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
        columns = [];
        rows = [];
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

        //get the data from the fields
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
        console.log('test3')
       
        //get the correct place
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

        console.log('test4')

        
        //set the name of the place in the field 'place'
        document.getElementById("place").innerHTML= "" + places;

        //if no boxes are selected do nothing, else go wild
        if(0<boxlist.length){

            //set the postdata with all the data from the form
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

            //stringify that shit
            var dataString = JSON.stringify(postData);

            //if the formname is laptopsform execute the following code
            if(formname == 'laptopsForm'){

                //make the ajaxrequest to get the list according to the request
                $.ajax({
                        method: "POST",
                        data: {action:dataString},
                        url: "../Ajax/getList/",
                        success: function(data){
                            var table = document.getElementById("exportTable");
                            var jsonOptions = JSON.parse(data);
                            // Loop over the JSON array.
                            var row = table.insertRow(0);
                            var i = 0;

                            //set the headers of the table
                            for (var k in jsonOptions[0]['data'][0]){
                                if (jsonOptions[0]['data'][0].hasOwnProperty(k)) {
                                     var cell1 = row.insertCell(i);
                                     cell1.innerHTML = k;
                                     i++;
                                     columns.push({title: k, dataKey:k});
                                }
                            }

                            //get the total number of items
                            for (var i = 0; i < jsonOptions.length; i++) {
                                total = total + jsonOptions[i]['data'].length;
                            }

                            //set the total number of laptops
                            document.getElementById("total").innerHTML = "computadoras totales :"+total; 
                            rows = jsonOptions; 
                            $width = [0,0,0,0,0,0,0,0];
                            var j = 1;

                            //set some example data in the window
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

                            //show the data
                            document.getElementById("pdfcontent").style.display = "inherit";

                        },
                        error: function(e){
                            console.log(e);
                        }
                });
            }

            //if its not a laptopform get the other list
            else{
                $.ajax({
                        method: "POST",
                        data: {action:dataString},
                        url: "../Ajax/getList/",
                        success: function(data){
                            var table = document.getElementById("exportTable");
                            var jsonOptions = JSON.parse(data);
                            // Loop over the JSON array.
                            var row = table.insertRow(0);
                            var i = 0;

                            //set the table headers
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

                            //set some example data
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
}

//when the downloadfile is selected, download a pdf file with all the data that has been gatherd.
$('#DownloadFile').click(function () {
    if(document.getElementById("DownloadFile").getAttribute("data")=='notclasses'){
        //create a new pdf file 
        var doc = new jsPDF('p', 'pt');
        console.log(rows);
        //set the font siez
        doc.setFontSize(14);

        //set a piece of text in the pdf file
        doc.text("Donde esta la computadora" , 40, 40);
        var listOfLaptops = [];
        for (var i = 0; i < rows.length; i++) {
             listOfLaptops[i]=rows[i][0];
        }
        console.log(listOfLaptops);
        //set the font siez
        doc.setFontSize(9);
        //set the data from the coloms and the rows in the pdf file
        doc.autoTable(columns, listOfLaptops, {

        //this is all some beautiful design
        startY: 60,
        styles: {
            fillStyle: 'DF',
            overflow: 'linebreak',
            halign: "center"
        },
        headerStyles: {
            fillColor: [22,127,146],
            textColor: 255,
            fontSize: 11,
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
        margin: {top: 80}
        });
        
        //safe the file as table.pdf to the pc
        doc.save('table.pdf');
    }
    else{

        //get the data from the place
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

        //get the total number of items
        total = 0;
        for (var i = 0; i < rows.length; i++) {
            total = total + rows[i]['data'].length;
        }

        //create a new pdf file 
        var doc = new jsPDF('p', 'pt');

        //set the font siez
        doc.setFontSize(14);

        //set a piece of text in the pdf file
        doc.text("place name: "+places + ' -- Total laptops:'+total , 40, 30);

        //loop over the data, these rows all present a class
        for (var i = 0; i < rows.length; i++) {

            //if its the first item, the design is a bit different
            if(i == 0){

                //set the name of the class in the pdf
                doc.text(''+rows[i]['name'], 40, 50);

                //set the data from the coloms and the rows in the pdf file
                doc.autoTable(columns, rows[i]['data'], {

                //this is all some beautiful design
                startY: 60,
                styles: {
                    fillStyle: 'DF',
                    overflow: 'linebreak',
                    halign: "center"
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
                margin: {top: 80}
                });

                doc.addPage();
            }

            //not the first class
            else{

                //set the name of the class in the pdf
                doc.text(''+rows[i]['name'], 40, 30);

                //set the data from the coloms and the rows in the pdf file
                doc.autoTable(columns, rows[i]['data'], {

                //this is all some beautiful design
                startY: 40,
                styles: {
                    fillStyle: 'DF',
                    overflow: 'linebreak',
                    halign: "center"
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
                margin: {top: 80}
                });

                doc.addPage();
                y = 0 ;
            }
            
        }
        
        //safe the file as table.pdf to the pc
        doc.save('table.pdf');
    }
});




//if the Departamento changes, change the data in the following fields
$('#Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistCiudad', 'ciudad', 'Ciudadhidden', "../Ajax/placescitys/", this,  'Ciudad');
    }
    showdropdowns(2);
});

//if the Ciudad changes, change the data in the following fields
$('#Ciudad').on('input', function(){
    var options = document.getElementById("json-datalistCiudad").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistEscuela', 'Escuela', 'Escuelahidden', "../Ajax/placesschools/", this,  'Escuela');
    }
    showdropdowns(3);
});

//if the Escuela changes, change the data in the following fields
$('#Escuela').on('input', function(){
    var options = document.getElementById("json-datalistEscuela").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistTurno', 'Turno', 'Turnohidden', "../Ajax/placesturnos/", this,  'Turno');
    }
    showdropdowns(4);
});

//if the Turno changes, change the data in the following fields
$('#Turno').on('input', function(){
    var options = document.getElementById("json-datalistTurno").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
          FillDataInDropdowon('json-datalistgrado', 'grado', 'gradohidden', "../Ajax/placesgrados/", this,  'grado');
    }
    showdropdowns(5);
});

//if the grado changes, change the data in the following fields
$('#grado').on('input', function(){
    var options = document.getElementById("json-datalistgrado").options
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()) 
         FillDataInDropdowon('json-datalistSeccion', 'Seccion', 'Seccionhidden', "../Ajax/placesseccions/", this,  'Seccion');
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

//set the data in the country list
SetData('Pais','json-datalistPais');

//show the country hide all the rest and clear the fields
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

//set the correct data in the correct datalist, this with an ajax request
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
          
          option.value = item.name;


          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);
        }); 
        
        // Update the placeholder text.
        input.placeholder = value+"...";
      } else {

        // An error occured :(
        input.placeholder = "No se pudo cargar la lista de opciones :(";
      }
    }
  };
    // Update the placeholder text.
  input.placeholder = "Cargando opciones...";

  // Set up and make the request.
  request.open('GET', '../Ajax/placescountries/', true);
  request.send();
}

//clear a datalist
function clearChildren( parent_id ) {
    var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren( parent_id );
    }
}

//fill the data in the dropdown
function FillDataInDropdowon(datalist, item2, displayitem, ajax, element, item3){
    $value = element.value;
    if(element.value==null){
        $value = element;
    }
    var Departamento = document.getElementById('Departamento').value;
    var Ciudad = document.getElementById('Ciudad').value;
    var Escuela = document.getElementById('Escuela').value;
    var Turno = document.getElementById('Turno').value;
    var grado = document.getElementById('grado').value;

    //delete all the data in selected datalist
    clearChildren(datalist);
    $('.'+item2).val('');
    $('.'+displayitem).css('display', 'inherit');

    //get the correct data for the ajax request
    var postData = 
        {
            "name": $value,
            "Ciudad":Ciudad,
            "Escuela":Escuela,
            "Turno":Turno,
            "grado":grado,
            "Departamento":Departamento
        }

    //stringify woop woop woop!!
    var dataString = JSON.stringify(postData);

    //do the request to the correct function
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

//if there is an value entered in .serial, call the function Fbarcode
$( ".serial" ).last().keyup(function() {
    Fserial();
});

//swap to the next field if the length of .serial is 10
function Fserial(){
    console.log($( ".serial" ).val().indexOf(";"));
    if($( ".serial" ).val().indexOf(";")>0){
        if(($( ".serial" ).val().length-11)%12==0){
            $( ".serial" ).val( $( ".serial" ).val() + ';');
        }
    }
    else{
        if($( ".serial" ).val().length%11==0){
            $( ".serial" ).val( $( ".serial" ).val() + ';');
        }
    }
}

//when the previeuw button of people is clicked set the proper data in the screen and change the ref in the download excel button
$( "#searchlaptops" ).click(function() {
    $("#exportTable tr").remove();
    var data = $(".serial" ).val()
    GetData(data, 'WhereAreTheLaptops');

    document.getElementById("DownloadFile").setAttribute("data", "notclasses");

    document.getElementById("DownloadFileasExcel").style.display = "none";  
    document.getElementById("DownloadBarcodes").style.display = "none";  
});
