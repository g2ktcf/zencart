<?php
/**
 * Module Template
 *
 * Loaded automatically by index.php?main_page=products_new.
 * Displays listing of New Products
 *
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2019 Dec 12 Modified in v1.5.7 $
 */
?>
<table class="products-listing productsNewListing">
          <tr>
            <td colspan="3"><hr></td>
          </tr>
<?php
  $group_id = zen_get_configuration_key_value('PRODUCT_NEW_LIST_GROUP_ID');

  if ($products_new_split->number_of_rows > 0) {
    $products_new = $db->Execute($products_new_split->sql_query);
    require DIR_WS_CLASSES . 'ProductConfigurationSwitch.php'; 
    while (!$products_new->EOF) {
      // Rebuild switch object only if not set or different prod type
      if (!isset($config_switches)) { 
        $config_switches = new ProductConfigurationSwitch($products_new->fields['products_id']);
      } else {
         if ($products_new->fields['products_type'] != $config_switches->getProductsType()) {
          $config_switches = new ProductConfigurationSwitch($products_new->fields['products_id']);
         } 
      }
      $flag_show_model = $config_switches->getSwitch('model');
      $flag_show_weight = $config_switches->getSwitch('weight');
      $flag_show_quantity = $config_switches->getSwitch('quantity');
      $flag_show_date_added = $config_switches->getSwitch('date_added');
      $flag_show_manufacturer = $config_switches->getSwitch('manufacturer');
      $flag_show_free_shipping_image = $config_switches->getSwitch('ALWAYS_FREE_SHIPPING_IMAGE_SWITCH');

      if (PRODUCT_NEW_LIST_IMAGE != '0') {
        if ($products_new->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) {
          $display_products_image = str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_IMAGE, 3, 1));
        } else {
          $display_products_image = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $products_new->fields['products_image'], $products_new->fields['products_name'], IMAGE_PRODUCT_NEW_LISTING_WIDTH, IMAGE_PRODUCT_NEW_LISTING_HEIGHT) . '</a>' . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_IMAGE, 3, 1));
        }
      } else {
        $display_products_image = '';
      }

      if (PRODUCT_NEW_LIST_NAME != '0') {
        $display_products_name = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '"><strong>' . $products_new->fields['products_name'] . '</strong></a>' . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_NAME, 3, 1));
      } else {
        $display_products_name = '';
      }

      if (PRODUCT_NEW_LIST_MODEL != '0' and $flag_show_model) {
        $display_products_model = TEXT_PRODUCT_MODEL . $products_new->fields['products_model'] . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_MODEL, 3, 1));
      } else {
        $display_products_model = '';
      }

      if (PRODUCT_NEW_LIST_WEIGHT != '0' and $flag_show_weight) {
        $display_products_weight = '<br>' . TEXT_PRODUCTS_WEIGHT . $products_new->fields['products_weight'] . TEXT_SHIPPING_WEIGHT . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_WEIGHT, 3, 1));
      } else {
        $display_products_weight = '';
      }

      if (PRODUCT_NEW_LIST_QUANTITY != '0' and $flag_show_quantity) {
        if ($products_new->fields['products_quantity'] <= 0) {
          $display_products_quantity = TEXT_OUT_OF_STOCK . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_QUANTITY, 3, 1));
        } else {
          $display_products_quantity = TEXT_PRODUCTS_QUANTITY . $products_new->fields['products_quantity'] . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_QUANTITY, 3, 1));
        }
      } else {
        $display_products_quantity = '';
      }

      if (PRODUCT_NEW_LIST_DATE_ADDED != '0' and $flag_show_date_added) {
        $display_products_date_added = TEXT_DATE_ADDED . ' ' . zen_date_long($products_new->fields['products_date_added']) . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_DATE_ADDED, 3, 1));
      } else {
        $display_products_date_added = '';
      }

      if (PRODUCT_NEW_LIST_MANUFACTURER != '0' and $flag_show_manufacturer) {
        $display_products_manufacturers_name = ($products_new->fields['manufacturers_name'] != '' ? TEXT_MANUFACTURER . ' ' . $products_new->fields['manufacturers_name'] . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_MANUFACTURER, 3, 1)) : '');
      } else {
        $display_products_manufacturers_name = '';
      }

      if ((PRODUCT_NEW_LIST_PRICE != '0' and zen_get_products_allow_add_to_cart($products_new->fields['products_id']) == 'Y') and zen_check_show_prices() == true) {
        $products_price = zen_get_products_display_price($products_new->fields['products_id']);
        $display_products_price = TEXT_PRICE . ' ' . $products_price . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_LIST_PRICE, 3, 1)) . ($flag_show_free_shipping_image ? (zen_get_product_is_always_free_shipping($products_new->fields['products_id']) ? TEXT_PRODUCT_FREE_SHIPPING_ICON . '<br>' : '') : '');
      } else {
        $display_products_price = '';
      }

