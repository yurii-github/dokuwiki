<?php

class pageutils_findnearest_test extends DokuWikiTest {

    protected $oldAuthAcl;

    function setUp() {
        parent::setUp();
        global $AUTH_ACL;
        global $auth;
        global $conf;
        $conf['superuser'] = 'john';
        $conf['useacl']    = 1;

        $this->oldAuthAcl = $AUTH_ACL;
        $auth = new DokuWiki_Auth_Plugin();

        $AUTH_ACL = array(
            '*           @ALL           1',
            'internal:*    @ALL           0',
            'internal:*    max            1',
            '*           @user          8',
        );
    }

    function tearDown() {
        global $AUTH_ACL;
        $AUTH_ACL = $this->oldAuthAcl;
    }

    function testNoSidebar() {
        global $ID;

        $ID = 'foo:bar:baz:test';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals(false, $sidebar);
    }

    function testExistingSidebars() {
        global $ID;

        saveWikiText('sidebar', 'topsidebar-test', '');

        $ID = 'foo:bar:baz:test';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('sidebar', $sidebar);

        $ID = 'foo';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('sidebar', $sidebar);

        saveWikiText('foo:bar:sidebar', 'bottomsidebar-test', '');

        $ID = 'foo:bar:baz:test';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('foo:bar:sidebar', $sidebar);

        $ID = 'foo:bar:test';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('foo:bar:sidebar', $sidebar);

        $ID = 'foo';
        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('sidebar', $sidebar);
    }

    function testACLWithSidebar() {
        global $ID;
        global $INPUT;

        $INPUT->server->set('REMOTE_USER', 'foo');

        saveWikiText('sidebar', 'top sidebar', '');
        saveWikiText('internal:sidebar', 'internal sidebar', '');

        $ID = 'internal:foo:bar';

        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('sidebar', $sidebar);

        $sidebar = page_findnearest('sidebar', false);
        $this->assertEquals('internal:sidebar', $sidebar);

        $INPUT->server->set('REMOTE_USER', 'max');

        $sidebar = page_findnearest('sidebar');
        $this->assertEquals('internal:sidebar', $sidebar);
    }
}
