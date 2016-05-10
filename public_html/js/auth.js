// change the height of the form, else the design is broken
if(document.getElementById("error").innerHTML!=''){
  $('.login').css( "height", "24rem" );
}

/*
//make the ajax request to get all the models with basic auth
$.ajax({
    method: "POST",
    url: "../../Ajax/model/",

    //set the password
    data: {password: btoa(username + ":" + password)},
    
    //if success show data in console
    success: function (data){
      console.log(data);
    }
});

//make the ajax request to get all the models with Oauth
$.ajax({
    method: "POST",
    url: "../../Ajax/model/",
    
    //add the token
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', "OAuth " + token);
      xhr.setRequestHeader('Accept', "application/json");
    },

    //if success show data in console
    success: function (data){
      console.log(data);
    }
});*/