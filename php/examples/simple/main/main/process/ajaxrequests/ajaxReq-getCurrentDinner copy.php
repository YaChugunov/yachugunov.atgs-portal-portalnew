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
$output = "";
$limit = 260.00;
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {
                $_QRY = mysqlQuery_atgsdinner("SELECT unique_key FROM users_unique_key WHERE id='" . $_SESSION['id'] . "' AND status = 1");
                $_ROW = mysqli_fetch_array($_QRY);
                $_QRY_SUMTOT = mysqlQuery_atgsdinner("SELECT SUM(item_total) as sum FROM `orders_item` WHERE MONTH(`order_to_date`) = MONTH(NOW()) AND YEAR(`order_to_date`) = YEAR(NOW()) AND `order_user_id`='" . $_SESSION['id'] . "'");
                $_ROW_SUMTOT = mysqli_fetch_array($_QRY_SUMTOT);

                $_QRY_NUM = mysqli_num_rows(mysqlQuery_atgsdinner("SELECT `order_id` FROM `orders_item` WHERE MONTH(`order_to_date`) = MONTH(NOW()) AND YEAR(`order_to_date`) = YEAR(NOW()) AND `order_user_id`='" . $_SESSION['id'] . "' GROUP BY `order_id`"));

                $unique_key = $_ROW['unique_key'];
                $sumtot = $_ROW_SUMTOT['sum'];
                $userName = $_SESSION['firstname'] . " " . $_SESSION['middlename'];

                $_QRY_1 = mysqlQuery_atgsdinner("SELECT `order_id`, SUM(item_total) as sum FROM `orders_item` WHERE MONTH(`order_to_date`) = MONTH(NOW()) AND YEAR(`order_to_date`) = YEAR(NOW()) AND `order_user_id`='" . $_SESSION['id'] . "' GROUP BY `order_id`");
                $sum_0 = 0.00;
                $sum_dop = 0.00;
                while ($_ROW_1 = mysqli_fetch_array($_QRY_1)) {
                    if ($_ROW_1['sum'] > $limit) {
                        $sum_0 += $limit;
                        $sum_dop += $_ROW_1['sum'] - $limit;
                    } else {
                        $sum_0 += $_ROW_1['sum'];
                        $sum_dop += 0.00;
                    }
                }

                if ($_QRY && $unique_key != "") {
                    $output .= '<img id="QR-currentDinner" class="img-thumbnail mb-3 w-50 mx-auto" src="http://qrcoder.ru/code/?http%3A%2F%2Fdinner.atgs.ru%2Findex.php%3Fview_my_order_today%3D' . $unique_key . '&3&0" alt="Мой обед сегодня" data-toggle="popover" />';
                    $output .= '<p class="small text-center text-light">' . $userName . ', <br>Ваш QR-код проверен и актуален</p>';
                } else {
                    $output .= '<p class="small text-danger text-center">' . $userName . '!<br>Если вы не видите QR-код или с ним что-то не так, срочно телеграфируйте администратору</p>';
                }
                if ($_QRY_SUMTOT && $sumtot != "") {
                    $output .= '<div class="lead">' . $sumtot . '<sub>съели за текущий месяц</sub></div>';
                } else {
                    $output .= '';
                }
                if ($_QRY_NUM != "") {
                    $output .= '<div class="lead">' . $_QRY_NUM . '<sub>cделали заказов</sub></div>';
                } else {
                    $output .= '';
                }
                if ($_QRY_1) {
                    $output .= '<div class="lead">' . $sum_0 . '<sub>заплатит фирма</sub>' . $limit . '</div>';
                    $output .= '<div class="lead">' . $sum_dop . '<sub>заплатите вы</sub></div>';
                } else {
                    $output .= '';
                }
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;
