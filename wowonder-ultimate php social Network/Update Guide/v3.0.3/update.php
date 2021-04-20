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
            'url' => oiteurl,
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'انتهى دفق {{user}}.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'تغيير مصدر الميكروفون');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'تغيير مصدر الفيديو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'المتبقي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'طقس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'ريح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'رطوبة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'الرؤية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'شروق الشمس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'غروب الشمس');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'ابحث عن الشركات القريبة منك بناءً على موقعك وتواصل معها مباشرة.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'تصدير');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', '{{user}} stream is beëindigd.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Wijzig microfoonbron');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Wijzig videobron');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'resterend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Weer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Wind');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Vochtigheid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Zichtbaarheid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'zonsopkomst');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Zonsondergang');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Vind bedrijven bij u in de buurt op basis van uw locatie en maak direct contact met hen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Exporteren');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'Le flux {{user}} est terminé.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Changer la source du micro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Changer la source vidéo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'restant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Temps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Vent');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Humidité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibilité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'lever du soleil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Le coucher du soleil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Trouvez des entreprises près de chez vous en fonction de votre emplacement et connectez-vous directement avec elles.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Exportation');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'Der {{user}} Stream wurde beendet.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Mikrofonquelle wechseln');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Videoquelle ändern');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'verbleibend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Wetter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Wind');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Feuchtigkeit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Sichtweite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Sonnenaufgang');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Sonnenuntergang');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Finden Sie Unternehmen in Ihrer Nähe basierend auf Ihrem Standort und verbinden Sie sich direkt mit ihnen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Export');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'Lo stream {{user}} è terminato.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Cambia sorgente microfono');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Cambia sorgente video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'residuo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Tempo metereologico');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Vento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Umidità');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibilità');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Alba');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Tramonto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Trova le attività vicino a te in base alla tua posizione e connettiti direttamente con loro.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Esportare');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'O fluxo do {{user}} foi encerrado.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Alterar fonte do microfone');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Alterar fonte de vídeo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'remanescente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Clima');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Vento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Umidade');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibilidade');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Nascer do sol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Pôr do sol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Encontre empresas próximas a você com base em sua localização e conecte-se diretamente a elas.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Exportar');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'Поток {{user}} завершен.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Изменить источник микрофона');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Изменить источник видео');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'осталось');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Погода');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'ветер');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'влажность');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'видимость');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Восход');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Заход солнца');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Найти предприятия рядом с вами на основе вашего местоположения и связаться с ними напрямую.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'экспорт');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', 'La transmisión de {{usuario}} ha finalizado.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Cambiar fuente de micrófono');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Cambiar fuente de video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'restante');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Clima');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Viento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Humedad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibilidad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'amanecer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Puesta de sol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Encuentre negocios cerca de usted según su ubicación y conéctese con ellos directamente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Exportar');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', '{{user}} akışı sona erdi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Mikrofon Kaynağını Değiştir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Video Kaynağını Değiştir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'kalan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Hava');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'rüzgar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Nem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'görünürlük');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'gündoğumu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Gün batımı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Bulunduğunuz yere göre yakınınızdaki işletmeleri bulun ve onlarla doğrudan bağlantı kurun.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'İhracat');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', '{{user}} stream has ended.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Change Mic Source');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Change Video Source');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'remaining');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Weather');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Wind');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Humidity');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibility');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Sunrise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Sunset');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Find businesses near to you based on your location and connect with them directly.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Export');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stream_has_ended', '{{user}} stream has ended.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mic_source', 'Change Mic Source');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cam_source', 'Change Video Source');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'event_remaining', 'remaining');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_weather', 'Weather');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_wind', 'Wind');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_humidity', 'Humidity');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_visibility', 'Visibility');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunrise', 'Sunrise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'weather_sunset', 'Sunset');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_business', 'Find businesses near to you based on your location and connect with them directly.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'export', 'Export');
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
                     <h2 class="light">Update to v3.0.3 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                                <li> [Added] ability to choose camera on live streaming. </li>
                                <li> [Added] thumbnail for live streaming videos. </li>
                                <li> [Added] Few more APIs. (payment methods) </li>
                                <li> [Fixed] 20+ reported bugs.</li>
                                <li> [Fixed] bugs in API.</li>
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
    "UPDATE `Wo_Config` SET `value` = '3.0.3' WHERE `name` = 'version';",
    "ALTER TABLE `Wo_Offers` CHANGE `expire_date` `expire_date` VARCHAR(50) NOT NULL;",
    "ALTER TABLE `Wo_GroupChat` CHANGE `group_name` `group_name` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';",
    "ALTER TABLE `Wo_Offers` CHANGE `expire_date` `expire_date` DATE NOT NULL;",
    "ALTER TABLE `Wo_Offers` CHANGE `expire_time` `expire_time` TIME NOT NULL;",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stream_has_ended');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'mic_source');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'cam_source');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_remaining');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'home_weather');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'weather_wind');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'weather_humidity');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'weather_visibility');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'weather_sunrise');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'weather_sunset');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'find_nearby_business');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'export');",
   
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