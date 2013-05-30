<html>
<head>
<title>RSS Feeds</title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<?php
	//http://blog.sherifmansour.com/?p=302
	if(isset($_POST['url']) && $_POST['url']!=''){
		$feed = file_get_contents($_POST['url']);
		$xml = new SimpleXmlElement($feed);
		$slide_array = array();
		$pdf_array = array();
		$slide=0;
		$list_item='';
		$list_img='';
		foreach ($xml->channel->item as $entry){
		  $dc = $entry->children('http://purl.org/rss/1.0/modules/content/');
			   preg_match_all('/<img[^>]+>/i', $dc->encoded,$img); 
			   preg_match('/<img\s.*?\bsrc="(.*?)".*?>/si',$img[0][0],$src);
			  $class = ($slide==0)?'active':'';
			  $len = strlen($entry->description);
			  $descrption = ($len>100)?substr($entry->description,0,100).'...':$entry->description;
			  $list_item .= '<li data-target="#myCarousel" data-slide-to="'.$slide++.'" class="'.$class.'"></li>';
			  $list_img .='<div class="'.$class.' item">
							<a href="'.$entry->link.'"><img src="imageresize.php?src='.$src[1].'"></a>
							<div class="carousel-caption">
								<h4><a href="'.$entry->title.'">'.$entry->title.'</a></h4>
								<p>'.$descrption.'</p>
							</div>
					  </div>';
			  $pdf_array[] = $src[1].'/**/'.$entry->title;
		 	  $url =$_POST['url'];
		}		
	}else{
		$url ='http://devilsworkshop.org/feed/';
	} // END IF 
	
?>
</head>
<body>
<div class="container" style="text-align:center;width:60%">
  <p>
  <form class="form-inline" action="index.php" method="post">
    <label class="control-label" for="url">Specify RSS Feed: </label>
    <input type="text" class="input-small input-xxlarge" placeholder="RSS Feed URL" name="url" id="url" value="<?php echo $url;?>">
    <button type="submit" class="btn btn-primary">Read</button>
    <p>
    <div class="accordion alert" id="accordion2" style="text-align:left;">
      <div class="accordion-group">
        <div class="accordion-heading" style=""> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> Note:- Please specify valid XML file as sated below (Click to view it).</a> </div>
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
<?php 	if(isset($_POST['url']) && $_POST['url']!=''){ ?>
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
<script src="bootstrap/js/bootstrap.js"></script>
<script>
 $(document).ready(function() {
	$('.carousel').carousel('cycle');
 	$('.slide').click(function() {
		 $('.carousel').carousel($(this).attr('id'));
	});
 	$('#download').click(function() {
		window.location="download.php?create-file=true&file=<?php echo $_POST['url'];?>";
	});
	$('.accordion-toggle').tooltip({placement: "top"});
 });
</script>
