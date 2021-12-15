<?php
namespace Terrazza\Component\Logger\Normalizer;
use Terrazza\Component\Logger\NormalizerInterface;

class NormalizeJson implements NormalizerInterface {
    use NormalizerTrait;
    const DEFAULT_ENCODE_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR;
    private int $encodeFlags;
    public function __construct(?int $encodeFlags=null) {
        $this->encodeFlags 							= $encodeFlags ?: self::DEFAULT_ENCODE_FLAGS;
    }

    /**
     * @param string $tokenKey
     * @param string|null $responseFormat
     * @param mixed $tokenValue
     * @return mixed
     */
    public function convertTokenValue(string $tokenKey, $tokenValue, ?string $responseFormat=null) {
        if (is_array($tokenValue)) {
            return $tokenValue;
        } else {
            $value 									= $this->convertValueToString($tokenValue);
            if ($responseFormat) {
                return sprintf($responseFormat, $value);
            } else {
                return $value;
            }
        }
    }

    /**
     * @param string $tokenKey
     * @param array $tokenValues
     * @param string|null $responseFormat
     * @return mixed
     */
    public function convertTokenValues(string $tokenKey, array $tokenValues, ?string $responseFormat = null) {
        if (count($tokenValues) > 1) {
            return $tokenValues;
        } else {
            return array_shift($tokenValues);
        }
    }

    /**
     * @param array $response
     * @return string
     */
    public function convertLine(array $response) : string {
        return json_encode($response, $this->encodeFlags);
    }
}