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
$userid = $_SESSION['id'];
$_QRY_Settings = mysqli_fetch_array(mysqlQuery("SELECT use_lightTheme FROM portal_userSettingsUI WHERE ID = '{$userid}'"));
$useLightTheme = !empty($_QRY_Settings) ? $_QRY_Settings['use_lightTheme'] : "";
#
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
$output = "";
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {
                $_QRY1 = mysqlQuery("SELECT kodwoker, kodwokersex, wokerbithdaydate, DAY(wokerbithdaydate) as day FROM hr_wokermaindata
                WHERE kodwoker NOT IN (SELECT kodwokerkart FROM hr_docwokerkart WHERE koddel='99' OR archwoker='1' OR archkart='1')
                AND ((MONTH(wokerbithdaydate) = MONTH(CURRENT_DATE) AND (DAYOFMONTH(wokerbithdaydate)-DAYOFMONTH(CURRENT_DATE) BETWEEN 0 AND 3))
                OR (MONTH(wokerbithdaydate)=IF(MONTH(CURRENT_DATE)+1=13,1,MONTH(CURRENT_DATE)+1) AND (DAYOFMONTH(CURRENT_DATE)-DAYOFMONTH(wokerbithdaydate)) BETWEEN 27 AND 30)) ORDER BY day ASC");

                $_QRY = mysqlQuery_atgsdinner("SELECT * FROM portal_absenceReportLog WHERE state='1' AND reportType NOT IN (9)");

                if (mysqli_num_rows($_QRY) > 0) {
                    while ($_ROW = mysqli_fetch_array($_QRY)) {

                        $userID = $_ROW['service_userID'];
                        $reportID = $_ROW['reportID'];

                        $_REQ_PRF = mysqli_fetch_array(mysqlQuery("SELECT kodwoker FROM hr_docwokerproftmp WHERE userID= '{$userID}' AND koddel <> '99' AND kodwoker NOT IN (SELECT kodwokerkart FROM hr_docwokerkart WHERE koddel='99' OR archwoker='1' OR archkart='1')"));
                        $kodwoker = !empty($_REQ_PRF['kodwoker']) ? $_REQ_PRF['kodwoker'] : "";

                        $_REQ_MND = mysqli_fetch_array(mysqlQuery("SELECT kodwokersex FROM hr_wokermaindata WHERE kodwoker= '{$kodwoker}' ORDER BY id DESC LIMIT 1"));
                        $sex = ($_REQ_MND['kodwokersex'] == "000000000000001") ? "M" : "F";

                        $_REQ_KRT = mysqli_fetch_array(mysqlQuery("SELECT wokerendname, workerendnamemain, wokerfistname, workerfistnamemain FROM hr_docwokerkart WHERE kodwokerkart = '{$kodwoker}' AND koddel<>'99' AND archwoker<>'1' ORDER BY id DESC LIMIT 1"));

                        $firstname = ($_REQ_KRT['workerfistnamemain'] != "") ? $_REQ_KRT['workerfistnamemain'] : (($_REQ_KRT['wokerfistname'] != "") ? $_REQ_KRT['wokerfistname'] : "Иван");
                        $lastname = ($_REQ_KRT['workerendnamemain'] != "") ? $_REQ_KRT['workerendnamemain'] : (($_REQ_KRT['wokerendname'] != "") ? $_REQ_KRT['wokerendname'] : "Безымянный");

                        $dateFrom = date("d.m.y", strtotime($_ROW['datefrom']));
                        $dateTo = date("d.m.y", strtotime($_ROW['dateto']));
                        $reportStatus = !empty($_ROW['reportStatus']) ? $_ROW['reportStatus'] : "";
                        $reportType = !empty($_ROW['reportType']) ? $_ROW['reportType'] : "";

                        $comment = !empty($_ROW['comment']) ? $_ROW['comment'] : "";
                        $controlEl = $userID == $_SESSION['id'] ? '<span class="ml-auto">asdas</span>' : '';

                        switch ($reportType) {
                        case 1:
                            $reportIcon = '<i class="fa-solid fa-calendar-day fa-lg"></i>';
                            break;
                        case 2:
                            $reportIcon = '<i class="fa-solid fa-calendar-day fa-lg"></i>';
                            break;
                        case 3:
                            $reportIcon = '<i class="fa-solid fa-umbrella-beach fa-lg" style="color: #74C0FC;"></i>';
                            break;
                        case 4:
                            $reportIcon = '<i class="fa-solid fa-house-medical fa-beat fa-lg" style="color: #c83c19;"></i>';
                            break;
                        case 5:
                            $reportIcon = '<i class="fa-solid fa-calendar-day fa-lg"></i>';
                            break;
                        case 6:
                            $reportIcon = '<i class="fa-solid fa-calendar-day fa-lg"></i>';
                            break;
                        default:
                            $reportIcon = '<i class="fa-solid fa-calendar-day fa-lg"></i>';
                        }
                        if (!empty($useLightTheme) && $useLightTheme == '1') {
                            $output .= '<div class="media p-2 mb-1 reportStatus" data-reportID="' . $reportID . '"><div class="rounded-circle mr-3">' . $reportIcon . '</div><div class="media-body d-flex"><div class=""><h5 class="mt-0 mb-0">' . $firstname . ' ' . $lastname . '</h5><p class="mb-0 text-dark">' . $reportStatus . '</p><p class="mb-0"><i class="fa-solid fa-comment mr-2"></i>' . $comment . '</p></div></div></div>';
                        } else {
                            $output .= '<div class="media p-2 mb-1 reportStatus" data-reportID="' . $reportID . '"><div class="rounded-circle mr-3">' . $reportIcon . '</div><div class="media-body d-flex"><div class="d-flex flex-row"><h5 class="mt-0 mb-0">' . $firstname . ' ' . $lastname . '</h5>' . $controlEl . '</div><p class="mb-0 text-white">' . $reportStatus . '</p><p class="mb-0"><i class="fa-solid fa-comment mr-2"></i>' . $comment . '</p></div></div>';
                        }
                    }
                } else {
                    $output .= '<div class="my-auto" style="font-size:0.75rem"><p class="text-center">Видимо команда в полном составе...</p><p class="text-center"><i class="fa-solid fa-face-flushed fa-2xl"></i></p></div>';
                }
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;