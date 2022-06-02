<?php

/*
 * manage all pages
 */


$resources = BASE_PATH . 'resources/';
$auth = $resources . 'auth/';

$customer = $resources . 'customer/';
$c_support = $customer . 'support/';
$c_settings = $customer . 'settings/';
$c_payment = $customer . 'payment/';
/*
 * customer products
 */
$c_lxc = $customer . 'lxc-servers/';
$c_kvm = $customer . 'kvm-servers/';
$c_webspace = $customer . 'webspace/';
$c_services = $customer . 'service/';

$partner = $resources . 'cooperation/';
$first = $resources . 'first/';
$second = $resources . 'second/';
$third = $resources . 'third/';
$team = $resources . 'team/';
$admin = $resources . 'admin/';

$sites = $resources . 'sites/';
$products = $sites . 'products/';
$legal = $sites . 'legal/';
$company = $sites . 'company/';

/*
 * Products
 */
$lxcservers = $products . 'lxc-servers/';
$kvmservers = $products . 'kvm-servers/';
$webspace = $products . 'webspace/';

/*
 * discord api
 */
$discord = $resources . 'api/';

$page = $helper->protect($_GET['page']);
if(isset($page)) {
    switch ($page) {
        default:
            include($sites . '404.php'); break;

        case "404": include($sites . '404.php'); break;

        //debug
        case "DEBUG": include(BASE_PATH . "debug/index.php");  break;

        // customer pages
        case "customer_index": include($customer . 'index.php'); break;
        case "customer_profile": include($c_settings . 'profile.php'); break;

        // auth pages
        case "auth_login": include($auth . 'login.php'); break;
        case "auth_register": include($auth . 'register.php'); break;
        case "auth_register_conform": include($auth . 'register_confirm.php'); break;
        case "auth_logout": setcookie('session_token', null, time(),'/', env('COOKIE_DOMAIN')); $_SESSION['success_msg'] = 'Ausloggen war erfolgreich.'; header('Location: ' . env('URL') . 'auth/login/'); break;
        case "auth_forgot_password": include($auth . 'forgot_password.php'); break;

        // customer payment module
        case "payment_charge": include($c_payment . 'charge.php'); break;
        case "payment_transactions": include($c_payment . 'transactions.php'); break;
        case "payment_invoice": include($c_payment . 'invoice.php'); break;
        case "payment_ok": include($c_payment . 'ok.php'); break;
        case "payment_fail": include($c_payment . 'fail.php'); break;

        // ticket system module
        case "support_index": include($c_support . 'index.php'); break;
        case "ticket_index": include($c_support . 'tickets.php'); break;
        case "ticket": include($c_support . 'ticket.php'); break;
        case "archive": include($c_support . 'archive.php'); break;

        // lxc module
        case "vserver_index": include($c_lxc . 'index.php'); break;
        case "vserver_manage": include($c_lxc . 'manage.php'); break;
        case "vserver_renew": include($c_lxc . 'renew.php'); break;
        case "vserver_reconfigure": include($c_lxc . 'reconfigure.php'); break;

        // kvm module
        case "rootserver_index": include($c_kvm . 'index.php'); break;
        case "rootserver_manage": include($c_kvm . 'manage.php'); break;
        case "rootserver_renew": include($c_kvm . 'renew.php'); break;
        case "rootserver_reconfigure": include($c_kvm . 'reconfigure.php'); break;

        // webspace module
        case "webspace_index": include($c_webspace . 'index.php'); break;
        case "webspace_manage": include($c_webspace . 'manage.php'); break;
        case "webspace_renew": include($c_webspace . 'renew.php'); break;

        // product - pages
        case "p_webspace_order": include($webspace . 'index.php'); break;

        # rootserver order
        case "p_rootserver_order": include($kvmservers . 'index.php'); break;
        # vserver order
        case "p_vserver_order": include($lxcservers . 'index.php'); break;

        // service module
        case "service_index": include($c_services . 'index.php'); break;
        case "service_manage": include($c_services . 'manage.php'); break;
        case "service_renew": include($c_services . 'renew.php'); break;

        // ajax pages
        case "get_load": include(BASE_PATH . 'software/ajax/get_load.php'); break;
        case "get_load_lxc": include(BASE_PATH . 'software/ajax/get_load_lxc.php'); break;

        // crone
        case "work_queue": include(BASE_PATH . 'software/crone/worker_queue.php'); break;
        case "runtime_queue": include(BASE_PATH . 'software/crone/runtime_queue.php'); break;

        // legal pages
        case "imprint": include($legal . 'imprint.php'); break;
        case "privacy": include($legal . 'privacy.php'); break;
        case "conditions": include($legal . 'conditions.php'); break;
        case "withdrawal": include($legal . 'withdrawal.php'); break;

        //team
        case "team_tickets": include($team."support/tickets.php");  break;
        case "team_ticket": include($team."support/ticket.php");  break;
        case "team_archiv": include($team."support/archiv.php"); break;
        case "team_users": include($team."user/users.php");  break;
        case "team_user": include($team."user/user.php");  break;
        case "team_spin_login": include($team."user/s_pin_login.php");  break;
        case "team_login_back": include($team."login_back.php");  break;
        case "team_transactions": include($team."transactions.php");  break;
        case "team_system": include($team."system.php");  break;
        case "team_ipam": include($team."ip_manager.php");  break;
        case "team_orders": include($team."orders/list.php");  break;
        case "team_order": include($team."orders/manage.php");  break;
        case "team_news": include($team."news/index.php"); break;
        case "team_new": include($team . "news/edit.php"); break;

        //api
        case "api_v1_discord": include($discord . 'index_discord.php');  break;

        # dev changelog
        case "changelog": include($sites . 'dev/changelog.php'); break;

    }

    if(strpos($currPage, 'system_') !== false || strpos($currPage, '_auth') !== false) {} else {
        include BASE_PATH . 'resources/additional/footer.php';
    }
} else {
    die(
    'Bitte aktiviere .htaccess auf deinem Server, um diese Software zu verwenden.'
    );
}