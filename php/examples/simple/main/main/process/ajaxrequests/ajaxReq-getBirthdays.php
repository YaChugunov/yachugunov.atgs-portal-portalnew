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
// список месяцев с названиями для замены
$_monthsList = array(
    ".01." => "января", ".02." => "февраля",
    ".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня",
    ".07." => "июля", ".08." => "августа", ".09." => "сентября",
    ".10." => "октября", ".11." => "ноября", ".12." => "декабря"
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
                $_QRY = mysqlQuery("SELECT kodwoker, kodwokersex, wokerbithdaydate, DAY(wokerbithdaydate) as day FROM hr_wokermaindata 
                WHERE kodwoker NOT IN (SELECT kodwokerkart FROM hr_docwokerkart WHERE koddel='99' OR archwoker='1' OR archkart='1') 
                AND ((MONTH(wokerbithdaydate) = MONTH(CURRENT_DATE) AND (DAYOFMONTH(wokerbithdaydate)-DAYOFMONTH(CURRENT_DATE) BETWEEN 0 AND 3)) 
                OR (MONTH(wokerbithdaydate)=IF(MONTH(CURRENT_DATE)+1=13,1,MONTH(CURRENT_DATE)+1) AND (DAYOFMONTH(CURRENT_DATE)-DAYOFMONTH(wokerbithdaydate)) BETWEEN 27 AND 30)) ORDER BY day ASC");
                if (mysqli_num_rows($_QRY) > 0) {
                    while ($_ROW = mysqli_fetch_array($_QRY)) {
                        $sex = ($_ROW['kodwokersex'] == "000000000000001") ? "M" : "F";
                        $_ROW1 = mysqli_fetch_array(mysqlQuery("SELECT wokerendname, workerendnamemain, wokerfistname, workerfistnamemain FROM hr_docwokerkart WHERE kodwokerkart = '" . $_ROW['kodwoker'] . "' AND koddel <> '99' AND archwoker <> '1'"));

                        $firstname = ($_ROW1['workerfistnamemain'] != "") ? $_ROW1['workerfistnamemain'] : (($_ROW1['wokerfistname'] != "") ? $_ROW1['wokerfistname'] : "John");
                        $lastname = ($_ROW1['workerendnamemain'] != "") ? $_ROW1['workerendnamemain'] : (($_ROW1['wokerendname'] != "") ? $_ROW1['wokerendname'] : "Doe");

                        $birthday = new DateTime($_ROW['wokerbithdaydate']);
                        $interval = $birthday->diff(new DateTime);
                        $formatted = ($sex == "M") ? " (" . ($interval->y) . ")" : "";
                        $formatted2 = ($sex == "M") ? " (" . (($interval->y) + 1) . ")" : "";

                        $birthday2 = date("d.m.", strtotime($_ROW['wokerbithdaydate']));
                        // $_mD = date(".m.");
                        $_mD = date(".m.", strtotime($_ROW['wokerbithdaydate']));
                        $birthday2 = str_replace($_mD, " " . $_monthsList[$_mD] . " ", $birthday2);

                        $_ROW2 = mysqli_fetch_array(mysqlQuery("SELECT kodofficework FROM hr_docwokerproftmp WHERE kodwoker = '" . $_ROW['kodwoker'] . "' AND koddel <> '99'"));
                        $office = "";
                        if ($_ROW2) {
                            $_ROW3 = mysqli_fetch_array(mysqlQuery("SELECT nameofficeplacefull FROM hr_docworkerofficeplace WHERE kodofficeplace = '" . $_ROW2['kodofficework'] . "' AND koddel <> '99'"));
                            $office = $_ROW3['nameofficeplacefull'];
                        }
                        $office_ed = str_replace('"', "", $office);
                        $office_ed = str_replace("'", "", $office_ed);
                        if (date("m-d", strtotime($_ROW['wokerbithdaydate'])) == date("m-d")) {
                            if (!empty($useLightTheme) && $useLightTheme == '1') {
                                $output .= '<div class="d-flex flex-row justify-content-center my-2" data-toggle="popover" data-content="' . $office_ed . '"><i class="fa-solid fa-cake-candles fa-xl text-primary mr-2 align-self-center"></i><div><span class="text-dark mr-1"><b>' . $firstname . ' ' . $lastname . '</b>,</span><span class="">' . $birthday2 . $formatted . '</span></div></div>';
                            } else {
                                $output .= '<div class="d-flex flex-row text-white justify-content-center my-2" data-toggle="popover" data-content="' . $office_ed . '"><i class="fa-solid fa-cake-candles fa-xl text-warning mr-2 align-self-center"></i><div><span class="mr-1"><b>' . $firstname . ' ' . $lastname . '</b>,</span><span class="">' . $birthday2 . $formatted . '</span></div></div>';
                            }
                        } else {
                            if (!empty($useLightTheme) && $useLightTheme == '1') {
                                $output .= '<div class="d-flex flex-row text-secondary justify-content-center my-2" data-toggle="popover" data-content="' . $office_ed . '"><div><span class="mr-1">' . $firstname . ' ' . $lastname . ',</span><span class="">' . $birthday2 . $formatted2 . '</span></div></div>';
                            } else {
                                $output .= '<div class="d-flex flex-row text-secondary justify-content-center my-2" data-toggle="popover" data-content="' . $office_ed . '"><div><span class="mr-1">' . $firstname . ' ' . $lastname . ',</span><span class="">' . $birthday2 . $formatted2 . '</span></div></div>';
                            }
                        }
                    }
                } else {
                    $output .= '<div class="my-auto"><p class="text-center">В ближайшие три дня именинников не ожидается...</p><p class="text-center"><i class="fa-regular fa-face-frown-open fa-2xl"></i></p></div>';
                }
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;