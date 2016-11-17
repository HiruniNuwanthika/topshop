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

//NOTE: Import joomla controller library
jimport('joomla.application.component.controller');

if(!defined('MAGICTOOLBOX_LEGACY_CONTROLLER_DEFINED')) {
    define('MAGICTOOLBOX_LEGACY_CONTROLLER_DEFINED', true);
    if(JVERSION_256) {
        class MagicToolboxLegacyController extends JControllerLegacy {}
    } else {
        class MagicToolboxLegacyController extends JController {}
    }
}

class MagicslideshowController extends MagicToolboxLegacyController {

    public function display($cachable = false, $urlparams = false) {
        JRequest::setVar('view', JRequest::getCmd('view', 'default'));
        parent::display($cachable, $urlparams);
        return $this;
    }

    public function install() {

        $app = JFactory::getApplication();
        $database = JFactory::getDBO();

        $pluginPackageDir = dirname(__FILE__).DS.'plugin';
        $modulePackageDir = dirname(__FILE__).DS.'module';

        //NOTE: to fix URL's in css files
        $this->fixCSS();

        jimport('joomla.installer.installer');

        if(!JVERSION_16) {
            //NOTE: it is important, that XML file name matches module name. Otherwise, Joomla wouldn't show parameters and additional info stored in XML.
            copy($pluginPackageDir.DS.'magicslideshow_j15.xml', $pluginPackageDir.DS.'magicslideshow.xml');
            copy($modulePackageDir.DS.'mod_magicslideshow_j15.xml', $modulePackageDir.DS.'mod_magicslideshow.xml');
        }

        $installer = new JInstaller();//JInstaller::getInstance();
        $installer->setOverwrite(true);
        if($installer->install($pluginPackageDir)) {
            $app->enqueueMessage(JText::_('COM_MAGICSLIDESHOW_INSTALL_PLUGIN_SUCCESS'), 'message');

            //NOTE: enable plugin
            if(JVERSION_16) {
                $query = "UPDATE `#__extensions` SET `enabled`=1 WHERE `name`='plg_system_magicslideshow'";
            } else {
                $query = "UPDATE `#__plugins` SET `published`=1, `name`='System - MagicSlideshow' WHERE `name`='plg_system_magicslideshow'";
            }
            $database->setQuery($query);
            if(!$database->query()) {
                $app->enqueueMessage(JText::_($database->getErrorMsg()), 'error');
            }

            $installer = new JInstaller();//JInstaller::getInstance();
            $installer->setOverwrite(true);
            if($installer->install($modulePackageDir)) {
                $app->enqueueMessage(JText::_('COM_MAGICSLIDESHOW_INSTALL_MODULE_SUCCESS'), 'message');

                //NOTE: update 'Details'
                $title = JText::_('COM_MAGICSLIDESHOW_MODULE_TITLE');
                $position = JVERSION_30 ? 'position-3' : (JVERSION_16 ? 'position-12' : 'user1');
                $database->setQuery("UPDATE `#__modules` SET `title`='{$title}', `ordering`=0, `position`='{$position}', `published`=1, `showtitle`=0 WHERE `module`='mod_magicslideshow'");
                if(!$database->query()) {
                    $app->enqueueMessage(JText::_($database->getErrorMsg()), 'error');
                }

                //NOTE: update 'Menu Assignment'
                $database->setQuery("INSERT IGNORE INTO `#__modules_menu` (`moduleid`, `menuid`) SELECT `m`.`id`, 0 FROM `#__modules`  AS `m` WHERE `m`.`module`='mod_magicslideshow'");
                if(!$database->query()) {
                    $app->enqueueMessage(JText::_($database->getErrorMsg()), 'error');
                }
            } else {
                $app->enqueueMessage(JText::_('COM_MAGICSLIDESHOW_INSTALL_MODULE_ERROR'), 'error');
            }
        } else {
            $app->enqueueMessage(JText::_('COM_MAGICSLIDESHOW_INSTALL_PLUGIN_ERROR'), 'error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_magicslideshow', false));

        return $this;
    }

    public function fixCSS() {

        //NOTE: to fix URL's in css files

        $path = dirname(__FILE__).DS.'plugin'.DS.'media';
        $list = glob($path.'/*');
        $files = array();
        if(is_array($list)) {
            for($i = 0; $i < count($list); $i++) {
                if(is_dir($list[$i])) {
                    if(!in_array(basename($list[$i]), array('.svn', '.git'))) {
                        $add = glob($list[$i].'/*');
                        if(is_array($add)) {
                            $list = array_merge($list, $add);
                        }
                    }
                } else if(preg_match('#\.css$#i', $list[$i])) {
                    $files[] = $list[$i];
                }
            }
        }

        foreach($files as $file) {
            if(!is_writable($file)) {
                continue;
            }
            $cssPath = dirname($file);
            $cssRelPath = str_replace($path, '', $cssPath);
            $toolPath = JURI::root(true).'/media/plg_system_magicslideshow'.$cssRelPath;
            $pattern = '#url\(\s*(\'|")?(?!data:|mhtml:|http(?:s)?:|/)([^\)\s\'"]+?)(?(1)\1)\s*\)#is';
            $replace = 'url($1'.$toolPath.'/$2$1)';
            $fileContents = file_get_contents($file);
            $fixedFileContents = preg_replace($pattern, $replace, $fileContents);
            //preg_match_all($pattern, $fileContents, $matches, PREG_SET_ORDER);
            //debug_log($matches);
            if($fixedFileContents != $fileContents) {
                $fp = fopen($file, 'w+');
                if($fp) {
                    fwrite($fp, $fixedFileContents);
                    fclose($fp);
                }
            }
        }

    }

