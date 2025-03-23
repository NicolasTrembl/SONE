<?php
session_start();

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function login($userId) {
    $_SESSION['user_id'] = $userId;
}

function logout() {
    session_destroy();
}
?>
