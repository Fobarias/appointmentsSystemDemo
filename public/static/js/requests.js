if(window.location.pathname.split("/").at(-2) == 'admin' || window.location.pathname.split("/").at(-2) == 'empPanel') {
  var path = '../';
} else {
  var path = '';
}


$(document).ready(function() {
  var phoneNumber;
  var documentSize = $(this).width();
  var dateSelected = $('#appDate').val();
  var servSelected = $('#serviceSel').val();
  $(window).on('resize', (function () {
    documentSize = $(this).width();
  }));

  /* GET VALUES OF A SPECIFIC PARAMETER OF URL */
  function GetURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
      var sParameterName = sURLVariables[i].split('=');
      if (sParameterName[0] == sParam) {
          return sParameterName[1];
      }
    }
  }

  /* ON SERVICE SELECT ENABLE EMPLOYEE SELECT -- REQUEST 1 */
  $('#serviceSel').on('change', function() {
    $('#emp').prop("disabled", false);
    $.ajax({
      url: path + 'systems/ajaxRequest.php' + window.location.search,
      type: 'post',
      data: {
        requestID: 1,
        serviceSelect: $(this).val(),
      },
      success: function(result) {
          $("#emp").html(result);
      }
    })
  });

  /* CHANGE AVAILABLE HOURS -- REQUEST 2 */
  function changeHours(dateSelected, servSelected) {
    if($('#emp').lenght != 0) {
      var empSel = $("#emp").val();
    } else {
      var empSel = 'Null';
    }

    $.ajax({
      url: path + 'systems/ajaxRequest.php' + window.location.search,
      type: 'post',
      data: {
        requestID: 2,
        empSel: empSel,
        servID: servSelected,
        date: dateSelected,
      },
      success: function(result) {
        $("#hours").html(result);
      }
    });
  }

  /* SAVE EMPLOYEE INFO -- REQUEST 3 */
  function saveAllInfo(clickedID) {
    $.ajax({
    url: path + 'systems/ajaxRequest.php',
    type: 'post',
    data: {
        requestID: 3,
        phoneNumberPass: clickedID,
        nameEmp:    $("input[name=nameEmpRow]").val(),
        emailEmp:   $("input[name=emailEmpRow]").val(),
        addressEmp: $("input[name=addressEmpRow]").val()      
    },
    success: function(result) {
        location.reload();
    }
    })
  }

  /* EMPLOYEE SHOW APPOINTMENT INFO -- REQUEST 4 & 5 */
  function showAptInfo() {
    var previusApt = 0;
    $(".editAppt").click(function(){
      var id = $(this).attr('id'); //get the id of the element that was clicked.
  
      var arr = id.split("_"); //split to get the number
      var no = arr[1]; //get the number
  
      if(previusApt != no) {
        $.ajax({
          url: path + 'systems/ajaxRequest.php',
          type: 'post',
          data: {
            requestID: 4,
            aptID: no
          },
          success: function(result) {
            if(documentSize < 1280) {
              $("#aptInformationMobile").html(result);
            } else {
              $("#aptInformation").html(result);
            }
            previusApt = no;
            $('#confirmApt').click(function() {
              $.ajax({
                url: path + 'systems/ajaxRequest.php',
                type: 'post',
                data: {
                  requestID: 15,
                  aptID: previusApt
                },
                success: function(result) {
                  location.reload();
                }
              })
            });

            $('#deleteApt').click(function() {
              $.ajax({
                url: path + 'systems/ajaxRequest.php',
                type: 'post',
                data: {
                  requestID: 5,
                  aptID: previusApt
                },
                success: function(result) {
                  location.reload();
                }
              })
            });
          }
        })
      }
    });
  }

  showAptInfo();


  /* DELETE SERVICE -- REQUEST 7 */
  function deleteServices(clickedID, serviceID) {
    $.ajax({
    url: path + 'systems/ajaxRequest.php',
    type: 'post',
    data: {
        phoneNumberPass: clickedID,
        serviceID: serviceID,
        requestID: 7
    },
    success: function(result) { 
        getAttr(clickedID);
    }
    })
  }

  /* INSERT APPOINTMENT INTO DATABASE -- REQUEST 8 */
  $('#aptInsert').on('click', (function() {
    var eClient = $("#eClient").hasClass('bg-purple-600');
    var nClient = $("#nClient").hasClass('bg-purple-600');
    
    $.ajax({
      url: path + 'systems/ajaxRequest.php',
      type: 'post',
      data: {
        requestID: 8,
        phoneNumber: $("#search").val(),
        phoneNumberNew: $("#clientPhoneNew").val(),
        clientName: $("#clientName").val(),
        serviceSel: $("#serviceSel").val(),
        empSel: $("#emp").val(),
        date: $('#appDate').val(),
        hour: $('#hours').val(),
        eClient: eClient,
        nClient: nClient
      },
      success: function(result) { 
        $("#aptInsertStatus").html(result);
        location.reload();
      }
    })
  }))

  /* SEARCH ON INTERVAL FOR DATE CHANGE AND DISPLAY AVAILABLE HOURS */
  if($('#selectDate').lenght != 0) {
    $('#selectDate').click(function() {
      setInterval(function() {
        var newDate = $('#appDate').val();
        var newServ = $('#serviceSel').val();
        if(newDate != dateSelected || newServ != servSelected) {
          if(newDate != dateSelected) dateSelected = newDate;
          if(newServ != servSelected) servSelected = newServ;
          
          changeHours(dateSelected, servSelected);
        }
  
      },100);
  
      setInterval
    });
  }

  if($('#selectDate2').lenght != 0) {
    $('#selectDate2').click(function() {
      setInterval(function() {
        var newDate = $('#appDate').val();
        var newServ = $('#serviceSel').val();
        if(newDate != dateSelected || newServ != servSelected) {
          if(newDate != dateSelected) dateSelected = newDate;
          if(newServ != servSelected) servSelected = newServ;
          
          changeHours(dateSelected, servSelected);
        }
      },100);
    });
  } 

  /* SELECT EMPLYOEE CALENDAR -- REQUEST 9 */
  $('#selectCalendar').on('change', function() {
    $.ajax({
      url: path + 'systems/ajaxRequest.php',
      type: 'post',
      data: {
        date: GetURLParameter('date'),
        empID: $(this).val(),
        requestID: 9
      },
      success: function(result) {
        $("#calendar").html(result);
  
        showAptInfo();
      }
    })
  });

  /* SEARCH CLIENT PHONE NUBMER -- REQUEST 12 */
  $("#search").keyup(function() {
    $.ajax({
      url: path + 'systems/ajaxRequest.php',
      type: 'post',
      data: {
        requestID: 12,
        search: $(this).val()
      },
      success: function(result) {
        $("#result").html(result);
      }
    })
  });

});

/* SAVE SERVICES -- REQUEST 6 */
function saveServices(clickedID) {
  $.ajax({
    url: path + 'systems/ajaxRequest.php',
    type: 'post',
    data: {
      phoneNumberPass: clickedID,
      service: $("#attrAdd").val(),
      serviceText: $("#attrAdd").text(),
      requestID: 6
    },
    success: function(result) { 
      getAttr(clickedID);
    }
  })
}

/* GET EMPLOYEE INFO -- REQUEST 10 */
function getInfo(clickedID) {

  $.ajax({
    url: path + 'systems/ajaxRequest.php',
    type: 'post',
    data: {
      requestID: 10,
      phoneNumberPass: clickedID
    },
    success: function(result) {
        $("#result").html(result);
    }
  })

  phoneNumber = clickedID;

}

/* GET EMPLOYEE ATTRIBUTES -- REQUEST 11 */
function getAttr(clickedID) {

    $.ajax({
    url: path + 'systems/ajaxRequest.php',
    type: 'post',
    data: {
      requestID: 11,
      phoneNumberPass: clickedID
    },
    success: function(result) {
        $("#attr").html(result);
    }
    })
}