<?php

namespace Terrazza\Component\Logger\Normalizer;

use Terrazza\Component\Logger\NormalizerInterface;

class NormalizeFlat implements NormalizerInterface {
    use NormalizerTrait;
    private string $delimiter;
    public function __construct(string $delimiter) {
        $this->delimiter 						= $delimiter;
    }

    /**
     * @param string $tokenKey
     * @param string|null $responseFormat
     * @param mixed $tokenValue
     * @return mixed
     */
    public function convertTokenValue(string $tokenKey, $tokenValue, ?string $responseFormat=null) {
        $value 									= $this->convertValueToString($tokenValue);
        if ($responseFormat) {
            $responseFormat                     = $this->extendTokenKey($tokenKey, $responseFormat);
            return sprintf($responseFormat, $value);
        } else {
            return $value;
        }
    }

    /**
     * @param array $response
     * @return string
     */
    public function convertLine(array $response) : string {
        return join($this->delimiter, $response);
    }
}