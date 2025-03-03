<?php

namespace App\Services;

use function Tinify\fromFile;
use function Tinify\setKey;

class TinifyService
{
    public function __construct(){
        setKey(config('services.tinify.key'));
    }

    public function optimaze(string $filename)
    {
        $source = fromFile($filename);
        $source->toFile($filename);
    }
}
