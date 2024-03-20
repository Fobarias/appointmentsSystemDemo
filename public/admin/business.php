<?php 

  include('../model/autoload_extra.php');
  include('../systems/login.php');
  include('../systems/saveDate.php');

  $userConn = new userController();
  $bissConn = new businessController();
  $bissView = new businessView();
  $userid   = $userConn->getUID(sha1($_COOKIE['LGSCCS']));

  $businessData = $bissConn->getBusinessData($userid);

  $deleteStatus = $_GET['delete'] ?? '';

  if($deleteStatus != '') {
    $bissConn->removeService($deleteStatus, $businessData['id']);
  }

  $openTime  = $businessData['openTime'];
  $closeTime = $businessData['closeTime'];

  include('header.php'); 
?>

      <link rel="stylesheet" href="../static/css/imageGallery.css">
      <main class="h-full pb-16 overflow-y-auto">
        <div class="container grid px-6 mx-auto">
          <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Business
          </h2>

          <!-- Featuare projects -->
          <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
            href="">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                </path>
              </svg>
              <span>We are working on our Artty Business package (more information soon)</span>
            </div>
            <span>View more &RightArrow;</span>
          </a>

          <!-- Business information -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Business information
          </h4>
          <form method="post">
            <div class="grid grid-cols-2 gap-4 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
              <div>
                  <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Name</span>
                    <input value="<?php if($businessData['name'] != '') { echo $businessData['name']; } ?>" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="businessName" placeholder="Marya's Makeup Saloon, LLC." />
                  </label>

                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Address</span>
                    <input value="<?php if($businessData['address'] != '') { echo $businessData['address']; } ?>" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="businessAddress" placeholder="43 Brookside Street, Forest Hills, NY 11375 " />
                  </label>

                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                      Business open time
                    </span>
                    <select value="<?php echo $businessData['openTime'] ?>" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="openTime">
                      <?php 
                        if($openTime != '') {
                          echo "<option value='". $openTime ."' selected='selected'>". $openTime ."</option>";
                        }
                      ?>
                      <option>00:00</option>
                      <option>01:00</option>
                      <option>02:00</option>
                      <option>03:00</option>
                      <option>04:00</option>
                      <option>05:00</option>
                      <option>06:00</option>
                      <option>07:00</option>
                      <option>08:00</option>
                      <option>09:00</option>
                      <option>10:00</option>
                      <option>11:00</option>
                      <option>12:00</option>
                      <option>13:00</option>
                      <option>14:00</option>
                      <option>15:00</option>
                      <option>16:00</option>
                      <option>17:00</option>
                      <option>18:00</option>
                      <option>19:00</option>
                      <option>20:00</option>
                      <option>21:00</option>
                      <option>22:00</option>
                      <option>23:00</option>
                    </select>
                  </label>

                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                      Business close time
                    </span>
                    <select value="<?php echo $businessData['closeTime'] ?>" name="closeTime" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                      <?php 
                        if($closeTime != '') {
                          echo "<option value='". $closeTime ."' selected='selected'>". $closeTime ."</option>";
                        }
                      ?>
                      <option>00:00</option>
                      <option>01:00</option>
                      <option>02:00</option>
                      <option>03:00</option>
                      <option>04:00</option>
                      <option>05:00</option>
                      <option>06:00</option>
                      <option>07:00</option>
                      <option>08:00</option>
                      <option>09:00</option>
                      <option>10:00</option>
                      <option>11:00</option>
                      <option>12:00</option>
                      <option>13:00</option>
                      <option>14:00</option>
                      <option>15:00</option>
                      <option>16:00</option>
                      <option>17:00</option>
                      <option>18:00</option>
                      <option>19:00</option>
                      <option>20:00</option>
                      <option>21:00</option>
                      <option>22:00</option>
                      <option>23:00</option>
                    </select>
                  </label>

                  <?php
                    if($bissConn->getBusinessSetup($userid) != 1) {
                      echo '<div class="flex mt-6 text-sm">
                        <label class="flex items-center dark:text-gray-400">
                          <input type="checkbox" name="agreeTerms" value="1" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                          <span class="ml-2">
                            I agree to the <span class="underline">privacy policy</span>
                          </span>
                        </label>
                      </div>';
                    }
                  ?>
              </div>
              <div>
                  <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Contry</span>
                    <input value="<?php if($businessData['country'] != '') { echo $businessData['country']; } ?>" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="businessCountry" placeholder="E.g United Kingdom" />
                  </label>

                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">City</span>
                    <input type="text" value="<?php if($businessData['city'] != '') { echo $businessData['city']; } ?>" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="businessCity" placeholder="London" />
                  </label>
                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                      Business Type
                    </span>
                    <select value="<?php echo $businessData['businessType'] ?>" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="businessType">
                      <?php 
                        if($openTime != '') {
                          echo "<option selected='selected'>". $businessData['businessType'] ."</option>";
                        }
                      ?>
                      <option>Medical clinic</option>
                      <option>Hairstyle</option>
                      <option>Barbershop</option>
                      <option>Nails</option>
                      <option>Makeup</option>
                      <option>Lashes</option>
                      <option>Tattoos</option>
                      <option>Spa</option>
                      <option>Others</option>
                    </select>
                  </label>

                  <?php
                    if($bissConn->getBusinessSetup($userid) != 1) {
                      echo '<div class="flex mt-6 text-sm">
                        <label class="flex items-center dark:text-gray-400">
                          <input type="checkbox" name="agreeTerms" value="1" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                          <span class="ml-2">
                            I agree to the <span class="underline">privacy policy</span>
                          </span>
                        </label>
                      </div>';
                    }
                  ?>
              </div>
              <div class="mt-2 col-span-2 h-32">
                <h3 class="text-gray-700 dark:text-gray-400 text-sm mb-1">Free days</h3>
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <?php $bissView->freeDays($businessData['id']); ?>
                </ul>

                <input value="Save" name="saveBusinessDetails" type="submit" class="px-4 py-2 mt-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">     
              </div>
            </div>
          </form>

          <!-- Gallery information -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Gallery
          </h4>
          <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="post" enctype="multipart/form-data">
              <div class="flex items-center justify-center">
                <div class="grid w-[90%] grid-cols-4 gap-4">
                  <?php $bissView->displayGallery($businessData['id']); ?>
                </div>
                <input type="submit" name="saveGallery" value="Save gallery" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              </div>
            </form>   
          </div>
           

          <!-- Services information -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Services provided
          </h4>
          <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

            <div class="mt-4">
              <button @click="openModal"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Add your services
              </button>
            </div>

            <div class="w-full overflow-x-auto">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr
                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3">Service provided</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Time</th>
                    <th class="px-4 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                  <?php $bissView->showServices($businessData['id']); ?>
                </tbody>
              </table>
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
            <span class="text-gray-700 dark:text-gray-400">Service name</span>
            <input name="services" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Hairstyle" />
          </label>

          <label class="block mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Default price</span>
            <input name="minPrice" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="60 RON" />
          </label>
          <div class="hidden" id="maxPrice">
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Maximum price</span>
              <input name="maxPrice" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="120 RON" />
            </label>
          </div>
          <label class="flex items-center mt-4 dark:text-gray-400">
            <input type="checkbox" id="fixedPrice" checked name="checkPrice" value="1" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
            <span class="ml-2">
              It's a fixed <span class="underline">price</span>
            </span>
          </label>

          <label class="block mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Time</span><br>
            <div class="grid grid-cols-2">
              <select name="timeHours" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-[95%] p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?php 
                  for($x = 0; $x <= 23; $x++) {
                    echo '<option value='. $x * 60 .'>'. $x .'h</option>' ;
                  }
                ?>
              </select>
              <select name="timeMin" class="px-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?php 
                  for($i = 0; $i <= 11; $i++) {
                    if($i == 1) {
                      echo '<option class="bg-transparent" selected value='. $i * 5 .'>'. $i * 5 .'m</option>' ; 
                    }
                    echo '<option class="bg-transparent" value='. $i * 5 .'>'. $i * 5 .'m</option>' ;
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
          <input value="Accept" name="saveServices" type="submit" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        </footer>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="../static/js/fixedPrice.js"></script>
  <script src="../static/js/previewImage.js"></script>
  <script src="../static/js/requests.js" defer></script>


  
</body>

</html>