<?php
// start the session and clear all session data
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit;