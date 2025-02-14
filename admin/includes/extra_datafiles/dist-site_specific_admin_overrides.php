<?php
/**
 * A collection of site-specific overrides for the admin operation.
 *
 * There are some features in the base Zen Cart processing that can be overridden for a specific
 * site, as identified in this module.
 *
 * For use on YOUR site, make a copy of this file (which has all entries commented-out) to /admin/includes/extra_datafiles/site_specific_admin_overrides.php
 * and make your edits there.  Otherwise, your overrides might get "lost" on a future Zen Cart upgrade.
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 Apr 30 Modified in v1.5.8 $
 */
// -----
// The flag show_attrib_images is used by the files 
// admin/invoice.php and admin/packingslip.php 
// to determine whether attribute images should be displayed. 
//
// true ...... attribute images are shown on invoice and packingslip (the default) 
// false ..... attribute images are not shown on invoice and packingslip 
// $show_attrib_images = false;

// The flag show_product_images is used by the files 
// admin/invoice.php and admin/packingslip.php 
// to determine whether product images should be displayed. 
//
// true ...... product images are shown on invoice and packingslip (the default) 
// false ..... product images are not shown on invoice and packingslip 
// $show_product_images = false;

// The flag show_product_tax is used by the file 
// admin/invoice.php 
// to determine whether product tax should be shown.
//
// true ...... product tax is shown on invoice (the default) 
// false ..... product tax is not shown on invoice 
// $show_product_tax = false;

// Identify whether the zcDate class' (added in Zen Cart 1.5.8) debug-output is initially enabled.
//
// true ...... The zcDate debug is enabled.
// false ..... The zcDate debug is disabled (the default).
//
//$zen_date_debug = false;
