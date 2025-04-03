<?php

if (!function_exists('formatDMY')) {
    function formatDMY($date)
    {
        return $date ? \Carbon\Carbon::parse($date)->format('d.m.Y') : '';
    }
}
