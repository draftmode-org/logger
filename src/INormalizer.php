<?php
namespace Terrazza\Component\Logger;

interface INormalizer {
    /**
     * @param string $tokenKey
     * @param $tokenValue
     * @param string|null $responseFormat
     * @return mixed
     */
    public function convertTokenValue(string $tokenKey, $tokenValue, ?string $responseFormat=null);
    /**
     * @param string $tokenKey
     * @param array $tokenValues
     * @param string|null $responseFormat
     */
    //public function convertTokenValues(string $tokenKey, array $tokenValues, ?string $responseFormat=null);

    /**
     * @param array $response
     * @return string
     */
    public function convertLine(array $response) : string;
}