<?php
/**
 * Created by PhpStorm.
 * User: itsan
 * Date: 8/18/2016
 * Time: 10:37 PM
 */

require_once "classes/membership.php";
$membership = new membership();
$membership ->isAuthenticated();

?>
<h1>Logged in page</h1>
<a href="login.php?status=loggedOut">Logout</a>
