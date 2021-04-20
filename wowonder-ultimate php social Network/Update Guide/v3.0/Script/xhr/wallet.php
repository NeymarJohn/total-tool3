<?php 
if ($f == 'wallet') {
    if ($s == 'replenish-user-account') {
        $error = "";
        if (!isset($_GET['amount']) || !is_numeric($_GET['amount']) || $_GET['amount'] < 1) {
            $error = $error_icon . $wo['lang']['please_check_details'];
        }
        if (empty($error)) {
            $data = Wo_ReplenishWallet($_GET['amount']);
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        } else {
            header("Content-type: application/json");
            echo json_encode(array(
                'status' => 500,
                'error' => $error
            ));
            exit();
        }
    }
    if ($s == 'get-paid') {
        if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
            if (!is_array(Wo_GetWalletReplenishingDone($_GET['paymentId'], $_GET['PayerID']))) {
                if (Wo_ReplenishingUserBalance($_GET['amount'])) {
                    $_GET['amount'] = Wo_Secure($_GET['amount']);
                    $create_payment_log = mysqli_query($sqlConnect, "INSERT INTO " . T_PAYMENT_TRANSACTIONS . " (`userid`, `kind`, `amount`, `notes`) VALUES ('" . $wo['user']['id'] . "', 'WALLET', '" . $_GET['amount'] . "', 'PayPal')");
                    $_SESSION['replenished_amount'] = $_GET['amount'];
                    header("Location: " . Wo_SeoLink('index.php?link1=wallet'));
                    exit();
                } else {
                    header("Location: " . Wo_SeoLink('index.php?link1=wallet'));
                    exit();
                }
            } else {
                header("Location: " . Wo_SeoLink('index.php?link1=wallet'));
                exit();
            }
        } else if (isset($_GET['success']) && $_GET['success'] == 0) {
            header("Location: " . Wo_SeoLink('index.php?link1=wallet'));
            exit();
        } else {
            header("Location: " . Wo_SeoLink('index.php?link1=wallet'));
            exit();
        }
    }
    if ($s == 'remove' && isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $data['status'] = 304;
        if (Wo_DeleteUserAd($_GET['id'])) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'send' && $wo['loggedin'] === true) {
        $data     = array(
            'status' => 400
        );
        $user_id  = (!empty($_POST['user_id']) && is_numeric($_POST['user_id'])) ? $_POST['user_id'] : 0;
        $amount   = (!empty($_POST['amount']) && is_numeric($_POST['amount'])) ? $_POST['amount'] : 0;
        $userdata = $db->where('user_id', $user_id)->where('active', '1')->getOne(T_USERS);
        $wallet   = $wo['user']['wallet'];
        if (empty($user_id) || empty($amount) || empty($userdata) || empty(floatval($wallet)) || $amount < 0) {
            $data['message'] = $wo['lang']['please_check_details'];
        } else if ($wallet < $amount) {
            $data['message'] = $wo['lang']['amount_exceded'];
        } else {
            $amount          = ($amount <= $wallet) ? $amount : $wallet;
            $up_data1        = array(
                'wallet' => sprintf('%.2f', $userdata->wallet + $amount)
            );
            $up_data2        = array(
                'wallet' => sprintf('%.2f', $wallet - $amount)
            );
            $recipient_name  = $userdata->username;
            $currency        = Wo_GetCurrency($wo['config']['ads_currency']);
            $success_msg     = $wo['lang']['money_sent_to'];
            $notif_msg       = $wo['lang']['sent_you'];
            $data['status']  = 200;
            $data['message'] = "$success_msg@ $recipient_name";
            $db->where('user_id', $user_id)->update(T_USERS, $up_data1);
            $db->where('user_id', $wo['user']['id'])->update(T_USERS, $up_data2);
            $notification_data_array = array(
                'recipient_id' => $user_id,
                'type' => 'sent_u_money',
                'user_id' => $wo['user']['id'],
                'text' => "$notif_msg $amount$currency!",
                'url' => 'index.php?link1=wallet'
            );
            Wo_RegisterNotification($notification_data_array);
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
