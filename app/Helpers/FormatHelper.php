<?php

if (!function_exists('formatRupiahSingkat')) {
    function formatRupiahSingkat($nilai)
    {
        if ($nilai >= 1000000) {
            return number_format($nilai / 1000000, ($nilai % 1000000 == 0 ? 0 : 1)) . 'jt';
        } elseif ($nilai >= 1000) {
            return number_format($nilai / 1000, ($nilai % 1000 == 0 ? 0 : 1)) . 'K';
        }
        return $nilai;
    }
}
