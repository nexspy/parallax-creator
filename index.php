<?php 
/**
 * 
 * Guide to Parallax Config
 * 
 * 
 * Factor Value and effects in Dynamics:
 *  > 1 -> Falling Objects
 *  1 ->  No motion [Distance -> Infinity]
 *  0-1 -> Stationary Object In finite distance
 *  0  ->  Moves with the movement of the layer. [Distance -> 0]
 *  < 0 -> Flying objects 
 * 
 *
 * 
 */
$pages =  json_decode(file_get_contents('data/new.json'),true);

aasort($pages,"weight");
function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
} 
    function render_dynamic_parallax($pages) {
      $html = '';

      foreach ($pages as $key => $page) {
        $text_bg = array_key_exists('text_bg',$page)?$page['text_bg']:"splash.png";
        $repeat = array_key_exists('bg-repeat',$page)?$page['bg-repeat']:"repeat";
        $html .= '
        <div id="' . $page['id'] . '" class="content" style="background-image: url(images/' . $page['bg'] . ');background-color: ' . $page['bg-color'] . ';background-repeat: ' . $repeat . ';">
         <div class="wrapper"> ';
         foreach ($page['texts'] as $key => $text_group) {
           $html .= ' <div class="page-text" id="' . $text_group['id'] . '" style="left:' . $text_group['position']['left'] . 'px ; top:' . $text_group['position']['top'] . 'px;width:' . $text_group['dimension']['width'] . 'px ; height:' . $text_group['dimension']['height'] . 'px;">
              <div class="page-text-head">
               ' . $text_group['title'] . '
              </div>
              <div class="page-text-body">' . $text_group['text'] . '</div>
            </div>
            <style>#' . $page['id'] . ' .page-text:before {background-image: url(images/' . $text_group['text_bg'] . ')}</style>
            ';
        }
              
        foreach ($page['objects'] as $key => $object) {
          $html .= '<div id="' . $object['id'] . '" class = "dejavu-pov" style="
            z-index: ' . $object['z-index'] . ';
            background-image: url(images/' . $object['img'] . ');
            background-position: ' . $object['position']['left'] . 'px ' . $object['position']['top'] . 'px;
              "></div>';
        }
        $html .= '
          </div>
        </div>
    ';
  }
  return $html;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=1080">
    <title>Dreamflight Web design</title>
    
    <link rel="stylesheet" href="css/reset.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title">
    <link rel="stylesheet" href="css/font.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title">	
    <link rel="stylesheet" href="css/core.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title">
    <link rel="stylesheet" href="css/main.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title">
      <link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
   
    <script type="text/javascript">
       <?php 
          if (strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
            print "var isIPad = true;";
          } else{
            print "var isIPad = false";
          }
       ?>
    </script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui-1.js" type="text/javascript"></script>
    <script src="js/jquery.waitForImage.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.inview.js"></script>	
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>	
    <script src="js/dp.js" type="text/javascript"></script>
    <script type="text/javascript">
      
dp.Parallax = (function() {
  
  var <?php foreach ($pages as $key => $page) {
       echo $page['id']." = $('#".$page['id']."'), ";
       $page_ids[$page['id']] =  "#".$page['id'];
        foreach ($page['objects'] as $key => $object) {
          echo $object['id']." = $('#".$object['id']."'), ";
          $object_ids[$page['id']][$object['id']] = "#".$object['id'];
          $objects[$object['id']] = $object;
        }
        
  }?>
      footer;
  var pos = $(window).scrollTop();  
	var windowHeight = 880;
  var newPos = function($page,left, top, factor) {
    var bgPosition;
    pos = $(window).scrollTop()
     bgPosition = left + "px " + (top + (pos - $page.position().top)*factor)  + "px";
    
    return bgPosition;
  }
  var newAng = function(pos,inertia) {
    return 'rotate(' + pos*inertia + 'deg)';
  }
  var newOpc = function(pos) {
    return pos/300;
  }
	
  var isTouchDevice = function() {
    return (typeof(window.ontouchstart) != 'undefined') ? true : false;
  }	
	
  var move = function() { 
    
    <?php 
   
    foreach($page_ids as $key => $page_id){
      $js = "";
      $js .= '
        if ($("'.$page_id.'").hasClass("inview")) {
          ';
      $left = $pages[$key]['position']['left'];
      $top = $pages[$key]['position']['top'];
      $factor = $pages[$key]['factor'];
      $js .= "$('".$page_id."').css({'background-position': newPos($('".$page_id."'),".$left.", ".$top.", ".$factor.")});
        ";
      foreach($object_ids[$key] as $okey => $object_id){
        $left = $objects[$okey]['position']['left'];
        $top = $objects[$okey]['position']['top'];
        $factor = $objects[$okey]['factor'];
         $js .= "$('".$object_id."').css({'background-position': newPos($('".$page_id."'),".$left.", ".$top.", ".$factor.")});
           ";
      }
      $js .= "
        }
        ";
      print $js;
    }
    ?>		
  }	
	
  var init = function() {
    
     $('<?php reset($page_ids); print $page_ids[key($page_ids)]?>').addClass("inview");
		
    $('<?php print implode(", ",$page_ids)?>').bind('inview', function (event, visible) {
      visible == true ? $(this).addClass("inview") : $(this).removeClass("inview");
    });
		if(!isIPad){
      addListener();
      move();
    }
  };
	
  var addListener = function() {
      $(window).resize(move);
      $(window).scroll(move);	
  };
  return {
    init: init
  };
}());
</script>
    <script type="text/javascript">
        $(function() {
        dp.Parallax.init();
        dp.Navigation.init();
        dp.Preloader.init();
      });
	
    </script>

  </head>
  <body>
    <div id="preload-images">
      <img src="images/logo.png">
      <?php
      
      //preloading all images in images directory
      $dir = opendir("images");
      $file_arr = array();
      while (false !== ($file = readdir($dir))) {
        if (strpos($file, '.gif', 1) || strpos($file, '.jpg', 1) || strpos($file, '.png', 1)) {
          $file_arr[] = $file;
        }
      }
      foreach ($file_arr as $img) {
        echo '<img src="images/' . $img . '">';
      }
      ?>                
    </div>
    <div id="header">
      <div class="header-wrapper">
        <div class="nav">
          <div id="logo"><span class="font1">Dreamflight 1.0</span></div>
          <ul>
            <?php 
            $i = 0;
            foreach($pages as $pageID => $page):
              $i++;
              ?>
            <li rel="<?php print $pageID;?>" class="nav-item <?php if ($i == 1) print "active first"; if ($i == count($pages)) print "last"; ?>" id="nav-<?php print $pageID;?>"><?php print $page['title'];?></li>
            <?php endforeach;?>											
          </ul>
        </div>
      </div>	
    </div>
    <?php print render_dynamic_parallax($pages);?>
<!--Footer -->
    <div id="footer">
      <div class="wrapper">				
        <div class="footer-text">
            &copy; 2013 Dreamflight Footer 
        </div>	
      </div>
    </div>
<!--Loading Screen -->
    <div id="preloader">
      <div class="preloader-wrapper">	
        <div class="preloader-content">
          <div class="preloader-progress"></div>
          <div class="logo"></div>
          <div class="preloader-info"></div>
          <div class="preloader-scroll"></div>
        </div>
      </div>
    </div>
  </body>
</html>