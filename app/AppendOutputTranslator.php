<?php

namespace App;

/**
 * Converts the raw values returned by the USADATA API into a format more
 * suitable for insertion into CRMs.
 *
 * Right now, the only supported translator is for booleans, but we might add
 * more in the future.
 */
class AppendOutputTranslator
{

    static public function translate($value, string $tranlator)
    {

        $method = 'translate' . ucfirst($translator);

        if (!method_exists(self::class, $method)) {
            throw new \Exception('Invalid translator: ' . $translator);
        }

        $value = self::$method($value);
        return $value;

    }

    static protected function translateYesno($value): string
    {

        if ($value === true || $value === 1 || $value === '1') {
            $value = 'Yes';
        } elseif ($value === false || $value === 0 || $value === '0') {
            $value = 'No';
        } else {
            $value = 'Unknown';
        }

        return $value;

    }

}
