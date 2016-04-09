<?php

$f3 = new BASE('base.php');

$f3->route('POST /auth/login', 'API->login');
$f3->route('POST /auth/login_facebook', 'API->login_facebook');
$f3->route('POST /auth/login_google', 'API->login_google');
$f3->route('POST /auth/register', 'API->register');
$f3->route('POST /auth/forgot-password', 'API->reserve_reset');
$f3->route('POST /auth/reset-password', 'API->reset_password');


$f3->route('POST /post/add', 'API->add_post');
$f3->route('POST /post/edit', 'API->edit_post');
$f3->route('POST /post/get-own', 'API->get_own_posts');
$f3->route('POST /post/get-all', 'API->get_all_posts');
$f3->route('POST /post/get-own-detail', 'API->get_own_post_detail');
$f3->route('POST /post/get-detail', 'API->get_post_detail');
$f3->route('POST /post/delete', 'API->delete_post');
$f3->route('POST /post/delete-from-match', 'API->delete_post_from_match');



$f3->route('POST /user/get-mine', 'API->get_my_profile');
$f3->route('POST /user/get', 'API->get_user_profile');
$f3->route('POST /user/rate', 'API->rate_user');
$f3->route('POST /user/upload-avatar', 'API->upload_avatar');
$f3->route('POST /user/upload-creci', 'API->upload_creci');
$f3->route('POST /user/send-phone', 'API->send_phone');
$f3->route('POST /user/verify-phone', 'API->verify_phone');
$f3->route('POST /user/request-email-verification', 'API->request_email_verification');
$f3->route('POST /user/verify-email', 'API->verify_email');
$f3->route('POST /user/images', 'API->user_images');
$f3->route('POST /user/ratings', 'API->get_user_ratings');
$f3->route('POST /user/rating/reply', 'API->reply_rating');



$f3->route('GET /admin/login', 'API->admin_login');
$f3->route('GET /admin/users', 'API->admin_get_users');
$f3->route('GET /admin/users/@id', 'API->admin_get_user');
$f3->route('DELETE /admin/users/@id', 'API->admin_get_users');
$f3->route('POST /admin/users/@id', 'API->admin_update_user');



$f3->route('GET /admin/posts', 'API->admin_get_posts');
$f3->route('GET /admin/posts/@id', 'API->admin_get_post');
$f3->route('DELETE /admin/posts/@id', 'API->admin_delete_posts');
$f3->route('POST /admin/posts/@id', 'API->admin_update_posts');







$f3->set('CORS.origin', '*');
$f3->set('CORS.headers', 'authorization');

$f3->run();

?>