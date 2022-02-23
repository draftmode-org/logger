<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\Converter\INonScalarConverter;
use Terrazza\Component\Logger\IRecordFormatter;
use Terrazza\Component\Logger\IRecordValueConverter;
use Terrazza\Component\Logger\Record;

class ArrayFormatter implements IRecordFormatter {
    private INonScalarConverter $nonScalarConverter;
    private array $format;
    private array $valueConverter=[];
    private CONST typeSafe = "t-y-p-e-s-a-v-e";
    private CONST defaultChar = "?";

    public function __construct(INonScalarConverter $nonScalarConverter, array $format=null) {
        $this->nonScalarConverter 					= $nonScalarConverter;
        $this->format 								= $format;
    }

    /**
     * @param array $format
     * @return IRecordFormatter
     */
    public function withFormat(array $format) : IRecordFormatter {
        $formatter                                  = clone $this;
        $formatter->format                          = $format;
        return $formatter;
    }

    /**
     * @param string $tokenKey
     * @param IRecordValueConverter $valueConverter
     */
    public function pushConverter(string $tokenKey, IRecordValueConverter $valueConverter) : void {
        $this->valueConverter[$tokenKey]            = $valueConverter;
    }

    /**
     * @param Record $record
     * @return array
     */
    public function formatRecord(Record $record) : array {
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
        //
        // extract context for tokens
        //
        $context 									= $tokens["Context"];
        unset($tokens["Context"]);

        //
        foreach ($this->format as $fKey => $sText) {
            if (is_numeric($fKey)) {
                $fKey                               = $sText;
                $sText                              = "{" . $sText . "}";
            }
            $nonScalar								= null;
            $rows[$fKey] 							= preg_replace_callback("/{(.*?)}/", function ($match) use ($tokens, &$context, &$nonScalar, $sText) {
                $fText 								= $match[0];
                $tKey 								= $match[1];
                if ($tKey == "Context") {
                    return $fText;
                }
                if (strpos($tKey, "Context.") === 0) {
                    $cKeys							= explode(".", $tKey);
                    $dataKey						= array_pop($cKeys);
                    $data							= &$context;
                } else {
                    $data							= $tokens;
                    $dataKey 						= $tKey;
                }
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
        //
        // handle context
        //
        if (count($context)) foreach ($rows as $fKey => $sText) {
            $nonScalar								= null;
            $rows[$fKey] 							= preg_replace_callback("/{Context}/", function ($match) use ($context, &$nonScalar, $sText) {
                $fText 								= $match[0];
                $tKey 								= strtr($fText, ["{" => "", "}" => ""]);
                if ($fText === $sText) {
                    $nonScalar						= $context;
                    return self::typeSafe;
                } else {
                    return $this->nonScalarConverter->getValue($tKey, $context);
                }
            }, $sText);
            if ($nonScalar) {
                $nonScalars[$fKey]					= $nonScalar;
            }
        }
        $rows 										= array_filter($rows);
        //
        // replace nonScalars
        //
        foreach ($nonScalars as $fKey => $fValue) {
            if (array_key_exists($fKey, $rows)) {
                $rows[$fKey] 						= $fValue;
            }
        }
        return $rows;
    }
}