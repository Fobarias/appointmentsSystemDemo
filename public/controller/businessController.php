<?php

    class businessController extends business {
        public function saveDataBusiness($bID, $bOwnerUID, $bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType) {
            $this->insertBusinessDetails($bID, $bOwnerUID, $bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType);
        }

        public function getBusinessID($bOwnerUID, $bName) {
            $result = $this->gattherBusinessID($bOwnerUID, $bName);
            return $result['id'];
        }

        public function updateBusiness($bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType, $bID) {
            $this->updateBusinessDetails($bName, $bAddr, $bOpen, $bClose, $bCountry, $bCity, $bType, $bID);
        }

        public function updateBusinessWithOwnerID($bName, $bAddr, $bCountry, $bCity, $bType, $oID) {
            $this->updateBusinessDetailsWithOWID($bName, $bAddr, $bCountry, $bCity, $bType, $oID);
        }

        public function getBusinessData($uid) {
            $result = $this->gattherBusinessDetails($uid);
            return $result;
        }

        public function getBusinessDataID($id) {
            $result = $this->gattherBusinessDetailsID($id);
            return $result;
        }

        public function updateBusSetup($uid) {
            $this->updateBusinessSetup($uid);
        }

        public function getBusinessSetup($uid) {
            $result = $this->gattherBusinessSetup($uid);
            return $result['business_setup'] ?? '';
        }

        public function getServiceName($bID, $bService) {
            $result = $this->gattherServiceName($bID, $bService);
            return $result['service_name'] ?? '';
        }

        public function saveServicesMinPrice($bID, $bService, $minPrice, $time) {
            $this->insertServicesMinPrice($bID, $bService, $minPrice, $time);
        }

        public function saveServicesPrice($bID, $bService, $minPrice, $maxPrice, $time) {
            $this->insertServicesPrice($bID, $bService, $minPrice, $maxPrice, $time);
        }

        public function removeService($bSID, $bID) {
            $this->deleteService($bSID, $bID);
        }

        public function getPhoneNumber($emPhone) {
            $result = $this->gattherPhoneNumber($emPhone);
            return $result['phoneNumber'] ?? '';
        }

        public function getUIDFromPhone($emPhone) {
            $result = $this->gattherUIDFromPhone($emPhone);
            return $result['id'] ?? '';
        }

        public function saveEmployee($bID, $uID) {
            $this->insertEmployee($bID, $uID);
        }

        public function removeEmployee($uid, $bID) {
            $this->deleteEmloyee($uid, $bID);
        }

        public function getEmployeeID($employeeID) {
            $result = $this->gattherEmployeeID($employeeID);
            return $result['uid'];
        }

        public function removeEmployeeServices($bID, $uid) {
            $this->deleteServicesEmployee($bID, $uid);
        }

        public function getClients($bID) {
            $result = $this->gattherClients($bID);
            return $result;
        }

        public function getEmpBasedServ($bID, $servID) {
            $result = $this->gattherEmpBasedServ($bID, $servID);
            return $result;
        }

        public function getNameBasedUID($uid) {
            $result = $this->gattherNameBasedOnUID($uid);
            return $result;
        }

        public function getUIDOfID($uid) {
            $result = $this->gattherUIDBasedOnID($uid);
            return $result['id'] ?? '';
        }

        public function saveNewClients($bID, $uID) {
            $this->insertNewClient($bID, $uID);
        }

        public function getClientID($uid, $bID) {
            $result = $this->gattherClientID($uid, $bID);
            return $result ?? '';
        }

        public function saveReviews($businessID, $review, $stars, $clientName) {
            $this->insertReviews($businessID, $review, $stars, $clientName);
        }

        public function getBusinessGallery($bID) {
            $result = $this->gattherBusinessGallery($bID);
            return $result;
        }

        public function setBusinessGallery($imageName, $bID, $imageNo) {
            $this->updateBusinessGallery($imageName, $bID, $imageNo);
        }

        public function saveBusinessGallery($bID, $imageNo, $imageName) {
            $this->insertBusinessGallery($bID, $imageNo, $imageName);
        }

        public function saveFreeDays($businessID, $dayNo) {
            $this->insertFreeDays($businessID, $dayNo);
        }

        public function freeDays($bID) {
            $result = $this->selectFreeDays($bID);
            return $result;
        }

        public function getBusinessDataBasedOnName($bName) {
            $result = $this->gattherBusinessDetailsBasedOnName($bName);
            return $result;
        }

        public function saveWorkingHours($bID, $dayNo, $bOpen, $bClose, $freeDays) {
            $this->insertWorkingHours($bID, $dayNo, $bOpen, $bClose, $freeDays);
        }

        public function workingHours($bID, $dayNo) {
            $result = $this->selectWorkingDays($bID, $dayNo);
            return $result;
        }
    }

?>