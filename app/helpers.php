<?php
if (!function_exists('phone_to_number')) {
    /**
     * 9832585992
     * @param string $phone
     * @return int
     */
    function phone_to_number($phone)
    {
        return substr(preg_replace('/[^0-9]/', '', $phone), 1);
    }
}
if (!function_exists('phone_to_string')) {
    /**
     * +7 (983) 258 59 92
     * @param int $phone
     * @return string|null
     */
    function phone_to_string($phone)
    {
        if (!is_int($phone) || strlen($phone) !== 10) {
            return null;
        }
        return preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '+7 ($1) $2 $3 $4', $phone);
    }
}
if (!function_exists('auth_code_generator')) {
    /**
     * Generating a random 6 digit number
     * @return string
     * @throws \Exception
     */
    function auth_code_generator(): string
    {
        return (string)random_int(100000, 999999);
    }
}

