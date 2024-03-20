<?php

    if(is_file('../../vendor/autoload.php') == True) {
        require '../../vendor/autoload.php';
    } else {
        require '../vendor/autoload.php';
    }

    $userConn     = new userController();
    $bissConn     = new businessController();
    $apntConn     = new appointmentsController();
    
    if(isset($_COOKIE['LGSCCS'])) {
        $userid = $userConn->getUID(sha1($_COOKIE['LGSCCS']));
    } else {
        $userid = 0;
    }

    $businessData = $bissConn->getBusinessData($userid);
       
    $phoneUtil    = \libphonenumber\PhoneNumberUtil::getInstance();

    if(isset($_POST['saveBusinessDetails'])) {
        $businessName = $_POST['businessName'];
        $businessAddress = $_POST['businessAddress'];
        $openTime = $_POST['openTime'];
        $closeTime = $_POST['closeTime'];
        $country = $_POST['businessCountry'];
        $city = $_POST['businessCity'];
        $businessType = $_POST['businessType'];

        $freeDays = '';
        $day1 = 0;
        $day2 = 0;
        $day3 = 0;
        $day4 = 0;
        $day5 = 0;
        $day6 = 0;
        $day7 = 0;

        if(isset($_POST['mon'])) {
            $freeDays = $freeDays . ' mon';
        }
        if(isset($_POST['tue'])) {
            $freeDays = $freeDays . ' tue';
        }
        if(isset($_POST['wed'])) {
            $freeDays = $freeDays . ' wed';
        }
        if(isset($_POST['thu'])) {
            $freeDays = $freeDays . ' thu';
        }
        if(isset($_POST['fri'])) {
            $freeDays = $freeDays . ' fri';
        }
        if(isset($_POST['sat'])) {
            $freeDays = $freeDays . ' sat';
        }
        if(isset($_POST['sun'])) {
            $freeDays = $freeDays . ' sun';
        }

        if($freeDays != '') {
            $bissConn->saveFreeDays($businessData['id'], $freeDays);
        }

        if($bissConn->getBusinessSetup($userid) == 1) {
            $businessID = $bissConn->getBusinessID($userid, $businessData['name']);
       
            $bissConn->updateBusiness($businessName, $businessAddress, $openTime, $closeTime, $country, $city, $businessType, $businessID);
        } else {
            if($businessName != "" || $businessAddress != "") {
                if(isset($_POST['agreeTerms'])) {
                    $result = bin2hex(random_bytes(32));
            
                    $bissConn->saveDataBusiness($result, $userid, $businessName, $businessAddress, $openTime, $closeTime, $country, $city, $businessType);
                    $bissConn->updateBusSetup($userid);
                } else {
                    echo 'You have to agree with our terms';
                }
            } else {
                echo 'Empty space not allowed';
            }
        }
        
    }
    
    if(isset($_POST['saveServices'])) {
        $serviceName = $_POST['services'];
        $minPrice    = $_POST['minPrice'];      
        $hours       = $_POST['timeHours'];
        $min         = $_POST['timeMin'];
        $totalTime   = $hours + $min; 
    
        if($serviceName != '' || $minPrice != '') {
            if($hours != 0 && $min != 0) {
                if(!isset($_POST['checkPrice'])) {
                    $maxPrice = $_POST['maxPrice'];
                    if($bissConn->getServiceName($businessData['id'], $serviceName) == '' || $maxPrice != '') {
                        $bissConn->saveServicesPrice($businessData['id'], $serviceName, $minPrice, $maxPrice, $totalTime);
                    }
                } else {
                    if($bissConn->getServiceName($businessData['id'], $serviceName) == '') {
                        $bissConn->saveServicesMinPrice($businessData['id'], $serviceName, $minPrice, $totalTime);
                    }
                }
            } else {
                echo 'Hours and minutes can\'t be both zero.';
            }
        }
    }

    if(isset($_POST['addEmployee'])) {
        $name = $_POST['name'];
        $nameArray = explode(' ', $name);
        $firstName = $nameArray[0];
        $lastName  = '';
        $namesCount = count($nameArray);

        for($i = 1; $i <= $namesCount - 1; $i++) {
            $lastName .= ' ' . $nameArray[$i];
        }

        $number = $_POST['phoneInput'];
        $region = $_POST['countryCode'];
        
        $parseNumber = $phoneUtil->parse($number, $region);
    
        if($name != '' && $number != '') {
            if($phoneUtil->isValidNumber($parseNumber)) {
                $newPhone = $phoneUtil->format($parseNumber, \libphonenumber\PhoneNumberFormat::E164);
                if($bissConn->getPhoneNumber($newPhone) != '') {
                    if($bissConn->getUIDOfID($userConn->getUIDPhone($newPhone)) == '') {
                        $uid = $bissConn->getUIDFromPhone($newPhone);
                        $bissConn->saveEmployee($businessData['id'], $uid);
                    } else {
                        echo 'User already employeed';
                    }
                } else {
                    function passGenerator($lenght) {
                        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz!@#$%^&*';
                        return substr(str_shuffle($data), 0, $lenght);
                    }

                    $passToEnc = passGenerator(16);

                    $ciphering = "AES-128-CTR";
                    $iv_length = openssl_cipher_iv_length($ciphering);
                    $options   = 0;
                    $encry_iv  = '1234567891011121';
                    $key       = "ArttyAppointmentsKnowsAsLIMSYBITCH";
                    $pass      = openssl_encrypt($passToEnc, $ciphering, $key, $options, $encry_iv);
                    
                    $userConn->saveNewEmployee($firstName, $lastName, $newPhone, $pass);
                    $newEmpID  = $userConn->getUIDPhone($newPhone);
                    $bissConn->saveEmployee($businessData['id'], $newEmpID);                    
                }
            } else {
                echo 'Not valid';
            }
        } 
    }

    if(isset($_POST['selectDate'])) {
        date_default_timezone_set('Europe/Bucharest');
        $date = $_POST['date'];
        $hour = date("G");
        $url  = "?date=". $date ."&hour=". $hour;
        echo '<script>
            window.location.replace("'. $url .'");
        </script>';
    }

    if(isset($_POST['saveAppData'])) {
        if(isset($_POST['address'])) {
            if(isset($_POST['emSelect'])) {
                $apntConn->saveAppSett(1, 1, $businessData['id']);
            } else {
                $apntConn->saveAppSett(1, 0, $businessData['id']);
            }
        } else {
            if(isset($_POST['emSelect'])) {
                $apntConn->saveAppSett(0, 1, $businessData['id']);
            }
        }
    }

    if(isset($_POST['bookNow'])) {
        $clientName     = $_POST['name'];
        $phoneNumber    = $_POST['phoneNumber'];
        $serviceID      = $_POST['service'];
        $empPhone       = $_POST['employee'];
        $date           = $_POST['date'];
        $hour           = $_POST['hour'];
        $email          = $_POST['email'];
        $address        = $_POST['address'];

        $businessID     = $_GET['businessID'];

        $result         = $apntConn->getServ($serviceID);
        $serviceName    = $result['service_name'];
        $serviceTime    = $result['time'];

        $empIDArray     = $userConn->getIDFromPhone($empPhone);
        if($empIDArray != ''){
            $empID          = $empIDArray['id'];
        } else {
            $empID = $userid;
        }

        $checkClient    = $userConn->getIDFromPhone($phoneNumber);        

        if($checkClient == '') {
            $clientIDCheck = '';
        } else {
            $checkID       = $checkClient['id'];
            $clientIDCheck = $bissConn->getClientID($checkID, $businessData['id']);
        }

        if($phoneNumber != '') {
            $eID = $userConn->getUIDPhone($phoneNumber) ?? '';
            if($clientIDCheck != '') {
                if($clientName != '') {
                    $apntConn->insertAppnt($businessID, $serviceName, $serviceID, $empID, $eID, $date, $hour, $serviceTime);
                } else {
                    echo 'No name written.';
                }
            } elseif($clientIDCheck == '' && $eID != '') {
                $eID = $userConn->getUIDPhone($phoneNumber);
                $bissConn->saveNewClients($businessID, $eID);
                $apntConn->insertAppnt($businessID, $serviceName, $serviceID, $empID, $eID, $date, $hour, $serviceTime);
            } else {
                $nameArray = explode(' ', $clientName);
                $firstName = $nameArray[0];
                $lastName  = '';
                $namesCount = count($nameArray);

                for($i = 1; $i <= $namesCount - 1; $i++) {
                    $lastName .= ' ' . $nameArray[$i];
                }

                $userConn->saveNewClient($firstName, $lastName, $email, $phoneNumber, $address);

                $eID = $userConn->getUIDPhone($phoneNumber);
                $bissConn->saveNewClients($businessID, $eID);
                $apntConn->insertAppnt($businessID, $serviceName, $serviceID, $empID, $eID, $date, $hour, $serviceTime);
                
                $lastID = $apntConn->getLastID();

                echo '<script>
                    window.location.replace("user/pinSetup.php?reqID='. $lastID['id'] .'");
                </script>';
                
            }
        } else {
            echo 'No phone number written.';
        }
    }

        
    if(isset($_POST['subReview'])) {
        $businessID = $_GET['businessID'];
        $clientName = $_POST['clientNameReview'];
        if(!empty($_POST['reviewMessage'])) {
            $review = htmlentities($_POST['reviewMessage']);
        } else {
            $review = '';
        }

        if(!empty($_POST['rating'])) {
            $ratingRaw = $_POST['rating'];
        } else {
            $ratingRaw = '';
            echo 'Please select the value.';
        }

        if($clientName != '' && $ratingRaw != '') {
            $rating = substr($ratingRaw, -1);

            $bissConn->saveReviews($businessID, $review, $rating, $clientName);
        }      
    }

    if(isset($_POST['saveGallery'])) {
        $path = '../static/img/businessImg/';

        function generateRandomString($length = 32) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        for($i = 1; $i <= 8; $i++) {
            $imageNumber = 'image' . $i;
            $imageNo     = substr($imageNumber, -1);

            $path = '../static/img/businessImg/';
            $targetPath = $path . basename($_FILES[$imageNumber]['name']);
            
            $oldFile = $_FILES[$imageNumber]['name'];
            $newFile = generateRandomString();
            $fileInfo = explode('.', $oldFile);
            $ext = end($fileInfo);

            $galleryArray = $bissConn->getBusinessGallery($businessData['id']);
            $galleryInfo  = array();
            while($row = $galleryArray->fetch()) {
                $galleryInfo[$row['imageNo']] = $row['imageName'];
            }
            if($_FILES[$imageNumber]['name'] != '') {
                if(count($galleryInfo) < $i) {
                    $newName = $newFile . '.' . $ext;
                    $bissConn->saveBusinessGallery($businessData['id'], $i, $newName);
                    if(!@move_uploaded_file($_FILES[$imageNumber]['tmp_name'], $path . $newFile . '.' . $ext)) {
                        echo 'Error';
                    }
                } elseif($galleryInfo[$i] != '') {
                    $newName = $newFile . '.' . $ext;
                    $bissConn->setBusinessGallery($newName, $businessData['id'], $i);
                    if(!unlink($path . $galleryInfo[$i])) {
                        echo 'Error';
                    }
                    if(!@move_uploaded_file($_FILES[$imageNumber]['tmp_name'], $path . $newFile . '.' . $ext)) {
                        echo 'Error';
                    }
                }
            }
            
            
        }
    }

    if(isset($_POST['nextStep'])) {
        $bName = $_POST['bName'];
        $bAddr = $_POST['bAddr'];
        $bCntr = $_POST['bCntr'];
        $bCity = $_POST['bCity'];
        $bType = $_POST['bType'];

        $whMonday = $_POST['whMonday'];
        $whTue = $_POST['whTue'];
        $whWed = $_POST['whWed'];
        $whThu = $_POST['whThu'];
        $whFri = $_POST['whFri'];
        $whSat = $_POST['whSat'];
        $whSun = $_POST['whSun'];

        $bussinessArray = $bissConn->getBusinessDataBasedOnName($bName);
        $checkedName    = 0;
        $errors         = 0;

        if($bName != '' && $bAddr != '' && $bCntr != '' && $bCity != '' && $bType != '') {
            while($row = $bussinessArray->fetch()) {
                print_r($row);
                if($bName == $row['name'] && $bCity == $row['city']) {
                    $checkedName++;
                }
            }
            if($checkedName == 0) {
                if(isset($_POST['monFree'])) {
                    if($whMonday != '') {
                        if(str_contains($whMonday, '-')) {
                            $arrayCheck = explode('-', $whMonday);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day1 = preg_replace('/\s+/', '', $whMonday);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day1 = 0;
                }

                if(isset($_POST['tueFree'])) {
                    if($whTue != '') {
                        if(str_contains($whTue, '-')) {
                            $arrayCheck = explode('-', $whTue);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day2 = preg_replace('/\s+/', '', $whTue);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day2 = 0;
                }

                if(isset($_POST['wedFree'])) {
                    if($whWed != '') {
                        if(str_contains($whWed, '-')) {
                            $arrayCheck = explode('-', $whWed);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day3 = preg_replace('/\s+/', '', $whWed);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day3 = 0;
                }

                if(isset($_POST['thuFree'])) {
                    if($whThu != '') {
                        if(str_contains($whThu, '-')) {
                            $arrayCheck = explode('-', $whThu);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day4 = preg_replace('/\s+/', '', $whThu);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day4 = 0;
                }

                if(isset($_POST['friFree'])) {
                    if($whFri != '') {
                        if(str_contains($whFri, '-')) {
                            $arrayCheck = explode('-', $whFri);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day5 = preg_replace('/\s+/', '', $whFri);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day5 = 0;
                }

                if(isset($_POST['satFree'])) {
                    if($whSat != '') {
                        if(str_contains($whSat, '-')) {
                            $arrayCheck = explode('-', $whSat);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day6 = preg_replace('/\s+/', '', $whSat);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        } else {
                            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                </div>
                            </div>';
                            $errors++;
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day6 = 0;
                }

                if(isset($_POST['sunFree'])) {
                    if($whSun != '') {
                        if(str_contains($whSun, '-')) {
                            $arrayCheck = explode('-', $whSun);
                            if(is_numeric($arrayCheck[0]) && is_numeric($arrayCheck[1])) {
                                $day7 = preg_replace('/\s+/', '', $whSun);
                            } else {
                                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-semibold">Error!</span> You\'ve entered the wrong format in your working time.
                                    </div>
                                </div>';
                                $errors++;
                            }
                        }
                    } else {
                        echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Info</span>
                            <div>
                            <span class="font-semibold">Error!</span> You have to set working hours, if the day is not set as free day.
                            </div>
                        </div>';
                        $errors++;
                    }
                } else {
                    $day7 = 0;
                }

                if($errors == 0) {
                    $bissConn->updateBusinessWithOwnerID($bName, $bAddr, $bCntr, $bCity, $bType, $userid);
                    for($i = 1; $i <= 7; $i++) {
                        if(${'day'.$i} == 0) {
                            $freeDays = 1;
                            $openingHours = '';
                            $closingHours = '';
                        } else {
                            $splitHours   = explode('-', ${'day'.$i});
                            if($splitHours[0] < 10) {
                                $openingHours = '0' . $splitHours[0] . ':00';
                                if($splitHours[1] < 10) {
                                    $closingHours = '0' . $splitHours[1] . ':00';
                                } else {
                                    $closingHours = $splitHours[1] . ':00';
                                }
                            } else {
                                $openingHours = $splitHours[0] . ':00';
                                if($splitHours[1] < 10) {
                                    $closingHours = '0' . $splitHours[1] . ':00';
                                } else {
                                    $closingHours = $splitHours[1] . ':00';
                                }
                            }
                            
                            $freeDays = 0;
                        }
    
                        $bissConn->saveWorkingHours($businessData['id'], $i, $openingHours, $closingHours, $freeDays);
                    }
                    $bissConn->updateBusSetup($userid);
                    echo '<script>
                        window.location.replace("index.php");
                    </script>';
                }
            } else {
                echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Info</span>
                        <div>
                        <span class="font-semibold">Error!</span> There is already a business with the same name in your city.
                        </div>
                    </div>';
            }
        } else {
            echo '<div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 absolute top-0 w-full dark:bg-red-200 dark:text-red-800" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Info</span>
                <div>
                <span class="font-semibold">Error!</span> You have to enter all of your business informations.
                </div>
            </div>';
        }
    }

?>