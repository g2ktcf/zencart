<?php
/**
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 May 16 Modified in v1.5.7 $
 */

  require('includes/application_top.php');
  $version_check_sysinfo = true;

  $system = zen_get_system_information();

// the following is for display later
  $sinfo =  '<div class="sysinfo wrapper">' .
         '  <div class="center"><a href="https://www.zen-cart.com"><img border="0" src="images/small_zen_logo.gif" alt=" Zen Cart "></a></div>' .
         '  <div class="center"><h2> ' . PROJECT_VERSION_NAME . ' ' . PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR . '</h2>' .
               ((PROJECT_VERSION_PATCH1 =='') ? '' : '<h3>Patch: ' . PROJECT_VERSION_PATCH1 . '::' . PROJECT_VERSION_PATCH1_SOURCE . '</h3>') .
               ((PROJECT_VERSION_PATCH2 =='') ? '' : '<h3>Patch: ' . PROJECT_VERSION_PATCH2 . '::' . PROJECT_VERSION_PATCH2_SOURCE . '</h3>') .
         '     <h2> ' . PROJECT_DATABASE_LABEL . ' ' . PROJECT_DB_VERSION_MAJOR . '.' . PROJECT_DB_VERSION_MINOR . '</h2>' .
               ((PROJECT_DB_VERSION_PATCH1 =='') ? '' : '<h3>Patch: ' . PROJECT_DB_VERSION_PATCH1 . '::' . PROJECT_DB_VERSION_PATCH1_SOURCE . '</h3>') .
               ((PROJECT_DB_VERSION_PATCH2 =='') ? '' : '<h3>Patch: ' . PROJECT_DB_VERSION_PATCH2 . '::' . PROJECT_DB_VERSION_PATCH2_SOURCE . '</h3>') ;
  $sinfo .= '  </div><div class="center">';
  $hist_query = "SELECT * from " . TABLE_PROJECT_VERSION . " WHERE project_version_key = 'Zen-Cart Main' ORDER BY project_version_date_applied DESC, project_version_major DESC, project_version_minor DESC";
  $hist_details = $db->Execute($hist_query);
      $sinfo .=  'v' . $hist_details->fields['project_version_major'] . '.' . $hist_details->fields['project_version_minor'];
      if (isset($hist_details->fields['project_version_patch']) && zen_not_null($hist_details->fields['project_version_patch'])) $sinfo .= '&nbsp;&nbsp;Patch: ' . $hist_details->fields['project_version_patch'];
      if (isset($hist_details->fields['project_version_date_applied']) && zen_not_null($hist_details->fields['project_version_date_applied'])) $sinfo .= ' &nbsp;&nbsp;[' . $hist_details->fields['project_version_date_applied'] . '] ';
      if (isset($hist_details->fields['project_version_comment']) && !empty($hist_details->fields['project_version_comment'])) $sinfo .= ' &nbsp;&nbsp;(' . $hist_details->fields['project_version_comment'] . ')';
      $sinfo .=  '<br>';
  $hist_query = "SELECT * from " . TABLE_PROJECT_VERSION_HISTORY . " WHERE project_version_key = 'Zen-Cart Main' ORDER BY project_version_date_applied DESC, project_version_major DESC, project_version_minor DESC, project_version_patch DESC";
  $hist_details = $db->Execute($hist_query);
    while (!$hist_details->EOF) {
      $sinfo .=  'v' . $hist_details->fields['project_version_major'] . '.' . $hist_details->fields['project_version_minor'];
      if (zen_not_null($hist_details->fields['project_version_patch'])) $sinfo .= '&nbsp;&nbsp;Patch: ' . $hist_details->fields['project_version_patch'];
      if (zen_not_null($hist_details->fields['project_version_date_applied'])) $sinfo .= ' &nbsp;&nbsp;[' . $hist_details->fields['project_version_date_applied'] . '] ';
      if (!empty($hist_details->fields['project_version_comment'])) $sinfo .= ' &nbsp;&nbsp;(' . $hist_details->fields['project_version_comment'] . ')';
      $sinfo .=  '<br>';
      $hist_details->MoveNext();
    }
    $sinfo .= '<br><a href="https://docs.zen-cart.com/user/first_steps/server_requirements/" rel="noopener" target="_blank">Zen Cart documentation: Server Requirements</a>';
    $sinfo .= '</div></div>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
