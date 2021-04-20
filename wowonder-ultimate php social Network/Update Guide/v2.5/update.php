<?php
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'يبدو أنك فقدت في الفضاء!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'إضافة الأموال');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'إرسال الأموال إلى الأصدقاء');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'عرض التحليلات');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'التالى');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'وسائل الإعلام');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'استهداف');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'اسم الشركة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'عنوان الحملة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'عنوان موقع الويب');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'وصف الحملة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'اختر صورة لحملتك');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'حدد تاريخ بدء الحملة ، UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'حدد تاريخ انتهاء الحملة ، UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'أخبر المستخدمين بماهية حملتك');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'ميزانية الحملة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'أدخل المبلغ الذي تريد إنفاقه على هذه الحملة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'معاينة الإعلان');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'اختر اسم الألبوم الخاص بك');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'تصفح المقالات');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'لم تنشئ أي مقالات حتى الآن.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'إنشاء دردشة جماعية');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'شغله');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'اكتب رسالة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'تحرير طلب التمويل');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'كم من المال تريد الحصول عليه؟');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'تصفح الأحداث');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'وقت البدء');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'وقت النهاية');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'يبدو أن لا أحد أنشأ حدثًا حتى الآن!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'متى سيبدأ هذا الحدث؟');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'متى سينتهي هذا الحدث؟');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'تصفح المنتدى');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'تصفح التمويل');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'منقي');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'صورتك الشخصية');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'ليس لديك حساب؟');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'هل لديك حساب؟');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'تم إرسال المنشور الخاص بك ، سنراجع المحتوى الخاص بك قريبًا.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'تسجيل الخروج من جميع الدورات');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'تمت الموافقة على مشاركتك ونشرها!');
        } else if ($value == 'dutch') {
           $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Het lijkt erop dat je verdwaald bent in de ruimte!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Fondsen toevoegen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Stuur geld naar vrienden');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Bekijk Analytics');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'volgende');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Media');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Gericht op');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Bedrijfsnaam');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Campagnetitel');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Website URL');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Campagnebeschrijving');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Selecteer een afbeelding voor uw campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Selecteer campagne startdatum, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Selecteer einddatum campagne, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Vertel gebruikers waar uw campagne over gaat');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Campagne Budget');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Voer het bedrag in dat u aan deze campagne wilt besteden');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Advertentievoorbeeld');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Kies je albumnaam');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Blader door artikelen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'U heeft nog geen artikelen gemaakt.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Maak een groepschat');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Aanzetten');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Type een bericht');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Financieringsaanvraag bewerken');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Hoeveel geld wilt u ontvangen?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Bladeren door evenementen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Starttijd');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Eindtijd');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Het lijkt erop dat nog niemand een evenement heeft gemaakt!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Wanneer begint dit evenement?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Wanneer eindigt dit evenement?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Browse forum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Browse financiering');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filter');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Je persoonlijke foto');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Heb je nog geen account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Heb je al een account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Je bericht is verzonden. We zullen je inhoud binnenkort beoordelen.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Uitloggen bij alle sessies');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Je bericht is goedgekeurd en gepubliceerd!');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'On dirait que tu es perdu dans l&#39;espace!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Ajouter des fonds');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Envoyer de l&#39;argent à des amis');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Afficher les analyses');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Prochain');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Médias');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Ciblage');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Nom de la compagnie');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Titre de la campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Website URL');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Description de la campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Sélectionnez une image pour votre campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Sélectionnez la date de début de la campagne, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Sélectionnez la date de fin de la campagne, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Expliquez aux utilisateurs en quoi consiste votre campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'budget de campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Saisissez le montant que vous souhaitez dépenser pour cette campagne');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Aperçu de l&#39;annonce');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Choisissez le nom de votre album');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Browse articles');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Vous n&#39;avez pas encore créé d&#39;articles.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Créer un chat en groupe');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Allumer');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Type a message');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Modifier la demande de financement');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Combien d&#39;argent aimeriez-vous recevoir?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Parcourir les événements');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Heure de début');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Heure de fin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Il semble que personne n&#39;a encore créé d&#39;événement!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Quand cet événement va-t-il commencer?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Quand cet événement se terminera-t-il?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Parcourir le forum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Parcourir le financement');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filtre');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Votre photo personnelle');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Vous n&#39;avez pas de compte?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Vous avez déjà un compte?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Votre message a été envoyé, nous examinerons bientôt votre contenu.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Déconnexion de toutes les sessions');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Votre message a été approuvé et publié!');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Sieht aus wie Sie im Weltraum verloren sind!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Geld hinzufügen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Geld an Freunde senden');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Analytics anzeigen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Nächster');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Medien');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Targeting');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Name der Firma');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Kampagnentitel');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Website URL');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Kampagnenbeschreibung');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Wählen Sie ein Bild für Ihre Kampagne aus');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Wählen Sie das Startdatum der Kampagne (UTC)');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Wählen Sie das Enddatum der Kampagne (UTC)');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Teilen Sie den Nutzern mit, worum es in Ihrer Kampagne geht');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Kampagnenbudget');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Geben Sie den Betrag ein, den Sie für diese Kampagne ausgeben möchten');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Anzeigenvorschau');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Wählen Sie Ihren Albumnamen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Artikel durchsuchen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Sie haben noch keine Artikel erstellt.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Erstelle einen Gruppenchat');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Anschalten');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Geben Sie eine Nachricht ein');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Förderantrag bearbeiten');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Wie viel Geld möchten Sie erhalten?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Events durchsuchen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Startzeit');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Endzeit');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Es scheint, dass noch niemand ein Event erstellt hat!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Wann beginnt diese Veranstaltung?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Wann endet diese Veranstaltung?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Forum durchsuchen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Durchsuchen Sie die Finanzierung');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filter');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Dein persönliches Bild');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Sie haben noch keinen Account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Hast du schon ein Konto?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Ihr Beitrag wurde übermittelt. Wir werden Ihren Inhalt in Kürze überprüfen.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Von allen Sitzungen abmelden');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Ihr Beitrag wurde genehmigt und veröffentlicht!');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Sembra che ti sei perso nello spazio!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Aggiungere fondi');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Invia denaro agli amici');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Visualizza Analytics');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Il prossimo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Media');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Targeting');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Nome della ditta');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Titolo della campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'URL del sito Web');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Descrizione della campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Seleziona un&#39;immagine per la tua campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Seleziona la data di inizio della campagna, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Seleziona la data di fine della campagna, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Dì agli utenti di cosa tratta la tua campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Budget della campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Inserisci l&#39;importo che desideri spendere per questa campagna');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Ad preview');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Scegli il nome del tuo album');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Sfoglia gli articoli');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Non hai ancora creato alcun articolo.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Crea una chat di gruppo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Accendere');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Scrivi un messaggio');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Modifica richiesta di finanziamento');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Quanti soldi vorresti ricevere?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Sfoglia gli eventi');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Orario di inizio');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Fine del tempo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Sembra che nessuno abbia ancora creato un evento!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Quando inizierà questo evento?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Quando finirà questo evento?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Sfoglia il forum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Sfoglia finanziamenti');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filtro');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'La tua foto personale');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Non hai un account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Hai già un account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Il tuo post è stato inviato, esamineremo presto i tuoi contenuti.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Esci da tutte le sessioni');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Il tuo post è stato approvato e pubblicato!');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Parece que você está perdido no espaço!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Adicionar fundos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Envie dinheiro para amigos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Ver análise');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Próximo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'meios de comunicação');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Alvejando');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Nome da empresa');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Título da campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'URL do site');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Descrição da campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Selecione uma imagem para sua campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Selecionar data de início da campanha, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Selecionar data de término da campanha, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Informe aos usuários o que é sua campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Orçamento de Campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Digite o valor que você deseja gastar nesta campanha');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Visualização do anúncio');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Escolha o nome do seu álbum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Procurar artigos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Você ainda não criou nenhum artigo.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Crie um chat em grupo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Ligar');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Digite uma mensagem');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Editar solicitação de financiamento');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Quanto dinheiro você gostaria de receber?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Procurar Eventos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Hora de início');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Fim do tempo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Parece que ninguém criou um evento ainda!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Quando este evento começará?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Quando este evento terminará?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Navegar no Fórum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Procurar financiamento');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filtro');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Sua foto pessoal');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Não possui uma conta?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'já tem uma conta?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Sua postagem foi enviada. Analisaremos seu conteúdo em breve.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Sair de todas as sessões');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Sua postagem foi aprovada e publicada!');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Похоже, вы потерялись в космосе!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Добавить средства');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Отправить деньги друзьям');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Просмотр аналитики');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'следующий');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'СМИ');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'нацеливание');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Название компании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Название кампании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'URL сайта');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Описание кампании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Выберите изображение для вашей кампании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Выберите дату начала кампании, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Выберите дату окончания кампании, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Расскажите пользователям о вашей кампании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Бюджет кампании');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Введите сумму, которую вы хотите потратить на эту кампанию');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Предварительный просмотр рекламы');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Выберите название вашего альбома');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Просмотр статей');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Вы еще не создали ни одной статьи.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Создать групповой чат');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Включать');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Введите сообщение');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Изменить заявку на финансирование');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Сколько денег вы хотели бы получить?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Просмотр событий');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Время начала');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Время окончания');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Кажется, что никто еще не создал событие!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Когда это событие начнется?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Когда это событие закончится?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Обзор форума');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Просмотр финансирования');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Фильтр');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Ваша личная фотография');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'У вас нет аккаунта?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Уже есть аккаунт?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Ваше сообщение отправлено, мы скоро рассмотрим ваш контент.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Выйти из всех сессий');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Ваш пост был одобрен и опубликован!');
        } else if ($value == 'spanish') {
           $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', '¡Parece que estás perdido en el espacio!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Añadir fondos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Envía dinero a amigos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Ver análisis');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'próximo');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Media');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Orientación');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Nombre de empresa');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Título de la campaña');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'URL del sitio web');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Descripción de la campaña.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Seleccione una imagen para su campaña');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Seleccione la fecha de inicio de la campaña, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Seleccione la fecha de finalización de la campaña, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Informe a los usuarios de qué trata su campaña');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Presupuesto Campaña');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Ingrese el monto que desea gastar en esta campaña');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Vista previa del anuncio');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Elige el nombre de tu álbum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Examinar artículos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Aún no has creado ningún artículo.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Crea un chat grupal');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Encender');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Escribe un mensaje');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Editar solicitud de financiación');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', '¿Cuánto dinero te gustaría recibir?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Examinar eventos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Hora de inicio');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Hora de finalización');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', '¡Parece que nadie creó un evento todavía!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', '¿Cuándo comenzará este evento?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', '¿Cuándo terminará este evento?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Examinar el foro');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Examinar Financiamiento');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filtrar');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Tu foto personal');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', '¿No tienes una cuenta?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', '¿Ya tienes una cuenta?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Su publicación fue enviada, revisaremos su contenido pronto.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Cerrar sesión de todas las sesiones');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', '¡Tu publicación fue aprobada y publicada!');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Uzayda kaybolmuş gibisin!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Fon ekle');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Arkadaşlarına para gönder');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'Analytics’i Görüntüle');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Sonraki');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'medya');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Hedefleme');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Şirket Adı');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Kampanya başlığı');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Web sitesi URL&#39;si');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Kampanya açıklaması');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Kampanyanız için bir resim seçin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Kampanya başlangıç ​​tarihini seçin, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Kampanya bitiş tarihini seçin, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Kullanıcılara kampanyanızın ne hakkında olduğunu söyleyin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Kampanya Bütçesi');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Bu kampanyada harcamak istediğiniz tutarı girin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Reklam önizlemesi');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Albüm adınızı seçin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Makalelere göz at');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'Henüz herhangi bir makale yazmadınız.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Grup sohbeti oluştur');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Aç');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Bir mesaj yazın');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Finansman talebini düzenle');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'Ne kadar para almak istersin?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Etkinliklere Göz At');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Başlama zamanı');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'Bitiş zamanı');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'Henüz kimse bir etkinlik oluşturmadı!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'Bu etkinlik ne zaman başlayacak?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'Bu etkinlik ne zaman bitecek?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Foruma Göz At');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Finansmana Göz Atın');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'filtre');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Kişisel resmin');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Hesabınız yok mu?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Zaten hesabınız var mı?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Gönderiniz gönderildi, içeriğinizi yakında inceleyeceğiz.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Tüm Oturumlardan Çıkış Yap');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Gönderiniz onaylandı ve yayınlandı!');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Looks like you\'re lost in space!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Add Funds');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Send money to friends');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'View Analytics');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Next');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Media');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Targeting');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Company name');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Campaign title');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Website URL');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Campaign description');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Select a image for your campaign');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Select campaign starting date, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Select campaign ending date, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Tell users what your campaign is about');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Campaign Budget');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Enter the amount you want to spend on this campaign');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Ad preview');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Choose your album name');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Browse articles');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'You haven\'t created any articles yet.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Create a group chat');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Turn On');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Type a message');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Edit funding request');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'How much money you would like to receive?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Browse Events');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Start time');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'End time');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'It seems like no one created an event yet!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'When this event will start?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'When this event will end?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Browse Forum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Browse Funding');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filter');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Your personal picture');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Don\'t have an account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Already have an account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Your post was submitted, we will review your content soon.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Logout From All Sessions');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Your post was approved and published!');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'lost_in_space', 'Looks like you\'re lost in space!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'add_funds', 'Add Funds');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'send_money_friends', 'Send money to friends');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_analytics', 'View Analytics');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'next', 'Next');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_media', 'Media');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'targeting', 'Targeting');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'comp_name', 'Company name');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_title', 'Campaign title');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'website_url', 'Website URL');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_desc', 'Campaign description');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_img_help', 'Select a image for your campaign');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_start_date_help', 'Select campaign starting date, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_end_date_help', 'Select campaign ending date, UTC');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_desc_help', 'Tell users what your campaign is about');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget', 'Campaign Budget');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'camp_budget_help', 'Enter the amount you want to spend on this campaign');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'ad_preview', 'Ad preview');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'album_name_help', 'Choose your album name');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_articles', 'Browse articles');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_blogs_created', 'You haven\'t created any articles yet.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group_chat', 'Create a group chat');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'turn_on', 'Turn On');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'type_message', 'Type a message');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_funding', 'Edit funding request');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_amount', 'How much money you would like to receive?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_events', 'Browse Events');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'start_time', 'Start time');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'end_time', 'End time');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'no_one_created_event', 'It seems like no one created an event yet!');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_start', 'When this event will start?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'event_end', 'When this event will end?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_forum', 'Browse Forum');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'browse_funding', 'Browse Funding');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'filter', 'Filter');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'personal_pic', 'Your personal picture');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'dont_have_account', 'Don\'t have an account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'already_have_account', 'Already have an account?');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post_text', 'Your post was submitted, we will review your content soon.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_all_sessions', 'Logout From All Sessions');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_post', 'Your post was approved and published!');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
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
                     <h2 class="light">Update to v2.5 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                           <li> [Added] the ability logout from all sessions.</li>
                                <li> [Added] the ability to approve or decline a post, enable / disable.</li>
                                <li> [Added] new APIs. </li>
                                <li> [Added] auto like for pages and auto join for groups. </li>
                                <li> [Updated] PHP libaries.</li>
                                <li> [Updated] Google login API.</li>
                                <li> [Improved] default theme desgin.</li>
                                <li> [Improved] speed.</li>
                                <li> [Improved] english in some parts.</li>
                                <li> [Fixed] 100+ reported bugs and issues.</li>
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
    "UPDATE `Wo_Config` SET `value` = '2.5' WHERE `name` = 'version';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'post_approval', '0');",
    "ALTER TABLE `Wo_Posts` ADD `active` INT(11) NOT NULL DEFAULT '1' AFTER `fund_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'auto_page_like', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'auto_group_join', '');",
    "ALTER TABLE `Wo_UserAds` ADD `page_id` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';",
    "ALTER TABLE `Wo_UserAds` ADD `start` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `page_id`, ADD `end` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `start`;",
    "ALTER TABLE `Wo_UserAds` CHANGE `start` `start` DATE NOT NULL;",
    "ALTER TABLE `Wo_UserAds` CHANGE `end` `end` DATE NOT NULL;",
    "ALTER TABLE `Wo_UserAds` ADD `budget` FLOAT(11) NOT NULL DEFAULT '0' AFTER `end`;",
    "ALTER TABLE `Wo_UserAds` ADD `spent` FLOAT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `budget`;",
    "ALTER TABLE `Wo_UserAds` CHANGE `budget` `budget` FLOAT UNSIGNED NOT NULL DEFAULT '0';",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'lost_in_space');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_funds');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'send_money_friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'view_analytics');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'next');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_media');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'targeting');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'comp_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'camp_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'website_url');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'camp_desc');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_img_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_start_date_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_end_date_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_desc_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'camp_budget');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'camp_budget_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ad_preview');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'album_name_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'browse_articles');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_blogs_created');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_group_chat');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'turn_on');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'type_message');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_funding');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'fund_amount');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'browse_events');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'start_time');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'end_time');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_one_created_event');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_start');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_end');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'browse_forum');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'browse_funding');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'filter');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'personal_pic');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'dont_have_account');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'already_have_account');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'approve_post_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'remove_all_sessions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'approve_post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'approve_post');",
    "UPDATE Wo_Langs SET `english` = 'Current balance' WHERE lang_key = 'my_balance';",
    "UPDATE Wo_Langs SET `english` = 'To who you want to send?' WHERE lang_key = 'send_to';",
    "UPDATE Wo_Langs SET `english` = 'Search by username or email' WHERE lang_key = 'search_name_or_email';",
    "UPDATE Wo_Langs SET `english` = 'No ads found. Create new ad and start getting traffic!' WHERE lang_key = 'no_ads_found';",
    "UPDATE Wo_Langs SET `english` = 'Wallet & Credits' WHERE lang_key = 'my_wallet';",
    "UPDATE Wo_Langs SET `english` = 'New campaign' WHERE lang_key = 'create_new_ads';",
    "UPDATE Wo_Langs SET `english` = 'Looks like you don&#39;t have any transaction yet!' WHERE lang_key = 'no_transactions_found';",
    "UPDATE Wo_Langs SET `english` = 'Campaigns' WHERE lang_key = 'my_campaigns';",
    "UPDATE Wo_Langs SET `english` = 'Are you sure that you want to delete this campaign? This action can&#39;t be undo.' WHERE lang_key = 'confirm_delete_ad';",
    "UPDATE Wo_Langs SET `english` = 'Delete campaign' WHERE lang_key = 'delete_ad';",
    "UPDATE Wo_Langs SET `english` = 'Edit campaign' WHERE lang_key = 'edit_ads';",
    "UPDATE Wo_Langs SET `english` = 'Analytics' WHERE lang_key = 'stats';",
    "UPDATE Wo_Langs SET `english` = 'You haven&#39;t created any albums yet.' WHERE lang_key = 'no_albums_found';",
    "UPDATE Wo_Langs SET `english` = 'Search for articles' WHERE lang_key = 'search_for_article';",
    "UPDATE Wo_Langs SET `english` = 'You are currently offline, turn on the chat to start chatting.' WHERE lang_key = 'you_are_currently_offline';",
    "UPDATE Wo_Langs SET `english` = 'Create new event' WHERE lang_key = 'create_events';",
    "UPDATE Wo_Langs SET `english` = 'No result to show' WHERE lang_key = 'no_result';",
    "UPDATE Wo_Langs SET `english` = 'No posts to show' WHERE lang_key = 'no_posts_found';",
    "UPDATE Wo_Langs SET `english` = 'No users to show' WHERE lang_key = 'no_users_found';",
    "UPDATE Wo_Langs SET `english` = 'No pages to show' WHERE lang_key = 'no_pages_found';",
    "UPDATE Wo_Langs SET `english` = 'No groups to show' WHERE lang_key = 'no_groups_found';",
    "UPDATE Wo_Langs SET `english` = 'No requests to show' WHERE lang_key = 'no_requests_found';",
    "UPDATE Wo_Langs SET `english` = 'No members to show' WHERE lang_key = 'no_members_found';",
    "UPDATE Wo_Langs SET `english` = 'No games to show' WHERE lang_key = 'no_games_found';",
    "UPDATE Wo_Langs SET `english` = 'No forums to show' WHERE lang_key = 'no_forums_found';",
    "UPDATE Wo_Langs SET `english` = 'No replies to show' WHERE lang_key = 'no_replies_found';",
    "UPDATE Wo_Langs SET `english` = 'No threads to show' WHERE lang_key = 'no_threads_found';",
    "UPDATE Wo_Langs SET `english` = 'No events to show' WHERE lang_key = 'no_events_found';",
    "UPDATE Wo_Langs SET `english` = 'No sections to show' WHERE lang_key = 'no_sections_found';",
    "UPDATE Wo_Langs SET `english` = 'No movies to show' WHERE lang_key = 'no_movies_found';",
    "UPDATE Wo_Langs SET `english` = 'No pokes to show' WHERE lang_key = 'no_pokes_found';",
    "UPDATE Wo_Langs SET `english` = 'No mutual friends to show' WHERE lang_key = 'no_mutual_friends';",
    "UPDATE Wo_Langs SET `english` = 'No comments to show' WHERE lang_key = 'no_comments_found';",
    "UPDATE Wo_Langs SET `english` = 'No funding to show' WHERE lang_key = 'no_funding_found';",
    "UPDATE Wo_Langs SET `english` = 'My Threads' WHERE lang_key = 'my_threads';",
    "UPDATE Wo_Langs SET `english` = 'No posts to show' WHERE lang_key = 'no_one_posted';",
    "UPDATE Wo_Langs SET `english` = 'changed his profile picture' WHERE lang_key = 'changed_profile_picture_male';",
    "UPDATE Wo_Langs SET `english` = 'changed her profile picture' WHERE lang_key = 'changed_profile_picture_female';",
    "UPDATE Wo_Langs SET `english` = 'changed his profile cover' WHERE lang_key = 'changed_profile_cover_picture_male';",
    "UPDATE Wo_Langs SET `english` = 'changed her profile cover' WHERE lang_key = 'changed_profile_cover_picture_female';",
    "UPDATE Wo_Langs SET `english` = 'created a new article' WHERE lang_key = 'created_new_blog';",
    "UPDATE Wo_Langs SET `english` = 'Copy of your passport or ID card' WHERE lang_key = 'passport_id';",

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
            }, 100);
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