<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <title>Mr. Smith</title>
        <script src="/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/crypto-js.min.js" crossorigin="anonymous"></script>
        <script src="/js/jsencrypt.min.js" crossorigin="anonymous"></script>
        <script src="/js/jquery.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="">
<?php
    if(session_status() == PHP_SESSION_NONE){
        //session has not started
        session_start();
    }