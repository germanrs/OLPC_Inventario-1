
SetData('places','json-datalistplaces');
SetData('profiles','json-datalistprofiles');
SetData('grade','json-datalistgrade');

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
  document.getElementById("Lastname").value = '';
  document.getElementById("birth_date").value = '';
  document.getElementById("phone").value = '';
  document.getElementById("email").value = '';
  document.getElementById("places").value = '';
  document.getElementById("notes").value = '';
  document.getElementById("profiles").value = '';
  document.getElementById("barcode").value = '';
  document.getElementById("grade").value = '';
  document.getElementById("AddPerson").text = 'Add';
  $("#alert").css("display", "none");
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});

$( ".Editperson" ).click(function() {
  editperson(this);
});

$( ".DeletePerson" ).click(function() {
  deleteperson(this);
});

$( "#ImportButton" ).click(function() {
  var fileinput = document.getElementById("uploadform_file")
  fileinput.click();
});



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
    document.getElementById("barcode").disabled=true;
    document.getElementById("notes").disabled=true;
    document.getElementById("Name").value='disabled';
    document.getElementById("Lastname").value='disabled';
    document.getElementById("birth_date").value='disabled';
    document.getElementById("phone").value='disabled';
    document.getElementById("email").value='disabled';
    document.getElementById("barcode").value='disabled';
    document.getElementById("notes").value='disabled';
    $("#openModal").css("opacity", "1");
    $("#openModal").css("pointer-events", "auto");
  }
});

$( "#DeleteSelectedPeople" ).click(function() {
  var checkedBoxes = getCheckedBoxes("checkbox");
  console.log(checkedBoxes);
  for (box in checkedBoxes) {
    deleteperson(checkedBoxes[box]);
  }
});

$( "#AddPerson" ).click(function() {
  var name = document.getElementById("Name").value;
  var lastname = document.getElementById("Lastname").value;
  var id_document = ''
  var birth_date = document.getElementById("birth_date").value;
  var phone = document.getElementById("phone").value;
  var email = document.getElementById("email").value;
  var places = document.getElementById("places").value;
  var notes = document.getElementById("notes").value;
  var barcode = document.getElementById("barcode").value;
  var profiles = document.getElementById("profiles").value;
  var grade = document.getElementById("grade").value;
  if(!name || 0 === name.length ||
    !lastname || 0 === lastname.length || 
    !places || 0 === places.length || 
    !grade || 0 === grade.length || 
    !barcode || 0 === barcode.length || barcode === parseInt(barcode, 10) ||
    !profiles || 0 === profiles.length){
    $("#alert").css("display", "initial");
    $("#alert").html("Fill in all fields!");
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
    if(document.getElementById("AddPerson").text == 'Add'){
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
                      "school_name":places,
                      "barcode":barcode,
                      "id_document_created_at":'NULL',
                      "notes":notes,
                      "places":places,
                      "profiles":profiles,
                      "grade":grade
                  }

      var dataString = JSON.stringify(postData);

      $.ajax({
              method: "POST",
              data: {action:dataString},
              url: "../../ajax/addperson/",
              success: function(data){
                  $("#alert").html(data);
                    if(data == 'person added'){
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
                    cell2.innerHTML = name;
                    cell3.innerHTML = lastname;
                    cell4.innerHTML = phone;
                    cell4.innerHTML = email;
                    cell6.innerHTML = places;
                    cell7.innerHTML = profiles;
                    $.ajax({
                          method: "POST",
                          data: {action:dataString},
                          url: "../../ajax/getidofperson/",
                          success: function(data){                          
                              console.log(data);
                              var data2 = data
                              cell1.innerHTML = '<input type="checkbox" id="'+data2+'" name="checkbox"> '
                              cell8.innerHTML = '<a class="button EditLaptop" onclick="Editperson(this)"  id="EditLaptop" data="'+data2+'"  role="button">Edit</a>';
                              cell9.innerHTML = '<a class="button DeleteLaptop" onclick="Deleteperson(this)" id="Deleteperson" data="'+data2+'" role="button">delete</a>';
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
      if($("#alert").html() != 'Person added'){
        $("#alert").css("display", "initial");
        
      }

    }
    else if(document.getElementById("AddPerson").text == 'Edit'){
      var Ids = $('#AddPerson').attr("data");

      //edit multiple laptops
      if(Ids.indexOf(',') > -1){
        var res = Ids.split(", ");
        res.pop();
        var teller = 0;
        for (Id in res) {
          var postData = 
                {
                    "id": res[Id],
                    "places":places,
                    "profiles":profiles,
                    "grade":grade
                }
          var dataString = JSON.stringify(postData);
          $.ajax({
                  method: "POST",
                  data: {action:dataString},
                  url: "../../ajax/editperson/",
                  success: function(data){
                      $("#alert").html(data); 
                      var index = $("#"+res[teller]).closest("tr").index();
                      var table = document.getElementById("table");
                      table.rows[index].cells[6].innerHTML = places;
                      table.rows[index].cells[7].innerHTML = profiles;
                      teller++;
                      console.log(data);
                  },
                  error: function(e){
                      $("#alert").html(e);
                      console.log(e);
                  }
          });
          if($("#alert").html() != 'laptops edited'){
            $("#alert").css("display", "initial");
            
          }
        }
      }

      //edit 1 laptop
      else{
        var index = $('#AddPerson').attr("index");
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
                      "school_name":places,
                      "barcode":barcode,
                      "id_document_created_at":'NULL',
                      "notes":notes,
                      "places":places,
                      "profiles":profiles,
                      "grade":grade
                  }

        var dataString = JSON.stringify(postData);
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../../ajax/editperson/",
                success: function(data){
                    $("#alert").html(data);
                    var table = document.getElementById("table");
                    table.rows[index].cells[1].innerHTML = name;
                    table.rows[index].cells[2].innerHTML = lastname;
                    table.rows[index].cells[3].innerHTML = phone;
                    table.rows[index].cells[4].innerHTML = email;
                    table.rows[index].cells[5].innerHTML = places;
                    table.rows[index].cells[6].innerHTML = profiles;
                    console.log(data);
                },
                error: function(e){
                    $("#alert").html(e);
                    console.log(e);
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


function deleteperson(datainput){
  var id = $(datainput).closest("tr")   // Finds the closest row <tr> 
                       .find(".Editperson")     // Gets a descendent with class="nr"
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
          url: "../../ajax/deleteperson/",
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
  var $namedescription = table.rows[index].cells[5].innerHTML;
  var $profdescription = table.rows[index].cells[6].innerHTML;
  var $birth_date = table.rows[index].cells[7].innerHTML;
  var $position = table.rows[index].cells[8].innerHTML;
  var $school_name = table.rows[index].cells[9].innerHTML;
  var $barcode = table.rows[index].cells[10].innerHTML;
  var $notes = table.rows[index].cells[12].innerHTML;
  var $typedescription = table.rows[index].cells[13].innerHTML;
  document.getElementById("AddPerson").setAttribute("data", $ID);
  document.getElementById("AddPerson").setAttribute("index", index);
  document.getElementById("Name").value = $Name;
  document.getElementById("Lastname").value = $Lastname;
  document.getElementById("birth_date").value = $birth_date;
  document.getElementById("phone").value = $phone;
  document.getElementById("email").value = $email;
  document.getElementById("notes").value = $notes;
  document.getElementById("profiles").value = $profdescription;
  document.getElementById("barcode").value = $barcode;
  document.getElementById("grade").value = $typedescription;
  document.getElementById("places").value = $namedescription;
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