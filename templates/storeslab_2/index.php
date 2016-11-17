<?php
/**
 * @package    Joomla.Site
 * @subpackage  tpl_storeslab2
 * @copyright  Copyright (C) 2014 Linelabox.com All rights reserved.
 * @license    GNU General Public License version 2
 */
defined('_JEXEC') or die;
define( 'YOURBASEPATH', dirname(__FILE__) );
JHtml::_('behavior.framework', false);
$frontpage = $this->params->get('frontpage', 1);
$menu = JSite::getMenu();
$slide = $this->params->get('display_slideshow', 1);
$display_logo = $this->params->get('display_logo', 1);
$templogo = $this->params->get('templogo', '');
$slidecontent1 = $this->params->get('slideshow1', ''); 
$slidecontent2 = $this->params->get('slideshow2', ''); 
$slidecontent3 = $this->params->get('slideshow3', '');
$description1 = $this->params->get('description1'); 
$description2 = $this->params->get('description2'); 
$description3 = $this->params->get('description3'); 
$sitetitle = $this->params->get("sitetitle", "STORESLAB 2");
$slideimages=array();
if ($slidecontent1) $slideimages[]=array('img'=>$slidecontent1, 'desc'=>$description1);
if ($slidecontent2) $slideimages[]=array('img'=>$slidecontent2, 'desc'=>$description2);
if ($slidecontent3) $slideimages[]=array('img'=>$slidecontent3, 'desc'=>$description3);
?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css">  
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap-responsive.css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/fonts/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/flexslider.css"/>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/jquery.isotope.css" media="screen" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin,latin-ext" rel="stylesheet" type="text/css" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/apple-touch-icon-57-precomposed.png">
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/respond.src.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.easing.1.2.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.anythingslider.fx.min.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.anythingslider.min.js" type="text/javascript"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/slide.js" type="text/javascript"></script>  
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/bootstrap/bootstrap.min.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.flexslider-min.js" type="text/javascript"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.ui.totop.min.js" type="text/javascript"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/selectnav.js" type="text/javascript"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.isotope.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/custom.js" type="text/javascript"></script>
  <jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/linelab.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Dosis">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans">
</head>
 <body class="box24bg jtpimg"> 
     <?php if ($this->countModules('topslideshow')) : ?>
   <style type="text/css">
div.absrel { left: 0; right: 0; margin-left: auto; margin-right: auto; position: absolute; top: 0; }
.borderlabs { height:auto;} 
.flex-direction-nav .flex-next { right: 0; }
.flex-direction-nav .flex-prev { left: 0; }
.flexslider:hover .flex-next { right: 25px; }
.flexslider:hover .flex-prev { left: 25px; }  
     </style>
         <div class="flexslider image-slider borderlabs hidden-phone" id="fluidslide">
	      <ul class="slides">
			<?php foreach ($slideimages as $i=>$si): ?>
			<li>
			<img src="<?php echo $this->baseurl . '/' . $si['img']; ?>" alt="" />
			<div class="flexrel"><div class="flex-caption line2 visible-desktop"><?php echo $si['desc'];?></div></div>
			</li>
			<?php endforeach; ?> 
          </ul>   
         </div> 
<?php endif; ?>   
<div class="boxprice absrel box20bg">
  <div class="tboxname topmenulab tbtboxname tblboxname tbboxname">
	<div class="container">
	  <div class="row-fluid">
      <?php 
$modules_no = 0;
$modules_no += $this->countModules('topmenuleft')?1:0;
$modules_no += $this->countModules('position-0')?1:0;
$modules_no += $this->countModules('topmenuright')?1:0;
switch ($modules_no) {
  case 1:  $span_class="12"; break;
  case 2:  $span_class="6"; break;
  case 3:  $span_class="4"; break;
}
?>  <?php if ($this->countModules('topmenuleft')) : ?>
    <div class="hidden-phone span<?php echo $span_class;?> leftlab"><jdoc:include type="modules" name="topmenuleft" style="none"/></div>
    <?php endif; ?>
    <?php if ($this->countModules('position-0')) : ?>
    <div class="hidden-phone span<?php echo $span_class;?> centerlab"><jdoc:include type="modules" name="position-0" style="none"/></div>
    <?php endif; ?>
    <?php if ($this->countModules('topmenuright')) : ?>
    <div class="span<?php echo $span_class;?> rightlabs"><jdoc:include type="modules" name="topmenuright" style="none"/></div>
    <?php endif; ?>
    </div>
    </div>
    </div> 
