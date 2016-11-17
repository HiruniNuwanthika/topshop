<?php

defined('_JEXEC') or die; 

function modChrome_linelab($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
	if (!empty ($module->content)) : ?>
	<div class="box25bg box25br box25bg_shadow box25br_radius botmodule moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>"> 
		<?php if ($module->showtitle != 0) : ?>      
		<h<?php echo $headerLevel; ?> class="tlmbfoxname"><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
		<?php endif; ?>
		<div class="linelabmodule"><span class="dboxname"></span></div>
	        <?php echo $module->content; ?>
        </div>
	<?php endif;    
}
               