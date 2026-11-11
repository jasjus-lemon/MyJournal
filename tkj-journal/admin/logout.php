<?php
require 'inc.php';
unset($_SESSION['guru'], $_SESSION['guru_nama']);
header('Location: ../login.php');
