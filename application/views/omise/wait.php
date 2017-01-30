<?php
/**
 * @author Adel Waehayi - www.adeeisme.com
 * @date: 2017-01-19
 * 
 * internet banking wait page
 * 
 * @copyright  Copyright (C) 2017 adeeisme.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang = getLanguages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>กำลังดำเนินการ</title>		
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimum-scale=0.5, maximum-scale=1.0"/>
	<link rel="shortcut icon" href="<?php echo base_url('media/assets/icon.png'); ?>" />
	<link href="https://fonts.googleapis.com/css?family=Oswald%7CPT+Sans%7COpen+Sans" rel="stylesheet" type="text/css"/>
	<link type="text/css" href="<?php echo base_url('application/views/themes/default/css/template.css'); ?>" rel="stylesheet" media="all" />
    <script type="text/javascript">
        (function() {
            var dots = window.setInterval( function() {
                var wait = document.getElementById("wait");
                if ( wait.innerHTML.length > 3 ) 
                    wait.innerHTML = "";
                else 
                    wait.innerHTML += ".";
                }, 100);
                window.setTimeout(function(){
                   window.location.reload(1);
                }, 5000);
        })();
    </script>
</head>
 <body>	
	<?php echo language('payment_wait_msg', $lang);?><span id="wait"></span>
  </body>
</html>