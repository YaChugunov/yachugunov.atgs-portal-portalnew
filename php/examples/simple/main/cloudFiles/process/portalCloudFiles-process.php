<?php
date_default_timezone_set('Europe/Moscow');
# Подключаем конфигурационный файл
require($_SERVER['DOCUMENT_ROOT'] . "/config.inc.php");
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
// session_start();# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
#
function removeFiles($db, $id) {
	$__file = $db->sql("SELECT file_truelocation, file_syspath FROM portal_cloud_files WHERE id=" . $id)->fetchAll();
	$__tmp1 = unlink($__file[0]['file_syspath']);
	$__tmp2 = unlink($__file[0]['file_truelocation']);
}
#
#
/*
Example PHP implementation used for the index.html example
*/
// DataTables PHP library
require(__DIR_ROOT . __SERVICENAME_PORTALNEW . '/_assets/libs/Datatables/Editor-PHP-1.9.7/lib/DataTables.php');

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst($db, 'portal_cloud_files')
	->fields(
		Field::inst('portal_cloud_files.id'),
		Field::inst('portal_cloud_files.koddel'),
		Field::inst('portal_cloud_files.kodcloudfile'),
		Field::inst('portal_cloud_files.useridloaded'),
		Field::inst('portal_cloud_files.usernameloaded'),
		Field::inst('portal_cloud_files.dateloaded')
			->getFormatter(Format::datetime(
				'Y-m-d H:i:s',
				'd.m.Y H:i:s'
			)),
		Field::inst('portal_cloud_files.file_originalname'),
		Field::inst('portal_cloud_files.file_truelocation'),
		Field::inst('portal_cloud_files.file_extension'),
		Field::inst('portal_cloud_files.file_size'),
		Field::inst('portal_cloud_files.file_symname'),
		Field::inst('portal_cloud_files.file_webpath'),
		Field::inst('portal_cloud_files.file_url'),
		Field::inst('portal_cloud_files.comment')
	)
	->on('preRemove', function ($editor, $id, $values) {
		removeFiles($editor->db(), $id);
	})

	->where('portal_cloud_files.koddel', '99', '!=')


	->process($_POST)
	->json();
