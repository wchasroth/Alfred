<?php
   header('Content-type: application/json');

   $accessToken = ["access_token" => "abcdef", "name" => "charles" ];
   echo json_encode($accessToken);
