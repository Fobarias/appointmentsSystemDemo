<!DOCTYPE html>
<?php

    include('model/autoload.php');
    include('systems/login.php');
    include('systems/saveDate.php');

    //include('../systems/ajaxRequest.php');

    $userConn = new userController();
    $bissConn = new businessController();
    $bissView = new businessView();
    $apntView = new appointmentsView();

    $businessID = $_GET['businessID'];

    $businessInfo = $bissConn->getBusinessDataID($businessID);

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
    }
    $openHour = $busClose - $busOpen;

    $bookConf = $_GET['bookConf'] ?? '';

?>


<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="static/css/tailwind.css">
    <link rel="stylesheet" href="static/css/selectWithSearch.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css">

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="static/css/business.css">

    <title><?php echo $businessInfo['name']; ?> | Appointment</title>
</head>

<?php
    if($bookConf == 1) {
        echo '<div id="info-popup" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-lg p-4 md:h-auto">
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 md:p-8">
                <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="successModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                    <svg aria-hidden="true" class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Success</span>
                </div>
                <p class="mb-4 text-lg text-center mt-3 font-semibold text-gray-900 dark:text-white">Booked successfully.</p>
                <div class="items-center justify-between pt-0 space-y-4 sm:flex sm:space-y-0">
                    <div class="justify-end w-full space-y-4 sm:space-x-4 sm:flex sm:space-y-0">
                        <button id="confirm-button" type="button" class="float-right w-full px-4 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-auto hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
      </div>';
    }
?>

