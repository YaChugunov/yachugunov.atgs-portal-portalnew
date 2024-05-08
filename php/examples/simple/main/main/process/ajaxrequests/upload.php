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
function newKodcloudfile() {
    $query  = mysqlQuery("SELECT MAX(kodcloudfile) as lastKod FROM portal_cloud_files ORDER BY id DESC");
    $row    = mysqli_fetch_assoc($query);
    $lastKod = $row['lastKod'];
    $newKod = $lastKod + rand(3, 13);
    return $newKod;
}

$d = dir("/mnt/cloudFiles/upload/");
$_PATHSRV = $d->path;
$_PATH = __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH .  "/cloudFiles/upload/";

$output = "";
$res = 1;
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ((checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 1 && checkUserRestrictions($_SESSION['id'], 'portalnew', 2, 0) == 1) or (checkServiceAccess('allservices') == 1 && checkServiceAccess('portalnew') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1) or (checkServiceAccess('allservices') == 0 && checkIsItSuperadmin($_SESSION['id']) == 1)) {

                if (isset($_FILES['file']['name'][0])) {
                    foreach ($_FILES['file']['name'] as $keys => $values) {
                        $kodcloudfile = newKodcloudfile();
                        $file_name = "PC." . $kodcloudfile . "-" . $values;
                        if (move_uploaded_file($_FILES['file']['tmp_name'][$keys], $_PATHSRV . $file_name)) {
                            $dateloaded = date("Y-m-d H:i:s");
                            $useridloaded = $_SESSION['id'];
                            $usernameloaded = $_SESSION['lastname'] . " " . $_SESSION['firstname'];
                            $file_originalname = $values;
                            $file_truelocation = $_PATHSRV . $file_name;

                            $path_parts = pathinfo($file_truelocation);
                            $file_extension = $path_parts['extension'];

                            $file_size = filesize($file_truelocation);
                            $file_size = $file_size ? number_format($file_size / 1024, 2) : 0;
                            $file_symname = md5(uniqid()) . "." . $file_extension;
                            symlink($file_truelocation, __DIR_ROOT . $_PATH . $file_symname);

                            $file_syspath = __DIR_ROOT . $_PATH . $file_symname;
                            $file_webpath = $_PATH .  $file_symname;
                            $file_url = __ROOT . $file_webpath;

                            $_QRY_CRRECORD = mysqlQuery("INSERT INTO portal_cloud_files (kodcloudfile, dateloaded, useridloaded, usernameloaded, file_originalname, file_name, file_symname, file_extension, file_size, file_truelocation, file_syspath, file_webpath, file_url) VALUES ('{$kodcloudfile}', NOW(), '{$useridloaded}', '{$usernameloaded}', '{$file_originalname}', '{$file_name}', '{$file_symname}', '{$file_extension}', '{$file_size}', '{$file_truelocation}', '{$file_syspath}', '{$file_webpath}', '{$file_url}')");
                            $res = $res && $_QRY_CRRECORD;
                        } else {
                            // $output .= ' error ';
                        }
                    }
                    $output = $res ? "ok" : "error";
                }
            }
        } else {
            $output .= '<div class="mx-2">Что-то пошло не так</div>';
        }
    }
}

echo $output;
