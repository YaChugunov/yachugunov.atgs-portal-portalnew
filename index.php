<?php
# Подключаем конфигурационный файл
require($_SERVER['DOCUMENT_ROOT'] . '/config.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/config.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаемся к базе
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/dbconn/db_connection.php');
// require('../_assets/drivers/db_controller.php');
// $db_handle = new DBController();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем общие функции безопасности
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.secure.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Подключаем собственные функции сервиса Почта
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/functions/func.portal.inc.php');
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# Включаем режим сессии
// session_start();
# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
	if (checkUserAuthorization_defaultDB($_SESSION['login'], $_SESSION['password']) == -1) {
		// Редирект на главную страницу
?>
<meta http-equiv="refresh" content="0; url=<?php echo __ROOT; ?>">
<?php
	} else {
		// При удачном входе пользователю выдается все, что расположено ниже между звездочками.
		// ************************************************************************************
		if (checkServiceAccess_defaultDB('allservices') == 1) {
			if (checkServiceAccess_defaultDB('portalnew') == 1 or (checkServiceAccess_defaultDB('portalnew') == 0 && checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1)) {
				require(__DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_HEADER);
				include(__DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_WORKPATH  . '/portalnew.php');
				require(__DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_FOOTER);
			} else {
				include(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/msg.inc/message_service-nopermission185.php');
			}
		} else {
			include(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/msg.inc/message_service-noaccess185.php');
		}
		// ************************************************************************************
		// При удачном входе пользователю выдается все, что расположено ВЫШЕ между звездочками.
	}
} else {
	if (isset($_GET['mode']) && $_GET['mode'] == "checkup" && isset($_GET['type']) && $_GET['type'] == "user" && !empty($_GET['secretkey'])) {
		include($_SERVER['DOCUMENT_ROOT'] . "/ism/php/examples/simple/checkup/checkup-main-usercheck.php");
	} else {
		// Редирект на главную страницу
		echo '<meta http-equiv="refresh" content="0; url=' . __ROOT . '">';
	}
}
?>