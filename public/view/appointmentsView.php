<?php 

    class appointmentsView extends appointments {
        public function showAttr($bID, $empID) {
            $result = $this->gattherServBasedEmp($bID, $empID); 
            while($row = $result->fetch()) {
                $serviceName = $this->gattherServName($row['service_id']);
                echo '
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            '. $serviceName['service_name'] .'
                        </th>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick="deleteServices(phoneNumber, this.id)" id="'. $row['service_id'] .'" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</button>
                        </td>
                    </tr>
                ';
            }
        }

        public function displayHours($bID, $date, $bOpen, $bClose, $serviceTime, $empID) {
            $result          = $this->gattherAppointments($bID, $date, $empID);
            $checks          = $result->fetch();

            //$bClose         -= 1;

            $timeTillOpen    = $bOpen * 60; // TIME FROM 00:00 TILL SHOP OPENS
            $timeFromOpenMin = 0;
            $currHour        = $timeFromOpenMin + $timeTillOpen;
            $nextApt         = $currHour + 0;
            $minOpen         = ($bClose - $bOpen) * 60;

            $timeBetween     = 10;

            if($checks == '') {
                if($timeFromOpenMin == 0) {
                    echo '<option value="'. $bOpen * 60 .'">'. $bOpen .':00</option>'; 
                }
                while($timeFromOpenMin <= $minOpen - $serviceTime) {
                    $timeFromOpenMin += $timeBetween;
                    $currHour += $timeBetween;
                    $nextApt = $currHour;

                    if($nextApt % 60 == 0) {
                        $minutes = '00';
                    } else {
                        $minutes = $nextApt % 60;
                    }

                    $time    = intdiv($nextApt, 60).':'. $minutes;
                    echo '<option value="'. $nextApt .'">'. $time .'</option>';  
                }
            } else {
                $scheduleStart = array();
                $scheduleTime  = array();
                $aptCout       = 0;
                
                array_push($scheduleStart, $checks['serviceStart']);
                array_push($scheduleTime, $checks['serviceTime']);

                while($row = $result->fetch()) {
                    $startTime = $row['serviceStart'];

                    array_push($scheduleStart, $startTime);
                    array_push($scheduleTime, $checks['serviceTime']);
                }
                
                $noAppt = count($scheduleStart);
                array_push($scheduleStart, 'Done');
                array_push($scheduleTime, 'Done');

                if($timeFromOpenMin == 0 && $scheduleStart[0] != $bOpen * 60) {
                    echo '<option value="'. $nextApt .'">'. $bOpen .':00</option>'; 
                } elseif($bOpen * 60 == $scheduleStart[0]) {
                    $timeFromOpenMin += $scheduleTime[0];
                    $currHour += $scheduleTime[0];
                    $nextApt = $currHour;

                    if($nextApt % 60 == 0) {
                        $minutes = '00';
                    } else {
                        $minutes = $nextApt % 60;
                    }

                    $time = intdiv($nextApt, 60).':'. $minutes; 

                    $aptCout++;
                }
                
                while($timeFromOpenMin <= $minOpen - $serviceTime) {
                    if($aptCout > $noAppt - 1) {
                        if($nextApt % 60 == 0) {
                            $minutes = '00';
                        } else {
                            $minutes = $nextApt % 60;
                        }

                        $time    = intdiv($nextApt, 60).':'. $minutes;
                        echo '<option value="'. $nextApt .'">'. $time .'</option>';
                        
                        $timeFromOpenMin += $timeBetween;
                        $currHour += $timeBetween;
                        $nextApt = $currHour;
                    } else {
                        if($nextApt != $scheduleStart[$aptCout]) {
                            if($nextApt + $serviceTime <= $scheduleStart[$aptCout] && $aptCout != $noAppt) {
                                if($nextApt % 60 == 0) {
                                    $minutes = '00';
                                } else {
                                    $minutes = $nextApt % 60;
                                }
            
                                $time    = intdiv($nextApt, 60).':'. $minutes;
                                echo '<option value="'. $nextApt .'">'. $time .'</option>';
    
                                $timeFromOpenMin += $timeBetween;
                                $currHour += $timeBetween;
                                $nextApt = $currHour;
                            } else {    
                                $timeFromOpenMin += $timeBetween;
                                $currHour += $timeBetween;
                                $nextApt = $currHour;
                            }
                        } else {
                            $timeFromOpenMin += $scheduleTime[$aptCout];
                            $currHour += $scheduleTime[$aptCout];
                            $nextApt = $currHour;

                            if($aptCout != $noAppt - 1) {
                                $aptCout++;
                            } else {
                                $aptCout = $noAppt;
                            }
                        }
                    }
                }
            }
        }

        public function displayApt($bID, $date, $bOpen, $bClose, $empID, $freeTime) {
            function checkBrightness($hex) {
                $hex = str_replace('#', '', $hex);
               
                $c_r = hexdec(substr($hex, 0, 2));
                $c_g = hexdec(substr($hex, 2, 2));
                $c_b = hexdec(substr($hex, 4, 2));
               
                return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
            }

            $result          = $this->gattherAppointments($bID, $date, $empID);
            $checks          = $result->fetch();

            if($freeTime == 0) {
                if($checks == '') {
                    for($i = $bOpen; $i <= $bClose - 1; $i++) {
                        echo '<div class="grid h-48 grid-rows-6 p-4 mb-2 text-sm font-semibold text-black border-t border-black dark:text-white dark:bg-gray-800 focus:outline-none focus:shadow-outline-purple"" id="'. $i * 60  .'">';
                          echo '<p id="'. $i * 60 .'" class="pl-2 border-b-2 dark:border-gray-700">'. $i .':00</p>';
                        for($j = 1; $j <= 5; $j++) {
                          echo '<p id="'. $i * 60 + $j * 10 .'" class="pl-2 border-b-2 dark:border-gray-700">'. $i .':'. $j * 10 .'</p>';
                        }
                        echo '</div>';
                    } 
                } else {
                    $scheduleStart = array();
                    $scheduleTime  = array();
                    $clientID      = array();
                    $aptID         = array();
                    $aptCout       = 0;
                    
                    array_push($scheduleStart, $checks['serviceStart']);
                    array_push($scheduleTime, $checks['serviceTime']);
                    array_push($clientID, $checks['userID']);
                    array_push($aptID, $checks['id']);
    
                    while($row = $result->fetch()) {
                        array_push($scheduleStart, $row['serviceStart']);
                        array_push($scheduleTime, $row['serviceTime']);
                        array_push($clientID, $row['userID']);
                        array_push($aptID, $row['id']);
                    }
                    
                    $noAppt = count($scheduleStart) - 1;
    
                    $endTime = $scheduleStart[0] + $scheduleTime[0];
    
                    $colorGenerated = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    
                    for($i = $bOpen; $i <= $bClose - 1; $i++) {
                        echo '<div class="grid h-48 grid-rows-6 p-4 mb-2 text-sm font-semibold text-black border-t border-black dark:text-white dark:bg-gray-800 focus:outline-none focus:shadow-outline-purple"">';
                        for($j = 0; $j <= 5; $j++) {
                            if($scheduleStart[$aptCout] <= $i * 60 + $j * 10 && $i * 60 + $j * 10 <= $scheduleStart[$aptCout] + $scheduleTime[$aptCout]) {
                                if($scheduleStart[$aptCout] == $i * 60 + $j * 10) {
                                    $clientInfoFetch = $this->gattherAllInfoFetch($clientID[$aptCout]); 
    
                                    if($j * 10 == 0) {
                                        $minutes = '00';
                                    } else {
                                        $minutes = $j * 10;
                                    }
        
                                    if(checkBrightness($colorGenerated) > 130) {
                                        $textColor = 'black';
                                    } else {
                                        $textColor = 'white';
                                    }
    
                                    echo '<p id="apt_'. $aptID[$aptCout] .'" class="text-'. $textColor .' border-b-2 pl-2 dark:border-gray-700 editAppt" style="background-color: '. $colorGenerated .'">'. $i .':'. $minutes .'</p>';
                                } else {
                                    if($j * 10 == 0) {
                                        $minutes = '00';
                                    } else {
                                        $minutes = $j * 10;
                                    }
        
                                    if(checkBrightness($colorGenerated) > 130) {
                                        $textColor = 'black';
                                    } else {
                                        $textColor = 'white';
                                    }
        
                                    echo '<p id="apt_'. $aptID[$aptCout] .'" class="text-'. $textColor .' border-b-2 pl-2 dark:border-gray-700 editAppt" style="background-color: '. $colorGenerated .'">'. $i .':'. $minutes .'</p>';   
                                }
                            } elseif($i * 60 + $j * 10 < $scheduleStart[$aptCout]) {
                                if($j * 10 == 0) {
                                    $minutes = '00';
                                } else {
                                    $minutes = $j * 10;
                                }
                                echo '<p class="pl-2 text-black border-b-2 dark:border-gray-700 dark:text-white">'. $i .':'. $minutes .'</p>';
                            } else {
                                if($j * 10 == 0) {
                                    $minutes = '00';
                                } else {
                                    $minutes = $j * 10;
                                }
                                echo '<p class="pl-2 border-b-2 dark:border-gray-700 dark:text-white">'. $i .':'. $minutes .'</p>';
                                if($aptCout < $noAppt) {
                                    $aptCout++;
                                    $colorGenerated = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                                }
                            }
                        }
                        echo '</div>';
                    } 
                }
            } else {
                echo '<div class="flex p-4 mb-4 text-sm text-yellow-700 bg-yellow-300 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
                    <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Info</span>
                    <div>
                    <span class="font-medium">Warning!</span> This is a free day.
                    </div>
                </div>';
            }
        }

        public function displayEmpServ($bID, $uID) {
            $empService = $this->getServicesForEmp($bID, $uID);
            
            while($row = $empService->fetch()) {
                $allService = $this->getServices($bID);
                while($rows = $allService->fetch()) {
                    if($rows['id'] == $row['service_id']) {
                        echo '<option value="'. $rows['id'] .'">'. $rows['service_name'] .'</option>';
                    }
                }
            }
            
            /*for($i = 0; $i <= count($allService) - 1; $i++) {
                for($j = 0; $j <= count($empService) - 1; $j++) {
                    if($allService[$i] == $empService[$j]) {
                        echo '<option value="'. $allService[$i]['id'] .'">'. $empService[$i]['service_name'] .'</option>';
                    }
                }
            }*/

            while($row = $empService->fetch()) {
                while($rows = $allService->fetch()) {
                    if($row['service_id'] == $rows['id']) {
                        echo '<option value="'. $rows['id'] .'">'. $rows['service_name'] .'</option>';
                    }
                }
            }
        }

        public function calculateClientsThisMonth($bID) {
            $clientInfo   = $this->gattherClients($bID);
            $totalClients = 0;
            $currentMonth =  date('m');

            while($row    = $clientInfo->fetch()) {
                $aptDateRaw  = explode('-', $row['date']);
                $aptDate = $aptDateRaw[0];
                if($aptDate == $currentMonth) {
                    $totalClients++;
                }
            }

            echo $totalClients;
        }

        public function calculateConfirmedThisMonth($bID) {
            $clientInfo   = $this->gattherClients($bID);
            $totalClients = 0;
            $currentMonth =  date('m');

            while($row    = $clientInfo->fetch()) {
                $aptDateRaw  = explode('-', $row['date']);
                $aptDate = $aptDateRaw[0]; 
                if($aptDate == $currentMonth && $row['confirmed'] == 1) {
                    $totalClients++;
                }
            }

            echo $totalClients;
        }

        public function calculateRevenueThisMonth($bID) {
            $clientInfo   = $this->gattherClients($bID);
            $totalRevenue = 0;
            $currentMonth =  date('m');

            while($row    = $clientInfo->fetch()) {
                $aptDateRaw  = explode('-', $row['date']);
                $aptDate = $aptDateRaw[0]; 
                if($aptDate == $currentMonth && $row['confirmed'] == 1) {
                    $serviceInfo = $this->getAllServInfo($row['serviceID']);
                    while($rows = $serviceInfo->fetch()) {
                        $totalRevenue += $rows['minPrice'];
                    }
                }
            }

            echo $totalRevenue;
        }

        public function displayGraphsConfCanc($bID) {
            $currentMonth   = date('m');
            $dateFrom       = $currentMonth . "-01-" . date("Y");
            $dateTo         = date('m', strtotime('+1 month')) . "-01-" . date("Y");

            $clientInfo     = $this->getAllServInfoDate($bID, $dateFrom, $dateTo);
            $arrayConfirmed = array(); 
            $arrayCanceled  = array(); 

            while($row      = $clientInfo->fetch()) {
                $aptDateRaw = explode('-', $row['date']);
                $aptDate    = $aptDateRaw[0]; 

                if($row['confirmed'] == 1) {
                    array_push($arrayConfirmed, $aptDateRaw[1]);
                } else {
                    array_push($arrayCanceled, $aptDateRaw[1]);
                }
            }

            $totalItemsConf = array_count_values($arrayConfirmed);
            $totalItemsCanc = array_count_values($arrayCanceled);
            
            echo "<script>
            
            const canceledConfirmed = document.getElementById('canceledConfirmed');

            new Chart(canceledConfirmed, {
                type: 'line',
                data: {
                    labels: [";
                    for($i = 1; $i <= 31; $i++) {
                        if($i < 10) {
                            echo "'0". $i ."', ";
                        } elseif($i == 31) {
                            echo $i;
                        }
                        else {
                            echo "'" . $i . "'" . ",";
                        }
                    }
                    echo "],
                    datasets: [{
                        label: 'Confirmed',
                        data: [";
                        for($i = 1; $i <= 31; $i++) {
                            if(isset($totalItemsConf[$i])) {
                                echo $totalItemsConf[$i] . ', ';
                            } else {
                                echo 0 . ', ';
                            }
                        }
                        echo "],
                        borderWidth: 1
                    }, {
                        label: 'Canceled',
                        data: [";
                        for($i = 1; $i <= 31; $i++) {
                            if(isset($totalItemsCanc[$i])) {
                                echo $totalItemsCanc[$i] . ', ';
                            } else {
                                echo 0 . ', ';
                            }
                        }
                        echo "],
                        borderWidth: 1
                    }],
                },
                options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                        },
                    }
                }
                }
            });
            </script>";
        }

        public function displayGraphsCurrentPrev($bID) {

            $currentMonth   = date('m');
            $dateFrom       = $currentMonth . "-01-" . date("Y");
            $dateTo         = date('m', strtotime('+1 month')) . "-01-" . date("Y");
            $datePrev       = date('m', strtotime('-1 month')) . "-01-" . date("Y");

            $currentMonth   = $this->getAllServInfoDate($bID, $dateFrom, $dateTo);
            $prevMonth      = $this->getAllServInfoDate($bID, $datePrev, $dateFrom);
            
            $arrayCurrent   = array(); 
            $arrayPrev      = array(); 

            while($row      = $currentMonth->fetch()) {
                $aptDateRaw = explode('-', $row['date']);
                $aptDate    = $aptDateRaw[0]; 

                array_push($arrayCurrent, $aptDateRaw[1]);
            }

            while($row      = $prevMonth->fetch()) {
                $aptDateRaw = explode('-', $row['date']);
                $aptDate    = $aptDateRaw[0]; 

                array_push($arrayPrev, $aptDateRaw[1]);
            }

            $totalItemsCurr = array_count_values($arrayCurrent);
            $totalItemsPrev = array_count_values($arrayPrev);
            
            echo "<script>
            
                const thisMonthAndPrevious = document.getElementById('thisMonthAndPrevious');

                new Chart(thisMonthAndPrevious, {
                    type: 'line',
                    data: {
                        labels: [";
                        for($i = 1; $i <= 31; $i++) {
                            if($i < 10) {
                                echo "'0". $i ."', ";
                            } elseif($i == 31) {
                                echo $i;
                            }
                            else {
                                echo "'" . $i . "'" . ",";
                            }
                        }
                        echo "],
                        datasets: [{
                            label: 'This month',
                            data: [";
                            for($i = 1; $i <= 31; $i++) {
                                if(isset($totalItemsCurr[$i])) {
                                    echo $totalItemsCurr[$i] . ', ';
                                } else {
                                    echo 0 . ', ';
                                }
                            }
                            echo "],
                            borderWidth: 1
                        }, {
                            label: 'Previous month',
                            data: [";
                            for($i = 1; $i <= 31; $i++) {
                                if(isset($totalItemsPrev[$i])) {
                                    echo $totalItemsPrev[$i] . ', ';
                                } else {
                                    echo 0 . ', ';
                                }
                            }
                            echo "],
                            borderWidth: 1
                        }],
                    },
                    options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5,
                            },
                        }, 
                    }
                    }
                });
            </script>";
        }
    }

?>