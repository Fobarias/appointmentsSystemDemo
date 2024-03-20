<?php
  include('../model/autoload_extra.php');
  include('../systems/login.php');
  
  $userConn = new userController();
  $bissConn = new businessController();
  $bissView = new businessView();
  $apntView = new appointmentsView();
  $userid   = $userConn->getUID(sha1($_COOKIE['LGSCCS'])) ?? '';

  if (login::isLoggedIn()) {
    if($bissConn->getBusinessSetup($userid) == 0) {
      include('../systems/checkSubscription.php');
    } else {
      echo '<script>
        window.location.replace("index.php");
      </script>';
    }
  } else {
      echo '<script>
        window.location.replace("../index.php");
      </script>';
  }
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artty | Business Wizard</title>
  <link rel="stylesheet" href="../static/css/tailwind.output.css" />
  <link rel="stylesheet" href="../static/css/tailwind.css">
  <link rel="stylesheet" href="assets/css/toggle.css">
</head>
<body>
  <form method="post">
    <div class="w-screen h-auto lg:h-screen overflow-x-hidden lg:overflow-hidden">
      <?php include('../systems/saveDate.php'); ?>
      <div class="flex justify-center items-center w-screen h-auto lg:h-screen overflow-x-hidden lg:overflow-hidden bg-white dark:bg-[#040F16]">
        <!-- COMPONENT CODE -->
        <div class="container mx-auto my-4 px-4 lg:px-20">
  
          <div class="w-full p-8 my-4 md:px-12 lg:w-9/12 lg:pl-20 lg:pr-40 mr-auto rounded-2xl shadow-2xl dark:bg-[#092434]">
            <div class="flex">
              <h1 class="font-bold uppercase text-3xl text-black dark:text-white">Let's setup your business information</h1>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mt-5">
              <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline" name="bName" type="text" placeholder="Business name" />
              <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline" name="bAddr" type="text" placeholder="Business address" />
              <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline" name="bCntr" type="text" placeholder="Country" />
              <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline" name="bCity" type="text" placeholder="City" />
              <select class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline" name="bType">
                <option disabled selected>Select a type</option>
                <option>Medical clinic</option>
                <option>Hairstyle</option>
                <option>Barbershop</option>
                <option>Nails</option>
                <option>Makeup</option>
                <option>Lashes</option>
                <option>Spa</option>
                <option>Others</option>
              </select>
            </div>
          </div>
  
            <div class="w-full lg:-mt-96 lg:w-2/6 px-8 py-12 ml-auto bg-[#058ED9] dark:bg-white rounded-2xl">
              <h1 class="mb-5 text-3xl font-bold uppercase text-white dark:text-black">Working hours</h1>
              <div class="flex flex-col text-white">
                <div class="mb-2 grid grid-cols-3">
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Monday</p>
                    </div>
                    <input type="text" name="whMonday" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" checked name="monFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
  
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Thuesday</p>
                    </div>
                    <input type="text" name="whTue" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5  dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" checked name="tueFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Wednesday</p>
                    </div>
                    <input type="text" name="whWed" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5   dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" checked name="wedFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
  
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Thursday</p>
                    </div>
                    <input type="text" name="whThu" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5   dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" checked name="thuFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
  
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Friday</p>
                    </div>
                    <input type="text" name="whFri" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5   dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" checked name="friFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
  
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Saturday</p>
                    </div>
                    <input type="text" name="whSat" id="email-address-icon"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5   dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" name="satFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
                <div class="mb-2 grid grid-cols-3">
                  <div class="relative mb-2 col-span-2">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <p class="text-black">Sunday</p>
                    </div>
                    <input type="text" name="whSun" id="email-address-icon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-28 p-2.5   dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="9-17">
                  </div>
                  <div class="ml-3">
                    <div class="toggle">
                      <input type="checkbox" name="sunFree"/>
                      <label></label>
                    </div>
                  </div>
                </div>
  
                <div class="my-4">
                  <input value="Next" type="submit" name="nextStep" class="cursor-pointer uppercase text-sm font-bold tracking-wide bg-white dark:bg-[#092434] dark:text-white text-black text-center p-3 rounded-lg w-full focus:outline-none focus:shadow-outline">
                </div>
  
              </div>
            </div>
          </div>
          <!-- COMPONENT CODE -->
      </div>
    </div>
  </form>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</body>
</html>



