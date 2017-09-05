<?php
/**
 * Template header, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

?>

<!-- ********** HEADER ********** -->
<div id="dokuwiki__header" style="padding-bottom: 0.5em;"><div class="pad group">

    <?php tpl_includeFile('header.html') ?>

    <div class="headings group">
        <ul class="a11y skip">
            <li><a href="#dokuwiki__content"><?php echo $lang['skip_to_content']; ?></a></li>
        </ul>

        <h1><?php
            tpl_link(wl(),'<img width="64" height="64" src="'.tpl_getMediaFile(['assets/mortar.svg']).'"  alt="" /> <span>'.$conf['title'].'</span>');
        ?></h1>
        <?php if ($conf['tagline']): ?>
            <p class="claim"><?php echo $conf['tagline']; ?></p>
        <?php endif ?>
    </div>

    <div class="tools group">
        <!-- USER TOOLS -->
        <?php if ($conf['useacl']): ?>
            <div id="dokuwiki__usertools">
                <h3 class="a11y"><?php echo $lang['user_tools']; ?></h3>
                <ul>
                    <?php
                        if (!empty($_SERVER['REMOTE_USER'])) {
                            echo '<li class="user">';
                            tpl_userinfo(); /* 'Logged in as ...' */
                            echo '</li>';
                        }
                        echo (new \dokuwiki\Menu\UserMenu())->getListItems('action ');
                    ?>
                </ul>
            </div>
        <?php endif ?>

        <!-- SITE TOOLS -->
        <div id="dokuwiki__sitetools">
            <h3 class="a11y"><?php echo $lang['site_tools']; ?></h3>
            <?php tpl_searchform(); ?>
            <div class="mobileTools">
                <?php echo (new \dokuwiki\Menu\MobileMenu())->getDropdown($lang['tools']); ?>
            </div>
            <ul>
                <?php echo (new \dokuwiki\Menu\SiteMenu())->getListItems('action ', false); ?>
            </ul>
        </div>

    </div>

    <!-- BREADCRUMBS -->
    <?php if($conf['breadcrumbs'] || $conf['youarehere']): ?>
        <div class="breadcrumbs">
            <?php if($conf['youarehere']): ?>
                <div class="youarehere"><?php tpl_youarehere() ?></div>
            <?php endif ?>
            <?php if($conf['breadcrumbs']): ?>
                <div class="trace"><?php tpl_breadcrumbs() ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>



    <hr class="a11y" />
</div></div><!-- /header -->
