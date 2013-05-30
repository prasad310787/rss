<html>
<head>
<link rel="stylesheet" href="gallery/slideshow/css/supersized.css" type="text/css" media="screen" />
<link rel="stylesheet" href="gallery/slideshow/theme/supersized.shutter.css" type="text/css" media="screen" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript" src="gallery/slideshow/js/jquery.easing.min.js"></script>
<script type="text/javascript" src="gallery/slideshow/js/supersized.3.2.7.js"></script>
<script type="text/javascript" src="gallery/slideshow/theme/supersized.shutter.js"></script>
<style>
	.farme{
		display:none;
	}
</style>
<?php
	//http://blog.sherifmansour.com/?p=302
	$feed = file_get_contents("http://devilsworkshop.org/feed/");
	$xml = new SimpleXmlElement($feed);
	$slide_array = array();
	$pdf_array = array();
	foreach ($xml->channel->item as $entry){
	  $dc = $entry->children('http://purl.org/rss/1.0/modules/content/');
		   preg_match_all('/<img[^>]+>/i', $dc->encoded,$img); 
		   preg_match('/<img\s.*?\bsrc="(.*?)".*?>/si',$img[0][0],$src);
		   $slide_array[] = '{image : \''.$src[1].'\', title : \''.$entry->title.'\'}';		   
		   $pdf_array[] = $src[1].'/**/'.$entry->title;
	}	

?>
<script type="text/javascript">
	jQuery(function($){
	$.supersized({
			// Functionality
			slide_interval          :   3000,		// Length between transitions
			transition              :   3, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
			transition_speed		:	700,		// Speed of transition
			fit_always				:	1,
			// Components							
			slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
			slides 					:  	[<?php echo implode(',',$slide_array); ?>]
		});
	});
</script>
</head>
<body>
<table style="width:100%!important;">
<tr>
<td>
<div id="test"></div>
<!--Thumbnail Navigation-->
<div id="prevthumb"></div>
<div id="nextthumb"></div>
<!--Arrow Navigation-->
<a id="prevslide" class="load-item"></a> <a id="nextslide" class="load-item"></a>
<div id="thumb-tray" class="load-item">
  <div id="thumb-back"></div>
  <div id="thumb-forward"></div>
</div>
<!--Time Bar-->
<div id="progress-back" class="load-item">
  <div id="progress-bar"></div>
</div>
<div id="controls-wrapper" class="load-item">
  <div id="controls"> <a id="play-button"><img id="pauseplay" src="gallery/slideshow/img/pause.png"/></a>
    <!--Slide counter-->
    <div id="slidecounter"> <span class="slidenumber"></span> / <span class="totalslides"></span> </div>
    <!--Slide captions displayed here-->
    <div id="slidecaption"></div>
    <!--Thumb Tray button-->
    <a id="tray-button"><img id="tray-arrow" src="gallery/slideshow/img/button-tray-up.png"/></a>
    <!--Navigation-->
    <ul id="slide-list">
    </ul>
  </div>
</div>
</td>
</tr>
</table>
</body>
</html>