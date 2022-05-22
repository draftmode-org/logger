<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\Converter\NonScalarConverterInterface;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\LogRecordValueConverterInterface;
use Terrazza\Component\Logger\Record\LogRecord;

class LogRecordFormatter implements LogRecordFormatterInterface {
    private NonScalarConverterInterface $nonScalarConverter;
    private array $format;
    private array $valueConverter=[];
    private CONST typeSafe = "t-y-p-e-s-a-v-e";
    private CONST defaultChar = "?";

    public function __construct(NonScalarConverterInterface $nonScalarConverter, array $format) {
        $this->nonScalarConverter 					= $nonScalarConverter;
        $this->format 								= $format;
    }

    /**
     * @param array $format
     * @return LogRecordFormatterInterface
     */
    public function withFormat(array $format) : LogRecordFormatterInterface {
        $formatter                                  = clone $this;
        $formatter->format                          = $format;
        return $formatter;
    }

    /**
     * @param string $tokenKey
     * @param LogRecordValueConverterInterface $valueConverter
     */
    public function pushConverter(string $tokenKey, LogRecordValueConverterInterface $valueConverter) : void {
        $this->valueConverter[$tokenKey]            = $valueConverter;
    }

    /**
     * @param LogRecord $record
     * @return array
     */
    public function formatRecord(LogRecord $record) : array {
        $token										= $record->getToken();
        return $this->formatTokens($token);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    private function convertValue(string $key, $value) {
        if ($converter = $this->valueConverter[$key] ?? null) {
            return $converter->getValue($value);
        } else {
            return $value;
        }
    }

    /**
     * @param array $tokens
     * @return array
     */
    private function formatTokens(array $tokens) : array {
        $rows 										= [];
        $nonScalars									= [];

        // move special context areas into separate array
        // to allow printing "not printed" values
        // e.g. {Date} {Context.a} {Context}
        // context = ["a" => 1, "b" => 2]
        // ...print context[a] => 1 and for {Context} print ["b" => 2"]
        $specialTokenKeys                           = ["Context", "iContext", "Trace"];
        $specialTokens                              = [];
        foreach ($specialTokenKeys as $tokenKey) {
            $specialTokens[$tokenKey]               = $tokens[$tokenKey] ?? [];
            unset($tokens[$tokenKey]);
        }

        //
        $specialTokensKey                           = "(".join("|", $specialTokenKeys).")";
        foreach ($this->format as $fKey => $sText) {
            $nonScalar								= null;
            $rows[$fKey]    					    = preg_replace_callback("/{(.*?)}/", function ($match) use ($tokens, &$specialTokens, $specialTokensKey, &$nonScalar, $sText) {
                $fText 								= $match[0];
                $tKey 								= $match[1];

                $dataKey                            = $tKey;
                $data							    = $tokens;

                // handle special tokens without any subKey later
                if (preg_match("/^$specialTokensKey\$/", $tKey)) {
                    return $fText;
                }

                // handle special token with subKeys
                // ...set dataKey based
                if (preg_match("/^$specialTokensKey\.(.*)/", $tKey, $matches)) {
                    $specialTokenKey                = $matches[1];
                    $data                           = &$specialTokens[$specialTokenKey];
                    $dataKey                        = $matches[2];
                }

                // tKey in dataKey
                if (array_key_exists($dataKey, $data)) {
                    $value 							= $this->convertValue($tKey, $data[$dataKey]);
                    unset($data[$dataKey]);

                    if ($fText === $sText) {
                        $nonScalar				    = $value;
                        return self::typeSafe;
                    }

                    if (!is_scalar($value) && !is_null($value)) {
                        $value                      = $this->nonScalarConverter->getValue($tKey, $value);
                    }
                    return $value;
                } elseif (strpos($dataKey, self::defaultChar)) {
                    list(,$value)                   = explode(self::defaultChar, $dataKey);
                    return $value;
                }
                return "";
            }, $sText);
            if ($nonScalar) {
                $nonScalars[$fKey]					= $nonScalar;
            }
        }
        $rows 										= array_filter($rows);

        // handle specialTokens to push the not printed arguments
        $specialTokensKey                           = "({".join("}|{", $specialTokenKeys)."})";
        foreach ($rows as $fKey => $sText) {
            $nonScalar                              = null;
            $rows[$fKey]                            = preg_replace_callback("/$specialTokensKey/", function ($matches) use ($specialTokens, &$nonScalar ,$sText) {
                $fText                              = $matches[0];
                $tKey                               = strtr($fText, ["{" => "", "}" => ""]);
                $data                               = $specialTokens[$tKey] ?? [];

                // replace specialToken with empty if no more content given
                if (count($data) === 0) {
                    return "";
                }
                // keep data as noScalar
                if ($fText === $sText) {
                    $nonScalar				        = $data;
                    return self::typeSafe;
                } else {
                    // return data
                    return $this->nonScalarConverter->getValue($tKey, $data);
                }
            }, $sText);
            if ($nonScalar) {
                $nonScalars[$fKey]					= $nonScalar;
            }
        }
        $rows 										= array_filter($rows);

        // replace nonScalars values
        foreach ($nonScalars as $fKey => $fValue) {
            if (array_key_exists($fKey, $rows)) {
                $rows[$fKey] 						= $fValue;
            }
        }
        return $rows;
    }
}