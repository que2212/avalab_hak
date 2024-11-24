<?php
session_start();
session_unset();
session_destroy();
header("Location: /olimpiada_laws/login.php");
exit();

?>