// more info in place of buy now
      if (PRODUCT_NEW_BUY_NOW != '0' and zen_get_products_allow_add_to_cart($products_new->fields['products_id']) == 'Y') {
        if (zen_has_product_attributes($products_new->fields['products_id'])) {
          $link = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
        } else {
//          $link= '<a href="' . zen_href_link(FILENAME_PRODUCTS_NEW, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT) . '</a>';
          if (PRODUCT_NEW_LISTING_MULTIPLE_ADD_TO_CART > 0 && $products_new->fields['products_qty_box_status'] != 0) {
//            $how_many++;
            $link = TEXT_PRODUCT_NEW_LISTING_MULTIPLE_ADD_TO_CART . '<input type="text" name="products_id[' . $products_new->fields['products_id'] . ']" value="0" size="4" aria-label="' . ARIA_QTY_ADD_TO_CART . '">';
          } else {
            $link = '<a href="' . zen_href_link(FILENAME_PRODUCTS_NEW, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT) . '</a>&nbsp;';
          }
        }

        $the_button = $link;
        $products_link = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
        $display_products_button = zen_get_buy_now_button($products_new->fields['products_id'], $the_button, $products_link) . '<br>' . zen_get_products_quantity_min_units_display($products_new->fields['products_id']) . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_BUY_NOW, 3, 1));
      } else {
        $link = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
        $the_button = $link;
        $products_link = '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
        $display_products_button = zen_get_buy_now_button($products_new->fields['products_id'], $the_button, $products_link) . '<br>' . zen_get_products_quantity_min_units_display($products_new->fields['products_id']) . str_repeat('<br class="clearBoth">', substr(PRODUCT_NEW_BUY_NOW, 3, 1));
      }

      if (PRODUCT_NEW_LIST_DESCRIPTION > '0') {
        $disp_text = zen_get_products_description($products_new->fields['products_id']);
        $disp_text = zen_clean_html($disp_text);

        $display_products_description = stripslashes(zen_trunc_string($disp_text, PRODUCT_NEW_LIST_DESCRIPTION, '<a href="' . zen_href_link(zen_get_info_page($products_new->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($products_new->fields['master_categories_id']) . '&products_id=' . $products_new->fields['products_id']) . '"> ' . MORE_INFO_TEXT . '</a>'));
      } else {
        $display_products_description = '';
      }

?>
          <tr>
            <td width="<?php echo IMAGE_PRODUCT_NEW_LISTING_WIDTH + 10; ?>" valign="top" class="main" align="center">
              <?php
                $disp_sort_order = $db->Execute("select configuration_key, configuration_value from " . TABLE_CONFIGURATION . " where configuration_group_id='" . $group_id . "' and (configuration_value >= 1000 and configuration_value <= 1999) order by LPAD(configuration_value,11,0)");
                while (!$disp_sort_order->EOF) {
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_IMAGE') {
                    echo $display_products_image;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_QUANTITY') {
                    echo $display_products_quantity;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_BUY_NOW') {
                    echo $display_products_button;
                  }

                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_NAME') {
                    echo $display_products_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MODEL') {
                    echo $display_products_model;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MANUFACTURER') {
                    echo $display_products_manufacturers_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_PRICE') {
                    echo $display_products_price;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_WEIGHT') {
                    echo $display_products_weight;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_DATE_ADDED') {
                    echo $display_products_date_added;
                  }
                  $disp_sort_order->MoveNext();
                }
              ?>
            </td>
            <td colspan="2" valign="top" class="main">
              <?php
                $disp_sort_order = $db->Execute("select configuration_key, configuration_value from " . TABLE_CONFIGURATION . " where configuration_group_id='" . $group_id . "' and (configuration_value >= 2000 and configuration_value <= 2999) order by LPAD(configuration_value,11,0)");
                while (!$disp_sort_order->EOF) {
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_IMAGE') {
                    echo $display_products_image;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_QUANTITY') {
                    echo $display_products_quantity;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_BUY_NOW') {
                    echo $display_products_button;
                  }

                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_NAME') {
                    echo $display_products_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MODEL') {
                    echo $display_products_model;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MANUFACTURER') {
                    echo $display_products_manufacturers_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_PRICE') {
                    echo $display_products_price;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_WEIGHT') {
                    echo $display_products_weight;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_DATE_ADDED') {
                    echo $display_products_date_added;
                  }
                  $disp_sort_order->MoveNext();
                }
              ?>
            </td>
          </tr>
<?php if (PRODUCT_NEW_LIST_DESCRIPTION > '0') { ?>
          <tr>
            <td colspan="3" valign="top" class="main">
              <?php
                echo $display_products_description;
              ?>
            </td>
          </tr>
<?php } ?>

          <tr>
            <td colspan="3"><hr></td>
          </tr>
<?php
      $products_new->MoveNext();
    }
  } else {
?>
          <tr>
            <td class="main" colspan="2"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
          </tr>
<?php
  }
?>
</table>
