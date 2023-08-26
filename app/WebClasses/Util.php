<?php

namespace App\WebClasses;

class Util
{
    static function generateCode($length = 6, $chars = null)
    {
        $characters = $chars ?? '123456789ABCDEFGHJKLMNPQRTUVWXYZadefghrt=+_0Oo';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
