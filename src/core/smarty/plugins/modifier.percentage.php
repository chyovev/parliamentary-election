<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.percentage.php
 * Type:     modifier
 * Name:     percentage
 * Purpose:  transforms a number as a percentage
 * -------------------------------------------------------------
 */

function smarty_modifier_percentage($number, $decimals = 2, $decimal_separator = '.', $stripTrailingZeroes = true)
{
    $number = number_format($number, $decimals, $decimal_separator, ' ');

    if ($stripTrailingZeroes) {
        $number = preg_replace('/(,0+)$/', '', $number);
    }

    return $number;
}
?>