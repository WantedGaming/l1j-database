<?php
require_once '../includes/functions.php';

// End the admin session
endAdminSession();

// Redirect to login page
header('Location: login.php');
exit;
