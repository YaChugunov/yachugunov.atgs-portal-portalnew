<?php
# Import PHPMailer classes into the global namespace
# These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
#
# Подключаем библиотеки
require "/var/www/html/atgs-portal.local/www/portalnew/_assets/libs/PHPMailer/src/Exception.php";
require "/var/www/html/atgs-portal.local/www/portalnew/_assets/libs/PHPMailer/src/PHPMailer.php";
require "/var/www/html/atgs-portal.local/www/portalnew/_assets/libs/PHPMailer/src/SMTP.php";
#
# 
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
# ##### КОНСТАНТЫ
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####

if (!isset($_IS_CRONTAB)) {
    $_reqDB_UIsettings = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM portal_userSettingsUI WHERE userid = '{$_SESSION['id']}'"));
    define('__UI_PERSONAL_PORTALNEW_SHOWTOPBLOCK', isset($_reqDB_UIsettings['mainPageUI_showTopblock']) ? $_reqDB_UIsettings['mainPageUI_showTopblock'] : '0');
    define('__UI_PERSONAL_PORTALNEW_SHOWLEFTBOTTOMBLOCK', isset($_reqDB_UIsettings['mainPageUI_showLeftBottomblock']) ? $_reqDB_UIsettings['mainPageUI_showLeftBottomblock'] : '0');
    define('__UI_PERSONAL_PORTALNEW_SHOWRIGHTBOTTOMBLOCK', isset($_reqDB_UIsettings['mainPageUI_showRightBottomblock']) ? $_reqDB_UIsettings['mainPageUI_showRightBottomblock'] : '0');
    define('__UI_PERSONAL_PORTALNEW_SHOWCLOUDBLOCK', isset($_reqDB_UIsettings['mainPageUI_showCloudblock']) ? $_reqDB_UIsettings['mainPageUI_showCloudblock'] : '0');
}
#
# 
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
# ##### КЛАССЫ
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####





# 
# 
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
# ##### ФУНКЦИИ
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####