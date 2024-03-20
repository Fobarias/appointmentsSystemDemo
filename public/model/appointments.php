<?php

    class appointments extends database {
        /* SAVE CLIENT ADDRESS, EMPLOYEE CHOOSE */
        protected function saveAppSettings($clientAddr, $empChoose, $bID) {
            $sql    = "UPDATE appointments_settings SET clientAddr = ?, empChoose = ? WHERE business_id = ?";

            $run    = $this->connect_appoiments()->prepare($sql);
            $run->execute([$clientAddr, $empChoose, $bID]);
        }

        protected function gattherClientAddr($bID) {
            $sql = "SELECT clientAddr, empChoose FROM appointments_settings WHERE business_id = ?";
            
            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID]);
            $result = $run->fetch();

            return $result;
        }

        /* GET ALL SERVICES BASED ON EMPLOYEE ID */
        protected function gattherServBasedEmp($bID, $empID) {
            $sql = "SELECT * FROM employee_services WHERE business_id = ? AND user_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID, $empID]);

            return $result;
        }

        /* GATTHER SERVICE NAME BASED ON SERVICE ID */
        protected function gattherServName($servID) {
            $sql = "SELECT service_name FROM business_services WHERE id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$servID]);
            $result = $run->fetch();

            return $result;
        }

        /* CHECK IF EMPLOYEE ALREADY HAS ATTR */
        protected function gattherEmpInfoAttr($bID, $uID, $servID) {
            $sql = "SELECT id FROM employee_services WHERE business_id = ? AND user_id = ? AND service_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $uID, $servID]);
            $result = $run->fetch();

            return $result;
        }

        /* SET EMPLOYEE ATTR */
        protected function insertServiceProvided($bID, $uID, $servID) {
            $sql = "INSERT INTO employee_services (business_id, user_id, service_id) VALUES (?, ?, ?)";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $uID, $servID]);
        }

        /* DELETE EMPLOYEE ATTR */
        protected function deleteServiceProvided($uID, $servID, $bID) {
            $sql = "DELETE FROM employee_services WHERE user_id = ? AND service_id = ? AND business_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$uID, $servID, $bID]);
        }

        /* GATTHER * BASED ON SERVICE ID */
        protected function gattherServ($servID) {
            $sql = "SELECT * FROM business_services WHERE id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$servID]);
            $result = $run->fetch();

            return $result;
        }

        protected function gattherAppointments($bID, $date, $empID) {
            $sql = "SELECT * FROM appointments WHERE business_id = ? AND date = ? AND empID = ? ORDER BY serviceStart ASC";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID, $date, $empID]);

            return $result;
        }

        protected function insertAppt($bID, $serviceName, $serviceID, $empID, $uID, $date, $serviceStart, $serviceTime) {
            $sql = "INSERT INTO appointments (business_id, serviceName, serviceID, empID, userID, date, serviceStart, serviceTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$bID, $serviceName, $serviceID, $empID, $uID, $date, $serviceStart, $serviceTime]);
        }

        /* GET CLIENT INFO BASED ON ID */
        protected function gattherAllInfoFetch($uid) {
            $sql = "SELECT * FROM accounts WHERE id = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$uid]);
            $result = $run->fetch();
            

            return $result;
        }

        /* GET APPOINTMENT DETAILS BASED ON ID */ 
        protected function gattherAppointmentInfo($aptID, $bID) {
            $sql = "SELECT * FROM appointments WHERE id = ? AND business_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$aptID, $bID]);
            $result = $run->fetch();

            return $result;
        }

        /* DELETE APPOINTMENT */
        protected function deleteAppointment($aptID, $bID) {
            $sql = "DELETE FROM appointments WHERE id = ? AND business_id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$aptID, $bID]);
        }

        /* GET ALL SERVICES */
        protected function getServices($bID) {
            $sql = "SELECT * FROM business_services WHERE business_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID]);

            return $result;
        }
        
        /* SHOW SERVICES FOR A EMPLOYEE */
        protected function getServicesForEmp($bID, $uID) {
            $sql = "SELECT service_id FROM employee_services WHERE business_id = ? AND user_id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$bID, $uID]);

            return $result;
        }

        /* GET APPOINTMENT DETAILS BASED ON ID */ 
        protected function gattherClientID($id) {
            $sql = "SELECT * FROM appointments WHERE id = ?";

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([$id]);
            $result = $run->fetch();

            return $result;
        }

        protected function gattherLastID() {
            $sql = "SELECT id FROM appointments ORDER BY id DESC LIMIT 1"; 

            $run = $this->connect_appoiments()->prepare($sql);
            $run->execute([]);
            $result = $run->fetch();

            return $result;
        }

        /* GATTHER ALL INFO ABOUT BUSINESS APPOINTMENTS */
        protected function gattherClients($id) {
            $sql = "SELECT * FROM appointments WHERE business_id = ?"; 

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$id]);

            return $result;
        }

        /* GATTHER * BASED ON SERVICE ID */
        protected function getAllServInfo($servID) {
            $sql = "SELECT * FROM business_services WHERE id = ?";

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$servID]);

            return $result;
        }

        /* GATTHER * BASED ON DATE */
        protected function getAllServInfoDate($id, $dateFrom, $dateTo) {
            $sql = "SELECT * FROM appointments WHERE business_id = ? AND date >= ? AND date < ? ORDER BY date ASC"; 

            $result = $this->connect_appoiments()->prepare($sql);
            $result->execute([$id, $dateFrom, $dateTo]);

            return $result;
        }

        /* SAVE CLIENT ADDRESS, EMPLOYEE CHOOSE */
        protected function updateConfirmStat($aptID, $bID) {
            $sql    = "UPDATE appointments SET confirmed = 1 WHERE id = ? AND business_id = ?";

            $run    = $this->connect_appoiments()->prepare($sql);
            $run->execute([$aptID, $bID]);
        }
    }

?>