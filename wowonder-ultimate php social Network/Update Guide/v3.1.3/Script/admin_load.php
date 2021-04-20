<?php
require_once('assets/init.php');

$is_admin = Wo_IsAdmin();
$is_moderoter = Wo_IsModerator();

if ($wo['config']['maintenance_mode'] == 1) {
    if ($wo['loggedin'] == false) {
        header("Location: " . Wo_SeoLink('index.php?link1=welcome') . $wo['marker'] . 'm=true');
        exit();
    } else {
        if ($is_admin === false) {
            header("Location: " . Wo_SeoLink('index.php?link1=welcome') . $wo['marker'] . 'm=true');
            exit();
        }
    } 
}
if ($is_admin == false && $is_moderoter == false) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}


if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_GET[$key] = strip_tags($value);
    }
}
if (!empty($_REQUEST)) {
    foreach ($_REQUEST as $key => $value) {
        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_REQUEST[$key] = strip_tags($value);
    }
}
if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_POST[$key] = strip_tags($value);
    }
}
$path = (!empty($_GET['path'])) ? getPageFromPath($_GET['path']) : null;
$files = scandir('admin-panel/pages');
unset($files[0]);
unset($files[1]);
unset($files[2]);
$page = 'dashboard';
if (!empty($path['page']) && in_array($path['page'], $files) && file_exists('admin-panel/pages/'.$path['page'].'/content.phtml')) {
    $page = $path['page'];
}
$data = array();
$text = Wo_LoadAdminPage($page.'/content');
?>
<input type="hidden" id="json-data" value='<?php echo htmlspecialchars(json_encode($data));?>'>
<?php
echo $text;
?>