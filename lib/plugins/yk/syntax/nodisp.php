<?php

/**
 *  https://www.dokuwiki.org/plugin:nodisp
 */
class syntax_plugin_yk_nodisp extends DokuWiki_Syntax_Plugin
{

    /**
     * return some info
     */
    function getInfo()
    {
        return array(
            'author' => 'Myron Turner',
            'email' => 'turnermm02 AT shaw DOT ca',
            'date' => '2016-01-16',
            'name' => 'nodisp Plugin',
            'desc' => 'hides display of enclosed text',
            'url' => 'http://www.mturner.org',
        );
    }

    function getType()
    {
        return 'formatting';
    }

    function getPType()
    {
        return 'stack';
    }

    function getAllowedTypes()
    {
        return array('formatting', 'substition', 'disabled', 'protected', 'container', 'paragraphs');
    }

    function getSort()
    {
        return 168;
    }

    function connectTo($mode)
    {
        $this->Lexer->addEntryPattern('<nodisp.*?>(?=.*?</nodisp>)', $mode, 'plugin_nodisp');
    }

    function postConnect()
    {
        $this->Lexer->addExitPattern('</nodisp>', 'plugin_nodisp');
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {
            case DOKU_LEXER_ENTER :
                return array($state, false);

            case DOKU_LEXER_UNMATCHED :
                return array($state, $match);
            case DOKU_LEXER_EXIT :
                return array($state, '');
        }

        return array();
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data)
    {
        global $INFO;
        if ($mode == 'xhtml') {
            $renderer->nocache(); // disable caching
            list($state, $match) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    if ($INFO['isadmin'] || $INFO['ismanager']) break;
                    $renderer->doc .= "<div style='display:none'>";
                    break;

                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= $renderer->_xmlEntities($match);
                    break;
                case DOKU_LEXER_EXIT :
                    if ($INFO['isadmin'] || $INFO['ismanager']) break;
                    $renderer->doc .= "</div>";
                    break;
            }
            return true;
        }
        return false;
    }


}