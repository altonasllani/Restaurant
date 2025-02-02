<?php
session_start(); // Starto sesionin
session_unset(); // Fshi të gjitha variablat e sesionit
session_destroy(); // Shkatërro sesionin
header("Location: index.php"); // Ridrejto në faqen kryesore pas shkyçjes
exit();
?>