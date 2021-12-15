<?php
namespace Terrazza\Component\Logger\Normalizer;
trait NormalizerTrait {
    private string $tokenKeyPattern="%tokenKey";

    /**
     * @param mixed $value
     * @return string|null
     */
    private function convertValueToString($value) :?string {
        if (is_null($value)) {
            return null;
        } elseif (is_bool($value)) {
            return $value;
        }
        elseif (is_scalar($value)) {
            return $value;
        }
        return $this->jsonEncode($value);
    }

    /**
     * @param string $tokenKey
     * @param string $responseFormat
     * @return string
     */
    private function extendTokenKey(string $tokenKey, string $responseFormat) : string {
        return strtr($responseFormat, [$this->tokenKeyPattern => $tokenKey]);
    }

    /**
     * @param object|array $value
     * @return string
     */
    private function jsonEncode($value) : string {
        $json                                       = @json_encode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "#jsonEncodeError:".json_last_error()."#";
        }
        return $json;
    }
}