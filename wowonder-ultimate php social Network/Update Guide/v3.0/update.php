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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'ذكريات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'في هذا اليوم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'ليس لديك أي ذكريات في هذا اليوم.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'لديك ذكرى في هذا اليوم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'تحليلات الصفحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'إجمالي الإعجابات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'اليوم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'هذا الشهر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'هذا العام');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'تحليلات المجموعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'مجموع الأعضاء');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'رد على موضوعك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'شارك على الجدول الزمني الخاص بي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'شارك منتدى');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'تمت إضافة مشاركات المنتدى إلى الجدول الزمني الخاص بك بنجاح!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'تمت إضافة الموضوع بنجاح إلى مخططك الزمني!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'شارك موضوع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'تصنيف فرعي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', '{{time}} المتبقية لعضويتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'لتحميل الصور ومقاطع الفيديو والملفات الصوتية ، يجب الترقية إلى عضو محترف.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'لتحميل الصور ومقاطع الفيديو والملفات الصوتية ، يجب الترقية إلى عضو محترف.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'تمت الموافقة على مدونتك ونشرها!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'إعادة مال');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'صفحة استرداد الأموال');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'السبب');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'سنراجع طلبك خلال 2 - 3 أيام عمل.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'عفوًا ، أنت لست مشتركًا ، لا يمكنك طلب استرداد الأموال.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'يرجى تحديد عضويتك الصحيحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'طلبك قيد المراجعة ، وسوف نخطرك بمجرد ذلك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'تم رفض طلب استرداد الأموال الخاص بك!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'تمت الموافقة على طلب استرداد الأموال الخاص بك! يرجى التحقق من رصيدك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'نقدي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'عرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'إنشاء عرض جديد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'انشر عرضًا لـ {{page_name}} في');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'نوع العرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'نسبة الخصم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'مقدار الخصم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'اشترِ X احصل على خصم Y');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'أنفق X احصل على Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'الشحن مجانا');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'احصل على');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'أنفق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'المبلغ خارج');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'تم إنشاء العرض بنجاح.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'تم إنشاء عرض جديد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'عناصر و / أو خدمات مخفضة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'أضف عناصر أو خدمات لهذا العرض بحد أقصى 100 حرف');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'يجب أن تكون العناصر المخصومة أقل من 100 حرف');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'عروض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'تحميل المزيد من العروض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'لا توجد عروض متاحة للعرض.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'حذف العرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'هل أنت متأكد أنك تريد حذف هذا العرض؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'تعديل العرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'تم تحديث العرض بنجاح.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'المتاجر القريبة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'وجدت المتاجر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'لم يتم العثور على متاجر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'الأعمال القريبة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'تم العثور على الأعمال');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'لم يتم العثور على عمل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'الكثير من محاولات تسجيل الدخول يرجى المحاولة مرة أخرى في وقت لاحق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'لا تفي كلمة المرور المقدمة بالحد الأدنى من متطلبات التعقيد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'يجب ألا يقل طول الأحرف عن 6 أحرف.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'يجب أن يحتوي على حرف صغير.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'يجب أن يحتوي على حرف كبير.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'يجب أن يحتوي على رقم أو حرف خاص.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'يمكن للمستخدمين النشر على صفحتي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'امتيازات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'الوصول إلى الإعدادات العامة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'الوصول إلى إعدادات معلومات الصفحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'الوصول إلى إعدادات الروابط الاجتماعية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'الوصول إلى الصورة الرمزية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'الوصول إلى إعدادات التصميم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'الوصول إلى إعدادات المسؤولين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'الوصول إلى إعدادات التحليلات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'الوصول لحذف إعدادات الصفحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'الوصول إلى إعدادات الخصوصية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'الوصول إلى إعدادات الأعضاء');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'الوصول إلى حذف إعدادات المجموعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'روابط الدعوة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'الروابط المتاحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'الروابط المولدة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'روابط مستعملة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'إنشاء روابط');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'تم إنشاء الرمز بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'نسخ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'نسخ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'المستخدم المدعو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'غير محدود');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'مجهول');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'رقم الحساب بصيغة IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'الاسم الكامل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'رمز السرعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'تمت الموافقة على طلب السحب الخاص بك!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'تم رفض طلب السحب الخاص بك!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'سجل وادفع باستخدام');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'حي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'انطلق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'بدأ فيديو مباشر.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'مسح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'غير متابع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'طريقة السحب');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'بنك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'نهاية العيش');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'احصل على إشعار عندما ينشئ {USER} مشاركة جديدة.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'التوقف عن تلقي إشعارات من {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'أنشأ مشاركة جديدة.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'لقد مر {TIME} لأنكما صديقان! أرسل لهم رسالة للاحتفال.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'طلب استرداد الأموال');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'يعيش الآن.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'كان يعيش.');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Herinneringen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'Op deze dag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Je hebt op deze dag geen herinneringen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Vriendschap');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Je hebt op deze dag een herinnering');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Pagina-analyse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Totaal aantal likes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Vandaag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Deze maand');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Dit jaar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Groepsanalyse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Totaal aantal leden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'antwoordde op uw thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Deel op mijn tijdlijn');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'heeft een forum gedeeld');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Forumberichten zijn met succes toegevoegd aan uw tijdlijn!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Discussie is met succes toegevoegd aan je tijdlijn!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'deelde een thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Subcategorie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Resterende {{tijd}} voor uw lidmaatschap');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Om afbeeldingen, video&#39;s en audiobestanden te uploaden, moet je upgraden naar pro-lid.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Om afbeeldingen, video&#39;s en audiobestanden te uploaden, moet je upgraden naar pro-lid.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Je blog is goedgekeurd en gepubliceerd!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Terugbetaling');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Pagina terugbetalen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Reden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'We beoordelen uw verzoek binnen twee tot drie werkdagen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Oeps, u bent geen abonnee, u kunt geen restitutie aanvragen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Selecteer uw juiste lidmaatschap');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Uw verzoek wordt beoordeeld, we zullen u hiervan op de hoogte stellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Uw teruggaveverzoek is afgewezen!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Uw teruggaveverzoek is goedgekeurd! controleer uw saldo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Geldvrij');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Aanbod');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Maak een nieuwe aanbieding');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Plaats een aanbieding voor {{page_name}} op');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Aanbiedingstype');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Kortingspercentage');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Korting hoeveelheid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Koop X krijg Y korting');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Besteed X Krijg Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Gratis verzending');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Krijgen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Besteden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Bedrag uit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Aanbieding succesvol gemaakt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Nieuwe aanbieding gemaakt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Afgeprijsde artikelen en / of services');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Voeg items of services toe aan deze aanbieding Max. 100 tekens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Items met korting moeten minder dan 100 tekens bevatten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Aanbiedingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Laad meer aanbiedingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Geen beschikbare aanbiedingen om weer te geven.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Aanbieding verwijderen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Weet u zeker dat u deze aanbieding wilt verwijderen?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Aanbieding bewerken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Aanbieding succesvol bijgewerkt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Nabijgelegen winkels');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Winkels gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Geen winkels gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Nabijgelegen bedrijf');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Bedrijf gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Geen bedrijf gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Te veel inlogpogingen, probeer het later opnieuw');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'Het opgegeven wachtwoord voldoet niet aan de minimale complexiteitsvereisten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Moet minimaal 6 tekens lang zijn.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Moet een kleine letter bevatten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Moet een hoofdletter bevatten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Moet een cijfer of een speciaal teken bevatten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Gebruikers kunnen op mijn pagina posten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Voorrechten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Toegang tot algemene instellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Toegang tot instellingen voor pagina-informatie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Toegang tot instellingen voor sociale links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Toegang tot avatar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Toegang tot ontwerpinstellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Toegang tot beheerdersinstellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Toegang tot analyse-instellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Toegang om pagina-instellingen te verwijderen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Toegang tot privacy-instellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Toegang tot ledeninstellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Toegang om groepsinstellingen te verwijderen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Invitation Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Beschikbare links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Gegenereerde links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Gebruikte links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Generate links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Code succesvol gegenereerd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Gekopieerd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Kopiëren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Uitgenodigde gebruiker');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Onbeperkt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anoniem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Voor-en achternaam');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Swift code');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Uw intrekkingsverzoek is goedgekeurd!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Uw verzoek tot intrekking is afgewezen!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Registreer en betaal met');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Leven');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Ga leven');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'begon een live video.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Scannen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'ontvolgen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Intrekkingsmethode');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Bank');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Live beëindigen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Ontvang een melding wanneer {USER} een nieuw bericht maakt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Geen meldingen meer ontvangen van {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'heeft een nieuw bericht gemaakt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'Het is {TIME} sinds jullie allebei vrienden zijn! Stuur ze een bericht om het te vieren.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Verzoek om terugbetaling');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'is nu live.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'was live.');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Souvenirs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'Ce jour-là');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Vous n&#39;avez aucun souvenir ce jour-là.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Vous vous souvenez de ce jour');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Page Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Total J&#39;aime');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Aujourd&#39;hui');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Ce mois-ci');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Cette année');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Analytique de groupe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Total des membres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'répondu à votre fil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Partager sur ma chronologie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'shared a forum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Les messages du forum ont été ajoutés avec succès à votre chronologie!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Le fil a été ajouté avec succès à votre chronologie!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'a partagé un fil de discussion');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Sous-catégorie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Reste {{time}} pour votre adhésion');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Pour télécharger des images, des vidéos et des fichiers audio, vous devez passer à un membre pro.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Pour télécharger des images, des vidéos et des fichiers audio, vous devez passer à un membre pro.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Votre blog a été approuvé et publié!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Rembourser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Page de remboursement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Raison');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Nous examinerons votre demande dans un délai de 2 à 3 jours ouvrables.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Oups, vous n&#39;êtes pas abonné, vous ne pouvez pas demander de remboursement.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Veuillez sélectionner votre adhésion correcte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Votre demande est en cours d&#39;examen, nous vous en informerons une fois');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Votre demande de remboursement a été refusée!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Votre demande de remboursement a été approuvée! veuillez vérifier votre solde.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Créer une nouvelle offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Publiez une offre pour {{page_name}} sur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Type d&#39;offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Pourcentage de remise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Montant de la remise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Acheter X Get Y Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Passer X descendre Y');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Livraison gratuite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Avoir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Dépenser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Montant hors');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Offre créée avec succès.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Création d&#39;une nouvelle offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Articles et / ou services à prix réduit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Ajouter des articles ou des services à cette offre 100 caractères max.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Les articles à prix réduits doivent contenir moins de 100 caractères');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Des offres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Charger plus d&#39;offres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Aucune offre disponible à afficher.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Supprimer l&#39;offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Êtes-vous sûr de vouloir supprimer cette offre?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Modifier l&#39;offre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Offre mise à jour avec succès.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Commerces à proximité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Magasins trouvés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Aucun magasin trouvé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Entreprise à proximité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Entreprise trouvée');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Aucune entreprise trouvée');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Trop de tentatives de connexion, veuillez réessayer plus tard');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'Le mot de passe fourni ne répond pas aux exigences minimales de complexité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Doit contenir au moins 6 caractères.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Doit contenir une lettre minuscule.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Doit contenir une lettre majuscule.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Doit contenir un chiffre ou un caractère spécial.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Les utilisateurs peuvent poster sur ma page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privilèges');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Accès aux paramètres généraux');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Accès aux paramètres des informations de la page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Accès aux paramètres des liens sociaux');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Accès à l&#39;avatar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Accès aux paramètres de conception');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Accès aux paramètres des administrateurs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Accès aux paramètres d&#39;analyse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Accès pour supprimer les paramètres de page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Accès aux paramètres de confidentialité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Accès aux paramètres des membres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Accès pour supprimer les paramètres du groupe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Invitation Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Liens disponibles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Liens générés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Liens utilisés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Générer des liens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Code généré avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copié');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Copie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Utilisateur invité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Illimité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonyme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Nom complet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Code rapide');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Votre demande de retrait a été approuvée!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Votre demande de retrait a été refusée!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Inscrivez-vous et payez en utilisant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Vivre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Go Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'a commencé une vidéo en direct.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Numériser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'ne pas suivre');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Méthode de retrait');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Banque');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Fin en direct');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Recevez une notification lorsque {USER} crée un nouveau message.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Ne plus recevoir de notifications de {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'a créé une nouvelle publication.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'Ça fait {TIME} puisque vous êtes tous les deux amis! Envoyez-leur un message pour célébrer.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Demande de remboursement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'est en direct maintenant.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'était en direct.');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Erinnerungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'An diesem Tage');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Sie haben an diesem Tag keine Erinnerungen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Sie haben Erinnerung an diesen Tag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Seitenanalyse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Total Likes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Heute');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Diesen Monat');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Dieses Jahr');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Gruppenanalyse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Mitglieder insgesamt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'hat auf deinen Thread geantwortet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Teile auf meiner Timeline');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'hat ein Forum geteilt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Forenbeiträge wurden erfolgreich zu Ihrer Timeline hinzugefügt!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Thread wurde erfolgreich zu Ihrer Timeline hinzugefügt!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'hat einen Thread geteilt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Unterkategorie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Verbleibende {{Zeit}} für Ihre Mitgliedschaft');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Um Bilder, Videos und Audiodateien hochzuladen, müssen Sie ein Upgrade auf Pro Member durchführen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Um Bilder, Videos und Audiodateien hochzuladen, müssen Sie ein Upgrade auf Pro Member durchführen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Ihr Blog wurde genehmigt und veröffentlicht!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Rückerstattung');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Rückerstattungsseite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Grund');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Wir werden Ihre Anfrage innerhalb von 2 - 3 Werktagen prüfen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Hoppla, Sie sind kein Abonnent, Sie können keine Rückerstattung beantragen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Bitte wählen Sie Ihre korrekte Mitgliedschaft');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Ihre Anfrage wird geprüft, wir werden Sie benachrichtigen, sobald sie vorliegt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Ihr Rückerstattungsantrag wurde abgelehnt!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Ihr Rückerstattungsantrag wurde genehmigt! Bitte überprüfen Sie Ihr Guthaben.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Bargeldlos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Angebot');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Neues Angebot erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Veröffentlichen Sie ein Angebot für {{Seitenname}} am');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Angebotsart');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Rabatt Prozent');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Rabattbetrag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Kaufen Sie X Get Y Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Verbringen Sie X Get Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Kostenloser Versand');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Bekommen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Verbringen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Betrag aus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Angebot erfolgreich erstellt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Neues Angebot erstellt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Ermäßigte Artikel und / oder Dienstleistungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Fügen Sie diesem Angebot Artikel oder Dienstleistungen hinzu. Maximal 100 Zeichen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Ermäßigte Artikel müssen weniger als 100 Zeichen enthalten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Bietet an');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Laden Sie weitere Angebote');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Keine verfügbaren Angebote zu zeigen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Angebot löschen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Sind Sie sicher, dass Sie dieses Angebot löschen möchten?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Angebot bearbeiten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Angebot erfolgreich aktualisiert.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'In der Nähe Geschäfte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Geschäfte gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Keine Geschäfte gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'In der Nähe Geschäft');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Geschäft gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Kein Geschäft gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Zu viele Anmeldeversuche versuchen Sie es später erneut');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'Das angegebene Passwort entspricht nicht den Mindestanforderungen an die Komplexität');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Muss mindestens 6 Zeichen lang sein.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Muss einen Kleinbuchstaben enthalten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Muss einen Großbuchstaben enthalten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Muss eine Zahl oder ein Sonderzeichen enthalten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Benutzer können auf meiner Seite posten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privilegien');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Zugriff auf allgemeine Einstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Zugriff auf Einstellungen für Seiteninformationen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Zugriff auf Einstellungen für soziale Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Zugang zum Avatar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Zugriff auf Designeinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Zugriff auf Administratoreinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Zugriff auf Analyseeinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Zugriff zum Löschen von Seiteneinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Zugriff auf Datenschutzeinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Zugriff auf Mitgliedereinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Zugriff zum Löschen von Gruppeneinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Invitation Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Verfügbare Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Generierte Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Verwendete Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Links generieren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Code erfolgreich generiert');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Kopiert');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Kopieren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Eingeladener Benutzer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Unbegrenzt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonym');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Vollständiger Name');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'SWIFT-Code');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Ihre Rücktrittsanfrage wurde genehmigt!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Ihre Auszahlungsanfrage wurde abgelehnt!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Registrieren und bezahlen mit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Wohnen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Geh Leben');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'startete ein Live-Video.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Scannen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'nicht mehr folgen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Methode zurückziehen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Bank');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Live beenden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Erhalten Sie eine Benachrichtigung, wenn {USER} einen neuen Beitrag erstellt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Keine Benachrichtigungen mehr von {USER} erhalten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'hat einen neuen Beitrag erstellt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'Es ist {ZEIT} her, seit ihr beide Freunde seid! Senden Sie ihnen eine Nachricht zum Feiern.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Geld zurück verlangen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'ist jetzt live.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'war live.');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Ricordi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'In questo giorno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Non hai ricordi in questo giorno.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Hai un ricordo in questo giorno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Analisi della pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Mi Piace totali');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Oggi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Questo mese');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Quest&#39;anno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Analisi di gruppo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Membri totali');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'rispose alla tua discussione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Condividi sulla mia cronologia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'ha condiviso un forum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'I post del forum sono stati aggiunti correttamente alla tua sequenza temporale!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'La discussione è stata aggiunta correttamente alla tua cronologia!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'ha condiviso una discussione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Sottocategoria');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Restante {{time}} per la tua iscrizione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Per caricare immagini, video e file audio, devi effettuare l&#39;upgrade a un membro professionista.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Per caricare immagini, video e file audio, devi effettuare l&#39;upgrade a un membro professionista.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Il tuo blog è stato approvato e pubblicato!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Rimborso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Pagina di rimborso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Motivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Esamineremo la tua richiesta entro 2-3 giorni lavorativi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Spiacenti, non sei un abbonato, non puoi richiedere il rimborso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Seleziona la tua iscrizione corretta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'La tua richiesta è in fase di revisione, ti informeremo una volta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'La tua richiesta di rimborso è stata rifiutata!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'La tua richiesta di rimborso è stata approvata! controlla il tuo saldo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Offrire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Crea nuova offerta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Pubblica un&#39;offerta per {{page_name}} su');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Tipo di offerta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Percentuale di sconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Totale sconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Acquista X Ottieni Y Sconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Spendi X Ottieni Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Spedizione gratuita');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Ottenere');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Trascorrere');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Importo Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Offerta creata con successo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Nuova offerta creata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Articoli e / o servizi scontati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Aggiungi articoli o servizi a questa offerta Max 100 caratteri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Gli articoli scontati devono contenere meno di 100 caratteri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'offerte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Carica più offerte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Nessuna offerta disponibile da mostrare.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Elimina offerta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Sei sicuro di voler eliminare questa offerta?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Modifica offerta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Offerta aggiornata con successo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Negozi nelle vicinanze');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Negozi trovati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Nessun negozio trovato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Attività nelle vicinanze');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Attività trovate');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Nessuna attività trovata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Troppi tentativi di accesso, riprovare più tardi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'La password fornita non soddisfa i requisiti minimi di complessità');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Deve contenere almeno 6 caratteri.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Deve contenere una lettera minuscola.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Deve contenere una lettera maiuscola.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Deve contenere un numero o un carattere speciale.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Gli utenti possono pubblicare sulla mia pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'privilegi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Accesso alle impostazioni generali');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Accesso alle impostazioni delle informazioni sulla pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Accesso alle impostazioni dei social network');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Access to avatar ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Accesso alle impostazioni di progettazione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Accesso alle impostazioni degli amministratori');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Accesso alle impostazioni di analisi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Accesso per eliminare le impostazioni della pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Accesso alle impostazioni sulla privacy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Accesso alle impostazioni dei membri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Accesso per eliminare le impostazioni del gruppo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Link all&#39;invito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Link disponibili');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Link generati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Link usati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Genera collegamenti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Codice generato correttamente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copiato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'copia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Utente invitato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Illimitato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonimo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Nome e cognome');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Codice SWIFT');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'La tua richiesta di prelievo è stata approvata!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'La tua richiesta di prelievo è stata rifiutata!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Registrati e paga usando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Vivere');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Andare in diretta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'ha iniziato un video dal vivo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Paysera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'Smetti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Metodo di prelievo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Banca');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Termina dal vivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Ricevi una notifica quando {USER} crea un nuovo post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Non ricevere più notifiche da {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'creato un nuovo post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'È stato {TIME} da quando entrambi siete amici! Invia loro un messaggio per festeggiare.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Richiesta di rimborso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'è in diretta ora.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'era vivo.');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Recordações');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'Neste dia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Você não tem lembranças neste dia.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Você tem lembrança neste dia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Page Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Total de curtidas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Hoje');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Este mês');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Este ano');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Análise de grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Total de membros');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'respondeu ao seu tópico');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Compartilhar na minha linha do tempo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'compartilhou um fórum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'As postagens do fórum foram adicionadas com sucesso à sua linha do tempo!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'O tópico foi adicionado com sucesso à sua linha do tempo!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'compartilhou um tópico');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Subcategoria');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Restante {{time}} para sua associação');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Para fazer upload de imagens, vídeos e arquivos de áudio, é necessário atualizar para o membro profissional.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Para fazer upload de imagens, vídeos e arquivos de áudio, é necessário atualizar para o membro profissional.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Seu blog foi aprovado e publicado!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Reembolso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Página de reembolso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Razão');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Analisaremos sua solicitação dentro de 2 a 3 dias úteis.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Ops, você não é assinante, não pode solicitar reembolso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Selecione sua associação correta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'O seu pedido está em revisão, iremos notificá-lo assim que');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'O seu pedido de reembolso foi recusado!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'O seu pedido de reembolso foi aprovado! por favor, verifique seu saldo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Criar nova oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Poste uma oferta para {{page_name}} no');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Tipo de oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Porcentagem de desconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Valor do desconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Compre o desconto X Get Y');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Gaste X e tire Y');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Envio Grátis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Obter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Gastar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Valor desativado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Oferta criada com sucesso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Nova oferta criada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Itens com desconto e / ou serviços');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Adicione itens ou serviços a esta oferta Máximo de 100 caracteres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Itens com desconto devem ter menos de 100 caracteres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Ofertas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Carregar mais ofertas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Não há ofertas disponíveis para mostrar.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Excluir oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Tem certeza de que deseja excluir esta oferta?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Editar oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Oferta atualizada com sucesso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Lojas próximas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Lojas encontradas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Nenhuma loja encontrada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Negócios nas proximidades');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Negócio encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Nenhuma empresa encontrada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Muitas tentativas de login, tente novamente mais tarde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'A senha fornecida não atende aos requisitos mínimos de complexidade');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Deve ter pelo menos 6 caracteres.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Deve conter uma letra minúscula.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Deve conter uma letra maiúscula.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Deve conter um número ou caractere especial.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Os usuários podem postar na minha página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privilégios');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Acesso a configurações gerais');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Acesso às configurações de informações da página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Acesso às configurações de links sociais');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Acesso ao avatar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Acesso às configurações de design');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Acesso às configurações de administrador');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Acesso às configurações de análise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Acesso para excluir configurações da página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Acesso a configurações de privacidade');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Acesso às configurações de membros');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Acesso para excluir configurações de grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Links para convites');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Links Disponíveis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Links gerados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Links Usados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Gere links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Código gerado com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copiado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'cópia de');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Usuário Convidado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Ilimitado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anônimo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Nome completo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Código Swift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Sua solicitação de retirada foi aprovada!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Sua solicitação de retirada foi recusada!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Registre-se e pague usando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Viver');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Go Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'iniciou um vídeo ao vivo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Digitalizar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'deixar de seguir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Retirar Método');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Banco');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Terminar ao vivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Receba uma notificação quando {USER} criar uma nova postagem.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Pare de receber notificações de {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'criou uma nova postagem.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'Faz {TIME} desde que vocês dois são amigos! Envie uma mensagem para comemorar.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Reembolso pedido');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'está ao vivo agora.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'estava vivo.');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Воспоминания');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'В этот день');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'У тебя нет воспоминаний в этот день.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'У вас есть память в этот день');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Аналитика страниц');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Всего лайков');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Cегодня');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Этот месяц');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Этот год');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Групповая аналитика');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Всего участников');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'ответил в вашу ветку');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Поделиться на моем графике');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'поделился форумом');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Сообщения на форуме были успешно добавлены в ваш график!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Тема была успешно добавлена ​​в ваш график!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'поделился темой');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Подкатегория');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Осталось {{time}} для вашего членства');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Чтобы загрузить изображения, видео и аудио файлы, вы должны перейти на профессиональный член.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Чтобы загрузить изображения, видео и аудио файлы, вы должны перейти на профессиональный член.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Ваш блог был одобрен и опубликован!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Возврат денег');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Страница возврата');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'причина');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Мы рассмотрим ваш запрос в течение 2–3 рабочих дней.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'К сожалению, вы не являетесь подписчиком, вы не можете запросить возврат.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Пожалуйста, выберите правильное членство');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Ваш запрос находится на рассмотрении, мы сообщим вам, как только');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Ваш запрос на возврат был отклонен!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Ваш запрос на возврат был одобрен! пожалуйста, проверьте свой баланс.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Предложение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Создать новое предложение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Разместите предложение для {{page_name}} в');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Тип предложения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Процент скидки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Сумма скидки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Купить X Получить Y Скидка');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Потрать X Получи Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Бесплатная доставка');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Получить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Проводить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Сумма выключена');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Предложение успешно создано.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Создано новое предложение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Уцененные товары и / или услуги');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Добавить товары или услуги к этому предложению Макс 100 символов');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Уцененные предметы должны быть менее 100 символов');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Предложения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Загрузить больше предложений');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Нет доступных предложений для показа.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Удалить предложение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Вы уверены, что хотите удалить это предложение?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Изменить предложение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Предложение успешно обновлено.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Магазины поблизости');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Магазины найдены');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Магазины не найдены');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Бизнес поблизости');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Бизнес найден');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'Бизнес не найден');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Слишком много попыток входа, попробуйте позже');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'Предоставленный пароль не соответствует требованиям минимальной сложности');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Должно быть длиной не менее 6 символов.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Должен содержать строчную букву.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Должен содержать заглавную букву.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Должен содержать номер или специальный символ.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Пользователи могут публиковать на моей странице');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'привилегии');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Доступ к общим настройкам');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Доступ к настройкам информации о странице');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Доступ к настройкам социальных ссылок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Доступ к аватару');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Доступ к настройкам дизайна');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Доступ к настройкам админов');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Доступ к настройкам аналитики');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Доступ к удалению настроек страницы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Доступ к настройкам конфиденциальности');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Доступ к настройкам участников');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Доступ к удалению настроек группы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Пригласительные ссылки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Доступные ссылки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Сгенерированные ссылки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Использованные ссылки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Генерировать ссылки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Код успешно сгенерирован');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'скопированный');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'копия');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Приглашенный пользователь');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'неограниченный');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'анонимное');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'ФИО');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Свифт код');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Ваш запрос на вывод средств был одобрен!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Ваш запрос на снятие был отклонен!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Зарегистрируйтесь и оплатите, используя');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Прямой эфир');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Go Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'начал живое видео.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Paysera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'Отсоединиться');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Метод вывода');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Банка');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Конец жить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Получите уведомление, когда {USER} создаст новое сообщение.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Прекратить получать уведомления от {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'создал новый пост.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'Это было {ВРЕМЯ}, так как вы оба друзья! Отправьте им сообщение, чтобы отпраздновать.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Запросить возврат');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'сейчас жив.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'был живой.');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Recuerdos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'En este día');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'No tienes ningún recuerdo en este día.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Recuerdas este día');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Analítica de página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Me gusta total');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Hoy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Este mes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Este año');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Analítica de grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Miembros totales');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'respondió a tu hilo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Comparte en mi línea de tiempo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'compartió un foro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', '¡Las publicaciones del foro se agregaron con éxito a su línea de tiempo!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', '¡El hilo se agregó con éxito a su línea de tiempo!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'ha compartido un hilo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Subcategoría');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Restante {{time}} para su membresía');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Para cargar imágenes, videos y archivos de audio, debe actualizar a miembro profesional.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Para cargar imágenes, videos y archivos de audio, debe actualizar a miembro profesional.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Su blog fue aprobado y publicado!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Reembolso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Página de reembolso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Razón');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'Revisaremos su solicitud dentro de 2 a 3 días hábiles.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Vaya, no eres un suscriptor, no puedes solicitar un reembolso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Por favor seleccione su membresía correcta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Su solicitud está bajo revisión, se lo notificaremos una vez');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', '¡Su solicitud de reembolso ha sido rechazada!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', '¡Su solicitud de reembolso ha sido aprobada! por favor revise su saldo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Libre de efectivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Crear nueva oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Publique una oferta para {{page_name}} en');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Tipo de oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Porcentaje de descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Importe de descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Compre X Obtenga Y Descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Gastar X Obtener Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Envío gratis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Obtener');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Gastar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Cantidad de descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Oferta creada con éxito.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Nueva oferta creada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Artículos y / o servicios con descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Agregue elementos o servicios a esta oferta Máximo 100 caracteres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Los artículos con descuento deben tener menos de 100 caracteres.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Ofertas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Cargue más ofertas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'No hay ofertas disponibles para mostrar.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Eliminar oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', '¿Estás seguro de que deseas eliminar esta oferta?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Editar oferta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Oferta actualizada con éxito.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Tiendas cercanas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Tiendas encontradas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'No se encontraron tiendas.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Negocios cercanos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Negocio encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'No se ha encontrado ningún negocio.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Demasiados intentos de inicio de sesión, intente nuevamente más tarde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'La contraseña suministrada no cumple los requisitos mínimos de complejidad.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Debe tener al menos 6 caracteres de longitud.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Debe contener una letra minúscula.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Debe contener una letra mayúscula.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Debe contener un número o carácter especial.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Los usuarios pueden publicar en mi página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privilegios');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Acceso a configuraciones generales');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Acceso a la configuración de información de la página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Acceso a la configuración de enlaces sociales');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Acceso al avatar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Acceso a configuraciones de diseño');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Acceso a la configuración de administradores');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Acceso a la configuración de análisis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Acceso para eliminar la configuración de la página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Acceso a la configuración de privacidad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Acceso a la configuración de miembros');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Acceso para eliminar la configuración del grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Enlaces de invitación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Enlaces Disponibles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Enlaces generados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Enlaces Usados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Generar enlaces');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Código generado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copiado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Copiar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Usuario invitado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Ilimitado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anónimo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Nombre completo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Código SWIFT');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', '¡Su solicitud de retiro ha sido aprobada!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', '¡Su solicitud de retiro ha sido rechazada!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Regístrese y pague usando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'En Vivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Ir a vivir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'comenzó un video en vivo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Escanear');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'dejar de seguir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Método de retirada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Banco');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Fin en vivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Reciba una notificación cuando {USUARIO} cree una nueva publicación.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Dejar de recibir notificaciones de {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'creó una nueva publicación.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', '¡Ha pasado {TIME} desde que ambos son amigos! Envíales un mensaje para celebrar.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Solicitud de reembolso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'es en vivo ahora.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'Fue en vivo.');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'hatıralar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'Bugün');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'Bu günle ilgili hatıralarınız yok.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'Bu gün hatırlıyorsun');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Sayfa Analizi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Toplam Beğenme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Bugün');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'Bu ay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'Bu yıl');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Grup Analizi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Toplam Üye');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'iş parçacığına cevap verdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Zaman çizelgemde paylaş');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'bir forum paylaştı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Forum gönderileri zaman çizelgenize başarıyla eklendi!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Konu zaman çizelgenize başarıyla eklendi!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'bir konu paylaştı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Alt Kategori');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Üyeliğiniz için {{time}} kaldı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'Resim, video ve ses dosyası yüklemek için profesyonel üyeye yükseltmelisiniz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'Resim, video ve ses dosyası yüklemek için profesyonel üyeye yükseltmelisiniz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Blogunuz onaylandı ve yayınlandı!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Geri ödeme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Geri ödeme sayfası');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'neden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'İsteğinizi 2-3 iş günü içinde inceleyeceğiz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Hata! Abone değilsiniz, geri ödeme isteğinde bulunamazsınız.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Lütfen doğru üyeliğinizi seçin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'İsteğiniz inceleniyor, size bir kez bildireceğiz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Geri ödeme isteğiniz reddedildi!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Geri ödeme isteğiniz onaylandı! lütfen bakiyenizi kontrol edin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Teklif');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Yeni Teklif Oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', '{{Page_name}} için bir teklif gönderin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Teklif Türü');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'İndirim Yüzdesi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'İndirim tutarı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'X Get Y İndirimi Satın Al');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'X Harcama Y Kazanın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Ücretsiz kargo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Almak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Harcama');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Tutar Kapalı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Teklif başarıyla oluşturuldu.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Yeni teklif oluşturuldu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'İndirimli Ürünler ve / veya Hizmetler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Bu teklife ürün veya hizmet ekleyin Maks. 100 karakter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'İndirimli ürünler 100 karakterden az olmalıdır');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Teklifler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Daha fazla teklif yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'Gösterilecek mevcut teklif yok.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Teklifi Sil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Bu teklifi silmek istediğinizden emin misiniz?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Teklifi Düzenle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Teklif başarıyla güncellendi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Yakındaki Mağazalar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Bulunan dükkanlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'Dükkan bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Yakındaki İşletmeler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'İşletme bulundu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'İşletme bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Çok fazla giriş denemesi lütfen daha sonra tekrar deneyin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'Verilen parola minimum karmaşıklık gereksinimlerini karşılamıyor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'En az 6 karakter uzunluğunda olmalı.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Küçük harf içermelidir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Bir büyük harf içermeli.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Bir sayı veya özel karakter içermelidir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Kullanıcılar sayfama mesaj gönderebilir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Ayrıcalıklar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Genel ayarlara erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Sayfa bilgisi ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Sosyal bağlantılar ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Avatara erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Tasarım ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Yönetici ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Analiz ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Sayfa ayarlarını silme erişimi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Gizlilik ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Üye ayarlarına erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Grup ayarlarını silme erişimi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Davetiye Bağlantıları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Kullanılabilir Bağlantılar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Oluşturulan Bağlantılar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Kullanılan Bağlantılar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Bağlantı oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Kod başarıyla oluşturuldu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Kopyalanan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'kopya');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Davet Edilen Kullanıcı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Sınırsız');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Ad Soyad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Swift kodu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Para çekme talebiniz onaylandı!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Para çekme talebiniz reddedildi!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Kayıt ve kullanarak ödeme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Canlı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Canlı Yayına Başla');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'canlı bir video başlattı.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Paysera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'vazgeçebilirim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Para Çekme Yöntemi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Banka');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'Canlı sona erdir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', '{USER} yeni bir yayın oluştururken bildirim alın.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', '{USER} adlı kullanıcıdan bildirim almayı durdur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'yeni bir yayın oluşturdu.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'İkiniz de arkadaş olduğunuz için {TIME} oldu! Kutlamak için onlara bir mesaj gönderin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'İstek iadesi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'şimdi canlı.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'canlıydı.');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Memories');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'On this day');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'You don\'t have any memories on this day.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'You have remembrance on this day');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Page Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Total Likes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Today');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'This Month');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'This Year');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Group Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Total Members');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'replied to your thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Share on my timeline');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'shared a forum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Forum posts were successfully added to your timeline!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Thread was successfully added to your timeline!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'shared a thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Sub Category');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Remaining {{time}} for your membership');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'To upload images, videos, and audio files, you have to upgrade to pro member.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'To upload images, videos, and audio files, you have to upgrade to pro member.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Your blog was approved and published!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Refund');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Refund page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Reason');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'We will review your request within 2 - 3 business days.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Oops, You are not a subscriber, you can\'t request refund.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Please select your correct membership');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Your request is under review, we will notify you once it&#039;s approved');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Your refund request has been declined!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Your refund request has been approved! please check your balance.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Create New Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Post a offer for {{page_name}} on');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Offer Type');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Discount Percent');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Discount Amount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Buy X Get Y Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Spend X Get Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Free Shipping');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Get');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Spend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Amount Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Offer successfully created.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Created new offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Discounted Items and/or Services');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Add items or services to this offer Max 100 character');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Discounted items must be less than 100 character');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Offers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Load more offers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'No available offers to show.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Delete Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Are you sure that you want to delete this offer?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Edit Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Offer successfully updated.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Nearby Shops');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Shops found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'No shops found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Nearby Business');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Business found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'No business found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Too many login attempts please try again later');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'The password supplied does not meet the minimum complexity requirements');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Must be at least 6 characters long.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Must contain a lowercase letter.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Must contain an uppercase letter.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Must contain a number or special character.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Users can post on my page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privileges');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Access to general settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Access to page information settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Access to social links settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Access to avatar & cover settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Access to design settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Access to admins settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Access to analytics settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Access to delete page settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Access to privacy settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Access to members settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Access to delete group settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Invitation Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Available Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Generated Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Used Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Generate links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Code successfully generated');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copied');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Copy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Invited User');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Unlimited');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonymous');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Full name');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Swift code');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Your withdraw request has been approved!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Your withdraw request has been declined!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Register and pay using ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Go Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'started a live video.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Paysera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'unfollow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Withdraw Method');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Bank');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'End live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Get a notification when {USER} create a new post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Stop getting a notifications from {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'created a new post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'It\'s been {TIME} since you both are friends! Send them a message to celebrate.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Request refund');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'is live now.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'was live.');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memories', 'Memories');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'on_this_day', 'On this day');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'there_are_no_memories_this_day', 'You don\'t have any memories on this day.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'friendversaries', 'Friendaversary');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'memory_this_day', 'You have remembrance on this day');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_analytics', 'Page Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_likes', 'Total Likes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'today', 'Today');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_month', 'This Month');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'this_year', 'This Year');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_analytics', 'Group Analytics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'total_member', 'Total Members');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_reply', 'replied to your thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'share_on_timeline', 'Share on my timeline');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_forum', 'shared a forum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'forum_shared', 'Forum posts were successfully added to your timeline!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'thread_shared', 'Thread was successfully added to your timeline!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shared_thread', 'shared a thread');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sub_category', 'Sub Category');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'remaining_text', 'Remaining {{time}} for your membership');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload', 'To upload images, videos, and audio files, you have to upgrade to pro member.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_plan_upload_pro', 'To upload images, videos, and audio files, you have to upgrade to pro member.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'approve_blog', 'Your blog was approved and published!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund', 'Refund');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_page', 'Refund page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reason', 'Reason');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_days', 'We will review your request within 2 - 3 business days.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_membership', 'Oops, You are not a subscriber, you can`t request refund.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'select_your_membership', 'Please select your correct membership');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_review_text', 'Your request is under review, we will notify you once it&#039;s approved');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_decline', 'Your refund request has been declined!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_approve', 'Your refund request has been approved! please check your balance.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paystack', 'Paystack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'cashfree', 'Cashfree');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer', 'Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_offer', 'Create New Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'post_offer_text', 'Post a offer for {{page_name}} on');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_type', 'Offer Type');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_percent', 'Discount Percent');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount_amount', 'Discount Amount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_get_discount', 'Buy X Get Y Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend_get_off', 'Spend X Get Y Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'free_shipping', 'Free Shipping');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get', 'Get');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'spend', 'Spend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_off', 'Amount Off');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_created', 'Offer successfully created.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_offer', 'Created new offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items', 'Discounted Items and/or Services');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'items_services', 'Add items or services to this offer Max 100 character');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discounted_items_less', 'Discounted items must be less than 100 character');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offers', 'Offers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_offers', 'Load more offers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_offers', 'No available offers to show.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_offer', 'Delete Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_delete_offer', 'Are you sure that you want to delete this offer?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_offer', 'Edit Offer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'offer_successfully_edited', 'Offer successfully updated.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_shops', 'Nearby Shops');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'shops_found', 'Shops found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_shops_found', 'No shops found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'nearby_business', 'Nearby Business');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'business_found', 'Business found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_business_found', 'No business found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'login_attempts', 'Too many login attempts please try again later');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'complexity_requirements', 'The password supplied does not meet the minimum complexity requirements');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'least_characters', 'Must be at least 6 characters long.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_lowercase', 'Must contain a lowercase letter.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contain_uppercase', 'Must contain an uppercase letter.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'number_special', 'Must contain a number or special character.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_can_post', 'Users can post on my page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'privileges', 'Privileges');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_general_settings', 'Access to general settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_info_settings', 'Access to page information settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_social_settings', 'Access to social links settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_avatar_settings', 'Access to avatar & cover settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_design_settings', 'Access to design settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_admins_settings', 'Access to admins settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_analytics_settings', 'Access to analytics settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_page_settings', 'Access to delete page settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_privacy_settings', 'Access to privacy settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_members_settings', 'Access to members settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'access_to_delete_group_settings', 'Access to delete group settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invitation_links', 'Invitation Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'available_links', 'Available Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generated_links', 'Generated Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'used_links', 'Used Links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'generate_link', 'Generate links');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'code_successfully', 'Code successfully generated');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copied', 'Copied');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'copy', 'Copy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'invited_user', 'Invited User');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unlimited', 'Unlimited');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'anonymous', 'Anonymous');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iban', 'IBAN');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'full_name', 'Full name');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'swift_code', 'Swift code');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_approve', 'Your withdraw request has been approved!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_declined', 'Your withdraw request has been declined!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'register_and_pay', 'Register and pay using ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'live', 'Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'go_live', 'Go Live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'started_live_video', 'started a live video.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'razorpay', 'Razorpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'paysera', 'Paysera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unfollow', 'unfollow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'withdraw_method', 'Withdraw Method');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'bank', 'Bank');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'end_live', 'End live');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_notification_posts', 'Get a notification when {USER} create a new post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop_notification_posts', 'Stop getting a notifications from {USER}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'created_new_post', 'created a new post.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'time_friends', 'It\'s been {TIME} since you both are friends! Send them a message to celebrate.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Request refund');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'is_live', 'is live now.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'was_live', 'was live.');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
    }
    $users = $db->get(T_USERS, null, array(
        'notification_settings',
        'user_id'
    ));
    if (!empty($users)) {
        foreach ($users as $key => $user) {
            $update_array = array();
            if (!empty($user->notification_settings)) {
                $notification_settings                 = (Array) json_decode(html_entity_decode($user->notification_settings));
                $notification_settings['e_memory']     = 1;
                $update_array['notification_settings'] = json_encode($notification_settings);
            }
            if (!empty($update_array)) {
                $db->where('user_id', $user->user_id)->update(T_USERS, $update_array);
            }
        }
    }
    $old = array();
    $ids = array();
    foreach ($wo['reactions_types'] as $key => $value) {
        $old[strtolower($value['name'])] = $value['id'];
        $ids[]                           = $value['id'];
    }
    $reactions = $db->get(T_REACTIONS);
    if (!empty($reactions)) {
        foreach ($reactions as $key => $value) {
            if (!empty($value->reaction) && !empty($old[strtolower($value->reaction)])) {
                $db->where('id', $value->id)->update(T_REACTIONS, array(
                    'reaction' => $old[strtolower($value->reaction)]
                ));
            } else {
                if (!in_array($value->reaction, $ids)) {
                    $db->where('id', $value->id)->delete(T_REACTIONS);
                }
            }
        }
    }
    unset($reactions);
    $reactions = $db->get(T_BLOG_REACTION);
    if (!empty($reactions)) {
        foreach ($reactions as $key => $value) {
            if (!empty($value->reaction) && !empty($old[strtolower($value->reaction)])) {
                $db->where('id', $value->id)->update(T_BLOG_REACTION, array(
                    'reaction' => $old[strtolower($value->reaction)]
                ));
            } else {
                if (!in_array($value->reaction, $ids)) {
                    $db->where('id', $value->id)->delete(T_BLOG_REACTION);
                }
            }
        }
    }
    unset($reactions);
    $reactions = $db->where('activity_type', '%reaction%', 'LIKE')->get(T_ACTIVITIES);
    if (!empty($reactions)) {
        foreach ($reactions as $key => $value) {
            if (strpos(strtolower($value->activity_type), 'like')) {
                $type = str_replace('like', $old['like'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            } elseif (strpos(strtolower($value->activity_type), 'love')) {
                $type = str_replace('love', $old['love'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            } elseif (strpos(strtolower($value->activity_type), 'haha')) {
                $type = str_replace('haha', $old['haha'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            } elseif (strpos(strtolower($value->activity_type), 'wow')) {
                $type = str_replace('wow', $old['wow'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            } elseif (strpos(strtolower($value->activity_type), 'sad')) {
                $type = str_replace('sad', $old['sad'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            } elseif (strpos(strtolower($value->activity_type), 'angry')) {
                $type = str_replace('angry', $old['angry'], strtolower($value->activity_type));
                $db->where('id', $value->id)->update(T_ACTIVITIES, array(
                    'activity_type' => $type
                ));
            }
        }
    }
    unset($reactions);
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
                     <h2 class="light">Update to v3.0 [Ultmiate Update] </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                                <li> [Added] the ability to edit/add/disable post reactions. </li>
                                <li> [Added] memories system, user can view his memories on this day.</li>
                                <li> [Added] live stream for posts [enable/disable] (third party sites).</li>
                                <li> [Added] page analytics, view latest likes per week, month, year, and today.</li>
                                <li> [Added] group analytics, view latest joins per week, month, year, and today.</li>
                                <li> [Added] blog post notifications, get a notification when someone comments on your blog post.</li>
                                <li> [Added] forum notifications, get a notification when someone comments on your forum thread.</li>
                                <li> [Added] the ability to require membership on sign up, [enable/disable]</li>
                                <li> [Added] sub categories for pages, groups and marketplace.</li>
                                <li> [Added] custom fields for pages, groups and marketplace. </li>
                                <li> [Added] remaining pro membership days announcement. </li>
                                <li> [Added] recurring payments for pro system.</li>
                                <li> [Added] the ability for only pro users could upload [enable/disable].</li>
                                <li> [Added] the ability for only pro users could make calls [enable/disable]</li>
                                <li> [Added] blog posts review/approval system. </li>
                                <li> [Added] the ability to request refund for pro package [enable/disable]</li>
                                <li> [Added] refund policy page. </li>
                                <li> [Added] paystack, razorpay, paysera and cashfree payment methods. </li>
                                <li> [Added] page to show shops/businesses nearby. </li>
                                <li> [Added] offer system for pages. </li>
                                <li> [Added] password complexity system on register page. </li>
                                <li> [Added] prevent brutforce on login system, limit failed login per min.</li>
                                <li> [Added] the ability for users to be able to post in the pages. [enable/disable]</li>
                                <li> [Added] the ability for pages and groups owners could add moderators with certain privileges.</li>
                                <li> [Added] google cloud for storage.</li>
                                <li> [Added] bank account withdrawal method, user can send his bank info to admin and admin can review then approve.</li>
                                <li> [Added] email mock system. send email for users who didn't login for X time.</li>
                                <li> [Added] the ability to add games from other sites, not just miniclip.</li>
                                <li> [Added] the ability to get notified when user posts new post.</li>
                                <li> [Added] ads within and inside articles.</li>
                                <li> [Added] the ability for users to generate invite links.</li>
                                <li> [Added] shout box, post anonymously.</li>
                                <li> [Added] the ability to get notified when a friend posts a new post.</li>
                                <li> [Fixed] 50+ bugs.</li>
                                <li> [Fixed] bugs in API.</li>
                                <li> [Fixed] Important security issues.</li>
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
    "UPDATE `Wo_Config` SET `value` = '3.0' WHERE `name` = 'version';",
    "CREATE TABLE `Wo_Reactions_Types` (`id` int(11) NOT NULL,`name` varchar(50) NOT NULL DEFAULT '',`wowonder_icon` varchar(300) NOT NULL DEFAULT '',`sunshine_icon` varchar(300) NOT NULL DEFAULT '',`status` int(11) NOT NULL DEFAULT '1') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Reactions_Types` (`id`, `name`, `wowonder_icon`, `sunshine_icon`, `status`) VALUES (1, 'like', '', '', 1),(2, 'love', '', '', 1),(3, 'haha', '', '', 1),(4, 'wow', '', '', 1),(5, 'sad', '', '', 1),(6, 'angry', '', '', 1);",
    "ALTER TABLE `Wo_Reactions_Types` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `Wo_Reactions_Types` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'memories_system', '1');",
    "ALTER TABLE `Wo_Followers` ADD `time` INT(50) NOT NULL DEFAULT '0' AFTER `active`;",
    "ALTER TABLE `Wo_Posts` ADD `forum_id` INT(11) NOT NULL DEFAULT '0' AFTER `blog_id`;",
    "ALTER TABLE `Wo_Posts` ADD `thread_id` INT(11) NOT NULL DEFAULT '0' AFTER `forum_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'membership_system', '0');",
    "ALTER TABLE `Wo_Pages` ADD `sub_category` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `page_category`;",
    "CREATE TABLE `Wo_Sub_Categories` (`id` int(11) NOT NULL AUTO_INCREMENT,`category_id` int(11) NOT NULL DEFAULT '0',`lang_key` varchar(200) NOT NULL DEFAULT '',`type` varchar(200) NOT NULL DEFAULT '',PRIMARY KEY (`id`),KEY `category_id` (`category_id`),KEY `lang_key` (`lang_key`),KEY `type` (`type`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Groups` ADD `sub_category` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `category`;",
    "ALTER TABLE `Wo_Products` ADD `sub_category` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `category`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'recurring_payment', 'off');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'who_upload', 'all');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'Who_call', 'all');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'blog_approval', '0');",
    "ALTER TABLE `Wo_Blog` ADD `active` ENUM('0','1') NOT NULL DEFAULT '1' AFTER `tags`;",
    "ALTER TABLE `Wo_Blog` ADD INDEX(`active`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'refund_system', 'on');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paystack_payment', 'no');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paystack_secret_key', '');",
    "ALTER TABLE `Wo_Users` ADD `paystack_ref` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `weather_unit`;",
    "ALTER TABLE `Wo_Users` ADD INDEX(`paystack_ref`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cashfree_payment', 'no');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cashfree_client_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cashfree_secret_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'msg91_authKey', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'offer_system', '1');",
    "ALTER TABLE `Wo_Posts` ADD `offer_id` INT(11) NOT NULL DEFAULT '0' AFTER `job_id`;",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`offer_id`);",
    "CREATE TABLE `Wo_Offers` (`id` int(11) NOT NULL AUTO_INCREMENT,`page_id` int(11) NOT NULL DEFAULT '0',`user_id` int(11) NOT NULL DEFAULT '0',`discount_type` varchar(200) NOT NULL DEFAULT '',`discount_percent` int(11) NOT NULL DEFAULT '0',`discount_amount` int(11) NOT NULL DEFAULT '0',`discounted_items` varchar(150) DEFAULT '',`buy` int(11) NOT NULL DEFAULT '0',`get_price` int(11) NOT NULL DEFAULT '0',`spend` int(11) NOT NULL DEFAULT '0',`amount_off` int(11) NOT NULL DEFAULT '0',`description` text,`expire_date` time NOT NULL,`expire_time` int(11) DEFAULT NULL,`image` varchar(300) NOT NULL DEFAULT '',`currency` varchar(50) NOT NULL DEFAULT '',`time` int(50) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'nearby_shop_system', '1');",
    "ALTER TABLE `Wo_Products` ADD `page_id` INT(11) NOT NULL DEFAULT '0' AFTER `user_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'nearby_business_system', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'prevent_system', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'bad_login_limit', '4');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'lock_time', '10');",
    "CREATE TABLE `Wo_Bad_Login` (`id` int(11) NOT NULL AUTO_INCREMENT,`ip` varchar(100) NOT NULL DEFAULT '',`time` int(50) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),KEY `ip` (`ip`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'password_complexity_system', '0');",
    "ALTER TABLE `Wo_Users` CHANGE `notification_settings` `notification_settings` VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '{\"e_liked\":1,\"e_shared\":1,\"e_wondered\":0,\"e_commented\":1,\"e_followed\":1,\"e_accepted\":1,\"e_mentioned\":1,\"e_joined_group\":1,\"e_liked_page\":1,\"e_visited\":1,\"e_profile_wall_post\":1,\"e_memory\":1}';",
    "CREATE TABLE `Wo_Custom_Fields` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL DEFAULT '',`description` text,`type` varchar(50) DEFAULT '',`length` int(11) NOT NULL DEFAULT '0',`placement` varchar(50) NOT NULL DEFAULT '',`required` varchar(11) NOT NULL DEFAULT 'on',`options` text,`active` int(11) NOT NULL DEFAULT '1',PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Pages` ADD `users_post` INT(11) NOT NULL DEFAULT '0' AFTER `cover`;",
    "ALTER TABLE `Wo_PageAdmins` ADD `general` INT(11) NOT NULL DEFAULT '1' AFTER `page_id`, ADD `info` INT(11) NOT NULL DEFAULT '1' AFTER `general`, ADD `social` INT(11) NOT NULL DEFAULT '1' AFTER `info`, ADD `avatar` INT(11) NOT NULL DEFAULT '1' AFTER `social`, ADD `design` INT(11) NOT NULL DEFAULT '1' AFTER `avatar`, ADD `admins` INT(11) NOT NULL DEFAULT '0' AFTER `design`, ADD `analytics` INT(11) NOT NULL DEFAULT '1' AFTER `admins`, ADD `delete_page` INT(11) NOT NULL DEFAULT '0' AFTER `analytics`;",
    "ALTER TABLE `Wo_GroupAdmins` ADD `general` INT(11) NOT NULL DEFAULT '1' AFTER `group_id`, ADD `privacy` INT(11) NOT NULL DEFAULT '1' AFTER `general`, ADD `avatar` INT(11) NOT NULL DEFAULT '1' AFTER `privacy`, ADD `members` INT(11) NOT NULL DEFAULT '0' AFTER `avatar`, ADD `analytics` INT(11) NOT NULL DEFAULT '1' AFTER `members`, ADD `delete_group` INT(11) NOT NULL DEFAULT '0' AFTER `analytics`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'invite_links_system', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'user_links_limit', '10');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'expire_user_links', 'month');",
    "CREATE TABLE `Wo_Invitation_Links` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0',`invited_id` int(11) NOT NULL DEFAULT '0',`code` varchar(300) NOT NULL DEFAULT '',`time` int(50) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),KEY `code` (`code`(255)),KEY `invited_id` (`invited_id`),KEY `time` (`time`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'shout_box_system', '1');",
    "ALTER TABLE `Wo_Posts` CHANGE `postPrivacy` `postPrivacy` ENUM('0','1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'bank_withdrawal_system', '1');",
    "ALTER TABLE `Wo_Affiliates_Requests` ADD `iban` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `full_amount`, ADD `country` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `iban`, ADD `full_name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `country`, ADD `swift_code` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `full_name`, ADD `address` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `swift_code`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'live_video', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'live_token', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'live_account_id', '');",
    "ALTER TABLE `Wo_Posts` ADD `stream_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `active`;",
    "ALTER TABLE `Wo_Posts` ADD `live_time` INT(50) NOT NULL DEFAULT '0' AFTER `stream_name`;",
    "CREATE TABLE `Wo_Live_Sub_Users` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0',`post_id` int(11) NOT NULL DEFAULT '0',`time` int(50) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'razorpay_payment', 'no');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'razorpay_key_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'razorpay_key_secret', '');",
    "ALTER TABLE `Wo_Live_Sub_Users` ADD `is_watching` INT(11) NOT NULL DEFAULT '0' AFTER `post_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paysera_payment', 'no');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paysera_project_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paysera_sign_password', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'paysera_mode', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cloud_upload', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cloud_file_path', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cloud_bucket_name', '');",
    "CREATE TABLE `Wo_Refund` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0',`pro_type` varchar(50) NOT NULL DEFAULT '',`description` text,`status` int(11) NOT NULL DEFAULT '0',`time` int(50) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),KEY `user_id` (`user_id`),KEY `pro_type` (`pro_type`),KEY `status` (`status`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Terms` (`id`, `type`, `text`) VALUES (NULL, 'refund', '<h4>1- Write your Terms Of Use here.</h4>     \r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis sdnostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');",
    "ALTER TABLE `Wo_Messages` ADD `lat` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' AFTER `product_id`, ADD `lng` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' AFTER `lat`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'live_video_save', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'notify_new_post', '0');",
    "ALTER TABLE `Wo_Followers` ADD `notify` INT(11) NOT NULL DEFAULT '0' AFTER `active`;",
    "ALTER TABLE `Wo_Posts` ADD `live_ended` INT(11) NOT NULL DEFAULT '0' AFTER `live_time`;",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`live_time`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`live_ended`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`active`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`job_id`);",
    "ALTER TABLE `Wo_Live_Sub_Users` ADD INDEX(`time`);",
    "ALTER TABLE `Wo_Live_Sub_Users` ADD INDEX(`is_watching`);",
    "ALTER TABLE `Wo_Live_Sub_Users` ADD INDEX(`post_id`);",
    "ALTER TABLE `Wo_Live_Sub_Users` ADD INDEX(`user_id`);",
    "ALTER TABLE `Wo_Invitation_Links` ADD INDEX(`user_id`);",
    "ALTER TABLE `Wo_Offers` ADD INDEX(`page_id`);",
    "ALTER TABLE `Wo_Offers` ADD INDEX(`user_id`)",
    "ALTER TABLE `Wo_Offers` ADD INDEX(`spend`);",
    "ALTER TABLE `Wo_Offers` ADD INDEX(`time`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`page_event_id`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`blog_id`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`color_id`);",
    "ALTER TABLE `Wo_Posts` ADD INDEX(`thread_id`);",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'memories');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'on_this_day');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'there_are_no_memories_this_day');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'friendversaries');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'memory_this_day');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'page_analytics');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total_likes');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'today');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'this_month');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'this_year');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'group_analytics');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total_member');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'thread_reply');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'share_on_timeline');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'shared_forum');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'forum_shared');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'thread_shared');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'shared_thread');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sub_category');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'remaining_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'free_plan_upload');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'free_plan_upload_pro');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'approve_blog');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'refund');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'refund_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'reason');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'business_days');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'you_not_membership');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'select_your_membership');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'request_review_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'refund_decline');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'refund_approve');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'paystack');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'cashfree');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'post_offer_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'offer_type');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'discount_percent');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'discount_amount');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'buy_get_discount');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'spend_get_off');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'free_shipping');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'spend');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'amount_off');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'offer_successfully_created');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'created_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'discounted_items');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'items_services');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'discounted_items_less');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'offers');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'load_more_offers');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_available_offers');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'confirm_delete_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_offer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'offer_successfully_edited');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'nearby_shops');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'shops_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_shops_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'nearby_business');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'business_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_business_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'login_attempts');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'complexity_requirements');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'least_characters');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'contain_lowercase');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'contain_uppercase');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'number_special');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'users_can_post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'privileges');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_general_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_info_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_social_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_avatar_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_design_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_admins_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_analytics_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_delete_page_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_privacy_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_members_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'access_to_delete_group_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'invitation_links');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'available_links');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'generated_links');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'used_links');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'generate_link');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'code_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'copied');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'copy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'invited_user');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'unlimited');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'anonymous');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'iban');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'full_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'swift_code');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'withdraw_approve');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'withdraw_declined');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'register_and_pay');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'live');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'go_live');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'started_live_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'razorpay');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'paysera');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'unfollow');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'withdraw_method');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'bank');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'end_live');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get_notification_posts');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stop_notification_posts');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'created_new_post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'time_friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'request_refund');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'is_live');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'was_live');",

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