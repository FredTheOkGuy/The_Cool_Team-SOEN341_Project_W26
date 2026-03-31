<?php
// Simple logout function, that just destroys the current session information and sends you to the login page (index)
session_start();
session_unset();
session_destroy();
header("Location: /src/views/index.php");
exit();
?>