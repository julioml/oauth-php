<?php
    require_once 'vendor/autoload.php';
    session_start();

    // init configuration
    $clientID = '353922802035-d0p6qvqm02e3mrtf4pra9k5v2bo6rk4v.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/oauth-php/welcome.php';

    // create Client Request to access Google API
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    $hostname = "localhost";
    $username = "root";
    $password = "tf8d111b279";
    $database = "oauth-php";

    $conn = mysqli_connect($hostname, $username, $password, $database);
?>