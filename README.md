# the logger component
This component is an implementation of PSR/Log standard with some extensions.

1. Object/Classes
   1. [Logger](#object-logger) 
      1. [method: withHandler](#object-logger-withhandler)
      2. [Context](#object-logger-context)
         1. method: hasContextKey
         2. method: getContextByKey
   2. [Record](#object-record)
   3. [LoggerFilter](#object-logger-filter)
   4. [Formatter](#object-formatter)
   5. Writer
      1. [StreamFile](#object-writer-stream-file)
   6. Handler
      1. [ChannelHandler](#object-channel-handler)
      2. [SingleHandler](#object-single-handler)
   7. [Converter](#object-converter)
3. [Install](#install)
4. [Requirements](#require)
5. [Examples](#examples)

## Object/Classes

<a id="object-logger" name="object-logger"></a>
<a id="user-content-object-logger" name="user-content-object-logger"></a>
### Logger
The Logger object provides 3 more properties than the common PSR implementations.
- context

<a id="object-logger-withhandler" name="object-logger-withhandler"></a>
<a id="user-content-object-logger-withhandler" name="user-content-object-logger-withhandler"></a>
#### method: withHandler
fulfill the same as pushHandler, but as immutable

<a id="object-logger-context" name="object-logger-context"></a>
<a id="user-content-object-logger-context" name="user-content-object-logger-context"></a>
#### context
the logger can be initialized, next to the name, with an initialized context.<br>
the context will be merged with the e.g. error(message, ["key" => "value"]).<br>

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

##### method: hasContextKey
validate if given key exists in the initialized context.<br>
with a DOT inside the key you can walk through the array.<br>
```
$logger = new Logger("name", ["user" => ["name" => "Max"]]);
$logger->hasContextKey("user"); // true
$logger->hasContextKey("email"); // false
$logger->hasContextKey("user.name"); // true
$logger->hasContextKey("user.email"); // false
```
##### method: getContextValue
returns the initialized context value by a given key.<br>
with a DOT inside the key you can walk through the array.<br>
```
$logger = new Logger("name", ["user" => ["name" => "Max"]]);
$logger->getContextByKey("user"); // ["name" => "Max"]
$logger->getContextByKey("email"); // null
$logger->getContextByKey("user.name"); // Max
$logger->getContextByKey("user.email"); // null
```

<a id="object-record" name="object-record"></a>
<a id="user-content-object-record" name="user-content-object-record"></a>
### Record
Against the common PSR implementation our component deals with an object and not an array.<br>
LogRecord properties:
- Date (\Datetime)
- Level (int)
- LevelName (string)
- LoggerName (string) 
- MemUsed (int)
- MemAllocated (int)
- Message (string)
- Context (array)

#### method/static createRecord
this method is used inside <i>Logger</i> to create a new LogRecord object.

#### method: getToken()
this method is used in the <i>Formatter</i> to get the record "encoded".
Every element can be accessed through the "format" e.g. {Level}{LevelName}{Context.name}<br>

**Namespace and sNamespace**:<br>
(s)Namespace returns/includes the callerNamespace.<br>

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
<a id="object-logger-filter" name="object-logger-filter"></a>
<a id="user-content-object-logger-filter" name="user-content-object-logger-filter"></a>
### LoggerFilter
[ChannelHandler](object-channel-handler) and [SingleHandler](object-single-handler) can have a LoggerFilter.<br>
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

<a id="object-formatter" name="object-formatter"></a>
<a id="user-content-object-formatter" name="user-content-object-formatter"></a>
### RecordFormatter
The RecordFormatter converts/maps a record to an array<br>
- NonScalarConverter
- format (array)
<br>
``["Date", "Message"]``

#### NonScalarConverter (INonScalarConverter)
The NonScalarConverter convert a nonScalar value (e.g. from Context) into a string.<br>
Actually the provided class _NonScalarJsonEncode_ use json_encode and prefix it with the attribute name.<br>
<br>
The NonScalarConverter is used, when a formatter-line includes a nonScalar and a scalar content.<br>

_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;

$record = ["message" => "myMessage", "key" => ["value1", "value2"]];
echo (new NonScalarJsonEncode())->getValue($context["key"]); // key:{"value1", "value2"}

// in context of the formatter it will be
$format = ["{Message}:{Context.key}"];                 // ... myMessage:key:{"value1", "value2"}
$format = ["Context" => "{Message}:{Context.key}"];    // ... myMessage:key:{"value1", "value2"}
```

A ValueConverter (IRecordTokenValueConverter) will be used to convert a value based on a key.
#### method: withFormat
Returns a new instance of the formatter but with a different format.<br>
<br>
_example of usage_:<br>
```
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
use Terrazza\Component\Logger\Formatter\RecordFormatter;

$nonScalar = new NonScalarJsonEncode();
$formatter = new RecordFormatter($nonScalar, ["Message" => "{Message}"];
$nformatter = $formatter->withFormat(["Message" => "{Message}:{Date}"]);
```
#### method: formatRecord
Maps the Record against the $format and returns a mapped array.<br>
Unknown patterns (e.g. {undefined}) are removed from the response.<br>
Empty "Lines" are also removed.

##### example<br>
The example uses an additional ValueConverter for the Record value "Date".
````
use DateTime;
use Terrazza\Component\Logger\IRecordValueConverter;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\Formatter\RecordFormatter;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;

class RecordTokenValueDate implements IRecordValueConverter {
    private string $dateFormat;
    public function __construct(string $dateFormat="Y-m-d H:i:s.u") {
        $this->dateFormat                           = $dateFormat;
    }
    public function getValue($value) {
        return $value->format($this->dateFormat);
    }
}

$formatter = new RecordFormatter(
   new NonScalarJsonEncode,
   ["Date", "Message"]
);
$formatter->pushConverter("Date", new RecordTokenValueDate);   

$record  = Record::create("LoggerName", 100, "myMessage");
var_dump($formatter->formatRecord($record)); 
/*
[
   "Date" => 2022-12-31 23:59:01,
   "Message" => "myMessage"
]
*/
````

### Handler
There are two different types of handler provided. In any case a handler is the interactor for the logger to write a record.

<a id="object-channel-handler" name="object-channel-handler"></a>
<a id="user-content-object-channel-handler" name="user-content-object-channel-handler"></a>
#### ChannelHandler
the ChannelHandler allows us to collect handler to the same channel. He takes care about, that every handler has 
- the same formatter
- the same writer
To use the channelHandler as a Logger itself he implements the IHandler-Methods, too.

##### method: pushHandler
Method to add a new Handler for a given LogLevel and format.

##### method: isHandling
Business logic to validate if any handler has to be used for a given Record<br>

##### method: writeRecord
all Handlers pushed to this channel will be used ($handler->writeRecord), if the handler::isHandling matches.

<a id="object-single-handler" name="object-single-handler"></a>
<a id="user-content-object-single-handler" name="user-content-object-single-handler"></a>
#### SingleHandler
The SingleHandler provides the common way to create a handler for a Logger.
The only difference to the common implementation: 
- instead of logLevel
- the SingleHandler has to be injected within a [Channel](#object-channel)

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

_notice:<br>
The Converter should convert the formatted LogRecord into a string._<br> 
 
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
### php version
- \>= 7.4
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
_notice:<br>
next code lines depends on previous example... (setup a channel)_
```
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\Logger;

$handler          = new SingleHandler(Logger::WARNING, $channel);
```
### Create Logger
#### create within a handler
_notice:<br>
next code lines depends on previous example... (setup a handler)_
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