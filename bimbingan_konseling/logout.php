<?php
// File: logout.php

// Memulai session
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login dengan pesan sukses (opsional)
header('Location: login.php?status=logout_success');
exit;
?>
