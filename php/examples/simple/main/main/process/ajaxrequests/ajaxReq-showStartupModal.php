<?php
date_default_timezone_set('Europe/Moscow');
# Подключаем конфигурационный файл
require $_SERVER['DOCUMENT_ROOT'] . '/config.inc.php';
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require_once __DIR_ROOT . __SERVICENAME_PORTALNEW . '/config.portal.inc.php';
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
require_once __DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_connection.php';
require_once __DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_controller.php';
$db_handle = new DBController();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
require_once __DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.secure.inc.php';
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require_once __DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.portal.inc.php';
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
// session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$msgid = isset($_POST['msgid']) ? $_POST['msgid'] : "";
$userid = $_SESSION['id'];
$result = "";
$_row = array();
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return -2;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) && !empty($msgid)) {
                $_req = mysqli_fetch_array(mysqlQuery("SELECT * FROM admin_startmodalmsg WHERE status = '1' AND msgid NOT IN (SELECT msgid FROM admin_startmodalmsg_check WHERE userid='{$userid}') ORDER BY id DESC LIMIT 1"));
                $_row['msgid'] = !empty($_req['msgid']) ? $_req['msgid'] : "";
                $_row['filepath'] = !empty($_req['filepath']) ? $_req['filepath'] : "";
                $_row['portal'] = !empty($_req['portal']) ? $_req['portal'] : "";
            } else {
                $result = 0;
            }
        } else {
            $result = -1;
        }
    }
}
$output = is_array($_row) ? json_encode($_row) : 0;
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;