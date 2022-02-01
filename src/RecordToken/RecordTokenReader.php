<?php
namespace Terrazza\Component\Logger\RecordToken;
use Terrazza\Component\Logger\IRecordTokenReader;
use Terrazza\Component\Logger\IRecordTokenValueConverter;

class RecordTokenReader implements IRecordTokenReader {
    /**
     * @var IRecordTokenValueConverter[]
     */
    private array $valueConverter                   = [];
    public function __construct(array $valueConverter=null) {
        foreach ($valueConverter ?? [] as $tokenKey => $converter) {
            if ($converter instanceof IRecordTokenValueConverter) {
                $this->pushValueConverter($tokenKey, $converter);
            } else {
                throw new RecordTokenReaderException($converter . " has to be an instance of ".IRecordTokenValueConverter::class);
            }
        }
    }

    /**
     * @param string $tokenKey
     * @param IRecordTokenValueConverter $valueConverter
     */
    public function pushValueConverter(string $tokenKey, IRecordTokenValueConverter $valueConverter) : void {
        $this->valueConverter[$tokenKey]            = $valueConverter;
    }

    /**
     * @param string $findKey
     * @return IRecordTokenValueConverter|null
     */
    private function getValueConverter(string $findKey) :?IRecordTokenValueConverter {
        if (array_key_exists($findKey, $this->valueConverter)) {
            return $this->valueConverter[$findKey];
        }
        return null;
    }

    private function deleteTokenKey(array &$array, array $keys) {
        $key                                        = array_shift($keys);
        if (count($keys) == 0) {
            unset($array[$key]);
        }
        else {
            $this->deleteTokenKey($array[$key], $keys);
        }
    }

    /**
     * @param array $token
     * @param string $findKey
     * @return mixed|null
     */
    public function getValue(array &$token, string $findKey) {
        $tokenKeys                                  = explode(".", $findKey);
        $usedKeys                                   = [];
        $useTokenKey                                = null;
        $data                                       = $token;
        while (count($tokenKeys)) {
            $tokenKey                               = array_shift($tokenKeys);
            if (is_array($data)) {
                if (array_key_exists($tokenKey, $data)) {
                    $data                           = $data[$tokenKey];
                    $usedKeys[]                     = $tokenKey;
                    $useTokenKey                    = $tokenKey;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
        if ($useTokenKey) {
            //
            // clean key from token
            //
            if (count($usedKeys) > 1) {
                $this->deleteTokenKey($token, $usedKeys);
            }
            //
            // use, if exists, value converter
            //
            $usedKey                                = join(".", $usedKeys);
            if ($valueConverter = $this->getValueConverter($usedKey)) {
                return $valueConverter->getValue($data);
            } else {
                return $data;
            }
        } else {
            return null;
        }
    }
}