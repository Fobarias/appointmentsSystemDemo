<?php

    class businessView extends business {
        public function showServices($bID) {
            $result = $this->getServices($bID); 
            while($row = $result->fetch()) {
                if($row['maxPrice'] != '') {
                    $price = $row['minPrice'] . ' - ' . $row['maxPrice'];
                } else {
                    $price = $row['minPrice'];
                }

                $hours = ($row['time'] - $row['time'] % 60) / 60;
                if($row['time'] - $hours * 60 != 0) {
                    $time = $hours . 'h ' . $row['time'] % 60 . 'm';
                } else {
                    $time = $hours . 'h';
                }

                echo '<tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-sm">
                        '. $row['service_name'] .'
                    </td>
                    <td class="px-4 py-3 text-sm">
                        '. $price .'
                    </td>
                    <td class="px-4 py-3 text-sm">
                        '. $time .'
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-4 text-sm">
                            <a href="business.php?delete='. $row['id'] .'">
                                <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>';
            }
        }

        public function showServiceSelect($bID, $showPrice) {
            $result = $this->getServices($bID);
            if($showPrice == True) {
                while($row = $result->fetch()) {
                    if($row['maxPrice'] != '') {
                    echo '<option value="'. $row['id'] .'">'. $row['service_name'] .' | '. $row['minPrice'] .' - '. $row['maxPrice'] .'</option>';
                    } else {
                    echo '<option value="'. $row['id'] .'">'. $row['service_name'] .' | '. $row['minPrice'] .'</option>';
                    }
                } 
            } else {
                while($row = $result->fetch()) {
                    echo '<option value="'. $row['id'] .'">'. $row['service_name'] .' </option>';
                }
            }
        }

        public function showOnlyServices($bID) {
            $result = $this->getServices($bID); 
            while($row = $result->fetch()) {
                echo '<option value="'. $row['id'] .'">'. $row['service_name'] .'</option>';
            }
        }

        public function showEmployee($bID) {
            $result = $this->gattherEmployee($bID);
            while($row = $result->fetch()) {
                $result_user = $this->gattherAllFromPhone($row['uid']);
                while($rows = $result_user->fetch()) {
                    echo '<tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                            <!-- Avatar with inset shadow -->
                            <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                            <img class="object-cover w-full h-full rounded-full"
                                src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                alt="" loading="lazy" />
                            <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                            </div>
                            <div>
                            <p class="font-semibold">'. $rows['firstName'] .' '. $rows['lastName'] .'</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                '. $rows['phoneNumber'] .'
                            </p>
                            </div>
                        </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <button id="'. $rows['phoneNumber'] .'"
                                    onclick="getInfo(this.id)"
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                    type="button" data-modal-toggle="defaultModal">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>   
                                <a href="?delete='. $row['id'] .'">
                                    <button
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                    aria-label="Delete">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                    </svg>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>';
                } 
            }
        }

        public function displayEmployeeSelect($bID) {
            $result = $this->gattherEmployee($bID);
            while($row = $result->fetch()) {
                $result_user = $this->gattherAllFromPhone($row['uid']);
                while($rows = $result_user->fetch()) {
                    echo '<option value="'. $row['uid'] .'">'. $rows['firstName'] .' '. $rows['lastName'] .'</option>';
                }
            }
        }

        public function returnAllReviewCount($bID) {
            $result = $this->gattherReviews($bID);
            $totalReviews = 0;
            while($row = $result->fetch()) {
                $totalReviews += 1;
            }

            echo $totalReviews;
        }

        public function displayReviewInfo($bID) {
            $result = $this->gattherReviews($bID);
            $totalReviews = 0;
            $starsCount = 0;
            while($row = $result->fetch()) {
                $totalReviews += 1;
                $starsCount += $row['stars'];
            }

            if(!$starsCount == 0) {
                $starsRaw = number_format($starsCount / $totalReviews, 2);
                $lastDecimals = substr($starsRaw, -2);
            } else {
                $starsRaw = 0;
            }

            $m = '';

            if(($starsRaw - (int)$starsRaw) == 0) {
                if($starsCount != 0) {
                    $stars = $starsCount / $totalReviews;
                } else {
                    $stars = '-';
                }
            } elseif(($starsRaw - (int)$starsRaw) != 0) {
                number_format($starsRaw, 2);
                $lastDecimals = substr($starsRaw, -2);
                if($lastDecimals <= 50 && $lastDecimals > 0) {
                    $stars = (int)$starsRaw + 0.5;
                } elseif($lastDecimals > 50 && $lastDecimals <= 99) {
                    $stars = (int)$starsRaw + 1;
                }
            }
            echo '<h1 class="ml-3 text-xl font-bold">'. $stars .' <span class="ml-10">'. $totalReviews .' reviews</span></h1>';
        }

        public function displayReviews($bID) {
            $result = $this->gattherReviews($bID);
            $pageCount = 0;
            $pages = 1;
            while($row = $result->fetch()) {
                $pageCount += 1;
                echo '<div class="flex items-center justify-center hidden" id="hiddenPages page'. $pages .'">
                        <div class="flex items-center justify-start w-11/12" style="background-color: #232323 !important;">
                            <div class="py-6 pl-4"> 
                                <div class="flex">
                                    <p class="mr-2 text-lg font-semibold">'. $row['clientName'] .' | '. $row['stars'] .'</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="#fcba03">
                                        <path
                                            d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z" />
                                    </svg>
                                </div>
                                <p class="text-md">'. $row['review'] .'</p>
                            </div>
                        </div>
                    </div>';
                if($pageCount % 9 == 0) {
                    $pages++;
                }
            }
        }

        public function displayPagination($bID) {
            $result = $this->gattherReviews($bID);
            $totalReviews = 0;
            while($row = $result->fetch()) {
                $totalReviews += 1;
            }

            $pages = $totalReviews / 9;
            if($totalReviews % 9 != 0) {
                $pages++;
            }
            
            for($i = 1; $i <= $pages; $i++) {
                echo '<li>
                    <p href="#" onclick="displayReviewPage(this.id)" id="display-page-'. $i .'" class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 hover:text-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white" style="background-color: #232323 !important">'. $i .'</p>
                </li>';
            }
        }
        
        public function displayGallery($bID) {
            $galleryArray = $this->gattherBusinessGallery($bID);
            $galleryInfo  = array();
            while($row = $galleryArray->fetch()) {
                $galleryInfo[$row['imageNo']] = $row['imageName'];
            }

            for($i = 1; $i <= 8; $i++) {
                echo '<div class="box">';
                if(isset($galleryInfo[$i])) {
                    echo '<div class="js--image-preview" style="background-image: url(\'../static/img/businessImg/'. $galleryInfo[$i] .'\')"></div>';
                } else {
                    echo '<div class="js--image-preview" style="background-image: url(\'../static/img/businessImg/imageGalleryMissing.jpg\')"></div>';
                } 
                echo'<div class="upload-options dark:bg-gray-700 dark:hover:bg-gray-800">
                    <label>
                        <input type="file" id="image'. $i .'" name="image'. $i .'" class="image-upload" accept="image/*" />
                    </label>
                    </div>
                </div>';                    
            }

            
        }

        public function dispalyBusinessGalleryPublic($bID) {
            $galleryArray = $this->gattherBusinessGallery($bID);
            while($row = $galleryArray->fetch()) {
                echo '<div class="flex items-center justify-center col-span-1 duration-700 ease-in-out" data-carousel-item>
                    <div class="relative top-0 left-0">
                        <img src="static/img/businessImg/'. $row['imageName'] .'" alt=""
                            class="h-[250px] w-[395px] absolute z-10  -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover">
                        <img src="static/img/businessImg/'. $row['imageName'] .'" alt=""
                            class="h-[300px] w-[495px] relative blur-lg">
                    </div>
                </div>';
            }
        }

        public function galleryDots($bID) {
            $galleryArray = $this->gattherBusinessGallery($bID);
            $i = 0;
            while($row = $galleryArray->fetch()) {
                echo '<button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide '. $row['imageNo'] .'"data-carousel-slide-to="'. $i .'"></button>';
                $i++;
            }
        }

        public function displayTotalOfClients($bID) {
            $totalClientsRaw = $this->gattherAllClients($bID);
            $i = 0;
            while($row = $totalClientsRaw->fetch()) {
                $i++;
            }

            echo $i;
        }

        public function freeDays($bID) {
            $freeDays = $this->selectFreeDays($bID);
            while($row = $freeDays->fetch()) {
                $days = explode(' ', $row['days']);
                if(in_array('mon', $days)) {
                    $mon = '<input id="vue-checkbox-list" type="checkbox" checked name="mon" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $mon = '<input id="vue-checkbox-list" type="checkbox" name="mon" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('tue', $days)) {
                    $tue = '<input id="vue-checkbox-list" type="checkbox" checked name="tue" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $tue = '<input id="vue-checkbox-list" type="checkbox" name="tue" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('wed', $days)) {
                    $wed = '<input id="vue-checkbox-list" type="checkbox" checked name="wed" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $wed = '<input id="vue-checkbox-list" type="checkbox" name="wed" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('thu', $days)) {
                    $thu = '<input id="vue-checkbox-list" type="checkbox" checked name="thu" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $thu = '<input id="vue-checkbox-list" type="checkbox" name="thu" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('fri', $days)) {
                    $fri = '<input id="vue-checkbox-list" type="checkbox" checked name="fri" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $fri = '<input id="vue-checkbox-list" type="checkbox" name="fri" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('sat', $days)) {
                    $sat = '<input id="vue-checkbox-list" type="checkbox" checked name="sat" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $sat = '<input id="vue-checkbox-list" type="checkbox" name="sat" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

                if(in_array('sun', $days)) {
                    $sun = '<input id="vue-checkbox-list" type="checkbox" checked name="sun" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                } else {
                    $sun = '<input id="vue-checkbox-list" type="checkbox" name="sun" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">';
                }

            }
            echo '
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $mon .'
                    <label for="vue-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Monday</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $thu .'
                    <label for="react-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Tuesday</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $wed .'
                    <label for="angular-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Wednesday</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $thu .'
                    <label for="laravel-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Thursday</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $fri .'
                    <label for="laravel-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Friday</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $sat .'
                    <label for="laravel-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Saturday</label>
                </div>
            </li>
            <li class="w-full dark:border-gray-600">
                <div class="flex items-center pl-3">
                    '. $sun .'
                    <label for="laravel-checkbox-list" class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Sunday</label>
                </div>
            </li>
            ';
        }
    }

?>