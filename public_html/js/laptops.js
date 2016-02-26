
SetData('Model','json-datalistModel');
SetData('People','json-datalistPeople');
SetData('Status','json-datalistStatus');

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

$( "#chechallboxes" ).click(function() {
  checkboxes = document.getElementsByName('checkbox');

  if (document.getElementById("chechallboxes").checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }

});


$( "#openAddModal" ).click(function() {
  document.getElementById("Serial").value = '';
  document.getElementById("Model").value = '';
  document.getElementById("People").value = '';
  document.getElementById("Status").value = '';
  document.getElementById("Uuid").value = '';
  document.getElementById("AddLaptop").text = 'Add';
  document.getElementById("Serial").disabled=false;
    document.getElementById("Uuid").disabled=false;
  $("#alert").css("display", "none");
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

$( ".EditLaptop" ).click(function() {
  editlaptop(this);
});

$( ".DeleteLaptop" ).click(function() {
  deletelaptop(this);
});

$( "#ImportButton" ).click(function() {
  var fileinput = document.getElementById("uploadform_file")
  fileinput.click();
});



$( "#EditSelectedLaptops" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  console.log(checkedBoxes);
  var id ='';
  for (box in checkedBoxes) {
    id = id +  checkedBoxes[box].id + ', ' ;
  }
  console.log(id);
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

$( "#DeleteSelectedLaptops" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  console.log(checkedBoxes);
  for (box in checkedBoxes) {
    deletelaptop(checkedBoxes[box]);
  }
});






$( "#AddLaptop" ).click(function() {
  var Serial = document.getElementById("Serial").value;
  var Model = document.getElementById("Model").value;
  var People = document.getElementById("People").value;
  var Status = document.getElementById("Status").value;
  var Uuid = document.getElementById("Uuid").value;

  if(!Serial || 0 === Serial.length ||!Model || 0 === Model.length || Model === parseInt(Model, 10) || !People || 0 === People.length || People === parseInt(People, 10) ||!Status || 0 === Status.length || Status === parseInt(Status, 10) ||!Uuid || 0 === Uuid.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in all fields!");
  }
  else{
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

    today = yyyy+'/'+mm+'/'+dd;

    //add 1 laptop
    if(document.getElementById("AddLaptop").text == 'Add'){
      var postData = 
                  {
                      "serial_number":Serial,
                      "created_at":today,
                      "model_id":Model,
                      "owner_id":People,
                      "status_id":Status,
                      "uuid":Uuid,
                      "registered":0,
                      "last_activation_date":'NULL'
                  }

      var dataString = JSON.stringify(postData);

      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../ajax/addlaptop/",
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
                  cell2.innerHTML = Serial;
                  cell3.innerHTML = People;
                  cell5.innerHTML = Model;
                  cell6.innerHTML = Status;
                  cell7.innerHTML = Uuid;
                  $.ajax({
                        method: "POST",
                        data: {action:dataString},
                        url: "../ajax/getidoflaptop/",
                        success: function(data){
                            var data2 = data
                            cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                            cell8.innerHTML = '<a class="button EditLaptop" onclick="editlaptop(this)"  id="EditLaptop" data="'+data2+'"  role="button">Edit</a>';
                            cell9.innerHTML = '<a class="button DeleteLaptop" onclick="deletelaptop(this)" id="DeleteLaptop" data="'+data2+'" role="button">delete</a>';
                        },
                        error: function(e){
                        }
                  });
              },
              error: function(e){
                  $("#alert").html(e);
              }
      });
      if($("#alert").html() != 'laptop added'){
        $("#alert").css("display", "initial");
        
      }

    }
    else if(document.getElementById("AddLaptop").text == 'Edit'){
      var Ids = $('#AddLaptop').attr("data");

      //edit multiple laptops
      if(Ids.indexOf(',') > -1){
        var res = Ids.split(", ");
        res.pop();
        var teller = 0;
        for (Id in res) {
          var postData = 
                {
                    "id": res[Id],
                    "serial_number":Serial,
                    "model_id":Model,
                    "owner_id":People,
                    "status_id":Status,
                    "uuid":Uuid,
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
                  method: "POST",
                  data: {action:dataString},
                  url: "../ajax/editlaptop/",
                  success: function(data){
                      $("#alert").html(data); 
                      var index = $("#"+res[teller]).closest("tr").index();
                      console.log('res3'+res[teller]);
                      var table = document.getElementById("table");
                      table.rows[index].cells[2].innerHTML = People;
                      table.rows[index].cells[4].innerHTML = Model;
                      table.rows[index].cells[5].innerHTML = Status;
                      teller++;
                  },
                  error: function(e){
                      $("#alert").html(e);
                  }
          });
          if($("#alert").html() != 'laptops edited'){
            $("#alert").css("display", "initial");
            
          }
          
        }
      }

      //edit 1 laptop
      else{
        var index = $('#AddLaptop').attr("index");
        var postData = 
                    {
                        "id": ID,
                        "serial_number":Serial,
                        "model_id":Model,
                        "owner_id":People,
                        "status_id":Status,
                        "uuid":Uuid,
                    }

        var dataString = JSON.stringify(postData);
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../ajax/editlaptop/",
                success: function(data){
                    $("#alert").html(data);
                    var table = document.getElementById("table");
                    table.rows[index].cells[1].innerHTML = Serial;
                    table.rows[index].cells[2].innerHTML = People;
                    table.rows[index].cells[4].innerHTML = Model;
                    table.rows[index].cells[5].innerHTML = Status;
                    table.rows[index].cells[6].innerHTML = Uuid;
                },
                error: function(e){
                    $("#alert").html(e);
                }
        });
        if($("#alert").html() != 'laptop edited'){
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
          url: "../ajax/deletelaptop/",
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
  var $model = table.rows[index].cells[4].innerHTML;
  var $status = table.rows[index].cells[5].innerHTML;
  var $uuid = table.rows[index].cells[6].innerHTML;
  document.getElementById("AddLaptop").setAttribute("data", $ID);
  document.getElementById("AddLaptop").setAttribute("index", index);
  document.getElementById("Serial").value = $serial;
  document.getElementById("Model").value = $model;
  document.getElementById("People").value = $name;
  document.getElementById("Status").value = $status;
  document.getElementById("Uuid").value = $uuid;
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