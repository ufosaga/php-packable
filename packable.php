<?php

abstract class Packable
{
    protected abstract function pack();
    protected abstract function unpack($data);

    public static function pack_u8($num)
    {
        return pack('C', $num);
    }

    public static function unpack_u8(&$data, &$pos)
    {
        $result = unpack('Cu8', substr($data, $pos++));
        return $result['u8'];
    }

    public static function pack_u16($num)
    {
        return pack('n', $num);
    }

    public static function unpack_u16($data, &$pos)
    {
        $result = unpack('nu16', substr($data, $pos));
        $pos += 2;
        return $result['u16'];
    }

    public static function pack_u32($num)
    {
        return pack('N', $num);
    }

    public static function unpack_u32($data, &$pos)
    {
        $result = unpack('Nu32', substr($data, $pos));
        $pos += 4;
        return $result['u32'];
    }

    public static function pack_str($str)
    {
        $len = self::pack_u16(strlen($str));
        return $len . $str;
    }

    public static function unpack_str($data, &$pos)
    {
        $len = self::unpack_u16($data, $pos);
        $str = substr($data, $pos, $len);
        $pos += $len;
        return $str;
    }

    public static function add_length($data)
    {
        $len = self::pack_u16(strlen($data) + 2);
        return $len . $data;
    }
}

