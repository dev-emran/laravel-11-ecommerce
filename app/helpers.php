<?php
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '৳ ' . $amount;
    }
}
