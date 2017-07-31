<?php

class action_plugin_yk_sync extends \DokuWiki_Action_Plugin
{


    public function register(\Doku_Event_Handler $controller)
    {
        $controller->register_hook('COMMON_WIKIPAGE_SAVE', 'AFTER', $this, 'eventSavePage', 'some-stuff', 3000); // late
    }


    public function eventSavePage(\Doku_Event $event, $param) {
        $doctrine = \Yurii\ServiceLocator::get('doctrine');
/*
        DOKU_CHANGE_TYPE_EDIT
DOKU_CHANGE_TYPE_REVERT
DOKU_CHANGE_TYPE_CREATE
DOKU_CHANGE_TYPE_DELETE
DOKU_CHANGE_TYPE_MINOR_EDIT*/

        global $lang;

$lang2 = $_SESSION[DOKU_COOKIE]['translationlc'];

        //$this->getLang()

        dbglog('LANG: '.$lang);
        dbglog($event);

      //  var_dump($event, $param); die;
    }
}