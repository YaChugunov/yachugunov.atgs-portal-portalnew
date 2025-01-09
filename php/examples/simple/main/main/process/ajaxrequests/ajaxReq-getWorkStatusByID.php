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
// список месяцев с названиями для замены
$_monthsList = array(
    ".01." => "января", ".02." => "февраля",
    ".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня",
    ".07." => "июля", ".08." => "августа", ".09." => "сентября",
    ".10." => "октября", ".11." => "ноября", ".12." => "декабря",
);
// текущая дата
$currentDate = date("d.m.Y");
// переменная $currentDate теперь хранит текущую дату в формате 22.07.2015
// но так как наша задача - вывод русской даты,
// заменяем число месяца на название:
$_mD = date(".m."); //для замены
$currentDate = str_replace($_mD, " " . $_monthsList[$_mD] . " ", $currentDate);
// теперь в переменной $currentDate хранится дата в формате 22 июня 2015
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
$userid = !empty($_SESSION['id']) ? $_SESSION['id'] : "";
$_QRY_Settings = mysqli_fetch_array(mysqlQuery("SELECT use_lightTheme FROM portal_userSettingsUI WHERE ID = '{$userid}'"));
$useLightTheme = !empty($_QRY_Settings) ? $_QRY_Settings['use_lightTheme'] : "";
#
$reportID = !empty($_POST['reportid']) ? $_POST['reportid'] : "";
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$output = "";
$outputArr = array();
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {

                $_QRY = mysqli_fetch_array(mysqlQuery_atgsdinner("SELECT * FROM portal_absenceReportLog WHERE state='1' AND reportType NOT IN (9) AND reportID='{$reportID}' AND service_userID='{$userid}'"));

                if ($_QRY) {
                    $userID = !empty($_QRY['service_userID']) ? $_QRY['service_userID'] : "";
                    $dateFrom = !empty($_QRY['datefrom']) ? date("Y-m-d", strtotime($_QRY['datefrom'])) : null;
                    $dateTo = !empty($_QRY['dateto']) ? date("Y-m-d", strtotime($_QRY['dateto'])) : null;
                    $reportName = !empty($_QRY['reportName']) ? $_QRY['reportName'] : "";
                    $reportStatus = !empty($_QRY['reportStatus']) ? $_QRY['reportStatus'] : "";
                    $reportType = !empty($_QRY['reportType']) ? $_QRY['reportType'] : "";
                    $reportOption = !empty($_QRY['reportOption']) ? $_QRY['reportOption'] : "";
                    $comment = !empty($_QRY['comment']) ? $_QRY['comment'] : "";

                    $outputArr['reportID'] = $reportID;
                    $outputArr['userID'] = $userID;
                    $outputArr['reportType'] = $reportType;
                    $outputArr['reportOption'] = $reportOption;
                    $outputArr['reportName'] = $reportName;
                    $outputArr['reportStatus'] = $reportStatus;
                    $outputArr['dateFrom'] = $dateFrom;
                    $outputArr['dateTo'] = $dateTo;
                    $outputArr['comment'] = $comment;
                }
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo json_encode($outputArr, JSON_UNESCAPED_UNICODE);