<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\FormatterInterface;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\NormalizerInterface;

class ArrayFormatter implements FormatterInterface {
    use FormatterTrait;
    private array $format;
    private NormalizerInterface $normalizer;
    private string $logDateFormat;

    public function __construct(string $logDateFormat, NormalizerInterface $normalizer, array $format=null) {
        $this->normalizer 							= $normalizer;
        $this->logDateFormat 						= $logDateFormat;
        $this->format                               = $format ?? [];
    }

    /**
     * @param array $format
     * @return FormatterInterface
     */
    public function withFormat($format) : FormatterInterface {
        if (!is_array($format)) {
            throw new FormatterException("format type expected array, given ".gettype($format));
        }
        $formatter                                  = clone $this;
        $formatter->format                          = $format;
        return $formatter;
    }

    /**
     * @param Record $record
     * @return string
     */
    public function formatRecord(Record $record) : string {
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
                $response[$responseKey]             = $this->normalizer->convertTokenValues($responseKey, $tokenValues, $responseFormat);
            }
        }
        return $this->normalizer->convertLine($response);
    }
}