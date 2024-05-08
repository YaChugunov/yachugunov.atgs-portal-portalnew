<?php
# 
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
# 
?>
<div id="portalnew-main-bottom-blocks" class="d-flex flex-row justify-content-center mt-5">
    <?php if (__UI_PERSONAL_PORTALNEW_SHOWLEFTBOTTOMBLOCK == '1') { ?>
    <div class="card card-bottom-left d-flex flex-column border-transparent mb-3 mr-2 corner-box corner-box-bottomL"
         style="min-width:25%">
        <div class="card-body <?php echo $cardBody_textColor_class; ?> corner-textbox d-flex flex-column">
            <div class="flex-fill mb-3">
                <h5 class="<?php echo $cardTitle_H5_class; ?>">Ваш обед</h5>
                <div id="currentDinner" class="d-flex flex-column">
                </div>
            </div>
            <div class="mt-auto">
                <a class="<?php echo $dinnerOrder_btn_class; ?> mb-3" href="#noanchor" data-toggle="modal"
                   data-target="#modalTelegramBot" data-toggle="popover"
                   data-content="Модно и современно работаем с сервисом заказов обедов" role="button">atgsdinner_bot</a>
                <a class="<?php echo $dinnerOrder_btn_class; ?>" href="http://dinner.atgs.ru" role="button"
                   data-toggle="popover" data-content="Заказать обед на сайте dinner.atgs.ru">Заказать обед</a>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="card border-transparent mb-3 mx-2 corner-box corner-box-icons corner-box-bottomC" style="min-width:50%">
        <div class="card-body d-flex flex-column text-secondary corner-textbox h-100">
            <h5 class="<?php echo $cardTitle_H5_class; ?>">Все сервисы</h5>
            <div class="d-flex flex-row flex-wrap justify-content-center h-100">

                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet" class="text-secondary"
                       data-toggle="popover" data-content="Открыть сервис Договор">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-file-signature fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Договор</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailnew/index.php?type=in&mode=thisyear"
                       class="text-secondary" data-toggle="popover" data-content="Открыть входящую почту АТГС">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-envelope-open fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Входящая</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailnew/index.php?type=out&mode=thisyear"
                       class="text-secondary" data-toggle="popover" data-content="Открыть исходящую почту АТГС">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-envelope fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Иcходящая</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet/dognet-agreeview.php?docview_type=current"
                       class="text-secondary" data-toggle="popover"
                       data-content="Открыть перечень заключенных соглашений с контрагентами">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-handshake fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Соглашения</div>
                    </a>
                </div>

                <div class="service-item d-flex flex-column text-secondary">
                    <?php if (checkUserRestrictions_defaultDB($_SESSION['id'], 'dognet', 3, 0) == 1 && checkIsItGIP($_SESSION['id'])) { ?>
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet/dognet-blankview.php?blankview_type=current"
                       class="text-secondary" data-toggle="popover"
                       data-content="<div class='text-center'>Открыть сервис создания заявок на договор (бланков) для ГИПов</div>">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-list-check fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Бланки (ГИП)</div>
                    </a>
                    <?php
                    } elseif (checkUserRestrictions_defaultDB($_SESSION['id'], 'dognet', 4, 0) == 1) { ?>
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet/dognet-blankview.php?blankview_type=edit"
                       class="text-secondary" data-toggle="popover"
                       data-content="<div class='text-center'>Открыть сервис обработки заявок на договор (бланков) для ОД</div>">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-list-check fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Бланки (ОД)</div>
                    </a>
                    <?php } else { ?>
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet/dognet-blankview.php?blankview_type=edit"
                       class="text-secondary" data-toggle="popover"
                       data-content="<div class='text-center'>Сервис заявок на договор (бланков)</div>">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-list-check fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Бланки</div>
                    </a>
                    <?php } ?>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dognet/dognet-zayvview.php?zayvview_type=current"
                       class="text-secondary" data-toggle="popover"
                       data-content="Открыть раздел Заявки сервиса Договор">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-rectangle-list fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Заявки</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailATC/incoming.php?mode=thisyear"
                       class="text-secondary" data-toggle="popover" data-content="Входящая почта АТ Система">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-envelope-open fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Входящая АТ</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailATC/outgoing.php?mode=thisyear"
                       class="text-secondary" data-toggle="popover" data-content="Исходящая почта АТ Система">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-envelope fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Иcходящая АТ
                        </div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/hr" class="text-secondary"
                       data-toggle="popover" data-content="Открыть сервис Кадры">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-people-group fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Кадры</div>
                    </a>
                </div>

                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/eda" class="text-secondary"
                       data-toggle="popover" data-content="Открыть сервис заказа обедов Еда 2.0">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-mug-hot fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Еда 2.0</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/ism" class="text-secondary"
                       data-toggle="popover" data-content="Открыть сервис ИСМ/СМК">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-folder-tree fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">ИСМ/СМК</div>
                    </a>
                </div>
                <div class="service-item d-flex flex-column text-secondary">
                    <div id="portalCloudFiles-button" class="text-secondary" data-toggle="popover"
                         data-content="Посмотреть содержимое Облака">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-cloud fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Облако</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php if (__UI_PERSONAL_PORTALNEW_SHOWRIGHTBOTTOMBLOCK == '1') { ?>
    <div class="mb-3 ml-2 d-flex flex-column" style="min-width:25%">
        <div class="card card-bottom-right border-transparent corner-box corner-box-bottomR flex-fill mb-3"
             style="width:100%">
            <div class="d-flex flex-column">
                <div class="card-body text-secondary corner-textbox">
                    <h5 class="<?php echo $cardTitle_H5_class; ?>">Дни рождения</h5>
                    <div id="currentBirthdays" class="d-flex flex-column"></div>
                </div>
            </div>
        </div>
        <!-- <img src="<?php echo __ROOT . __SERVICENAME_PORTALNEW . '/_assets/images/banners/banner-squad-22032024.jpg'; ?>" class="img-fluid mx-auto mb-3"> -->
        <!-- <a href="#noanchor" class="" data-toggle="modal" data-target="#modalTelegramBot"><img
                 src="<?php echo __ROOT . __SERVICENAME_PORTALNEW . '/_assets/images/banners/banner-squad-dinner-01.jpg'; ?>"
                 class="img-fluid mx-auto mb-3"></a> -->
        <div class="card card-bottom-right border-transparent corner-box corner-box-bottomR mt-auto" style="width:100%">
            <div class="d-flex flex-column">
                <div class="card-body text-secondary corner-textbox">
                    <div class="card-text text-center align-self-center mb-0">
                        <h5 class="<?php echo $cardTitle_H5_class; ?>">Список контактов АТГС</h5>
                        <a class="getDoc-SpTel-button <?php echo $getSpTel_btn_class; ?>" role="button"
                           data-toggle="popover"
                           data-content="<div class='text-center w-100'>Скачать актуальный файл со списком внутренних телефонов и электронной почты сотрудников в офисе в привычном оформлении.</div>">Получить
                            документ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php if (__UI_PERSONAL_PORTALNEW_SHOWCLOUDBLOCK == '1') { ?>
<div id="portalnew-main-bottom-files" class="d-flex flex-column justify-content-center mt-2">
    <div class="card bg-transparent border-0 mb-3 h-100">
        <div class="card-body text-secondary d-flex align-items-top">
            <div class="card-text text-center d-flex flex-column mb-3 w-100" style="min-height: 20rem">
                <h5 class="card-title mb-4 text-center text-uppercase" style="letter-spacing: 0.75rem">
                    Облако</h5>
                <div class="drop-container drop-class drag-none d-flex flex-column justify-content-center text-center h-100"
                     style="">
                    <div class="drag-caption none align-self-center" style="">Drag and drop<br><span
                              class="special-text" style="">Если вы хотите закинуть файл(ы) в облако, прсто перетащите
                            их сюда</span>
                    </div>
                    <div id="upload-msg" class="upload-msg d-flex flex-row justify-content-center mx-auto"></div>
                </div>

                <div class="service-item d-flex flex-column align-items-center text-secondary">
                    <div id="portalCloudFiles-button" class="text-secondary" data-toggle="popover"
                         data-content="Посмотреть содержимое Облака">
                        <div class="service-icon px-5 py-2 mb-1 text-center">
                            <i class="fa-solid fa-cloud fa-2xl"></i>
                        </div>
                        <div class="service-title_S text-center">Облако</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center align-items-center py-3 border-bottom-0">
                <h3 class="modal-title text-uppercase text-danger"
                    style="font-family:'HeliosCond',Arial,Helvetica Neue,Helvetica,sans-serif; font-size:2.25rem; font-weight:600">
                    Анонс новой функции</h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p class="text-justify text-dark"><b>UPD 31.01.2024</b> <span class="text-danger">Допиливаю
                            телеграм-бот, который будет
                            выполнять все те функции, заявленные ранее (см. ниже). Решил, что это будет лучшей
                            альтернативой
                            просто прямым ссылкам. На этой неделе все будет. Ожидайте...</span></p>
                    <p class="text-justify text-dark">Для тех, кто в полях, в дороге, занят... Или же если тупо лень
                        заходить через
                        телефон или комп,
                        логиниться и что-то там выбирать, но обедать на следующей неделе вы все-таки планируете и против
                        полного комплексного обеда от Нам-Ням ничего не имеете, то
                        появилась возможность сделать заказ максимально просто и быстро.</p>
                    <p class="text-justify text-dark">Со следующей недели в этом
                        всплывающем окне каждый пользователь
                        сможет обнаружить <span class="text-danger"><b>6 уникальных ссылок</b></span>, сохранив
                        которые,
                        вы сможете впоследствии сделать заказ комплексного обеда себе на следующую неделю просто кликнув
                        на ней.</p>
                    <p class="text-justify alert alert-warning">Ссылки уникальные для каждого, делиться ими не стоит,
                        иначе будете оплачивать то, что не заказывали сами :) В любом случае, свой заказ стоит
                        проконтролировать и сверить с рассылкой с заказанными позициями, которая приходит перед
                        закрытием текущего заказа.</p>
                    <p class="text-justify text-dark">Почему шесть? Очень просто, 5 дней недели отдельно + вся неделя
                        сразу. Т.е. просто кликнув на
                        ссылку "вся неделя сразу", вы одним кликом обеспечиваете себя комплексными обедами на всю неделю
                        заказа.</p>
                    <p class="text-justify text-dark"><b>UPD</b> Вы всегда сможете заблокировать работу этих ссылок,
                        если вы сначала их сформировали, но потом решили, что вам такое больше не нужно. В этом случае
                        ссылки будут удалены, пока вы их не сформируете снова.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center align-items-center border-top-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="modalTelegramBot" tabindex="-1" role="dialog" aria-labelledby="modalTelegramBotTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center align-items-center py-3 border-bottom-0">
                <h3 class="modal-title text-uppercase text-dark"
                    style="font-family:'HeliosCond',Arial,Helvetica Neue,Helvetica,sans-serif; font-size:2.25rem; font-weight:600">
                    Телеграм-бот сервиса заказа обедов</h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p class="text-justify text-dark">Stable-версия телеграм-бота сервиса ЕДА доступна для
                        работы с реальной БД и реальными заказами. Этап тестирования завершен. Для полноценной работы с
                        ботом вам в необходимо будет пройти в нем авторизацию с
                        учетными данными, с которыми вы работаете с сервисом <a href="https://dinner.atgs.ru"
                           target="_blank">АТГС.Еда</a>.</p>
                    <p class="text-justify text-dark">Что можно сделать в боте:</p>
                    <ul class="text-dark" style="list-style:none">
                        <li>- Отменить авторизацию :)</li>
                        <li>- Посмотреть свои заказы на текущую и на неделю заказа</li>
                        <li>- Сделать заказ на выбранный день (любой поставщик, любая позиция, любое количество)</li>
                        <li>- Удалить заказ на выбранный день</li>
                        <li>- Финансовая сводка ваших расходов на питание на текущей неделе и за текущий месяц</li>
                    </ul>
                    <h3 class="text-center text-dark"><b><a href="https://t.me/atgsdinner_bot"
                               target="_blank">atgsdinner_bot</a></b></h3>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" language="javascript" class="init">
$(window).on("load", function() {

    ajaxRequest_getCurrentDinner('currentDinner');
    ajaxRequest_getBirthdays('currenBirthdays');

    $('#portalnew-main-bottom-files div[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
    })
});


