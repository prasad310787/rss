<?php
	//echo "<pre>";var_dump($_SERVER);die;
	define('SITE_URL','http://'.$_SERVER['HTTP_HOST'].''.dirname($_SERVER['PHP_SELF']).'/');
	$permalink = str_replace('http://','',ltrim($_SERVER['REDIRECT_URL'],'/'));
	$permalink = 'http://'.$permalink;
?>
<html>
<head>
<title>RSS Feeds</title>
<link href="<?php echo SITE_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="<?php echo SITE_URL;?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<?php
	//http://blog.sherifmansour.com/?p=302
	libxml_use_internal_errors(true); 
	if(1){
		try{
				$permalink = checkFeedUrl($permalink);
				$feed = @file_get_contents($permalink);
				$xml = new SimpleXmlElement($feed);		
				$slide_array = array();
				$pdf_array = array();
				$slide=0;
				$list_item='';
				$list_img='';
				foreach ($xml->channel->item as $entry){
				  $dc = $entry->children('http://purl.org/rss/1.0/modules/content/');
					   preg_match_all('/<img[^>]+>/i', $dc->encoded,$img);  // GET IMAGE TAG
					   preg_match('/<img\s.*?\bsrc="(.*?)".*?>/si',$img[0][0],$src); // GET SOURCE FROM ABOVE IMG TAG
					  $class = ($slide==0)?'active':'';
					  //$len = strlen($entry->description);
					  //$descrption = ($len>100)?substr($entry->description,0,100).'...':$entry->description;
					  preg_match_all("/<\s*p[^>]*>([^<]*)<\s*\/\s*p\s*>/", $entry->description,$p_tag);
					  $descrption = $p_tag[0][0];
					  $list_item .= '<li data-target="#myCarousel" data-slide-to="'.$slide++.'" class="'.$class.'"></li>';
					  $list_img .='<div class="'.$class.' item">
									<div class="container-fluid">
									<div class="row-fluid">
										<div class="span4">
											<a href="'.$entry->link.'"><img src="'.SITE_URL.'imageresize.php?src='.$src[1].'" class="img-rounded"></a>
										</div>
										<div class="span8 text-left text-info">
											<h5><a href="'.$entry->link.'">'.filterString($entry->title).'</a></h5>
											<p>'.filterString($descrption).'</p>
										</div>
									</div>
									</div>
								  </div>';
					  $pdf_array[] = $src[1].'/**/'.$entry->title;
					  $url =$permalink ;
		 		} // END FOR
		  }catch (Exception $e){
		 	echo '<div class=""><div class="container alert alert-block alert-error fade in" style="width:60%;margin-bottom:30px;">					
					<h5 class="alert-heading">Message: </h5> ' .$e->getMessage().'. Please refer "Specify RSS Feed" field.
				  </div></div>';
		  } // TRY CATCH
	}else{
		$url = $permalink ;
	} // END IF 
	
	function filterString($text){
		return html_entity_decode(preg_replace('/[^a-zA-Z0-9 \,\;\&\*\<\>\{\}\'\"\:\_\\\%\[().\]\\/-]/s', '', $text), ENT_COMPAT, "UTF-8");
	}
	
	function checkFeedUrl($url){
		if($url=='' || !filter_var($url, FILTER_VALIDATE_URL)){
			throw new Exception("Please enter valid feed URL");
		}
    	return $url;
	}
?>
</head>
<body>
<div class="container" style="text-align:center;width:60%;margin-bottom:30px;">
  <p>
  <form class="form-inline" action="index.php" method="post">
    <label class="control-label" for="url">Specify RSS Feed: </label>
    <input type="text" class="input-small input-xxlarge" placeholder="I'll back soon... Please try http://assignment.net76.net/http://devilsworkshop.org/feed/" name="url" id="url" value="" title="I'll back soon!!!" disabled>
    <button type="submit" class="btn btn-primary" disabled>Read</button>
    <p>
    <div class="accordion alert" id="accordion2" style="text-align:left;">
      <div class="accordion-group">
        <div class="accordion-heading" style=""> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" title="Click me to view."> Note:- Please specify valid XML file as sated below (Click to view it).</a> </div>
        <div id="collapseOne" class="accordion-body collapse">
          <div class="accordion-inner">
          <pre>
            &ltrss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="..." xmlns:dc="..." xmlns:atom="..." xmlns:sy="..." version="2.0"&gt
              &ltchannel&gt
                &ltitem&gt
                  &lttitle&gt...&lt;/title&gt;
                  &ltlink&gt...&lt/link&gt
                  &ltcomments&gt...&lt/comments&gt
                  &ltpubDate&gt...&lt/pubDate&gt
                  &ltdc:creator&gt...&lt/dc:creator&gt
                  &ltcategory&gt...&lt/category&gt
                  &ltdescription&gt ... &lt/description&gt
                  &ltcontent:encoded&gt ... &lt/content:encoded&gt
                  &ltwfw:commentRss&gt...&lt/wfw:commentRss&gt
                  &ltslash:comments&gt...&lt/slash:comments&gt
                &lt/item&gt
                &ltitem&gt
                  &lttitle&gt...&lt;/title&gt;
                  &ltlink&gt...&lt/link&gt
                  &ltcomments&gt...&lt/comments&gt
                  &ltpubDate&gt...&lt/pubDate&gt
                  &ltdc:creator&gt...&lt/dc:creator&gt
                  &ltcategory&gt...&lt/category&gt
                  &ltdescription&gt ... &lt/description&gt
                  &ltcontent:encoded&gt ... &lt/content:encoded&gt
                  &ltwfw:commentRss&gt...&lt/wfw:commentRss&gt
                  &ltslash:comments&gt...&lt/slash:comments&gt
                &lt/item&gt
                ...
                ...
              &lt/channel&gt
            &lt/rss&gt
            </pre>
          </div>
        </div>
      </div>
    </div>
    </p>
  </form>
  </p>
</div>
<? if(!empty($list_item)) {?>
<div class="container" style="text-align:center;width:60%;height:60%;">
  <div id="myCarousel" class="carousel slide">
    <ol class="carousel-indicators">
      <?php echo  $list_item;?>
    </ol>
    <!-- Carousel items -->
    <div class="carousel-inner"> <?php echo  $list_img;?> </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> </div>
  <button id="cycle" class="btn btn-primary slide">Play</button>
  <button id="pause" class="btn btn-primary slide">Pause</button>
  <button class="btn btn-primary" id="download">Download PDF</button>
</div>
<? } ?>
</body>
</html>
<script src="http://code.jquery.com/jquery.js"></script>
<script src="<?php echo SITE_URL;?>bootstrap/js/bootstrap.js"></script>
<script>
 $(document).ready(function() {
 	$('.slide').click(function() {
		 $('.carousel').carousel($(this).attr('id'));
	});
 	$('#download').click(function() {
		window.location="<?php echo SITE_URL;?>download.php?create-file=true&file=<?php echo $permalink;?>";
	});
	$('input,.accordion-toggle').tooltip({placement: "bottom"});
	
 });
</script>
