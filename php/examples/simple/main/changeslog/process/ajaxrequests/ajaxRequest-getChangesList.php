<?php
date_default_timezone_set('Europe/Moscow');
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# Подключаем конфигурационный файл
require(__DIR_ROOT . __ADM_FOLDER . '/config.adminize.inc.php');
# Подключаемся к базе
require(__DIR_ASSETS . '/dbconn/db_connection.php');
# Подключаем общие функции безопасности
require(__DIR_ASSETS . '/functions/func.cmn.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require(__DIR_ASSETS . '/functions/func.adminize.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
# session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

$monthArr = ['', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
$output = $changesArr = $changesYearArr = $changesMonthArr = $changesItemArr = array();


# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

$error = $success = "";

if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            for ($YR = date('Y'); $YR > 2015; $YR--) {
                $_QRY_MONTH = mysqlQuery("SELECT MONTH(timestamp) as chMonth FROM changeslog WHERE kodstatus='399111233518001' AND YEAR(timestamp) IN ($YR) GROUP BY chMonth ORDER BY timestamp DESC");
                for ($MN = 12; $MN > 0; $MN--) {
                    $_QRY = mysqlQuery("SELECT * FROM changeslog WHERE kodstatus='399111233518001' AND YEAR(timestamp) IN ($YR) AND MONTH(timestamp)='$MN' ORDER BY timestamp DESC");
                    while ($_ROW = mysqli_fetch_assoc($_QRY)) {
                        //
                        $_QRY_Status = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM changeslog_statuslist WHERE kodstatus='{$_ROW['kodstatus']}' ORDER BY kodstatus ASC"));
                        $CH_status = $_QRY_Status['title'];
                        //
                        $_QRY_Service = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM changeslog_servicelist WHERE kodservice='{$_ROW['kodservice']}' ORDER BY kodservice ASC"));
                        $CH_service = $_QRY_Service['title'];
                        //
                        $_QRY_Flag = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM changeslog_flaglist WHERE kodflag='{$_ROW['kodflag']}' ORDER BY kodflag ASC"));
                        $CH_flag = $_QRY_Flag['title'];
                        //
                        $_QRY_Restr = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM changeslog_restrlist WHERE kodrestr='{$_ROW['kodrestr']}' ORDER BY kodrestr ASC"));
                        $CH_restriction = $_QRY_Restr['title'];
                        //
                        $_QRY_Type = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM changeslog_typelist WHERE kodtype='{$_ROW['kodtype']}' ORDER BY kodtype ASC"));
                        $CH_type = $_QRY_Type['title'];
                        //
                        $CH_comment = $_ROW['comment'];
                        $CH_timestamp = $_ROW['timestamp'];
                        $CH_shortname = $_ROW['shortname'];
                        $CH_fullname = $_ROW['fullname'];
                        $CH_linkimg = $_ROW['linkimg'];
                        $CH_link1 = $_ROW['link1'];
                        $CH_link2 = $_ROW['link2'];
                        $CH_kodchange = $_ROW['kodchange'];

                        $changesArr[$YR][] = [
                            'change' => [
                                'year'          => "{$YR}",
                                'month'         => "{$MN}",
                                'status'        => "{$CH_status}",
                                'timestamp'     => "{$CH_timestamp}",
                                'service'       => "{$CH_service}",
                                'comment'       => "{$CH_comment}",
                                'flag'          => "{$CH_flag}",
                                'restriction'   => "{$CH_restriction}",
                                'shortname'     => "{$CH_shortname}",
                                'fullname'      => "{$CH_fullname}",
                                'linkimg'       => "{$CH_linkimg}",
                                'link1'         => "{$CH_link1}",
                                'link2'         => "{$CH_link2}",
                                'kodchange'     => "{$CH_kodchange}",
                                'type'          => "{$CH_type}",
                            ]
                        ];
                    }
                }
            }
        } else {
            $output = '-1';
        }
    }
}
// Вывод сообщений о результате загрузки.
echo json_encode($changesArr);
// echo $YR;