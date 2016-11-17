<?php
/**
*
* Show the products in a category
*
* @package	VirtueMart
* @subpackage
* @author RolandD
* @author Max Milbers
* @todo add pagination
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 5120 2011-12-18 18:29:26Z electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.modal','a.vmmodal');
vmJsApi::js( 'fancybox/jquery.fancybox-1.3.4.pack');
vmJsApi::css('jquery.fancybox-1.3.4');
	
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});

";
JFactory::getDocument()->addScript(JFactory::getUri()->base(true).'/templates/'.JFactory::getApplication()->getTemplate().'/assets/js/pikachoose/jquery.pikachoose.full.js');
JFactory::getDocument()->addStyleSheet(JFactory::getUri()->base(true).'/templates/'.JFactory::getApplication()->getTemplate().'/assets/css/pikachoose/base.css');
$document = JFactory::getDocument();
$document->addScriptDeclaration($js);
if (JFile::exists(dirname(__FILE__).DS.'default_quicklook.php')) echo $this->loadTemplate('quicklook');
?>
<script type="text/javascript">
window.addEvent('domready',function() {
document.getElements('form[name=ratingForm]').each(function(elm) {
if(elm.getElementById('editable').value==1) {
elm.getElement('.ratingbox').addEvent('mousemove',function(e) {
var stars=this.getElement('span.stars-orange');
var pos=this.getPosition();
var size=this.getSize();
var diff=Math.floor((e.page.x-pos.x)/size.x*<?php echo VmConfig::get ('vm_maximum_rating_scale', 5); ?>)+1;
if(diff><?php echo VmConfig::get ('vm_maximum_rating_scale', 5); ?>) {
diff=5;
}
elm.getElement('input').value=diff;
stars.setStyle('width',diff*(size.x/<?php echo VmConfig::get ('vm_maximum_rating_scale', 5); ?>));
});
elm.getElement('.ratingbox').addEvent('click',function(e) {
elm.getElement('.ratingbox').removeEvents('click');
elm.getElement('.ratingbox').removeEvents('mousemove');
new Request.JSON({
'url':'<?php echo JFactory::getURI()->base(); ?>index.php?option=com_virtuemart&controller=productdetails&task=review',
'method':'post',
'data':elm.toQueryString(),
'onSuccess':function(json,text) {
}
}).send();
});
}
});
});
</script>
<?php
/* Show child categories */
  
  if ( VmConfig::get('showCategory',1)&& empty($this->keyword) ) {
	if ($this->category->haschildren) {

		// Category and Columns Counter
		$iCol = 1;
		$iCategory = 1;

		// Calculating Categories Per Row
		$categories_per_row = VmConfig::get ('categories_per_row', 3);
		$category_cellwidth = ' width' . floor (100 / $categories_per_row);

		// Separator
		$verticalseparator = " varianta2";
		?>

		<div class="category-view">

	<?php // Start the Output
		if (!empty($this->category->children)) {
			foreach ($this->category->children as $category) {

				// Show the horizontal seperator
				if ($iCol == 1 && $iCategory > $categories_per_row) {
					?>
					<div class="boxnone"></div>
					<?php
				}

				// this is an indicator wether a row needs to be opened or not
				if ($iCol == 1) {
					?> 
			<div class="row-fluid">
			<?php }

			// Show the vertical seperator
			if ($iCategory == $categories_per_row or $iCategory % $categories_per_row == 0) {
				$show_vertical_separator = ' ';
			} else {
				$show_vertical_separator = $verticalseparator;
			}

			// Category Link
			$caturl = JRoute::_ ( 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id );

				// Show Category ?>
				<div class="span4">
					<div class="spacer kategorie">
						<h3 class="boxname tlmbfoxname">
							<a href="<?php echo $caturl ?>" class="dboxname" title="<?php echo $category->category_name ?>">
							<?php echo $category->category_name ?>
							<br />
							<?php // if ($category->ids) {
								echo $category->images[0]->displayMediaThumb("",false);
							//} ?>
							</a>
						</h3>
					</div>
				</div>
			<?php
			$iCategory ++;

		// Do we need to close the current row now?
		if ($iCol == $categories_per_row) { ?>
		<div class="clear"></div>
		</div>
			<?php
			$iCol = 1;
		} else {
			$iCol ++;
		}
	}
	}
	// Do we need a final closing row tag?
	if ($iCol != 1) { ?>
		<div class="clear"></div>
		</div>
	<?php } ?>
</div>
<?php }
}

