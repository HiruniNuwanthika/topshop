<?php

/*------------------------------------------------------------------------
# plg_system_magicslideshow - Magic Slideshow for Joomla
# ------------------------------------------------------------------------
# Magic Toolbox
# Copyright 2011 MagicToolbox.com. All Rights Reserved.
# @license - http://www.opensource.org/licenses/artistic-license-2.0  Artistic License 2.0 (GPL compatible)
# Website: http://www.magictoolbox.com/magicslideshow/modules/joomla/
# Technical Support: http://www.magictoolbox.com/contact/
/*-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access.');

//ini_set('display_errors', true );
//error_reporting(E_ALL & ~E_NOTICE);

global $displayMagicSlideshowForJoomlaHeaders;

if(!defined('MAGICSLIDESHOW_FOR_JOOMLA_INIT')) {
    define('MAGICSLIDESHOW_FOR_JOOMLA_INIT', true);
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    defined('JVERSION_16') or define('JVERSION_16', version_compare(JVERSION, '1.6.0','>=') ? true : false);
    defined('JVERSION_30') or define('JVERSION_30', version_compare(JVERSION, '3.0.0','>=') ? true : false);
    $displayMagicSlideshowForJoomlaHeaders = false;
}

if(!defined('MAGICTOOLBOX_JURI_BASE')) {
    $url = JURI::base(true);
    //NOTE: JURI::base() return URI according to $live_site variable in configuration
    //      this leads to problem with wrong protocol prefix (http/https)
    //      so this is a fix
    if(empty($_SERVER['HTTPS']) || (strtolower($_SERVER['HTTPS']) == 'off')) {
        $url = preg_replace('/^https:/i', 'http:', $url);
    } else {
        $url = preg_replace('/^http:/i', 'https:', $url);
    }
    define('MAGICTOOLBOX_JURI_BASE', $url);
}

class PlgSystemMagicSlideshow extends JPlugin {

    protected static $instance = null;

    public function __construct(&$subject, $config = array()) {
        parent::__construct($subject, $config);
        if(is_null(self::$instance)) {
            self::$instance = $this;
        }
        //$this->loadLanguage();
    }

    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new PlgSystemMagicSlideshow(JEventDispatcher::getInstance(), JPluginHelper::getPlugin('system', 'magicslideshow'));
        }
        return self::$instance;
    }

    public function onAfterRender() {

		$app = JFactory::getApplication();
		if($app->isAdmin()) return;

        global $displayMagicSlideshowForJoomlaHeaders;

        $tool = $this->getToolMagicSlideshowForJoomla();

        $contents = JResponse::getBody();//JResponse::toString();
        //$app->getBody();

        if(!$tool->params->checkValue('enable-effect', 'No', 'default')) {

            $tool->params->setProfile('default');

            //NOTE: added support for shortcodes
            //      e.g. [magicslideshow folder="images/magicslideshow_shortcode_files/"]
            $matches = array();
            preg_match_all('/\[magicslideshow\s*+folder\s*+=\s*+"([^"]*+)"\s*+\]/ims', $contents, $matches);
            if(!empty($matches[1])) {
                foreach($matches[1] as $i => $path) {
                    if(!empty($path)) {
                        //NOTE: fix directory separators
                        $path = preg_replace('#^(?:\\\\|/)++|(?:\\\\|/)++$#is', '', $path);
                        $path = preg_replace('#\\\\++|/++#is', DS, $path);

                        $folderPath = JPATH_SITE.DS.$path.DS;
                        $wsFolderPath = MAGICTOOLBOX_JURI_BASE.'/'.str_replace(DS, '/', $path).'/';

                        if(is_dir($folderPath)) {
                            $files = scandir($folderPath);
                            if(is_array($files)) {
                                $files = array_diff($files, array('.', '..'));
                                $html = '';
                                foreach($files as $file) {
                                    //if(preg_match('#.(?:bmp|gif|jpe?g|png|tiff?)$#is', $file) && is_file($folderPath.$file)) {
                                    //    $html .= '<img src="'.$wsFolderPath.$file.'"/>';
                                    //}
                                    $size = @getimagesize($folderPath.$file);
                                    if(is_array($size)) {
                                        $html .= '<img src="'.$wsFolderPath.$file.'"/>';
                                    }
                                }
                                if(!empty($html)) {
                                    $contents = str_replace($matches[0][$i], '<div class="MagicSlideshow">'.$html.'</div>', $contents);
                                }
                            }
                        }
                    }
                }
            }

            //NOTE: old pattern
            //<(p|div)\b([^>]*?\bclass=["\'][^"\']*?\bMagicSlideshow\b[^"\']*+["\'][^>]*+)>(.*?)</\1>
            $pattern =  '<(p|div)\b([^>]*?\bclass=["\'][^"\']*?\bMagicSlideshow\b[^"\']*+["\'][^>]*+)>'.
                        '('.
                        '(?:'.
                            '[^<]++'.
                            '|'.
                            '<(?!/?\1\b|!--)'.
                            '|'.
                            '<!--.*?-->'.
                            '|'.
                            '<\1\b[^>]*+>'.
                                '(?3)'.
                            '</\1\s*+>'.
                        ')*+'.
                        ')'.
                        '</\1\s*+>';

            //$contents = preg_replace_callback("#{$pattern}#is", 'callbackMagicSlideshowForJoomla', $contents);                            
            $contents = preg_replace_callback("#{$pattern}#is", array('PlgSystemMagicSlideshow', 'callbackMagicSlideshowForJoomla'), $contents);
            //preg_match_all('%'.$pattern.'%is', $contents, $__matches, PREG_SET_ORDER);

            //NOTE: to fix relative URLs (rev/data-image/... etc tags) (like SEF plugin)
            //NOTE: using the previous pattern
            $contents = preg_replace_callback("#{$pattern}#is", array('PlgSystemMagicSlideshow', 'callbackMagicSlideshowForJoomlaFixURL'), $contents);

        }

        if(!$displayMagicSlideshowForJoomlaHeaders || defined('MagicSlideshow_HEADERS_LOADED')) return true;

        define('MagicSlideshow_HEADERS_LOADED', true);

        $tool->params->resetProfile();
        $url = MAGICTOOLBOX_JURI_BASE.'/media/plg_system_magicslideshow';
        $headers = array();
        $headers[] = "\n".$tool->getHeadersTemplate($url);
        $headers[] = "\n<link type=\"text/css\" href=\"{$url}/magictoolbox.css\" rel=\"stylesheet\" media=\"screen\" />\n";
        $contents = preg_replace('#</head>#is', implode($headers).'</head>', $contents, 1);

        JResponse::setBody($contents);//$app->setBody($data);

        return true;
    }

    public function getToolMagicSlideshowForJoomla() {
        static $mainCoreClass = null;
        if($mainCoreClass === null) {
            require_once(dirname(__FILE__).DS.'magicslideshow_classes'.DS.'magicslideshow.module.core.class.php');
            $mainCoreClass = new MagicSlideshowModuleCoreClass();
            $database = JFactory::getDBO();
            $database->setQuery("SELECT `profile`, `name`, `value` FROM `#__magicslideshow_config` WHERE `disabled`='0'");
            $results = $database->loadAssocList();
            if(!empty($results)) {
                foreach($results as $row) {
                    $mainCoreClass->params->setValue($row['name'], $row['value'], $row['profile']);
                }
                // for MT module
                //$this->conf->set('caption-source', 'Title');
            }
        }
        return $mainCoreClass;
    }

    public function callbackMagicSlideshowForJoomla($_matches) {


        $html = "<div{$_matches[2]}>{$_matches[3]}</div>";

        global $displayMagicSlideshowForJoomlaHeaders;
        $displayMagicSlideshowForJoomlaHeaders = true;

        return $html;
    }

    public function callbackMagicSlideshowForJoomlaFixURL($_matches) {
        $protocols = '[a-zA-Z0-9\-]+:';
        $pattern = '\bdata(?:-(?:thumb|fullscreen))?-image\s*+=\s*+["\'](?!/|'.$protocols.')([^"\']*+)["\']';
        if(preg_match_all('#'.$pattern.'#is', $_matches[0], $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {
                $attribute = str_replace($match[1], MAGICTOOLBOX_JURI_BASE.'/'.$match[1], $match[0]);
                $_matches[0] = str_replace($match[0], $attribute, $_matches[0]);
            }
        }

        return $_matches[0];

    }

}
