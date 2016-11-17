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

if(!function_exists('com_install')) {
    function com_install() {
        return installMagicslideshowForJoomla();
    }
}

if(!function_exists('com_uninstall')) {
    function com_uninstall() {
        return uninstallMagicslideshowForJoomla();
    }
}

function installMagicslideshowForJoomla() {

    $database = JFactory::getDBO();
    $database->setQuery("SELECT COUNT(*) as `count` FROM `#__magicslideshow_config` LIMIT 1");
    $results = $database->loadObject();
    if($results->count) {
        $database->setQuery("DROP TABLE IF EXISTS `#__magicslideshow_config_bak`;");
        $database->query();
        $database->setQuery("RENAME TABLE `#__magicslideshow_config` TO `#__magicslideshow_config_bak`;");
        if($database->query()) {
            $database->setQuery("CREATE TABLE `#__magicslideshow_config` LIKE `#__magicslideshow_config_bak`;");
            if($database->query()) {
                $results->count = 0;
            }
        }
    }
    if($results->count == 0) {
        $query = <<<SQL
INSERT INTO `#__magicslideshow_config` (`profile`, `name`, `value`, `default`, `disabled`) VALUES
 ('default', 'width', 'auto', 'auto', '0'),
 ('default', 'height', '100%', '100%', '0'),
 ('default', 'orientation', 'horizontal', 'horizontal', '0'),
 ('default', 'arrows', 'Yes', 'Yes', '0'),
 ('default', 'loop', 'Yes', 'Yes', '0'),
 ('default', 'effect', 'slide', 'slide', '0'),
 ('default', 'effect-speed', '600', '600', '0'),
 ('default', 'autoplay', 'Yes', 'Yes', '0'),
 ('default', 'slide-duration', '3000', '3000', '0'),
 ('default', 'shuffle', 'No', 'No', '0'),
 ('default', 'kenburns', 'No', 'No', '0'),
 ('default', 'pause', 'false', 'false', '0'),
 ('default', 'selectors', 'none', 'none', '0'),
 ('default', 'selectors-style', 'bullets', 'bullets', '0'),
 ('default', 'selectors-size', '70', '70', '0'),
 ('default', 'selectors-eye', 'Yes', 'Yes', '0'),
 ('default', 'caption', 'No', 'No', '0'),
 ('default', 'caption-effect', 'fade', 'fade', '0'),
 ('default', 'fullscreen', 'No', 'No', '0'),
 ('default', 'preload', 'Yes', 'Yes', '0'),
 ('default', 'keyboard', 'Yes', 'Yes', '0'),
 ('default', 'loader', 'Yes', 'Yes', '0'),
 ('default', 'show-message', 'No', 'No', '0'),
 ('default', 'message', '', '', '0'),
 ('custom_slideshow', 'enable-effect', 'Yes', 'Yes', '0'),
 ('custom_slideshow', 'thumb-max-width', '400', '400', '0'),
 ('custom_slideshow', 'thumb-max-height', '400', '400', '0'),
 ('custom_slideshow', 'selector-max-width', '70', '70', '1'),
 ('custom_slideshow', 'selector-max-height', '70', '70', '1'),
 ('custom_slideshow', 'square-images', 'disable', 'disable', '1'),
 ('custom_slideshow', 'width', 'auto', 'auto', '0'),
 ('custom_slideshow', 'height', '62.5%', '62.5%', '0'),
 ('custom_slideshow', 'orientation', 'horizontal', 'horizontal', '1'),
 ('custom_slideshow', 'arrows', 'Yes', 'Yes', '0'),
 ('custom_slideshow', 'loop', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'effect', 'slide', 'slide', '1'),
 ('custom_slideshow', 'effect-speed', '600', '600', '1'),
 ('custom_slideshow', 'autoplay', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'slide-duration', '3000', '3000', '1'),
 ('custom_slideshow', 'shuffle', 'No', 'No', '1'),
 ('custom_slideshow', 'kenburns', 'No', 'No', '1'),
 ('custom_slideshow', 'pause', 'false', 'false', '1'),
 ('custom_slideshow', 'selectors', 'none', 'none', '1'),
 ('custom_slideshow', 'selectors-style', 'bullets', 'bullets', '1'),
 ('custom_slideshow', 'selectors-size', '70', '70', '1'),
 ('custom_slideshow', 'selectors-eye', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'caption', 'Yes', 'Yes', '0'),
 ('custom_slideshow', 'caption-effect', 'fade', 'fade', '1'),
 ('custom_slideshow', 'fullscreen', 'Yes', 'Yes', '0'),
 ('custom_slideshow', 'preload', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'keyboard', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'loader', 'Yes', 'Yes', '1'),
 ('custom_slideshow', 'show-message', 'No', 'No', '0'),
 ('custom_slideshow', 'message', '', '', '1'),
 ('custom_slideshow', 'imagemagick', 'auto', 'auto', '1'),
 ('custom_slideshow', 'image-quality', '100', '100', '1'),
 ('custom_slideshow', 'watermark', '', '', '1'),
 ('custom_slideshow', 'watermark-max-width', '30%', '30%', '1'),
 ('custom_slideshow', 'watermark-max-height', '30%', '30%', '1'),
 ('custom_slideshow', 'watermark-opacity', '50', '50', '1'),
 ('custom_slideshow', 'watermark-position', 'center', 'center', '1'),
 ('custom_slideshow', 'watermark-offset-x', '0', '0', '1'),
 ('custom_slideshow', 'watermark-offset-y', '0', '0', '1'),
 ('default', 'version', '3.2.4', '3.2.4', '0');
SQL;
        $database->setQuery($query);
        if(!$database->query()) {
            return JError::raiseWarning(500, $database->getError());
        }
    }

    $url = 'index.php?option=com_magicslideshow&task=install';
?>
<style>
.magictoolbox-message-container h1 {
    color: #468847;
}
.magictoolbox-message-container {
    color: #468847;   
    background-color: #DFF0D8;
    border: 1px solid #D6E9C6;
    border-radius: 4px;
    margin-bottom: 18px;
    padding: 8px 35px 8px 14px;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}
</style>
<div class="magictoolbox-message-container">
<h1>Please wait...</h1>
<h2>The plugin and frontend module will be installed automatically...</h2>
<h2>Please click <a href="<?php echo $url; ?>" style="color: black;">here</a> if you are not automatically redirected within <span id="redirect_timer">3</span> seconds</h2>
<script language="javascript" type="text/javascript">
var intervalCounter = 3;
var intervalID = setInterval(function() {
    if(intervalCounter) {
        intervalCounter--;
        document.getElementById('redirect_timer').innerHTML = intervalCounter;
    }
    if(!intervalCounter) {
        clearInterval(intervalID);
        document.location.href = '<?php echo $url; ?>';
    }
}, 1000);
</script>
</div>
<?php
    sendJoomlaMagicslideshowModuleStat('install');
    return true;
}