// Show child categories
if (!empty($this->products)) {
	if (!empty($this->keyword)) {
		?>
		<h3 class="boxname tlmbfoxname"><?php echo $this->keyword; ?></h3>
		<?php
	}
	?>

<?php // Category and Columns Counter
$iBrowseCol = 1;
$iBrowseProduct = 1;

// Calculating Products Per Row
$BrowseProducts_per_row = $this->perRow;
$Browsecellwidth = ' width'.floor ( 100 / $BrowseProducts_per_row );
switch ($BrowseProducts_per_row) {
	case 1:
		$spanClass = '12';
		break;
	case 2:
		$spanClass = '6';
		break;
	case 3:
		$spanClass = '4';
		break;
}
// Separator
$verticalseparator = " vertical-separator";
?>

<div class="browse-view" id="browse-view">
	
		<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0&virtuemart_category_id='.$this->category->virtuemart_category_id ); ?>" method="get">
		<?php if ($this->search) { ?>
		<!--BEGIN Search Box --><div class="virtuemart_search btn-group">
		<?php echo $this->searchcustom ?>
		<br />
		<?php echo $this->searchcustomvalues ?>
		<input style="height:16px; vertical-align :top;" name="keyword" class="inputbox btn" type="text" size="20" value="<?php echo $this->keyword ?>" />
		<input type="submit" value="<?php echo JText::_('COM_VIRTUEMART_SEARCH') ?>" class="button btn" onclick="this.form.keyword.focus();"/>
		</div>
				<input type="hidden" name="search" value="true" />
				<input type="hidden" name="view" value="category" />


		<!-- End Search Box -->
		<?php } ?>
    <div class="labpagination boxprice box25br">
		<?php echo $this->vmPagination->getPagesLinks (); ?>
	</div>
<div class="orderby-displaynumber boxprice box25bg box25br">
	<div class="floatleft">
			<?php echo str_replace("-/+</a>",'<img src="'.JFactory::getURI()->base().'/images/ordering_asc.png" alt="-/+" class="ordering" />',str_replace("+/-</a>",'<img src="'.JFactory::getURI()->base().'/images/ordering_desc.png" alt="+/-" class="ordering"/></a>',$this->orderByList['orderby'])); ?>
      <?php echo $this->orderByList['manufacturer']; ?>
	</div>
	<div class="floatright display-number"><div class="titleorder"><?php echo $this->vmPagination->getResultsCounter ();?></div><div class="limitlab box25br"><?php echo $this->vmPagination->getLimitBox (); ?></div></div>

	<div class="clear"></div>
  
</div>
		</form>      
     <h3 class="boxname tlmbfoxname"><?php echo $this->category->category_name; ?></h3>
     <div class="linelabmodule">
    <span class="dboxname"></span>
</div>
    <?php

	// Start the Output
	$model=VMModel::getModel('product');
	$model->addImages($this->products);
	foreach ($this->products as $product) {
		echo '<input type="hidden" name="special_product_id" value="'.$product->virtuemart_product_id.'" disabled="disabled" />';

		// Show the horizontal seperator
		if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) {
			?>
		<div class="varianta2"></div>
			<?php
		}

		// this is an indicator wether a row needs to be opened or not
		if ($iBrowseCol == 1) {
			?>
	<div class="varianta1 row-fluid">
	<?php
		}

		// Show the vertical seperator
		if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
			$show_vertical_separator = ' ';
		} else {
			$show_vertical_separator = $verticalseparator;
		}

		// Show Products
		?>
		<div class="produkt span<?php echo $spanClass . $show_vertical_separator?>">
			<div class="spacer box1bg box1br box1bg_shadow box1br_radius">
				<div class="nadpis box2bg box2br boximg box2bg_shadow box2br_radius" id="nadpis_<?php echo $product->virtuemart_product_id ?>">
				<h2 class="dboxname tlmbfoxname"><?php echo JHTML::link($product->link, $product->product_name); ?></h2>
					<div class="obrazek shine box3bg box3br box3bg_shadow box3br_radius">
 <?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$product->images[0]->displayMediaThumb('class="catImage" border="0"',false));
                  ?>
  </div>
            					<!-- The "Average Customer Rating" Part -->
					<?php if ($this->showRating) { ?>
					<span class="hodnoceni labrating hidden-phone">
					<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>" method="post" name="ratingForm">



<?php

$ratingModel = VmModel::getModel('ratings');

$rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);

