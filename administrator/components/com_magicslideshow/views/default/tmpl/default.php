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

//NOTE: load tooltip behavior
JHtml::_('behavior.tooltip');

$excludedParams = array('enable-effect', 'thumb-max-width', 'thumb-max-height', 'selector-max-width', 'selector-max-height', 'square-images', 'image-quality', 'imagemagick', 'use-original-file-names');

?>
<form action="<?php echo JRoute::_('index.php?option=com_magicslideshow'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
<div id="container"<?php if(JVERSION_16) echo ' class="jommla-ver-16"'; ?>>
<ul class="magic_tabs">
<?php foreach($this->profiles as $profileId => $profileTitle) { ?>
<li><a id="<?php echo $profileId; ?>" onclick="return magic_changeTab(this);" <?php if($profileId == $this->tab) echo 'class="tabactive" '; ?>href="#"><?php echo $profileTitle; ?></a></li>
<?php } ?>
</ul>
<div class="magic_tabContWrapper">
<?php foreach($this->paramsMap as $profileId => $groups) {
    $this->tool->params->setProfile($profileId);
?>
<div id="<?php echo $profileId; ?>_content" class="magic_tabCont<?php if($profileId == $this->tab) echo ' magic_active'; ?>">
    <?php foreach($groups as $groupTitle => $params) { ?>
    <fieldset>
    <legend><?php echo $groupTitle; ?></legend>

        <?php
        if($profileId == 'custom_slideshow' && $groupTitle == 'Setup slideshow') {
            if(!empty($this->imagesData)) {
        ?>
                <div style="margin: 10px 0 10px 0;">
                <table id="magicslideshow_images" cellspacing="0" cellpadding="0" class="table">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Link</th>
                    <th>Order</th>
                    <th>Exclude</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($this->imagesData as $imageData) {
                ?>
                    <tr id="row-<?php echo $imageData['id']; ?>">
                        <td><img src="<?php echo $this->baseImageUrl.$imageData['name']; ?>" alt="<?php echo $imageData['title']; ?>" title="<?php echo $imageData['title']; ?>" /></td>
                        <td style="vertical-align: top;"><input type="text" name="images-data[<?php echo $imageData['id']; ?>][title]" id="title-<?php echo $imageData['id']; ?>" value="<?php echo $imageData['title']; ?>" /></td>
                        <td style="vertical-align: top;"><textarea name="images-data[<?php echo $imageData['id']; ?>][description]" id="description-<?php echo $imageData['id']; ?>" ><?php echo $imageData['description']; ?></textarea></td>
                        <td style="vertical-align: top;"><input type="text" name="images-data[<?php echo $imageData['id']; ?>][link]" id="link-<?php echo $imageData['id']; ?>" value="<?php echo $imageData['link']; ?>" /></td>
                        <td style="vertical-align: top;"><input type="text" name="images-data[<?php echo $imageData['id']; ?>][order]" id="order-<?php echo $imageData['id']; ?>" value="<?php echo $imageData['order']; ?>" class="input-order" /></td>
                        <td class="center"><input type="checkbox" name="images-data[<?php echo $imageData['id']; ?>][exclude]" id="exclude-<?php echo $imageData['id']; ?>" value="<?php echo $imageData['exclude']; ?>" <?php echo intval($imageData['exclude'])?'checked="checked" ':''; ?> /></td>
                        <td class="center"><input type="checkbox" name="images-data[<?php echo $imageData['id']; ?>][delete]" id="delete-<?php echo $imageData['id']; ?>" value="0" /></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
                </table>
                </div>
            <?php
            }
            ?>
            <div class="upload-container">
                <input class="upload-button button" type="button" value="Upload images" />
                <input class="upload-file" type="file" name="magicslideshow_image_files[]" id="upload-file" multiple="multiple" accept="image/*" size="1" onchange="javascript: submitbutton('apply');"/>
            </div>
        <?php
        }
        ?>

        <?php foreach($params as $paramId) {
            $paramValue = $this->tool->params->getValue($paramId);
            $paramEnable = ($profileId == 'default'
                            || in_array($paramId, $excludedParams)
                            || $groupTitle == 'Watermark'
                            || $this->tool->params->paramExists($paramId));
        ?>
        <label for="<?php echo $profileId.'-'.$paramId; ?>"><?php echo $this->tool->params->getLabel($paramId); ?></label>
        <div class="margin-form">
        <?php
        switch($this->tool->params->getType($paramId)) {
            case "array":
                if($this->tool->params->getSubType($paramId, $this->tool->params->generalProfile) == 'radio') {
                    foreach($this->tool->params->getValues($paramId) as $value) {
                        ?><input type="radio" value="<?php echo $value; ?>"<?php echo (($paramValue == $value)?' checked="checked"':''); ?><?php echo $paramEnable ? '' : ' disabled="disabled"'; ?> name="magicslideshow<?php echo "[{$profileId}][{$paramId}]"; ?>" id="<?php echo $profileId.'-'.$paramId.'-'.$value; ?>" /><?php
                        ?><label class="t" for="<?php echo $profileId.'-'.$paramId.'-'.$value; ?>"><?php
                        $valueLower = strtolower($value);
                        if($valueLower == "yes")
                            echo '<img src="'.$this->imageUrl.'yes.gif" alt="Enabled" title="Enabled" />';
                        else if($valueLower == "no")
                            echo '<img src="'.$this->imageUrl.'no.gif" alt="Disabled" title="Disabled" />';
                        else if($valueLower == "left")
                            echo '<img src="'.$this->imageUrl.'left.gif" alt="Left" title="Left" />';
                        else if($valueLower == "right")
                            echo '<img src="'.$this->imageUrl.'right.gif" alt="Right" title="Right" />';
                        else if($valueLower == "top")
                            echo '<img src="'.$this->imageUrl.'top.gif" alt="Top" title="Top" />';
                        else if($valueLower == "bottom")
                            echo '<img src="'.$this->imageUrl.'bottom.gif" alt="Bottom" title="Bottom" />';
                        else echo $value;
                        ?></label><?php
                    }
                } else if($this->tool->params->getSubType($paramId, $this->tool->params->generalProfile) == 'select') {
                    ?><select name="magicslideshow<?php echo "[{$profileId}][{$paramId}]"; ?>" id="<?php echo $profileId.'-'.$paramId; ?>"<?php echo $paramEnable ? '' : ' disabled="disabled"'; ?>><?php
                    foreach($this->tool->params->getValues($paramId) as $value) {
                        ?><option value="<?php echo $value; ?>"<?php echo (($paramValue==$value)?' selected="selected"':''); ?>><?php echo $value; ?></option><?php
                    }
                    ?></select><?php
                } else {
                    ?><input type="text" name="magicslideshow<?php echo "[{$profileId}][{$paramId}]"; ?>" id="<?php echo $profileId.'-'.$paramId; ?>" value="<?php echo $paramValue; ?>"<?php echo $paramEnable ? '' : ' disabled="disabled"'; ?> /><?php
                }
                break;
            case "num":
            case "text":
            default:
                ?><input type="text" name="magicslideshow<?php echo "[{$profileId}][{$paramId}]"; ?>" id="<?php echo $profileId.'-'.$paramId; ?>" value="<?php echo $paramValue; ?>"<?php echo $paramEnable ? '' : ' disabled="disabled"'; ?> /><?php
        }
        if($profileId != 'default' && !in_array($paramId, $excludedParams) && $groupTitle != 'Watermark') {
            if($paramEnable) {
                echo '&nbsp;&nbsp;<a href="#" onclick="return useDefaultOption(this, \''.$paramId.'\', \''.$profileId.'\');">use default option</a>';
            } else {
                echo '&nbsp;&nbsp;<a href="#" onclick="return useDefaultOption(this, \''.$paramId.'\', \''.$profileId.'\');" class="optionDisabled">edit</a>';
            }
        }
        $hint = '';
        if($this->tool->params->getDescription($paramId))
            $hint = $this->tool->params->getDescription($paramId);
        if($this->tool->params->getType($paramId) != "array" && $this->tool->params->valuesExists($paramId, '', false)) {
            if($hint != '') $hint .= "<br />";
            $hint .= "#allowed values: ".implode(", ",$this->tool->params->getValues($paramId));
        }
        if($hint != '') {
            ?><p class="magic_hint clear"><?php echo $hint; ?></p><?php
        }
        ?>
        </div>
        <div class="clear pspace"></div>
        <?php } ?>
    </fieldset>
    <?php } ?>
</div>
<?php } ?>
</div>
<input type="hidden" id="magic_tabs_current" name="magic_tabs_current" value="<?php echo $this->tab; ?>" />
<script type="text/javascript">
//<![CDATA[
var magic_tabs_current = '<?php echo $this->tab; ?>';
function magic_changeTab(elm) {
    if(document.getElementById(magic_tabs_current)) {
        document.getElementById(magic_tabs_current).className = '';
        document.getElementById(magic_tabs_current+'_content').className = 'magic_tabCont';
    }
    magic_tabs_current = document.getElementById('magic_tabs_current').value = elm.id;
    document.getElementById(magic_tabs_current).className = 'tabactive';
    document.getElementById(magic_tabs_current+'_content').className = 'magic_tabCont magic_active';
    elm.blur();
    return false;
}
//]]>
</script>
<div class="clear"></div>
<script type="text/javascript">
//<![CDATA[
var profiles = ["<?php echo implode('", "', array_keys($this->profiles)); ?>"];
function magicToolboxHasClass(element, className) {
    return element.className.match(new RegExp('(\\s|^)'+className+'(\\s|$)'));
}
function magicToolboxAddClass(element, className) {
    if(!this.magicToolboxHasClass(element, className)) element.className += " "+className;
}
function magicToolboxRemoveClass(element, className) {
    if(magicToolboxHasClass(element, className)) {
        var reg = new RegExp('(\\s|^)'+className+'(\\s|$)');
        element.className = element.className.replace(reg, ' ');
    }
}

function useDefaultOption(anchorEl, optionId, profileId) {
    var elements = null;
    if(magicToolboxHasClass(anchorEl, 'optionDisabled')) {
        magicToolboxRemoveClass(anchorEl, 'optionDisabled');
        anchorEl.innerHTML = 'use default option';
        elements = anchorEl.parentNode.getElementsByTagName('select');
        for(var i = 0, l = elements.length; i < l; i++) {
            elements[i].removeAttribute('disabled');
        }
        elements = anchorEl.parentNode.getElementsByTagName('input');
        for(var i = 0, l = elements.length; i < l; i++) {
            elements[i].removeAttribute('disabled');
        }
    } else {
        magicToolboxAddClass(anchorEl, 'optionDisabled');
        anchorEl.innerHTML = 'edit';
        elements = anchorEl.parentNode.getElementsByTagName('select');
        for(var i = 0, l = elements.length; i < l; i++) {
            elements[i].setAttribute('disabled', true);
            var value = document.getElementById('default-'+optionId).value;
            for(var j = 0, ol = elements[i].options.length; j < ol; j++) {
                if(elements[i].options[j].value == value) {
                    elements[i].value = value;
                    elements[i].selectedIndex = j;
                    break;
                }
            }
        }
        elements = anchorEl.parentNode.getElementsByTagName('input');
        for(var i = 0, l = elements.length; i < l; i++) {
            if(elements[i].getAttribute('type') == 'text') {
                elements[i].setAttribute('disabled', true);
                elements[i].value = document.getElementById('default-'+optionId).value;
            } else if(elements[i].getAttribute('type') == 'radio') {
                elements[i].setAttribute('disabled', true);
                var radios = document.getElementsByName('magicslideshow[default]['+optionId+']');
                var j = 0, rl;
                for(rl = radios.length; j < rl; j++) {
                    if(radios[j].checked) {
                        break;
                    }
                }
                if(j != rl) {
                    var id = radios[j].id.replace(/^default/, profileId);
                    radios = document.getElementsByName(elements[i].name);
                    for(j = 0, rl = radios.length; j < rl; j++) {
                        radios[j].checked = false;
                        radios[j].setAttribute('disabled', true);
                    }
                    document.getElementById(id).checked = true;
                }
                break;
            }
        }
    }
    return false;
}

var defaultContent = document.getElementById('default_content');
var elements = defaultContent.getElementsByTagName('select');
for(var i = 0, l = elements.length; i < l; i++) {
    elements[i].onchange = function() {
        var element = null;
        for(var k = 0; k < profiles.length; k++) {
            if(profiles[k] == 'default') continue;
            element = document.getElementById(this.id.replace(/^default/, profiles[k]));
            if(element && element.disabled) {
                for(var j = 0, ol = element.options.length; j < ol; j++) {
                    if(element.options[j].value == this.value) {
                        element.value = this.value;
                        element.selectedIndex = j;
                        break;
                    }
                }
            }
        }
    }
}
elements = defaultContent.getElementsByTagName('input');
for(var i = 0, l = elements.length; i < l; i++) {
    if(elements[i].getAttribute('type') == 'text') {
        elements[i].onchange = function() {
            var element = null;
            for(var k = 0; k < profiles.length; k++) {
                if(profiles[k] == 'default') continue;
                element = document.getElementById(this.id.replace(/^default/, profiles[k]));
                if(element && element.disabled) {
                    element.value = this.value
                }
            }
        }
    } else if(elements[i].getAttribute('type') == 'radio') {
        elements[i].onchange = function() {
            var element = null, radios = null, rl = null;
            for(var k = 0; k < profiles.length; k++) {
                if(profiles[k] == 'default') continue;
                radios = document.getElementsByName(this.name.replace(/\[default\]/, '['+profiles[k]+']'));
                rl = radios.length;
                if(rl && radios[0].disabled) {
                    for(var j = 0; j < rl; j++) {
                        radios[j].checked = false;
                    }
                    element = document.getElementById(this.id.replace(/^default/, profiles[k]));
                    if(element) {
                        element.checked = true;
                    }
                }
            }
        }
    }
}

//]]>
</script>

</div>
</form>
