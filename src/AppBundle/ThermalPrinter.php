<?php

namespace AppBundle;

class ThermalPrinter
{
    const PATH = '/dev/serial0';

    private $fp = null;

    private $isInverse = false;

    public function __construct($debug = false)
    {
        if ($debug) {
            $this->fp = fopen('php://stdout', 'w+');
        } else {
            $this->fp = fopen(self::PATH, 'w+');
        }

        $this->init();

        return $this;
    }

    public function init()
    {
        fwrite($this->fp, chr(27).chr(64)); // flush printer
        fwrite($this->fp, chr(27).chr(116).chr(6)); // set codepage
        fwrite($this->fp, chr(27).chr(55).chr(8).chr(100).chr(5)); // set codepage
        return $this;
    }

    public function writeString($string)
    {
        fwrite($this->fp, wordwrap(iconv('UTF-8', 'cp1251', $string), 32));
        return $this;
    }

    public function hr()
    {
        fwrite($this->fp, "\n\n\n");
        fwrite($this->fp, '================================');
        fwrite($this->fp, "\n\n\n");
        return $this;
    }

    public function hhr()
    {
        fwrite($this->fp, "\n\n\n");
        $this->inv()->writeString(str_repeat(' ', 32))->inv();
        fwrite($this->fp, "\n\n\n");
        return $this;
    }

    public function inv()
    {
        $this->isInverse = !$this->isInverse;
        fwrite($this->fp, chr(29).chr(66).chr($this->isInverse?1:0));
        return $this;
    }

    public function fiscal()
    {
        fwrite($this->fp, "\n");
        $this->inv();
        fwrite($this->fp, '           '.iconv(
            'UTF-8',
            'cp1251',
            '<D0><A4><D0><98><D0><A1><D0><9A><D0><90><D0><9B><D0><AC><D0><9D><D0><AB><D0><99>'
        ).'           ');
        $this->inv();
        fwrite($this->fp, "\n");
        return $this;
    }

    public function writeText($text)
    {
        fwrite($this->fp, iconv('UTF-8', 'cp1251', $text));
        return $this;
    }

    public function __destruct()
    {
        fwrite($this->fp, "\n\n\n\n\n\n\n");
        fclose($this->fp);
    }
}
