<?php

namespace App\Utils;

use Carbon\Carbon;
use DateException;

class Dates
{
    const UTC = "Y-m-d";
    const UTC_TIME = "Y-m-d H:i:s";
    const PTBR = "d/m/Y";
    const PTBR_TIME = "d/m/Y H:i:s";
    const PTBR_DATEH = "d/m/Y H:i";
    const TZ = "Y-m-d\TH:i:s.000000\Z";

    /**
     * Returns the current UTC datetime value
     * @return string
     */
    public static function nowUTC(): string
    {
        return Carbon::now('UTC')->toDateTimeString();
    }

    /**
     * Returns the current PT_BR datetime value
     * @return string
     */
    public static function nowPTBR(): string
    {
        return Carbon::now('UTC')->format(self::PTBR_TIME);
    }

    /**
     * Returns the current date with a given format
     * @param string $format Defines the format, example **dd/mm/YY**
     * @return string or FALSE in order to invalid formats
     */
    public static function nowWithFormat(string $format): string
    {
        return Carbon::now('UTC')->format($format);
    }

    /**
     * Returns the future PT_BR datetime value
     * @param int $hour Define numb hours to add in date
     */
    public static function futurePTBR(int $hour): string
    {
        $dataHora = Carbon::now();
        $dataHora->addHours($hour);
        return $dataHora->format(self::PTBR_TIME);
    }

    /**
     * Converts a UTC date string to PTBR format
     *
     * @param string $utcDate Defines the date in the UTC format
     *
     * @return string
     */
    public static function utcToPtbr(string $utcDate): string
    {
        if (!Carbon::canBeCreatedFromFormat($utcDate, self::UTC)) {
            throw new DateException("The given date does not match the UTC format");
        }

        return Carbon::createFromFormat(self::UTC, $utcDate)
            ->format(self::PTBR);
    }

    /**
     * Converts a PTRB date string to UTC format
     *
     * @param string $ptbrDate Defines the date in the PTBR format
     *
     * @return string
     */
    public static function ptbrToUtc(string $ptbrDate): string
    {
        if (!Carbon::canBeCreatedFromFormat($ptbrDate, self::PTBR)) {
            throw new DateException("The given date does not match the PTBR format");
        }

        return Carbon::createFromFormat(self::PTBR, $ptbrDate)
            ->format(self::UTC);
    }

    /**
     * Converts a date string from a given format to other
     *
     * @param ?string $date Defines the date string, if null, then null will be returned
     *
     * @param string $from Defines the **$date** current format
     *
     * @param string $to Defines the expected **$date** format
     *
     * @return ?string
     */
    public static function convert(?string $date, string $from, string $to): ?string
    {
        if ($date == null || empty($date) || $date === 'null') {
            return null;
        }

        if (!Carbon::canBeCreatedFromFormat($date, $from)) {
            \Log::info($date);
            throw new DateException("The given date does not match the given format");
        }

        return Carbon::createFromFormat($from, $date)->format($to);

    }
}
