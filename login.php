<?php
session_start();

require_once "classes/membership.php";
require_once "classes/dev.php";

$dev  = new dev();
//$dev->dumpPOST();

$membership = new membership();

if(isset($_GET['status']) && $_GET['status']=="loggedOut"){
    $membership->logOutUser();
}

if(isset($_GET['type']) && $_GET['type']=="fb"){
    $membership->handleFBLogin($_GET['id'], $_GET['name']);
}

if(isset($_GET['type']) && $_GET['type']=="google"){
    $membership->handleGoogleLogin($_GET['id'], $_GET['name']);
}

if($_POST && !empty($_POST['username']) && !empty($_POST['password'])){
    $response = $membership->verifyAuthentication($_POST['username'], $_POST['password']);
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="CSS/login.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.0.0/bootstrap-social.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://apis.google.com/js/api:client.js"></script>
    <script>
        function statusChangeCallback(response) {
            if (response.status === 'connected') {
                logInFB();
            } else if (response.status === 'not_authorized') {
                document.getElementById('status').innerHTML = 'Please log ' + 'into this app.';
            } else {
                document.getElementById('status').innerHTML = 'Please log ' +'into Facebook.';
            }
        }

        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });
        }

        window.fbAsyncInit = function() {
            FB.init({
                appId      : '165708737169252',
                cookie     : true,  // enable cookies to allow the server to access
                                    // the session
                xfbml      : true,  // parse social plugins on this page
                version    : 'v2.5' // use graph api version 2.5
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function logInFB() {
            FB.api('/me', function(response) {
                window.location.href = window.location.href.split('?')[0] + "?type=fb" + "&name=" + response.name + "&id=" + response.id;
            });
        }


        var googleUser = {};
        (function() {
            gapi.load('auth2', function(){
                auth2 = gapi.auth2.init({
                    client_id: '210773401839-7mkh7floh1nm5m5ilsks188f2fj5dkj2.apps.googleusercontent.com',
                    cookiepolicy: 'single_host_origin'
                });
                attachSignin(document.getElementById('googleLoginBtn'));
            });
        })();

        function attachSignin(element) {
            auth2.attachClickHandler(element, {},
                function(googleUser) {
                    onSignIn(googleUser);
                }, function(error) {
                    document.getElementById('status').innerHTML = JSON.stringify(error, undefined, 2);
                });
        }

        function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();
            window.location.href = window.location.href.split('?')[0] + "?type=google" + "&name=" + profile.getName() + "&id=" + profile.getId();
        }
    </script>
</head>
<body>

<div id="loginForm" class="bg-info panel panel-info">
    <form method="post" action="login.php" enctype="multipart/form-data">
        <div id="signIdlabel">
            <h3>
                Sign in LoginMechanism
            </h3>
        </div>
        <div class="form-group has-feedback has-feedback-right">
            <input type="text" name="username" id="username" placeholder="Username" class="form-control"/>
            <i class="form-control-feedback glyphicon-user glyphicon"></i>
        </div>
        <div class="form-group has-feedback has-feedback-right">
            <input type="password" name="password" id="password" placeholder="Password" class="form-control"/>
            <i class="form-control-feedback glyphicon-lock glyphicon"></i>
        </div>
        <input type="submit" value="Log In" id="submit" name="submit" class="btn btn-primary btn-block"/>
        <a class="btn btn-block btn-social btn-facebook" onclick="checkLoginState();">
            <span class="fa fa-facebook"></span> Sign in with Facebook
        </a>
        <a class="btn btn-block btn-social btn-google" id="googleLoginBtn">
            <span class="fa fa-google"></span> Sign in with Google
        </a>
        <div id="status">
            <?php if(isset($response)) echo "<h5>".$response."</h5>"; ?>
        </div>
        <!--<fb:login-button size="xlarge" scope="public_profile,email" onlogin="checkLoginState();">
        </fb:login-button>
        <div class="g-signin2" data-onsuccess="onSignIn" data-width="358" data-height="50" data-theme="dark" data-longtitle="true"></div>-->
    </form>
</div>
</body>
</html>