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

                // $query_post = mysqlQuery_atgssite("SELECT * FROM wp_posts WHERE post_type='news' AND post_date > (NOW() - INTERVAL 1 MONTH) ORDER BY post_date DESC LIMIT 5");
                $query_post = mysqlQuery_atgssite("SELECT * FROM wp_posts WHERE post_type='news' ORDER BY post_date DESC LIMIT 5");
                $j = $i;
                while ($row_post = mysqli_fetch_array($query_post)) {
                    $postID = $row_post['id'];

                    $__tmp1 = array("[:ru]", "[:]");
                    $str = str_replace($__tmp1, "", $row_post["post_title"]);
                    $array = explode(" ", $str);
                    $array = array_slice($array, 0, 10);
                    // $postTitle = implode(" ", $array);
                    $postTitle = $str;
                    $postName = $row_post['post_name'];
                    $postDate = date('d.m.Y в H:i', strtotime($row_post["post_date"]));

                    $post_link = '<a href="http://www.atgs.ru/news/' . $postName . '" target="_blank">' . $postTitle . '</a>';

                    $outputArr[$j] =
                        [
                            "servicename" => "atgs.ru",
                            "date" => date('d.m.Y', strtotime($row_post["post_date"])),
                            "time" => date('H:i', strtotime($row_post["post_date"])),
                            "author" => "",
                            "posttitle" => $postTitle,
                            "postlink" => $post_link,
                            "topiclink" => ""
                        ];
                    $j++;
                }

                function sort_date($a_new, $b_new) {
                    $a_new = strtotime($a_new["date"]);
                    $b_new = strtotime($b_new["date"]);
                    return $b_new - $a_new;
                }

                usort($outputArr, "sort_date");


                $output = $personsIn . "<<>>" . $personsOut;
            }
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
// echo $output;
echo json_encode($outputArr, JSON_UNESCAPED_UNICODE);