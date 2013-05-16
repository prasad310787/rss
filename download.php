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
		   $slide_array[] = '{image : "'.$src[1].'", title : "'.$entry->title.'"}';		   
		   $pdf_array[] = $src[1].'/**/'.$entry->title;
	}
			require_once('fpdf/fpdf.php');
			$pdf=new PDF_MC_Table();
			$pdf->setTitle("RSS Slides");
			//$pdf->pdf_header($image[0][1]);
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial', 'B',10);
			$row_arr = array();
			foreach($pdf_array as $val){
				$image[] = explode('/**/',$val);
				//$row_arr[]='<image src="'.$image[0][0].'">';
				//$pdf->Row($row_arr);
				$pdf->WriteHTML($image[0][1]);
				$pdf->Image($image[0][0],30,40);
				//unset($row_arr);
				unset($image);
				if($val != end($pdf_array))
				$pdf->AddPage();

			}
			$pdf->Output('Slides.pdf','D');die;
?>