function uninstallMagicslideshowForJoomla() {

    if(version_compare(JVERSION, '1.6.0', '<')) {
        //NOTE: need to load lang file for uninstall string
        $lang = JFactory::getLanguage();
        $lang->load('com_magicslideshow', JPATH_ADMINISTRATOR, null, false);
    }

    $database = JFactory::getDBO();

    //NOTE: uninstall plugin
    if(version_compare(JVERSION, '1.6.0', '<')) {
        $query = "DELETE FROM `#__plugins` WHERE element='magicslideshow'";
    } else {
        $query = "DELETE FROM `#__extensions` WHERE element='magicslideshow'";
    }
    $database->setQuery($query);
    $database->query();

    //$manifest = file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow'.DS.'magicslideshow.xml') ? simplexml_load_file(JPATH_SITE.DS.'pugins'.DS.'system'.DS.'magicslideshow'.DS.'magicslideshow.xml') : false;//SimpleXMLElement
    //if($manifest) {
    //}
    if(is_file(JPATH_SITE.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_magicslideshow.ini')) {
        JFile::delete(JPATH_SITE.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_magicslideshow.ini');
    }
    if(is_file(JPATH_SITE.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_magicslideshow.sys.ini')) {
        JFile::delete(JPATH_SITE.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_magicslideshow.sys.ini');
    }
    if(is_dir(JPATH_SITE.DS.'media'.DS.'plg_system_magicslideshow')) {
        JFolder::delete(JPATH_SITE.DS.'media'.DS.'plg_system_magicslideshow');
    }
    if(version_compare(JVERSION, '1.6.0', '<')) {
        if(is_dir(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow_classes')) {
            JFolder::delete(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow_classes');
        }
        if(is_file(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow.php')) {
            JFile::delete(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow.php');
        }
        if(is_file(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow.xml')) {
            JFile::delete(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow.xml');
        }
    } else {
        if(is_dir(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow')) {
            JFolder::delete(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'magicslideshow');
        }
    }

    //NOTE: uninstall module
    $module = 'mod_magicslideshow';
    $query = "SELECT `id` FROM `#__modules` WHERE module='{$module}'";
    $database->setQuery($query);
    $modIDs = version_compare(JVERSION, '1.7.0', '<') ? $database->loadResultArray() : $database->loadColumn();
    if(count($modIDs)) {
        $modID = implode(',', $modIDs);
        $query = 'DELETE FROM #__modules_menu WHERE moduleid IN ('.$modID.')';
        $database->setQuery($query);
        $database->query();
        $query = "DELETE FROM `#__modules` WHERE module='{$module}'";
        $database->setQuery($query);
        $database->query();
        if(version_compare(JVERSION, '1.6.0', '>=')) {
            $query = "DELETE FROM `#__extensions` WHERE element='{$module}'";
            $database->setQuery($query);
            $database->query();
        }
        //$query = "DELETE FROM `#__menu` WHERE link LIKE '%{$module}%'";
    }

    $manifest = file_exists(JPATH_SITE.DS.'modules'.DS.$module.DS."{$module}.xml") ? simplexml_load_file(JPATH_SITE.DS.'modules'.DS.$module.DS."{$module}.xml") : false;//SimpleXMLElement
    if($manifest) {
        $elements = array('media', 'languages');
        foreach($elements as $elementPath) {
            $element = $manifest->$elementPath;//SimpleXMLElement
            if(is_a($element, 'SimpleXMLElement') && count($element->children())) {
                switch($elementPath) {
                    case 'media':
                        $source = JPATH_SITE.DS.'media';
                        $destination = $element->attributes()->destination;
                        if($destination) {
                            $source = $source.DS.$destination;
                        }
                        break;
                    case 'languages':
                        $source = JPATH_SITE.DS.'language';
                        break;
                }
                foreach($element->children() as $child) {
                    if($child->getName() == 'language' && $child->attributes()->tag) {
                        $path = $source.DS.$child->attributes()->tag;
                        if(!JFolder::exists($path)) continue;
                        $path = $path.DS.$child;
                    } else {
                        $path = $source.DS.$child;
                    }
                    if(is_file($path)) {
                        JFile::delete($path);
                    } else if(is_dir($path)) {
                        $val = JFolder::delete($path);
                    }
                }
                //if($elementPath == 'media' && $destination) {
                //    JFolder::delete($source);
                //}
            }
        }
    }

    if(is_dir(JPATH_SITE.DS.'media'.DS.'mod_magicslideshow')) {
        JFolder::delete(JPATH_SITE.DS.'media'.DS.'mod_magicslideshow');
    }
    if(is_dir(JPATH_SITE.DS.'modules'.DS.$module)) {
        JFolder::delete(JPATH_SITE.DS.'modules'.DS.$module);
    }

    //if(is_dir(JPATH_SITE.DS.'media'.DS.'com_magicslideshow')) {
    //    JFolder::delete(JPATH_SITE.DS.'media'.DS.'com_magicslideshow');
    //}

    echo '<div style="background-color: #C3D2E5;">
          <p style="color: #0055BB;font-weight: bold;">'.JText::_('COM_MAGICSLIDESHOW_UNINSTALL_TEXT').'</p>
          </div>';

    sendJoomlaMagicslideshowModuleStat('uninstall');
    return true;

}

class com_magicslideshowInstallerScript {

    function preflight($type, $parent) {
        return true;
    }

    function install($parent) {
        return installMagicslideshowForJoomla();
    }

    function update($parent) {
        return installMagicslideshowForJoomla();
    }

    function uninstall($parent) {
        return uninstallMagicslideshowForJoomla();
    }

    function postflight($type, $parent) {
        return true;
    }

}

function sendJoomlaMagicslideshowModuleStat($action = '') {

    //NOTE: don't send from working copy
    if('working' == 'v3.2.4' || 'working' == 'v2.0.35') {
        return;
    }
    $hostname = 'www.magictoolbox.com';

    $url = $_SERVER['HTTP_HOST'].JURI::root(true);
    $url = urlencode(urldecode($url));

    if(class_exists('joomlaVersion')) {
        //old joomla, 1.0.x
        $versionObj = new joomlaVersion();
    } elseif(class_exists('JVersion')) {
        $versionObj = new JVersion();
    } else {
        return;
    }

    $platformVersion = $versionObj->getShortVersion();

    $path = "api/stat/?action={$action}&tool_name=magicslideshow&license=trial&tool_version=v2.0.35&module_version=v3.2.4&platform_name=joomla&platform_version={$platformVersion}&url={$url}";

    $handle = @fsockopen($hostname, 80, $errno, $errstr, 30);
    if($handle) {
        $headers = "GET /{$path} HTTP/1.1\r\n";
        $headers .= "Host: {$hostname}\r\n";
        $headers .= "Connection: Close\r\n\r\n";
        fwrite($handle, $headers);
        fclose($handle);
    }

}
