<?php
if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
header('X-UA-Compatible: IE=edge,chrome=1');

$hasSidebar = page_findnearest($conf['sidebar']);
$showSidebar = $hasSidebar && ($ACT=='show');

// in-place replacement for tpl_pageinfo()
$editDate = function () use (&$INFO, &$lang) {
    if($INFO['exists']) {
        return $lang['lastmod'] .' '. dformat($INFO['lastmod'], '%d/%m/%Y');
    }
}

?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="utf-8" />
    <title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <?php tpl_metaheaders() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/png" href="<?= tpl_getMediaFile(['assets/mortar.png'])?>">
    <?php tpl_includeFile('meta.html') ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-1647951743023830",
            enable_page_level_ads: true
        });
    </script>
</head>

<body>
    <div id="dokuwiki__site">
        <div id="dokuwiki__top" class="site <?php echo tpl_classes(); ?> <?php echo ($showSidebar) ? 'showSidebar' : ''; ?> <?php echo ($hasSidebar) ? 'hasSidebar' : ''; ?>">
            <?php include('tpl_header.php') ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Page TOP -->
            <ins class="adsbygoogle"
                 style="display:block; margin-bottom: 1rem"
                 data-ad-client="ca-pub-1647951743023830"
                 data-ad-slot="1745999970"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

            <div class="wrapper group">
                <?php if($showSidebar): ?>
                    <!-- ********** ASIDE ********** -->
                    <div id="dokuwiki__aside"><div class="pad aside include group">
                        <h3 class="toggle"><?php echo $lang['sidebar'] ?></h3>
                        <div class="content"><div class="group">
                            <?php tpl_flush() ?>
                            <?php tpl_include_page($conf['sidebar'], true, true) ?>
                        </div></div>
                    </div></div><!-- /aside -->
                <?php endif; ?>

                <!-- ********** CONTENT ********** -->
                <div id="dokuwiki__content">
                    <div class="pad group">
                        <?php html_msgarea() ?>
                        <div class="page group">
                            <?php tpl_flush() ?>
                            <!-- wikipage start -->
                            <?php tpl_content() ?>
                            <!-- wikipage stop -->
                        </div>
                        <div class="docInfo"><?= $editDate(); ?></div>
                        <?php tpl_flush() ?>

                        <?php if ($ACT=='show'): ?>
                            <div id="hypercomments_widget"></div>
                            <script type="text/javascript">
                                _hcwp = window._hcwp || [];
                                _hcwp.push({widget:"Stream", widget_id: 91700});
                                (function() {
                                    if("HC_LOAD_INIT" in window)return;
                                    HC_LOAD_INIT = true;
                                    var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
                                    hcc.src = "//w.hypercomments.com/widget/hc/91700/<?= $conf['lang'];?>/widget.js";
                                    var s = document.getElementsByTagName("script")[0];
                                    s.parentNode.insertBefore(hcc, s.nextSibling);
                                })();
                            </script>
                        <?php endif; ?>

                    </div>
                </div><!-- /content -->

                <hr class="a11y" />

                <!-- PAGE ACTIONS -->
                <div id="dokuwiki__pagetools">
                    <h3 class="a11y"><?php echo $lang['page_tools']; ?></h3>
                    <div class="tools">
                        <ul>
                            <?php
                            $admin = [];
                                $GLOBALS['USERINFO']['uid'] == 1 ?
                                    $admin = [
                                        'edit'  => tpl_action('edit', true, 'li', true, '<span>', '</span>'),
                                        'revert' => tpl_action('revert', true, 'li', true, '<span>', '</span>'),
                                        'revisions' => tpl_action('revisions', true, 'li', true, '<span>', '</span>'),
                                        'subscribe' => tpl_action('subscribe', true, 'li', true, '<span>', '</span>'),
                                    ] : [];

                                $data = array(
                                    'view'  => 'main',
                                    'items' =>  $admin + array(
                                        'backlink'  => tpl_action('backlink',  true, 'li', true, '<span>', '</span>'),
                                        'top' => tpl_action('top',  true, 'li', true, '<span>', '</span>')
                                    )
                                );

                                // the page tools can be amended through a custom plugin hook
                                $evt = new Doku_Event('TEMPLATE_PAGETOOLS_DISPLAY', $data);
                                if($evt->advise_before()){
                                    foreach($evt->data['items'] as $k => $html) echo $html;
                                }
                                $evt->advise_after();
                                unset($data);
                                unset($evt);
                            ?>
                        </ul>
                    </div>
                </div>
            </div><!-- /wrapper -->
            <hr>
            <!-- ********** FOOTER ********** -->
            <div id="dokuwiki__footer">
                <div class="pad">
                    <?php  tpl_link(wl('ліцензії'),'<img width="15" height="15" src="'.tpl_getMediaFile(['assets/zero.svg']).'"  alt="ліцензії" /> Ліцензії'); ?>
                     |
                    <a target="_blank" href="https://nic.ua/ru/signup/jbduuyeq">Хостинг від NIC.UA<sup>-10%</sup></a>
                </div>
            </div>
        </div>
    </div><!-- /site -->

    <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
    <div id="screen__mode" class="no"></div><?php /* helper to detect CSS media query in script.js */ ?>
</body>
</html>