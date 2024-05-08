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

// Код документа в таблице mailbox_incoming
$searchstring = isset($_POST['searchstring']) ? $_POST['searchstring'] : "";
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : "";

# ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

$data = array();
$error = $search_output = "";

if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization($_SESSION['login'], $_SESSION['password']) == -1) {
        return 0;
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $searchstring != "") {
            // $_QRY = mysqlQuery("SELECT inbox_docID, inbox_docDate, inbox_docAbout, inbox_docSender, (IF (`inbox_docAbout` LIKE '%{$_POST['searchstring']}%', 40, 0) + IF (`inbox_docSender` LIKE '%{$_POST['searchstring']}%', 40, 0) + IF (`inbox_docID` LIKE '%{$_POST['searchstring']}%', 20, 0)) AS `relevant` FROM mailbox_incoming WHERE inbox_docAbout LIKE '%{$_POST['searchstring']}%' AND YEAR(inbox_docDate) = YEAR(CURDATE()) HAVING relevant > 0 ORDER BY inbox_docDate DESC, relevant DESC");


            /*
MySQL PHP Search Exercises by Adam Khoury @ developphp.com
MySQL Database Version Used In the Lessons: 5.1.58
PHP Version Used in the Lessons: 5.2.17
For Code Logic and Code Explanations Watch the Videos
*/
            // Using MATCH AGAINST over multiple tables to perform search
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $search_output = "";
            if (isset($_POST['searchstring']) && $_POST['searchstring'] != "") {
                // $searchstring = preg_replace('#[^a-z 0-9?!]#i', '', $_POST['searchstring']);

                /*

SELECT SQL_CALC_FOUND_ROWS *,
MATCH (text,text_index) AGAINST (‘>»отпуск за работу» <(+отпуск +работа)’ IN BOOLEAN MODE) AS rel
FROM table
WHERE MATCH (text,text_index) AGAINST (‘>»отпуск за работу» <(+отпуск +работа)’ IN BOOLEAN MODE)
ORDER BY rel DESC LIMIT 0, 10

*/

                if ($_POST['filter1'] == "Whole Site") {
                    $sqlCommand = "
                    SELECT * FROM (
                    (
                        SELECT
                            'mail_inbox' AS type,
                            ID AS id,
                            koddocmail AS kod,
                            inbox_docID AS par1,
                            inbox_docDate AS par2,
                            inbox_docSender AS par3,
                            inbox_docSender_kodzakaz AS par4,
                            inbox_docAbout AS par5,
                            inbox_docRecipientSTR AS par6,
                            inbox_docContractorSTR AS par7,
                            inbox_tags AS par8,
                            inbox_defaultTags AS par9,
                            inbox_UID AS par10, 
                            inbox_docFileID AS mainfile, 
                            inbox_docComment AS comment, 
                            'empty' AS par11, 
                            'empty' AS par12, 
                            MATCH (inbox_defaultTags, inbox_UID, inbox_docAbout, inbox_docSender, inbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) AS rel
                        FROM
                            mailbox_incoming
                        WHERE
                            MATCH (inbox_defaultTags, inbox_UID, inbox_docAbout, inbox_docSender, inbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                    )
                    ";
                    $sqlCommand .= "
                    UNION
                    (
                        SELECT
                            'mail_outbox' AS type,
                            ID AS id,
                            koddocmail AS kod,
                            outbox_docID AS par1,
                            outbox_docDate AS par2,
                            outbox_docRecipient AS par3,
                            outbox_docRecipient_kodzakaz AS par4,
                            outbox_docAbout AS par5,
                            outbox_docSenderSTR AS par6,
                            outbox_docContractorSTR AS par7,
                            outbox_tags AS par8,
                            outbox_defaultTags AS par9,
                            outbox_UID AS par10,
                            outbox_docFileID AS mainfile, 
                            outbox_docComment AS comment, 
                            'empty' AS par11, 
                            'empty' AS par12, 
                            MATCH (outbox_defaultTags, outbox_UID, outbox_docAbout, outbox_docRecipient, outbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                        FROM
                            mailbox_outgoing
                        WHERE
                            MATCH (outbox_defaultTags, outbox_UID, outbox_docAbout, outbox_docRecipient, outbox_tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                    )
                    ";
                    $sqlCommand .= "
                    UNION
                    (
                        SELECT
                            'dog_base' AS type,
                            ID AS id,
                            koddoc AS kod,
                            docnumberSTR AS par1,
                            yearnachdoc AS par2,
                            docpartnernumberSTR AS par3,
                            nameobjectfull AS par4,
                            docnameshot AS par5,
                            docnamefullm AS par6,
                            namezakfull AS par7,
                            tags AS par8,
                            defaultTags AS par9,
                            'empty' AS par10,
                            'empty' AS mainfile, 
                            'empty' AS comment, 
                            kodispolruk AS par11, 
                            kodispol AS par12, 
                            MATCH (defaultTags, docnumberSTR, docnamefullm, docpartnernumberSTR, namezakfull, nameobjectfull, docnameshot, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                        FROM
                            dognet_docbase
                        WHERE
                            MATCH (defaultTags, docnumberSTR, docnamefullm, docpartnernumberSTR, namezakfull, nameobjectfull, docnameshot, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                    )
                    ";
                    $sqlCommand .= "
                    UNION
                    (
                        SELECT
                            'dog_stage' AS type,
                            ID AS id,
                            kodkalplan AS kod,
                            koddoc AS par1,
                            namefullstage AS par2,
                            numberstage AS par3,
                            'empty' AS par4,
                            nameobjectshort AS par5,
                            nameobjectfull AS par6,
                            nameshotstage AS par7,
                            tags AS par8,
                            defaultTags AS par9,
                            'empty' AS par10,
                            'empty' AS mainfile, 
                            'empty' AS comment, 
                            'empty' AS par11, 
                            'empty' AS par12, 
                            MATCH (defaultTags, namefullstage, nameobjectfull, nameshotstage, nameobjectshort, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                        FROM
                            dognet_dockalplan
                        WHERE
                            MATCH (defaultTags, namefullstage, nameobjectfull, nameshotstage, nameobjectshort, tags) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                    )
                    ";
                    // $sqlCommand .= "
                    // UNION
                    // (
                    //     SELECT
                    //         'dog_sub' AS type,
                    //         id AS id,
                    //         kodcontragent AS kod,
                    //         'empty' AS par1,
                    //         'empty' AS par2,
                    //         'empty' AS par3,
                    //         'empty' AS par4,
                    //         nameshort AS par5,
                    //         namefull AS par6,
                    //         'empty' AS par7,
                    //         MATCH (nameshort, namefull) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
                    //     FROM
                    //         dognet_docsubpodr
                    //     WHERE
                    //         MATCH (nameshort, namefull) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
                    // )
                    // ";
                    $sqlCommand .= "
                    ) AS sitewide WHERE rel > 0
                    ORDER BY
                        rel DESC
                    LIMIT
                        250
                    ";
                } else if ($_POST['filter1'] == "Inbox") {
                    $sqlCommand = "SELECT koddocmail AS kod, inbox_docID AS par1, inbox_docDate AS par2, inbox_docSender AS par3, inbox_docAbout AS par5 FROM mailbox_incoming WHERE MATCH (inbox_docAbout, inbox_docSender) AGAINST ('{$searchstring}' IN BOOLEAN MODE)";
                } else if ($_POST['filter1'] == "Outbox") {
                    $sqlCommand = "SELECT koddocmail AS kod, outbox_docID AS par1, outbox_docDate AS par2, outbox_docRecipient AS par3, outbox_docAbout AS par5 FROM mailbox_outgoing WHERE MATCH (outbox_docAbout, outbox_docRecipient) AGAINST ('{$searchstring}' IN BOOLEAN MODE)";
                }
                $query = mysqlQuery($sqlCommand) or die(mysqli_error($sqlCommand));
                $count = mysqli_num_rows($query);
                if ($count > 1) {
                    $search_output .= '<div class="resultCount">' . $count . ' совпадений по запросу <span class="text-warning"><b>' . $searchstring . '</b></span></div>';
                    $i = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        // 
                        $_resItem = '';
                        switch ($row["type"]) {
                            case 'mail_inbox':
                                $serviceTitle = "Входящая почта";
                                $docID = $row["kod"];
                                $tagsDef = $row["par9"];
                                $tags = $row["par8"];
                                $comment = $row["comment"];

                                $fileID = $row["mainfile"];
                                $filelink = "";
                                if (!empty($fileID)) {
                                    $_filesQuery = mysqli_fetch_assoc(mysqlQuery("SELECT file_originalname, file_url, file_extension FROM mailbox_incoming_files WHERE id = '{$fileID}'"));
                                    $filename = $_filesQuery['file_originalname'];
                                    $fileurl = $_filesQuery['file_url'];
                                    if ($_filesQuery['file_extension'] == 'pdf') {
                                        $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                    } elseif ($_filesQuery['file_extension'] == 'doc') {
                                        $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                    } elseif ($_filesQuery['file_extension'] == 'xls' || $_filesQuery['file_extension'] == 'xlsx') {
                                        $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                    } else {
                                        $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                    }
                                    $filelink = !empty($filename && $fileurl) ? '<span class="mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="" target="_blank">' . $filename . '</a>' : "";
                                }

                                $mainlink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=in&mode=profile&uid=' . $row["kod"] . '" title="" target="_blank">' . $row["par5"] . '</a>';
                                $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=in&mode=thisyear" class="badge badge-light mr-3">' . $serviceTitle . '</a>';
                                $serviceinfo = '<span class="caption mr-2">№</span><span style="" class="mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="mr-2">' . date('d . m . Y', strtotime($row["par2"])) . '</span>';
                                $text_str1 = '<span class="caption mr-2">Контрагент</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=' . $row["par4"] . '" class="text-white mr-2" title="" target="_blank">' . $row["par3"] . '</a>';
                                $text_str2 = '<span class="caption mr-2">Получатель</span><span class="text-white mr-2">' . $row["par6"] . '</span><span class="caption mr-2">Ответственный(ые)</span><span class="text-white mr-2">' . $row["par7"] . '</span>';
                                $text_str3 = '<span class="text-secondary small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                $text_comment = '<span class="small">' . $comment . '</span>';
                                $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                //
                                $_resItem .= '<div class="resultItem d-flex flex-row shadow">';
                                $_resItem .= '<div class="d-flex flex-column w-75">';
                                $_resItem .= '<div class="resultItem-link mb-2"><span class="text-warning mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . $text_str2 . '</div>';
                                // $_resItem .= '<div class="resultItem-text mb-1">' . $text_str2 . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_comment . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<div class="d-flex flex-column w-25">';
                                $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<|>';
                                break;
                            case 'mail_outbox':
                                $serviceTitle = "Исходящая почта";
                                $docID = $row["kod"];
                                $tagsDef = $row["par9"];
                                $tags = $row["par8"];
                                $comment = $row["comment"];

                                $fileID = $row["mainfile"];
                                $filelink = "";
                                if (!empty($fileID)) {
                                    $_filesQuery = mysqli_fetch_assoc(mysqlQuery("SELECT file_originalname, file_url, file_extension FROM mailbox_outgoing_files WHERE id = '{$fileID}'"));
                                    $filename = $_filesQuery['file_originalname'];
                                    $fileurl = $_filesQuery['file_url'];
                                    if ($_filesQuery['file_extension'] == 'pdf') {
                                        $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                    } elseif ($_filesQuery['file_extension'] == 'doc') {
                                        $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                    } elseif ($_filesQuery['file_extension'] == 'xls' || $_filesQuery['file_extension'] == 'xlsx') {
                                        $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                    } else {
                                        $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                    }
                                    $filelink = !empty($filename && $fileurl) ? '<span class="mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="" target="_blank">' . $filename . '</a>' : "";
                                }

                                $mainlink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=out&mode=profile&uid=' . $row["kod"] . '" title="" target="_blank">' . $row["par5"] . '</a>';
                                $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/mailnew/index.php?type=out&mode=thisyear" class="badge badge-light mr-3">' . $serviceTitle . '</a>';
                                $serviceinfo = '<span class="caption mr-2">№</span><span style="" class="mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="mr-2">' . date('d . m . Y', strtotime($row["par2"])) . '</span>';
                                $text_str1 = '<span class="caption mr-2">Контрагент</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=' . $row["par4"] . '" class="text-white mr-2" title="" target="_blank">' . $row["par3"] . '</a>';
                                $text_str2 = '<span class="caption mr-2">Отправитель</span><span class="text-white mr-2">' . $row["par6"] . '</span><span class="caption mr-2">Исполнитель</span><span class="text-white mr-2">' . $row["par7"] . '</span>';
                                $text_str3 = '<span class="text-secondary small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                $text_comment = '<span class="small">' . $comment . '</span>';
                                $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                //
                                $_resItem .= '<div class="resultItem d-flex flex-row shadow">';
                                $_resItem .= '<div class="d-flex flex-column w-75">';
                                $_resItem .= '<div class="resultItem-link mb-2"><span class="text-warning mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . $text_str2 . '</div>';
                                // $_resItem .= '<div class="resultItem-text mb-1">' . $text_str2 . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_comment . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<div class="d-flex flex-column w-25">';
                                $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<|>';
                                break;
                            case 'dog_base':
                                $serviceTitle = "Договор";
                                $docID = $row["kod"];
                                $tagsDef = $row["par9"];
                                $tags = $row["par8"];
                                $comment = $row["comment"];
                                $kodispolruk = $row["par11"];
                                $kodispol = $row["par12"];
                                $docPartnerNumnber = !empty($row["par3"]) ? $row["par3"] : '<span class="text-secondary">не указан</span>';
                                $_ispolRukQuery = mysqli_fetch_assoc(mysqlQuery("SELECT ispolrukname, ispolruknamefull FROM dognet_spispolruk WHERE kodispolruk = '{$kodispolruk}'"));
                                $_ispolQuery = mysqli_fetch_assoc(mysqlQuery("SELECT ispolnameshot, ispolnamefull FROM dognet_spispol WHERE kodispol = '{$kodispol}'"));

                                $filelink = "";
                                if (!empty($docID)) {
                                    $_filesQuery = mysqlQuery("SELECT file_originalname, file_url, file_extension FROM dognet_docpaper_files WHERE koddoc = '{$docID}'");
                                    while ($_filesRow = mysqli_fetch_array($_filesQuery)) {
                                        $filename = $_filesRow['file_originalname'];
                                        $fileurl = $_filesRow['file_url'];
                                        if ($_filesRow['file_extension'] == 'pdf') {
                                            $filext = '<i class="fa-regular fa-file-pdf fa-lg"></i>';
                                        } elseif ($_filesRow['file_extension'] == 'doc') {
                                            $filext = '<i class="fa-regular fa-file-word fa-lg"></i>';
                                        } elseif ($_filesRow['file_extension'] == 'xls' || $_filesRow['file_extension'] == 'xlsx') {
                                            $filext = '<i class="fa-regular fa-file-excel fa-lg"></i>';
                                        } else {
                                            $filext = '<i class="fa-regular fa-file fa-lg"></i>';
                                        }
                                        $filelink .= !empty($filename && $fileurl) ? '<span class="mr-1">' . $filext . '</span><a href="' . $fileurl . '" class="" target="_blank">' . $filename . '</a>' : "";
                                        $filelink .= ' , ';
                                    }
                                    $filelink = rtrim($filelink, ' , ');
                                }

                                $mainlink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet/dognet-docview.php?docview_type=details&uniqueID=' . $row["kod"] . '" title="" target="_blank">' . $row["par6"] . '</a>';
                                $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet" class="badge badge-info mr-3">' . $serviceTitle . '</a>';
                                $serviceinfo = '<span class="caption mr-2">Номер АТГС</span><span style="" class="mr-2">' . $row["par1"] . '</span><span class="caption mr-2">от</span><span style="" class="mr-2">' . date("Y", strtotime($row["par2"])) . 'г.</span><span class="caption mr-2">Номер партнера</span><span style="" class="mr-2">' . $docPartnerNumnber  . '</span>';
                                $text_str1 = '<span class="caption mr-2">Заказчик</span><a href="http://' . $_SERVER["HTTP_HOST"] . '/sp/index.php?type=contragents&mode=profile&uid=" class="text-white mr-2" title="" target="_blank">' . $row["par7"] . '</a><span class="caption mr-2">Объект</span><span class="text-white mr-2">' . $row["par4"] . '</span>';
                                $text_str2 = '<span class="caption mr-2">Руководитель</span><span class="text-white mr-2">' . $_ispolRukQuery["ispolrukname"] . '</span><span class="caption mr-2">ГИП</span><span class="text-white mr-2">' . $_ispolQuery["ispolnameshot"] . '</span>';
                                $text_str3 = '<span class="text-secondary small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                $file_links = '<span class="file-link small">' . $filelink . '</span>';
                                $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';
                                // 
                                $_resItem .= '<div class="resultItem d-flex flex-row shadow">';
                                $_resItem .= '<div class="d-flex flex-column w-75">';
                                $_resItem .= '<div class="resultItem-link mb-2"><span class="text-warning mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                $_resItem .= '<div class="resultItem-text mb-2">' . $text_str1 . $text_str2 . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $file_links . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<div class="d-flex flex-column w-25">';
                                $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<|>';
                                break;
                            case 'dog_stage':
                                $serviceTitle = "Этап";
                                $docID = $row["kod"];
                                $tagsDef = $row["par9"];
                                $tags = $row["par8"];
                                $koddoc = $row["par1"];
                                $numberstage = $row["par3"];
                                $namestage = $row["par7"];
                                $nameobject = $row["par6"];
                                $_ispolDocnumber = mysqli_fetch_assoc(mysqlQuery("SELECT docnumber FROM dognet_docbase WHERE koddoc = '{$koddoc}'"));
                                $docnumber = !empty($_ispolDocnumber["docnumber"]) ? $_ispolDocnumber["docnumber"] : 'не найден';

                                $mainlink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet/dognet-docview.php?docview_type=details&uniqueID=' . $koddoc . '" title="" target="_blank">' . $namestage . '</a>';
                                $servicelink = '<a href="http://' . $_SERVER["HTTP_HOST"] . '/dognet" class="badge badge-info mr-3">' . $serviceTitle . '</a>';
                                $serviceinfo = '<span class="caption mr-2">Этап</span><span style="" class="mr-2">' . $numberstage . '</span><span class="caption mr-2">договора</span><span style="" class="mr-2">' . $docnumber . '</span><span class="caption mr-2">Объект</span><span class="text-white mr-2">' . $nameobject . '</span>';
                                $text_str3 = '<span class="text-secondary small">' . str_replace(',', ' , ', $tagsDef) . str_replace(',', ' , ', $tags) . '</span>';
                                $text_str4 = '<span class="small">ID<span class="ml-1">' . $docID . '</span></span>';
                                $relevance = '<span class="small">Релевантность запросу<span class="ml-2">' . round($row["rel"], 2, PHP_ROUND_HALF_UP) . '</span></span>';

                                $_resItem .= '<div class="resultItem d-flex flex-row shadow">';
                                $_resItem .= '<div class="d-flex flex-column w-75">';
                                $_resItem .= '<div class="resultItem-link mb-2"><span class="text-warning mr-2">' . $i . '.</span>' . $mainlink . '</div>';
                                $_resItem .= '<div class="resultItem-badges mb-1">' . $servicelink . $serviceinfo . '</div>';
                                $_resItem .= '<div class="resultItem-text">' . $text_str3 . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<div class="d-flex flex-column w-25">';
                                $_resItem .= '<div class="resultItem-text text-right">' . $text_str4 . '</div>';
                                $_resItem .= '<div class="resultItem-text text-right">' . $relevance . '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '</div>';
                                $_resItem .= '<|>';
                                break;
                            case 'dog_sub':
                                $serviceTitle = "Субподряд";
                                break;
                            case 'contragent':
                                $serviceTitle = "Справочник";
                                break;
                            default:
                                $serviceTitle = "Общий поиск";
                                break;
                        }
                        // Убираем разделитель, если не используем jquery pagination при выводе
                        // $search_output .= $_resItem;
                        $search_output .= str_replace('<|>', '', $_resItem);
                        $i++;
                    } // close while
                } else {
                    $search_output = '<div class="noResult mb-1">По запросу <b>' . $searchstring . '</b> ничего не найдено</div>';
                }
            }
        } else {
            $search_output = '<div class="errorResult text-danger mb-1">Что-то пошло не так...</div>';
        }
    }
}
unset($_POST);
// Вывод сообщений о результате загрузки.
echo $search_output;