</head>
<body class="sysinfoBody">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<!-- body_text //-->
<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
<div class="serverInfo">
      <div class="infocell"><strong><?php echo TITLE_SERVER_HOST; ?></strong> <?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE; ?></strong> <?php echo zen_output_string_protected(DB_DATABASE); ?></div>
      <div class="infocell"><strong><?php echo STORE_HOME; ?></strong> <?php echo zen_output_string_protected(DIR_FS_CATALOG); ?></div>
      <div class="infocell"><strong><?php echo TITLE_SERVER_OS; ?></strong> <?php echo $system['system'] . ' ' . $system['kernel']; ?> </div>
      <div class="infocell"><strong><?php echo TITLE_SERVER_DATE; ?></strong> <?php echo $system['date']; ?> &nbsp;</div>
      <div class="infocell"><strong><?php echo TITLE_SERVER_UP_TIME; ?></strong> <?php echo $system['uptime']; ?></div>
      <div class="infocell"><strong><?php echo TITLE_HTTP_SERVER; ?></strong> <?php echo $system['http_server']; ?></div>
      <div class="infocell"><strong><?php echo TITLE_PHP_VERSION; ?></strong> <?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></div>
      <div class="infocell"><strong><?php echo TITLE_PHP_FILE_UPLOADS; ?></strong> <?php echo ($system['php_file_uploads'] != '' && $system['php_file_uploads'] != 'off' && $system['php_file_uploads'] != '0') ? 'On' : 'Off';?></div>
      <div class="infocell"><strong><?php echo TITLE_PHP_UPLOAD_MAX;?></strong> <?php echo $system['php_uploadmaxsize'];?></div>
      <?php echo ($system['php_memlimit'] != '' ? '<div class="infocell"><strong>' . TITLE_PHP_MEMORY_LIMIT . '</strong> ' . $system['php_memlimit'] . '</div>' : ''); ?>
      <div class="infocell"><strong><?php echo TITLE_PHP_POST_MAX_SIZE; ?></strong> <?php echo $system['php_postmaxsize']; ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_ENGINE; ?></strong> <?php echo $system['db_version'] . ($system['mysql_strict_mode'] == true ? '<em> ' . TITLE_MYSQL_STRICT_MODE . '</em>' : ''); ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_HOST; ?></strong> <?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_DATE; ?></strong> <?php echo $system['db_date']; ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_DATA_SIZE; ?></strong> <?php echo number_format(($system['database_size']/1024),0); ?> kB</div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_INDEX_SIZE; ?></strong> <?php echo number_format(($system['index_size']/1024),0); ?> kB</div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_MYSQL_SLOW_LOG_STATUS; ?></strong> <?php echo $system['mysql_slow_query_log_status'] == '1' ? 'On' : 'Off'; ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_MYSQL_SLOW_LOG_FILE; ?></strong> <?php echo zen_output_string_protected($system['mysql_slow_query_log_file']); ?></div>
      <div class="infocell"><strong><?php echo TITLE_DATABASE_MYSQL_MODE; ?></strong> <?php echo $system['mysql_mode'] == '' ? '(None set)' : zen_output_string_protected(str_replace(',', ', ', $system['mysql_mode'])); ?></div>
</div>
<br class="clearBoth">
<?php echo $sinfo; ?>
<br>
<?php
$disabled_functions = ini_get('disable_functions');
if (strpos($disabled_functions,"phpinfo") === false) {
?>
<div class="phpinfo">
<?php
  if (function_exists('ob_start')) {
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();
    $regs = '';
    preg_match('/<body>(.*)<\/body>/msi', $phpinfo, $regs);
    echo $regs[1];
  } else {
    phpinfo();
  }
?>
</div>
<?php } else { ?>
<div class="phpinfo phpinfo-is-disabled"><?php echo ERROR_UNABLE_TO_DISPLAY_SERVER_INFORMATION; ?></div>
<?php } ?>
<!-- body_text_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
