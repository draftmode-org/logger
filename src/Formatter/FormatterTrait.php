<?php
namespace Terrazza\Component\Logger\Formatter;
use Throwable;
trait FormatterTrait {
    /**
     * @param array $token
     * @param string $findKey
     * @return mixed|null
     */
    private function getTokenValue(array $token, string $findKey) {
        $tokenKeys                                  = explode(".", $findKey);
        while (count($tokenKeys)) {
            $tokenKey = array_shift($tokenKeys);
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
            if ($token instanceof Throwable) {
                $traceMessageMax                    = array_shift($tokenKeys);
                $traceMessageArgs                   = array_shift($tokenKeys);
                $this->traceMessageMax              = $traceMessageMax ?? 0;
                $this->traceMessageArgs             = (bool)$traceMessageArgs;
                $this->convertException($token);
                return $this->traceMessage;
            }
        }
        return $token;
    }

    private int $traceMessageMax                    = 0;
    private bool $traceMessageArgs                  = false;
    private array $traceMessage                     = [];

    /**
     * @param array $traceMessage
     * @return bool
     */
    private function pushMessage(array $traceMessage) : bool {
        if (!$this->traceMessageMax || $this->traceMessageMax > count($this->traceMessage)) {
            array_push($this->traceMessage, $traceMessage);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $trace
     * @param $iTrace
     * @return array|string[]
     */
    private function getTraceMessage(array $trace, $iTrace) : array {
        $line                                       = "-";
        $file                                       = "-";
        if (array_key_exists($iTrace, $trace)) {
            $line                                   = $trace[$iTrace]["line"] ?? "-";
            $file                                   = $trace[$iTrace]["file"] ?? "-";
        }
        $function                                   = "-";
        $class                                      = "-";
        if (array_key_exists($iTrace+1, $trace)) {
            $function                               = $trace[$iTrace+1]["function"] ?? "-";
            $class                                  = $trace[$iTrace+1]["class"] ?? "-";
        }
        $message = [
            "line"                                  => $line,
            "function"                              => $function,
            "class"                                 => $class,
            "file"                                  => $file,
        ];
        if ($this->traceMessageArgs && array_key_exists("args", $trace[$iTrace])) {
            $message["args"]                        = $trace[$iTrace]["args"];
        }
        return $message;
    }

    /**
     * @param Throwable $exception
     * @param Throwable|null $parent
     * @return void
     */
    private function convertException(Throwable $exception, Throwable $parent=null) : void {
        $trace                                      = $exception->getTrace();
        for ($iTrace = 0; $iTrace < count($trace); $iTrace=$iTrace+2) {
            $message                                = $this->getTraceMessage($trace, $iTrace);
            if ($iTrace === 0) {
                $message                            = array_merge(["message" => $exception->getMessage()], $message);
            }
            if (!$this->pushMessage($message)) break;
            if ($previous = $exception->getPrevious()) {
                $this->convertException($previous, $exception);
                $pTrace                             = $previous->getTrace();
                $message = [
                    "message"                       => $previous->getMessage(),
                    "line"                          => $previous->getLine(),
                    "function"                      => $pTrace[0]["function"] ?? "-",
                    "class"                         => $pTrace[0]["class"] ?? "-",
                ];
                if ($this->traceMessageArgs && array_key_exists("args", $pTrace[0])) {
                    $message["args"]                = $pTrace[0]["args"];
                }
                if (!$this->pushMessage($message)) break;
                return;
            }
            if ($parent) return;
        }
    }

}