<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\FormatterInterface;
use Terrazza\Component\Logger\LogRecord;
use Terrazza\Component\Logger\NormalizerInterface;

class ArrayFormatter implements FormatterInterface {
    use FormatterTrait;
    CONST DEFAULT_FORMAT 							= "%s";
    private array $format;
    private NormalizerInterface $normalizer;
    private string $logDateFormat;

    public function __construct(array $format, string $logDateFormat, NormalizerInterface $normalizer) {
        $this->format 								= $format;
        $this->normalizer 							= $normalizer;
        $this->logDateFormat 						= $logDateFormat;
    }

    /**
     * @param LogRecord $record
     * @return string
     */
    public function format(LogRecord $record) : string {
        $token										= $record->getToken($this->logDateFormat);
        $response 									= [];
        foreach ($this->format as $responseKey => $responseFormat) {
            if (is_numeric($responseKey)) {
                $responseKey 						= $responseFormat;
                if ($tokenValue = $this->getTokenValue($token, $responseKey)) {
                    $response[$responseKey]		    = $this->normalizer->convertTokenValue($responseKey, $tokenValue);
                }
            } else {
                $tokenValues                        = [];
                $responseFormat                     = preg_replace_callback("/{(.*?)}/", function ($matches) use ($token, &$tokenValues) {
                    $tokenKey                       = $matches[1];
                    if ($tokenValue = $this->getTokenValue($token, $tokenKey)) {
                        $tokenValues[$tokenKey]     = $tokenValue;
                    }
                }, $responseFormat);
                //$response[$responseKey]             = sprintf($responseFormat, ...$tokenValues);
                $response[$responseKey]             = $this->normalizer->convertTokenValue($responseKey, $tokenValues, $responseFormat);
            }
        }
        return $this->normalizer->convertLine($response);
    }
}