<?php 
  include('../model/autoload_extra.php');
  include('../systems/login.php');
  include('../systems/saveDate.php');

  //include('../systems/ajaxRequest.php');
  
  $userConn = new userController();
  $bissConn = new businessController();
  $bissView = new businessView();
  $aptConn  = new appointmentsController(); 
  $aptView  = new appointmentsView();

  if (login::isLoggedIn()) {
    echo '<script>
      window.location.replace("../index.php");
    </script>';
  }

  $reqID      = $_GET['reqID'];
  $clientID   = $aptConn->getClientId($reqID);
  

  include('header.php');
?>

  <style>
    #defaultModal { 
      background-color: rgba(0, 0, 0, 0.5) !important;
    }

    #appointmentsController {
      background-color: rgba(0, 0, 0, 0.5) !important;
    }

    .h-modal {
      height: 100% !important;
    }
  </style>

  <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.2/dist/flowbite.min.css" />  
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

  <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

  <main class="h-screen overflow-y-auto">
    <div class="flex items-center justify-center h-full">
      <div class="w-full bg-white rounded-md shadow-lg xl:w-1/3 md:w-4/5 dark:bg-gray-800 h-1/2">
        <div class="grid grid-rows-3 h-4/5">
          <div class="row-span-2">
            <h1 class="mt-4 text-3xl font-semibold text-center text-white">Great! Let's create a PIN.</h1>
            <h3 class="block w-4/5 mx-auto mt-5 text-center text-white">You will need this PIN to access your account and controll your appointments. Don't worry, you can set a password from profile settings.</h3>
          </div>
        
          <div class="flex items-center justify-center row-span-1 pin-wrapper">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200 pin-input">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="mx-2 my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200 pin-input">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="mx-2 my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200">
            <input type="text" data-role="pin" onkeypress="return isNumberKey(event)" maxlength="1" class="mx-2 my-2 text-xl text-center border-2 border-white rounded-md outline-none w-11 h-11 bg-slate-200">
          </div>
        </div>
        <p class="hidden pin"></p>
      </div>
    </div>
  </main>

  <script>
    $(document).ready(function() {
      $(".pin-wrapper").validatePin({
        numericKeyboardOnMobile: true,
        blurOnSuccess: true,
        onSuccess: function() {
          $.ajax({
            url: '../systems/ajaxRequest.php',
            type: 'post',
            data: {
              requestID: 14,
              pinCode: pin,
              reqID: GetURLParameter('reqID')
            },
            success: function(result) {
                window.location.replace("../businesses.php?businessID=<?php echo $clientID['business_id'] ?>&bookConf=1");
            }
          })
          $(".pin").html(pin);
        },
        onFailure: function() {
          $(".pin").html("");
        }
      });
    });
  </script>

  <script src="../static/js/userPin.js" defer></script>
  <script src="../static/js/clientSettings.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.1/dist/datepicker.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.2/dist/flowbite.js"></script>
  <script src="../static/js/requests.js" defer></script>

  </body>

  </html>