<?php
	

       $ak="LTAI4tcYIO64d4fM";

       $sk="ptdRJL8tWyu1XFZ5sJaeVzNpj9fF0R";

       $domain="http://ceshi2323.oss-cn-beijing.aliyuncs.com/";//图片域名或bucket域名

       $expire=time()+3600*24;

       $bucketname="ceshi2323";

       $file="029.mp4";//或者"mulu/1.jpg@!样式名"  或者 mulu/1.jpg”

       $StringToSign="GET\n\n\n".$expire."\n/".$bucketname."/".$file;

       $Sign=base64_encode(hash_hmac("sha1",$StringToSign,$sk,true));

       $url=$domain.urlencode($file)."?OSSAccessKeyId=".$ak."&Expires=".$expire."&Signature=".urlencode($Sign);

       echo $url."\n";


?>