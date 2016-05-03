//hide the form when escpase is pressed
$(document).keyup(function(e) {
      // escape key maps to keycode `27`
     if (e.keyCode == 27) { 

        //hide the form
        $("#openModal").css("opacity", "0");
        $("#openModal").css("pointer-events", "none");
    }
});

