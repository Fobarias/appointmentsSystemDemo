<?php

    class business extends database {
        /* INSERT BUSINESS DETAILS IN DATABASE */
        protected function insertBusinessDetails($bID, $bOwnerUID, $bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType) {
            $sql    = "INSERT INTO businesses (id, owner_uid, name, address, openTime, closeTime, country, city, businessType) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bID, $bOwnerUID, $bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType]);
        }

        protected function gattherBusinessID($bOwnerUID, $bName) {
            $sql = "SELECT id FROM businesses WHERE owner_uid = ? AND name = ?";
            
            $run = $this->connect_business()->prepare($sql);
            $run->execute([$bOwnerUID, $bName]);
            $result = $run->fetch();

            return $result;
        }

        /* INSERT BUSINESS DETAILS IN DATABASE */
        protected function updateBusinessDetails($bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType, $bID) {
            $sql    = "UPDATE businesses SET name = ?, address = ?, openTime = ?, closeTime = ?, country = ?, city = ?, businessType = ? WHERE id = ?";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType, $bID]);
        }

        /* INSERT BUSINESS DETAILS IN DATABASE */
        protected function updateBusinessDetailsWithOWID($bName, $bAddr,  $bCountry, $bCity, $bType, $oID) {
            $sql    = "UPDATE businesses SET name = ?, address = ?, country = ?, city = ?, businessType = ? WHERE owner_uid = ?";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bName, $bAddr, $bCountry, $bCity, $bType, $oID]);
        }

        /* GET BUSINESS DETAILS */
        protected function gattherBusinessDetails($uid) {
            $sql = "SELECT * FROM businesses WHERE owner_uid = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$uid]);
            $result = $run->fetch();

            return $result;
        }

        /* GET BUSINESS DETAILS BASED ON NAME */
        protected function gattherBusinessDetailsBasedOnName($bName) {
            $sql = "SELECT * FROM businesses WHERE name = ?";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$bName]);

            return $result;
        }

        /* GET BUSINESS DETAILS BASED ON ID */
        protected function gattherBusinessDetailsID($id) {
            $sql = "SELECT * FROM businesses WHERE id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$id]);
            $result = $run->fetch();

            return $result;
        }

        /* UPDATE BUSINESS SETUP - ARRTY APPOIMENTS */
        protected function updateBusinessSetup($uid) {
            $sql    = "UPDATE business_sub SET business_setup = 1 WHERE owner_id = ?";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$uid]);
        }

        /* GET BUSINESS SETUP STATUS */
        protected function gattherBusinessSetup($uid) {
            $sql = "SELECT business_setup FROM business_sub WHERE owner_id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$uid]);
            $result = $run->fetch();

            return $result;
        }


        /* CHECK IF THERE IS A SERVICE UNDER THE BUSINESS WITH THE SAME NAME */
        protected function gattherServiceName($bID, $bService) {
            $sql = "SELECT service_name FROM business_services WHERE business_id = ? AND service_name = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $bService]);
            $result = $run->fetch();

            return $result;
        }

        /* INSERT SERVICES IN DATABASE */
        protected function insertServicesMinPrice($bID, $bService, $minPrice, $time) {
            $sql = "INSERT INTO business_services (business_id, service_name, minPrice, time) VALUES (?, ?, ?, ?)";

            $run    = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $bService, $minPrice, $time]);
        }

        protected function insertServicesPrice($bID, $bService, $minPrice, $maxPrice, $time) {
            $sql = "INSERT INTO business_services (business_id, service_name, minPrice, maxPrice, time) VALUES (?, ?, ?, ?, ?)";

            $run    = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $bService, $minPrice, $maxPrice, $time]);
        }

        /* GET ALL SERVICES */
        protected function getServices($bID) {
            $sql = "SELECT * FROM business_services WHERE business_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }

        /* DELETE SERVICE */
        protected function deleteService($bSID, $bID) {
            $sql = "DELETE FROM business_services WHERE id = ? AND business_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bSID, $bID]);
        }

        /* CHCEK IF THERE IS A BUSINESS UNDER THE SAME NAME */
    
        /* CHECK IF PHONE NUMBER EXIST */
        protected function gattherPhoneNumber($emPhone) {
            $sql = "SELECT phoneNumber FROM accounts WHERE phoneNumber = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$emPhone]);
            $result = $run->fetch();

            return $result;
        }

        /* GET UID FROM PHONE NUMBER */
        protected function gattherUIDFromPhone($emPhone) {
            $sql = "SELECT id FROM accounts WHERE phoneNumber = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$emPhone]);
            $result = $run->fetch();

            return $result;
        }

        /* INSERT EMPLOYEE IN DATABASE */
        protected function insertEmployee($bID, $uID) {
            $sql = "INSERT INTO employee (business_id, uid) VALUES (?, ?)";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bID, $uID]); 
        }


        /* GET ALL EMPLOYEE */
        protected function gattherEmployee($bID) {
            $sql = "SELECT * FROM employee WHERE business_id = ?";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }

        /* GET UID FROM PHONE NUMBER */
        protected function gattherAllFromPhone($id) {
            $sql = "SELECT * FROM accounts WHERE id = ?";

            $result = $this->connect_client()->prepare($sql);
            $result->execute([$id]);

            return $result;
        }

        /* DELETE EMPLOYEE */
        protected function deleteEmloyee($uid, $bID) {
            $sql = "DELETE FROM employee WHERE id = ? AND business_id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$uid, $bID]);
        }

        /* GET EMPLOYEE ID */
        protected function gattherEmployeeID($employeeID) {
            $sql = "SELECT uid FROM employee WHERE id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$employeeID]);
            $result = $run->fetch();

            return $result;
        }

        /* REMOVE ALL EMPLOYEE SERVIES */
        protected function deleteServicesEmployee($bID, $uid) {
            $sql = "DELETE FROM employee_services WHERE business_id = ? AND user_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $uid]);
        }

        /* GET ALL CLIENTS OF A BUSINESS */
        protected function insertNewClient($bID, $uID) {
            $sql = "INSERT INTO  business_clients (business_id, user_id) VALUES (?, ?)";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$bID, $uID]);
        }

        /* GET ALL CLIENTS OF A BUSINESS */
        protected function gattherClients($bID) {
            $sql = "SELECT * FROM business_clients WHERE business_id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$bID]);

            return $run;
        }

        /* GET ALL EMPLOYEES BASED ON SERVICE ID */
        protected function gattherEmpBasedServ($bID, $servID) {
            $sql = "SELECT * FROM employee_services WHERE business_id = ? AND service_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID, $servID]);

            return $result;
        }

        protected function gattherNameBasedOnUID($uid) {
            $sql = "SELECT firstName, lastName, phoneNumber FROM accounts WHERE id = ?";

            $result = $this->connect_client()->prepare($sql);
            $result->execute([$uid]);

            return $result;
        }

        /* CHECK IF USER EMPLOYEED */
        protected function gattherUIDBasedOnID($uid) {
            $sql = "SELECT id FROM employee WHERE uid = ?";
            
            $run = $this->connect_business()->prepare($sql);
            $run->execute([$uid]);
            $result = $run->fetch();

            return $result;
        }

        /* CHECK IF ALREADY EXISTING CLIENT */
        protected function gattherClientID($uid, $bID) {
            $sql = "SELECT user_id FROM business_clients WHERE user_id = ? AND business_id = ?";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$uid, $bID]);
            $result = $run->fetch();

            return $result;
        }

        /* INSERT REVIEW */
        protected function insertReviews($businessID, $review, $stars, $clientName) {
            $sql = "INSERT INTO reviews (business_id, review, stars, clientName) VALUES (?, ?, ?, ?)";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$businessID, $review, $stars, $clientName]); 
        }

        /* GET ALL REVIEWS BASED ON BUSINESS ID */
        protected function gattherReviews($bID) {
            $sql = "SELECT * FROM reviews WHERE business_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }

        /* GET ALL PHOTOS OF A BUSINESS */
        protected function gattherBusinessGallery($bID) {
            $sql = "SELECT * FROM business_gallery WHERE business_id = ? ORDER BY imageNo";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }

        /* UPDATE PHOTOS OF A BUSINESS */
        protected function updateBusinessGallery($imageName, $bID, $imageNo) {
            $sql = "UPDATE business_gallery SET imageName = ? WHERE business_id = ? AND imageNo = ?";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$imageName, $bID, $imageNo]);
        }

        /* INSERT BUSINESS DETAILS IN DATABASE */
        protected function insertBusinessGallery($bID, $imageNo, $imageName) {
            $sql    = "INSERT INTO business_gallery (business_id, imageNo, imageName) VALUES (?, ?, ?)";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bID, $imageNo, $imageName]);
        }

        /* GET ALL REVIEWS BASED ON BUSINESS ID */
        protected function gattherAllClients($bID) {
            $sql = "SELECT user_id FROM business_clients WHERE business_id = ?";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }

        /* INSERT FREE DAYS */
        protected function insertFreeDays($businessID, $dayNo) {
            $sql = "INSERT INTO business_freetime (business_id, days) VALUES (?, ?)";

            $run = $this->connect_business()->prepare($sql);
            $run->execute([$businessID, $dayNo]); 
        }

        /* INSERT FREE DAYS */
        protected function selectFreeDays($businessID) {
            $sql = "SELECT * FROM business_freetime WHERE business_id = ?";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$businessID]);

            return $result;
        }

        /* INSERT BUSINESS DETAILS IN DATABASE */
        protected function insertWorkingHours($bID, $dayNo, $bOpen, $bClose, $freeDays) {
            $sql    = "INSERT INTO business_workinghours (business_id, dayNo, openingHours, closingHours, freeDay) VALUES (?, ?, ?, ?, ?)";

            $run    = $this->connect_business()->prepare($sql);
            $run->execute([$bID, $dayNo, $bOpen, $bClose, $freeDays]);
        }

        /* INSERT FREE DAYS */
        protected function selectWorkingDays($businessID, $dayNo) {
            $sql = "SELECT * FROM business_workinghours WHERE business_id = ? AND dayNo = ?";

            $result = $this->connect_business()->prepare($sql);
            $result->execute([$businessID, $dayNo]);

            return $result;
        }
    }

?>