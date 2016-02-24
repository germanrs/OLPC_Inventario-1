
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

$( "#openAddModal" ).click(function() {
   $("#openModal").css("opacity", "1");
   $("#openModal").css("pointer-events", "auto");
});

$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
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
                console.log(data);
                $("#alert").html(data);
            },
            error: function(e){
                console.log(e);
                $("#alert").html(e);
            }
    });
    if($("#alert").html() != 'laptop added'){
      $("#alert").css("display", "initial");
      
    }
    else{
      $("#alert").css("display", "none");
      $("#openModal").css("opacity", "0");
      $("#openModal").css("pointer-events", "none");
    }
  }
});