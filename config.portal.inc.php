<?php
# Включаем режим сессии
session_start();
#
$mysqli = new mysqli(__DEFAULT_DBCONN_HOST, __DEFAULT_DBCONN_USER, __DEFAULT_DBCONN_PASS, __DEFAULT_DBCONN_NAME, __DEFAULT_DBCONN_PORT);
$result = $mysqli->query("SELECT portalnew_testmode FROM users_restrictions WHERE id='{$_SESSION['id']}'");
$row = $result->fetch_array(MYSQLI_ASSOC);
$_SESSION['userInPortalTestmode'] = $row['portalnew_testmode'];
/* Close the connection as soon as it becomes unnecessary */
$mysqli->close();
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### 
##### ОСНОВНАЯ КОНФИГУРАЦИЯ СЕРВИСА ПОЧТА (MAIL)
##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
#
# РЕЖИМ РАБОТЫ СЕРВИСА : __PORTAL_TESTMODE_ON
# >>> TRUE или 1	- отладочный/тестовый режим
# >>> FALSE	или 0	- рабочий режим

define('__PORTAL_TESTMODE_ON', TRUE);

# ТИП ОТЛАДОЧНОГО РЕЖИМА : __PORTAL_TESTMODE_TYPE
# >>> 0	- рабочая БД, рабочее хранилище, отладочные папки restr_N.test для users.testmode = 1
# >>> 1	- рабочая БД (дублирующие тестовые таблицы), тестовое хранилище, отладочные папки restr_N.test для users.testmode = 1
# >>> 2	- тестовая БД, тестовое хранилище, отладочные папки restr_N.test для users.testmode = 1
# >>> 3	- тестовая БД, тестовое хранилище, рабочие папки restr_N для users.testmode = 1

define('__PORTAL_TESTMODE_TYPE', 0);

##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
if (!isset($_IS_CRONTAB)) {

    if ((__PORTAL_TESTMODE_ON == TRUE || __PORTAL_TESTMODE_ON == 1) && $_SESSION['userInPortalTestmode'] >= 1) {
        #
        if (__PORTAL_TESTMODE_TYPE == 0) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        } elseif (__PORTAL_TESTMODE_TYPE == 1) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        } elseif (__PORTAL_TESTMODE_TYPE == 2) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db_test');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        }
        #
        define('__PORTAL_HEADER', '/___header.test.php');
        define('__PORTAL_FOOTER', '/___footer.test.php');
        #
    } else {
        #
        define('__PORTAL_DBCONN_HOST', '192.168.1.89');
        define('__PORTAL_DBCONN_USER', 'root');
        define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
        define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
        define('__PORTAL_DBCONN_PORT', '3306');
        #
        define('__PORTAL_HEADER', '/___header.php');
        define('__PORTAL_FOOTER', '/___footer.php');
        #
    }
} else {
    if ((__PORTAL_TESTMODE_ON == TRUE || __PORTAL_TESTMODE_ON == 1)) {
        #
        if (__PORTAL_TESTMODE_TYPE == 0) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        } elseif (__PORTAL_TESTMODE_TYPE == 1) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        } elseif (__PORTAL_TESTMODE_TYPE == 2) {
            #
            define('__PORTAL_DBCONN_HOST', '192.168.1.89');
            define('__PORTAL_DBCONN_USER', 'root');
            define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
            define('__PORTAL_DBCONN_NAME', 'yachu_devs_db_test');
            define('__PORTAL_DBCONN_PORT', '3306');
            #
        }
    } else {
        define('__PORTAL_DBCONN_HOST', '192.168.1.89');
        define('__PORTAL_DBCONN_USER', 'root');
        define('__PORTAL_DBCONN_PASS', 'Atgs_1992');
        define('__PORTAL_DBCONN_NAME', 'yachu_devs_db');
        define('__PORTAL_DBCONN_PORT', '3306');
        #
    }
}
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
# Абсолютный путь к сервису
define('__PORTAL_ABSPATH', '/var/www/html/atgs-portal.local/www/portalnew');
#
# Главная страница
define('__PORTAL_WORKPATH', '/php/examples/simple');
define('__PORTAL_MAIN_WORKPATH', '/php/examples/simple/main');
define('__PORTAL_MAIN_MAIN_WORKPATH', '/php/examples/simple/main/main');
#
# 
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
# ##### КЛАССЫ
# ##### ##### ##### ##### ##### ##### ##### ##### ##### #####


#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 