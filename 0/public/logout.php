<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php?status=logged_out");
exit(); // Ensure no further code is executed
