<?php
namespace Terrazza\Component\Logger;
interface IRecordTokenReader {
    /**
     * @param array $token
     * @param string $findKey
     * @return mixed|null
     */
    public function getValue(array $token, string $findKey);
}