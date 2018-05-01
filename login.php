<?php
require dirname(__FILE__).'/source/class/class_core.php';
require dirname(__FILE__).'/source/class/class_load.php';
$load = new Load;
$load->loadClass('template');
$load->loadFunction('filter');

//Template setting
$options = array(
    'template_dir' => 'template/common/',
    'cache_dir' => 'data/cache/',
    'auto_update' => true,
    'cache_lifetime' => 0,
);

$template = Template::getInstance();
$template->setOptions($options);

//Check login
if (!empty($now_login)) {
    header('Location: ./');
}

$account_error = $password_error = '';
if (isset($_POST['submit'])) {
    $login_permit = true;
    $account = $_POST['account'];
    $password = input_filter($_POST['password']);
    $login_query = prepare('SELECT username, password, email FROM user WHERE (ussrname = :username OR email = :email)');
    $login_stmt = $con->stmt_init();
    $login_stmt->bindparam(':username', $account);
    $login_stmt->bindparam(':email', $email);
    $login_stmt->bindparam(':password', $password);

    if ($result->num_rows == 0){
        $account_error = $lang_username_not_exist;
        $login_permit = false;
    } elseif (!password_verify($password, $row['password'])) {
        $password_error = $lang_wrong_password;
        $login_permit = false;
    }

    if ($result->num_rows != 0 && $login_permit === true) {
        $_SESSION['username'] = $row['username'];
        $display = 'view_success';
    } else {
        $display = 'view_login';
    }
} else {
    $display = 'view_login';
}

include($template->loadTemplate('header_common.html'));
include($template->loadTemplate($display.'.html'));
include($template->loadTemplate('footer_common.html'));
