<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\IFormatter;
use Terrazza\Component\Logger\IRecordTokenReader;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\INormalizer;

class ArrayFormatter implements IFormatter {
    private array $format;
    private IRecordTokenReader $recordTokenReader;
    private INormalizer $normalizer;

    public function __construct(IRecordTokenReader $recordTokenReader, INormalizer $normalizer, array $format=null) {
        $this->recordTokenReader 					= $recordTokenReader;
        $this->normalizer 							= $normalizer;
        $this->format                               = $format ?? [];
    }

    /**
     * @param array $format
     * @return IFormatter
     */
    public function withFormat($format) : IFormatter {
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
        $token										= $record->getToken();
        $response 									= [];
        foreach ($this->format as $responseKey => $responseFormat) {
            if (is_numeric($responseKey)) {
                $responseKey 						= $responseFormat;
                if ($tokenValue = $this->recordTokenReader->getValue($token, $responseKey)) {
                    $response[$responseKey]		    = $this->normalizer->convertTokenValue($responseKey, $tokenValue);
                }
            } else {
                $tokenValues                        = [];
                $responseFormat                     = preg_replace_callback("/{(.*?)}/", function ($matches) use ($token, &$tokenValues) {
                    $tokenKey                       = $matches[1];
                    if ($tokenValue = $this->recordTokenReader->getValue($token, $tokenKey)) {
                        $tokenValues[$tokenKey]     = $tokenValue;
                    }
                }, $responseFormat);
                $response[$responseKey]             = $this->normalizer->convertTokenValues($responseKey, $tokenValues, $responseFormat);
            }
        }
        return $this->normalizer->convertLine($response);
    }
}