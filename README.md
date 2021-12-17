# the logger component
This component is an implementation of PSR/Log standard with some extensions.

1. Object/Classes
   1. [Logger](#object-logger) 
      1. [withHandler](#object-logger-withhandler)
      2. [withNamespace](#object-logger-withnamespace)
      3. [withMethod](#object-logger-withmethod)
      4. [Context](#object-logger-context)
   2. [Record](#object-record)
   3. [Channel](#object-channel)
2. [Install](#install)
3. [Requirements](#require)

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

##### method: getContextKey
returns the initialized context value by a given key.<br>
with a DOT inside the key you can walk through the array.<br>
```
$logger = new Logger("name", ["user" => ["name" => "Max"]]);
$logger->getContextKey("user"); // ["name" => "Max"]
$logger->getContextKey("email"); // null
$logger->getContextKey("user.name"); // Max
$logger->getContextKey("user.email"); // null
```
<a id="object-record" name="object-record"></a>
<a id="user-content-object-record" name="user-content-object-record"></a>
### Record
Against the common PSR implementation our component deals with an object and not an array.<br>
LogRecord properties:
- logDate (\Datetime)
- loggerName (string)
- logLevel (string)
- logMessage (string)
- context (array)
- memUsed (int)
- memAllocated (int)
- namespace (string, optional)
- method (string, optional)
#### ::createRecord
this method is used inside <i>Logger</i> to create a new LogRecord object.
#### getToken (string $dateFormat)
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
Each handler is initialized with 
- a handlerPattern (currently only loglevel)
- and a channel

the channel provide the writer and the formatter. Therefore, the channel object determines the infrastructure and formatting type and prevent the handlers to use different ones. 

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
