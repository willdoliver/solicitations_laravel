<?php

if (!function_exists('formatDateTime')) {
    /**
     * Format a DateTime object with a -3 hour offset (Brasilia Time).
     *
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    function formatDateTimeToLocal(\DateTimeInterface $dateTime): string
    {
        $dateTime->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        return $dateTime->format('Y-m-d_H:i:s');
    }

}
