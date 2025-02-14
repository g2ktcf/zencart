<?php
/**
 * sanitize the GET parameters
 * see  {@link  https://docs.zen-cart.com/dev/code/init_system/} for more details.
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 May 17 Modified in v1.5.7 $
 */

use Zencart\PageLoader\PageLoader;
use Zencart\FileSystem\FileSystem;
use Zencart\Request\Request;

  if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
  }

  foreach ($_GET as $getvar) { 
     if (is_array($getvar)) { 
        $site_array_override = false;
        $zco_notifier->notify('NOTIFY_INIT_SANITIZE_GET_VAR_CHECK', ['getvarname' => $getvar], $site_array_override);
        if ($site_array_override === false) {
           zen_redirect(zen_href_link(FILENAME_DEFAULT));
        }
     }
  }
  $csrfBlackListLocal = array();
  $csrfBlackList = (isset($csrfBlackListCustom)) ? array_merge($csrfBlackListLocal, $csrfBlackListCustom) : $csrfBlackListLocal;
  if (! isset ( $_SESSION ['securityToken'] ))
  {
    $_SESSION ['securityToken'] = md5 ( uniqid ( rand (), true ) );
  }

  if (zen_is_hmac_login()) {
    if (!zen_validate_hmac_login()) {
        unset($_GET['action']);
    } else {
        $_POST['securityToken'] = $_SESSION['securityToken'];
    }
  }

  if ((isset ( $_GET ['action'] ) || isset($_POST['action']) ) && $_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $mainPage = isset($_GET['main_page']) ? $_GET['main_page'] : FILENAME_DEFAULT;
    if (!in_array($mainPage, $csrfBlackList))
    {
      if ((! isset ( $_SESSION ['securityToken'] ) || ! isset ( $_POST ['securityToken'] )) || ($_SESSION ['securityToken'] !== $_POST ['securityToken']))
      {
        zen_redirect ( zen_href_link ( FILENAME_TIME_OUT, '', $request_type ) );
      }
    }
  }
  if (isset($_GET['typefilter'])) $_GET['typefilter'] = preg_replace('/[^0-9a-zA-Z_-]/', '', $_GET['typefilter']);
  if (isset($_GET['products_id'])) $_GET['products_id'] = preg_replace('/[^0-9a-f:]/', '', $_GET['products_id']);
  if (isset($_GET['manufacturers_id'])) $_GET['manufacturers_id'] = preg_replace('/[^0-9]/', '', $_GET['manufacturers_id']);
  if (isset($_GET['categories_id'])) $_GET['categories_id'] = preg_replace('/[^0-9]/', '', $_GET['categories_id']);
  if (isset($_GET['cPath'])) $_GET['cPath'] = preg_replace('/[^0-9_]/', '', $_GET['cPath']);
  if (isset($_GET['main_page'])) $_GET['main_page'] = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['main_page']);
  if (isset($_GET['sort'])) $_GET['sort'] = preg_replace('/[^0-9a-zA-Z]/', '', $_GET['sort']);
  // if present, 'page' should always be a number because it is used for pagination and canonical URL generation
  if (isset($_GET['page'])) $_GET['page'] = (int)$_GET['page'];
  $saniGroup1 = array('action', 'addr', 'alpha_filter_id', 'alpha_filter', 'authcapt', 'chapter', 'cID', 'currency', 'debug', 'delete', 'dfrom', 'disp_order', 'dto', 'edit', 'faq_item', 'filter_id', 'goback', 'goto', 'gv_no', 'id', 'inc_subcat', 'language', 'markflow', 'music_genre_id', 'nocache', 'notify', 'number_of_uploads', 'order_id', 'order', 'override', 'page', 'pfrom', 'pid', 'pID', 'pos', 'product_id', 'products_image_large_additional', 'products_tax_class_id', 'pto', 'record_company_id', 'referer', 'reviews_id', 'search_in_description', 'set_session_login', 'token', 'tx', 'type', 'zenid', $zenSessionId);
  foreach ($saniGroup1 as $key)
  {
    if (isset($_GET[$key]))
    {
      $_GET[$key] = preg_replace('/[^\/0-9a-zA-Z_:@.-]/', '', $_GET[$key]);
      if (isset($_REQUEST[$key])) $_REQUEST[$key] = preg_replace('/[^\/0-9a-zA-Z_:@.-]/', '', $_REQUEST[$key]);
    }
  }

