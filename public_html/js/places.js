
SetData('place_type','json-datalistplace_type');
SetData('ancestor','json-datalistancestor');

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
  document.getElementById("Name").value = '';
  document.getElementById("place_type").value = '';
  document.getElementById("server_hostname").value = '';
  document.getElementById("ancestor").value = '';
  document.getElementById("AddPlace").text = 'Add';
  $("#alert").css("display", "none");
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

$( ".Editplace" ).click(function() {
  Editplace(this);
});

$( ".Deleteplace" ).click(function() {
  Deleteplace(this);
});

$( "#ImportButton" ).click(function() {
  var fileinput = document.getElementById("uploadform_file")
  fileinput.click();
});



$( "#EditSelectedplaces" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  var id ='';
  for (box in checkedBoxes) {
    id = id +  checkedBoxes[box].id + ', ' ;
  }
  if(id !=''){
    $("#alert").css("display", "none");
    document.getElementById("AddPlace").text = 'Edit';
    document.getElementById("AddPlace").setAttribute("data", id);
    document.getElementById("Name").disabled=true;
    document.getElementById("server_hostname").disabled=true;
    document.getElementById("ancestor").disabled=true;
    document.getElementById("Name").value='disabled';
    document.getElementById("server_hostname").value='disabled';
    document.getElementById("ancestor").value='disabled';
    $("#openModal").css("opacity", "1");
    $("#openModal").css("pointer-events", "auto");
  }
});

$( "#DeleteSelectedplaces" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  for (box in checkedBoxes) {
    Deleteplace(checkedBoxes[box]);
  }
});

$( "#AddPlace" ).click(function() {
  var name = document.getElementById("Name").value;
  var place_type = document.getElementById("place_type").value;
  var server_hostname = document.getElementById("server_hostname").value;
  var ancestor = document.getElementById("ancestor").value;
  if(!name || 0 === name.length ||
    !place_type || 0 === place_type.length || 
    !ancestor || 0 === ancestor.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in the fields: name, server-hostname and ancestor!");
  }
  else{
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
      var postData = 
                  {
                      "created_at":created_at,
                      "name":name,
                      "place_type":place_type,
                      "server_hostname":server_hostname,
                      "ancestor":ancestor
                  }

      var dataString = JSON.stringify(postData);

      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../ajax/addplace/",
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
                  cell2.innerHTML = name;
                  cell3.innerHTML = place_type;
                  cell4.innerHTML = server_hostname;
                  cell5.innerHTML = ancestor;
                  cell5.className ='status displaynone';
                  $.ajax({
                        method: "POST",
                        data: {action:dataString},
                        url: "../../ajax/getidofplace/",
                        success: function(data){
                            var data2 = data
                            cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                            cell6.innerHTML = '<a class="button EditLaptop" onclick="Editplace(this)"  id="EditLaptop" data="'+data2+'"  role="button">Edit</a>';
                            cell7.innerHTML = '<a class="button DeleteLaptop" onclick="Deleteplace(this)" id="Deleteperson" data="'+data2+'" role="button">delete</a>';
                        },
                        error: function(e){
                        }
                  });
              },
              error: function(e){
                  $("#alert").html(e);
              }
      });
      if($("#alert").html() != 'Person added'){
        $("#alert").css("display", "initial");
        
      }

    }
    else if(document.getElementById("AddPlace").text == 'Edit'){
      var Ids = $('#AddPlace').attr("data");

      //edit multiple laptops
      if(Ids.indexOf(',') > -1){
        var res = Ids.split(", ");
        res.pop();
        var teller = 0;
        for (Id in res) {
          var postData = 
                {
                    "id":res[Id],
                    "place_type":place_type
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
                  method: "POST",
                  data: {action:dataString},
                  url: "../../ajax/editplace/",
                  success: function(data){
                      $("#alert").html(data); 
                      var index = $("#"+res[teller]).closest("tr").index();
                      var table = document.getElementById("table");
                      table.rows[index].cells[2].innerHTML = place_type;
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
        
        var postData = 
                  {
                      "id":Ids,
                      "name":name,
                      "place_type":place_type,
                      "server_hostname":server_hostname,
                      "ancestor":ancestor
                  }

        var dataString = JSON.stringify(postData);
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../../ajax/editplace/",
                success: function(data){
                    $("#alert").html(data);
                    var index = $('#AddPlace').attr("index");
                    var table = document.getElementById("table");
                    table.rows[index].cells[1].innerHTML = name;
                    table.rows[index].cells[2].innerHTML = place_type;
                    table.rows[index].cells[3].innerHTML = server_hostname;
                    table.rows[index].cells[4].innerHTML = ancestor;
                },
                error: function(e){
                    $("#alert").html(e);
                }
        });
        if($("#alert").html() != 'Person edited'){
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


function Deleteplace(datainput){
  var id = $(datainput).closest("tr")   // Finds the closest row <tr> 
                       .find(".Editplace")     // Gets a descendent with class="nr"
                       .attr("data");
  
  var index = $(datainput).closest("tr").index();
  var postData = 
          {
              "id":id
          }

  var dataString = JSON.stringify(postData);

  $.ajax({
          method: "POST",
          data: {action:dataString},
          url: "../../ajax/deleteplace/",
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

function Editplace(datainput){
  $("#alert").css("display", "none");
  document.getElementById("AddPlace").text = 'Edit';
  var table = document.getElementById("table");
  var element = document.getElementById($(datainput).attr("data"));
  
  var index = $(element).closest("tr").index();
  var $ID = $(datainput).attr("data");
  var $Name = table.rows[index].cells[1].innerHTML;
  var $place_type = table.rows[index].cells[2].innerHTML;
  var $server_hostname = table.rows[index].cells[3].innerHTML;
  var $ancestor = table.rows[index].cells[4].innerHTML;
  var datalist = document.getElementById("json-datalistancestor");
  var datalength = datalist.getElementsByTagName("option");
  for(var i=0;i<datalength.length;i++){
    if(datalength[i].text==$ancestor){
      $ancestor = datalength[i].value;
    }
  }
  
  document.getElementById("AddPlace").setAttribute("data", $ID);
  document.getElementById("AddPlace").setAttribute("index", index);
  document.getElementById("Name").value = $Name;
  document.getElementById("place_type").value = $place_type;
  document.getElementById("server_hostname").value = $server_hostname;
  document.getElementById("ancestor").value = $ancestor;
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