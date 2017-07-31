<?php
use \Yurii\Entity\Page;

class action_plugin_yk_i18n extends \DokuWiki_Action_Plugin
{
    public function register(\Doku_Event_Handler $controller) {
        $controller->register_hook('INIT_LANG_LOAD', 'BEFORE', $this, 'eventInitLang', 'some-stuff', -3000);
     //   $controller->register_hook('JS_CACHE_USE', 'BEFORE', $this, 'eventJSCacheUse');
    }

    public function eventInitLang(\Doku_Event $event, $args) {
        $pageId = getID(); // use low level access, gobals[id] is not set
        $lang = $GLOBALS['conf']['lang'];

        /** @var \Doctrine\ORM\EntityManager $doctrine */
        $doctrine = \Yurii\ServiceLocator::get('doctrine');
        $repo = $doctrine->getRepository(Page::class);
        /** @var \Yurii\Entity\Page $page */
        if($page = $repo->findOneBy(['pageId' => $pageId])) {
            dbglog($page->getPageId());
            $lang = $page->getLang();

        } else {
            if (!empty($_SESSION[DOKU_COOKIE]['yk_1i8n_lang'])) {
                $lang = $_SESSION[DOKU_COOKIE]['yk_1i8n_lang'];
            }
        }

        // set dokuwiki globals, etc
        $event->data = $lang;
        $GLOBALS['conf']['lang'] = $lang;
        if ($_SESSION[DOKU_COOKIE]['yk_1i8n_lang'] != $lang) {
            $_SESSION[DOKU_COOKIE]['yk_1i8n_lang'] = $lang;
        }

    }

    /**
     * Hook Callback. Make sure the JavaScript is translation dependent
     *
     * @param Doku_Event $event
     * @param $args
     */
    function eventJSCacheUse(Doku_Event $event, $args)
    {
        dbglog($event);
        if (!isset($_GET['lang'])) return;
        if (!in_array($_GET['lang'], $this->helper->translations)) return;

        $lang = $_GET['lang'];

        $event->data->__construct(
            $event->data->key . $lang,
            $event->data->ext
        );

    }


}