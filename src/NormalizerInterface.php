<?php
namespace Terrazza\Component\Logger;

interface NormalizerInterface {
    public function convertTokenValue(string $tokenKey, $tokenValue, ?string $responseFormat=null);
    public function convertLine(array $response) : string;
}