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

    /**
     * @param array $token
     * @param string $findKey
     * @return mixed|null
     */
    public function getValue(array $token, string $findKey) {
        $tokenKeys                                  = explode(".", $findKey);
        $usedKeys                                   = [];
        while (count($tokenKeys)) {
            $tokenKey                               = array_shift($tokenKeys);
            if (is_array($token)) {
                if (array_key_exists($tokenKey, $token)) {
                    $token                          = $token[$tokenKey];
                } elseif ($tokenKey === "*") {
                    return $token;
                } else {
                    return null;
                }
            } else {
                return null;
            }
            $usedKeys[]                             = $tokenKey;
            $usedKey                                = join(".", $usedKeys);
            if ($valueConverter = $this->getValueConverter($usedKey)) {
                return $valueConverter->getValue($token);
            }
        }
        return $token;
    }
}