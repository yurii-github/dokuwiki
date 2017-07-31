<?php
use \Yurii\ServiceLocator;
use \Yurii\Entity\Page;

class action_plugin_yk_sync extends \DokuWiki_Action_Plugin
{

    public function register(\Doku_Event_Handler $controller)
    {
        $controller->register_hook('COMMON_WIKIPAGE_SAVE', 'AFTER', $this, 'eventSavePage', 'some-stuff', 3000); // late
    }


    public function eventSavePage(\Doku_Event $event, $args)
    {
        /** @var \Doctrine\ORM\EntityManager $doctrine */
        $doctrine = ServiceLocator::get('doctrine');

        switch ($event->data['changeType']) {
            //dbglog($event);
            case DOKU_CHANGE_TYPE_EDIT:
                break;
            case DOKU_CHANGE_TYPE_REVERT:
                break;
            case DOKU_CHANGE_TYPE_CREATE:
                $repo = $doctrine->getRepository(Page::class);
                $page = new Page();
                $page->setLang($GLOBALS['conf']['lang']);
                $page->setPageId($GLOBALS['ID']);
                //TODO: base page
                $doctrine->persist($page);
                $doctrine->flush();
            case DOKU_CHANGE_TYPE_DELETE:
                break;
            case OKU_CHANGE_TYPE_MINOR_EDIT:
                break;
            default:

                break;
        }


        //   global $lang;

//$lang2 = $_SESSION[DOKU_COOKIE]['translationlc'];

        //$this->getLang()

        //   dbglog('LANG: '.$lang);
        //   dbglog($event);

        //  var_dump($event, $param); die;
    }
}