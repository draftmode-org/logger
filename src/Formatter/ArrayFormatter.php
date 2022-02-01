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

    private function _formatRecord(array &$response, array &$token, array $format) {
        foreach ($format as $responseKey => $responseFormat) {
            if (is_numeric($responseKey)) {
                $tokenKeys                          = explode("?", $responseFormat);
                $tokenKey                           = array_shift($tokenKeys);
                $tokenDefault                       = array_shift($tokenKeys);
                if ($tokenValue = $this->recordTokenReader->getValue($token, $tokenKey)) {
                    $response[$tokenKey]		    = $this->normalizer->convertTokenValue($tokenKey, $tokenValue);
                } elseif ($tokenDefault) {
                    $response[$tokenKey]            = $tokenDefault;
                }
            } else {
                $hasTokenValue                      = false;
                $tokenValue                         = preg_replace_callback("/{(.*?)}/", function ($matches) use (&$token, &$hasTokenValue) {
                    $tokenKeys                      = explode("?", $matches[1]);
                    $tokenKey                       = array_shift($tokenKeys);
                    $tokenDefault                   = array_shift($tokenKeys);
                    if ($tokenValue = $this->recordTokenReader->getValue($token, $tokenKey)) {
                        if ($normalized = $this->normalizer->convertTokenValue($tokenKey, $tokenValue)) {
                            $hasTokenValue          = true;
                            return $normalized;
                        }
                    } elseif ($tokenDefault) {
                        $hasTokenValue              = true;
                        return $tokenDefault;
                    }
                    return $matches[1];
                }, $responseFormat);
                if ($hasTokenValue) {
                    $response[$responseKey]         = $tokenValue;
                }
            }
        }
    }

    /**
     * @param Record $record
     * @return string
     */
    public function formatRecord(Record $record) : string {
        $token										= $record->getToken();
        $response                                   = [];
        $this->_formatRecord($response, $token, $this->format);
        return $this->normalizer->convertLine($response);
    }
}