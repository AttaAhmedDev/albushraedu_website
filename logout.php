<?php
session_start();

// حذف جميع بيانات الجلسة
session_unset();
session_destroy();

// التحويل إلى صفحة login
header("Location: login.php");
exit;
