<?php

abstract class Initializer {

    ///////////////////////////////////////////////////////////////////////////
    public static function smarty() {
        $smarty = new ExtendedSmarty();

        $smarty->setCacheDir(SMARTY_PATH    . '/cache')
               ->setCompileDir(SMARTY_PATH  . '/compile')
               ->setConfigDir(SMARTY_PATH   . '/configs')
               ->addPluginsDir(SMARTY_PATH  . '/plugins')
               ->setTemplateDir(TEMPLATES_PATH);

        $smarty->assign('_root', WEBROOT);

       return $smarty;
    }

}