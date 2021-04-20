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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'لا يوجد مستخدمون ذاهبون.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'صفحات أعجبتني');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'انضم إلى المجموعات');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'كسب٪ d نقاط عن طريق إنشاء مدونة جديدة');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'عرض المرشحين المهتمين');
        } else if ($value == 'dutch') {
           $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Er zijn geen vertrekkende gebruikers.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Liked Pages');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Aangesloten groepen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Verdien %d punten door een nieuw blog te maken');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Bekijk geïnteresseerde kandidaten');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Il n&#39;y a aucun utilisateur actif.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Pages aimées');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Groupes joints');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Gagnez %d points en créant un nouveau blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Voir les candidats intéressés');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Es gibt keine gehenden Benutzer.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Gefallene Seiten');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Verbundene Gruppen');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Verdiene %d Punkte, indem du ein neues Blog erstellst');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Interessierte Kandidaten anzeigen');
        } else if ($value == 'italian') {
           $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Non ci sono utenti attivi.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Pagine piaciute');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Gruppi uniti');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Guadagna %d punti creando un nuovo blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Visualizza i candidati interessati');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Não há usuários ativos.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Páginas curtidas');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Grupos Associados');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Ganhe %d pontos criando um novo blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Exibir candidatos interessados');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Там нет идущих пользователей.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Понравившиеся страницы');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Объединенные группы');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Заработайте %d баллов, создав новый блог');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Посмотреть заинтересованных кандидатов');
        } else if ($value == 'spanish') {
           $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'No hay usuarios que vayan.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Páginas Me gusta');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Grupos unidos');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Gane %d puntos creando un nuevo blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'Ver candidatos interesados');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'Giden kullanıcı yok.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Beğenilen Sayfalar');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Katılan Gruplar');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Yeni bir blog oluşturarak %d puan kazanın');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'İlgilenen Adayları Göster');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'There are no going users.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Liked Pages');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Joined Groups');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Earn %d points by creating a new blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'View Interested Candidates');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_going_people', 'There are no going users.');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_pages', 'Liked Pages');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'joined_groups', 'Joined Groups');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_blog', 'Earn %d points by creating a new blog');
    $lang_update_queries[] = Wo_UpdateLangs($value, 'view_interested_Candidates', 'View Interested Candidates');
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
                     <h2 class="light">Update to v2.5.1 [Important] </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                                <li> [Added] view joined groups and liked pages from one page.</li>
                                <li> [Added] the ability to attache photos in commment replies.</li>
                                <li> [Added] missing files from v2.5</li>
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
    "UPDATE `Wo_Config` SET `value` = '2.5.1' WHERE `name` = 'version';",
    "ALTER TABLE `Wo_Comment_Replies` ADD `c_file` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `text`;",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_going_people');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'liked_pages');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'joined_groups');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'earn_text_create_blog');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'view_interested_Candidates');",
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