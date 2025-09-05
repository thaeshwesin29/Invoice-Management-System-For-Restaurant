<?php

function translate($str_en, $str_mm, $locale = null)
{
    if (is_null($locale)){
        $locale = app()->getLocale();
    }

    if ($locale == 'en') {
        return $str_en;
    } else if ($locale == 'mm') {
        return $str_mm;
    }

    return $str_en;
}
