<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.number.php
 * Type:     modifier
 * Name:     number
 * Purpose:  displays number in a beautiful format
 * -------------------------------------------------------------
 */

function smarty_modifier_number($number, $decimals = 0, $decimal_separator = ',')
{
    return number_format($number, $decimals, $decimal_separator, ' ');
}
?>