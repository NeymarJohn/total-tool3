<?php
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}
ini_set('max_execution_time', 0);
function check_($check) {
    $siteurl           = urlencode(getBaseUrl());
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false
        )
    );
    $file              = file_get_contents('http://www.wowonder.com/purchase.php?code=' . $check . '&url=' . $siteurl, false, stream_context_create($arrContextOptions));
    if ($file) {
        $check = json_decode($file, true);
    } else {
        $check = array(
            'status' => 'SUCCESS',
            'url' => $siteurl,
            'code' => $check
        );
    }
    return $check;
}
$updated = false;
if (!empty($_GET['updated'])) {
    $updated = true;
}
if (!empty($_POST['code'])) {
    $code = check_($_POST['code']);
    if ($code['status'] == 'SUCCESS') {
        $data['status'] = 200;
    } else {
        $data['status'] = 400;
        $data['error']  = $code['ERROR_NAME'];
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['query'])) {
    $query = mysqli_query($sqlConnect, base64_decode($_POST['query']));
    if ($query) {
        $data['status'] = 200;
    } else {
        $data['status'] = 400;
        $data['error']  = mysqli_error($sqlConnect);
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['update_langs'])) {
    $data  = array();
    $query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM `Wo_Langs`");
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = $fetched_data['Field'];
    }
    unset($data[0]);
    unset($data[1]);
    unset($data[2]);
    function Wo_UpdateLangs($lang, $key, $value) {
        global $sqlConnect;
        $update_query         = "UPDATE Wo_Langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
        $update_replace_array = array(
            "{lang}",
            "{lang_text}",
            "{lang_key}"
        );
        return str_replace($update_replace_array, array(
            $lang,
            Wo_Secure($value),
            $key
        ), $update_query);
    }
    $lang_update_queries = array();
    foreach ($data as $key => $value) {
        $value = ($value);
        if ($value == 'arabic') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'كانون الثاني');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'شهر فبراير');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'مارس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'أبريل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'مايو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'يونيو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'يوليو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'أغسطس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'سبتمبر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'اكتوبر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'شهر نوفمبر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'ديسمبر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'إشعارات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'البدء');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'يرجى تمكين الموقع في المتصفح لعرض الطقس الحالي.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'الوصول إلى الموقع الخاص بك هو معطل. تمكينه على المتصفح الخاص بك إذا كنت تريد أن ترى الناس من حولك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'الأحد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'الإثنين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'الثلاثاء');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'الأربعاء');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'الخميس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'يوم الجمعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'يوم السبت');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'januari');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'februari');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'maart');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'april');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'mei');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'juni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'juli');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'augustus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'september');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'oktober');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'november');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'december');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'meldingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Begin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Activeer locatie op uw browser om de actuele weergegevens te bekijken.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'De toegang tot uw locatie is uitgeschakeld. Schakel het op uw browser als u wilt mensen om je heen te zien.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'zondag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'maandag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'dinsdag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'woensdag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'donderdag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'vrijdag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'zaterdag');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'janvier');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'février');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'Mars');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'avril');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Mai');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'juin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'juillet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'août');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'septembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'octobre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'novembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'décembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'notifications');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Commencer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'S\'il vous plaît activer adresse de votre navigateur pour afficher la météo actuelle.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'L\'accès à votre emplacement est désactivé. Activez-le sur votre navigateur si vous voulez voir les gens autour de vous.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'dimanche');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Lundi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'Mardi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'Mercredi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Jeudi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Vendredi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'samedi');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'Januar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'Februar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'März');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'April');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Kann');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'Juni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'Juli');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'August');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'September');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'Oktober');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'November');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'Dezember');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Benachrichtigungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Loslegen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Bitte aktivieren Sie diese Position auf Ihrem Browser aktuelles Wetter anzuzeigen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Zugriff auf Ihren Standort ist deaktiviert. Aktivieren Sie es auf Ihrem Browser, wenn du um dich herum zu sehen, die Leute wollen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Sonntag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Montag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'Dienstag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'Mittwoch');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Donnerstag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Freitag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'Samstag');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'gennaio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'febbraio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'marzo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'aprile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Maggio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'giugno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'luglio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'agosto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'settembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'ottobre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'novembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'dicembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'notifiche');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Iniziare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Si prega di abilitare posizione sul tuo browser per visualizzare meteo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'L\'accesso alla tua posizione è Disabilitato. Attiva sul vostro browser, se volete vedere persone intorno a voi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Domenica');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Lunedi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'martedì');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'mercoledì');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'giovedi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Venerdì');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'Sabato');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'janeiro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'fevereiro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'marcha');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'abril');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Maio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'Junho');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'Julho');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'agosto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'setembro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'Outubro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'novembro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'dezembro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'notificações');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Iniciar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Ative o local em seu navegador para ver o tempo atual.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Acesso à sua localização está desativada. Ativá-lo no seu navegador se você quiser ver as pessoas ao seu redor.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'domingo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Segunda-feira');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'terça');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'quarta-feira');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'quinta-feira');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Sexta-feira');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'sábado');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'январь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'февраль');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'марш');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'апреля');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'май');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'июнь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'июль');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'августейший');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'сентябрь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'октября');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'ноябрь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'Декабрь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Уведомления');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Начать');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Пожалуйста, включите местоположение в вашем браузере для просмотра текущей погоды.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Доступ к вашему местоположению отключен. Включите его в вашем браузере, если вы хотите, чтобы видеть, что люди вокруг вас.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Воскресенье');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'понедельник');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'вторник');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'среда');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Четверг');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'пятница');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'суббота');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'enero');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'febrero');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'marzo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'abril');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Mayo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'junio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'julio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'agosto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'septiembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'octubre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'noviembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'diciembre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Notificaciones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Empezar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Por favor, activa ubicación en su navegador para ver tiempo actual.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'El acceso a su ubicación es Desactivado. Activarlo en su navegador si desea ver a las personas que le rodean.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'domingo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'lunes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'martes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'miércoles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'jueves');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'viernes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'sábado');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'Ocak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'Şubat');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'Mart');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'Nisan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'Mayıs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'Haziran');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'Temmuz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'Ağustos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'Eylül');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'Ekim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'Kasım');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'Aralık');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Bildirimler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Başlamak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Şu anki hava durumunu görüntülemek için tarayıcınızda konum özelliğini etkinleştirmek edin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Bulunduğunuz yere erişim Devre Dışı. çevrenizdeki insanları görmek istiyorsanız tarayıcınızda etkinleştirin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Pazar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Pazartesi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'Salı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'Çarşamba');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Perşembe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Cuma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'Cumartesi');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'January');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'February');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'March');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'April');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'May');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'June');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'July');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'August');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'September');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'October');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'November');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'December');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Notifications');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Get Started');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Please enable location on your browser to view current weather.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Access to your location is Disabled. Enable it on your browser if you want to see people around you.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Sunday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Monday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'Tuesday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'Wednesday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Thursday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Friday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'Saturday');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'january', 'January');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'february', 'February');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'march', 'March');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'april', 'April');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'may', 'May');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'june', 'June');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'july', 'July');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'august', 'August');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'september', 'September');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'october', 'October');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'november', 'November');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'december', 'December');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notifications_single', 'Notifications');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_started', 'Get Started');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_weather_loc', 'Please enable location on your browser to view current weather.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'enable_friend_loc', 'Access to your location is Disabled. Enable it on your browser if you want to see people around you.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sunday', 'Sunday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'monday', 'Monday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'tuesday', 'Tuesday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wednesday', 'Wednesday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thursday', 'Thursday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friday', 'Friday');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'saturday', 'Saturday');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
    }
    $games = $db->get(T_GAMES);
    if (!empty($games)) {
        foreach ($games as $key => $value) {
            if (!Wo_IsUrl($value->game_link)) {
                $db->where('id', $value->id)->update(T_GAMES, array(
                    'game_link' => 'https://www.miniclip.com/games/' . $value->game_link . '/en/webgame.php'
                ));
            }
        }
    }
    $name = md5(microtime()) . '_updated.php';
    rename('update.php', $name);
}
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <title>Updating WoWonder</title>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
         @import url('https://fonts.googleapis.com/css?family=Roboto:400,500');
         @media print {
            .wo_update_changelog {max-height: none !important; min-height: !important}
            .btn, .hide_print, .setting-well h4 {display:none;}
         }
         * {outline: none !important;}
         body {background: #f3f3f3;font-family: 'Roboto', sans-serif;}
         .light {font-weight: 400;}
         .bold {font-weight: 500;}
         .btn {height: 52px;line-height: 1;font-size: 16px;transition: all 0.3s;border-radius: 2em;font-weight: 500;padding: 0 28px;letter-spacing: .5px;}
         .btn svg {margin-left: 10px;margin-top: -2px;transition: all 0.3s;vertical-align: middle;}
         .btn:hover svg {-webkit-transform: translateX(3px);-moz-transform: translateX(3px);-ms-transform: translateX(3px);-o-transform: translateX(3px);transform: translateX(3px);}
         .btn-main {color: #ffffff;background-color: #a84849;border-color: #a84849;}
         .btn-main:disabled, .btn-main:focus {color: #fff;}
         .btn-main:hover {color: #ffffff;background-color: #c45a5b;border-color: #c45a5b;box-shadow: -2px 2px 14px rgba(168, 72, 73, 0.35);}
         svg {vertical-align: middle;}
         .main {color: #a84849;}
         .wo_update_changelog {
          border: 1px solid #eee;
          padding: 10px !important;
         }
         .content-container {display: -webkit-box; width: 100%;display: -moz-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: column;flex-direction: column;min-height: 100vh;position: relative;}
         .content-container:before, .content-container:after {-webkit-box-flex: 1;box-flex: 1;-webkit-flex-grow: 1;flex-grow: 1;content: '';display: block;height: 50px;}
         .wo_install_wiz {position: relative;background-color: white;box-shadow: 0 1px 15px 2px rgba(0, 0, 0, 0.1);border-radius: 10px;padding: 20px 30px;border-top: 1px solid rgba(0, 0, 0, 0.04);}
         .wo_install_wiz h2 {margin-top: 10px;margin-bottom: 30px;display: flex;align-items: center;}
         .wo_install_wiz h2 span {margin-left: auto;font-size: 15px;}
         .wo_update_changelog {padding:0;list-style-type: none;margin-bottom: 15px;max-height: 440px;overflow-y: auto; min-height: 440px;}
         .wo_update_changelog li {margin-bottom:7px; max-height: 20px; overflow: hidden;}
         .wo_update_changelog li span {padding: 2px 7px;font-size: 12px;margin-right: 4px;border-radius: 2px;}
         .wo_update_changelog li span.added {background-color: #4CAF50;color: white;}
         .wo_update_changelog li span.changed {background-color: #e62117;color: white;}
         .wo_update_changelog li span.improved {background-color: #9C27B0;color: white;}
         .wo_update_changelog li span.compressed {background-color: #795548;color: white;}
         .wo_update_changelog li span.fixed {background-color: #2196F3;color: white;}
         input.form-control {background-color: #f4f4f4;border: 0;border-radius: 2em;height: 40px;padding: 3px 14px;color: #383838;transition: all 0.2s;}
input.form-control:hover {background-color: #e9e9e9;}
input.form-control:focus {background: #fff;box-shadow: 0 0 0 1.5px #a84849;}
         .empty_state {margin-top: 80px;margin-bottom: 80px;font-weight: 500;color: #6d6d6d;display: block;text-align: center;}
         .checkmark__circle {stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;}
         .checkmark {width: 80px;height: 80px; border-radius: 50%;display: block;stroke-width: 3;stroke: #fff;stroke-miterlimit: 10;margin: 100px auto 50px;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;}
         .checkmark__check {transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;}
         @keyframes stroke { 100% {stroke-dashoffset: 0;}}
         @keyframes scale {0%, 100% {transform: none;}  50% {transform: scale3d(1.1, 1.1, 1); }}
         @keyframes fill { 100% {box-shadow: inset 0px 0px 0px 54px #7ac142; }}
      </style>
   </head>
   <body>
      <div class="content-container container">
         <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
               <div class="wo_install_wiz">
                 <?php if ($updated == false) { ?>
                  <div>
                     <h2 class="light">Update to v3.0.2 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                                <li> [Added] Agora Live Streaming. </li>
                                <li> [Added] Few more APIs. </li>
                                <li> [Fixed] 40+ reported bugs.</li>
                                <li> [Fixed] bugs in API.</li>
                                <li> [Improved] Load speed.</li>
                        </ul>
                        <p class="hide_print">Note: The update process might take few minutes.</p>
                        <p class="hide_print">Important: If you got any fail queries, please copy them, open a support ticket and send us the details.</p>
                        <p class="hide_print">Most of the features are disabled by default, you can enable them from Admin > Site Settings > Manage Site Features, reaction can be enabled from Settings > Site Sttings.</p><br>
                        <p class="hide_print">Please enter your valid purchase code:</p>
                        <input type="text" id="input_code" class="form-control" placeholder="Your Envato purchase code" style="padding: 10px; width: 50%;"><br>

                        <br>
                             <button class="pull-right btn btn-default" onclick="window.print();">Share Log</button>
                             <button type="button" class="btn btn-main" id="button-update" disabled>
                             Update 
                             <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                                <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                             </svg>
                          </button>
                     </div>
                     <?php }?>
                     <?php if ($updated == true) { ?>
                      <div>
                        <div class="empty_state">
                           <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                              <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                              <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                           </svg>
                           <p>Congratulations, you have successfully updated your site. Thanks for choosing WoWonder.</p>
                           <br>
                           <a href="<?php echo $wo['config']['site_url'] ?>" class="btn btn-main" style="line-height:50px;">Home</a>
                        </div>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
            <div class="col-md-1"></div>
         </div>
      </div>
   </body>
</html>
<script>  
var queries = [
    "UPDATE `Wo_Config` SET `value` = '3.0.2' WHERE `name` = 'version';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'agora_app_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'agora_live_video', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'millicast_live_video', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'agora_customer_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'agora_customer_certificate', '');",
    "ALTER TABLE `Wo_Posts` ADD `agora_resource_id` TEXT NULL DEFAULT NULL AFTER `live_ended`, ADD `agora_sid` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `agora_resource_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yahoo_consumer_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yahoo_consumer_secret', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cashfree_mode', 'sandBox');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'amazone_s3_2', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'bucket_name_2', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'amazone_s3_key_2', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'amazone_s3_s_key_2', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'region_2', 'eu-west-1');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'january');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'february');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'march');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'april');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'may');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'june');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'july');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'august');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'september');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'october');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'november');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'december');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'notifications_single');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get_started');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'enable_weather_loc');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'enable_friend_loc');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sunday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'monday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tuesday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'wednesday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'thursday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'friday');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'saturday');",
];
$('#input_code').bind("paste keyup input propertychange", function(e) {
    if (isPurchaseCode($(this).val())) {
        $('#button-update').removeAttr('disabled');
    } else {
        $('#button-update').attr('disabled', 'true');
    }
});

function isPurchaseCode(str) {
    var patt = new RegExp("(.*)-(.*)-(.*)-(.*)-(.*)");
    var res = patt.test(str);
    if (res) {
        return true;
    }
    return false;
}

$(document).on('click', '#button-update', function(event) {
    if ($('body').attr('data-update') == 'true') {
        window.location.href = '<?php echo $wo['config']['site_url']?>';
        return false;
    }
    $(this).attr('disabled', true);
    var PurchaseCode = $('#input_code').val();
    $.post('?check', {code: PurchaseCode}, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').html('');
            $('.wo_update_changelog').css({
                background: '#1e2321',
                color: '#fff'
            });
            $('.setting-well h4').text('Updating..');
            $(this).attr('disabled', true);
            RunQuery();
        } else {
            $(this).removeAttr('disabled');
            alert(data.error);
        }
    });
});

var queriesLength = queries.length;
var query = queries[0];
var count = 0;
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}
function RunQuery() {
    var query = queries[count];
    $.post('?update', {
        query: b64EncodeUnicode(query)
    }, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').append('<li><span class="added">SUCCESS</span> ~$ mysql > ' + query + '</li>');
        } else {
            $('.wo_update_changelog').append('<li><span class="changed">FAILED</span> ~$ mysql > ' + query + '</li>');
        }
        count = count + 1;
        if (queriesLength > count) {
            setTimeout(function() {
                RunQuery();
            }, 1500);
        } else {
            $('.wo_update_changelog').append('<li><span class="added">Updating Langauges</span> ~$ languages.sh, Please wait, this might take some time..</li>');
            $.post('?run_lang', {
                update_langs: 'true'
            }, function(data, textStatus, xhr) {
              $('.wo_update_changelog').append('<li><span class="fixed">Finished!</span> ~$ Congratulations! you have successfully updated your site. Thanks for choosing WoWonder.</li>');
              $('.setting-well h4').text('Update Log');
              $('#button-update').html('Home <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"> <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path> </svg>');
              $('#button-update').attr('disabled', false);
              $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
              $('body').attr('data-update', 'true');
            });
        }
        $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
    });
}
</script>