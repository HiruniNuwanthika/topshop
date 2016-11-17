<?php

/*------------------------------------------------------------------------
# com_magicslideshow - Magic Slideshow for Joomla
# ------------------------------------------------------------------------
# Magic Toolbox
# Copyright 2011 MagicToolbox.com. All Rights Reserved.
# @license - http://www.opensource.org/licenses/artistic-license-2.0  Artistic License 2.0 (GPL compatible)
# Website: http://www.magictoolbox.com/magicslideshow/modules/joomla/
# Technical Support: http://www.magictoolbox.com/contact/
/*-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access.');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

//NOTE: Import joomla view library
jimport('joomla.application.component.view');

if(!defined('MAGICTOOLBOX_LEGACY_VIEW_DEFINED')) {
    define('MAGICTOOLBOX_LEGACY_VIEW_DEFINED', true);
    if(JVERSION_256) {
        class MagicToolboxLegacyView extends JViewLegacy {}
    } else {
        class MagicToolboxLegacyView extends JView {}
    }
}

class MagicslideshowViewDefault extends MagicToolboxLegacyView {

    function display($tpl = null) {

        JRequest::setVar('hidemainmenu', true);

        $document = JFactory::getDocument();

        $document->addStyleSheet(JURI::root().'media/com_magicslideshow/backend.css');

        JToolBarHelper::title(JText::_('COM_MAGICSLIDESHOW_MANAGER_SETTINGS'), 'magicslideshow.png');
        JToolBarHelper::save('save');//Save & Close
        JToolBarHelper::apply('apply');//Save
        JToolBarHelper::cancel('cancel', 'Close');//Close

        $classesFolded = JVERSION_16 ? 'magicslideshow'.DS.'magicslideshow_classes' : 'magicslideshow_classes';
        require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.$classesFolded.DS.'magicslideshow.module.core.class.php');
        $this->tool = new MagicSlideshowModuleCoreClass();
        $database = JFactory::getDBO();
        $database->setQuery("SELECT `profile`, `name`, `value` FROM `#__magicslideshow_config` WHERE `disabled`='0'");
        $results = $database->loadAssocList();
        if(!empty($results)) {
            foreach($results as $row) {
                $this->tool->params->setValue($row['name'], $row['value'], $row['profile']);
            }
        }

        //NOTE: change subtype for some params to display them like radio
        foreach($this->tool->params->getParams() as $paramId => $param) {
           if($this->tool->params->getSubType($paramId) == 'select' && count($this->tool->params->getValues($paramId)) < 6)
               $this->tool->params->setSubType($paramId, 'radio');
        }

        $this->tab = JRequest::getVar('tab', 'default', 'get');

        $this->profiles = array('default' => 'General', 'custom_slideshow' => 'Home page Slideshow');
        $this->imageUrl = JURI::root().'media/com_magicslideshow/images/';
        $this->paramsMap = array(
			'default' => array(
				'Common settings' => array(
					'width',
					'height',
					'orientation',
					'arrows',
					'loop',
					'effect',
					'effect-speed',
				),
				'Autoplay' => array(
					'autoplay',
					'slide-duration',
					'shuffle',
					'kenburns',
					'pause',
				),
				'Selectors' => array(
					'selectors',
					'selectors-style',
					'selectors-size',
					'selectors-eye',
				),
				'Caption' => array(
					'caption',
					'caption-effect',
				),
				'Other settings' => array(
					'fullscreen',
					'preload',
					'keyboard',
					'loader',
				),
				'Miscellaneous' => array(
					'show-message',
					'message',
				),
			),
			'custom_slideshow' => array(
				'General' => array(
					'enable-effect',
				),
				'Setup slideshow' => array(
				),
				'Positioning and Geometry' => array(
					'thumb-max-width',
					'thumb-max-height',
					'selector-max-width',
					'selector-max-height',
					'square-images',
				),
				'Common settings' => array(
					'width',
					'height',
					'orientation',
					'arrows',
					'loop',
					'effect',
					'effect-speed',
				),
				'Autoplay' => array(
					'autoplay',
					'slide-duration',
					'shuffle',
					'kenburns',
					'pause',
				),
				'Selectors' => array(
					'selectors',
					'selectors-style',
					'selectors-size',
					'selectors-eye',
				),
				'Caption' => array(
					'caption',
					'caption-effect',
				),
				'Other settings' => array(
					'fullscreen',
					'preload',
					'keyboard',
					'loader',
				),
				'Miscellaneous' => array(
					'show-message',
					'message',
					'imagemagick',
					'image-quality',
				),
				'Watermark' => array(
					'watermark',
					'watermark-max-width',
					'watermark-max-height',
					'watermark-opacity',
					'watermark-position',
					'watermark-offset-x',
					'watermark-offset-y',
				),
			),
		);

        $database->setQuery("SELECT * FROM `#__magicslideshow_images` ORDER BY `order`");
        $results = $database->loadAssocList();
        $this->imagesData = empty($results) ? array() : $results;
        //$this->baseImageUrl = JURI::base(true);
        $this->baseImageUrl = JFactory::getURI()->toString();
        $this->baseImageUrl = substr($this->baseImageUrl, 0, strrpos(strtolower($this->baseImageUrl), '/administrator/'));
        $this->baseImageUrl .= '/images/magicslideshow/';

        parent::display($tpl);

    }

}
