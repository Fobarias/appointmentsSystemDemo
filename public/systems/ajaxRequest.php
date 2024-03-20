<?php

    include('../model/db.inc.php');

    include('../model/autoload_extra.php');

    $requestID = $_POST['requestID'];
    $phoneNumber = $_POST['phoneNumberPass'] ?? '';
    
    $bissConn  = new businessController();
    $userConn  = new userController();
    $aptConn   = new appointmentsController();

    $aptView   = new appointmentsView();
    $bissView  = new businessView();

    if(isset($_COOKIE['LGSCCS'])) {
        $userid = $userConn->getUID(sha1($_COOKIE['LGSCCS']));
    } else {
        $userid = 0;
    }
    
    $businessData = $bissConn->getBusinessData($userid);
    
    $userData     = $userConn->getAllInfo($phoneNumber);

    while($row = $userData->fetch()) {
        $empID = $row['id'];
    }

    if($requestID == 1) {
        $businessID = $_GET['businessID'] ?? '';
        if(isset($_POST['serviceSelect'])) {
            $serviceID = $_POST['serviceSelect'];

            if($businessID == '') {
                $result = $bissConn->getEmpBasedServ($businessData['id'], $serviceID);
            } else {
                $result = $bissConn->getEmpBasedServ($businessID, $serviceID);
            }

            echo '<option disabled selected>Select a employee</option>';
            while($row = $result->fetch()) {
                $userResult = $bissConn->getNameBasedUID($row['user_id']);
                while($rows = $userResult->fetch()) {
                    echo '<option value="'. $rows['phoneNumber'] .'">'. $rows['firstName'] .' '. $rows['lastName'] .'</option>';
                }
            }
        }
    } elseif($requestID ==  2) {

        $date      = $_POST['date'];
        $serviceID = $_POST['servID'];
        $businessID = $_GET['businessID'] ?? '';

        $servInfo  = $aptConn->getServ($serviceID);
        
        $empPhone  = $_POST['empSel'];

        $empIDArray     = $userConn->getIDFromPhone($empPhone);
        if($empIDArray != ''){
            $empID          = $empIDArray['id'];
        } else {
            $empID = $userid;
        }

        $freeDaysRaw = $bissConn->freeDays($businessData['id']);
        while($row = $freeDaysRaw->fetch()) {
            $freeDays = $row['days'];
        }

        $days = explode(' ', $freeDays);
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

        if($businessID == '') {
            $dayInfo = $bissConn->workingHours($businessData['id'], $day);
            while($row = $dayInfo->fetch()) {
                $busOpen   = (int)substr($row['openingHours'], 0, 2);
                $busClose  = (int)substr($row['closingHours'], 0, 2);
                $freeDay   = $row['freeDay'];
            }
        } else {
            $dayInfo = $bissConn->workingHours($businessID, $day);
            while($row = $dayInfo->fetch()) {
                $busOpen   = (int)substr($row['openingHours'], 0, 2);
                $busClose  = (int)substr($row['closingHours'], 0, 2);
                $freeDay   = $row['freeDay'];
            }
        }
        
        
        if($freeDay == 0) {

            if($date == '') {
                echo '<option disabled selected>Select a date</option>';
            } elseif($serviceID == '') {
                echo '<option disabled selected>Select a service</option>';
            } elseif($empPhone == '' && $empPhone != Null) {
                echo '<option disabled selected>Select a employee</option>';
            } elseif($date < date("m-d-Y")) {
                $dateSplit = explode('-', $date);
                $newDateSplit = explode('-', date("m-d-Y"));
                if(!$dateSplit[2] >= $newDateSplit[2] || $dateSplit[2] < $newDateSplit[2]) {
                    echo '<option disabled selected>Change date</option>';
                } elseif($date < date("m-d-Y") && $dateSplit[2] == $newDateSplit[2]) {
                    echo '<option disabled selected>Change date</option>';
                } else {
                    if($businessID == '') {
                        $aptView->displayHours($businessData['id'], $date, $busOpen, $busClose, $servInfo['time'], $empID);
                    } else {
                        $aptView->displayHours($businessID, $date, $busOpen, $busClose, $servInfo['time'], $empID);
                    }
                }
            } else {
                if($businessID == '') {
                    $aptView->displayHours($businessData['id'], $date, $busOpen, $busClose, $servInfo['time'], $empID);
                } else {
                    $aptView->displayHours($businessID, $date, $busOpen, $busClose, $servInfo['time'], $empID);
                } 
            }
        } else {
            echo '<option disabled selected>Select a working day</option>';
        }
    } elseif($requestID ==  3) {
        $phoneNumber = $_POST['phoneNumberPass'];
        $name        = $_POST['nameEmp'];
        $email       = $_POST['emailEmp'];
        $address     = $_POST['addressEmp'];  

        $nameSeparate = explode(" ", $name);
        $firstName    = $nameSeparate[0];
        $lastName     = "";
        for($i = 1; $i <= count($nameSeparate) - 1; $i++) {
            $lastName .= $nameSeparate[$i] . " ";
        }

        if(strlen($name) >= 3) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL) || $email == '') {
                $userConn->saveEmpInfo($firstName, $lastName, $email, $address, $phoneNumber);
            } else {
                echo 'The email is not valid';
            }
        } else {
            echo 'Name field can\'t be empty';
        }
    } elseif($requestID ==  4) {
        $aptID = $_POST['aptID'];

        $result  = $aptConn->getAptInfo($aptID, $businessData['id']);
        $clientInfo = $aptConn->getAllInfo($result['userID']);
        $empInfo = $aptConn->getAllInfo($result['empID']);

        if(!$result['confirmed'] == 1) {
            echo '<h2 class="text-black dark:text-white ">Appointment information</h2>
            <h2 class="mt-2 text-black text-md dark:text-white">Client</h2>
            <div class="grid grid-cols-2 gap-x-2">
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $clientInfo['firstName'] .' '. $clientInfo['lastName'] .'</p>
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $clientInfo['phoneNumber'] .'</p>
            </div>
            <h2 class="mt-2 text-black text-md dark:text-white">Appointment details</h2>
            <div class="grid grid-cols-2 gap-x-2">
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $empInfo['firstName'] .' '. $empInfo['lastName'] .'</p>
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $result['serviceName'] .'</p>
            </div>
            <button id="deleteApt" class="px-8 py-1 text-sm text-white bg-purple-600 rounded-md dark:text-black hover:bg-purple-700">Delete</button>
            <button id="confirmApt" class="px-8 py-1 text-sm text-white bg-green-600 rounded-md dark:text-black hover:bg-green-700">Confirm appointment</button>
            ';
        } else {
            echo '<div class="flex">
                <h2 class="text-black dark:text-white">Appointment information</h2>
                <span class="ml-2 bg-green-100 text-green-800 font-medium mr-2 px-1 rounded dark:bg-green-200 dark:text-green-900" style="font-size: 10px;">Appointment completed</span>
            </div>
            <h2 class="mt-2 text-black text-md dark:text-white">Client</h2>
            <div class="grid grid-cols-2 gap-x-2">
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $clientInfo['firstName'] .' '. $clientInfo['lastName'] .'</p>
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $clientInfo['phoneNumber'] .'</p>
            </div>
            <h2 class="mt-2 text-black text-md dark:text-white">Appointment details</h2>
            <div class="grid grid-cols-2 gap-x-2">
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $empInfo['firstName'] .' '. $empInfo['lastName'] .'</p>
              <p class="block w-full mt-1 mb-3 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $result['serviceName'] .'</p>
            </div>

            ';
        }
        

    } elseif($requestID ==  5) {
        $aptID = $_POST['aptID'];

        $aptConn->removeApt($aptID, $businessData['id']);
    } elseif($requestID ==  6) {
        $service     = $_POST['service'];
        $serviceText = $_POST['serviceText'];

        if($aptConn->getInfoEmpAttr($businessData['id'], $empID, $service) == '') {
            $aptConn->insertEmpAttr($businessData['id'], $empID, $service);
        } else {
            echo 'He already has this attribute';
        }
    } elseif($requestID ==  7) {
        $serviceID = $_POST['serviceID'];

        $aptConn->deleteAttr($empID, $serviceID, $businessData['id']);
    } elseif($requestID ==  8) {
        $phoneNumberNew = $_POST['phoneNumberNew'];
        $clientName     = $_POST['clientName'];
        $phoneNumber    = $_POST['phoneNumber'];
        $serviceID      = $_POST['serviceSel'];
        $empPhone       = $_POST['empSel'];
        $date           = $_POST['date'];
        $hour           = $_POST['hour'];
        $nClient        = $_POST['nClient'];
        $eClient        = $_POST['eClient'];

        $result         = $aptConn->getServ($serviceID);
        $serviceName    = $result['service_name'];
        $serviceTime    = $result['time'];

        $empIDArray     = $userConn->getIDFromPhone($empPhone);
        if($empIDArray != ''){
            $empID          = $empIDArray['id'];
        } else {
            $empID = $userid;
        }

        $checkClient    = $userConn->getIDFromPhone($phoneNumberNew);

        

        if($checkClient == '') {
            $clientIDCheck = '';
        } else {
            $checkID       = $checkClient['id'];
            $clientIDCheck = $bissConn->getClientID($checkID, $businessData['id']);
        }

        if($eClient == 'true') {
            if($phoneNumber != '') {
                $phoneNumberArray = explode('|', $phoneNumber);
                $clientPhone = trim($phoneNumberArray[1]);

                $clientIDArray = $userConn->getIDFromPhone($clientPhone);
                $clientID = $clientIDArray['id'];

                $aptConn->insertAppnt($businessData['id'], $serviceName, $serviceID, $empID, $clientID, $date, $hour, $serviceTime);
            } else {
                echo 'No phone number writen.';
            }
        } elseif($nClient == 'true') {
            if($clientIDCheck['user_id'] == '') {
                if($clientName != '' && $phoneNumberNew != '') {
                    $eID = $userConn->getUIDPhone($phoneNumberNew);
                    if($eID == '') {
                        $nameArray    = explode(' ', $clientName);
                        $totalNames   = count($nameArray);
                        $firstName    = trim($nameArray[0]);
                        $lastName     = '';
                        for($i = 1; $i <= $totalNames - 1; $i++) {
                            $lastName .= ' ' . trim($nameArray[$i]);
                        } 
    
                        $userConn->saveDefaultData($firstName, $lastName, $phoneNumberNew);
    
                        $eID = $userConn->getUIDPhone($phoneNumberNew);
    
                        $bissConn->saveNewClients($businessData['id'], $eID);
                        $aptConn->insertAppnt($businessData['id'], $serviceName, $serviceID, $empID, $eID, $date, $hour, $serviceTime);
                    } else {
                        $bissConn->saveNewClients($businessData['id'], $eID);
                        $aptConn->insertAppnt($businessData['id'], $serviceName, $serviceID, $empID, $eID, $date, $hour, $serviceTime);
                    }
                } else {
                    echo 'Both name and phone number have to be filled for new clients';
                }
            } else {
                echo 'This phone number is registered as client already';
            }
        }
    } elseif($requestID ==  9) {
        $date = $_POST['date'];
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
        $empID = $_POST['empID'];

        $aptView->displayApt($businessData['id'], $date, $busOpen, $busClose, $empID, $freeDay);
    } elseif($requestID == 10) {
        $phoneNumber = $_POST['phoneNumberPass'];
        $result = $userConn->getAllInfo($phoneNumber);
        
        while($row = $result->fetch()) {
            if($row['accountSetup'] != 0) {
                echo '
                    <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Employee Information
                    </p>
                    <div class="grid grid-cols-2">
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Name and surname</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $row['firstName'] .' '. $row['lastName'] .'</p>
                        </label>
    
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $row['email'] .'</p>
                        </label>
    
                        <label class="block px-2 mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Phone number</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $row['phoneNumber'] .'</p>
                        </label>
    
                        <label class="block px-2 mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Address</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $row['address'] .'</p>
                        </label>
                    </div>
    
                    <p class="mt-4 mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Login information
                    </p>
                    <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span>This user had setup his Artty ID and have custom credentials</span>
                        </div>
                    </a>
    
                    <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Job
                    </p>
                    <div class="grid grid-cols-2">                
                ';
            } else {
                $ciphering = "AES-128-CTR";
                $iv_length = openssl_cipher_iv_length($ciphering);
                $options   = 0;
                $encry_iv  = '1234567891011121';
                $key       = "ArttyAppointmentsKnowsAsLIMSYBITCH";
                $pass      = openssl_decrypt($row['pass'], $ciphering, $key, $options, $encry_iv);   
    
                echo '
                    
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Employee Information
                    </p>
                    <div class="grid grid-cols-2">
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Name and surname</span>
                        <input name="nameEmpRow" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="'. $row['firstName'] .' '. $row['lastName'] .'">
                        </label>
    
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <input name="emailEmpRow" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="'. $row['email'] .'">
                        </label>
    
                        <label class="block px-2 mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Phone number</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $row['phoneNumber'] .'</p>
                        </label>
    
                        <label class="block px-2 mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Address</span>
                        <input name="addressEmpRow" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="'. $row['address'] .'">
                        </label>
                    </div>
    
                    <p class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Login information
                    </p>
                    <div class="grid grid-cols-2">
    
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Name and surname</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" >'. $row['phoneNumber'] .'</p>
                        </label>
    
                        <label class="block px-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Password</span>
                        <p class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">'. $pass .'</p>
                        </label>
                    </div>
    
                    <p class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Job
                    </p>
                    <div class="grid grid-cols-2">                
                ';
            }
        }
    } elseif($requestID == 11) {
        
        $userid   = $userConn->getUID(sha1($_COOKIE['LGSCCS']));
        while($row = $userData->fetch()) {
            $empID = $row['id'];
        }

        echo '

        <form method="post">
            <div class="grid w-full grid-cols-3">
                <div class="col-span-2 px-3">
                        <select name="attr" id="attrAdd" class="block w-full px-6 py-2 text-base text-gray-900 border border-gray-300 rounded-lg employee bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">';
                            $bissView->showOnlyServices($businessData['id']);
                echo   '</select>
                </div>
                <div class="px-3">
                    <button type="button" id="'. $phoneNumber .'" onclick="saveServices(this.id)" name="addAttr" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Add attribute</button>
                </div>
            </div>
        </form>
        <div class="relative mt-3 overflow-x-auto shadow-md sm:rounded-md">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                    Services made by this employee
                    <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Add and delete your employee attributes and services.</p>
                </caption>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Service/Attribute name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="empAttr">
                    <span id="testSpanChange"></span>';
                    echo $aptView->showAttr($businessData['id'], $empID);
        echo '      
                </tbody>
            </table>
        </div>
        ';
    } elseif($requestID == 12) {
        $bData = $bissConn->getBusinessData($userConn->getUID(sha1($_COOKIE['LGSCCS'])));
        $bID   = $bData['id'];

        if(isset($_POST['search'])) {
            $searchDefault = $_POST['search'];
            $search        = '%' . $searchDefault . '%'; 

            if(strlen($search) > 3) {
                $sql  = "SELECT * FROM accounts WHERE phoneNumber LIKE :s";
                $stmt = $db->prepare($sql);
                $stmt->bindParam('s', $search);
                $stmt->execute();

                $result = $bissConn->getClients($bID) ?? '';         

                if($result != '') {
                    while($row = $stmt->fetch()) {
                        while($rows = $result->fetch()) {
                            if($rows['user_id'] == $row['id']) {
                                $firstName = $row['firstName'];
                                $lastName  = $row['lastName'];
                
                                echo '
                                <button id="inputValue" class="w-full outline-none cursor-pointer hover:dark:bg-gray-800 dark:bg-gray-700"><p class="py-2 text-white text-md">'. $firstName . ' ' .  $lastName .' | '. $row['phoneNumber'] .'</p></button>
                                <script>
                                    $(\'#inputValue\').click(function(){
                                        var clientInfo = $(\'#inputValue\').text();

                                        $(\'#search\').val(clientInfo);
                                        $(\'#inputValue\').addClass("hidden");
                                    });
                                </script>
                                '; 
                            }
                        }
                    }
                } else {
                    echo '<button id="inputValue" class="w-full outline-none cursor-pointer hover:dark:bg-gray-800 dark:bg-gray-700"><p class="py-2 text-white text-md">No client found</p></button>';
                }
                
                
            }
        }
    } elseif($requestID == 13) {
        $date      = $_POST['date'];
        $serviceID = $_POST['servID'];
        $businessID = $_GET['businessID'] ?? '';

        $servInfo  = $aptConn->getServ($serviceID);
        
        $empPhone  = $_POST['empSel'];

        $empIDArray     = $userConn->getIDFromPhone($empPhone);
        if($empIDArray != ''){
            $empID          = $empIDArray['id'];
        } else {
            $empID = $userid;
        }

        $freeDaysRaw = $bissConn->freeDays($businessData['id']);
        while($row = $freeDaysRaw->fetch()) {
            $freeDays = $row['days'];
        }

        $days = explode(' ', $freeDays);
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

        if($businessID == '') {
            $dayInfo = $bissConn->workingHours($businessData['id'], $day);
            while($row = $dayInfo->fetch()) {
                $busOpen   = (int)substr($row['openingHours'], 0, 2);
                $busClose  = (int)substr($row['closingHours'], 0, 2);
                $freeDay   = $row['freeDay'];
            }
        } else {
            $dayInfo = $bissConn->workingHours($businessID, $day);
            while($row = $dayInfo->fetch()) {
                $busOpen   = (int)substr($row['openingHours'], 0, 2);
                $busClose  = (int)substr($row['closingHours'], 0, 2);
                $freeDay   = $row['freeDay'];
            }
        }
        
        
        if($freeDay == 0) {

            if($date == '') {
                echo '<option disabled selected>Select a date</option>';
            } elseif($serviceID == '') {
                echo '<option disabled selected>Select a service</option>';
            } elseif($empPhone == '' && $empPhone != Null) {
                echo '<option disabled selected>Select a employee</option>';
            } elseif($date < date("m-d-Y")) {
                $dateSplit = explode('-', $date);
                $newDateSplit = explode('-', date("m-d-Y"));
                if(!$dateSplit[2] >= $newDateSplit[2] || $dateSplit[2] < $newDateSplit[2]) {
                    echo '<option disabled selected>Change date</option>';
                } elseif($date < date("m-d-Y") && $dateSplit[2] == $newDateSplit[2]) {
                    echo '<option disabled selected>Change date</option>';
                } else {
                    if($businessID == '') {
                        $aptView->displayHours($businessData['id'], $date, $busOpen, $busClose, $servInfo['time'], $empID);
                    } else {
                        $aptView->displayHours($businessID, $date, $busOpen, $busClose, $servInfo['time'], $empID);
                    }
                }
            } else {
                if($businessID == '') {
                    $aptView->displayHours($businessData['id'], $date, $busOpen, $busClose, $servInfo['time'], $empID);
                } else {
                    $aptView->displayHours($businessID, $date, $busOpen, $busClose, $servInfo['time'], $empID);
                } 
            }
        } else {
            echo '<option disabled selected>Select a working day</option>';
        }
    } elseif($requestID == 14) {
        $passToEnc = $_POST['pinCode'];
        $reqID     = $_POST['reqID'];
        $clientID  = $aptConn->getClientId($reqID);

        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options   = 0;
        $encry_iv  = '1234567891011121';
        $key       = "ArttyAppointmentsKnowsAsLIMSYBITCH";
        $pass      = openssl_encrypt($passToEnc, $ciphering, $key, $options, $encry_iv);

        $userConn->saveUserPass($pass, $clientID['userID']);
    } elseif($requestID == 15) {
        $aptID = $_POST['aptID'];

        $aptConn->saveConfirmStat($aptID, $businessData['id']);
    }

    

?>