<?php
namespace Terrazza\Component\Logger\Formatter;

trait FormatterTrait {
    /**
     * @param array $token
     * @param string $findKey
     * @return array|mixed|null
     */
    private function getTokenValue(array $token, string $findKey) {
        foreach (explode(".", $findKey) as $tokenKey) {
            if (array_key_exists($tokenKey, $token)) {
                $token								= $token[$tokenKey];
            } else {
                return null;
            }
        }
        return $token;
    }
}