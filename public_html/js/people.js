//call the function setData to fill in the datalist.
SetData('profiles','json-datalistprofiles');
SetData('grade','json-datalistgrade');
SetDepartments();

//call the function SetStaticDataTurno to fill in the datalist with static date//me not like.
SetStaticDataTurno('Turno','json-datalistTurno');
SetStaticDataSeccion('Seccion','json-datalistSeccion');



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
    url: "../../Ajax/placesstates/",
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

//set the data of the turno list
function SetStaticDataTurno(input, datalist){
  var dataList = document.getElementById(datalist);
  var input = document.getElementById(input);

          var option = document.createElement('option');
          // Set the value using the item in the JSON array.
          option.value = 'Turno Mañana';
          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);

          var option = document.createElement('option');
          // Set the value using the item in the JSON array.
          option.value = 'Turno Tarde';
          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);
}

//set the data of the seccion list
function SetStaticDataSeccion(input, datalist){
  var dataList = document.getElementById(datalist);
  var input = document.getElementById(input);

          var option = document.createElement('option');
          // Set the value using the item in the JSON array.
          option.value = 'Seccion A';
          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);

          var option = document.createElement('option');
          // Set the value using the item in the JSON array.
          option.value = 'Seccion B';
          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);

          var option = document.createElement('option');
          // Set the value using the item in the JSON array.
          option.value = 'Seccion C';
          // Add the <option> element to the <datalist>.
          dataList.appendChild(option);
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
        input.placeholder = "No se pudo cargar la lista de opciones :(";
      }
    }
  };

  // Update the placeholder text.
  input.placeholder = "Cargando opciones...";

  // Set up and make the request.
  request.open('GET', '../../Ajax/'+value+'/', true);
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