<div class="container">                              
<div class="row">  
<div class="navbar span12">
  <div class="container"> 
	   <div class="boxprice storesl box20bg" id="storesl">                            
 <div class="container">
<div id="sticky_navigation_wrapper" class="row">
          <div class="span2"> 
      <?php if ($display_logo == 1) : ?>
	 <h1 class="centr"><a href="index.php" id="logobox"><img src="<?php echo $this->baseurl ?>/<?php echo $templogo ?>" alt="<?php echo $sitetitle ?>" /></a></h1>
	 <?php else: ?>
    <h1 class="centr"><a id="logobox" class="tlfboxname tlcboxname tlcfboxname" href="index.php" title="<?php echo $sitetitle ?>"><?php echo $sitetitle; ?></a></h1>
	  <?php endif; ?>   
          </div> 
<?php if ($this->countModules('position-1')) : ?>
    <div class="span10">
        <div id="sticky_navigation" style="float:right" class="container box20bg box20bg_shadow box20br box20br_radius tmh linelab">  
 <jdoc:include type="modules" name="position-1" style="none"/>  
    </div>
</div>   
<?php endif; ?> 
</div> 
                </div>
              </div>

</div>  
</div> 
 </div>
 </div> 
  </div>    
            <div class="container boxprice">  
			             <jdoc:include type="modules" name="position-2" style="none"/>                  
  <div id="message">
        <jdoc:include type="message" />
    </div>  
</div>
<?php if ($this->countModules('position-3 or position-4 or position-5 or position-13')) : ?>
   <div id="lab" class="box25br">  
  <div class="container boxprice">                            
 <div class="row">
