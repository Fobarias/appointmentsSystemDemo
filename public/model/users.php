<?php

    class users extends database {
        /* SAVE TOKEN REQUEST INTO DATABASE */
        protected function insertTokenRequest($tokenRequest) {
            $sql    = "INSERT INTO login_tokens (token_request, login_site, redirect_link) VALUES (?, 'artty_appoiments', 'http://localhost/Artty%20Ecosystem/Artty%20Appoiments/Version%200.0.1A/public/')";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$tokenRequest]);
        }

        /* SELECTE TOKEN REQUEST */
        protected function gattherLoginToken($loginToken) {
            $sql    = "SELECT token_request FROM login_tokens WHERE login_token = ?";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$loginToken]);
            $result = $run->fetch();

            return $result;
        }

        /* SAVE USER LOGIN TOKEN */
        protected function insertUserToken($userToken, $loginToken) {
            $sql    = "UPDATE login_tokens SET user_token = ? WHERE login_token = ?";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$userToken, $loginToken]);
        }

        /* GET USER ID BASED ON USER LOGIN TOKEN */
        protected function gattherUID($loginToken) {
            $sql    = "SELECT user_id FROM login_tokens WHERE user_token = ?";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$loginToken]);
            $result = $run->fetch();

            return $result;
        }

        /* GET SUBSCRIPTION ENDING TIME */
        protected function gatterEndingTimeSub($user) {
            $sql = "SELECT end_date FROM subscriptions WHERE uid = ?";
            
            $run = $this->connect_client()->prepare($sql);
            $run->execute([$user]);
            $result = $run->fetch();

            return $result;
        }

        protected function gattherAllInfo($phoneNumber) {
            $sql = "SELECT * FROM accounts WHERE phoneNumber = ?";

            $result = $this->connect_client()->prepare($sql);
            $result->execute([$phoneNumber]);
            

            return $result;
        }

        protected function gattherAllInfoFetch($phoneNumber) {
            $sql = "SELECT * FROM accounts WHERE phoneNumber = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$phoneNumber]);
            $result = $run->fetch();
            

            return $result;
        }

        protected function gattherUIDBasedOnPhone($phoneNumber) {
            $sql = "SELECT id FROM accounts WHERE phoneNumber = ?";
            
            $run = $this->connect_client()->prepare($sql);
            $run->execute([$phoneNumber]);
            $result = $run->fetch();

            return $result;
        }

        /* ADD NEW EMPLOYEE TO ARTTY ID */
        protected function insertNewEmployee($firstName, $lastName, $phoneNumber, $pass) {
            $sql    = "INSERT INTO accounts (firstName, lastName, phoneNumber, pass) VALUES (?, ?, ?, ?)";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$firstName, $lastName, $phoneNumber, $pass]);
        }

        /* SAVE EMPLOYEE INFORMATION */
        protected function updateUserInfo($firstName, $lastName, $email, $address, $phoneNumber) {
            $sql = "UPDATE accounts SET firstName = ?, lastName = ?, email = ?, address = ? WHERE phoneNumber = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$firstName, $lastName, $email, $address, $phoneNumber]);
        }

        /* CREATE CLIENT / USER */
        protected function insertDefaultData($firstName, $lastName, $phoneNumber) {
            $sql    = "INSERT INTO accounts (firstName, lastName, phoneNumber) VALUES (?, ?, ?)";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$firstName, $lastName, $phoneNumber]);
        }

        /* ADD NEW CLIENTS TO ARTTY ID */
        protected function insertNewClient($firstName, $lastName, $email, $phoneNumber, $address) {
            $sql    = "INSERT INTO accounts (firstName, lastName, email, phoneNumber, address) VALUES (?, ?, ?, ?, ?)";

            $run    = $this->connect_client()->prepare($sql);
            $run->execute([$firstName, $lastName, $email, $phoneNumber, $address]);
        }

        /* SAVE NEW PASSWORD */
        protected function updateUserPass($pass, $id) {
            $sql = "UPDATE accounts SET pass = ? WHERE id = ?";

            $run = $this->connect_client()->prepare($sql);
            $run->execute([$pass, $id]);
        }
    }

?>