?>

<div class="rating boxrate">

<label for="vote">

<span title="<?php echo (JText::_ ("COM_VIRTUEMART_RATING_TITLE").(empty($rating)?VmConfig::get ('vm_maximum_rating_scale', 5):$rating->rating).'/'.VmConfig::get ('vm_maximum_rating_scale', 5)); ?>" class="vmicon ratingbox" style="display:inline-block;width:<?php echo 24 * VmConfig::get ('vm_maximum_rating_scale', 5); ?>px;">

				<span class="stars-orange" style="width:<?php echo empty($rating)?(24 * VmConfig::get ('vm_maximum_rating_scale', 5)):24*$rating->rating; ?>px"></span>
				
							    </span>
							    
										    </label>
										    
													    <input type="hidden" id="vote" name="vote" value="<?php echo VmConfig::get ('vm_maximum_rating_scale', 5); ?>" />
													    
																    <input type="hidden" name="virtuemart_product_id" id="virtuemart_product_id" value="<?php echo $product->virtuemart_product_id; ?>" />
																    
																			    <!--<input type="hidden" name="task" value="review"/>-->
																			    
																						    <input type="hidden" name="editable" id="editable" value="<?php echo @(JFactory::getUser()->get('id')==0||$rating->created_by==JFactory::getUser()->get('id'))?0:1; ?>" />	
																						    
																						    
																						    
																									</div>
																									
																											    </form>
</span>
					<?php } ?> 
						<?php
						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none') && (VmConfig::get ( 'display_stock', 1 )) ){?>
<!-- 						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none')){?> -->
						<div class="paddingtop8">
							<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
							<span class="stock-level"><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
						</div>
						<?php }?>
				</div>


