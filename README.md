# Terrazza/Logger
This component is an implementation of PSR/Log standard with some extensions.

## _Structure_
1. the [Logger](#object-logger)<br>
has to be initialized with 0-n [ChannelHandler](#object-channel-handler)<br>
and provides the common known methods:
   - warning
   - error
   - notice
   - ...
2. the [ChannelHandler](#object-channel-handler)<br>
is responsible for the target/writer and the recordFormatter configuration<br>
every [ChannelHandler](#object-channel-handler) can have 0-n [LogHandler](#object-log-handler)
3. the [LogHandler](#object-log-handler)<br>
determines
   - the logLevel
   - the format (optional, default: from ChannelHandler)

## _Object/Classes_

1. [Logger](#object-logger)
   1. [method: registerChannelHandler](#object-logger-registerChannelHandler)
   2. [method: getChannelHandler](#object-logger-getChannelHandler)
   3. [method: pushLogHandler](#object-logger-pushLogHandler)
   4. [method: registerExceptionHandler](#object-logger-registerExceptionHandler)
   5. [method: registerErrorHandler](#object-logger-registerErrorHandler)
   6. [method: registerFatalHandler](#object-logger-registerFatalHandler)
   7. [method: setExceptionFileName](#object-logger-setExceptionFileName)
   8. [constructor: context (array)](#object-logger-constructor-context)
2. Handler
   1. [ChannelHandler](#object-channel-handler)
   2. [LogHandler](#object-log-handler)
3. [LogRecord](#object-record)
4. [LogRecordFormatter](#object-log-record-formatter)
5. [LogHandlerFilter](#object-log-handler-filter)
6. [Converter](#object-converter)
7. [Install](#install)
8. [Requirements](#require)
9. [Examples](#examples)

<a id="object-logger" name="object-logger"></a>
<a id="user-content-object-logger" name="user-content-object-logger"></a>
### Logger
The Logger object/methods is/are close to the common PSR implementations but!<br>
The Logger is initialized with channelHandler(s) and not Handler<br>
>each channelHandler executes only one logHandler

<a id="object-logger-registerChannelHandler" name="object-logger-registerChannelHandler"></a>
<a id="user-content-object-logger-registerChannelHandler" name="user-content-object-logger-registerChannelHandler"></a>
#### method: registerChannelHandler
adds a channelHandler to logger (immutable).<br>
>notice<br>
The channel.name is used as a unique identifier for the channel collection.

<a id="object-logger-getChannelHandler" name="object-logger-getChannelHandler"></a>
<a id="user-content-object-logger-getChannelHandler" name="user-content-object-logger-getChannelHandler"></a>
#### method: getChannelHandler
returns a [ChannelHandler](#object-channel-handler) by name, if already registered.

<a id="object-logger-pushLogHandler" name="object-logger-pushLogHandler"></a>
<a id="user-content-object-logger-pushLogHandler" name="user-content-object-logger-pushLogHandler"></a>
#### method: pushLogHandler
adds a [LogHandler](#object-log-handler) to a given [ChannelHandler](#object-channel-handler) (byName).<br>
Throws an Exception if given channel.name is not registered.

<a id="object-logger-registerExceptionHandler" name="object-logger-registerExceptionHandler"></a>
<a id="user-content-object-logger-registerExceptionHandler" name="user-content-object-logger-registerExceptionHandler"></a>
#### method: registerExceptionHandler
register a callback for php exception handler.<br>
>well-developed projects should handle/cover all exceptions by themselves, but ;-)

<a id="object-logger-registerErrorHandler" name="object-logger-registerErrorHandler"></a>
<a id="user-content-object-logger-registerErrorHandler" name="user-content-object-logger-registerErrorHandler"></a>
#### method: registerErrorHandler
register a callback for php error handler.<br>
>sometime this kind of errors can't be catched without this workaround

<a id="object-logger-registerFatalHandler" name="object-logger-registerFatalHandler"></a>
<a id="user-content-object-logger-registerFatalHandler" name="user-content-object-logger-registerFatalHandler"></a>
#### method: registerFatalHandler
register a callback for php shutdown.<br>
>sometime this kind of errors can't be catched without this workaround

<a id="object-logger-setExceptionFileName" name="object-logger-setExceptionFileName"></a>
<a id="user-content-object-logger-setExceptionFileName" name="user-content-object-logger-setExceptionFileName"></a>
#### method: setExceptionFileName
The method:addMessage itself is covered with try/catch.<br>
The catch handler writes the Exception.Message to a file which can be set with the method setExceptionFileName.<br>
>notice:<br>
default: php://stderr

<a id="object-logger-constructor-context" name="object-logger-constructor-context"></a>
<a id="user-content-object-logger-constructor-context" name="user-content-object-logger-constructor-context"></a>
#### constructor: context (array)
the logger can be initialized, next to the name, with an initialized context.<br>
This context will be merged with the e.g. error(message, ["key" => "value"]).<br>

<i>example of usage</i><br>
a class is injected with the component and inside the constructor<br>
```
$logger = new Logger("name", ["user" => "user"]);
$logger->notice("hello", ["my" => "value"]);

/* Record.Context will now include both: 
- the pushed context for the message
- the initialzed context
[
"user" => "user", 
"my" => "value"
]
*/
```
### Handler
<a id="object-channel-handler" name="object-channel-handler"></a>
<a id="user-content-object-channel-handler" name="user-content-object-channel-handler"></a>
#### ChannelHandler
A ChannelHandler collect [LogHandler](#object-log-handler) to the same channel and provides
- the same writer
- the same formatter<br>
for each [LogHandler](#object-log-handler).

A ChannelHandler can be registered through the [Logger](#object-logger) with [method: registerChannelHandler](#object-logger-registerChannelHandler).

##### method: getChannel (ChannelInterface)
##### method: getLogHandler (array)
##### method: pushLogHandler
Method to add a new [LogHandler](#object-log-handler). After pushing a new [LogHandler](#object-log-handler) logHandler-array will be key-sorted.<br>
This key-sort is important to prevent multiple write transaction for different LogLevels.

##### method: getEffectedHandler
return the matched [LogHandler](#object-log-handler) for a given LogRecord.

##### method: writeRecord
for a passed [LogHandler](#object-log-handler) the record will be
- formatted
- and written to the Writer

<a id="object-log-handler" name="object-log-handler"></a>
<a id="user-content-object-log-handler" name="user-content-object-log-handler"></a>
#### LogHandler
The SingleHandler provides the common way to create a handler for a Logger.
The only difference to the common implementation:
- instead of logLevel
- the SingleHandler has to be injected within a [Channel](#object-channel)

<a id="object-record" name="object-record"></a>
<a id="user-content-object-record" name="user-content-object-record"></a>
### LogRecord
Against the common PSR implementation our component deals with an object and not an array.<br>
LogRecord properties:
- logDate (\Datetime)
- loggerName (string)
- logLevel (int)
- logMessage (string)
- context (array)
- memUsed (int)
- memAllocated (int)

additional, and optional properties:
- traceNamespace
- traceMethod
- traceLine

#### method/static createRecord
this method is used inside <i>Logger</i> to create a new LogRecord object.

#### method: getToken()
this method is used in the <i>LogRecordFormatterInterface</i> to get the LogRecord "encoded".
Every element can be accessed through his "format" e.g. {Level}{LevelName}{Context.name}<br>

```
return [
  'Date'         => $this->getLogDate(),
  'Level'        => $this->getLogLevel(),
  'LevelName'    => $this->getLogLevelName(),
  'LoggerName'   => $this->getLoggerName(),
  'Namespace'    => $this->getNamespace(),
  'sNamespace'   => $this->getNamespace() ? basename($this->getNamespace()) : null,
  'Method'       => $this->getMethod(),
  'sMethod'      => $this->getMethod() ? basename($this->getMethod()) : null,
  'MemUsed'      => $this->getMemUsed(),
  'MemAllocated' => $this->getMemAllocated(),
  'Message'      => $this->getLogMessage(),
  'Context'      => $this->getContext(),
]
```
<a id="object-log-record-formatter" name="object-log-record-formatter"></a>
<a id="user-content-object-log-record-formatter" name="user-content-object-log-record-formatter"></a>
### LogRecordFormatter
The LogRecordFormatter converts/maps a record to an array<br>.
Initialized properties:
- NonScalarConverterInterface
- format (array)
<br>
``["Date", "Message"]``

#### NonScalarConverter (NonScalarConverterInterface)
The NonScalarConverter convert a nonScalar value (e.g. from Context) into a string.<br>
Actually the provided class _NonScalarJsonEncode_ use json_encode and prefix it with the attribute name.<br>
<br>
The NonScalarConverter is used, when a formatter-line includes a nonScalar and a scalar content.<br>

_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonConverter;

$record = ["message" => "myMessage", "key" => ["value1", "value2"]];
echo (new NonScalarJsonEncode())->getValue($context["key"]); // key:{"value1", "value2"}

// in context of the formatter it will be
$format = ["{Message}:{Context.key}"];                 // ... myMessage:key:{"value1", "value2"}
$format = ["Context" => "{Message}:{Context.key}"];    // ... myMessage:key:{"value1", "value2"}
```
a ValueConverter (LogRecordValueConverterInterface) can be used to convert special value based on his key.

#### method: withFormat
Returns a new instance of the formatter but with a different format.<br>
<br>
_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
use Terrazza\Component\Logger\Formatter\LogRecordFormatter;

$nonScalar = new NonScalarJsonEncode();
$formatter = new LogRecordFormatter($nonScalar, ["Message" => "{Message}"];
$nformatter = $formatter->withFormat(["Message" => "{Message}:{Date}"]);
```

#### method: pushConverter
to map/convert a special value, based on his key, push a special converter.<br>
This converter has to fulfill LogRecordValueConverterInterface. 

#### method: formatRecord
Maps the Record against the $format and returns a mapped array.<br>
Unknown patterns (e.g. {undefined}) are removed from the response.<br>
Empty "Lines" are also removed.

##### example<br>
The example uses an additional ValueConverter for the Record value "Date".
````
use DateTime;
use Terrazza\Component\Logger\LogRecordValueConverterInterface;
use Terrazza\Component\Logger\LOgRecord;
use Terrazza\Component\Logger\Formatter\LogRecordFormatter;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;

class RecordTokenValueDate implements LogRecordValueConverterInterface {
    private string $dateFormat;
    public function __construct(string $dateFormat="Y-m-d H:i:s.u") {
        $this->dateFormat                           = $dateFormat;
    }
    public function getValue($value) {
        return $value->format($this->dateFormat);
    }
}

$formatter = new LogRecordFormatter(
   new NonScalarJsonEncode,
   ["Date", "Message"]
);
$formatter->pushConverter("Date", new RecordTokenValueDate);   

$record  = LogRecord::create("LoggerName", 100, "myMessage");
var_dump($formatter->formatRecord($record)); 
/*
[
   "Date" => 2022-12-31 23:59:01,
   "Message" => "myMessage"
]
*/
````
<a id="object-log-handler-filter" name="object-log-handler-filter"></a>
<a id="user-content-object-log-handler-filter" name="user-content-object-log-handler-filter"></a>
### LogHandlerFilter
[LogHandler](object-log-handler) can have a LogHandlerFilter.<br>
Properties:
- include (array, optional)
- exclude (array, optional)
- start (array, optional)
#### method: isHandling (string $callerNamespace) : bool
**include**<br>
preg_match callerNamespace against include patterns<br>
**exclude**<br>
preg_match callerNamespace against exclude patterns<br>
**start**<br>
preg_match callerNamespace against start patterns<br>
if preg_match is true all further isHandling will be true.
_(exclude filter overrules start)_

<a id="object-converter" name="object-converter"></a>
<a id="user-content-object-converter" name="user-content-object-converter"></a>
### Converter
A [LogRecord](#object-record) will be first converted/mapped with the [RecordFormatter](#object-formatter)<br>
Afterwards, it depends on the target/writer the array has to be formatted again.<br><br> 
We actually include/provide two Converter:
- convert to a string, json type
- convert to a string, e.g. for console logging
In any case the Converter is injected into the [Writer](#object-writer-stream).

#### FormattedRecordFlat
Converts the mapped LogRecord into a string within a delimiter for each row.<br>
For nonScalar values we use json_encode to convert the value.<br>
##### method: setNonScalarPrefix(string $delimiter)
by using this method nonScalar values will be prefixed with the dataKey and the delimiter.<br>
arguments:
- delimiter (string, required)
- encodingFlags (int, optional)

_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordFlat;

$formatter = new FormattedRecordFlat("|",0);
echo $formatter->convert(["message" => "myMessage", "context" => ["k" => "v"]); 
//myMessage|{"k":"v"}

$formatter->setNonScalarPrefix(":");
echo $formatter->convert(["message" => "myMessage", "context" => ["k" => "v"]); 
//myMessage|context:{"k":"v"}
```

#### FormattedRecordJson
Converts the mapped LogRecord into a string by using json_encode.<br>
arguments:
- encodingFlags (int, optional)

_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordJson;

$formatter = new FormattedRecordJson(0);
echo $formatter->convert(["message" => "myMessage", "context" => ["k" => "v"]); 
//{"message" : "myMessage", "context": {"k":"v"}}
```
### Writer

<a id="object-writer-stream-file" name="object-writer-stream-file"></a>
<a id="user-content-object-writer-stream-file" name="user-content-object-writer-stream-file"></a>
#### StreamFile
Save converted record to a file.<br>
arguments:
- converter (IFormattedRecordConverter, required
- filename (string required)
- flags (int, optional, default: 0)

>notice:<br>
The Converter should convert the formatted LogRecord into a string. 
 
```
use Terrazza\Component\Logger\Writer\StreamFile;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordFlat;

$logFile    = "log.txt";
@unlink($logFile);
$converter  = new FormattedRecordFlat("|",0);
$writer     = new StreamFile($converter, $logFile);
$writer->write(["message" => "myMessage", "context" => ["k" => "v"]);

$logContent = file_get_contents($logFile);
echo $logContent;
//{"message" : "myMessage", "context": {"k":"v"}}
```

<a id="install" name="install"></a>
<a id="user-content-install" name="user-content-install"></a>
## How to install
### Install via composer
```
composer require terrazza/logger
```
<a id="require" name="require"></a>
<a id="user-content-require" name="user-content-require"></a>
## Requirements
- php >= 7.4
### composer packages
- psr/log

<a id="examples" name="examples"/></a>
<a id="user-content-examples" name="user-content-examples"/></a>
## Examples
### Channel
#### setup a channel
```
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordFlat;
use Terrazza\Component\Logger\Writer\StreamFile;
use Terrazza\Component\Logger\Formatter\RecordFormatter;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
use Terrazza\Component\Logger\Channel;

$writeConverter   = new FormattedRecordFlat("|",0); 
$writer           = new StreamFile($writeConverter, "test.log");
$formatter        = new RecordFormatter(new NonScalarJsonEncode(), [
   "Message" => "{Level}-{Message}-{Context.pid}"
]); 
$channel          = new Channel("channelName", $writer, $formatter);
```
### SingleHandler
#### setup a handler
>notice<br>
next code lines depends on previous example... (setup a channel)
```
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\Logger;

$handler          = new SingleHandler(Logger::WARNING, $channel);
```
### Create Logger
#### create within a handler
>notice:<br>
next code lines depends on previous example... (setup a handler)
```
use Terrazza\Component\Logger\Logger;

// additinal we initialize the Context with pid = getmypid
// the formatter uses {Context.pid} and will print it

$logger           = new Logger("loggerName", ["pid" => "myPID"], $handler);
$logger->error($message = "message");
```
#### create and push a handler
```
use Terrazza\Component\Logger\Logger;

$logger           = new Logger("loggerName", ["pid" => getmypid()]);
$logger           = $logger->withHandler($handler);
$logger->error($message = "message");
```
### Usage of Logger
#### produce a NOTICE
```
$logger->notice("myMessage");
// by using our examples above this message will not be printed
// ...cause the logLevel for the Handler is WARNING
```

#### produce an ERROR
```
$logger->error("myMessage"); 
// output to file will be: 400-myMessage-myPID
```