$(document).ready(function() {
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    $('.drop-container').on('dragover', function() {
        $("#upload-msg").html('');
        $('.drop-class').removeClass('drag-none').addClass(
            'drag-over');
        $('.drop-container .drag-caption').removeClass('none').addClass('over').html(
            'Drag and drop'
        );
        return false;
    });

    $(".drop-container").on('dragleave', function() {
        $(".drop-class").removeClass('drag-over').addClass(
            'drag-none');
        $('.drop-container .drag-caption').removeClass('over').addClass('none').html(
            'Drag and drop<br><span style="font-size:0.9rem; color:#444444">Если вы хотите закинуть файл(ы) в облако, прсто перетащите их сюда</span>'
        );
        return false;
    });

    $(".drop-container").on("drop", function(e) {
        e.preventDefault();
        $(".drop-class").removeClass('drag-over').addClass(
            'drag-none');
        $('.drop-container .drag-caption').removeClass('over').addClass('none').html(
            '<div class="spinner-border text-warning" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>'
        );
        var formData = new FormData();
        var files_list = e.originalEvent.dataTransfer.files;
        //console.log(file_list);
        for (var i = 0; i < files_list.length; i++) {
            formData.append('file[]', files_list[i]);
        }

        $.ajax({
            method: "POST",
            url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/upload.php',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function(response) {
                $('.drop-container .drag-caption').removeClass('over')
                    .addClass('none').empty('');
                if (response === 'ok') {
                    console.log('upload1', response);
                    $("#upload-msg").html(
                        '<span class=""><i class="fa-solid fa-check fa-2xl text-success"></i></span>'
                    );
                    sleep(2000).then(() => {
                        $("#upload-msg").html('');
                        $('.drop-container .drag-caption').removeClass('over')
                            .addClass('none').text(
                                'Drag and drop');
                    });
                } else {
                    console.log('upload2', response);
                    $("#upload-msg")
                        .html(
                            '<span class=""><i class="fa-solid fa-mark fa-2xl text-danger"></i></span>'
                        )
                }
            }
        })
    });


    $(document).on("click", "div#portalCloudFiles-button", function() {
        // var koddocmail = $(this).attr('data-id');
        $('#portalCloud-listFiles-modal').modal('show');
        console.log('#portalCloud-listFiles-modal clicked!');
        $('#portalCloud-listFiles-modal > div.modal-dialog').on('shown.bs.modal', function(e) {
            console.log('#portalCloud-listFiles-modal > div.modal-dialog shown!');
            $("#portalCloud-listFiles-modal > div.modal-dialog").css({
                "width": "50%",
                "min-width": "640px",
                "max-width": "800px"
            });
            $.fn.dataTable.tables({
                visible: true,
                api: true
            });
        });
        var editor_portalCloudFiles = new $.fn.dataTable.Editor({
            display: "bootstrap",
            ajax: {
                url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH; ?>/cloudFiles/process/portalCloudFiles-process.php",
                type: "POST",
            },
            table: "#table-portalCloudFiles",
            fields: [{
                label: "",
                type: "textarea",
                name: "portal_cloud_files.comment",
                attr: {
                    placeholder: 'Описание файла'
                },
                className: "block"
            }]
        });
        var table_portalCloudFiles = $('#table-portalCloudFiles').dataTable({
            dom: "<'row'<'col-sm-5'><'col-sm-4'><'col-sm-3'>>" +
                "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-6'><'col-sm-6'p>>",
            language: {
                url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH; ?>/cloudFiles/dt_russian-portalCloudFile.json"
            },
            ajax: {
                url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH; ?>/cloudFiles/process/portalCloudFiles-process.php",
                type: "POST",
            },
            serverSide: true,
            createdRow: function(row, data, index) {
                $('span[data-toggle="popover"]', row).popover({
                    html: true,
                    placement: 'top',
                    trigger: "hover"
                });

            },
            columns: [{
                data: "portal_cloud_files.file_url"
            }, {
                data: "portal_cloud_files.comment",
                className: "fileComment"
            }, {
                data: null,
                className: "text-center",
                defaultContent: '',
                orderable: false
            }],
            columnDefs: [{
                    orderable: false,
                    searchable: true,
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return data ?
                            '<span class="file-info mr-2" data-toggle="popover" data-content="' +
                            row
                            .portal_cloud_files.dateloaded + '<br>' +
                            row.portal_cloud_files.usernameloaded + '<br>' + row
                            .portal_cloud_files.file_size +
                            ' кБ"><i class="fa-regular fa-lightbulb fa-sm"></i></span><span class=""><a target="_blank" href="' +
                            data + '">' + row
                            .portal_cloud_files
                            .file_originalname + '</a></span>' : row.portal_cloud_files
                            .file_originalname;
                    },
                },
                {
                    orderable: false,
                    searchable: true,
                    targets: 1,
                    render: function(data, type, row, meta) {
                        if (data !== "" && data !== null) {
                            return data;
                        } else {
                            return '<span class="text-secondary">Описания к файлу нет</span>';
                        }
                    }
                },
                {
                    orderable: false,
                    searchable: false,
                    targets: 2,
                    render: function(data, type, row, meta) {
                        return '<span class=""><i class="cloudFile-remove fa-solid fa-trash-can fa-sm text-danger mx-1"></i></span>';
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            ordering: false,
            select: false,
            processing: false,
            destroy: true,
            paging: true,
            pagingType: "numbers",
            searching: false,
            pageLength: 10,
            lengthChange: false,
            lengthMenu: [
                [30, 50, 100, -1],
                [30, 50, 100, "Все"]
            ],
            buttons: [{
                extend: "edit",
                editor: editor_portalCloudFiles,
                text: 'Комментарий',
                formButtons: ['Сохранить',
                    {
                        text: 'Отмена',
                        action: function() {
                            this.close();
                        }
                    }
                ]
            }]
        });
        //
        $('#table-portalCloudFiles').on('click', 'tbody td:nth-child(2)', function(e) {
            // e.preventDefault();
            editor_portalCloudFiles.inline(this, {
                submitOnBlur: true
            });
        });
        //
        $('#table-portalCloudFiles').on('click', 'td i.cloudFile-remove', function(e) {
            //     e.preventDefault();
            editor_portalCloudFiles.remove($(this).closest('tr'), false).submit();
        });
    });
    //
    $(document).on("click", "a.getDoc-SpTel-button", function() {
        console.log('a.getDoc-SpTel-button clicked!');
        ajaxRequest_getSpTelDocListAsync();
    });
})
</script>