<div class="popis">

					<?php // Product Short Description
					if (!empty($product->product_s_desc)) {
						?>
						<div class="product_s_desc boxprice hidden-phone">
							<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 100, '...') ?>
						</div>
						<?php } ?>

					<div class="product-price boxprice" id="productPrice<?php echo $product->virtuemart_product_id ?>">
						<?php
						if ($this->show_prices == '1') {
							if (empty($product->prices['salesPrice']) and VmConfig::get ('askprice', 1) and  !$product->images[0]->file_is_downloadable) {
								echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
							}
							//todo add config settings
							if ($this->showBasePrice) {
								echo $this->currency->createPriceDiv ('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $product->prices);
								echo $this->currency->createPriceDiv ('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $product->prices);
							}
							echo $this->currency->createPriceDiv ('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $product->prices);
							if (round($product->prices['basePriceWithTax'],VmConfig::get('salesPriceRounding')) != $product->prices['salesPrice']) {
								echo '' . $this->currency->createPriceDiv ('basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $product->prices) . "";
							}
							if (round($product->prices['salesPriceWithDiscount'],VmConfig::get('salesPriceRounding')) != $product->prices['salesPrice']) {
								echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
							}
							echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);
							echo $this->currency->createPriceDiv ('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
							echo $this->currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
							echo $this->currency->createPriceDiv ('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $product->prices);
							$unitPriceDescription = JText::sprintf ('COM_VIRTUEMART_PRODUCT_UNITPRICE', $product->product_unit);
							echo $this->currency->createPriceDiv ('unitPrice', $unitPriceDescription, $product->prices);
						} ?>

					</div>  <div class="abox">
                    <div class="bbox span6 boxnone hidden-phone">
				
					<?php // Product Details Button
					echo '<a href="#" id="quickview_'.$product->virtuemart_product_id.'" class="quickbtn boxcart box4br_radius boxcart_shadow" onclick="show_detail('.$product->virtuemart_product_id.', \''.$product->link.(strpos($product->link,"?")?'&tmpl=component':'?tmpl=component').'\'); return false;" >'.JText::_('TPL_ULTIMELAB_QUICK_LOOK').'</a>';
					?>    
   </div>
      <div class="cbox span6"> 
        <form method="post" class="product js-recalculate" action="index.php"> 
	<?php // Product custom_fields
	if (!empty($product->customfieldsCart)) { ?>
    	<div class="product-fields">
		<?php foreach ($product->customfieldsCart as $field) { ?>
		    <div class="product-field product-field-type-<?php echo $field->field_type ?>">
			<span class="product-fields-title" ><strong><?php echo JText::_($field->custom_title) ?></strong></span>
			<?php if ($field->custom_tip)
			    echo JHTML::tooltip($field->custom_tip, JText::_($field->custom_title), 'tooltip.png'); ?>
			<span class="product-field-display"><?php echo $field->display ?></span>

			<span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
		    </div><br />
		    <?php
		}
		?>
    	</div>
	<?php
	}
	/* Product custom Childs
	 * to display a simple link use $field->virtuemart_product_id as link to child product_id
	 * custom_value is relation value to child
	 */

	if (!empty($product->customsChilds)) {
	    ?>
    	<div class="product-fields">
    <?php foreach ($product->customsChilds as $field) { ?>
		    <div class="product-field product-field-type-<?php echo $field->field->field_type ?>">
			<span class="product-fields-title" ><strong><?php echo JText::_($field->field->custom_title) ?></strong></span>
			<span class="product-field-desc"><?php echo JText::_($field->field->custom_value) ?></span>
			<span class="product-field-display"><?php echo $field->display ?></span>

		    </div><br />
		<?php } ?>
    	</div>
<?php } ?>

	<div class="addtocart-bar">

<?php // Display the quantity box

    $stockhandle = VmConfig::get('stockhandle', 'none');
    if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
 ?>
		<a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id='.$product->virtuemart_product_id); ?>" class="notify"><?php echo JText::_('COM_VIRTUEMART_CART_NOTIFY') ?></a>

<?php } else { ?>
						<!-- <label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
	 <span style="display:none"> <span class="quantity-box">
		<input type="text" class="quantity-input js-recalculate" name="quantity[]" value="<?php if (isset($product->min_order_level) && (int) $product->min_order_level > 0) {
    echo $product->min_order_level;
} else {
    echo '1';
} ?>" />
	    </span>
	    <span class="quantity-controls js-recalculate">
		<input type="button" class="quantity-controls quantity-plus" />
		<input type="button" class="quantity-controls quantity-minus" />
	    </span></span>
	    <?php // Display the quantity box END ?>

	    <?php
	    // Display the add to cart button
	    ?>
		<span class="addtocart-button"> 
		<input type="submit" name="addtocart" class="addtocart-button boxcarthover box4br_radius boxcart_shadow" value="<?php echo JText::_('COM_VIRTUEMART_CART_ADD_TO') ?>" title="<?php echo JText::_('COM_VIRTUEMART_CART_ADD_TO') ?>" />
		</span>
<?php } ?>

	    <div class="clear"></div>
	</div>

	<?php // Display the add to cart button END  ?>
	<input type="hidden" class="pname" value="<?php echo $product->product_name ?>" />
	<input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="view" value="cart" />
	<noscript><input type="hidden" name="task" value="add" /></noscript>
	<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>" />
<?php /** @todo Handle the manufacturer view */ ?>
	<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id ?>" />
	<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>" />
    </form>   	
  </div>
    <div class="gridbox"></div>
					</div>
				</div>
				<div class="clear"></div>	</div>
		</div>
	<?php
	$iBrowseProduct ++;

	// Do we need to close the current row now?
	if ($iBrowseCol == $BrowseProducts_per_row) { ?>
	<div class="clear"></div>
	</div>
		<?php
		$iBrowseCol = 1;
	} else {
		$iBrowseCol ++;
	}
}
// Do we need a final closing row tag?
if ($iBrowseCol != 1) { ?>
	<div class="clear"></div>
	</div>
<?php
}
?><div class="nogrid"></div><div class="labpagination boxprice box25br"><?php echo $this->vmPagination->getPagesLinks (); ?><span style="float:right; padding-right: 23px;" class="boxprice hidden-phone"><?php echo $this->vmPagination->getPagesCounter (); ?></span></div><div class="clear"></div>
<div class="category_description boxprice hidden-phone">
	<?php echo $this->category->category_description ; ?>
</div></div>
<?php } ?>

<?php if (JFile::exists(dirname(__FILE__).DS.'default_products_tabs.php')) echo $this->loadTemplate('products_tabs'); ?>
