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
                $_QRY = mysqlQuery("SELECT kodwoker, kodwokersex, wokerbithdaydate, DAY(wokerbithdaydate) as day, MONTH(wokerbithdaydate) as month FROM hr_wokermaindata
                WHERE kodwoker NOT IN (SELECT kodwokerkart FROM hr_docwokerkart WHERE koddel='99' OR archwoker='1' OR archkart='1')
                AND ((MONTH(wokerbithdaydate) = MONTH(CURRENT_DATE) AND (DAYOFMONTH(wokerbithdaydate)-DAYOFMONTH(CURRENT_DATE) BETWEEN 0 AND 3))
                OR (MONTH(wokerbithdaydate)=IF(MONTH(CURRENT_DATE)+1=13,1,MONTH(CURRENT_DATE)+1) AND (DAYOFMONTH(CURRENT_DATE)-DAYOFMONTH(wokerbithdaydate)) BETWEEN 27 AND 30)) ORDER BY month ASC, day ASC");
                if (mysqli_num_rows($_QRY) > 0) {
                    while ($_ROW = mysqli_fetch_array($_QRY)) {
                        $kodwoker = !empty($_ROW['kodwoker']) ? $_ROW['kodwoker'] : "";
                        $sex = ($_ROW['kodwokersex'] == "000000000000001") ? "M" : "F";
                        $_ROW1 = mysqli_fetch_array(mysqlQuery("SELECT wokerendname, workerendnamemain, wokerfistname, workerfistnamemain FROM hr_docwokerkart WHERE kodwokerkart = '" . $kodwoker . "' AND koddel <> '99' AND archwoker <> '1'"));

                        $firstname = ($_ROW1['workerfistnamemain'] != "") ? $_ROW1['workerfistnamemain'] : (($_ROW1['wokerfistname'] != "") ? $_ROW1['wokerfistname'] : "John");
                        $lastname = ($_ROW1['workerendnamemain'] != "") ? $_ROW1['workerendnamemain'] : (($_ROW1['wokerendname'] != "") ? $_ROW1['wokerendname'] : "Doe");

                        $birthday = new DateTime($_ROW['wokerbithdaydate']);
                        $today = new DateTime("today");
                        $dayBirth = $birthday->format('d');
                        $dayNow = $today->format('d');

                        $interval = $birthday->diff(new DateTime);
                        $formatted = ($sex == "M") ? " (" . ($interval->y) . ")" : "";
                        $formatted2 = ($sex == "M") ? " (" . (($interval->y) + 1) . ")" : "";
                        $formattedOut = $dayBirth === $dayNow ? $formatted : $formatted2;
                        $birthday2 = date("d.m.", strtotime($_ROW['wokerbithdaydate']));
                        // $_mD = date(".m.");
                        $_mD = date(".m.", strtotime($_ROW['wokerbithdaydate']));
                        $birthday2 = str_replace($_mD, " " . $_monthsList[$_mD] . " ", $birthday2);
                        $birthday2 = !empty($birthday2) ? ", " . $birthday2 : "";

                        $_ROW2 = mysqli_fetch_array(mysqlQuery("SELECT kodofficework FROM hr_docwokerproftmp WHERE kodwoker = '" . $kodwoker . "' AND koddel <> '99'"));
                        $dopinfo = "";
                        $office = "";
                        $office_city = "";
                        if ($_ROW2) {
                            $_ROW3 = mysqli_fetch_array(mysqlQuery("SELECT * FROM hr_docworkerofficeplace WHERE kodofficeplace = '" . $_ROW2['kodofficework'] . "' AND koddel <> '99'"));
                            $office = $_ROW3['nameofficeplacefull'];
                            $office_city = !empty($_ROW3['nameofficecity']) ? $_ROW3['nameofficecity'] : "";
                        }
                        $office_ed = str_replace('"', "", $office);
                        $office_ed = str_replace("'", "", $office_ed);

                        $_QRY_NewInfo2 = mysqli_fetch_assoc(mysqlQuery("
                        SELECT kodwoker, koddoljprof as koddolj, kodofficework as kodoffice, kodstructpodr as kodstruc, kodrazdid as kodrazd, namedoljtmp as namedolj FROM hr_docwokerproftmp tb2 WHERE kodwoker='" . $kodwoker . "'"));
                        $dolj = !empty($_QRY_NewInfo2['namedolj']) ? $_QRY_NewInfo2['namedolj'] : "";

                        if (!empty($_QRY_NewInfo2['kodrazd'])) {
                            $_QRY_NewInfo4 = mysqli_fetch_assoc(mysqlQuery("
                        SELECT namerazdshot as nameshort, namerazdfull as namefull FROM hr_sporgschema WHERE kodrazdid ='{$_QRY_NewInfo2['kodrazd']}'
                        "));
                            $namepodr = $_QRY_NewInfo4['nameshort'];
                        } elseif (!empty($_QRY_NewInfo2['kodstruc'])) {
                            $_QRY_NewInfo4 = mysqli_fetch_assoc(mysqlQuery("
                            SELECT shortname2 as nameshort FROM ism_spstructpodr WHERE kodstructpodr ='{$_QRY_NewInfo2['kodstruc']}'
                            "));
                            $namepodr = $_QRY_NewInfo4['nameshort'];
                        } else {
                            $namepodr = "";
                        }

                        $dopinfo = !empty($office_city) ? $office_city : "";
                        // $dopinfo .= !empty($dopinfo) && !empty($namepodr) ? ", " . $namepodr : "";
                        $dopinfo .= !empty($dopinfo) && !empty($dolj) ? ", " . $dolj : "";

                        if (date("m-d", strtotime($_ROW['wokerbithdaydate'])) == date("m-d")) {
                            if (!empty($useLightTheme) && $useLightTheme == '1') {
                                $icon = '<i class="fa-solid fa-cake-candles fa-beat fa-lg" style="color:#EA4335 !important"></i>';
                                $output .= '<div class="media p-2 mb-1 reportStatus"><div class="rounded-circle mr-3">' . $icon . '</div><div class="media-body"><h5 class="mt-0 mb-0" style="color:#6400FF !important">' . $firstname . ' ' . $lastname . $birthday2 . $formattedOut . '</h5><p class="mb-0">' . $dopinfo . '</p></div></div>';
                            } else {
                                $icon = '<i class="fa-solid fa-cake-candles fa-beat fa-lg" style="color:#EA4335 !important"></i>';
                                $output .= '<div class="media p-2 mb-1 reportStatus"><div class="rounded-circle mr-3">' . $icon . '</div><div class="media-body"><h5 class="mt-0 mb-0" style="color:#FFC107 !important">' . $firstname . ' ' . $lastname . $birthday2 . $formattedOut . '</h5><p class="mb-0">' . $dopinfo . '</p></div></div>';
                            }
                        } else {
                            if (!empty($useLightTheme) && $useLightTheme == '1') {
                                $icon = '<i class="fa-regular fa-face-smile-wink fa-lg" style="color:#646C73 !important"></i>';
                                $output .= '<div class="media p-2 mb-1 reportStatus"><div class="rounded-circle mr-3">' . $icon . '</div><div class="media-body"><h5 class="mt-0 mb-0" style="color:#646C73 !important">' . $firstname . ' ' . $lastname . $birthday2 . $formattedOut . '</h5><p class="mb-0">' . $dopinfo . '</p></div></div>';
                            } else {
                                $icon = '<i class="fa-regular fa-face-smile-wink fa-lg" style="color:#646C73 !important"></i>';
                                $output .= '<div class="media p-2 mb-1 reportStatus"><div class="rounded-circle mr-3">' . $icon . '</div><div class="media-body"><h5 class="mt-0 mb-0">' . $firstname . ' ' . $lastname . $birthday2 . $formattedOut . '</h5><p class="mb-0">' . $dopinfo . '</p></div></div>';
                            }
                        }
                    }
                } else {
                    $output .= '<div class="my-auto" style="font-size:0.75rem"><p class="text-center">В ближайшие три дня именинников не ожидается...</p><p class="text-center"><i class="fa-solid fa-face-sad-tear fa-2xl"></i></p></div>';
                }
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;