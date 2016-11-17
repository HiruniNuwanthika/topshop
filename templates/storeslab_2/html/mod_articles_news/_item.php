<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_news
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$item_heading = $params->get('item_heading', 'h4');
global $collapse_count;
if (!$collapse_count) $collapse_count=1;
?>
<div class="accordion-group box25br">
<?php if ($params->get('item_title')) : ?>
	<div class="accordion-heading">
	<<?php echo $item_heading; ?> class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if ($params->get('link_titles') && $item->link != '') : ?>
		<a class="dboxname accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse<?php echo $collapse_count;?>">
			<?php echo $item->title;?></a>
	<?php else : ?>
		<?php echo $item->title; ?>
	<?php endif; ?>
	</<?php echo $item_heading; ?>>
   </div>
<?php endif; ?>
 <div id="collapse<?php echo $collapse_count;?>" class="accordion-body collapse"><div class="accordion-inner">
<?php if (!$params->get('intro_only')) :
	echo $item->afterDisplayTitle;
endif; ?>

<?php echo $item->beforeDisplayContent; ?>

<?php echo $item->introtext; ?>

<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
	echo '<a class="quickbtn boxcart boxcartbt box4br_radius boxcart_shadow rmnews" href="'.$item->link.'">'.$item->linkText.'</a>';
endif; ?>  </div></div></div>
<?php $collapse_count++; ?>