//when clicken on the add button, set all the value of the form to default
$( "#openAddModal" ).click(function() {
  document.getElementById("Name").value = '';
  document.getElementById("Lastname").value = '';
  document.getElementById("birth_date").value = '';
  document.getElementById("phone").value = '';
  document.getElementById("email").value = '';
  document.getElementById("notes").value = '';
  document.getElementById("profiles").value = '';
  document.getElementById("grade").value = '';
  document.getElementById("Turno").value = '';
  document.getElementById("Seccion").value = '';
  document.getElementById("Departamento").value = '';
  document.getElementById("Ciudad").value = '';
  document.getElementById("Escuela").value = '';
  document.getElementById("Ciudad").disabled=true;
  document.getElementById("Escuela").disabled=true;
  document.getElementById("grade").disabled=true;
  document.getElementById("Turno").disabled=true;
  document.getElementById("Seccion").disabled=true;
  document.getElementById("AddPerson").text = 'Add';
  $("#alert").css("display", "none");
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

//when an item has been selected in the departmentdatalist change the form
//select the correct city's from the database with a json request
$('#Departamento').on('input', function(){
    var options = document.getElementById("json-datalistDepartamento").options
    for (var i=0;i<options.length;i++){

      //if it changes, clear all the subjects of depertment: "citys and schools"
       if (options[i].value == $(this).val()){
          $value = this.value;
          clearChildren('json-datalistCiudad');
          $('#Ciudad').val('');
          clearChildren('json-datalistEscuela');
          $('#Escuela').val('');
          $('#Turno').val('');
          $('#grade').val('');
          $('#Seccion').val('');
          $("#Departamento").val($value);

          //set the data for an ajax request to obtain the correct data for the city list
          var postData = 
                {
                    "name": $value,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../Ajax/placescitys/",
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
          clearChildren('json-datalistEscuela');
          $('#Escuela').val('');
          $('#Turno').val('');
          $('#grade').val('');
          $('#Seccion').val('');
          $("#Ciudad").val($value);

          //set the data for an ajax request to obtain the correct data for the school list
          var postData = 
                {
                    "Ciudad": $value,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
            method: "POST",
            data: {action:dataString},
            url: "../../Ajax/placesschools/",
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
            },
            error: function(e){
              console.log(e);
              $("#alert").html(e);
            }
          }); 
       }
    }
});  

//if the school changes, call the AllowSchoolDetails() function
$('#Escuela').on('input', function(){
    var options = document.getElementById("json-datalistEscuela").options;
    for (var i=0;i<options.length;i++){
      if (options[i].value == $(this).val()){
        AllowSchoolDetails();
        $('#Turno').val('');
        $('#grade').val('');
        $('#Seccion').val('');
      }
    }
});

//if the profiles changes, call the AllowSchoolDetails() function
$('#profiles').on('input', function(){
    var options = document.getElementById("json-datalistprofiles").options;
    for (var i=0;i<options.length;i++){
      if (options[i].value == $(this).val()){
        AllowSchoolDetails();
      }
    }
});

//allow the user to select the details of the school: turno, grado, seccion
function AllowSchoolDetails(){
  $profile = document.getElementById("profiles").value;
  $school = document.getElementById("Escuela").value;
  if($profile == 'Estudiante' && $school != ''){
    document.getElementById("grade").disabled=false;
    document.getElementById("Turno").disabled=false;
    document.getElementById("Seccion").disabled=false;
    document.getElementById("grade").value = '';
    document.getElementById("Turno").value = '';
    document.getElementById("Seccion").value = '';
  }
  else{
    document.getElementById("grade").disabled=true;
    document.getElementById("Turno").disabled=true;
    document.getElementById("Seccion").disabled=true;
    document.getElementById("grade").value = '';
    document.getElementById("Turno").value = '';
    document.getElementById("Seccion").value = '';
  }
}

//close the from
$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

//edit 1 person
$( ".Editperson" ).click(function() {
  editperson(this);
});

//delete 1 person
$( ".DeletePerson" ).click(function() {
  deleteperson(this);
});

//import 1 perosn
$( "#ImportButton" ).click(function() {
  var fileinput = document.getElementById("uploadform_file")
  fileinput.click();
});

//set the form with the correct data to edit 1 perosn
$( "#EditSelectedPeople" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  console.log(checkedBoxes);
  var id ='';
  for (box in checkedBoxes) {
    id = id +  checkedBoxes[box].id + ', ' ;
  }
  if(id !=''){
    $("#alert").css("display", "none");
    document.getElementById("AddPerson").text = 'Edit';
    document.getElementById("AddPerson").setAttribute("data", id);
    document.getElementById("Name").disabled=true;
    document.getElementById("Lastname").disabled=true;
    document.getElementById("birth_date").disabled=true;
    document.getElementById("phone").disabled=true;
    document.getElementById("email").disabled=true;
    document.getElementById("notes").disabled=true;
    document.getElementById("Name").value='disabled';
    document.getElementById("Lastname").value='disabled';
    document.getElementById("birth_date").value='disabled';
    document.getElementById("phone").value='disabled';
    document.getElementById("email").value='disabled';
    document.getElementById("notes").value='disabled';
    $("#openModal").css("opacity", "1");
    $("#openModal").css("pointer-events", "auto");
  }
});

//get all the selected items and delete them
$( "#DeleteSelectedPeople" ).on('click', function(){
  $("#openModal2").css("opacity", "1");
  $("#openModal2").css("pointer-events", "auto");
});

$("#confirmDelete").on("click", function(){
  //get all the selected items
  var checkedBoxes = getCheckedBoxes("checkbox");
  //loop over the selected items and delete them
  for (box in checkedBoxes) {
    deleteperson(checkedBoxes[box]);
  }
  $("#openModal2").css("opacity", "0");
  $("#openModal2").css("pointer-events", "none");
});
$( "#CloseAddModal2" ).click(function() {
   $("#openModal2").css("opacity", "0");
   $("#openModal2").css("pointer-events", "none");
});
$( "#cancelDelete" ).click(function() {
   $("#openModal2").css("opacity", "0");
   $("#openModal2").css("pointer-events", "none");
});

