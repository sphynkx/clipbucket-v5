<?php
define('THIS_PAGE', 'photos');
define('PARENT_PAGE', 'photos');

require 'includes/config.inc.php';

if( !isSectionEnabled('photos') ){
    redirect_to(BASEURL);
}

pages::getInstance()->page_redir();
userquery::getInstance()->perm_check('view_photos', true);

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page, config('photo_main_list'));
$params = Photo::getInstance()->getFilterParams($_GET['sort'], []);
$params = Photo::getInstance()->getFilterParams($_GET['time'], $params);
$params['limit'] = $get_limit;
$photos = Photo::getInstance()->getAll($params);
assign('photos', $photos);

if( empty($photos) ){
    $count = 0;
} else if( count($photos) < config('photo_main_list') && $page == 1 ){
    $count = count($photos);
} else {
    unset($params['limit']);
    $params['count'] = true;
    $count = Photo::getInstance()->getAll($params);
}

$total_pages = count_pages($count, config('photo_main_list'));

//Pagination
pages::getInstance()->paginate($total_pages, $page);

subtitle(lang('photos'));
//Displaying The Template
template_files('photos.html');
display_it();
