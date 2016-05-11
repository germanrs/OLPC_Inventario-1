$(document).ready(function() {
    setDataUsers();
    enabledPass();
    //SetData('Persons','json-datalistpersons');
    SetData('Profiles','json-datalistprofiles');
});

$('#newPassword1').on('input', function(){
	enabledPass();
});
$('#password').on('input', function(){
	enabledPass();
});

function enabledPass(){
	if ($('#newPassword1').val().length > 0 ){
		$('#newPassword2').removeAttr('disabled');
	}else{
		$('#newPassword2').attr('disabled', true);
	}
	if ($('#password').val().length > 0 ){
		$('#newPassword1').removeAttr('disabled');
	}else{
		$('#newPassword1').attr('disabled', true);
	}
}

$('#save').on('click', function(){
	var userName = document.getElementById('userName').value;
	var password = document.getElementById('password').value;
	var newPassword1 = document.getElementById('newPassword1').value;
	var newPassword2 = document.getElementById('newPassword2').value;

	if (!userName || 0 === userName.length) {
		$('#error').html('Campo nombre de usuario es obligatorio!');
		$("#error").fadeToggle(1500);
		$("#error").fadeToggle(1500);
	}
	else if(!password || 0 === password.length){
	    $('#error').html('Campo contraseña es obligatorio!');
		$("#error").fadeToggle(1500);
		$("#error").fadeToggle(1500);
	}
	/*else if (!newPassword1 || 0 === newPassword1.length) {
		$('#error').html('Campo nueva contraseña es obligatorio!');
		$("#error").fadeToggle(1500);
		$("#error").fadeToggle(1500);
	}*/
	else if(newPassword1 || newPassword1.length > 0){
		if(newPassword1.length < 6){
		    $('#error').html('contraseña muy corta! (6 caracteres minimo)');
			$("#error").fadeToggle(1500);
			$("#error").fadeToggle(1500);
		}
		else if ($('#newPassword1').val() != $('#newPassword2').val()) {
			$('#error').html('Contraseñas no coinciden!');
			$("#error").fadeToggle(1500);
			$("#error").fadeToggle(1500);
		}
		else{
			save(password, newPassword1);
		}
	}
	else{
		save(password, newPassword1);
	}

});

function save(password, newPassword1){
	if (!newPassword1 || 0 === newPassword1.length) {
		newPassword1 = password;
	}
	var postData = {
		"actualName":$('#userName').attr('data'),
		"name": $('#userName').val(),
		"password":password,
		"newPassword":newPassword1
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: "../Ajax/getusersinfo/",
		success: function(data){
			var jsonOptions = JSON.parse(data);
			console.log(jsonOptions);
			if (jsonOptions['exist'] != false) {
				$('#error').html('Nombre de usuario existente!');
				$("#error").fadeToggle(1500);
				$("#error").fadeToggle(1500);
			}
			else if (jsonOptions['pass'] != true) {
				$('#error').html('Contraseña actual incorrecta!');
				$("#error").fadeToggle(1500);
				$("#error").fadeToggle(1500);
			}
			else if (jsonOptions['upd']){
				//$('#userName').val('');
				$('#password').val('');
				$('#newPassword1').val('');
				$('#newPassword2').val('');
				$("#success").fadeToggle(1500);
				$("#success").fadeToggle(1500);
			}
		},
		error: function(e){
			console.log(e);
		}
	});
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
				option.setAttribute("data", item.id);
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
  request.open('GET', '../Ajax/'+value+'/', true);
  request.send();
}

$('#Persons').keyup( function(){
	clearChildren('json-datalistpersons');
	if($('#Persons').val().length>0){
		GetUsersdata($('#Persons').val(),'json-datalistpersons');
	}
});
function clearChildren(parent_id){
	var childArray = document.getElementById( parent_id ).children;
    if ( childArray.length > 0 ) {
        document.getElementById( parent_id ).removeChild( childArray[ 0 ] );
        clearChildren(parent_id);
    }
}
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
      
      jsonOptions.forEach(function(item) {
	      var option = document.createElement('option');
	      option.text = item.id;
	      option.value = item.name;
	      dataList.appendChild(option);
      }); 
    },
    error: function(e){
      console.log(e);
      $("#alert").html(e);
    }
  });
}

function setDataUsers(){
  	$.ajax({
    	method: "POST",
    	url: "../Ajax/getdataforuserstable/",
    	success: function(data){
			console.log(data);
			var jsonOptions = JSON.parse(data);
			var table = document.getElementById("table");
			while(table.rows.length > 1) {
				table.deleteRow(1);
			}

			// Loop over the JSON array.
			for($index = 0; $index < jsonOptions.length; $index++){
				var row = table.insertRow(1);
				var cell0 = row.insertCell(0);
				var cell1 = row.insertCell(1);
				var cell2 = row.insertCell(2);
				var cell3 = row.insertCell(3);
				var cell4 = row.insertCell(4);
				cell0.innerHTML = '<input type="checkbox" id="'+jsonOptions[$index]['id']+'" name="checkbox"> '
				cell1.innerHTML = jsonOptions[$index]['usuario'];
				cell2.innerHTML = jsonOptions[$index]['description'];
				cell3.innerHTML = '<a class="button EditUser" onclick="Edituser(this)"  id="EditUser" data="'+jsonOptions[$index]['id']+'"  role="button" >Editar</a>';
				//cell4.innerHTML = '<a class="button DeleteUser" onclick="Deleteuser(this)" id="DeleteUser" data="'+jsonOptions[$index]['id']+'" role="button">Eliminar</a>'; 
			}
		},
		error: function(e){
			console.log(e);
		}
	}); 
}

