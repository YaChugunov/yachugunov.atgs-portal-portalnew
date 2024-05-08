<?php
# 
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
# 
?>
<script type="text/javascript" language="javascript" class="">
    // 
    // ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
    function delay(fn, ms) {
        let timer = 0
        return function(...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    } // 
    //
    function simpleTemplating(data) {
        var html = '<div class="d-flex flex-column">';
        $.each(data, function(index, item) {
            html += item;
        });
        html += '</div>';
        return html;
    }
    //
    var reqField_searchString = {
        searchString: function(response) {
            console.log('searching output', response);
            $('#search-output-container').empty();
            if (checkVal(response)) {
                //     // $('#search-output').html(response);
                //     var x = response.split('<|>');
                //     $('#search-pagination-container').pagination({
                //         dataSource: x,
                //         pageNumber: 1,
                //         pageSize: 5,
                //         pageRange: 1,
                //         callback: function(data, pagination) {
                //             // template method of yourself
                //             var html = simpleTemplating(data);
                //             $('#search-output-container').html(html);
                //         }
                //     });
                $('#search-output-container').html(response);
            }
        }
    };

    function ajaxRequest_searchString(searchstring, filter_all, filter_mail, filter_dog, responseHandler) {
        request_searchString = $.ajax({
            type: "post",
            url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/search.php',
            cache: false,
            data: {
                searchstring: searchstring,
                filter_all: filter_all,
                filter_mail: filter_mail,
                filter_dog: filter_dog,
            },
            success: reqField_searchString[responseHandler]
        });
        // Callback handler that will be called on success
        request_searchString.done(function(response, textStatus, jqXHR) {
            res = response.replace(new RegExp("\\r?\\n", "g"), "");
            $('#searchingOutput-modal').modal('show');
        });
        request_searchString.fail(function(jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        // Callback handler that will be called regardless
        // if the request_addItem failed or succeeded
        request_searchString.always(function() {});
    }
    //
    //
    var reqField_searchHelp = {
        searchHelp: function(response) {
            $('#search-help').empty();
            if (checkVal(response)) {
                $('#search-help').html(response);
                //
            }
        }
    };

    function ajaxRequest_searchHelp(responseHandler) {}


    //
</script>
<?php
#
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
# 
?>
<div class="container d-flex flex-column">

    <div id="portalnew-main-center-icons" class="d-flex flex-row justify-content-center py-3">
        <div class="service-item d-flex flex-column">
            <div class="service-icon px-5 pt-2 pb-1">
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/sp/index.php?type=contragents&mode=main">
                    <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/sp/_assets/images/favicons/favicon.ico" alt="" width="64" height="64" class="d-inline-block align-text-middle">
                </a>
            </div>
            <div class="service-title text-center">Справочник</div>
        </div>
        <div class="service-item d-flex flex-column">
            <div class="service-icon px-5 pt-2 pb-1">
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailnew/index.php?type=main">
                    <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mailnew/_assets/images/favicons/favicon.ico" alt="" width="64" height="64" class="d-inline-block align-text-middle">
                </a>
            </div>
            <div class="service-title text-center">Почта</div>
        </div>
        <div class="service-item d-flex flex-column">
            <div id="portalCloudFiles-button" class="service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/php/examples/simple/main/cloudFiles/favicon.ico" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center">Облако</div>
        </div>
        <div class="service-item inactive d-flex flex-column" data-toggle="popover" data-content="1-я очередь (3-4 квартал, 2024)">
            <div class="service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/_assets/images/service-icons/icon-serviceLocked.svg" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center text-secondary">Новый договор</div>
        </div>
        <div class="service-item inactive d-flex flex-column" data-toggle="popover" data-content="1-я очередь (3-4 квартал, 2024)">
            <div class="service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/_assets/images/service-icons/icon-serviceLocked.svg" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center text-secondary">Субподрядчики</div>
        </div>
        <div class="service-item inactive d-flex flex-column" data-toggle="popover" data-content="2-я очередь (2-3 квартал, 2024)">
            <div class="service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/_assets/images/service-icons/icon-serviceLocked.svg" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center text-secondary">Новые кадры</div>
        </div>
        <div class="service-item inactive d-flex flex-column" data-toggle="popover" data-content="2-я очередь (1-2 квартал, 2025)">
            <div class=" service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/_assets/images/service-icons/icon-serviceLocked.svg" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center text-secondary">Новая СМК</div>
        </div>
        <div class="service-item inactive d-flex flex-column" data-toggle="popover" data-content="3-я очередь (3-4 квартал, 2024)">
            <div class=" service-icon px-5 pt-2 pb-1">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/portalnew/_assets/images/service-icons/icon-serviceLocked.svg" alt="" width="64" height="64" class="d-inline-block align-text-middle">
            </div>
            <div class="service-title text-center text-secondary">Новая еда</div>
        </div>
    </div>

    <div id="portalnew-main-input" class="d-flex flex-row shadow input-inactive-border">
        <input id="main-input" class="main-input form-control form-control-lg" type="text" placeholder="Тыкайте сюда, не бойтесь">
        <div class="d-flex align-self-center py-3 px-1" data-toggle="popover" data-content='<div class="text-center">Очистить поле поискового запроса.</div>'><i id="searchInput-clear" class="fa-solid fa-xmark fa-2xl mx-2 align-self-center text-secondary"></i></div>
        <div class="d-flex align-self-center py-3 px-1" data-toggle="popover" data-content='<div class="text-center">Настройка текущего поиска. Можно выбрать поиск как по всем разделам Портала, так и по какому-то отдельному.</div>'>
            <i id="searchInput-settings" class="fa-solid fa-gear fa-2xl mx-2 align-self-center text-secondary"></i>
        </div>
        <div class="d-flex align-self-center py-3 px-1" data-toggle="popover" data-content='<div class="text-center">Нажмите, если хотите узнать как работает посик по сервисам Портала и как им правильно пользоваться.</div>'>
            <i id="searchInput-help" class="fa-solid fa-question fa-2xl ml-2 mr-3 align-self-center text-secondary"></i>
        </div>
    </div>


</div>
<?php
# 
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
# 
?>

<script type="text/javascript" language="javascript" class="init">
    $(window).on("load", function() {
        //$(document).ready(function() {
        getUserSettings();

        $("#main-input").focus(function() {
            // $('#portalnew-main-center-icons').fadeOut('fast', function() {
            //     $(this).removeClass('invisible').fadeIn('slow');
            // });
            $('#portalnew-main-input').removeClass('shadow input-inactive-border').addClass(
                'shadow input-active-border');
            $('#footer .footer-logo, #portlnew-navbar .navbar-title h3').stop().animate({
                opacity: '1.0'
            }, 600);
            $(this).attr('placeholder',
                'Кстати, поиск теперь работает. Как им пользоваться? Кликайте на знак ? справа.'
            );
            $('#searchInput-help').addClass('fa-bounce');
        });
        //
        $("#main-input").blur(function() {
            // $('#portalnew-main-center-icons').fadeOut('slow', function() {
            //     $(this).addClass('invisible');
            // });
            $('#portalnew-main-input').removeClass('shadow input-active-border').addClass(
                'shadow input-inactive-border');
            $('#footer .footer-logo, #portlnew-navbar .navbar-title h3').stop().animate({
                opacity: '0.65'
            }, 600);
            $(this).attr('placeholder', 'Поиск по сервисам Портала');
            $('#searchInput-help').removeClass('fa-bounce');
        });
        //
        $("#searchInput-clear").click(function() {
            $("#main-input").val('');
        });
        $("#searchInput-settings").click(function() {
            getUserRestrictions('mail');
            getUserRestrictions('dognet');
        });



        //
        $('#main-input').keyup(delay(function(e) {
            var query = $(this).val();
            $('#searchInput-help').removeClass('fa-bounce');
            if (e.keyCode === 13 && query !== "") {
                e.preventDefault();
                filter_all = $('input[id="searchSwitch-all"]').is(':checked') ? 1 : 0;
                filter_mail = $('input[id="searchSwitch-mail"]').is(':checked') ? 1 : 0;
                filter_dog = $('input[id="searchSwitch-dog"]').is(':checked') ? 1 : 0;
                console.log('search settings!', filter_all, filter_mail, filter_dog)
                ajaxRequest_searchString(query, filter_all, filter_mail, filter_dog, 'searchString');
            }
        }, 500));

        $('#portalnew-main-center-icons div[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover',
            placement: 'top',
        })


    });
</script>