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
function newReportID() {
    $_reqMaxID = mysqli_fetch_assoc(mysqlQuery_atgsdinner("SELECT MAX(reportID) as lastKod FROM portal_absenceReportLog ORDER BY id DESC"));
    return !empty($_reqMaxID['lastKod']) ? $_reqMaxID['lastKod'] + rand(3, 9) : null;
}
#
$userid = !empty($_SESSION['id']) ? $_SESSION['id'] : "";
#
$_QRY_Settings = mysqli_fetch_array(mysqlQuery("SELECT use_lightTheme FROM portal_userSettingsUI WHERE ID = '{$userid}'"));
$useLightTheme = !empty($_QRY_Settings) ? $_QRY_Settings['use_lightTheme'] : "";
#
$userID = !empty($_POST['userid']) ? $_POST['userid'] : "";
$action = !empty($_POST['action']) ? $_POST['action'] : "";
$reportID = !empty($_POST['reportid']) ? $_POST['reportid'] : "";
$reportType = !empty($_POST['reporttype']) ? $_POST['reporttype'] : "";
$reportOption = !empty($_POST['reportoption']) ? $_POST['reportoption'] : "";
$datefrom = !empty($_POST['datefrom']) ? date("Y-m-d", strtotime($_POST['datefrom'])) : "";
$dateto = !empty($_POST['dateto']) ? date("Y-m-d", strtotime($_POST['dateto'])) : "";
$comment = !empty($_POST['comment']) ? $_POST['comment'] : "";
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$output = "";
$outputArr = array();
$reportStatus = "";
$reportName = "";
$reportDates = "";
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {

                $timestamp = date("Y-m-d H:i:s");
                switch ($reportType) {
                case 1:
                    $reportName = 'Буду отсутствовать';
                    break;
                case 2:
                    $reportName = 'В отпуске';
                    break;
                case 3:
                    $reportName = 'На больничном';
                    break;
                case 4:
                    $reportName = 'В командировке';
                    break;
                case 5:
                    $reportName = 'Работаю дистанционно';
                    break;
                default:
                    $reportName = null;
                }
                //
                //
                switch ($reportOption) {
                case 1:
                    $date1 = new Datetime($datefrom);
                    $date1db = $date1->format("Y-m-d");
                    $date2db = '';
                    $reportDates = $date1->format("d.m.Y");
                    break;
                case 2:
                    $date1 = new Datetime($datefrom);
                    $date1db = $date1->format("Y-m-d");
                    $date2db = '';
                    $reportDates = $date1->format("d.m.Y");
                    break;
                case 3:
                    $date1 = new Datetime($datefrom);
                    $date1db = $date1->format("Y-m-d");
                    $date2db = '';
                    $reportDates = 'с ' . $date1->format("d.m.Y") . ' до отмены статуса';
                    break;
                case 4:
                    $date1 = new Datetime($datefrom);
                    $date1db = $date1->format("Y-m-d");
                    $date2 = new Datetime($dateto);
                    $date2db = $date2->format("Y-m-d");
                    $reportDates = 'с ' . $date1->format("d.m.Y") . ' по ' . $date2->format("d.m.Y");
                    break;
                default:
                    $reportDates = null;
                }
                //
                $reportStatus = $reportDates;
                //
                if ($action == "new") {
                    // Очищаем предыдущие статусы
                    $_QRY1 = mysqlQuery_atgsdinner("UPDATE portal_absenceReportLog SET state='0' WHERE state='1' AND service_userID='{$userID}'");
                    //
                    $reportID = newReportID();
                    //
                    $_QRY2 = mysqli_fetch_array(mysqlQuery_atgsdinner("SELECT * FROM botTelg_users WHERE service_userID='{$userID}'"));
                    $chatID = !empty($_QRY2['chatID']) ? $_QRY2['chatID'] : "";
                    // Делаем новую запись о новом статусе
                    if (empty($date2db)) {
                        $_QRY = mysqlQuery_atgsdinner("INSERT INTO portal_absenceReportLog (`reportID`, `chatID`, `service_userID`, `service_kodwoker`, `timestamp`, `reportType`, `reportOption`, `reportName`, `state`, `datefrom`, `dateto`, `reportStatus`, `comment`) VALUES ('{$reportID}', '{$chatID}', '{$userID}', null, '{$timestamp}', '{$reportType}', '{$reportOption}', '{$reportName}', '1', '{$date1db}', null, '{$reportStatus}', '{$comment}')");
                    } else {
                        $_QRY = mysqlQuery_atgsdinner("INSERT INTO portal_absenceReportLog (`reportID`, `chatID`, `service_userID`, `service_kodwoker`, `timestamp`, `reportType`, `reportOption`, `reportName`, `state`, `datefrom`, `dateto`, `reportStatus`, `comment`) VALUES ('{$reportID}', '{$chatID}', '{$userID}', null, '{$timestamp}', '{$reportType}', '{$reportOption}', '{$reportName}', '1', '{$date1db}', '{$date2db}', '{$reportStatus}', '{$comment}')");
                    }
                } elseif ($action == "edit") {
                    // Обновляем выбранную запись о статусе
                    if (empty($date2db)) {
                        $_QRY = mysqlQuery_atgsdinner("UPDATE portal_absenceReportLog SET timestamp='{$timestamp}', reportType='{$reportType}', reportOption='{$reportOption}', reportName='{$reportName}', reportStatus='{$reportStatus}', datefrom='{$date1db}', dateto=null, comment='{$comment}' WHERE reportID='{$reportID}' AND state='1' AND service_userID='{$userID}'");
                    } else {
                        $_QRY = mysqlQuery_atgsdinner("UPDATE portal_absenceReportLog SET timestamp='{$timestamp}', reportType='{$reportType}', reportOption='{$reportOption}', reportName='{$reportName}', reportStatus='{$reportStatus}', datefrom='{$date1db}', dateto='{$date2db}', comment='{$comment}' WHERE reportID='{$reportID}' AND state='1' AND service_userID='{$userID}'");
                    }
                }
                $output = $_QRY ? 1 : null;
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;