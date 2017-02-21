<?php

namespace AppBundle\Variable\Parser;

use Symfony\Component\HttpFoundation\Request;

class Simple implements ParserInterface
{
    public function parse(Request $request)
    {
        return $request->query->get('value',false);
    }
}