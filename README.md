## php-packable
pack/unpack wrapper utility for php.

This utility is originally used to encode/decode data to/from network byte order.

If you'd like to understand pack/unpack. There is a tutorial here in perl, that works equally well in understanding it for php:
http://perldoc.perl.org/perlpacktut.html

## Quick Example

```php
require_once dirname(__FILE__) . '/packable.php';

$lf = "<br>";
if (php_sapi_name() === "cli") {
    $lf = "\n";
}

function say($msg)
{
    global $lf;
    print_r($msg);
    echo($lf);
}

// General usage
{
    $var_u8 = 255;
    $var_u16 = 256;
    $var_u32 = 23769;
    $var_str1 = 'This is ';
    $var_str2 = 'a test';

    $data = Packable::pack_u8($var_u8);
    $data .= Packable::pack_u16($var_u16);
    $data .= Packable::pack_u32($var_u32);
    $data .= Packable::pack_str($var_str1);
    $data .= Packable::pack_str($var_str2);

    $var_u8 = 0;
    $var_u16 = 0;
    $var_u32 = 0;
    $var_str1 = '';
    $var_str2 = '';

    $var_u8 = Packable::unpack_u8($data, $pos);
    $var_u16 = Packable::unpack_u16($data, $pos);
    $var_u32 = Packable::unpack_u32($data, $pos);
    $var_str1 = Packable::unpack_str($data, $pos);
    $var_str2 = Packable::unpack_str($data, $pos);

    say("General usage:");
    say("u8: $var_u8 u16: $var_u16 u32: $var_u32 str: " .$var_str1 . $var_str2);
}
```

## The test.php

```shell
$ php test.php
```
or
```shell
$ chmod +x test.php
$ ./test.php
```
