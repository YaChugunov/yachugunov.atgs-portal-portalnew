<?php
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки доступности сайта/хоста
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# See code status - http://curl.haxx.se/libcurl/c/libcurl-errors.html
# Пример использования:
/*
$url = array( 'http://site1.com/', 'http://site2.com/', 'http://site3.com/', 'http://site4.com/' );
foreach ($url as $val)
{
	$answer = check_http_status($val);
	$answer = check_http_status($val);
	if ($answer == 200)
		echo 'Site '.$val.' is avaliable.', PHP_EOL;
	else {
		if ($answer == 28) {
			echo 'Resource '.$val.' is not responding. Time out operation (more than 10 sec)'. PHP_EOL;
		}
		else {
			echo 'Resource '.$val.' is not avaliable. Reason: '.$answer.'. ', PHP_EOL;
		}
	}
}
*/
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция для генерации случайной строки (hash)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function getServiceVersion($service) {
	$result = array();
	$QRY = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM aconf_versions WHERE service='$service'"));
	if ($QRY['beta'] !== 1) {
		$result = !empty($QRY) ? '<span class="badge badge-primary px-2 py-1"><span class="">' . $QRY['name'] . '</span><i class="fa-solid fa-ellipsis-vertical mx-1"></i><span class="mr-1">версия</span>' . $QRY['version_s'] . '</span>' : "";
	} else {
		$result = !empty($QRY) ? '<span class="badge badge-danger px-2 py-1"><span class="">' . $QRY['name'] . '</span><i class="fa-solid fa-ellipsis-vertical mx-1"></i><span class="mr-1">версия</span>' . $QRY['version_s'] . ' beta</span>' : "";
	}
	return $result;
}



# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function check_http_status($url) {
	$user_agent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	$page = curl_exec($ch);

	$error = curl_errno($ch);
	if (!empty($error)) {
		return "err:" . $error;
	} else {
		return ($httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
	}
	curl_close($ch);
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function mysqlQuery($sql) {
	$result = mysqli_query(db::$linkDB, $sql) or die(mysqli_error(db::$linkDB));
	return $result;
}
function mysqlQuery_Remote($sql) {
	$result = mysqli_query(db::$linkDB_Remote, $sql) or die(mysqli_error(db::$linkDB_Remote));
	return $result;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция для генерации случайной строки (hash)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function generateCode($length) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;
	while (strlen($code) < $length) {
		$code .= $chars[mt_rand(0, $clen)];
	}
	return $code;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки авторизации
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkUserAuthorization($_funcLogin, $_funcPass) {
	// «AND activation='1'» - пользователь будет искаться только среди активированных
	mysqlQuery(
		"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'"
	);
	$query_checkUser = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM users WHERE login='$_funcLogin' AND password='$_funcPass' AND activation='1'"));
	// 	echo mysqli_error();
	if (!empty($query_checkUser['id'])) {
		$_funcStatus = $query_checkUser['id'];
	} else {
		$_funcStatus = -1;
	}
	return $_funcStatus;
}
function checkUserAuthorization_defaultDB($_funcLogin, $_funcPass) {
	// «AND activation='1'» - пользователь будет искаться только среди активированных
	mysqlQuery_defaultDB(
		"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'"
	);
	$query_checkUser = mysqli_fetch_assoc(mysqlQuery_defaultDB("SELECT * FROM users WHERE login='$_funcLogin' AND password='$_funcPass' AND activation='1'"));
	// 	echo mysqli_error();
	if (!empty($query_checkUser['id'])) {
		$_funcStatus = $query_checkUser['id'];
	} else {
		$_funcStatus = -1;
	}
	return $_funcStatus;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция сохранения номера сессии
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function _saveSessionID($_funcLogin, $_funcPass, $_funcCookiesSave, $_funcAutoLogin) {
	$checkUserStatus = checkUserAuthorization($_funcLogin, $_funcPass);
	if ($checkUserStatus != -1) {
		// «AND activation='1'» - пользователь будет искаться только среди активированных
		mysqlQuery("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		$query_checkUser = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM users WHERE login='$_funcLogin' AND password='$_funcPass' AND activation='1'"));
		$_id = $query_checkUser['id'];
		// Если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
		$_SESSION['password'] = $query_checkUser['password'];
		$_SESSION['login'] = $query_checkUser['login'];
		// Эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
		$_SESSION['id'] = $_id;
		$_SESSION['permissions'] = $query_checkUser['permissions'];
		$_SESSION['firstname'] = $query_checkUser['firstname'];
		$_SESSION['middlename'] = $query_checkUser['middlename'];
		$_SESSION['lastname'] = $query_checkUser['lastname'];
		// -----
		$query_checkRestrictions = mysqli_fetch_assoc(mysqlQuery(" SELECT * FROM users_restrictions WHERE id='$_id' AND status='1' "));
		if ($query_checkRestrictions) {
			$_SESSION['restrictions']['portal'] 	= $query_checkRestrictions['portal'];
			$_SESSION['restrictions']['mail'] 		= $query_checkRestrictions['mail'];
			$_SESSION['restrictions']['dognet']	= $query_checkRestrictions['dognet'];
			$_SESSION['restrictions']['ism']	 	= $query_checkRestrictions['ism'];
			$_SESSION['restrictions']['hr'] 		= $query_checkRestrictions['hr'];
			$_SESSION['restrictions']['piter'] 	= $query_checkRestrictions['piter'];
			$_SESSION['restrictions']['auto'] 		= $query_checkRestrictions['auto'];
		}
		/*
		Далее мы запоминаем данные в куки, для последующего входа.
		ВНИМАНИЕ!!! ДЕЛАЙТЕ ЭТО НА ВАШЕ УСМОТРЕНИЕ, ТАК КАК ДАННЫЕ ХРАНЯТСЯ В КУКАХ БЕЗ ШИФРОВКИ
		*/
		if ($_funcCookiesSave == 'saveCookies') {
			// Если пользователь хочет, чтобы его данные сохранились для последующего входа, то сохраняем в куках его браузера
			setcookie("login", $_POST["login"], time() + 9999999);
			setcookie("password", $_POST["password"], time() + 9999999);
			setcookie("id", $query_checkUser['id'], time() + 9999999);
		}
		if ($_funcAutoLogin == 'autoLogin') {
			// Если пользователь хочет входить на сайт автоматически
			setcookie("auto", "yes", time() + 9999999);
			setcookie("login", $_POST["login"], time() + 9999999);
			setcookie("password", $_POST["password"], time() + 9999999);
			setcookie("id", $query_checkUser['id'], time() + 9999999);
		}
	}
	return;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция вывода русского названия месяца в стандартном формате
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function rdate($param, $time = 0) {
	if (intval($time) == 0) $time = time();
	$MonthNames = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	if (strpos($param, 'M') === false) return date($param, $time);
	else return date(str_replace('M', $MonthNames[date('n', $time) - 1], $param), $time);
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки прав администратора
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkIsItSuperadmin($id) {
	$query = mysqlQuery(" SELECT superadmin FROM users WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if ($row['superadmin'] == '1') {
		return 1;
	} else {
		return 0;
	}
}
function checkIsItSuperadmin_defaultDB($id) {
	$query = mysqlQuery_defaultDB(" SELECT superadmin FROM users WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if ($row['superadmin'] == '1') {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки прав администратора
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function getUserDeptNum($id) {
	$query = mysqlQuery(" SELECT dept_num FROM users WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if ($row['dept_num'] != 0) {
		return $row['dept_num'];
	} else {
		return 0;
	}
}
function getUserDeptNum_defaultDB($id) {
	$query = mysqlQuery_defaultDB(" SELECT dept_num FROM users WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if ($row['dept_num'] != 0) {
		return $row['dept_num'];
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки является ли пользователь договорной программы ГИПом
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkKoduseGIP($id) {
	$query1 = mysqlQuery(" SELECT kodusegip, kodispol FROM dognet_users_kods WHERE id='$id' ");
	$row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC);
	if ($row1['kodispol'] != 0) {
		if ($row1['kodusegip'] == 1) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
function checkKoduseGIP_defaultDB($id) {
	$query1 = mysqlQuery_defaultDB(" SELECT kodusegip, kodispol FROM dognet_users_kods WHERE id='$id' ");
	$row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC);
	if ($row1['kodispol'] != 0) {
		if ($row1['kodusegip'] == 1) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки является ли пользователь договорной программы ГИПом
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkIsItGIP($id) {
	$query = mysqlQuery(" SELECT dognet, dognet_gip FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row['dognet'] >= 3) and ($row['dognet_gip'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
function checkIsItGIP_defaultDB($id) {
	$query = mysqlQuery_defaultDB(" SELECT dognet, dognet_gip FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row['dognet'] >= 3) and ($row['dognet_gip'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки является ли пользователь заявителем на закупку (ZAYVTEL)
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkIsItZAYV($id) {
	$query = mysqlQuery(" SELECT dognet, dognet_zayv FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row['dognet'] >= 1) and ($row['dognet_zayv'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
function checkIsItZAYV_defaultDB($id) {
	$query = mysqlQuery_defaultDB(" SELECT dognet, dognet_zayv FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row['dognet'] >= 1) and ($row['dognet_zayv'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkUserRestrictions2($id, $service_name, $min_restr, $max_restr) {
	$checkUserStatus = checkUserAuthorization($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_userRestrictions = mysqlQuery(" SELECT " . $service_name . " FROM users_restrictions WHERE id='$id' AND status='1' ");
		$row_userRestrictions = mysqli_fetch_array($query_userRestrictions, MYSQLI_ASSOC);
		if ($row_userRestrictions[$service_name] >= $min_restr && $row_userRestrictions[$service_name] <= $max_restr) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function getUserRestrictions($id, $service_name) {
	$checkUserStatus = checkUserAuthorization($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_userRestrictions = mysqlQuery(" SELECT " . $service_name . " FROM users_restrictions WHERE id='$id' AND status='1' ");
		$row_userRestrictions = mysqli_fetch_array($query_userRestrictions, MYSQLI_ASSOC);
		$USER_ACCESS_LVL = $row_userRestrictions[$service_name];
		return $USER_ACCESS_LVL;
	} else {
		return 0;
	}
}
function getUserRestrictions_defaultDB($id, $service_name) {
	$checkUserStatus = checkUserAuthorization_defaultDB($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_userRestrictions = mysqlQuery_defaultDB(" SELECT " . $service_name . " FROM users_restrictions WHERE id='$id' AND status='1' ");
		$row_userRestrictions = mysqli_fetch_array($query_userRestrictions, MYSQLI_ASSOC);
		$USER_ACCESS_LVL = $row_userRestrictions[$service_name];
		return $USER_ACCESS_LVL;
	} else {
		return 0;
	}
}
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### 
##### ПРОВЕРЯЕМ НАХОДИТСЯ ЛИ ПОЛЬЗОВАТЕЛЬ В РЕЖИМЕ ТЕСТИРОВАНИЯ СЕРВИСА
##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
#
function checkUserRestrictions_defaultDB($id, $service_name, $min_restr, $flag) {
	$checkUserStatus = checkUserAuthorization($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_userRestrictions = mysqlQuery_defaultDB(" SELECT " . $service_name . " FROM users_restrictions WHERE id='$id' AND status='1' ");
		$row_userRestrictions = mysqli_fetch_array($query_userRestrictions, MYSQLI_ASSOC);
		if ($flag == 1) {
			if ($row_userRestrictions[$service_name] == $min_restr) {
				return 1;
			} else {
				return 0;
			}
		} else {
			if ($row_userRestrictions[$service_name] >= $min_restr) {
				return 1;
			} else {
				return 0;
			}
		}
	} else {
		return 0;
	}
}
function checkUserRestrictions($id, $service_name, $min_restr, $flag) {
	$checkUserStatus = checkUserAuthorization($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_userRestrictions = mysqlQuery(" SELECT " . $service_name . " FROM users_restrictions WHERE id='$id' AND status='1' ");
		$row_userRestrictions = mysqli_fetch_array($query_userRestrictions, MYSQLI_ASSOC);
		if ($flag == 1) {
			if ($row_userRestrictions[$service_name] == $min_restr) {
				return 1;
			} else {
				return 0;
			}
		} else {
			if ($row_userRestrictions[$service_name] >= $min_restr) {
				return 1;
			} else {
				return 0;
			}
		}
	} else {
		return 0;
	}
}
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### 
##### Функция проверки открыт/закрыт ли сервис
##### (используется для закрытия сервиса на техническое обслуживание)
##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
#
function checkServiceAccess($service) {
	$query_allServicesAccess = mysqlQuery("SELECT access FROM service_access WHERE service_name='allservices'");
	$query_serviceAccess = mysqlQuery("SELECT access FROM service_access WHERE service_name='$service'");
	$row_allServicesAccess = mysqli_fetch_array($query_allServicesAccess, MYSQLI_ASSOC);
	$row_serviceAccess = mysqli_fetch_array($query_serviceAccess, MYSQLI_ASSOC);
	if ($row_allServicesAccess['access'] == '1') {
		if ($row_serviceAccess['access'] == '1') {
			return 1;
		} elseif ($row_serviceAccess['access'] == '0' && checkIsItSuperadmin($_SESSION['id']) == 1) {
			return 1;
		} else {
			return 0;
		}
	} elseif ($row_allServicesAccess['access'] == '0' && checkIsItSuperadmin($_SESSION['id']) == 1) {
		return 1;
	} else {
		return 0;
	}
}
#
function checkServiceAccess_defaultDB($service) {
	$query_allServicesAccess = mysqlQuery_defaultDB("SELECT access FROM service_access WHERE service_name='allservices'");
	$query_serviceAccess = mysqlQuery_defaultDB("SELECT access FROM service_access WHERE service_name='$service'");
	$row_allServicesAccess = mysqli_fetch_array($query_allServicesAccess, MYSQLI_ASSOC);
	$row_serviceAccess = mysqli_fetch_array($query_serviceAccess, MYSQLI_ASSOC);
	if ($row_allServicesAccess['access'] == '1') {
		if ($row_serviceAccess['access'] == '1') {
			return 1;
		} elseif ($row_serviceAccess['access'] == '0' && checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1) {
			return 1;
		} else {
			return 0;
		}
	} elseif ($row_allServicesAccess['access'] == '0' && checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1) {
		return 1;
	} else {
		return 0;
	}
}
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки открыт/закрыт ли сервис
# +++++ (используется для закрытия сервиса на техническое обслуживание)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkDognetSectionAccess($section, $type) {
	$query_sectionAccess = mysqlQuery("SELECT * FROM dognet_section_access WHERE section_name='$section'");
	$row_sectionAccess = mysqli_fetch_array($query_sectionAccess, MYSQLI_ASSOC);
	if ($row_sectionAccess[$type] == '1') {
		return 1;
	} elseif ($row_sectionAccess[$type] == '0' && checkIsItSuperadmin($_SESSION['id']) == 1) {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки открыт/закрыт ли сервис
# +++++ (используется для закрытия сервиса на техническое обслуживание)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkUsergroupAccess($service) {
	$query_serviceAccess = mysqlQuery(" SELECT access FROM service_access WHERE service_name='$service' ");
	$row_serviceAccess = mysqli_fetch_array($query_serviceAccess, MYSQLI_ASSOC);
	if ($row_serviceAccess['access'] == '1') {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция проверки открыта/закрыта ли ВСЯ СИСТЕМА на техобслуживание
# +++++ (используется для закрытия ВСЕЙ СИСТЕМЫ на техническое обслуживание)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkUnderconstructionMode() {
	$query_sysAccess = mysqlQuery_defaultDB(" SELECT access FROM service_access WHERE service_name='underconstruction' ");
	$row_sysAccess = mysqli_fetch_array($query_sysAccess, MYSQLI_ASSOC);
	if ($row_sysAccess['access'] == '1') {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция фиксации времени активности и страницы пребывания пользователя
# +++++ (  )
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function logActivity() {

	// Обновляем метку последней активности в БД
	if (!empty($_SESSION['login']) && !empty($_SESSION['password'])) {
		$__id = $_SESSION['id'];
		$__login = $_SESSION['login'];
		$__lastname = $_SESSION['lastname'];
		$__password = $_SESSION['password'];
		$__script = $_SERVER['PHP_SELF'];
		$__SESSID = session_id();
		$__userAgent = $_SERVER['HTTP_USER_AGENT'];
		$__ip = $_SERVER['REMOTE_ADDR'];

		// Если пользователь авторизуется с новой сессией, то помечаем status = 2, что означает, что предыдущая сессия была удалена по времени или по закрытию браузера
		// status = 0 , если сесиия была закрыта пользователем по выходу из сервиса

		mysqlQuery("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

		// 	mysqlQuery( "UPDATE log_auth SET status = '2', comment='Сессия была закрыта' WHERE user_id = '$__id' AND NOT SESSID = '$__SESSID' AND NOT status = '0'" );

		// 	mysqlQuery( "INSERT INTO log_auth ( user_id , user_login , status , login_timestamp , ip , SESSID , user_agent , comment ) VALUES ( '$__id' , '$__login' , '1' , NOW() , '$__ip' , '$__SESSID' , '$__userAgent' , 'Новая сессия' )" );

		$result1 = mysqlQuery("SELECT * FROM log_auth WHERE user_login = '$__login' AND status = '1' AND SESSID = '$__SESSID'");
		$row1 = mysqli_fetch_assoc($result1);
		if ($row1) {
			// ----- ----- ----- ----- -----
			$__loginTimestamp = $row1['login_timestamp'];
			$__logoutTimestamp = $row1['logout_timestamp'];
			$__sessID0 = $row1['SESSID'];

			if ($row1['ip'] == $__ip && $row1['SESSID'] == $__SESSID) {
				mysqlQuery("UPDATE log_auth SET lastactivity_timestamp = NOW(), lastactivity_script = '$__script' WHERE user_login = '$__login' AND SESSID = '$__SESSID' AND status = '1'");
			} elseif ($row1['ip'] != $__ip && $row1['SESSID'] == $__SESSID) {
				mysqlQuery("UPDATE log_auth SET lastactivity_timestamp = NOW(), lastactivity_script = '$__script', status = '0', comment = 'Сессия устарела, более не активна' WHERE user_login = '$__login' AND SESSID = '$__SESSID' AND status = '1'");
				mysqlQuery("INSERT INTO log_auth (user_id, ip, user_login, user_lastname, comment, login_timestamp, logout_timestamp, lastactivity_timestamp, lastactivity_script, SESSID, user_agent, status) VALUES ('$__id', '$__ip', '$__login', '$__lastname', 'Смена IP в текущей сессии', '$__loginTimestamp', '$__logoutTimestamp', NOW(), '$__script', '$__SESSID', '$__userAgent', '2')");
			} elseif ($row1['ip'] == $__ip && $row1['SESSID'] != $__SESSID) {
				mysqlQuery("UPDATE log_auth SET lastactivity_timestamp = NOW(), lastactivity_script = '$__script', status = '0', comment = 'Сессия устарела, более не активна' WHERE user_login = '$__login' AND SESSID = '$__SESSID' AND status = '1'");
				mysqlQuery("INSERT INTO log_auth (user_id, ip, user_login, user_lastname, comment, login_timestamp, logout_timestamp, lastactivity_timestamp, lastactivity_script, SESSID, user_agent, status) VALUES ('$__id', '$__ip', '$__login', '$__lastname', 'Новая сессия на активном IP', '$__loginTimestamp', '$__logoutTimestamp', NOW(), '$__script', '$__SESSID', '$__userAgent', '1')");
			}
			// ----- ----- ----- ----- -----
		}
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция загрузки баннер с объявлением
# +++++ (используется для закрытия сервиса на техническое обслуживание)
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function loadBanner($service) {
	$query_serviceAccess = mysqlQuery(" SELECT access FROM service_access WHERE service_name='$service' ");
	$row_serviceAccess = mysqli_fetch_array($query_serviceAccess, MYSQLI_ASSOC);
	if ($row_serviceAccess['access'] == '1') {
		return 1;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function checkDognetMainpageViewBlock($lvlaccess, $blockname) {
	$checkUserStatus = checkUserAuthorization($_SESSION['login'], $_SESSION['password']);
	if ($checkUserStatus != -1) {
		$query_viewblock = mysqlQuery(" SELECT " . $blockname . " FROM dognet_view_mainpage WHERE lvl_access='$lvlaccess'");
		$row_viewblock = mysqli_fetch_array($query_viewblock, MYSQLI_ASSOC);
		$VIEWBLOCK = $row_viewblock[$blockname];
		return $VIEWBLOCK;
	} else {
		return 0;
	}
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function is_session_exists() {
	$sessionName = session_name();
	if (isset($_COOKIE[$sessionName]) || isset($_REQUEST[$sessionName])) {
		if (!isset($_SESSION)) {
			session_start();
		}
		return !empty($_SESSION);
	}
	return false;
}
#
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
# +++++ Функция сохранения состояния панели фильтров для списков (договора, заявки прочее)
# Created: 21.01.2021
# Comment: Пока только для списка текущих договоров
# ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
function _saveFiltersPanelState($_idFiltersPanel, $_nameElement, $_val) {
	$_SESSION[$_idFiltersPanel][$_nameElement] = $_val;
	return;
}
#
#
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### 
##### ПРОВЕРЯЕМ НАХОДИТСЯ ЛИ ПОЛЬЗОВАТЕЛЬ В РЕЖИМЕ ТЕСТИРОВАНИЯ СЕРВИСА
##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### ##### 
#
#
function checkUserInTestMode($id, $servicename) {
	$query = mysqlQuery(" SELECT {$servicename}, {$servicename}_testmode FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row[$servicename] >= 3) && ($row[$servicename . '_testmode'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
#
#
function checkUserInTestMode_defaultDB($id, $servicename) {
	$query = mysqlQuery_defaultDB(" SELECT {$servicename}, {$servicename}_testmode FROM users_restrictions WHERE id='$id' ");
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if (($row[$servicename] >= 3) && ($row[$servicename . '_testmode'] > 0)) {
		return 1;
	} else {
		return 0;
	}
}
