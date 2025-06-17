<?php
session_start();
$_SESSION['test'] = 'Session Berjalan!';
echo $_SESSION['test'];