function getUserInfo(){
	var postData = {
		"name": document.getElementById("password").value
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: "../Ajax/getusersinfo/",
		success: function(data){
			var jsonOptions = JSON.parse(data);
			console.log(data);
			console.log(jsonOptions['usuario']);
		},
		error: function(e){
		console.log(e);
		}
	}); 
}
function Deleteuser(datainput){
	var id = $(datainput).closest("tr").find(".EditUser").attr("data");
	document.getElementById(id).checked = true;

	$("#openModal2").css("opacity", "1");
	$("#openModal2").css("pointer-events", "auto");
}


function DeleteUser(datainput){
    var id = $(datainput).closest("tr").find(".EditUser").attr("data");
	var index = $(datainput).closest("tr").index();

	console.log(id);
	console.log(index);

	var postData = {
		"id":id
	}
	var dataString = JSON.stringify(postData);

	//make a json request to delete 1 person.
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: "../Ajax/deleteUser/",
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

$('#addUser').on('click', function(){
	Adduser();
});
$( ".EditUser" ).click(function() {
  Edituser(this);
});
$( "#CloseAddModal" ).click(function() {
   $("#openModal").css("opacity", "0");
   $("#openModal").css("pointer-events", "none");
});
$( "#CloseAddModal2" ).click(function() {
   $("#openModal2").css("opacity", "0");
   $("#openModal2").css("pointer-events", "none");
});
$( "#cancelDelete" ).click(function() {
   $("#openModal2").css("opacity", "0");
   $("#openModal2").css("pointer-events", "none");
});

function Edituser(datainput){
	$("#alert").css("display", "none");
	document.getElementById("EditPerson").text = 'Guardar cambios';
	$("#divAddPerson").addClass("hidden");
	$('#Persons').attr('disabled', true);
	$('#Profiles').removeAttr('disabled');
	$("#AddPerson").addClass("hidden");
	$("#EditPerson").removeClass("hidden");
	document.getElementById("Name").value = "";
	document.getElementById("Profiles").value = "";
	document.getElementById("Persons").value = "";
	document.getElementById("Password").value = "";
	document.getElementById("Password2").value = "";

	var index = $(datainput).closest("tr").index();
	var $ID = $(datainput).attr("data");
	console.log($ID);

	var table = document.getElementById("table");
	var $Name = table.rows[index].cells[1].innerHTML;
	var $Profile = table.rows[index].cells[2].innerHTML;

	document.getElementById("EditPerson").setAttribute("data", $ID);
	document.getElementById("EditPerson").setAttribute("index", index);
	document.getElementById("EditPerson").setAttribute("name", $Name);
	document.getElementById("Name").value = $Name;
	document.getElementById("Profiles").value = $Profile;

	$("#openModal").css("opacity", "1");
	$("#openModal").css("pointer-events", "auto");
}

$("#EditPerson").on("click", function(){
	var usuario = document.getElementById("Name").value;
	var perfil = document.getElementById("Profiles").value;
	var clave = document.getElementById("Password").value;
	var clave2 = document.getElementById("Password2").value;

	if (!usuario || 0 === usuario.length) {
		$("#alert").css("display", "initial");
	    $("#alert").html("Fill in a usuario!");
	}
	else if(!perfil || 0 === perfil.length){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Fill in a perfil!");
	}
	/*else if(!clave || 0 === clave.length){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Fill in a clave!");
	}*/
	else if (clave || clave.length > 0) {
		if(clave.length < 6){
		    $("#alert").css("display", "initial");
		    $("#alert").html("clave muy corta!");
		}
		else if(!clave2 || 0 === clave2.length){
		    $("#alert").css("display", "initial");
		    $("#alert").html("Fill in a clave2!");
		}
		else if(clave !== clave2){
		    $("#alert").css("display", "initial");
		    $("#alert").html("Contraseñas no coinciden!");
		}
		else{
			editPerson(usuario, perfil, clave);
		}
	}
	else{
		editPerson(usuario, perfil, clave);
	}

});

function editPerson(usuario, perfil, clave){
	var postData = {'name':usuario, 'actualName':$("#EditPerson").attr("name")}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: "../Ajax/validateUser/",
		success: function(data){
			var jsonOptions = JSON.parse(data);
			resp = jsonOptions['resp'];
			if (jsonOptions['resp']) {
				$("#alert").css("display", "initial");
    			$("#alert").html("Nombre de usuario existente!");
			}else{
				var id = $("#EditPerson").attr("data");
				var profile_id = $('[value="'+perfil+'"]').attr('data');
				var postData = {
					"id":id,
					"usuario":usuario,
					"clave":clave,
					"profile_id":profile_id
				}
				var dataString = JSON.stringify(postData);
				$.ajax({
					method: "POST",
					data: {action:dataString},
					url: "../Ajax/editUser/",
					success: function(data){
						console.log(data);
						
						var index = $("#EditPerson").attr("index");
						var table = document.getElementById("table");
						table.rows[index].cells[1].innerHTML = usuario;
						table.rows[index].cells[2].innerHTML = perfil;
						
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
			console.log(e);
		}
	});
}

function Adduser(){
	$("#alert").css("display", "none");
	document.getElementById("AddPerson").text = 'Agregar usuario';

	$("#divAddPerson").removeClass("hidden");
	$('#Persons').removeAttr('disabled');
	$('#Profiles').attr('disabled', true);
	$("#AddPerson").removeClass("hidden");
	$("#EditPerson").addClass("hidden");
	document.getElementById("Name").value = "";
	document.getElementById("Profiles").value = "";
	document.getElementById("Persons").value = "";
	document.getElementById("Password").value = "";
	document.getElementById("Password2").value = "";

	$("#openModal").css("opacity", "1");
	$("#openModal").css("pointer-events", "auto");
}

$("#AddPerson").on("click", function(){
	var usuario = document.getElementById("Name").value;
	var person = document.getElementById("Persons").value;
	var clave = document.getElementById("Password").value;
	var clave2 = document.getElementById("Password2").value;

	if (!usuario || 0 === usuario.length) {
		$("#alert").css("display", "initial");
	    $("#alert").html("Fill in a usuario!");
	}
	else if(!person || 0 === person.length){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Fill in a person!");
	}
	else if(!clave || 0 === clave.length){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Fill in a clave!");
	}
	else if(clave.length < 6){
	    $("#alert").css("display", "initial");
	    $("#alert").html("clave muy corta!");
	}
	else if(!clave2 || 0 === clave2.length){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Fill in a clave2!");
	}
	else if(clave !== clave2){
	    $("#alert").css("display", "initial");
	    $("#alert").html("Contraseñas no coinciden!");
	}
	else{
		var postData = {'name':usuario, 'actualName':''}
		var dataString = JSON.stringify(postData);
		$.ajax({
			method: "POST",
			data: {action:dataString},
			url: "../Ajax/validateUser/",
			success: function(data){
				var jsonOptions = JSON.parse(data);
				resp = jsonOptions['resp'];
				if (jsonOptions['resp']) {
					$("#alert").css("display", "initial");
	    			$("#alert").html("Nombre de usuario existente!");
				}else{
					var postData = {
						"usuario":usuario,
						"clave":clave,
						"person":person
					}
					var dataString = JSON.stringify(postData);
					$.ajax({
						method: "POST",
						data: {action:dataString},
						url: "../Ajax/addUser/",
						success: function(data){
							var jsonOptions = JSON.parse(data);
							console.log(data);
							var table = document.getElementById("table");
			                var row = table.insertRow(1);
							var cell0 = row.insertCell(0);
							var cell1 = row.insertCell(1);
							var cell2 = row.insertCell(2);
							var cell3 = row.insertCell(3);
							cell0.innerHTML = '<input type="checkbox" id="'+jsonOptions[0]['id']+'" name="checkbox"> '
							cell1.innerHTML = jsonOptions[0]['usuario'];
							cell2.innerHTML = jsonOptions[0]['description'];
							cell3.innerHTML = '<a class="button EditUser" onclick="Edituser(this)"  id="EditUser" data="'+jsonOptions[0]['id']+'"  role="button">Editar</a>';
							//cell4.innerHTML = '<a class="button DeleteUser" onclick="Deleteuser(this)" id="DeleteUser" data="'+jsonOptions[0]['id']+'" role="button">Eliminar</a>'; 

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
				console.log(e);
			}
		});
	}	
});

$("#checkallboxes").click(function(){
	//get all the cechboxes.
	checkboxes = document.getElementsByName('checkbox');
	//check all the chexboxes
	if (document.getElementById("checkallboxes").checked) {
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

//get all the selected items and delete them
$( "#deleteSelected" ).on('click', function(){
	if (getCheckedBoxes('checkbox')!=null) {
		$("#openModal2").css("opacity", "1");
		$("#openModal2").css("pointer-events", "auto");
	}
});

$("#confirmDelete").on("click", function(){
	//get all the selected items
	var checkedBoxes = getCheckedBoxes("checkbox");
	//loop over the selected items and delete them
	for (box in checkedBoxes) {
		DeleteUser(checkedBoxes[box]);
	}
	$("#openModal2").css("opacity", "0");
	$("#openModal2").css("pointer-events", "none");
});

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