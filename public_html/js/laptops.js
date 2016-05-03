//call the function setData to fill in the datalists.
SetData('Model','json-datalistModel');
SetData('Status','json-datalistStatus');

//when the users enter a name, search for the 5 names that are alike
$( "#People" ).keyup(function() {
  clearChildren('json-datalistPeople');
  if($('#People').val().length>0){
    GetUsersdata($('#People').val(),'json-datalistPeople');
  }
});

//when the users enter a name, search for the 5 names that are alike
$( "#assignee" ).keyup(function() {
  clearChildren('json-datalistassignee');
  if($('#assignee').val().length>0){
    GetUsersdata($('#assignee').val(),'json-datalistassignee');
  }
});

//clear all the itemes in the datalist
function clearChildren( parent_id ) {
    var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren( parent_id );
    }
}

//get the names of the users that are like 'input' and put them in the datalist 'datalist'
function GetUsersdata(input, datalist) {
  var postData = 
    {
      "name": input
    }
  var dataString = JSON.stringify(postData);
  $.ajax({
    method: "POST",
    data: {action:dataString},
    url: "../Ajax/getusersdata/",
    success: function(data){
      console.log(data);
      var dataList = document.getElementById(datalist);
      var input = document.getElementById(input);
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
//set the data of a datalist
//input = ID of textbox
//dataliist = id of datalist
//returns: fills up the datalist with the requested data
function SetData(input, datalist){

  //set input to lowercase
  var value = input.toLowerCase();

  var placeholder = input.toLowerCase();

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
        input.placeholder = placeholder+"...";
      } else {

        // An error occured :(
        input.placeholder = "No se pudo cargar la lista de opciones :(";
      }
    }
  };

  // Update the placeholder text.
  input.placeholder = "Cargando opciones...";

  //workaround for assignee, he needs the same data as people.
  if(value == 'assignee'){
    value = 'people';
  }
  // Set up and make the request.
  request.open('GET', '../Ajax/'+value+'/', true);
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

//clear all the fields when the add model is opend
$( "#openAddModal" ).click(function() {
  document.getElementById("Serial").value = '';
  document.getElementById("assignee").value = '';
  document.getElementById("Model").value = '';
  document.getElementById("People").value = '';
  document.getElementById("Status").value = '';
  document.getElementById("Uuid").value = '';
  document.getElementById("AddLaptop").text = 'Add';
  document.getElementById("Serial").disabled=false;
  document.getElementById("Uuid").disabled=false;

  //hidde the error
  $("#alert").css("display", "none");

  //show the form
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

//close the form
$( "#CloseAddModal" ).click(function() {

    //hide the form
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

//hide the form when escpase is pressed
$(document).keyup(function(e) {
      // escape key maps to keycode `27`
     if (e.keyCode == 27) { 

        //hide the form
        $("#openModal").css("opacity", "0");
        $("#openModal").css("pointer-events", "none");
    }
});

//edit 1 laptop
$( ".EditLaptop" ).click(function() {
  editlaptop(this);
});

//delete 1 laptop
$( ".DeleteLaptop" ).click(function() {
  deletelaptop(this);
});

//edit multiple laptops
$( "#EditSelectedLaptops" ).click(function() {

  // get all the selected items to edit
  var checkedBoxes = getCheckedBoxes("checkbox");

  //create the variable id;
  var id ='';

  // put all the id's from the selected items into the variable id
  for (box in checkedBoxes) {
    id = id +  checkedBoxes[box].id + ', ' ;
  }

  //if none is selected => do nothing
  //else get the form in the proper form, disable some fields and clear the text
  if(id !=''){
    $("#alert").css("display", "none");
    document.getElementById("AddLaptop").text = 'Edit';
    document.getElementById("AddLaptop").setAttribute("data", id);
    document.getElementById("Serial").disabled=true;
    document.getElementById("Uuid").disabled=true;
    document.getElementById("Serial").value='disabled';
    document.getElementById("Uuid").value='disabled';
    $("#openModal").css("opacity", "1");
    $("#openModal").css("pointer-events", "auto");
  }
});

//get all the selected items and delete them
$( "#DeleteSelectedLaptops" ).click(function() {

  //get all the selected items
  var checkedBoxes = getCheckedBoxes("checkbox");

  //loop over the selected items and delete them
  for (box in checkedBoxes) {
    deletelaptop(checkedBoxes[box]);
  }
});

//when the addlaptop function is pressed, add the items from the form into the database
//by using json requests
$( "#AddLaptop" ).click(function() {

  //get the value's of the field
  var Serial = document.getElementById("Serial").value;
  var Model = document.getElementById("Model").value;
  var People = document.getElementById("People").value;
  var Status = document.getElementById("Status").value;
  var Uuid = document.getElementById("Uuid").value;
  var assignee = document.getElementById("assignee").value;

  //check if all the fields are used
  if(!Serial || 0 === Serial.length ||!Model || 0 === Model.length || Model === parseInt(Model, 10) || !assignee || 0 === assignee.length || assignee === parseInt(assignee, 10)  || !People || 0 === People.length || People === parseInt(People, 10) ||!Status || 0 === Status.length || Status === parseInt(Status, 10) ||!Uuid || 0 === Uuid.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Rellene todos los campos!");
  }

  //check length of serial
  else if(Serial.length != 11){
    $("#alert").html("Formato de serial incorrecto!");
    $("#alert").css("display", "initial");
  }

  //check length of uuid
  else if(Uuid.length != 36){
    $("#alert").html("Formato de UUID incorrecto!");
    $("#alert").css("display", "initial");
  }

  //add laptop
  else{

    //create the variable date and set it
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 

    //create the date
    today = yyyy+'/'+mm+'/'+dd;

    //add 1 laptop
    if(document.getElementById("AddLaptop").text == 'Add'){

      //set all the data into an array named postdata
      var postData = 
                  {
                      "serial_number":Serial,
                      "created_at":today,
                      "model_id":Model,
                      "owner_id":People,
                      "status_id":Status,
                      "uuid":Uuid,
                      "assignee_id":assignee,
                      "registered":0,
                      "last_activation_date":'NULL'
                  }

      //make a json of the postdata
      var dataString = JSON.stringify(postData);

      //make an ajax request to the php server where you add a laptop to the database
      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../Ajax/addlaptop/",
              success: function(data){
                  $("#alert").html(data);
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
                  cell2.innerHTML = Serial;
                  cell3.innerHTML = People;
                  cell10.innerHTML = Model;
                  cell11.innerHTML = Status;
                  cell12.innerHTML = Uuid;
                  cell13.innerHTML = ((assignee == People)?'yes':'no');

                  //if succes get the id of the newest added laptop to make the buttons work
                  $.ajax({
                        method: "POST",
                        data: {action:dataString},
                        url: "../../Ajax/getidoflaptop/",
                        success: function(data){
                            var data2 = data
                            cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                            cell14.innerHTML = '<a class="button EditLaptop" onclick="editlaptop(this)"  id="EditLaptop" data="'+data2+'"  role="button">Editar</a>';
                            //hide the form
                             $("#openModal").css("opacity", "0");
                             $("#openModal").css("pointer-events", "none");
                        },
                        error: function(e){
                        }
                  });
              },
              error: function(e){
                  $("#alert").html(e);
              }
      });
  
      //if laptop is not added, show the form with the error.
      if($("#alert").html() != 'laptop added'){
        $("#alert").css("display", "initial");    
      }

    }

    //if the user eddited a laptop change the data in the database
    else if(document.getElementById("AddLaptop").text == 'Edit'){

      //get all the ids
      var Ids = $('#AddLaptop').attr("data");
      
      //edit multiple laptops
      if(Ids.indexOf(',') > -1){
        var res = Ids.split(", ");
        res.pop();
        var teller = 0;
        for (Id in res) {

          //create the array postdata with all the variables from the form
          var postData = 
                {
                    "id": res[Id],
                    "serial_number":Serial,
                    "model_id":Model,
                    "owner_id":People,
                    "status_id":Status,
                    "uuid":Uuid,
                    "assignee_id":assignee
                }

          //make a json blob of the array postdata
          var dataString = JSON.stringify(postData);

          //make a json request to edit multiple laptops.
          $.ajax({
                  method: "POST",
                  data: {action:dataString},
                  url: "../../Ajax/editlaptop/",
                  success: function(data){
                      $("#alert").html(data); 
                      var index = $("#"+res[teller]).closest("tr").index();
                      console.log('res3'+res[teller]);
                      var table = document.getElementById("table");
                      table.rows[index].cells[2].innerHTML = People;
                      table.rows[index].cells[9].innerHTML = Model;
                      table.rows[index].cells[10].innerHTML = Status;
                      table.rows[index].cells[12].innerHTML = ((assignee==People)?'Si':'No');
                      teller++;

                      //hide the form
                      $("#openModal").css("opacity", "0");
                      $("#openModal").css("pointer-events", "none");
                  },
                  error: function(e){
                      $("#alert").html(e);
                  }
          });
          if($("#alert").html() != 'Laptops editadas'){
            $("#alert").css("display", "initial");
            
          }
          
        }
      }

      //edit 1 laptop
      else{
        var ID = $('#AddLaptop').attr("data");
        var index = $('#AddLaptop').attr("index");

        //create the array postdata with all the variables from the form
        var postData = 
                    {
                        "id": ID,
                        "serial_number":Serial,
                        "model_id":Model,
                        "owner_id":People,
                        "status_id":Status,
                        "uuid":Uuid,
                        "assignee_id":assignee
                    }

        //make a json blob of the array postdata
        var dataString = JSON.stringify(postData);

        //make a json request to edit 1 laptop.
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../../Ajax/editlaptop/",
                success: function(data){
                    $("#alert").html(data);
                    console.log(data);
                    var table = document.getElementById("table");
                    table.rows[index].cells[1].innerHTML = Serial;
                    table.rows[index].cells[2].innerHTML = People;
                    table.rows[index].cells[9].innerHTML = Model;
                    table.rows[index].cells[10].innerHTML = Status;
                    table.rows[index].cells[11].innerHTML = Uuid;
                    table.rows[index].cells[12].innerHTML = ((assignee==People)?'yes':'no');
                    //hide the form
                    $("#openModal").css("opacity", "0");
                    $("#openModal").css("pointer-events", "none");
                },
                error: function(e){
                    $("#alert").html(e);
                    console.log(e);
                }
        });
        if($("#alert").html() != 'Laptop editada'){
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

//delete 1 laptop 
//not in use
function deletelaptop(datainput){
  var id = $(datainput).closest("tr")   // Finds the closest row <tr> 
                       .find(".EditLaptop")     // Gets a descendent with class="nr"
                       .attr("data");
  
  var index = $(datainput).closest("tr").index();
  console.log(id);
  console.log(index);
  var postData = 
          {
              "id":id
          }

  var dataString = JSON.stringify(postData);

  $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../Ajax/deletelaptop/",
          success: function(data){
              $("#alert").html(data);
              var index = $(datainput).closest("tr").index();
              document.getElementById("table").deleteRow(index); 
          },
          error: function(e){
              $("#alert").html(e);
          }
  });
}

//edit 1 laptop, change the html of the ducument, by filling in the form
function editlaptop(datainput){
  document.getElementById("Serial").disabled=true;
  document.getElementById("Uuid").disabled=true;
  $("#alert").css("display", "none");
  document.getElementById("AddLaptop").text = 'Edit';
  var element = document.getElementById($(datainput).attr("data"));
  var index = $(element).closest("tr").index();
  var $ID = $(datainput).attr("data");
  var $serial = table.rows[index].cells[1].innerHTML;
  var $name = table.rows[index].cells[2].innerHTML;
  var $model = table.rows[index].cells[9].innerHTML;
  var $status = table.rows[index].cells[10].innerHTML;
  var $uuid = table.rows[index].cells[11].innerHTML;
  document.getElementById("AddLaptop").setAttribute("data", $ID);
  document.getElementById("AddLaptop").setAttribute("index", index);
  document.getElementById("Serial").value = $serial;
  document.getElementById("Model").value = $model;
  document.getElementById("People").value = $name;
  document.getElementById("Status").value = $status;
  document.getElementById("Uuid").value = $uuid;
  var assignee_id = table.rows[index].cells[12].getAttribute("data");
  var postData = 
          {
              "assignee_id":assignee_id
          }

  var dataString = JSON.stringify(postData);

  //do an ajax request to get the name of the assignee to fill in the form
  $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../Ajax/getuserbyid/",
          success: function(data){
            console.log(data);
             document.getElementById("assignee").value = data;
          },
          error: function(e){
              $("#alert").html(e);
          }
  });
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