//do an ajax request to add an person
$( "#AddPerson" ).click(function() {
  var name = document.getElementById("Name").value;
  var lastname = document.getElementById("Lastname").value;
  var id_document = ''
  var birth_date = document.getElementById("birth_date").value;
  var phone = document.getElementById("phone").value;
  var email = document.getElementById("email").value;
  var Departamento = document.getElementById("Departamento").value;
  var Ciudad = document.getElementById("Ciudad").value;
  var Escuela = document.getElementById("Escuela").value;
  var notes = document.getElementById("notes").value;
  var profiles = document.getElementById("profiles").value;
  var grade = document.getElementById("grade").value;
  var Turno = document.getElementById("Turno").value;
  var Seccion = document.getElementById("Seccion").value;

  //give alert if name is not fild in
  if(!name || 0 === name.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in a name!");
  }

  //give alert if lastname is not fild in
  else if(!lastname || 0 === lastname.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in a lastname!");
  }

  //give alert if department is not fild in
  else if(!Departamento || 0 === Departamento.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Choose a Departament!");
  }

  //give alert if profile is not fild in
  else if(!profiles || 0 === profiles.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Choose a profile!");
  }

  //no errors? continue with adding person
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

    //add 1 person
    if(document.getElementById("AddPerson").text == 'Add'){

      //set all the data into an array named postdata
      var postData = 
                  {
                      "created_at":created_at,
                      "name":name,
                      "lastname":lastname,
                      "id_document":id_document,
                      "birth_date":birth_date,
                      "phone":phone,
                      "email":email,
                      "position":'NULL',
                      "id_document_created_at":'NULL',
                      "notes":notes,
                      "Departamento":Departamento,
                      "Ciudad":Ciudad,
                      "Escuela":Escuela,
                      "profiles":profiles,
                      "grade":grade,
                      "Turno":Turno,
                      "Seccion":Seccion
                  }

      //make a json of the postdata
      var dataString = JSON.stringify(postData);

      //make an ajax request to the php server where you add a person to the database
      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../Ajax/addperson/",
              success: function(data){
                  $("#alert").html(data);
                  console.log(data);
                    if(data == 'Estudiante agregan' || data == 'Persona agregada'){
                    var table = document.getElementById("table");
                    var row = table.insertRow(1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    var cell5 = row.insertCell(4);
                    var cell6 = row.insertCell(5);
                    var cell7 = row.insertCell(6);
                    var cell8 = row.insertCell(7);
                    var cell9 = row.insertCell(8);
                    var cell10 = row.insertCell(9);
                    var cell11 = row.insertCell(10);
                    var cell12 = row.insertCell(11);
                    var cell13 = row.insertCell(12);
                    var cell14 = row.insertCell(13);
                    cell2.innerHTML = name;
                    cell3.innerHTML = lastname;
                    cell4.innerHTML = phone;
                    cell5.innerHTML = email;
                    cell6.innerHTML = Departamento;
                    cell7.innerHTML = Ciudad;
                    cell8.innerHTML = Escuela;
                    cell9.innerHTML = Turno;
                    cell10.innerHTML = grade;
                    cell11.innerHTML = Seccion;
                    cell12.innerHTML = profiles;
                    cell4.setAttribute("class", 'displaynone');
                    cell5.setAttribute("class", 'displaynone');

                    

                    //if succes get the id of the newest added person to make the buttons work
                    $.ajax({
                          method: "POST",
                          data: {action:dataString},
                          url: "../../Ajax/getidofperson/",
                          success: function(data){                          
                              console.log(data);
                              var data2 = data
                              cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                              cell13.innerHTML = '<a class="button EditLaptop" onclick="editperson(this)"  id="EditLaptop" data="'+data2+'"  role="button">Editar</a>';
                              cell14.innerHTML = '<a class="button DeleteLaptop" onclick="deleteperson(this)" id="Deleteperson" data="'+data2+'" role="button">Eliminar</a>';
                              //hide the form
                             $("#openModal").css("opacity", "0");
                             $("#openModal").css("pointer-events", "none");
                          },
                          error: function(e){
                            console.log(e);
                          }
                    });
                  }
              },
              error: function(e){
                  $("#alert").html(e);
                  console.log(e);
              }
      });

      //if laptop is not added, show the form with the error.
      if($("#alert").html() != 'Persona agregada!'){
        $("#alert").css("display", "initial");
        
      }

    }

    //if the user eddited a person change the data in the database
    else if(document.getElementById("AddPerson").text == 'Edit'){

      //get all the ids
      var Ids = $('#AddPerson').attr("data");

      //edit multiple persons
      if(Ids.indexOf(',') > -1){
        var res = Ids.split(", ");
        res.pop();
        var teller = 0;
        for (Id in res) {

          //create the array postdata with all the variables from the form
          var postData = 
                {
                    "id": res[Id],
                    "Departamento":Departamento,
                    "Ciudad":Ciudad,
                    "Escuela":Escuela,
                    "profiles":profiles,
                    "grade":grade,
                    "Turno":Turno,
                    "Seccion":Seccion

                }

          //make a json blob of the array postdata
          var dataString = JSON.stringify(postData);

          //make a json request to edit multiple persons.
          $.ajax({
                  method: "POST",
                  data: {action:dataString},
                  url: "../../Ajax/editperson/",
                  success: function(data){
                      $("#alert").html(data); 
                      var index = $("#"+res[teller]).closest("tr").index();
                      var table = document.getElementById("table");
                      table.rows[index].cells[5].innerHTML = Departamento;
                      table.rows[index].cells[7].innerHTML = Escuela;
                      table.rows[index].cells[11].innerHTML = profiles;
                      teller++;
                      console.log(data);

                      //hide the form
                      $("#openModal").css("opacity", "0");
                      $("#openModal").css("pointer-events", "none");
                  },
                  error: function(e){
                      $("#alert").html(e);
                      console.log(e);
                  }
          });
          if($("#alert").html() != 'Laptops editadas'){
            $("#alert").css("display", "initial");
            
          }
        }
      }

      //edit 1 person
      else{
        var index = $('#AddPerson').attr("index");

        //create the array postdata with all the variables from the form
        var postData = 
                  {
                      "id":Ids,
                      "created_at":created_at,
                      "name":name,
                      "lastname":lastname,
                      "id_document":'',
                      "birth_date":birth_date,
                      "phone":phone,
                      "email":email,
                      "position":'NULL',
                      "id_document_created_at":'NULL',
                      "notes":notes,
                      "Departamento":Departamento,
                      "Ciudad":Ciudad,
                      "Escuela":Escuela,
                      "profiles":profiles,
                      "grade":grade,
                      "Turno":Turno,
                      "Seccion":Seccion
                  }

        //make a json blob of the array postdata
        var dataString = JSON.stringify(postData);

        //make a json request to edit 1 person.
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../../Ajax/editperson/",
                success: function(data){
                    $("#alert").html(data);
                    var table = document.getElementById("table");
                    table.rows[index].cells[1].innerHTML = name;
                    table.rows[index].cells[2].innerHTML = lastname;
                    table.rows[index].cells[3].innerHTML = phone;
                    table.rows[index].cells[4].innerHTML = email;
                    table.rows[index].cells[3].setAttribute("class", 'displaynone');
                    table.rows[index].cells[4].setAttribute("class", 'displaynone');
                    table.rows[index].cells[5].innerHTML = Departamento;
                    table.rows[index].cells[6].innerHTML = Ciudad;
                    table.rows[index].cells[7].innerHTML = Escuela;
                    table.rows[index].cells[8].innerHTML = Turno;
                    table.rows[index].cells[9].innerHTML = grade;
                    table.rows[index].cells[10].innerHTML = Seccion;
                    table.rows[index].cells[11].innerHTML = profiles;
                    console.log(data);

                    //hide the form
                    $("#openModal").css("opacity", "0");
                    $("#openModal").css("pointer-events", "none");
                },
                error: function(e){
                    $("#alert").html(e);
                    console.log(e);
                }
        });
        if($("#alert").html() != 'Person editadar'){
          $("#alert").css("display", "initial");
          
        }
      }
    }
    else{
      $("#alert").css("display", "none");
      $("#openModal").css("opacity", "0");
      $("#openModal").css("pointer-events", "none");
    }
  }
});

