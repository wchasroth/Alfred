<?php
   header('Content-type: application/json');

   $userProfile = ["id" => "101790690783650220217", "verified_email" => "1", "name" => "Charles Roth"];
   echo json_encode($userProfile);
