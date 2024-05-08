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
$limit = 300.00;
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

                $output .= '<div class="d-flex flex-column">';
                if ($_QRY && $unique_key != "") {
                    $output .= '<img id="QR-currentDinner" class="mb-3 w-75 mx-auto" src="http://qrcoder.ru/code/?http%3A%2F%2Fdinner.atgs.ru%2Findex.php%3Fview_my_order_today%3D' . $unique_key . '&3&0" alt="Мой обед сегодня" data-toggle="popover" />';
                    $output .= '<p class="small text-center mb-4">' . $userName . ', <br>Ваш QR-код проверен и актуален</p>';
                } else {
                    $output .= '<p class="small text-danger text-center mb-4">' . $userName . '!<br>Если вы не видите QR-код или с ним что-то не так, срочно телеграфируйте администратору</p>';
                }
                $output .= '<div class="d-flex flex-row justify-content-between mb-3">';
                if ($_QRY_SUMTOT && $sumtot != "") {
                    $output .= '<div class="d-flex flex-column align-items-center w-50">';
                    $output .= '<div class="digit text-info" data-toggle="popover" data-content="Общая сумма за текущий месяц, на которую вы сделали заказы">' . number_format($sumtot, 0, ".", " ") . '</div>';
                    $output .= '<div class="text text-secondary">съели за месяц</div>';
                    $output .= '</div>';
                } else {
                    $output .= '';
                }
                if ($_QRY_NUM != "") {
                    $output .= '<div class="d-flex flex-column align-items-center w-50">';
                    $output .= '<div class="digit text-info" data-toggle="popover" data-content="Количество дней за текущий месяц, на которые вы сделали заказ">' . $_QRY_NUM . '</div>';
                    $output .= '<div class="text text-secondary">заказов за месяц</div>';
                    $output .= '</div>';
                } else {
                    $output .= '';
                }
                $output .= '</div>';
                //
                //
                $output .= '<div class="d-flex flex-row justify-content-between">';
                if ($_QRY_1 && !empty($sum_0) && !empty($sum_dop)) {
                    $output .= '<div class="d-flex flex-column align-items-center w-50">';
                    $output .= '<div class="digit text-info" data-toggle="popover" data-content="Сумма на текущий месяц, которая будет покрыта компанией из расчета лимита в ' . $limit . ' р.">' . number_format($sum_0, 0, ".", " ") . '</div>';
                    $output .= '<div class="text text-secondary">заплатит АТГС</div>';
                    $output .= '</div>';
                    $output .= '<div class="d-flex flex-column align-items-center w-50">';
                    $output .= '<div class="digit text-info data-toggle="popover" data-content="Сумма на текущий месяц, не покрываемая лимитом, которая будет вычтена из вашей зарплаты">' . number_format($sum_dop, 0, ".", " ") . '</div>';
                    $output .= '<div class="text text-secondary">заплатите вы</div>';
                    $output .= '</div>';
                } else {
                    $output .= '';
                }
                $output .= '</div>';
                $output .= '</div>';
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $output;
