<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;
use AppBundle\Entity\Variable;

class Saytext extends BaseExecutor implements ExecutorInterface
{
    public function say(Action $action)
    {
        $args =  json_decode($action->getArguments(), true);

        if (isset($this->parameters['text'])) {
            $text = $this->parameters['text'];
        } else {
            $text = $args['text'];
        }

        if (!$text) {
            return;
        }

        $textKey = md5($text);

        if (!file_exists($this->getContainer()->getParameter('kernel.cache_dir').'/voice')) {
            mkdir($this->getContainer()->getParameter('kernel.cache_dir').'/voice');
        }

        $file = $this->getContainer()->getParameter('kernel.cache_dir').'/voice/'.$textKey.'.mp3';

        $result = 'Played from cache';

        if (!file_exists($file)) {
            $res = file_get_contents("http://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&q=".
                urlencode($text)."&tl=De_de");
            file_put_contents($file, $res);

            $result= 'Saved in cache';
        }

        $res = exec('which mplayer');

        exec($res." -really-quiet -noconsolecontrols ".$file);

        return $result;
    }
}
