<?php
// services-config.php - Конфигурация сервисов заказа обедов

$servicesConfig = [
    'title' => 'Как можно заказать обед',
    'subtitle' => 'Эволюция сервиса с 2016 года...',
    'services' => [
        [
            'id' => 'eda',
            'name' => 'Еда',
            'desc' => 'С 2016 года по настоящее время',
            'icon' => 'fa-solid fa-box-seam',
            'icon_class' => 'orange',
            'rating' => '★★★★★',
            'rating_value' => '4.8',
            'popular' => true,
            'features' => [
                ['label' => 'Доступ к сервису', 'icon' => 'fa-solid fa-wifi', 'status' => 'full', 'popover' => 'Это обычный общедоступный сайт через авторизацию по логину и паролю, на который можно зайти из любой точки вселенной как на любой другой сайт.'],
                ['label' => 'Заказ с телефона', 'icon' => 'fa-solid fa-mobile-screen', 'status' => 'mid', 'popover' => '<div>Сервис плохо адаптирован под экраны мобильных устройств. Работа с сервисом возможна, но крайне неудобна.</div>'],
                ['label' => 'Заказ для коллеги', 'icon' => 'fa-regular fa-user', 'status' => 'yes', 'popover' => 'Возможен заказ для коллег по подразделению.'],
                ['label' => 'Доставка до офиса', 'icon' => 'fa-solid fa-truck', 'status' => 'yes', 'popover' => 'Доставка обедов прямо в офис к указанному времени'],
                ['label' => 'Скидки постоянным', 'icon' => 'fa-regular fa-gem', 'status' => 'no', 'popover' => 'Система скидок для постоянных клиентов'],
                ['label' => 'Свободный доступ к сервису', 'icon' => 'fa-regular fa-calendar', 'status' => 'yes', 'popover' => '<a href="https://dinner.atgs.ru" target="_blank">dinner.atgs.ru</a> - это обычный общедоступный сайт для заказа обедов с доступом через авторизацию по логину и паролю.'],
                ['label' => 'Свободный доступ к сервису', 'icon' => 'fa-regular fa-calendar', 'status' => 'yes', 'popover' => '<a href="https://dinner.atgs.ru" target="_blank">dinner.atgs.ru</a> - это обычный общедоступный сайт для заказа обедов с доступом через авторизацию по логину и паролю.'],
            ],
            'btn_text' => 'Выбрать сервис',
            'btn_link' => '#'
        ],
        [
            'id' => 'eda20',
            'name' => 'Еда 2.0',
            'desc' => 'С 2022 года по настоящее время',
            'icon' => 'fa-solid fa-clock',
            'icon_class' => 'green',
            'rating' => '★★★★☆',
            'rating_value' => '4.3',
            'popular' => false,
            'features' => [
                ['label' => 'Доступ к сервису', 'icon' => 'fa-solid fa-wifi', 'status' => 'lim', 'popover' => 'Этот сервис является частью корпоративного Портала, поэтому доступ к нему возможен исключительно из корпоративной локальной сети или извне через VPN.'],
                ['label' => 'Заказ с телефона', 'icon' => 'fa-solid fa-mobile-screen', 'status' => 'no', 'popover' => 'Интерфейс сервиса как и интерфейс всех сервисов корпоративного Портала не адаптировался под работу на экранах мобильных устройств по причине отсутствия такой задачи изначально.'],
                ['label' => 'Заказ для коллеги', 'icon' => 'fa-regular fa-user', 'status' => 'no', 'popover' => 'Заказ для коллег не предусмотрен.'],
                ['label' => 'Доставка до офиса', 'icon' => 'fa-solid fa-truck', 'status' => 'yes', 'popover' => 'Доставка обедов прямо в офис к указанному времени'],
                ['label' => 'Скидки постоянным', 'icon' => 'fa-regular fa-gem', 'status' => 'yes', 'popover' => 'Система скидок для постоянных клиентов'],
            ],
            'btn_text' => 'Выбрать сервис',
            'btn_link' => '#'
        ],
        [
            'id' => 'newfood',
            'name' => 'Новая еда',
            'desc' => 'С 2026 года, активно использовался вайб кодинг',
            'icon' => 'fa-solid fa-star',
            'icon_class' => 'purple',
            'rating' => '★★★★★',
            'rating_value' => '4.9',
            'popular' => false,
            'features' => [
                ['label' => 'Доступ к сервису', 'icon' => 'fa-solid fa-wifi', 'status' => 'full', 'popover' => 'Это обычный общедоступный сайт через авторизацию по логину и паролю, на который можно зайти из любой точки вселенной как на любой другой сайт.'],
                ['label' => 'Заказ с телефона', 'icon' => 'fa-solid fa-mobile-screen', 'status' => 'yes', 'popover' => '<span>Максимальная адаптация интерфейса под экраны мобильных устройств - телефон, планшет. Полная унификация интерфейса для телефона, планшета, ноутбука и десктопной версии.</span>'],
                ['label' => 'Заказ для коллеги', 'icon' => 'fa-regular fa-user', 'status' => 'yes', 'popover' => 'Возможен заказ для любых избранных коллег без привязки к подразделению. Перечень избранных коллег на текущий момент формируется админом по запросу пользователя.'],
                ['label' => 'Доставка до офиса', 'icon' => 'fa-solid fa-truck', 'status' => 'yes', 'popover' => 'Доставка обедов прямо в офис к указанному времени'],
                ['label' => 'Скидки постоянным', 'icon' => 'fa-regular fa-gem', 'status' => 'yes', 'popover' => 'Система скидок для постоянных клиентов'],
            ],
            'btn_text' => 'Выбрать сервис',
            'btn_link' => '#'
        ]
    ]
];

// Статусы для отображения
$statusMap = [
    'yes' => ['class' => 'status-yes', 'icon' => 'fa-solid fa-check', 'text' => 'Есть'],
    'full' => ['class' => 'status-yes', 'icon' => 'fa-solid fa-check', 'text' => 'Полный'],
    'no' => ['class' => 'status-no', 'icon' => 'fa-solid fa-xmark', 'text' => 'Нет'],
    'mid' => ['class' => 'status-mid', 'icon' => 'fa-solid fa-minus', 'text' => 'Частично'],
    'lim' => ['class' => 'status-mid', 'icon' => 'fa-solid fa-minus', 'text' => 'Ограниченный'],
];
?>