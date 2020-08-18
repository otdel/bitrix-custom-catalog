<?php

namespace Oip\Util\Phone;

trait PhoneNormalizer
{
    /**
     * @param string $phone
     * @throws PhoneNormalizerException
     * @return string
     */
    public function normalize(string $phone) {
        $normalized = str_replace(["(", ")", "-", " "], "", $phone);

        if ($normalized[0] === '8') {
            $normalized = '7' . substr($normalized, 1);
        }

        if (substr($normalized, 0, 2) === "+7") {
            $normalized = '7' . substr($normalized, 2);
        }

        if (strlen($normalized) === 10) {
            $normalized = '7' . $normalized;
        }

        if (!preg_match('/^7[0-9]{10}$/', $normalized)) {
           throw new PhoneNormalizerException("Неверный формат телефона $phone");
        }

        return $normalized;
    }
}