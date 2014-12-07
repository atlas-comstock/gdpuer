<?php 
include '../includes/init.php';
 $media=$db->getAll("select * from av_video order by fetch_time desc LIMIT 20");
 if($media){
	 foreach($media as $single_media)
	 {
		 $title_id = $single_media['title_id'];
		 $title_name = $db->getRow("select title_name from av_title WHERE title_id=$title_id");
		 echo $single_media['rawtext']."\n";
	 }
 }
 	die();
?>