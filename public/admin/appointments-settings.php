<?php 
  include('../model/autoload_extra.php');
  include('../systems/login.php');
  include('../systems/saveDate.php');

  require '../../vendor/autoload.php';
                
  $userConn = new userController();
  $bissConn = new businessController();
  $bissView = new businessView();
  $apntConn = new appointmentsController();
  $userid   = $userConn->getUID(sha1($_COOKIE['LGSCCS'])) ?? '';

  $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
  $arrRegions = $phoneUtil->getSupportedRegions();
  sort($arrRegions);

  $businessData = $bissConn->getBusinessData($userid);

  $deleteStatus = $_GET['delete'] ?? '';

  if($deleteStatus != '') {
    $bissConn->removeEmployeeServices($businessData['id'], $bissConn->getEmployeeID($deleteStatus));
    $bissConn->removeEmployee($deleteStatus, $businessData['id']);
    echo '<script>
      window.location.replace("appointments-settings.php");
    </script>'; 
  }

  include('header.php'); 

?>

<link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.2/dist/flowbite.min.css" />  
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <main class="h-full overflow-y-auto">
      <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
          Appoiments settings
        </h2>
        <!-- CTA -->
        <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple" href="">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
              </path>
            </svg>
            <span>We are working on our Artty Human Resources package (more information soon)</span>
          </div>
          <span>View more &RightArrow;</span>
        </a>

        <!-- Appoiments settings -->
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
          Appoiments settings
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form method="post">
            <label class="block mt-4 text-sm">
                <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                  <input type="checkbox"
                    class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" <?php if($apntConn->getClientAddr($businessData['id']) == 1) { echo 'checked'; } ?>
                    name="address" value="1"/>
                  <span class="ml-2">Do you need your client's address?</span>
                </label><br>
                
                <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                  <input type="checkbox" value="1"
                    class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" <?php if($apntConn->getEmpChoose($businessData['id']) == 1) { echo 'checked'; } ?>
                    name="emSelect" />
                  <span class="ml-2">Would you like to let your client chose your employee?</span>
                </label><br>
            </label>
            
            <input type="submit" name="saveAppData" value="Save" class="px-4 py-2 mt-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          </form>
        </div>

        <!-- Button sizes -->
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
          Employee information
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <div class="max-w-2xl px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <p class="mb-4 text-gray-600 dark:text-gray-400">
              We made it <strong>simple!</strong> Add a new employee by introducing his name and phone number and the service it provides.
              Once you do, you will see it in your employee list. By pressing on the "eye" icon you will get its login information and Artty ID.
            </p>

            <p class="text-gray-600 dark:text-gray-400">
              Encourage your employees to create setup a Artty ID based on the information provided.
            </p>
          </div>

          <div>
            <button @click="openModal"
              class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Add employee
            </button>
          </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
              <thead>
                <tr
                  class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3">Employee</th>
                  <th class="px-4 py-3">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <?php $bissView->showEmployee($businessData['id']); ?>
              </tbody>
            </table>
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
      x-transition:leave-end="opacity-0  transform translate-y-1/2" @click.away="closeModal"
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

      <form method="post">
        <div class="mt-4 mb-6">
          <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
            Services provided
          </p>
          <p class="text-sm text-gray-700 dark:text-gray-400">
            Add your services provided for your appoiments
          </p>
          <label class="block mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Employee name</span>
            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Johnny Doe" />
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">
              Button left
            </span>
            <div class="relative">
              <input name="phoneInput" class="block w-full pl-20 mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                placeholder="Phone Number" />
              <select name="countryCode" class="absolute inset-y-0 px-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-l-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <?php

                  foreach($arrRegions as $region) {
                    echo '<option value="'. $region .'">
                      +'. $phoneUtil->getCountryCodeForRegion($region) .'
                    </option>';
                  }

                ?>
              </select>
            </div>
          </label>

        </div>
        <footer
          class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
          <button @click="closeModal"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <input id="submit" value="Accept" name="addEmployee" type="submit" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        </footer>
      </form>
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
            <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
              <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                  </path>
              </svg>
              <span>This is a beta function of our Artty Human Resources, further information will be release on the beginning of AHR development.</span>
              </div>
            </a>
            <span id="result"></span>
            <div class="px-2">
                    <label class="block mb-2 text-sm font-medium text-white-900 dark:text-gray-400">Job attributes</label>
                    <button type="button" id="modifyAttr" onclick="getAttr(phoneNumber)" data-modal-toggle="jobAttribute" class="openAttr bg-purple-600 border border-purple-600 text-white text-sm rounded-lg outline-none block w-full p-2.5 dark:bg-gray-700 dark:border-purple-600 dark:placeholder-gray-400 dark:text-white text-left">
                      Modify attributes
                    </button>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center float-right p-6 space-x-2 border-gray-200 rounded-b dark:border-gray-600">
              <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
              <button onclick="saveAllInfo(phoneNumber)" name="saveEmp" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div id="jobAttribute" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto bg-black bg-opacity-50 md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 rounded-t dark:border-gray-600">
          <button type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-toggle="jobAttribute">
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
            
            <span id="attr"></span>
            <span id="newResult"></span>
            
            
            <!-- Modal footer -->
            <div class="flex items-center float-right p-6 space-x-2 border-gray-200 rounded-b dark:border-gray-600">
              <span id="text12"></span> 
              <button data-modal-toggle="jobAttribute" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
              <button data-modal-toggle="jobAttribute" type="button" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">I accept</button>
            </div>
            
          </div>
        </div>
      </div>

    </div>
  </div>
  
  <script src="https://unpkg.com/flowbite@1.5.1/dist/datepicker.js"></script>
  <script src="https://unpkg.com/flowbite@1.5.2/dist/flowbite.js"></script>
  <script src="../static/js/requests.js"></script>
</body>

</html>