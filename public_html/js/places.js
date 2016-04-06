//call the function setData to fill in the datalist.
SetData('grade','json-datalistgrade');
SetDepartments();

//set the data from the department list
function SetDepartments(){
  var postData = 
        {
            "name": 'Nicaragua',
        }
  var dataString = JSON.stringify(postData);
  $.ajax({
    method: "POST",
    data: {action:dataString},
    url: "../../ajax/placesstates/",
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
        document.getElementById("Ciudad").disabled=true;
        document.getElementById("Escuela").disabled=true;
        document.getElementById("Turno").disabled=true;
        document.getElementById("grade").disabled=true;
        document.getElementById("Seccion").disabled=true;
        document.getElementById("ancestor").disabled=true;
        document.getElementById("place_type").disabled=true;
      });
      document.getElementById("ancestor").value = "Nicaragua";
      document.getElementById("place_type").value = "Departamento";
      setDataInTable(); 
    },
    error: function(e){
      console.log(e);
      $("#alert").html(e);
    }
  });
} 

//set the data of a datalist
//input = ID of textbox
//dataliist = id of datalist
//returns: fills up the datalist with the requested data
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
  request.open('GET', 'http://localhost:8080/rein.bauwens/site%20OLPC/website/source/public_html/ajax/'+value+'/', true);
  request.send();
}

//if the "all checkboxes box" is selected, select all the textboxes
$( "#chechallboxes" ).click(function() {

  //get all the cechboxes.
  checkboxes = document.getElementsByName('checkbox');

  //check all the chexboxes
  if (document.getElementById("chechallboxes").checked) {

          //loop over all the checkboxes and check the checkboxes
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
        //loop over all the checkboxes and uncheck the checkboxes
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }

});

