<?php
    setcookie('token', '', time()-60*60*24*90, '/', '', 0, 0);
    unset($_COOKIE['token']);
    header('Location: /');
?>