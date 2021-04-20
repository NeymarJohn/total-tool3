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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'أضف إلى تقويم Google');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'ينعش');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'تعبيري');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'ابحث عن المتاجر القريبة منك بناءً على موقعك وتواصل معها مباشرةً.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'منتجات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'احصل على قروض.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'لم يتم العثور على تبرعات حديثة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'لديك سيطرة كاملة على معلوماتك الشخصية التي تشاركها.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'حسابك آمن تمامًا. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'م');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'ح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'حاليا');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'ساعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'د');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'ث');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'ذ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'سنوات');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Toevoegen aan Google Agenda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Vernieuwen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Vind winkels bij u in de buurt op basis van uw locatie en maak rechtstreeks contact met hen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Producten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Krijg credits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Geen recente donaties gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'U heeft volledige controle over uw persoonlijke informatie die u deelt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Uw account is volledig beveiligd. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'nu');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'uur');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'jr');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Ajouter à Google Agenda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Rafraîchir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Trouvez des magasins près de chez vous en fonction de votre emplacement et connectez-vous directement avec eux.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Des produits');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Obtenez des crédits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Aucun don récent trouvé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Vous avez un contrôle total sur vos informations personnelles que vous partagez.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Votre compte est entièrement sécurisé. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'à présent');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'heures');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'ré');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'ans');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Zum Google Kalender hinzufügen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Aktualisierung');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Finden Sie anhand Ihres Standorts Geschäfte in Ihrer Nähe und verbinden Sie sich direkt mit ihnen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Produkte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Holen Sie sich Credits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Keine aktuellen Spenden gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Sie haben die volle Kontrolle über Ihre persönlichen Daten, die Sie teilen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Ihr Konto ist vollständig sicher. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'jetzt');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'Std');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'Jahre');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Aggiungi a Google Calendar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'ricaricare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Trova negozi vicino a te in base alla tua posizione e connettiti direttamente con loro.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Prodotti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Ottieni crediti.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Nessuna donazione recente trovata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Hai il pieno controllo sulle tue informazioni personali che condividi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Il tuo account è completamente sicuro. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'adesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'ore');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'anni');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Adicionar ao Google Agenda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Atualizar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Encontre lojas perto de você com base em sua localização e conecte-se com elas diretamente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Produtos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Obtenha créditos.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Nenhuma doação recente encontrada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Você tem controle total sobre as informações pessoais que compartilha.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Sua conta está totalmente segura. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'agora');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'horas');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'C');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'anos');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Добавить в Календарь Google');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Обновить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Эмодзи');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Находите магазины рядом с вами в зависимости от вашего местоположения и связывайтесь с ними напрямую.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Товары');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Получите кредиты.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Недавних пожертвований не найдено');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Вы полностью контролируете свою личную информацию, которой вы делитесь.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Ваша учетная запись полностью защищена. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'м');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'час');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'сейчас же');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'часы');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'ш');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'лет');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Agregar a Google Calendar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Actualizar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Encuentre tiendas cercanas a usted según su ubicación y conéctese con ellas directamente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Productos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Obtener créditos.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'No se encontraron donaciones recientes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Tienes control total sobre la información personal que compartes.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Tu cuenta está completamente segura. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'metro');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'ahora');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'horas');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'D');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'años');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Google Takvim’e ekle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Yenile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Bulunduğunuz yere göre yakınınızdaki mağazaları bulun ve onlarla doğrudan bağlantı kurun.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Ürün:% s');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Kredi Alın.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'Yakın zamanda bağış bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'Paylaştığınız kişisel bilgileriniz üzerinde tam kontrole sahipsiniz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Hesabınız tamamen güvende. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'şimdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'saat');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'yıl');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Add to Google Calendar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Refresh');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Find shops near to you based on your location and connect with them directly.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Products');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Get Credits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'No recent donations found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'You have full control over your personal information that you share.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Your account is fully secure. We never share your data with third party.');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'now');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'hrs');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'yrs');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'add_google_calendar', 'Add to Google Calendar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'home_refresh', 'Refresh');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_emoji', 'Emoji');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'find_nearby_shops', 'Find shops near to you based on your location and connect with them directly.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'main_products', 'Products');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_credits', 'Get Credits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_recent_donation', 'No recent donations found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_privacy_text', 'You have full control over your personal information that you share.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'welcome_security_text', 'Your account is fully secure. We never share your data with third party.');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_m', 'm');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_h', 'h');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'now', 'now');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_hrs', 'hrs');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_d', 'd');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_w', 'w');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_y', 'y');
            $lang_update_queries[] = Wo_UpdateLangs($value, '_time_yrs', 'yrs');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
    }
    $users = $db->get(T_PAGES);
    if (!empty($users)) {
        foreach ($users as $key => $user) {
            if (empty($user->time)) {
                $array = explode('/', $user->registered);
                $time  = strtotime($array[1] . '/' . $array[0] . '/01');
                if ($array[1] == '0000' || $array[0] == '00') {
                    $time = time();
                }
                $db->where('page_id', $user->page_id)->update(T_PAGES, array(
                    'time' => $time
                ));
            }
        }
    }
    $users = $db->get(T_GROUPS);
    if (!empty($users)) {
        foreach ($users as $key => $user) {
            if (empty($user->time)) {
                $array = explode('/', $user->registered);
                $time  = strtotime($array[1] . '/' . $array[0] . '/01');
                if ($array[1] == '0000' || $array[0] == '00') {
                    $time = time();
                }
                $db->where('id', $user->id)->update(T_GROUPS, array(
                    'time' => $time
                ));
            }
        }
    }
    foreach ($wo['config']['currency_symbol_array'] as $key => $value) {
        if ($key == 'USD') {
            $wo['config']['currency_symbol_array'][$key] = '$';
        }
        if ($key == 'EUR') {
            $wo['config']['currency_symbol_array'][$key] = '€';
        }
        if ($key == 'JPY') {
            $wo['config']['currency_symbol_array'][$key] = '¥';
        }
        if ($key == 'TRY') {
            $wo['config']['currency_symbol_array'][$key] = '₺';
        }
        if ($key == 'GBP') {
            $wo['config']['currency_symbol_array'][$key] = '£';
        }
        if ($key == 'RUB') {
            $wo['config']['currency_symbol_array'][$key] = '₽';
        }
        if ($key == 'PLN') {
            $wo['config']['currency_symbol_array'][$key] = 'zł';
        }
        if ($key == 'ILS') {
            $wo['config']['currency_symbol_array'][$key] = '₪';
        }
        if ($key == 'BRL') {
            $wo['config']['currency_symbol_array'][$key] = 'R$';
        }
        if ($key == 'INR') {
            $wo['config']['currency_symbol_array'][$key] = '₹';
        }
    }
    if (empty($wo['config']['currency_symbol_array']['JPY'])) {
        $wo['config']['currency_symbol_array']['JPY'] = '¥';
    }
    Wo_SaveConfig('currency_symbol_array', json_encode($wo['config']['currency_symbol_array']));
     $node_content = '{
    "sql_db_host": "' . $sql_db_host . '",
    "sql_db_user": "' . $sql_db_user . '",
    "sql_db_pass": "' . $sql_db_pass . '",
    "sql_db_name": "' . $sql_db_name . '",
    "site_url": "' . $site_url . '",
    "purchase_code": "' . $purchase_code . '"
}';
    $node_file_name = './nodejs/config.json';
    $node_file = file_put_contents($node_file_name, $node_content);
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
                     <h2 class="light">Update to v3.1</span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                                <li> [Added] new messaging system powered by NodeJS and websockets, now chat are faster, real time, and smoother. </li>
                                <li> [Added] new admin panel v2, with many options.</li>
                                <li> [Added] new timestamp system, e.g (1 mintue ago -> 1 m, 2 hours ago -> 2 hrs, 1 month ago -> 4 w, 1 year ago -> 1 y)</li>
                                <li> [Added] new notifications for admin panel, now you'll get notificed for any new bank payments, verfication requests and more.</li>
                                <li> [Added] night mode to admin panel. </li>
                                <li> [Improved] security. [Important] </li>
                                <li> [Fixed] 60+ reported bugs.</li>
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
    "UPDATE `Wo_Config` SET `value` = '3.1' WHERE `name` = 'version';",
    "ALTER TABLE `Wo_Users` ADD `code_sent` INT(11) NOT NULL DEFAULT '0' AFTER `paystack_ref`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'node_socket_flow', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'redis', 'N');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'youtube_api_key', '');",
    "ALTER TABLE `Wo_Users` ADD `StripeSessionId` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `code_sent`;",
    "ALTER TABLE `Wo_Notifications` ADD `admin` INT(11) NOT NULL DEFAULT '0' AFTER `sent_push`;",
    "ALTER TABLE `Wo_Notifications` ADD INDEX(`admin`);",
    "ALTER TABLE `Wo_Payments` ADD `time` INT(20) NOT NULL DEFAULT '0' AFTER `date`;",
    "ALTER TABLE `Wo_Pages` ADD `time` INT(20) NOT NULL DEFAULT '0' AFTER `boosted`;",
    "ALTER TABLE `Wo_Groups` ADD `time` INT(20) NOT NULL DEFAULT '0' AFTER `registered`;",
    "ALTER TABLE `Wo_Groups` ADD INDEX(`time`);",
    "ALTER TABLE `Wo_Pages` ADD INDEX(`time`);",
    "CREATE TABLE `wondertage_settings` (  `id` int(11) NOT NULL,  `name` varchar(100) NOT NULL DEFAULT '',  `value` varchar(20000) NOT NULL DEFAULT '') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(1, 'tag_trend', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(2, 'tag_header_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(3, 'tag_welcome_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(4, 'tag_prods_autoload', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(5, 'tag_expand_search', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(6, 'tag_show_side_trend', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(7, 'tag_auto_dark', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(8, 'tag_anron_ico_head', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(9, 'tag_prods_slider', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(10, 'tag_send_comment', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(11, 'tag_profile_donation', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(12, 'tag_profile_qr', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(13, 'tag_prods_cat_slider', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(14, 'tag_wallet_layout', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(15, 'tag_go_pro_layout', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(16, 'tag_show_password', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(17, 'tag_prods_layout', '1');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(18, 'tag_ads_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(19, 'tag_wallet_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(20, 'tag_album_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(21, 'tag_blog_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(22, 'tag_friends_nearby_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(23, 'tag_games_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(24, 'tag_group_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(25, 'tag_home_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(26, 'tag_job_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(27, 'tag_messages_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(28, 'tag_page_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(29, 'tag_search_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(30, 'tag_settings_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(31, 'tag_timeline_layout', '0');",
    "INSERT INTO `wondertage_settings` (`id`, `name`, `value`) VALUES(32, 'tag_extra_opts', '0');",
    "ALTER TABLE `wondertage_settings`  ADD PRIMARY KEY (`id`),  ADD KEY `name` (`name`);",
    "ALTER TABLE `wondertage_settings`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;",
    "COMMIT;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'redis_port', '3080');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'nodejs_port', '3000');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'nodejs_ssl', '0'), (NULL, 'nodejs_key_path', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'nodejs_cert_path', ''), (NULL, 'nodejs_ssl_port', '449');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_google_calendar');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'home_refresh');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat_emoji');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'find_nearby_shops');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'main_products');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get_credits');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_recent_donation');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'welcome_privacy_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'welcome_security_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_m');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_h');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'now');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_hrs');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_d');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_w');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_y');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, '_time_yrs');",

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