//when an item has been selected in the departmentdatalist change the form
//select the correct city's from the database with a json request
$('#Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of depertment: "citys and schools"
       if (options[i].value == $(this).val()){
          $value = this.value;
          $("#Departamento").val($value);

          $('#Ciudad').val('');
          clearChildren('json-datalistCiudad');
          $('#Escuela').val('');
          clearChildren('json-datalistEscuela');
          $('#Turno').val('');
          clearChildren('json-datalistTurno');
          $('#grade').val('');
          clearChildren('json-datalistgrade');
          $('#Seccion').val('');
          clearChildren('json-datalistSeccion');
          document.getElementById("Escuela").disabled=true;
          document.getElementById("Turno").disabled=true;
          document.getElementById("grade").disabled=true;
          document.getElementById("Seccion").disabled=true;
          document.getElementById("ancestor").value = $value;
          document.getElementById("place_type").value = "Ciudad";
          

          //set the data for an ajax request to obtain the correct data for the city list
          var postData = 
                {
                    "name": $value,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../ajax/placescitys/",
            success: function(data){
              document.getElementById("Ciudad").disabled=false;
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
              setDataInTable();
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});

//when an item has been selected in the citydatalist change the form
//select the correct schools from the database with a json request
$('#Ciudad').on('input', function(){
    var options = document.getElementById("json-datalistCiudad").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of citys: schools
       if (options[i].value == $(this).val()){
          $value = this.value;
          $('#Escuela').val('');
          clearChildren('json-datalistEscuela');
          $('#Turno').val('');
          clearChildren('json-datalistTurno');
          $('#grade').val('');
          clearChildren('json-datalistgrade');
          $('#Seccion').val('');
          clearChildren('json-datalistSeccion');
          $("#Ciudad").val($value);
          document.getElementById("Turno").disabled=true;
          document.getElementById("grade").disabled=true;
          document.getElementById("Seccion").disabled=true;
          document.getElementById("ancestor").value = $value;
          document.getElementById("place_type").value = "Escuela";

          //set the data for an ajax request to obtain the correct data for the school list
          var postData = 
                {
                    "Ciudad": $value,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../ajax/placesschools/",
            success: function(data){
              document.getElementById("Escuela").disabled=false;
              console.log(data);
              var dataList = document.getElementById('json-datalistEscuela');
                var input = document.getElementById('Escuela');
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
              setDataInTable(); 
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});  

//when an item has been selected in the Escueladatalist change the form
//select the correct turnos from the database with a json request
$('#Escuela').on('input', function(){
    var options = document.getElementById("json-datalistEscuela").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of schools: turnos
       if (options[i].value == $(this).val()){
          $value = this.value;
          $('#Turno').val('');
          clearChildren('json-datalistTurno');
          $('#grade').val('');
          clearChildren('json-datalistgrade');
          $('#Seccion').val('');
          clearChildren('json-datalistSeccion');
          $("#Escuela").val($value);
          document.getElementById("grade").disabled=true;
          document.getElementById("Seccion").disabled=true;
          document.getElementById("ancestor").value = $value;
          document.getElementById("place_type").value = "Turno";
          //set the data for an ajax request to obtain the correct data for the turno list
          var postData = 
                {
                    "Ciudad": document.getElementById("Ciudad").value,
                    "name": $value
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../ajax/placesturnos/",
            success: function(data){
              document.getElementById("Turno").disabled=false;
              console.log(data);
              var dataList = document.getElementById('json-datalistTurno');
                var input = document.getElementById('Turno');
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
              setDataInTable(); 
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});

//when an item has been selected in the Turnodatalist change the form
//select the correct grades from the database with a json request
$('#Turno').on('input', function(){
    var options = document.getElementById("json-datalistTurno").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of Turno: grade
       if (options[i].value == $(this).val()){
          $value = this.value;
          $('#grade').val('');
          clearChildren('json-datalistgrade');
          $('#Seccion').val('');
          clearChildren('json-datalistSeccion');
          $("#Turno").val($value);
          document.getElementById("Seccion").disabled=true;
          document.getElementById("ancestor").value = $value;
          document.getElementById("place_type").value = "Grado";
          //set the data for an ajax request to obtain the correct data for the grade list
          var postData = 
                {
                    "Ciudad": document.getElementById("Ciudad").value,
                    "Escuela": document.getElementById("Escuela").value,
                    "name": $value
                    
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../ajax/placesgrados/",
            success: function(data){
              document.getElementById("grade").disabled=false;
              console.log(data);
              var dataList = document.getElementById('json-datalistgrade');
                var input = document.getElementById('grade');
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
              setDataInTable(); 
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});    

//when an item has been selected in the Turnodatalist change the form
//select the correct grades from the database with a json request
$('#grade').on('input', function(){
    var options = document.getElementById("json-datalistgrade").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of Turno: grade
       if (options[i].value == $(this).val()){
          $value = this.value;
          $('#Seccion').val('');
          clearChildren('json-datalistSeccion');
          $("#grade").val($value);
          document.getElementById("ancestor").value = $value;
          document.getElementById("place_type").value = "Seccion";
          //set the data for an ajax request to obtain the correct data for the grade list
          var postData = 
                {
                    "Ciudad": document.getElementById("Ciudad").value,
                    "Escuela": document.getElementById("Escuela").value,
                    "Turno": document.getElementById("Turno").value,
                    "name": $value
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../ajax/placesseccions/",
            success: function(data){
              document.getElementById("Seccion").disabled=false;
              console.log(data);
              var dataList = document.getElementById('json-datalistSeccion');
                var input = document.getElementById('Seccion');
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
              setDataInTable(); 
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});

//opening the add model. set all the values to default zero
$( "#openAddModal" ).click(function() {
  document.getElementById("Name").value = '';
  document.getElementById("server_hostname").value = '';
  document.getElementById("AddPlace").text = 'Add';

  //if the city is selected and the school not, then the user can add a school => he can add a server_hostname
  if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
   document.getElementById("server_hostname").disabled=false;
  }
  else{
   document.getElementById("server_hostname").disabled=true;
  }  
  $("#alert").css("display", "none");
  $("#openModal").css("opacity", "1");
  $("#openModal").css("pointer-events", "auto");
});

//close the model
$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

//call the function to eddit the place
$( ".Editplace" ).click(function() {
  Editplace(this);
});

//call the function to delete the place
$( ".Deleteplace" ).click(function() {
  Deleteplace(this);
});

//delete all the selected places
$( "#DeleteSelectedplaces" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  for (box in checkedBoxes) {
    Deleteplace(checkedBoxes[box]);
  }
});

//add a place
$( "#AddPlace" ).click(function() {

  //get all the vvalues from the form
  var name = document.getElementById("Name").value;
  var place_type = document.getElementById("place_type").value;
  var server_hostname = document.getElementById("server_hostname").value;
  var ancestor = document.getElementById("ancestor").value;

  //check if the values are not null
  if(!name || 0 === name.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in the fields: name, server-hostname and ancestor!");
  }
  else{

    //create the date
    var created_at = new Date();
    var dd = created_at.getDate();
    var mm = created_at.getMonth()+1; //January is 0!
    var yyyy = created_at.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 

    created_at = yyyy+'/'+mm+'/'+dd;

    //add 1 laptop
    if(document.getElementById("AddPlace").text == 'Add'){

      //set the data for an ajax request to add a place
      var postData = 
                  {
                      "Departamento":document.getElementById("Departamento").value,
                      "Ciudad":document.getElementById("Ciudad").value,
                      "Escuela":document.getElementById("Escuela").value,
                      "Turno":document.getElementById("Turno").value,
                      "grade":document.getElementById("grade").value,
                      "created_at":created_at,
                      "name":name,
                      "place_type":place_type,
                      "server_hostname":server_hostname,
                      "ancestor":ancestor
                  }

      //make a json of the postdata
      var dataString = JSON.stringify(postData);

      //make an ajax request to the php server to add a place to the database
      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../ajax/addplace/",
              success: function(data){
                  $("#alert").html(data);
                  if($("#alert").html() == 'place added'){
                    var table = document.getElementById("table");
                    var row = table.insertRow(1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
                      var cell3 = row.insertCell(2);
                      var cell4 = row.insertCell(3);
                      var cell5 = row.insertCell(4);
                      cell3.innerHTML = server_hostname;
                    }
                    else{
                      var cell3 = row.insertCell(2);
                      var cell4 = row.insertCell(3);
                    }
                    cell2.innerHTML = name;

                    //get the id of the new added place to create the buttons
                    $.ajax({
                          method: "POST",
                          data: {action:dataString},
                          url: "../../ajax/getidofplace/",
                          success: function(data){
                              var data2 = data
                              cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                              
                              if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
                                cell5.innerHTML = '<a class="button DeleteLaptop" onclick="Deleteplace(this)" id="Deleteperson" data="'+data2+'" role="button">delete</a>';
                                cell4.innerHTML = '<a class="button EditLaptop" onclick="Editplace(this)"  id="EditLaptop" data="'+data2+'"  role="button">Edit</a>';
                              }
                              else{
                                cell3.innerHTML = '<a class="button DeleteLaptop" onclick="Deleteplace(this)" id="Deleteperson" data="'+data2+'" role="button">delete</a>';
                                cell4.innerHTML = '<a class="button EditLaptop" onclick="Editplace(this)"  id="EditLaptop" data="'+data2+'"  role="button">Edit</a>';
                              }
                              
                          },
                          error: function(e){
                          }
                      });
                    }
              },
              error: function(e){
                  $("#alert").html(e);
              }
      });
      if($("#alert").html() != 'place added'){
        $("#alert").css("display", "initial");
        
      }

    }

    //edit a laptop
    else if(document.getElementById("AddPlace").text == 'Edit'){
      var Id = $('#AddPlace').attr("data");
      var postData = 
                {
                    "id":Id,
                    "name":name,
                    "server_hostname":server_hostname
                }

      var dataString = JSON.stringify(postData);

      //make an ajax request to the php server to edit a place in the database
      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../ajax/editplace/",
              success: function(data){
                  $("#alert").html(data);
                  var index = $('#AddPlace').attr("index");
                  var table = document.getElementById("table");
                  table.rows[index].cells[1].innerHTML = name;
                  if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
                    table.rows[index].cells[2].innerHTML = server_hostname;
                  }
                  
              },
              error: function(e){
                  $("#alert").html(e);
              }
      });
      if($("#alert").html() != 'Person edited'){
        $("#alert").css("display", "initial");        
      }
    }
    else{
      $("#alert").css("display", "none");
      $("#openModal").css("opacity", "0");
      $("#openModal").css("pointer-events", "none");
    }
  }
});

//delete a place
function Deleteplace(datainput){
   var ID = $(datainput).attr("data");
  //get the index of the deleted row
  var index = $(datainput).closest("tr").index();

  //set the data for an ajax request
  var postData = 
          {
              "id":ID
          }

  //Make a json from the data 
  var dataString = JSON.stringify(postData);

  //make an ajax request to the php server to delete a place to the database
  $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../ajax/deleteplace/",
          success: function(data){
              $("#alert").html(data);
              var index = $(datainput).closest("tr").index();
              console.log(data);
              document.getElementById("table").deleteRow(index); 
          },
          error: function(e){
              $("#alert").html(e);
          }
  });
}
  
// when the function edotplace is been called, set the form with the proper data to edit.
function Editplace(datainput){
  $("#alert").css("display", "none");
  document.getElementById("AddPlace").text = 'Edit';
  var table = document.getElementById("table");
  var element = document.getElementById($(datainput).attr("data"));
  
  var index = $(element).closest("tr").index();
  var $ID = $(datainput).attr("data");
  var $Name = table.rows[index].cells[1].innerHTML;
  var $server_hostname = table.rows[index].cells[2].innerHTML;
    
  document.getElementById("AddPlace").setAttribute("data", $ID);
  document.getElementById("AddPlace").setAttribute("index", index);
  document.getElementById("Name").value = $Name;
  if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
    document.getElementById("server_hostname").disabled=false;
    document.getElementById("server_hostname").value = $server_hostname;
  }
  else{
    document.getElementById("server_hostname").disabled=true;
  }  
  $("#openModal").css("opacity", "1");
  $("#openModal").css("pointer-events", "auto");
}

// Pass the checkbox name to the function
function getCheckedBoxes(chkboxName) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkboxesChecked = [];
  // loop over them all
  for (var i=0; i<checkboxes.length; i++) {
     // And stick the checked ones onto an array...
     if (checkboxes[i].checked) {
        checkboxesChecked.push(checkboxes[i]);
     }
  }
  // Return the array if it is non-empty, or null
  return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}

//clear the datalist where the id = parent_id
function clearChildren( parent_id ) {
    var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren( parent_id );
    }
}

function setDataInTable(){
  //set the data for an ajax request to obtain the correct data for the table
  var postData = 
        {
            "Departamento": document.getElementById("Departamento").value,
            "Ciudad": document.getElementById("Ciudad").value,
            "Escuela": document.getElementById("Escuela").value,
            "Turno": document.getElementById("Turno").value,
            "grade": document.getElementById("grade").value,
            "Seccion": document.getElementById("Seccion").value
        }
  var dataString = JSON.stringify(postData);
  $.ajax({
    method: "POST",
    data: {action:dataString},
    url: "../../ajax/getdataforplacestable/",
    success: function(data){
      console.log(data);
      var jsonOptions = JSON.parse(data);
      var table = document.getElementById("table");
      while(table.rows.length > 1) {
        table.deleteRow(1);
      }
      console.log(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value=='');
      
      
      // Loop over the JSON array.
      for($index = 0;$index < jsonOptions.length; $index++){
        var row = table.insertRow(1);
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var cell3 = row.insertCell(3);
        var cell4 = row.insertCell(4);
        cell0.innerHTML = '<input type="checkbox" id="'+jsonOptions[$index]['id']+'" name="checkbox"> '
        cell1.innerHTML = jsonOptions[$index]['name'];
        cell2.innerHTML = jsonOptions[$index]['server_hostname'];
        cell3.innerHTML = '<a class="button EditLaptop" onclick="Editplace(this)"  id="EditLaptop" data="'+jsonOptions[$index]['id']+'"  role="button">Edit</a>';
        cell4.innerHTML = '<a class="button DeleteLaptop" onclick="Deleteplace(this)" id="Deleteperson" data="'+jsonOptions[$index]['id']+'" role="button">delete</a>'; 
      }

      if(document.getElementById("Ciudad").value!='' && document.getElementById("Escuela").value==''){
        $('th, td', 'tr').filter(':nth-child(3)').show();
      }
      else{
        $('th, td', 'tr').filter(':nth-child(3)').hide();
      }
    },
    error: function(e){
      console.log(e);
      $("#alert").html(e);
    }
  }); 
}