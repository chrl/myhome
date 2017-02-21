<?php

namespace AppBundle\Variable\Parser;

class Argument implements ParserInterface
{
    public function parse($arg)
    {
        return $arg;
    }
}