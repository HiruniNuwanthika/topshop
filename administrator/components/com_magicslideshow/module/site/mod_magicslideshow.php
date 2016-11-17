<?php

/*------------------------------------------------------------------------
# mod_magicslideshow - Magic Slideshow for Joomla
# ------------------------------------------------------------------------
# Magic Toolbox
# Copyright 2011 MagicToolbox.com. All Rights Reserved.
# @license - http://www.opensource.org/licenses/artistic-license-2.0  Artistic License 2.0 (GPL compatible)
# Website: http://www.magictoolbox.com/magicslideshow/modules/joomla/
# Technical Support: http://www.magictoolbox.com/contact/
/*-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access.');

//NOTE: this file is included in JModuleHelper::renderModule function

//ini_set('display_errors', true );
//error_reporting(E_ALL & ~E_NOTICE);

global $displayMagicSlideshowForJoomlaHeaders;
global $plgSystemMagicSlideshow;

if(!class_exists('PlgSystemMagicSlideshow')) return;//NOTE: plugin disabled

//JPluginHelper::importPlugin('system', 'magicslideshow')
$plgSystemMagicSlideshow = PlgSystemMagicSlideshow::getInstance();

if(!defined('MAGICSLIDESHOW_FOR_JOOMLA_FUNCTIONS_DEFINED')) {

    define('MAGICSLIDESHOW_FOR_JOOMLA_FUNCTIONS_DEFINED', true);

    function getImageMagicSlideshowForJoomla($path, $type = 'original', $id = null) {
        static $imageHelper = null;
        if($imageHelper === null) {
            $classesFolded = JVERSION_16 ? 'magicslideshow'.DS.'magicslideshow_classes' : 'magicslideshow_classes';
            require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.$classesFolded.DS.'magictoolbox.imagehelper.class.php');
            global $plgSystemMagicSlideshow;
            $tool = $plgSystemMagicSlideshow->getToolMagicSlideshowForJoomla();
            $imageHelper = new MagicToolboxImageHelperClass(JPATH_SITE,
                                                            DS.'media'.DS.'mod_magicslideshow'.DS.'magictoolbox_cache',
                                                            $tool->params,
                                                            null,
                                                            MAGICTOOLBOX_JURI_BASE);
        }
        if(!$path || !is_file(JPATH_SITE.$path)) {
            return '';
        }
        return $imageHelper->create($path, $type, $id);
    }

}

$magicslideshow = $plgSystemMagicSlideshow->getToolMagicSlideshowForJoomla();

$view = trim(JRequest::getVar('view', ''));
$homepage = $view == 'frontpage' || JVERSION_16 && $view == 'featured';
defined('JVERSION_25') or define('JVERSION_25', version_compare(JVERSION, '2.5.0','>=') ? true : false);
/*
//NOTE: for Joomla 1.5
$applicationMenu = & JSite::getMenu();
if($applicationMenu->getActive() == $applicationMenu->getDefault()) {
    $homepage = true;
}
*/
//NOTE: for Joomla 2.5 and 3.x
if(JVERSION_25/*JVERSION_30*/) {
    $applicationMenu = JFactory::getApplication()->getMenu();
    if($applicationMenu->getActive() == $applicationMenu->getDefault(JFactory::getLanguage()->getTag())) {
        $homepage = true;
    }
}

if($homepage && !$magicslideshow->params->checkValue('enable-effect', 'No', 'custom_slideshow')) {

    $magicslideshow->params->setProfile('custom_slideshow');
    $database = JFactory::getDBO();
    $database->setQuery("SELECT * FROM `#__magicslideshow_images` WHERE `exclude`='0' ORDER BY `order`");
    $imagesData = $database->loadAssocList();
    if(!empty($imagesData)) {
        $baseImagePath = DS.'images'.DS.'magicslideshow'.DS;
        foreach($imagesData as &$imageData) {
            $imageData['alt'] = $imageData['title'];
            $imageData['img'] = getImageMagicSlideshowForJoomla($baseImagePath.$imageData['name'], 'thumb', 'custom_slideshow');
            $imageData['thumb'] = getImageMagicSlideshowForJoomla($baseImagePath.$imageData['name'], 'selector', 'custom_slideshow');
            $imageData['fullscreen'] = getImageMagicSlideshowForJoomla($baseImagePath.$imageData['name'], 'original', 'custom_slideshow');
            $imageData['link'] = $imageData['link'];
                                 //empty($imageData['link']) ? false : $imageData['link'];
        }
        $magicslideshowHTML = $magicslideshow->getMainTemplate($imagesData, array('id' => 'customMagicSlideshow'));
        //require(JModuleHelper::getLayoutPath('mod_magicslideshow'));//modules/mod_magicslideshow/tmpl/default.php

        $displayMagicSlideshowForJoomlaHeaders = true;

        /*
        //NOTE: display headers if need it
        if(!defined('MagicSlideshow_HEADERS_LOADED')) {
            define('MagicSlideshow_HEADERS_LOADED', true);
            $magicslideshow->params->resetProfile();
            $magicslideshowURL = MAGICTOOLBOX_JURI_BASE.'/media/mod_magicslideshow';
            $magicslideshowHeaders = array();
            $magicslideshowHeaders[] = "\n".$magicslideshow->getHeadersTemplate($magicslideshowURL);
            $magicslideshowHeaders[] = "\n<link type=\"text/css\" href=\"{$magicslideshowURL}/module.css\" rel=\"stylesheet\" media=\"screen\" />\n";
            echo implode($magicslideshowHeaders);
        }
        */

        echo $magicslideshowHTML;

    }

}
