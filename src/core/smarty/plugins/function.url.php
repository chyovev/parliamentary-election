<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.url.php
 * Type:     function
 * Name:     url
 * Purpose:  generate url using Router
 * -------------------------------------------------------------
 */

function smarty_function_url($params, &$smarty) {
    return Router::url($params);
}
?>