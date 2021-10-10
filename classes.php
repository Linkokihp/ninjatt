<?php

    class user{
        private $UserId, $UserName, $UserMail, $UserPassword, $UserNinja, $OnlineState, $X, $Y;

        //GET/SET UserId
        public function getUserId() {
            return $this->UserId;
        }
        public function setUserId($UserId) {
            $this->UserId=$UserId;
        }

        //GET/SET UserName
        public function getUserName() {
            return $this->UserName;
        }
        public function setUserName($UserName) {
            $this->UserName=$UserName;
        }

        //GET/SET UserMail
        public function getUserMail() {
            return $this->UserMail;
        }
        public function setUserMail($UserMail) {
            $this->UserMail=$UserMail;
        }

        //GET/SET UserPassword
        public function getUserPassword() {
            return $this->UserPassword;
        }
        public function setUserPassword($UserPassword) {
            $this->UserPassword=$UserPassword;
        }

        //GET/SET UserNinja
        public function getUserNinja() {
            return $this->UserNinja;
        }
        public function setUserNinja($UserNinja) {
            $this->UserNinja=$UserNinja;
        }

        //GET/SET OnlineState
        public function getOnlineState() {
            return $this->OnlineState;
        }
        public function setOnlineState($OnlineState) {
            $this->OnlineState=$OnlineState;
        }

        //GET/SET X-Position
        public function getXPosition() {
            return $this->X;
        }
        public function setXPosition($X) {
            $this->X=$X;
        }

        //GET/SET Y-Position
        public function getYPosition() {
            return $this->Y;
        }
        public function setYPosition($Y) {
            $this->Y=$Y;
        }

        //Display OnlineUsers
        public function displayOnlineUsers(){
            include 'config.php';
            $Onlinereq=$bdd->prepare("SELECT * FROM Users Where 1");
            $Onlinereq->execute();

            while($Result = $Onlinereq->fetch()) {
                if($Result['OnlineState'] == 1) {
                ?>
                    <li><i class="fas fa-user-ninja" style="color: green;"></i> <?php echo $Result['UserName'];?></li>
                <?php }
                }
            }

        //Display Character
        public function displayCharacter(){
            include 'config.php';
            $Charreq=$bdd->prepare("SELECT * FROM users WHERE UserName=:UserName");
            $Charreq->execute(array(
                'UserName'=>$this->getUserName()
            ));

            while($Result = $Charreq->fetch()) {
                if($Result['OnlineState'] == 1) {
                    ?>
                    <div class="username"><?php echo $Result['UserName'];?></span></div>
                    <div class="shadow pixel-art"></div>
                    <div style='background-image: url("src/chars/ninja-<?php echo $Result['UserNinja'];?>.png");' class="character_spritesheet pixel-art"></div>
                    <div class="chatMessages"></div>
                    <?php  
                }
            }
        }


        //Update Ninjafit
        public function updateNinjafit() {
            include "config.php";
            $req =$bdd->prepare("UPDATE users SET UserNinja=:UserNinja WHERE UserName=:UserName");

            //Execute Data
            $req->execute(array(
                'UserName'=>$this->getUserName(),
                'UserNinja'=>$this->getUserNinja()
            ));
        }

        //Insert user into db
        public function insertUser() {
            include "config.php";
            $req=$bdd->prepare("INSERT INTO users(UserName,UserMail,UserPassword,UserNinja,OnlineState) VALUES(:UserName,:UserMail,:UserPassword,'default',0)");

            //Execute Data
            $req->execute(array(
                'UserName'=>$this->getUserName(),
                'UserMail'=>$this->getUserMail(),
                'UserPassword'=>$this->getUserPassword()
            ));
        }

        //User Login
        public function userLogin() {
            include "config.php";
            $req=$bdd->prepare("SELECT * FROM users WHERE UserMail=:UserMail AND UserPassword=:UserPassword");
            $onlineState =$bdd->prepare("UPDATE users SET OnlineState= 1 WHERE UserMail=:UserMail");

            //Execute Data
            $req->execute(array(
                'UserMail'=>$this->getUserMail(),
                'UserPassword'=>$this->getUserPassword()
            ));

            $onlineState->execute(array(
                'UserMail'=>$this->getUserMail()
            ));

            if($req->rowCount()==0) {
                header("Location:index.php?error=1");
                return false;
            } else {
                while($data=$req->fetch()){
                    $this->setUserId($data['UserId']);
                    $this->setUserName($data['UserName']);
                    $this->setUserPassword($data['UserPassword']);
                    $this->setUserMail($data['UserMail']);
                    header("Location:../game.php");
                    return true;
                }
            }
        }

        //User Logout onlineState
        public function userLogout($userMail) {
            include "config.php";
            $onlineState =$bdd->prepare("UPDATE users SET OnlineState= 0 WHERE UserMail=:UserMail");
            $onlineState->execute(array(
                'UserMail'=> $userMail
            ));
        }

        // escape value from form
        public function esc(String $value) {
            // bring the global db connect object into function
            include "config.php";

            $val = trim($value); // remove empty space sorrounding string
            $val = mysqli_real_escape_string($conn, $value);

            return $val;
        }
    }


    class chat{
        private $ChatId, $ChatUserId, $ChatText;

        //GET/SET ChatId
        public function getChatId(){
            return $this->ChatId;
        }
        public function setChatId($ChatId){
            $this->ChatId = $ChatId;
        }

        //GET/SET ChatUserId
        public function getChatUserId(){
            return $this->ChatUserId;
        }
        public function setChatUserId($ChatUserId){
            $this->ChatUserId = $ChatUserId;
        }

        //GET/SET ChatText
        public function getChatText(){
            return $this->ChatText;
        }
        public function setChatText($ChatText){
            $this->ChatText = $ChatText;
        }

        //Insert ChatMessage
        public function insertChatMessage(){
            include 'config.php';
            $req=$bdd->prepare("INSERT INTO chat(ChatUserId, ChatText) VALUES (:ChatUserId, :ChatText)");

            //Execute Data
            $req->execute(array(
                'ChatUserId'=>$this->getChatUserId(),
                'ChatText'=>$this->getChatText()
            ));
        }
        
        //Display ChatMessage
        public function displayChatMessage(){
            include 'config.php';
            $ChatReq=$bdd->prepare("SELECT * FROM chat WHERE ChatUserId=:ChatUserId");
            $ChatReq->execute(array(
                'ChatUserId' =>$this->getChatUserId()
            ));

            while($DataChat = $ChatReq->fetch()){
                $UserReq=$bdd->prepare("SELECT * FROM users WHERE UserId=:UserId");
                //Execute Data
                $UserReq->execute(array(
                    'UserId' => $DataChat['ChatUserId']
                ));
                $DataUser = $UserReq->fetch();

                    ?>
                    <!-- start speech bubble -->
                    <div id="speech-bubble">
                        <div id="bub-part-a"></div>
                        <div id="speech-txt" class="chatMessages userMessages"><?php echo htmlspecialchars($DataChat['ChatText']);?></div>
                        <div id="bub-part-a"></div>

                        <div id="speech-arrow">
                            <div id="arrow-w"></div>
                            <div id="arrow-x"></div>
                            <div id="arrow-y"></div>
                            <div id="arrow-z"></div>
                        </div>
                    </div>
                    <!--end speech bubble-->
                <?php

            }
        }

        //Delete ChatMessage
        public function deleteChatMessage(){
            include 'config.php';
            $req=$bdd->prepare("DELETE FROM chat WHERE ChatUserId=ChatUserId");

            //Execute Data
            $req->execute(array(
                'ChatUserId'=>$this->getChatUserId(),
            ));
        }
    }



?>