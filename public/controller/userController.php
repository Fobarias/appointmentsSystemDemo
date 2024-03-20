<?php

    class userController extends users {
        public function saveTokenRequest($tokenRequest) {
            $this->insertTokenRequest($tokenRequest);
        }

        public function getLoginToken($loginToken) {
            $result = $this->gattherLoginToken($loginToken);
            return $result['token_request'];
        }
        
        public function saveUserToken($userToken, $loginToken) {
            $this->insertUserToken($userToken, $loginToken);
        }

        public function getUID($loginToken) {
            $result = $this->gattherUID($loginToken);
            return $result['user_id'];
        }

        public function getEndingSub($user) {
            $result = $this->gatterEndingTimeSub($user);
            return $result['end_date'];
        }

        public function getAllInfo($phoneNumber) {
            $result = $this->gattherAllInfo($phoneNumber);
            return $result;
        }

        public function getIDFromPhone($phoneNumber) {
            $result = $this->gattherAllInfoFetch($phoneNumber);
            return $result;
        }

        public function getUIDPhone($phoneNumber) {
            $result = $this->gattherUIDBasedOnPhone($phoneNumber);
            return $result['id'] ?? '';
        }

        public function saveNewEmployee($firstName, $lastName, $phoneNumber, $pass) {
            $this->insertNewEmployee($firstName, $lastName, $phoneNumber, $pass);
        }

        public function saveNewClient($firstName, $lastName, $email, $phoneNumber, $address) {
            $this->insertNewClient($firstName, $lastName, $email, $phoneNumber, $address);
        }

        public function getLatestID() {
            $result = $this->gattherLatestID();
            return $result['id'];
        }

        public function saveEmpInfo($firstName, $lastName, $email, $address, $phoneNumber) {
            $this->updateUserInfo($firstName, $lastName, $email, $address, $phoneNumber);
        }

        public function saveDefaultData($firstName, $lastName, $phoneNumber) {
            $this->insertDefaultData($firstName, $lastName, $phoneNumber);
        }

        public function saveUserPass($pass, $id) {
            $this->updateUserPass($pass, $id);
        }
    }

?>

