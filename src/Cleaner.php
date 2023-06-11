<?php

namespace App;

class Cleaner
{
    const PHONE_REGEX = '/(?<prefix>\+[0-9]{2})?(?<number>[0-9]+)/';
    public function cleanPhone(string $number): string
    {
        $number = str_replace(['.', ' ', '-'], '', $number);
        preg_match(self::PHONE_REGEX, $number, $matches);
        if (empty($matches)) {
            return $number;
        }

        $number = '';
        if (!empty($matches['prefix']) && $matches['prefix'] !== '+33') {
            $number .= sprintf('(%s) ', $matches['prefix']);
        }
        if (!empty($matches['number'])) {
            if (strlen($matches['number']) === 9) {
                $matches['number'] = '0' . $matches['number'];
            }
            $number .= implode('.', str_split($matches['number'], 2));
        }
        return $number;
    }
}
