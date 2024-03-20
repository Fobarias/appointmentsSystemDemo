<?php 
  include('../model/autoload_extra.php');
  include('../systems/login.php');
  include('../systems/saveDate.php');

  //include('../systems/ajaxRequest.php');
  
  $userConn = new userController();
  $bissConn = new businessController();
  $bissView = new businessView();
  $aptView = new appointmentsView();
  $userid   = $userConn->getUID(sha1($_COOKIE['LGSCCS']));
  
  if (login::isLoggedIn()) {
    include('../systems/checkSubscription.php');
  } else {
      echo '<script>
        window.location.replace("../index.php");
      </script>';
  }

  $date     = $_GET['date'] ?? '';
  $hour     = $_GET['hour'] ?? '';

  $dateBroken = str_replace('-', '/', $date);
  $dateToTime = strtotime($dateBroken);
  $dateDay  = strtolower(date('D', $dateToTime));

  if($dateDay == 'mon') {
      $day = 1;
  } elseif($dateDay == 'tue') {
      $day = 2;
  } elseif($dateDay == 'wed') {
      $day = 3;
  } elseif($dateDay == 'thu') {
      $day = 4;
  } elseif($dateDay == 'fri') {
      $day = 5;
  } elseif($dateDay == 'sat') {
      $day = 6;
  } elseif($dateDay == 'sun') {
      $day = 7;
  }

  $dayInfo = $bissConn->workingHours($businessData['id'], $day);
  while($row = $dayInfo->fetch()) {
      $busOpen   = (int)substr($row['openingHours'], 0, 2);
      $busClose  = (int)substr($row['closingHours'], 0, 2);
      $freeDay   = $row['freeDay'];
  }
  $openHour = $busClose - $busOpen;


  if($date == '' || $hour == '') {
    date_default_timezone_set('Europe/Bucharest');
    $currDate = date("m-d-Y");
    $hour     = date("G");
    echo '<script>
      window.location.replace("?date='. $currDate .'&hour='. $hour .'");
    </script>'; 
  }

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




  <main class="h-full overflow-y-auto">
    <div class="grid px-0 md:px-6 mx-auto w-[80%]">
      <div class="flex justify-between">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
          Dashboard
        </h2>
        <div class="flex items-center justify-center">
          <button @click="openModal" id="openController" class="px-4 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg h-1/2 xl:hidden active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Appointments info
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-2">
        <div>          
          <div id="calendar" class="grid mt-2 overflow-y-auto grid-rows-<?php echo $openHour + 1 ?>">
            <?php $aptView->displayApt($businessData['id'], $date, $busOpen, $busClose, $userid, $freeDay); ?>
          </div>
        </div>

        <div class="justify-center hidden w-full xl:flex">
          <div class="w-[480px] 2xl:w-[550px]">
            <div class="fixed rounded-md shadow-md dark:shadow-none w-inherit dark:bg-gray-800 h-500px">
              <div class="relative mt-3 ml-4 2xl:ml-10">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <form method="post" autocomplete="off">
                  <div class="flex">
                    <input datepicker datepicker-buttons datepicker-format="mm-dd-yyyy" name="date" type="text"
                      class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-[80%] pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      id="dateSelect"
                      placeholder="Select date">
                    <input type="submit" name="selectDate" value="Search"
                      class="px-3 ml-3 text-white bg-purple-600 rounded-md cursor-pointer">
                  </div>
                </form>
              </div>
              <div class="ml-4 2xl:ml-10 mt-8 text-lg font-semibold w-[90%]" id="aptInformation">
                
              </div>
              <div class="absolute bottom-0 right-0 mb-3 ml-4 mr-3 2xl:ml-10">
                <button
                  class="block text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600  dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                  type="button" id="selectDate2" data-modal-toggle="defaultModal">
                  Create appointment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>
  </main>
  </div>
  </div>

  <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0  transform translate-y-1/2"
      @keydown.escape="closeModal"
      class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
      role="dialog" id="modal">
      <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
      <header class="flex justify-end">
        <button
          class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
          aria-label="close" @click="closeModal">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
            <path
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd" fill-rule="evenodd"></path>
          </svg>
        </button>
      </header>

      <div class="flex justify-center w-full h-500px dark:bg-gray-800">
        <div class="w-[90%]">
          <div class="w-full mt-4 mb-6">            
          <div class="relative mt-3 ml-4 2xl:ml-10">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                  viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <form method="post" autocomplete="off">
                <div class="flex">
                  <input datepicker datepicker-buttons datepicker-format="mm-dd-yyyy" name="date" type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-[80%] pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    id="dateSelect"
                    placeholder="Select date">
                  <input type="submit" name="selectDate" value="Search"
                    class="px-3 ml-3 text-white bg-purple-600 rounded-md cursor-pointer">
                </div>
              </form>
            </div>
            <div class="ml-4 2xl:ml-10 mt-8 text-lg font-semibold w-[90%]" id="aptInformationMobile">
              
            </div>
            <div class="h-inherit">
              <div class="mt-6 mb-3 ml-4 mr-3 2xl:ml-10">
                <button
                  class="block text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600  dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                  type="button" id="selectDate" data-modal-toggle="defaultModal">
                  Create appointment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t dark:border-gray-600">
          <button type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-toggle="defaultModal">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
            </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <script>
        
      </script>

      <div class="flex items-center justify-center w-full">
        <div class="w-[90%]">
          <div class="w-full mt-4 mb-6">            
            <h2 class="mb-2 text-xl font-semibold text-white">Client</h2>
            <span id="aptInsertStatus"></span>
            <div class="grid w-full grid-cols-2 bg-gray-700 rounded-md">
              <div class="flex items-center justify-center w-full">
                <button class="w-full text-center text-white bg-purple-600 rounded-md" id="eClient">Existing client</button>
              </div>
              <div class="flex items-center justify-center w-full">
                <button class="w-full text-center text-white rounded-md" id="nClient">New client</button>
              </div>
            </div>

            <div id="existingClient" class="block">
              <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                </div>
                <label class="block mt-4 text-sm">
                  <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <input type="text" autocomplete="off" id="search" name="clientPhoneSearch" placeholder="Client phone" class="w-full bg-transparent outline-none">
                  </div>
                </label>
                <span id="result" class="absolute z-20 w-full">
                  
                </span>                
              </div>
            </div>

            <div id="newClient" class="hidden">
              <label class="block mt-4 text-sm">
                <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                  <input type="text" name="clientName" id="clientName" placeholder="Client name" class="w-full bg-transparent outline-none">
                </div>
              </label>

              <label class="block mt-4 text-sm">
                <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                  <input type="text" name="clientPhone" id="clientPhoneNew" placeholder="Client phone" class="w-full bg-transparent outline-none">
                </div>
              </label>
            </div>

            <div class="w-full h-[2px] mt-6 bg-gray-700"></div>
            <h2 class="mt-2 text-xl font-semibold text-white">Service</h2>

            <label class="block mt-4 text-sm">
              <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <select id="serviceSel" class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option disabled selected>Select service</option>
                  <?php $aptView->displayEmpServ($businessData['id'], $userid); ?>
                </select>
              </div>
            </label>

            <div class="w-full h-[2px] mt-6 bg-gray-700"></div>
            <h2 class="mt-2 text-xl font-semibold text-white">Date & hour</h2>

            <div class="grid w-full grid-cols-2">
              <div class="mt-4">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                      viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd"></path>
                    </svg>
                  </div>
                  <form method="post" autocomplete="off">
                    <div class="flex">
                      <input datepicker datepicker-buttons datepicker-format="mm-dd-yyyy" name="date" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-[90%] pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        id="appDate"
                        placeholder="Select date">
                    </div>
                  </form>
                </div>
              </div>
              <div class="flex justify-center items-center w-[90%] h-[70%] mt-4 text-white">
                <select id="hours" name="time" class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  
                </select>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center float-right p-6 space-x-2 border-gray-200 rounded-b dark:border-gray-600">
              <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
              <button id="aptInsert" name="insertApt" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div> 

  <div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t dark:border-gray-600">
          <button type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-toggle="defaultModal">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
            </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <div class="flex items-center justify-center w-full">
        <div class="w-[90%]">
          <div class="w-full mt-4 mb-6">            
            <h2 class="mb-2 text-xl font-semibold text-white">Client</h2>
            <span id="aptInsertStatus"></span>
            <div class="grid w-full grid-cols-2 bg-gray-700 rounded-md">
              <div class="flex items-center justify-center w-full">
                <button class="w-full text-center text-white bg-purple-600 rounded-md" id="eClient">Existing client</button>
              </div>
              <div class="flex items-center justify-center w-full">
                <button class="w-full text-center text-white rounded-md" id="nClient">New client</button>
              </div>
            </div>

            <div id="existingClient" class="block">
              <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                </div>
                <label class="block mt-4 text-sm">
                  <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <input type="text" autocomplete="off" id="search" name="clientPhoneSearch" placeholder="Client phone" class="w-full bg-transparent outline-none">
                  </div>
                </label>
                <span id="result" class="absolute z-20 w-full">
                  
                </span>                
              </div>
            </div>

            <div id="newClient" class="hidden">
              <label class="block mt-4 text-sm">
                <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                  <input type="text" name="clientName" id="clientName" placeholder="Client name" class="w-full bg-transparent outline-none">
                </div>
              </label>

              <label class="block mt-4 text-sm">
                <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                  <input type="text" name="clientPhone" id="clientPhoneNew" placeholder="Client phone" class="w-full bg-transparent outline-none">
                </div>
              </label>
            </div>

            <div class="w-full h-[2px] mt-6 bg-gray-700"></div>
            <h2 class="mt-2 text-xl font-semibold text-white">Service</h2>

            <label class="block mt-4 text-sm">
              <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <select id="serviceSel" class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option disabled selected>Select service</option>
                  <?php $bissView->showServiceSelect($businessData['id'], False); ?>
                </select>
              </div>
            </label>

            <label class="block mt-4 text-sm">
              <div class="block w-full mt-1 text-sm text-black cursor-pointer dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <select id="emp" disabled class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option disabled selected>Select employee</option>
                </select>
              </div>
            </label>

            <div class="w-full h-[2px] mt-6 bg-gray-700"></div>
            <h2 class="mt-2 text-xl font-semibold text-white">Date & hour</h2>

            <div class="grid w-full grid-cols-2">
              <div class="mt-4">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                      viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd"></path>
                    </svg>
                  </div>
                  <form method="post" autocomplete="off">
                    <div class="flex">
                      <input datepicker datepicker-buttons datepicker-format="mm-dd-yyyy" name="date" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-[90%] pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        id="appDate"
                        placeholder="Select date">
                    </div>
                  </form>
                </div>
              </div>
              <div class="flex justify-center items-center w-[90%] h-[70%] mt-4 text-white">
                <select id="hours" name="time" class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  
                </select>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center float-right p-6 space-x-2 border-gray-200 rounded-b dark:border-gray-600">
              <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
              <button id="aptInsert" name="insertApt" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="../static/js/clientSettings.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.1/dist/datepicker.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.2/dist/flowbite.js"></script>
  <script src="../static/js/requests.js" defer></script>

  </body>

  </html>