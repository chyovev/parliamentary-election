<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.url_full.php
 * Type:     function
 * Name:     url
 * Purpose:  generate full url including host using Router
 * -------------------------------------------------------------
 */

function smarty_function_url_full($params, &$smarty) {
    return Router::url($params, true);
}
?>