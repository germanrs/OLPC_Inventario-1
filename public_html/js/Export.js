var columns = [];
var rows = [];

$(document).ready(function() {
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
 
    $('.accordion-section-title').click(function(e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');
 
        if($(e.target).is('.active')) {
            close_accordion_section();
        }else {
            close_accordion_section();
 
            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
        }
 
        e.preventDefault();
    });
});

$( "#submitlaptop" ).click(function() {
    GetData(this, 'laptopsForm');
       
});

function GetData(datainput, formname){
    columns = [];
    rows = [];
    var boxlist = '';
    var total = $("#"+formname+" input:checkbox:checked").length;
    $("#"+formname+" input:checkbox:checked").each(function(index) {
        if (index === total - 1) {
            boxlist += this.value;
        }
        else{
            boxlist += this.value + ', ';
        }
        
    });
    $("#exportTable tr").remove();
    var OrderByTerm = $(datainput).closest("form")   
                       .find("#orderByTerm")
                       .val();                          
    var orderList = $(datainput).closest("form")   
                       .find("#orderList")
                       .val();  
    var GroupByTerm = $(datainput).closest("form")   
                       .find("#GroupByTerm")
                       .val();    
    var inputfield = $(datainput).closest("form")   
                       .find("#inputfield")
                       .val();
    if(0<boxlist.length){
        var postData = 
                    {
                        "formname": formname,
                        "coloms": boxlist,
                        "OrderByTerm":OrderByTerm,
                        "orderList":orderList,
                        "GroupByTerm":GroupByTerm,
                        "inputfield":inputfield
                    }

        var dataString = JSON.stringify(postData);
        $.ajax({
                method: "POST",
                data: {action:dataString},
                url: "../ajax/getList/",
                success: function(data){
                    
                    var table = document.getElementById("exportTable");
                    var jsonOptions = JSON.parse(data);
                    // Loop over the JSON array.
                    var row = table.insertRow(0);
                    document.getElementById("totalitems").innerHTML = "total items:"+jsonOptions.length;
                    
                    var i = 0;
                    for (var k in jsonOptions[1]){
                        if (jsonOptions[1].hasOwnProperty(k)) {
                             var cell1 = row.insertCell(i);
                             cell1.innerHTML = k;
                             i++;
                             columns.push({title: k, dataKey:k});
                        }
                    }
                    
                    rows = jsonOptions.slice(0,200); 
                    document.getElementById("totalitems").setAttribute("data",i);
                    $width = [0,0,0,0,0,0,0,0];
                    var j = 1;
                    for(var i = 0; i < jsonOptions.length; ++i) {
                        var l=0;
                        var row = table.insertRow();
                        for (var k in jsonOptions[i]){
                            var cell1 = row.insertCell(l);
                            cell1.innerHTML =jsonOptions[i][k];
                            l++;
                        }
                        j++;
                        if(j===20){
                            break;
                        }
                    };
                    document.getElementById("pdfcontent").style.display = "inherit";

                },
                error: function(e){
                    console.log(e);
                }
        });
    }
}



$('#DownloadFile').click(function () {
    var doc = new jsPDF('p', 'pt');
    doc.autoTable(columns, rows, {
        styles: {
            fillStyle: 'DF',
            overflow: 'linebreak',
        },
        headerStyles: {
            fillColor: [22,127,146],
            textColor: 255,
            fontSize: 15,
            rowHeight: 30
        },
        bodyStyles: {
            fillColor: [255, 255, 255],
            textColor: 000
        },
        alternateRowStyles: {
            fillColor:[234,243,243]
        },
        columnStyles: {
            email: {
                fontStyle: 'bold'
            }
        },
        margin: {top: 60},
        beforePageContent: function(data) {
            doc.text("Laptop Table", 40, 30);
        }
    });
    doc.save('table.pdf');
});

