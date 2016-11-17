<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<form id="searchform" name="searchform" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get">
<div class="fieldcontainer tbslboxname box20br_radius">
<?php $output = '<input name="keyword" id="mod_virtuemart_search" class="searchfield tbslboxname tbsoxname" type="text" size="'.$width.'" placeholder="'.$text.'" tabindex="1" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';
 $image = JURI::base().'components/com_virtuemart/assets/images/vmgeneral/search.png' ;

			if ($button) :
			    if ($imagebutton) :
			        $button = '<input name="searchbtn" id="searchbtn" value="" type="image" src="'.$image.'" onclick="this.form.keyword.focus();"/>';
			    else :
			        $button = '<input type="submit" value="" name="searchbtn" id="searchbtn" onclick="this.form.keyword.focus();"/>';
			    endif;
		

			switch ($button_pos) :
			    case 'top' :
				    $button = $button.'<br />';
				    $output = $button.$output;
				    break;

			    case 'bottom' :
				    $button = '<br />'.$button;
				    $output = $output.$button;
				    break;

			    case 'right' :
				    $output = $output.$button;
				    break;

			    case 'left' :
			    default :
				    $output = $button.$output;
				    break;
			endswitch;
			endif;
			
			echo $output;
?>
</div>
		<input type="hidden" name="limitstart" value="0" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="category" />
	  </form>

