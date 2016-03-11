#!/usr/bin/env php
<?php

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

// Use derived class
{
    class PackableTest extends Packable {
        public $var_u8 = 0;
        public $var_u16 = 0;
        public $var_u32 = 0;
        public $var_str1 = '';
        public $var_str2 = '';

        public function pack()
        {
            $data = self::pack_u8($this->var_u8);
            $data .= self::pack_u16($this->var_u16);
            $data .= self::pack_u32($this->var_u32);
            $data .= self::pack_str($this->var_str1);
            $data .= self::pack_str($this->var_str2);
            $data = self::add_length($data);
            return $data;
        }

        public function unpack($data)
        {
            $len = self::unpack_u16($data, $pos);

            $this->var_u8 = self::unpack_u8($data, $pos);
            $this->var_u16 = self::unpack_u16($data, $pos);
            $this->var_u32 = self::unpack_u32($data, $pos);
            $this->var_str1 = self::unpack_str($data, $pos);
            $this->var_str2 = self::unpack_str($data, $pos);
            return $len === $pos;
        }

        public function dump() {
            say("u8: $this->var_u8 u16: $this->var_u16 u32: $this->var_u32 str: "
                .$this->var_str1 . $this->var_str2);
        }
    }

    $p1 = new PackableTest();
    $p1->var_u8 = 255;
    $p1->var_u16 = 256;
    $p1->var_u32 = 32769;
    $p1->var_str1 = 'This is ';
    $p1->var_str2 = 'a test!';

    say("Derived class:");
    $p1->dump();

    $data = $p1->pack();

    $p2 = new PackableTest();
    $p2->unpack($data);

    $p2->dump();
}


// Use normal class
{
    class PackableTest1 {
        public $var_u8 = 0;
        public $var_u16 = 0;
        public $var_u32 = 0;
        public $var_str1 = '';
        public $var_str2 = '';

        public function pack()
        {
            $data = PackableTest::pack_u8($this->var_u8);
            $data .= PackableTest::pack_u16($this->var_u16);
            $data .= PackableTest::pack_u32($this->var_u32);
            $data .= PackableTest::pack_str($this->var_str1);
            $data .= PackableTest::pack_str($this->var_str2);
            $data = PackableTest::add_length($data);
            return $data;
        }

        public function unpack($data)
        {
            $len = PackableTest::unpack_u16($data, $pos);

            $this->var_u8 = PackableTest::unpack_u8($data, $pos);
            $this->var_u16 = PackableTest::unpack_u16($data, $pos);
            $this->var_u32 = PackableTest::unpack_u32($data, $pos);
            $this->var_str1 = PackableTest::unpack_str($data, $pos);
            $this->var_str2 = PackableTest::unpack_str($data, $pos);
            return $len === $pos;
        }

        public function dump() {
            say("u8: $this->var_u8 u16: $this->var_u16 u32: $this->var_u32 str: "
                .$this->var_str1 . $this->var_str2);
        }
    }

    $p1 = new PackableTest1();
    $p1->var_u8 = 255;
    $p1->var_u16 = 256;
    $p1->var_u32 = 32769;
    $p1->var_str1 = 'This is ';
    $p1->var_str2 = 'a test!';

    say("Normal class:");
    $p1->dump();

    $data = $p1->pack();

    $p2 = new PackableTest1();
    $p2->unpack($data);

    $p2->dump();
}
