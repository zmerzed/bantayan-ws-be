<?php

namespace Kolette\Auth\Support;

use Illuminate\Support\Str;

trait ValidatesPhone
{
    /**
     * Checks through all validation methods to verify it is in a
     * phone number format of some type
     */
    private function isPhone(?string $value): bool
    {
        if (!$value) {
            return false;
        }

        return $this->isE164($value) || $this->isNANP($value) || $this->isDigits($value);
    }

    /**
     * Format example 5555555555, 15555555555
     * @param string $value The phone number to check
     * @return boolean is it correct format?
     */
    private function isDigits(string $value): bool
    {
        $conditions = [];
        $conditions[] = strlen($value) >= 10;
        $conditions[] = strlen($value) <= 16;
        $conditions[] = preg_match("/[^\d]/i", $value) === 0;
        return (bool)array_product($conditions);
    }

    /**
     * Format example +22 555 555 1234, (607) 555 1234, (022607) 555 1234
     * @param string $value The phone number to check
     * @return bool is it correct format?
     */
    private function isE123(string $value): bool
    {
        return preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $value) === 1;
    }

    /**
     * Format example +15555555555
     * @param string $value The phone number to check
     * @return bool is it correct format?
     */
    private function isE164(string $value): bool
    {
        $conditions = [];
        $conditions[] = strpos($value, "+") === 0;
        $conditions[] = strlen($value) >= 9;
        $conditions[] = strlen($value) <= 16;
        $conditions[] = preg_match("/[^\d+]/i", $value) === 0;
        return (bool)array_product($conditions);
    }

    /**
     * Format examples: (555) 555-5555, 1 (555) 555-5555, 1-555-555-5555, 555-555-5555, 1 555 555-5555
     * https://en.wikipedia.org/wiki/National_conventions_for_writing_telephone_numbers#United_States.2C_Canada.2C_and_other_NANP_countries
     * @param string $value The phone number to check
     * @return bool is it correct format?
     */
    private function isNANP(string $value): bool
    {
        $conditions = [];
        $conditions[] = preg_match("/^(?:\+1|1)?\s?-?\(?\d{3}\)?(\s|-)?\d{3}-\d{4}$/i", $value) > 0;
        return (bool)array_product($conditions);
    }

    /**
     * Remove plus (+) sign for phone numbers
     */
    public function cleanPhoneNumber(?string $value): string
    {
        if (!$value) {
            return $value;
        }

        return Str::cleanPhoneNumber($value);
    }

    /**
     * Attache the plus (+) sign that was remove for phone numbers
     */
    public function uncleanPhoneNumber(?string $value): string
    {
        // We will clean it first before appending to avoid duplicate + sign.
        return '+' . $this->cleanPhoneNumber($value);
    }
}
