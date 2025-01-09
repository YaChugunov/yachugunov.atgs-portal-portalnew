<?php
# http://192.168.1.89/dognet/_assets/cron-phpscript/dognet-crontab-actionScript-docKalplanProgressCorrection.php
#
$_IS_CRONTAB = TRUE;
#
$path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']); // определяем директорию скрипта
chdir($path_parts['dirname']); // задаем директорию выполнение скрипта
#
date_default_timezone_set('Europe/Moscow');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем конфигурационный файл
require_once "/var/www/html/atgs-portal.local/www/config.inc.php";
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require_once "/var/www/html/atgs-portal.local/www/portalnew/config.portal.inc.php";
#
# Подключаемся к базе
require_once "/var/www/html/atgs-portal.local/www/portalnew/_assets/dbconn/db_connection.php";
require_once "/var/www/html/atgs-portal.local/www/portalnew/_assets/dbconn/db_controller.php";
$db_handle = new DBController();
#
# Подключаем общие функции безопасности
require "/var/www/html/atgs-portal.local/www/portalnew/_assets/functions/func.secure.inc.php";
# Подключаем собственные функции сервиса Почта
require "/var/www/html/atgs-portal.local/www/portalnew/_assets/functions/func.portal.inc.php";
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
/**
 * !!! Сброс рабочего статус по истечение объявленного конечного срока
 *
 * ? > Сброс рабочего статус по истечение объявленного конечного срока
 * ? > Last edition 25.10.2024
 */
#

$datenow = date("Y-m-d");
$reqUpdate = mysqlQuery_atgsdinner("UPDATE portal_absenceReportLog SET state='0' WHERE ((datefrom<'{$datenow}' AND dateto IS NULL AND reportOption!='3') OR (dateto<'{$datenow}' AND datefrom IS NOT NULL)) AND state='1'");

unset($_IS_CRONTAB);