    public function apply() {
        $this->saveParamsToDB();
        $this->setMessage(JText::_('COM_MAGICSLIDESHOW_SAVE_TEXT'), 'message');
        $tab = JRequest::getVar('magic_tabs_current', 'default', 'post');
        $tab = ($tab == 'default' ? '' : '&tab='.$tab);
        $this->setRedirect(JRoute::_('index.php?option=com_magicslideshow'.$tab, false));
        return $this;
    }

    public function save() {
        $this->saveParamsToDB();
        $this->setMessage(JText::_('COM_MAGICSLIDESHOW_SAVE_TEXT'), 'message');
        $this->setRedirect(JRoute::_('index.php', false));
        return $this;
    }

    public function cancel() {
        $this->setRedirect(JRoute::_('index.php', false));
        return $this;
    }

    public function saveParamsToDB() {
        $post = JRequest::get('post');
        $database = JFactory::getDBO();
        if(!empty($post) && !empty($post['magicslideshow']) && is_array($post['magicslideshow'])) {
            $database->setQuery("UPDATE `#__magicslideshow_config` SET disabled='1' WHERE profile!='default'");
            $database->query();
            $profiles = array('default' => false, 'custom_slideshow' => false);
            foreach($post['magicslideshow'] as $profile => $params) {
                if(!isset($profiles[$profile]) || !is_array($params)) continue;
                foreach($params as $name => $value) {
                    $value = $database->quote($value);
                    $database->setQuery("UPDATE `#__magicslideshow_config` SET `value`={$value}, `disabled`='0' WHERE profile='{$profile}' AND name='{$name}'");
                    $database->query();
                }
            }
        }

        $baseImagePath = JPATH_ROOT.DS.'images'.DS.'magicslideshow'.DS;//JPATH_BASE depends on the interface
        $escapeMethod = JVERSION_25 ? 'escape' : 'getEscaped';

        if(!empty($post) && !empty($post['images-data']) && is_array($post['images-data'])) {
            $imagesData = & $post['images-data'];
            foreach($imagesData as $imageId => $imageData) {
                if(isset($imageData['delete'])) {
                    $database->setQuery("SELECT `name` FROM `#__magicslideshow_images` WHERE `id`={$imageId}");
                    $name = $database->loadResult();
                    if(!empty($name)) {
                        @unlink($baseImagePath.$name);
                        $database->setQuery("DELETE FROM `#__magicslideshow_images` WHERE `id`={$imageId}");
                        $database->query();
                    }
                } else {
                    $imageData['title'] = str_replace('"', '&quot;', $imageData['title']);
                    $imageData['title'] = $database->{$escapeMethod}($imageData['title']);
                    $imageData['description'] = str_replace('"', '&quot;', $imageData['description']);
                    $imageData['description'] = $database->{$escapeMethod}($imageData['description']);
                    $imageData['link'] = str_replace('"', '&quot;', $imageData['link']);
                    $imageData['link'] = $database->{$escapeMethod}($imageData['link']);
                    $imageData['order'] = intval($imageData['order']);
                    $imageData['exclude'] = isset($imageData['exclude']) ? '1' : '0';
                    $database->setQuery(
                        "UPDATE `#__magicslideshow_images` SET ".
                        "`title`='{$imageData['title']}', ".
                        "`description`='{$imageData['description']}', ".
                        "`link`='{$imageData['link']}', ".
                        "`order`={$imageData['order']}, ".
                        "`exclude`='{$imageData['exclude']}' ".
                        "WHERE `id`={$imageId}"
                    );
                    $database->query();
                }
            }
        }

        $files = JRequest::get('files');
        if(!empty($files) && !empty($files['magicslideshow_image_files']['tmp_name'])) {
            if(is_dir($baseImagePath) || mkdir($baseImagePath)) {
                foreach($files['magicslideshow_image_files']['tmp_name'] as $key => $tempName) {
                    if(!empty($tempName) && file_exists($tempName)) {
                        $name = preg_replace('/[^a-zA-Z0-9()_\.-]/is', '', $files['magicslideshow_image_files']['name'][$key]);
                        $ext = substr($name, strrpos($name, '.'));
                        $name = substr($name, 0, -strlen($ext));
                        $index = 0;
                        $suffix = '';
                        while(file_exists($baseImagePath.$name.$suffix.$ext)) {
                            $suffix = "({$index})";
                            $index++;
                        }
                        if(!move_uploaded_file($tempName, $baseImagePath.$name.$suffix.$ext)) {
                            //An error occurred during the image upload.
                            break;
                        }
                        $database->setQuery("INSERT INTO `#__magicslideshow_images` (`name`) VALUES ('{$name}{$suffix}{$ext}')");
                        $database->query();
                    }
                }
            }
        }
    }

}
