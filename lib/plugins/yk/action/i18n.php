<?php
use \Yurii\Entity\Page;

class action_plugin_yk_i18n extends \DokuWiki_Action_Plugin
{
    public function register(\Doku_Event_Handler $controller) {
        $controller->register_hook('INIT_LANG_LOAD', 'BEFORE', $this, 'eventInitLang', 'some-stuff', -3000);
    }

    public function eventInitLang(\Doku_Event $event, $args) {
        $pageId = getID(); // use low level access, gobals[id] is not set

        /** @var \Doctrine\ORM\EntityManager $doctrine */
        $doctrine = \Yurii\ServiceLocator::get('doctrine');
        $repo = $doctrine->getRepository(Page::class);
        /** @var \Yurii\Entity\Page $page */
        if($page = $repo->findOneBy(['pageId' => $pageId])) {
            dbglog($page->getPageId());
            dbglog(\Doctrine\Common\Util\Debug::dump($page));
            $event->data = $page->getLang();
        }
    }
}