<?php 
$modules_no = 0;
$modules_no += $this->countModules('position-3')?1:0;
$modules_no += $this->countModules('position-4')?1:0;
$modules_no += $this->countModules('position-5')?1:0;
$modules_no += $this->countModules('position-13')?1:0;
switch ($modules_no) {
  case 1:  $span_class="12"; break;
  case 2:  $span_class="6"; break;
  case 3:  $span_class="4"; break;
  case 4:  $span_class="3"; break;
}
?>
<?php if ($this->countModules('position-3')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-3" style="none"/>
          </div> 
<?php endif; ?>
<?php if ($this->countModules('position-4')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-4" style="none"/>
          </div> 
<?php endif; ?>
<?php if ($this->countModules('position-5')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-5" style="none"/>
          </div> 
<?php endif; ?> 
<?php if ($this->countModules('position-13')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-13" style="none"/>
          </div> 
<?php endif; ?>   
                </div>
              </div>
            </div><div class="shadowlab"><img class="shadow" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/shadow.png" alt=""></div>      
        <?php endif; ?> <div id="linlabcontent" class="boxprice"> 
<div class="container"> 
<?php  if ($frontpage == 0 || $tslider=='fluidslide') {
 if ($menu->getActive() !== $menu->getDefault()) { ?>
   <div class="row">
<div class="span<?php echo $this->countModules('position-6')>0?'9':'12'; ?>">
	  <div id="header" class="boxprice"> 
  <?php if ($this->countModules('position-12')) : ?>
    <div id="slide" class="hidden-phone sliderh box25bg box25br box25bg_shadow box25br_radius" style="display:block;">
      <?php if ($slide == 1) { ?><div class="flexslider image-slider">
      <ul class="slides">
		<?php foreach ($slideimages as $i=>$si): ?>
		<li>
		<img src="<?php echo $this->baseurl . '/' . $si['img']; ?>" alt="" />
		<div class="flex-caption line2"><?php echo $si['desc'];?></div>
		</li>
		<?php endforeach; ?> 
      </ul>
    </div>
      <?php } else { ?>
       <jdoc:include type="modules" name="position-12" style="none"/>  
      <?php } ?>  
   </div>
<?php endif; ?>      
</div> 
           <div class="row">
		   <?php
switch (($this->countModules('position-6')?1:0) + ($this->countModules('position-7')?1:0)) {
	case 0:$span_class_component=12; break;
	case 1:$span_class_component=9; break;
	case 2:$span_class_component=6; break;
}  
		   ?>
         <div class="span<?php echo $span_class_component;?> pull-right">
		 <div class="box25bg box25br box25bg_shadow box25br_radius" id="linelabmain">
		 <jdoc:include type="component" />
		 </div>
		 </div>
       <?php if ($this->countModules('position-7')): ?>
            <div class="span3">
             <jdoc:include type="modules" name="position-7" style="linelab"/> 
            </div>
          <?php endif; ?>   
           </div>
    </div>
    <?php if ($this->countModules('position-6')): ?>
            <div class="span3">
             <jdoc:include type="modules" name="position-6" style="linelab"/> 
            </div>
          <?php endif; ?> 
                       </div> 
                       <?php }
                  } else {
                         ?> 
   <div class="row"> 
<div class="span<?php echo $this->countModules('position-6')>0?'9':'12'; ?>">
<div id="header" class="boxprice"> 
  <?php if ($this->countModules('position-12')) : ?>
    <div id="slide" class="hidden-phone sliderh box25bg box25br box25bg_shadow box25br_radius" style="display:block;">
      <?php if ($slide == 1) { ?><div class="flexslider image-slider">
      <ul class="slides">
		<?php foreach ($slideimages as $i=>$si): ?>
		<li>
		<img src="<?php echo $this->baseurl . '/' . $si['img']; ?>" alt="" />
		<div class="flex-caption line2"><?php echo $si['desc'];?></div>
		</li>
		<?php endforeach; ?> 
      </ul>
    </div>
      <?php } else { ?>
       <jdoc:include type="modules" name="position-12" style="none"/>  
      <?php } ?>  
   </div>
<?php endif; ?>      
</div> 
           <div class="row"> 
		   <?php
switch (($this->countModules('position-6')?1:0) + ($this->countModules('position-7')?1:0)) {
	case 0:$span_class_component=12; break;
	case 1:$span_class_component=9; break;
	case 2:$span_class_component=6; break;
}  
		   ?>
         <div class="span<?php echo $span_class_component;?> pull-right">
		 <div class="box25bg box25br box25bg_shadow box25br_radius" id="linelabmain">
		 <jdoc:include type="component" />
		 </div>
		 </div>   
       <?php if ($this->countModules('position-7')): ?>
            <div class="span3">
             <jdoc:include type="modules" name="position-7" style="linelab"/> 
            </div>
          <?php endif; ?>   
           </div>
    </div>
    <?php if ($this->countModules('position-6')): ?>
            <div class="span3">
             <jdoc:include type="modules" name="position-6" style="linelab"/> 
            </div>
          <?php endif; ?> 
                       </div>                     
                    <?php 
                     }
                    ?>
					  
                            <div class="row">     
            <div class="span12">
            <jdoc:include type="modules" name="topscroller" style="linelab"/> <div class="row">
              <?php if ($this->countModules('vmproducts')) : ?>
          <div class="span12 random hidden-phone">
           <ul id="tabs" class="nav nav-tabs sortbylabs" data-id="vmgorupproduct" data-option-key="filter">  
            <li data-option-value=".random_product" class="active"><a class="dboxname" href="#"><?php echo JText::_('TPL_ULTIMELAB_RANDOM'); ?></a></li>
						<li data-option-value=".featured_product"><a class="dboxname" href="#"><?php echo JText::_('TPL_ULTIMELAB_FEATURED'); ?></a></li>
						<li data-option-value=".latest_product"><a class="dboxname" href="#"><?php echo JText::_('TPL_ULTIMELAB_LATEST'); ?></a></li>
						<li data-option-value=".best_sales_product"><a class="dboxname" href="#"><?php echo JText::_('TPL_ULTIMELAB_BEST'); ?></a></li>
						<li data-option-value=".recently_viewed_product"><a class="dboxname" href="#"><?php echo JText::_('TPL_ULTIMELAB_RECENT'); ?></a></li>
					</ul>
<script type="text/javascript">
window.addEvent('domready', function() {
	random_classes=Array('latest', 'random','featured','best_sales','recently_viewed');
	document.id('tabs').getElements('li').each(function(el) {
		el.addEvent('click', function() {
			alert('stop');
		});
	});
});
</script>
           <jdoc:include type="modules" name="vmproducts" style="none"/> 
          </div> 
<?php endif; ?>
            </div></div>
        </div> 
        
        <div class="container random">
         <div class="row">
      <?php 
$modules_no = 0;
$modules_no += $this->countModules('bottomleft')?1:0;
$modules_no += $this->countModules('bottomcenter')?1:0;
$modules_no += $this->countModules('bottomright')?1:0;
$modules_no += $this->countModules('position-14')?1:0;
switch ($modules_no) {
  case 1:  $span_class="12"; break;
  case 2:  $span_class="6"; break;
  case 3:  $span_class="4"; break;
  case 4:  $span_class="3"; break;
}
?>    <?php if ($this->countModules('absleft')) : ?>
    <div class="span4"><jdoc:include type="modules" name="absleft" style="xhtml"/></div>
    <?php endif; ?>
    	<?php if ($this->countModules('position-17')) : ?>
    <div class="span8 vmmod"><jdoc:include type="modules" name="position-17" style="linelab"/></div>
    <?php endif; ?> 

<?php if ($this->countModules('bottomleft')) : ?>
    <div class="span<?php echo $span_class;?>"><jdoc:include type="modules" name="bottomleft" style="linelab"/></div>
    <?php endif; ?>
    <?php if ($this->countModules('bottomcenter')) : ?>
    <div class="span<?php echo $span_class;?>"><jdoc:include type="modules" name="bottomcenter" style="linelab"/></div>
    <?php endif; ?>
    <?php if ($this->countModules('bottomright')) : ?>
    <div class="span<?php echo $span_class;?>"><jdoc:include type="modules" name="bottomright" style="linelab"/></div>
    <?php endif; ?>
      <?php if ($this->countModules('position-14')) : ?>
    <div class="span<?php echo $span_class;?>"><jdoc:include type="modules" name="position-14" style="linelab"/></div>
    <?php endif; ?>
 
     </div>
    </div> 
            </div>
        </div>
              <?php if ($this->countModules('parallaxlab')) : ?>
        <div class="row-fluid">
          <div class="span12">
          <div class="clearfix">
<div class="parallaxlab hidden-phone">  
    <jdoc:include type="modules" name="parallaxlab" style="none"/>
      </div>
    </div>
   </div>
  </div>
  <?php endif; ?>
        <div class="footerlab fboxname fhboxname fbgname fbtcname dboxprice">   
  <div class="container footer fmbgname fbbcname fbhcoxname dboxprice">                            
 <div class="row">
<?php 
$modules_no = 0;
$modules_no += $this->countModules('position-9')?1:0;
$modules_no += $this->countModules('position-10')?1:0;
$modules_no += $this->countModules('position-11')?1:0;
$modules_no += $this->countModules('position-15')?1:0;
switch ($modules_no) {
  case 1:  $span_class="12"; break;
  case 2:  $span_class="6"; break;
  case 3:  $span_class="4"; break;
  case 4:  $span_class="3"; break;
}
?>  
<?php if ($this->countModules('position-9')) : ?>
          <div class="column span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-9" style="xhtml"/>
          </div> 
<?php endif; ?>
<?php if ($this->countModules('position-10')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-10" style="xhtml"/>
          </div> 
<?php endif; ?>
<?php if ($this->countModules('position-11')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-11" style="xhtml"/>
          </div> 
<?php endif; ?>
<?php if ($this->countModules('position-15')) : ?>
          <div class="span<?php echo $span_class;?>"> 
            <jdoc:include type="modules" name="position-15" style="xhtml"/>
          </div> 
<?php endif; ?>
                </div>
              </div>
              </div>
                <div class="foterlab fbgname dboxprice">   
            <div class="container">
          <div class="row">
              <?php if ($this->countModules('footerload')) : ?>     
            <div class="span12">
<jdoc:include type="modules" name="footerload" style="none"/>
                      </div> 
                      <?php endif; ?> 
                      <!-- Do not remove this line! Read more http://www.linelabox.com/pricing -->
					  <?php echo base64_decode('PGEgY2xhc3M9ImZib3huYW1lIGNvcHkiIGhyZWY9Imh0dHA6Ly93d3cubGluZWxhYm94LmNvbSIgdGFyZ2V0PSJfYmxhbmsiIHRpdGxlPSJKb29tbGEhIHRlbXBsYXRlIGNyZWF0ZWQgd2l0aCBMaW5lbGFib3giPkNyZWF0ZWQgd2l0aCA8c3Ryb25nPkxpbmVsYWJveDwvc3Ryb25nPjwvYT48L2Rpdj4='); ?> 
              </div>
                 </div>              
         <jdoc:include type="modules" name="debug" style="none" />  
    </body>
</html>