/**
 * process all $_GET terms
 */
  $strictReplace = '[<>\']';
  $unStrictReplace = '[<>]';
  if (isset($_GET) && count($_GET) > 0) {
    foreach($_GET as $key=>$value){
      if(is_array($value)){
        foreach($value as $key2 => $val2){
          if ($key2 == 'keyword') {
            $_GET[$key][$key2] = preg_replace('/'.$unStrictReplace.'/', '', $val2);
            if (isset($_REQUEST[$key][$key2])) $_REQUEST[$key][$key2] = preg_replace('/'.$unStrictReplace.'/', '', $val2);
          } elseif(is_array($val2)){
              foreach($val2 as $key3 => $val3){
                  $_GET[$key][$key2][$key3] = preg_replace('/'.$strictReplace.'/', '', $val3);
                  if (isset($_REQUEST[$key][$key2][$key3])) $_REQUEST[$key][$key2][$key3] = preg_replace('/'.$strictReplace.'/', '', $val3);
              }
          } else {
            $_GET[$key][$key2] = preg_replace('/'.$strictReplace.'/', '', $val2);
            if (isset($_REQUEST[$key][$key2])) $_REQUEST[$key][$key2] = preg_replace('/'.$strictReplace.'/', '', $val2);
          }
        }
      } else {
        if ($key == 'keyword') {
          $_GET[$key] = preg_replace('/'.$unStrictReplace.'/', '', $value);
          if (isset($_REQUEST[$key])) $_REQUEST[$key] = preg_replace('/'.$unStrictReplace.'/', '', $value);
        } else {
          $_GET[$key] = preg_replace('/'.$strictReplace.'/', '', $value);
          if (isset($_REQUEST[$key])) $_REQUEST[$key] = preg_replace('/'.$strictReplace.'/', '', $value);
        }
      }
    }
  }

/**
 * validate products_id for search engines and bookmarks, etc.
 */
  if (isset($_GET['products_id']) && (!isset($_SESSION['check_valid_prod']) || $_SESSION['check_valid_prod'] != false)) {
    $check_valid = zen_products_id_valid($_GET['products_id']) && !empty($_GET['main_page']);
    if (!$check_valid) {
      $_GET['main_page'] = zen_get_info_page($_GET['products_id']);
      /**
       * do not recheck redirect
       */
      $_SESSION['check_valid_prod'] = false;
      zen_redirect(zen_href_link($_GET['main_page'], 'products_id=' . $_GET['products_id']));
    }
  }

  $_SESSION['check_valid_prod'] = true;
/**
 * We do some checks here to ensure $_GET['main_page'] has a sane value
 */
  if (empty($_GET['main_page'])) $_GET['main_page'] = 'index';

  $pageLoader = PageLoader::getInstance();
  $pageLoader->init($installedPlugins, $_GET['main_page'], new FileSystem);

  $pageDir = $pageLoader->findModulePageDirectory();
  if ( $pageDir === false) {
    if (MISSING_PAGE_CHECK == 'On' || MISSING_PAGE_CHECK == 'true') {
      zen_redirect(zen_href_link(FILENAME_DEFAULT));
    } elseif (MISSING_PAGE_CHECK == 'Page Not Found') {
      header('HTTP/1.1 404 Not Found');
      zen_redirect(zen_href_link(FILENAME_PAGE_NOT_FOUND));
    }
  }

  $current_page = $_GET['main_page'];
  $current_page_base = $current_page;
  $code_page_directory = $pageDir;
  $page_directory = $code_page_directory;

$sanitizedRequest = Request::capture();
