<?php
//session_start();
session_destroy();
header("Location: app/views/login.php");
exit();