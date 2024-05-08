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
$outputArr = array();

if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {

                $_QRY_NewPersons = mysqlQuery("SELECT kodwokerkart, wokerendname, wokerfistname, wokersecondname FROM hr_docwokerkart WHERE kodwokerkart IN (SELECT kodwoker FROM hr_docworkerlaborcontr WHERE datelaborcontract > NOW() - INTERVAL 14 DAY ORDER BY datelaborcontract DESC)");
                $personsIn = "";
                $i = 0;
                while ($_ROW_NewPersons = mysqli_fetch_assoc($_QRY_NewPersons)) {
                    $_QRY_NewInfo1 = mysqli_fetch_assoc(mysqlQuery("
                    SELECT kodwoker, numberlaborcontract as numdoc, datelaborcontract as datedoc, null, null, null FROM hr_docworkerlaborcontr tb1 WHERE kodwoker='{$_ROW_NewPersons['kodwokerkart']}'"));

                    $_QRY_NewInfo2 = mysqli_fetch_assoc(mysqlQuery("
                    SELECT kodwoker, koddoljprof as koddolj, kodofficework as kodoffice, kodstructpodr as kodstruc, kodrazdid as kodrazd, namedoljtmp as namedolj FROM hr_docwokerproftmp tb2 WHERE kodwoker='{$_ROW_NewPersons['kodwokerkart']}' 
                    "));

                    $_QRY_NewInfo3 = mysqli_fetch_assoc(mysqlQuery("
                    SELECT nameofficecity as city FROM hr_docworkerofficeplace WHERE kodofficeplace='{$_QRY_NewInfo2['kodoffice']}' 
                    "));

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

                    $personsIn .= $_ROW_NewPersons['wokerendname'] . " " . $_ROW_NewPersons['wokerfistname'] . " " . $_ROW_NewPersons['wokersecondname'];
                    $personsIn .= "|";

                    $outputArr['in'][$i]['name'] = $_ROW_NewPersons['wokerendname'] . " " . $_ROW_NewPersons['wokerfistname'] . " " . $_ROW_NewPersons['wokersecondname'];
                    $outputArr['in'][$i]['date'] = !empty($_QRY_NewInfo1['datedoc']) ? date('d.m.Y', strtotime($_QRY_NewInfo1['datedoc'])) : "";
                    $outputArr['in'][$i]['order'] = !empty($_QRY_NewInfo1['numdoc']) ? $_QRY_NewInfo1['numdoc'] : "";
                    $outputArr['in'][$i]['office'] = !empty($_QRY_NewInfo3['city']) ? $_QRY_NewInfo3['city'] : "";
                    $outputArr['in'][$i]['dept'] = $namepodr;
                    $outputArr['in'][$i]['dolj'] = !empty($_QRY_NewInfo2['namedolj']) ? $_QRY_NewInfo2['namedolj'] : "";
                    $i++;
                }
                //
                $_QRY_OutPersons = mysqlQuery("SELECT kodwokerkart, wokerendname, wokerfistname, wokersecondname FROM hr_docwokerkart WHERE kodwokerkart IN (SELECT kodwoker FROM hr_docworkerworkend WHERE datedocworkend > NOW() - INTERVAL 14 DAY ORDER BY datedocworkend DESC)");
                $personsOut = "";
                $i = 0;
                while ($_ROW_OutPersons = mysqli_fetch_assoc($_QRY_OutPersons)) {
                    $_QRY_OutInfo1 = mysqli_fetch_assoc(mysqlQuery("SELECT numberdocworkend as numdoc, datedocworkend as datedoc FROM hr_docworkerworkend WHERE kodwoker='{$_ROW_OutPersons['kodwokerkart']}'"));

                    $_QRY_NewInfo2 = mysqli_fetch_assoc(mysqlQuery("
                    SELECT kodwoker, koddoljprof as koddolj, kodofficework as kodoffice, kodstructpodr as kodstruc, kodrazdid as kodrazd, namedoljtmp as namedolj FROM hr_docwokerproftmp tb2 WHERE kodwoker='{$_ROW_OutPersons['kodwokerkart']}' 
                    "));

                    $_QRY_NewInfo3 = mysqli_fetch_assoc(mysqlQuery("
                    SELECT nameofficecity as city FROM hr_docworkerofficeplace WHERE kodofficeplace='{$_QRY_NewInfo2['kodoffice']}' 
                    "));

                    $personsOut .= $_ROW_OutPersons['wokerendname'] . " " . $_ROW_OutPersons['wokerfistname'] . " " . $_ROW_OutPersons['wokersecondname'];
                    $personsOut .= "|";

                    $outputArr['out'][$i]['name'] = $_ROW_OutPersons['wokerendname'] . " " . $_ROW_OutPersons['wokerfistname'] . " " . $_ROW_OutPersons['wokersecondname'];
                    $outputArr['out'][$i]['date'] = !empty($_QRY_OutInfo1['datedoc']) ? date('d.m.Y', strtotime($_QRY_OutInfo1['datedoc'])) : "";
                    $outputArr['out'][$i]['order'] = !empty($_QRY_OutInfo1['numdoc']) ? $_QRY_OutInfo1['numdoc'] : "";
                    $outputArr['out'][$i]['office'] = !empty($_QRY_NewInfo3['city']) ? $_QRY_NewInfo3['city'] : "";
                    $i++;
                }
                $output = $personsIn . "<<>>" . $personsOut;
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
// echo $output;
echo json_encode($outputArr, JSON_UNESCAPED_UNICODE);