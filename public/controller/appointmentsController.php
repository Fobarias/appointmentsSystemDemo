<?php

    class appointmentsController extends appointments {
        public function saveAppSett($clientAddr, $empChoose, $bID) {
            $this->saveAppSettings($clientAddr, $empChoose, $bID);
        }

        public function getClientAddr($bID) {
            $result = $this->gattherClientAddr($bID);
            return $result['clientAddr'];
        }

        public function getEmpChoose($bID) {
            $result = $this->gattherClientAddr($bID);
            return $result['empChoose'];
        }

        public function getServBasedEmp($bID, $empID) {
            $result = $this->gattherServBasedEmp($bID, $empID);
            return $result;
        }

        public function getServName($servID) {
            $result = $this->gattherServName($servID);
            return $result['service_name'];
        }

        public function getInfoEmpAttr($bID, $uID, $servID) {
            $result = $this->gattherEmpInfoAttr($bID, $uID, $servID);
            return $result['id'] ?? '';
        }

        public function insertEmpAttr($bID, $uID, $servID) {
            $this->insertServiceProvided($bID, $uID, $servID);
        }

        public function deleteAttr($uID, $servID, $bID) {
            $this->deleteServiceProvided($uID, $servID, $bID);
        }

        public function getServ($servID) {
            $result = $this->gattherServ($servID);
            return $result;
        }

        public function insertAppnt($bID, $serviceName, $serviceID, $empID, $uID, $date, $serviceStart, $serviceTime) {
            $this->insertAppt($bID, $serviceName, $serviceID, $empID, $uID, $date, $serviceStart, $serviceTime);
        }

        public function getAllInfo($uid) {
            $result = $this->gattherAllInfoFetch($uid);
            return $result;
        }

        public function getAptInfo($aptID, $bID) {
            $result = $this->gattherAppointmentInfo($aptID, $bID);
            return $result;
        }

        public function removeApt($aptID, $bID) {
            $this->deleteAppointment($aptID, $bID);
        }
        
        public function getClientId($id) {
            $result = $this->gattherClientID($id);
            return $result;
        }

        public function getLastID() {
            $result = $this->gattherLastID();
            return $result;
        }

        public function saveConfirmStat($aptID, $bID) {
            $this->updateConfirmStat($aptID, $bID);
        }
    }
?>