<body class="bg-[#1C1C1C] dark:text-white">
    <header class="h-16">
        <div class="w-full h-full">
            <h1 class="flex justify-center mt-5 text-3xl font-bold">LIMSY</h1>
        </div>
        <div class="absolute top-0 right-0 mt-5 mr-10 cursor-pointer">
            <svg width="23" height="46" viewBox="0 0 23 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="3" height="41" fill="#D9D9D9" />
                <rect x="10" width="3" height="35" fill="#D9D9D9" />
                <rect x="20" width="3" height="46" fill="#D9D9D9" />
            </svg>
        </div>
    </header>

    <section class="block w-4/6 h-auto py-10 mx-auto mt-10 shadowCard">
        <div class="grid grid-cols-2">
            <div id="indicators-carousel" class="relative" data-carousel="static">
                <!-- Carousel wrapper -->
                <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                    <?php $bissView->dispalyBusinessGalleryPublic($businessID); ?>
                </div>
                <!-- Slider indicators -->
                <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                    <?php $bissView->galleryDots($businessID); ?>
                </div>
                <!-- Slider controls -->
                <button type="button"
                    class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer ml-[50px] group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-white sm:w-6 sm:h-6 dark:text-gray-800" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none mr-[50px]"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-white sm:w-6 sm:h-6 dark:text-gray-800" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
            <div class="w-full grid-rows-4">
                <h1 class="flex justify-center text-2xl font-bold"><?php echo $businessInfo['name']; ?></h1>
                <div class="flex justify-center mt-10">
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="#59009F">
                            <path
                                d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z" />
                        </svg>
                        <?php $bissView->displayReviewInfo($businessID); ?>
                    </div>
                </div>
                <div class="flex items-center justify-center mt-10 span-rows-2">
                    <div>
                        <img src="static/img/business/mapExemple.png" alt="">
                        <div id="map_canvas"></div>
                        <div id='map'></div>
                        <div class="flex items-center justify-center mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="#59009F">
                                <path
                                    d="M12 0c-3.148 0-6 2.553-6 5.702 0 3.148 2.602 6.907 6 12.298 3.398-5.391 6-9.15 6-12.298 0-3.149-2.851-5.702-6-5.702zm0 8c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm4 14.5c0 .828-1.79 1.5-4 1.5s-4-.672-4-1.5 1.79-1.5 4-1.5 4 .672 4 1.5z" />
                            </svg>
                            <h1 class="font-semibold text-md"><?php echo $businessInfo['address']; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="block w-4/6 h-auto py-10 mx-auto mt-10 mb-10 shadowCard">
        <h1 class="mb-5 text-2xl font-bold text-center">Book an appointment</h1>
        <form method="post">
            <div class="grid grid-cols-2">
                <div class="flex justify-center">
                    <div class="inline-grid pl-7 w-[70%]">
                        <p class="mb-2 font-bold text-md">Service information</p>
                        <select name="service" id="serviceSel" class="mb-1 rounded-md bg-[#232323] border-none">
                            <option disabled selected>Select a service</option>
                            <?php $bissView->showServiceSelect($businessID, True); ?>
                        </select>

                        <select name="employee" id="emp" disabled class="mb-1 rounded-md bg-[#232323] border-none">
                            <option disabled selected>Select a employee</option>
                        </select>

                        <p class="pt-3 mb-2 font-bold text-md">Appointment information</p>
                        <div class="flex justify-between">
                            <input datepicker name="date" autocomplete="off" id="appDate" datepicker-format="mm-dd-yyyy"
                                datepicker-autohide datepicker-orientation="top" type="text" placeholder="Select date"
                                class="mb-1 text-white placeholder-white border-none rounded-md"
                                style="background-color: #232323 !important">
                            <select name="hour" id="hours"
                                class="mb-1 rounded-md bg-[#232323] border-none w-[50%] ml-2">
                                <option disabled selected>Select a date</option>`
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="pl-7 w-[70%]">
                        <p class="mb-2 font-bold text-md">Client information</p>
                        <input type="text" name="name" placeholder="Name*"
                            class="w-full mb-1 text-white placeholder-white border-none rounded-md"
                            style="background-color: #232323 !important">
                        <input type="text" name="phoneNumber" placeholder="Phone number*"
                            class="w-full mb-1 text-white placeholder-white border-none rounded-md"
                            style="background-color: #232323 !important">
                        <input type="text" name="email" placeholder="Email"
                            class="w-full mb-1 text-white placeholder-white border-none rounded-md"
                            style="background-color: #232323 !important">
                        <input type="text" name="address" placeholder="Address"
                            class="w-full mb-1 text-white placeholder-white border-none rounded-md"
                            style="background-color: #232323 !important">
                        <input type="submit" name="bookNow" value="Book now"
                            class="float-right px-4 py-2 mt-2 duration-200 bg-transparent border-2 border-gray-600 rounded-md cursor-pointer hover:bg-white hover:text-black">
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section class="block w-4/6 h-auto py-10 mx-auto mt-10 mb-10 shadowCard">
        <h1 class="mb-5 ml-10 text-2xl font-bold text-center">Reviews</h1>
        <form method="post">
            <div class="grid grid-cols-2 grid-rows-1">
                <div class="flex items-center justify-center">
                    <div class="grid w-2/3 grid-cols-1 h-[210px]">
                        <input type="text" name="clientNameReview" placeholder="Client name" class="w-full mb-2 text-white placeholder-white border-none rounded-md" style="background-color: #232323 !important">
                        <textarea name="reviewMessage" cols="30" rows="5" class="w-full mb-2 text-white placeholder-white border-none rounded-md resize-none" placeholder="Review message" style="background-color: #232323 !important" style="resize: none !important; background-color: #232323 !important;" ></textarea>
                        <input type="submit" value="Submit" class="float-right w-1/3 px-4 py-2 mt-2 duration-200 bg-transparent border-2 border-gray-600 rounded-md cursor-pointer hover:bg-white hover:text-black" name="subReview">
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="feedback">
                        <div class="rating">
                        <input type="radio" name="rating" id="rating-5" value="star5">
                        <label for="rating-5"></label>
                        <input type="radio" name="rating" id="rating-4" value="star4">
                        <label for="rating-4"></label>
                        <input type="radio" name="rating" id="rating-3" value="star3">
                        <label for="rating-3"></label>
                        <input type="radio" name="rating" id="rating-2" value="star2">
                        <label for="rating-2"></label>
                        <input type="radio" name="rating" id="rating-1" value="star1">
                        <label for="rating-1"></label>
                        <div class="emoji-wrapper">
                            <div class="emoji">
                                <svg class="rating-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <circle cx="256" cy="256" r="256" fill="#ffd93b" />
                                    <path
                                        d="M512 256c0 141.44-114.64 256-256 256-80.48 0-152.32-37.12-199.28-95.28 43.92 35.52 99.84 56.72 160.72 56.72 141.36 0 256-114.56 256-256 0-60.88-21.2-116.8-56.72-160.72C474.8 103.68 512 175.52 512 256z"
                                        fill="#f4c534" />
                                    <ellipse transform="scale(-1) rotate(31.21 715.433 -595.455)" cx="166.318"
                                        cy="199.829" rx="56.146" ry="56.13" fill="#fff" />
                                    <ellipse transform="rotate(-148.804 180.87 175.82)" cx="180.871" cy="175.822"
                                        rx="28.048" ry="28.08" fill="#3e4347" />
                                    <ellipse transform="rotate(-113.778 194.434 165.995)" cx="194.433" cy="165.993"
                                        rx="8.016" ry="5.296" fill="#5a5f63" />
                                    <ellipse transform="scale(-1) rotate(31.21 715.397 -1237.664)" cx="345.695"
                                        cy="199.819" rx="56.146" ry="56.13" fill="#fff" />
                                    <ellipse transform="rotate(-148.804 360.25 175.837)" cx="360.252" cy="175.84"
                                        rx="28.048" ry="28.08" fill="#3e4347" />
                                    <ellipse transform="scale(-1) rotate(66.227 254.508 -573.138)" cx="373.794"
                                        cy="165.987" rx="8.016" ry="5.296" fill="#5a5f63" />
                                    <path
                                        d="M370.56 344.4c0 7.696-6.224 13.92-13.92 13.92H155.36c-7.616 0-13.92-6.224-13.92-13.92s6.304-13.92 13.92-13.92h201.296c7.696.016 13.904 6.224 13.904 13.92z"
                                        fill="#3e4347" />
                                </svg>
                                <svg class="rating-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <circle cx="256" cy="256" r="256" fill="#ffd93b" />
                                    <path
                                        d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                        fill="#f4c534" />
                                    <path
                                        d="M328.4 428a92.8 92.8 0 0 0-145-.1 6.8 6.8 0 0 1-12-5.8 86.6 86.6 0 0 1 84.5-69 86.6 86.6 0 0 1 84.7 69.8c1.3 6.9-7.7 10.6-12.2 5.1z"
                                        fill="#3e4347" />
                                    <path
                                        d="M269.2 222.3c5.3 62.8 52 113.9 104.8 113.9 52.3 0 90.8-51.1 85.6-113.9-2-25-10.8-47.9-23.7-66.7-4.1-6.1-12.2-8-18.5-4.2a111.8 111.8 0 0 1-60.1 16.2c-22.8 0-42.1-5.6-57.8-14.8-6.8-4-15.4-1.5-18.9 5.4-9 18.2-13.2 40.3-11.4 64.1z"
                                        fill="#f4c534" />
                                    <path
                                        d="M357 189.5c25.8 0 47-7.1 63.7-18.7 10 14.6 17 32.1 18.7 51.6 4 49.6-26.1 89.7-67.5 89.7-41.6 0-78.4-40.1-82.5-89.7A95 95 0 0 1 298 174c16 9.7 35.6 15.5 59 15.5z"
                                        fill="#fff" />
                                    <path
                                        d="M396.2 246.1a38.5 38.5 0 0 1-38.7 38.6 38.5 38.5 0 0 1-38.6-38.6 38.6 38.6 0 1 1 77.3 0z"
                                        fill="#3e4347" />
                                    <path
                                        d="M380.4 241.1c-3.2 3.2-9.9 1.7-14.9-3.2-4.8-4.8-6.2-11.5-3-14.7 3.3-3.4 10-2 14.9 2.9 4.9 5 6.4 11.7 3 15z"
                                        fill="#fff" />
                                    <path
                                        d="M242.8 222.3c-5.3 62.8-52 113.9-104.8 113.9-52.3 0-90.8-51.1-85.6-113.9 2-25 10.8-47.9 23.7-66.7 4.1-6.1 12.2-8 18.5-4.2 16.2 10.1 36.2 16.2 60.1 16.2 22.8 0 42.1-5.6 57.8-14.8 6.8-4 15.4-1.5 18.9 5.4 9 18.2 13.2 40.3 11.4 64.1z"
                                        fill="#f4c534" />
                                    <path
                                        d="M155 189.5c-25.8 0-47-7.1-63.7-18.7-10 14.6-17 32.1-18.7 51.6-4 49.6 26.1 89.7 67.5 89.7 41.6 0 78.4-40.1 82.5-89.7A95 95 0 0 0 214 174c-16 9.7-35.6 15.5-59 15.5z"
                                        fill="#fff" />
                                    <path
                                        d="M115.8 246.1a38.5 38.5 0 0 0 38.7 38.6 38.5 38.5 0 0 0 38.6-38.6 38.6 38.6 0 1 0-77.3 0z"
                                        fill="#3e4347" />
                                    <path
                                        d="M131.6 241.1c3.2 3.2 9.9 1.7 14.9-3.2 4.8-4.8 6.2-11.5 3-14.7-3.3-3.4-10-2-14.9 2.9-4.9 5-6.4 11.7-3 15z"
                                        fill="#fff" />
                                </svg>
                                <svg class="rating-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <circle cx="256" cy="256" r="256" fill="#ffd93b" />
                                    <path
                                        d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                        fill="#f4c534" />
                                    <path
                                        d="M336.6 403.2c-6.5 8-16 10-25.5 5.2a117.6 117.6 0 0 0-110.2 0c-9.4 4.9-19 3.3-25.6-4.6-6.5-7.7-4.7-21.1 8.4-28 45.1-24 99.5-24 144.6 0 13 7 14.8 19.7 8.3 27.4z"
                                        fill="#3e4347" />
                                    <path d="M276.6 244.3a79.3 79.3 0 1 1 158.8 0 79.5 79.5 0 1 1-158.8 0z"
                                        fill="#fff" />
                                    <circle cx="340" cy="260.4" r="36.2" fill="#3e4347" />
                                    <g fill="#fff">
                                        <ellipse transform="rotate(-135 326.4 246.6)" cx="326.4" cy="246.6" rx="6.5"
                                            ry="10" />
                                        <path d="M231.9 244.3a79.3 79.3 0 1 0-158.8 0 79.5 79.5 0 1 0 158.8 0z" />
                                    </g>
                                    <circle cx="168.5" cy="260.4" r="36.2" fill="#3e4347" />
                                    <ellipse transform="rotate(-135 182.1 246.7)" cx="182.1" cy="246.7" rx="10" ry="6.5"
                                        fill="#fff" />
                                </svg>
                                <svg class="rating-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <circle cx="256" cy="256" r="256" fill="#ffd93b" />
                                    <path
                                        d="M407.7 352.8a163.9 163.9 0 0 1-303.5 0c-2.3-5.5 1.5-12 7.5-13.2a780.8 780.8 0 0 1 288.4 0c6 1.2 9.9 7.7 7.6 13.2z"
                                        fill="#3e4347" />
                                    <path
                                        d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                        fill="#f4c534" />
                                    <g fill="#fff">
                                        <path
                                            d="M115.3 339c18.2 29.6 75.1 32.8 143.1 32.8 67.1 0 124.2-3.2 143.2-31.6l-1.5-.6a780.6 780.6 0 0 0-284.8-.6z" />
                                        <ellipse cx="356.4" cy="205.3" rx="81.1" ry="81" />
                                    </g>
                                    <ellipse cx="356.4" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347" />
                                    <g fill="#fff">
                                        <ellipse transform="scale(-1) rotate(45 454 -906)" cx="375.3" cy="188.1" rx="12"
                                            ry="8.1" />
                                        <ellipse cx="155.6" cy="205.3" rx="81.1" ry="81" />
                                    </g>
                                    <ellipse cx="155.6" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347" />
                                    <ellipse transform="scale(-1) rotate(45 454 -421.3)" cx="174.5" cy="188" rx="12"
                                        ry="8.1" fill="#fff" />
                                </svg>
                                <svg class="rating-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <circle cx="256" cy="256" r="256" fill="#ffd93b" />
                                    <path
                                        d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                        fill="#f4c534" />
                                    <path
                                        d="M232.3 201.3c0 49.2-74.3 94.2-74.3 94.2s-74.4-45-74.4-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z"
                                        fill="#e24b4b" />
                                    <path
                                        d="M96.1 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2C80.2 229.8 95.6 175.2 96 173.3z"
                                        fill="#d03f3f" />
                                    <path
                                        d="M215.2 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z"
                                        fill="#fff" />
                                    <path
                                        d="M428.4 201.3c0 49.2-74.4 94.2-74.4 94.2s-74.3-45-74.3-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z"
                                        fill="#e24b4b" />
                                    <path
                                        d="M292.2 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2-77.8-65.7-62.4-120.3-61.9-122.2z"
                                        fill="#d03f3f" />
                                    <path
                                        d="M411.3 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z"
                                        fill="#fff" />
                                    <path
                                        d="M381.7 374.1c-30.2 35.9-75.3 64.4-125.7 64.4s-95.4-28.5-125.8-64.2a17.6 17.6 0 0 1 16.5-28.7 627.7 627.7 0 0 0 218.7-.1c16.2-2.7 27 16.1 16.3 28.6z"
                                        fill="#3e4347" />
                                    <path
                                        d="M256 438.5c25.7 0 50-7.5 71.7-19.5-9-33.7-40.7-43.3-62.6-31.7-29.7 15.8-62.8-4.7-75.6 34.3 20.3 10.4 42.8 17 66.5 17z"
                                        fill="#e24b4b" />
                                </svg>
                                <svg class="rating-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <g fill="#ffd93b">
                                        <circle cx="256" cy="256" r="256" />
                                        <path
                                            d="M512 256A256 256 0 0 1 56.8 416.7a256 256 0 0 0 360-360c58 47 95.2 118.8 95.2 199.3z" />
                                    </g>
                                    <path
                                        d="M512 99.4v165.1c0 11-8.9 19.9-19.7 19.9h-187c-13 0-23.5-10.5-23.5-23.5v-21.3c0-12.9-8.9-24.8-21.6-26.7-16.2-2.5-30 10-30 25.5V261c0 13-10.5 23.5-23.5 23.5h-187A19.7 19.7 0 0 1 0 264.7V99.4c0-10.9 8.8-19.7 19.7-19.7h472.6c10.8 0 19.7 8.7 19.7 19.7z"
                                        fill="#e9eff4" />
                                    <path
                                        d="M204.6 138v88.2a23 23 0 0 1-23 23H58.2a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z"
                                        fill="#45cbea" />
                                    <path
                                        d="M476.9 138v88.2a23 23 0 0 1-23 23H330.3a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z"
                                        fill="#e84d88" />
                                    <g fill="#38c0dc">
                                        <path
                                            d="M95.2 114.9l-60 60v15.2l75.2-75.2zM123.3 114.9L35.1 203v23.2c0 1.8.3 3.7.7 5.4l116.8-116.7h-29.3z" />
                                    </g>
                                    <g fill="#d23f77">
                                        <path
                                            d="M373.3 114.9l-66 66V196l81.3-81.2zM401.5 114.9l-94.1 94v17.3c0 3.5.8 6.8 2.2 9.8l121.1-121.1h-29.2z" />
                                    </g>
                                    <path
                                        d="M329.5 395.2c0 44.7-33 81-73.4 81-40.7 0-73.5-36.3-73.5-81s32.8-81 73.5-81c40.5 0 73.4 36.3 73.4 81z"
                                        fill="#3e4347" />
                                    <path
                                        d="M256 476.2a70 70 0 0 0 53.3-25.5 34.6 34.6 0 0 0-58-25 34.4 34.4 0 0 0-47.8 26 69.9 69.9 0 0 0 52.6 24.5z"
                                        fill="#e24b4b" />
                                    <path
                                        d="M290.3 434.8c-1 3.4-5.8 5.2-11 3.9s-8.4-5.1-7.4-8.7c.8-3.3 5.7-5 10.7-3.8 5.1 1.4 8.5 5.3 7.7 8.6z"
                                        fill="#fff" opacity=".2" />
                                </svg>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
        <div class="grid grid-cols-3 gap-1 mt-10">
           <?php $bissView->displayReviews($businessID); ?>       
        </div>
        <div class="flex items-center justify-center w-full mt-10">
            <nav aria-label="Page navigation example">
                <ul class="inline-flex items-center -space-x-px">
                    <li>
                        <p onclick="displayReviewPage('prev')" class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:text-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white" style="background-color: #232323 !important"> 
                            <span class="sr-only">Previous</span>
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </p>
                    </li>
                    
                    <?php $bissView->displayPagination($businessID); ?>
                    
                    <!-- background-color: #1f1e1e !important -->
                    
                    
                    <li>
                        <p onclick="displayReviewPage('next')" class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:text-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white" style="background-color: #232323 !important">
                            <span class="sr-only">Next</span>
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        </p>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <script>
        $(document).ready(function () {

            var dateSelected = $('#appDate').val();
            var servSelected = $('#serviceSel').val();

            setInterval(function () {

                var newDate = $('#appDate').val();
                var newServ = $('#serviceSel').val();
                if (newDate != dateSelected || newServ != servSelected) {
                    if (newDate != dateSelected) dateSelected = newDate;
                    if (newServ != servSelected) servSelected = newServ;

                    changeHours(dateSelected, servSelected);
                }

            }, 100);

            function changeHours(dateSelected, servSelected) {


                $.ajax({
                    url: 'systems/ajaxRequest.php' + window.location.search,
                    type: 'post',
                    data: {
                        requestID: 2,
                        empSel: $("#emp").val(),
                        servID: servSelected,
                        date: dateSelected,
                    },
                    success: function (result) {
                        $("#hours").html(result);
                    }
                })
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('*[id*=page1]').each(function() {
                $(this).removeClass('hidden');
            });
        });

        pageNumb = 1;

        function displayReviewPage(page) {
            totalReviews = <?php $bissView->returnAllReviewCount($businessID) ?>;
            pages = Math.ceil(totalReviews / 9);

            if(page == 'prev') {
                if(pageNumb > 1) {
                    pageNumb -= 1;

                    $('*[id*=hiddenPages]').each(function() {
                        $(this).addClass('hidden');
                    });

                    $('*[id*=page'+ pageNumb +']').each(function() {
                        $(this).removeClass('hidden');
                    });                    
                }
            } else if(page == 'next') {
                pageNumb += 1;
                
                if(pageNumb <= pages) {
                    $('*[id*=hiddenPages]').each(function() {
                    $(this).addClass('hidden');
                    });

                    $('*[id*=page'+ pageNumb +']').each(function() {
                        $(this).removeClass('hidden');
                    });
                } else {
                    pageNumb = pageNumb - (pageNumb - pages);
                }
            } else {
                const pageArray = page.split("-");
                pageNumb = parseInt(pageArray[2]);

                $('*[id*=hiddenPages]').each(function() {
                    $(this).addClass('hidden');
                });

                $('*[id*='+ pageNumb +']').each(function() {
                    $(this).removeClass('hidden');
                });
            }
        }
    
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.1/dist/datepicker.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
    <script defer>
        const modalEl = document.getElementById("info-popup");
        const privacyModal = new Modal(modalEl, {
            placement: "center"
        });
        
        privacyModal.show();
        
        const acceptPrivacyEl = document.getElementById("confirm-button");
        acceptPrivacyEl.addEventListener("click", function() {
            privacyModal.hide();
        });
    </script>
    <script src="static/js/requests.js" defer></script>
</body>

</html>