//delete 1 persen and assign the laptop back to FZT 
function deleteperson(datainput){
  var id = $(datainput).closest("tr")   // Finds the closest row <tr> 
                       .find(".Editperson")     // Gets a descendent with class="nr"
                       .attr("data");
  
  //get the id of the selected row you want to delete
  var index = $(datainput).closest("tr").index();
  console.log(id);
  console.log(index);

  //create the array postdata with all the variables from the form
  var postData = 
          {
              "id":id
          }

  //make a json blob of the array postdata
  var dataString = JSON.stringify(postData);

  //make a json request to delete 1 person.
  $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../Ajax/deleteperson/",
          success: function(data){
              $("#alert").html(data);
              console.log(data);
              var index = $(datainput).closest("tr").index();
              document.getElementById("table").deleteRow(index); 
          },
          error: function(e){
              $("#alert").html(e);
              console.log(e);
          }
  });
}

//edit 1 person, change the html of the ducument, by filling in the form
function editperson(datainput){
  $("#alert").css("display", "none");
  document.getElementById("AddPerson").text = 'Edit';
  var element = document.getElementById($(datainput).attr("data"));
  var index = $(element).closest("tr").index();
  var $ID = $(datainput).attr("data");
  var $Name = table.rows[index].cells[1].innerHTML;
  var $Lastname = table.rows[index].cells[2].innerHTML;
  var $phone = table.rows[index].cells[3].innerHTML;
  var $email = table.rows[index].cells[4].innerHTML;
  var $region = table.rows[index].cells[5].innerHTML;
  var $Ciudad = table.rows[index].cells[6].innerHTML;
  var $Schoolname = table.rows[index].cells[7].innerHTML;
  var $Turno = table.rows[index].cells[8].innerHTML;
  var $Seccion = table.rows[index].cells[9].innerHTML;
  var $grade = table.rows[index].cells[10].innerHTML;
  var $profdescription = table.rows[index].cells[11].innerHTML;
  var $DocumentID = table.rows[index].cells[12].innerHTML;
  var $birth_date = table.rows[index].cells[13].innerHTML;
  var $position = table.rows[index].cells[14].innerHTML;
  var $notes = table.rows[index].cells[16].innerHTML;
  var $typedescription = table.rows[index].cells[17].innerHTML;
  if($profdescription !='Estudiante'){
    document.getElementById("grade").disabled=true;
    document.getElementById("Turno").disabled=true;
    document.getElementById("Seccion").disabled=true;
  }
  
  document.getElementById("AddPerson").setAttribute("data", $ID);
  document.getElementById("AddPerson").setAttribute("index", index);
  document.getElementById("Name").value = $Name;
  document.getElementById("Lastname").value = $Lastname;
  document.getElementById("birth_date").value = $birth_date;
  document.getElementById("phone").value = $phone;
  document.getElementById("email").value = $email;
  document.getElementById("notes").value = $notes;
  document.getElementById("profiles").value = $profdescription;
  document.getElementById("grade").value = $grade;
  document.getElementById("Turno").value = $Turno;
  document.getElementById("Seccion").value = $Seccion;
  document.getElementById("Escuela").value = $Schoolname;
  document.getElementById("Departamento").value = $region;
  document.getElementById("Ciudad").value = $Ciudad;
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