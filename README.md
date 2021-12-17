# the logger component
This component is an implementation of PSR/Log standard with some extensions.

1. [Logger](#logger) 
   1. [withHandler](#logger-withhandler)
   2. [withNamespace](#logger-withnamespace)
   3. [withMethod](#logger-withmethod)
   4. [Context](#logger-context)
2. [Install](#install)
3. [Requirements](#require)

<a id="logger" name="logger"></a>
<a id="user-content-logger" name="user-content-logger"></a>
## Logger
The Logger object provides 3 more properties than the common PSR implementations.
- namespace
- method
- context
<a id="logger-withhandler" name="logger-withhandler"></a>
<a id="user-content-logger-withhandler" name="user-content-logger-withhandler"></a>
### method: withHandler
fulfill the same as pushHandler, but is immutable
<a id="logger-withnamespace" name="logger-withnamespace"></a>
<a id="user-content-logger-withnamespace" name="user-content-logger-withnamespace"></a>
### method: withNamespace
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
<a id="logger-withmethod" name="logger-withmethod"></a>
<a id="user-content-logger-withmethod" name="user-content-logger-withmethod"></a>
### method: withMethod
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
<a id="logger-context" name="logger-context"></a>
<a id="user-content-logger-context" name="user-content-logger-context"></a>
### context
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

#### method: hasContextKey
validate if given key exists in the initialized context.<br>
with a DOT inside the key you can walk through the array.<br>
```
$logger = new Logger("name", ["user" => ["name" => "Max"]]);
$logger->hasContextKey("user"); // true
$logger->hasContextKey("email"); // false
$logger->hasContextKey("user.name"); // true
$logger->hasContextKey("user.email"); // false
```

#### method: getContextKey
returns the initialized context value by a given key.<br>
with a DOT inside the key you can walk through the array.<br>
```
$logger = new Logger("name", ["user" => ["name" => "Max"]]);
$logger->getContextKey("user"); // ["name" => "Max"]
$logger->getContextKey("email"); // null
$logger->getContextKey("user.name"); // Max
$logger->getContextKey("user.email"); // null
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
