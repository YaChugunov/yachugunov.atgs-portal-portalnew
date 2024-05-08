<?php
date_default_timezone_set('Europe/Moscow');
# Подключаем конфигурационный файл
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/config.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_connection.php');
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_controller.php');
$db_handle = new DBController();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.secure.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require_once(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
// session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

$userid = $_SESSION['id'];
$output = "";
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {
                $_QRY = mysqli_fetch_array(mysqlQuery("SELECT * FROM portal_userSettingsUI WHERE ID = '{$userid}'"));
                if (!empty($_QRY)) {
                    $output .= $_QRY['enable_searchingMail'] . "///" . $_QRY['enable_searchingDog'] . "///" . $_QRY['enable_searchingSP'] . "///" . $_QRY['enable_searchingISM'] . "///" . $_QRY['enable_searchingHR'] . "///" . $_QRY['mainPageUI_showTopblock'] . "///" . $_QRY['mainPageUI_showLeftBottomblock'] . "///" . $_QRY['mainPageUI_showRightBottomblock'] . "///" . $_QRY['mainPageUI_showCloudblock'] . "///" . $_QRY['use_pushMessages'] . "///" . $_QRY['use_lightTheme'];
                } else {
                    $output .= 'error';
                }
            }
        } else {
            $output .= 'error';
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;