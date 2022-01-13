# the logger component
This component is an implementation of PSR/Log standard with some extensions.

1. Object/Classes
   1. [Logger](#object-logger) 
      1. [method: withHandler](#object-logger-withhandler)
      2. [method: withNamespace](#object-logger-withnamespace)
      3. [method: withMethod](#object-logger-withmethod)
      4. [Context](#object-logger-context)
         1. method: hasContextKey
         2. method: getContextByKey
   2. [Record](#object-record)
   3. [Channel](#object-channel)
   4. [Formatter](#object-formatter)
   5. Writer
      1. [StreamWriter](object-writer-stream)
      2. [HtmlWriter](#object-writer-html)
   6. Handler
      1. [ChannelHandler](#object-channel-handler)
      2. [SingleHandler](#object-single-handler)
      3. [HandlerPattern](#object-handler-pattern)
   7. [Normalizer](#object-normalizer)
3. [Install](#install)
4. [Requirements](#require)
5. [Examples](#examples)

<a id="object-logger" name="object-logger"></a>
<a id="user-content-object-logger" name="user-content-object-logger"></a>
## Object/Classes
### Logger
The Logger object provides 3 more properties than the common PSR implementations.
- namespace
- method
- context
<a id="object-logger-withhandler" name="object-logger-withhandler"></a>
<a id="user-content-object-logger-withhandler" name="user-content-object-logger-withhandler"></a>
#### method: withHandler
fulfill the same as pushHandler, but is immutable
<a id="object-logger-withnamespace" name="object-logger-withnamespace"></a>
<a id="user-content-object-logger-withnamespace" name="user-content-object-logger-withnamespace"></a>
#### method: withNamespace
this method set the property immutable.
the property itself is forwarded to the RECORD object and can be printed through the formatter with
- Namespace (full namespace)
- sNamesace (with the basename of the given namespace)

<i>example of usage</i><br>
a class is injected with the component and inside the constructor<br>
```
$this->logger = $logger->withNamespace(__NAMESPACE__);
```
for all methods (error, waring, ...) the property is available in the Record and can be formatted.
<a id="object-logger-withmethod" name="object-logger-withmethod"></a>
<a id="user-content-object-logger-withmethod" name="user-content-object-logger-withmethod"></a>
#### method: withMethod
this method set the property immutable.
the property itself is forwarded to the RECORD object and can be printed through the formatter with
- Method (full method)
- sMethod (with the basename of the given method)

<i>example of usage</i><br>
a class is injected with the component and inside the constructor<br>
```
$logger = $logger->withMethod(__METHOD__);
$logger->notice("hello");
```

for all methods (error, waring, ...) the property is available in the Record and can be formatted.
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
- Namespace (string, optional)
- sNamespace (string, optional) (_basename(Namespace)_)
- Method (string, optional)
- sMethod (string, optional) (_basename(Method)_)
- MemUsed (int)
- MemAllocated (int)
- Message (string)
- Context (array) 
#### method/static createRecord
this method is used inside <i>Logger</i> to create a new LogRecord object.
#### method: getToken(string $dateFormat)
this method is used in the <i>Formatter</i> to get the record "encoded".
Every element can be accessed through the "format" e.g. {Level}{LevelName}{Context.name}
```
return [
  'Date'         => $this->getLogDate()->format($dateFormat),
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
<a id="object-channel" name="object-channel"></a>
<a id="user-content-object-channel" name="user-content-object-channel"></a>
### Channel
The channel covers the infrastructure (Writer) and formatting type (Formatter).

<a id="object-formatter" name="object-formatter"></a>
<a id="user-content-object-formatter" name="user-content-object-formatter"></a>
### Formatter
A formatter converts a record to a string by using<br>
- RecordTokenReader
- [Normalizer](#object-normlizer)
- $format (array)
<br>
``["Date", "Message"]``
#### RecordTokenReader
The RecordTokenReader tries to find a key (e.g. ``Date``) in an array (_record 2 array_).<br>
The RecordTokenReader itself can be extended with 0..n valueConverter.<br>
A ValueConverter (IRecordTokenValueConverter) will be used to convert a value based on a key.
#### method: withFormat
...
#### method: formatRecord
Maps the Record against the $format and normalize the found value(s)

##### example<br>
The example uses a special formatter for the Record value "Date".
````
use DateTime;
use Terrazza\Component\Logger\IRecordTokenValueConverter;
use Terrazza\Component\Logger\Record;

class RecordTokenValueDate implements IRecordTokenValueConverter {
    private string $dateFormat;
    public function __construct(string $dateFormat="Y-m-d H:i:s.u") {
        $this->dateFormat                           = $dateFormat;
    }
    public function getValue($value) {
        return $value->format($this->dateFormat);
    }
}

$formatter = new ArrayFormatter(
   new RecordTokenReader([
      "Date" => new RecordTokenValueDate("Y-m-d H:i:s")
   ]),
   new NormalizerFlat("|"),
   ["Date", "Message"]
);   

$example = new Example();
$record  = Record::create("LoggerName", 100, "Message");
echo $formatter->formatRecord($record); // 2022-12-31 23:59:01
````

### Handler
There are two different types of handler provided. In any case a handler is the interactor for the logger to write a record.

<a id="object-channel-handler" name="object-channel-handler"></a>
<a id="user-content-object-channel-handler" name="user-content-object-channel-handler"></a>
#### ChannelHandler
the ChannelHandler allows us to collect handler to the same channel. He takes care about, that every handler has 
- the same formatter
- the same normalizer
- the same writer
To use the channelHandler as a Logger itself he implements the IHander-Methods, too.

#### method: pushHandler
Method to add a new Handler within his [HandlerPattern](#object-handler-pattern) and format.

<a id="object-single-handler" name="object-single-handler"></a>
<a id="user-content-object-single-handler" name="user-content-object-single-handler"></a>

#### SingleHandler
The SingleHandler provides the common way to create a handler for a Logger.
The only difference to the common implementation: 
- instead of logLevel, every handler is identified by a HandlerPattern
- the SingleHandler has to be injected within a [Channel](#object-channel)

<a id="object-handler-pattern" name="object-handler-pattern"></a>
<a id="user-content-object-handler-pattern" name="user-content-object-handler-pattern"></a>
#### HandlerPattern
The HandlerPattern cover the unique identifier for a Handler. The common identifier is the LogLevel.

<a id="object-normlizer" name="object-normlizer"></a>
<a id="user-content-object-normlizer" name="user-content-object-normlizer"></a>
### Normalizer
The normalizers are required to
- convert a single value from the Record into a "normalized" value
- convert the full message into a "normalized" value.

Every writer will require his own Normalizer and its up to the devs to initialize them however they want.
#### FlatNormalizer
```
$normalizer = new NormalizerFlat($delimiter); // string which will be used to join the message properties
```
Normalizer to
- convert a record value int a flat string
- convert the full record into a flat string
#### JsonNormalizer
```
$normalizer = new NormalizerJson($encodeFlags); // option to set eg. JSON_PRETTY_PRINT
```
Normalizer to
- convert a record value int a flat string, by using a json_encode
- convert the full record into a flat string, by using a json_encode

### Writer
<a id="object-writer-html" name="object-writer-html"></a>
<a id="user-content-object-writer-html" name="user-content-object-writer-html"></a>
#### HtmlWriter
Simple echo of the converted record.
The content can be wrapped within a given htmlWrap.
In any case the content will be echoed by using sprintf to allows the dev to format the response.
```
$writer = new HtmlWriter("<span>%s</span>");
```

<a id="object-writer-stream" name="object-writer-stream"></a>
<a id="user-content-object-writer-stream" name="user-content-object-writer-stream"></a>
#### StreamWriter
Save converted record to a file.<br>
<i>The implementation add a PHP_EOF at the end of the converted record.</i> 
```
$writer = new HtmlWriter("php://stdout");
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
$writer    = new StreamWriter("php://stdout");
$formatter = new ArrayFormatter(new RecordTokenReader([
      "Date" => new RecordTokenValueDate("Y-m-d H:i:s.U"),
      "Context.Exception" => new RecordTokenValueException(0, false), // maxTraceLevel=0, dumpArgs=false
   ]), new NormalizerFlat("|")); 
$channel   = new Channel("channelName", $writer, $formatter);
```
### Handler
#### setup a handler
```
$pattern   = new HandlerPattern(Logger::ERROR);
$format    = ["LoggerName", "Level", "Message"];
$handler   = new SingleHandler($pattern, $channel, $format);
```
### Create Logger
#### create within a handler
```
$logger = new Logger("loggerName", [], $handler);
$logger->error($message = "message");
```
#### create and push a handler
```
$logger = new Logger("loggerName", []);
$logger = $logger->withHandler($handler);
$logger->error($message = "message");
```
### Usage of Logger
#### produce a NOTICE
```
$logger->notice("myMessage");
// by using our examples above this message will not be printed
// ...cause the logLevel for the Handler is ERROR
```

#### produce an ERROR
```
$logger->error("myMessage"); 
// output to stdout will be
// loggerName|400|myMessage
```