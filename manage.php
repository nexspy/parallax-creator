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
 $dir = opendir("images");
  $file_arr = array();
  while (false !== ($file = readdir($dir))) {
    if (strpos($file, '.gif', 1) || strpos($file, '.jpg', 1) || strpos($file, '.png', 1)) {
      $images[] = $file;
    }
  }

$pages =  json_decode(file_get_contents('data/pages.json'),true);

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
          <div class="wrapper">';
        foreach ($page['texts'] as $key => $text_group) {
           $html .= ' <div class="page-text" id="' . $text_group['id'] . '" style="left:' . $text_group['position']['left'] . 'px ; top:' . $text_group['position']['top'] . 'px;width:' . $text_group['dimension']['width'] . 'px ; top:' . $text_group['dimension']['height'] . 'px;">
              <div class="page-text-head">
               ' . $text_group['title'] . '
              </div>
              <div class="page-text-body">' . $text_group['text'] . '</div>
                <form action="" class="text-properties-form properties-form" id="text-property--' . $text_group['id'] . '">
          <div class="object-property"><label for="">ID: </label><input type="text" value="' . $text_group['id'] . '"/></div>
          <div class="object-property"><label for="">BgImg: </label><input class="edit-image" type="text" value="' . $text_group['text_bg'] . '"/></div>
          <div class="object-property"><label for="">Title: </label><input class="edit-text-title" type="text" value="' . $text_group['title'] . '"/></div>
          <div class="object-property"><label for="">Text: </label><textarea class="edit-text-body">' .htmlentities($text_group['text']) . '</textarea></div>
          <div class="object-property position"><label for="">Position: </label><input type="text" class="left" value="' . $text_group['position']['left'] . '"/>, <input type="text" class="top" value="' .$text_group['position']['top'] . '"/></div>
          <div class="object-property dimension"><label for="">Dimension: </label><input type="text" class="width" value="' . $text_group['dimension']['width'] . '"/> X <input type="text" class="height" value="' .$text_group['dimension']['height'] . '"/></div>
<div class="remove-object">x</div>        
</form>  
            </div>
            <style>#' . $page['id'] . ' .page-text:before {background-image: url(images/' . $text_bg . ')}</style>
            ';
        }
        foreach ($page['objects'] as $key => $object) {
          $html .= '<div id="' . $object['id'] . '" class = "dejavu-pov" style="
            z-index: ' . $object['z-index'] . ';
            left: ' . $object['position']['left'] . 'px;
            top:  ' . $object['position']['top'] . 'px;
              "><img src="images/' . $object['img'] . '"/>
        <form action="" class="object-properties-form properties-form" id="object-property--' . $object['id'] . '">
          <div class="object-property"><label for="">ID: </label><input type="text" value="' . $object['id'] . '"/></div>
          <div class="object-property"><label for="">Image: </label><input type="text" class="edit-image" value="' . $object['img'] . '"/></div>
          <div class="object-property with-slider"><label for="">Dynamix Factor: </label>
          <div class="d-factor-slider slider"></div>
          <input type="text" class="edit-d-factor" value="' . $object['factor'] . '"/></div>
          <div class="object-property with-slider"><label for="">Z-index: </label>
          <div class="z-index-slider slider"></div>
          <input type="text" class="edit-z-index" value="' . $object['z-index'] . '" />
            </div>
          <div class="object-property position"><label for="">Position: </label><input type="text" class="left" value="' . $object['position']['left'] . '"/>, <input type="text" class="top" value="' . $object['position']['top'] . '"/></div>
<div class="remove-object">x</div>        
</form>     
        </div>';
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
  
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=1080">
        <title>Dreamflight Web design</title>
          
        <link rel="stylesheet" href="css/reset.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title"/>
        <link rel="stylesheet" href="css/core.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title"/>
        <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="screen" title="no title"/>
        <link rel="stylesheet" href="css/main-manage.css?r=<?php echo time(); ?>" type="text/css" media="screen" title="no title"/>
        <link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'/>
        
        <!--HTML Templates for JS-->
        <script type="text/template" id="pageThumbFormTemplate">
          <form action="" class="page-properties-form properties-form" id="page-property--{pageID}">
            <div class="object-property"><label for="">ID: </label><input class="edit-id" type="text" value="{pageID}"/></div>
            <div class="object-property"><label for="">Title: </label><input class="edit-title" type="text" value="{title}"/></div>
            <div class="object-property"><label for="">BgImg: </label><input class="edit-image" type="text" value="{bg}"/></div>
            <div class="object-property"><label for="">BgColor: </label><input class="edit-bg-color" type="text" value="{bg-color}"/></div>
            <div class="object-property">
              <label for="">BgRepeat: </label>
              <select class="edit-bg-repeat" rel="{bg-repeat}"> 
                <option value="no-repeat">no-repeat</option>
                <option value="repeat">repeat</option>
                <option value="repeat-x">repeat-x</option>
                <option value="repeat-y">repeat-y</option> 
              </select>
            </div>
            <div class="object-property"><label for="">Factor: </label><input class="edit-factor" type="text" value="{factor}"/></div>
            <div class="object-property position"><label for="">Position: </label><input type="text" class="left" value="{left}"/>, <input type="text" class="top" value="{top}"/></div>
            <div class="remove-object">x</div>        
          </form>  
        </script>
        <script type="text/javascript">
          var pagesJSON, imagesJSON;
          var isBuilderPage = true;
          pagesJSON = <?php print json_encode($pages); ?>;
          imagesJSON = <?php print json_encode($images); ?>;
          
       <?php 
          if (strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
            print "var isIPad = true;
              ";
          } else{
            print "var isIPad = false;
              ";
          }
          
         
       ?>
       
        
    </script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/jquery.waitForImage.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.inview.js"></script>	
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>	
    <script src="js/format.js" type="text/javascript"></script>
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
	var windowHeight = 920;
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
      
//      UI Functions

      (function( $ ) {
        $.fn.resizableObject = function() {
          return  this.resizable({resize:function( event, ui ) {
              $(event.target).find('input.width').val(ui.size.width);
              $(event.target).find('input.height').val(ui.size.height);
            }});
        };
        $.fn.draggableObject = function() {
          return  this.draggable({
            containment:'parent',
            snap:'parent',
            drag: function( event, ui ) {
              $(event.target).find('input.left').val(ui.position.left);
              $(event.target).find('input.top').val(ui.position.top);
            }
          })
        };
        $.fn.draggableToolbar = function() {
          return this.draggable({
          revert: "invalid", // when not dropped, the item will revert back to its initial position
          containment: "document",
          helper: "clone",
          cursor: "move",
    
          drag: function( event, ui ) {
//            $(event.target).find('input.left').val(ui.position.left);
//            $(event.target).find('input.top').val(ui.position.top);
          }
        });
        };
        $.fn.imageDroppableForm = function() {
          return this.droppable({
          greedy: true,
          accept: ".add-image-object",
          activeClass: "ui-state-highlight",
          drop: function( event, ui ) {
            if($(event.target).hasClass('object-properties-form')){
              $(event.target).prev().attr('src',ui.draggable.attr('src'));
            }else if($(event.target).hasClass('page-properties-form')){
              $(event.target).parent().css({'background-image':'url('+ui.draggable.attr('src')+')'});
              $('.content#'+$(event.target).find('input.edit-id').val()).css({'background-image':'url('+ui.draggable.attr('src')+')'});
              
            }else if($(event.target).hasClass('text-properties-form')){
//              $(event.target).parent().css('background-image',ui.draggable.attr('src'));
            }
            $(event.target).find('input.edit-image').val(ui.draggable.attr('title'));
          }
        })
        };
        $.fn.droppablePage = function() {
          return this.droppable({
          greedy: true,
          accept: ".add-object",
          activeClass: "ui-state-highlight",
          drop: function( event, ui ) {
            if( ui.draggable.hasClass('add-object')){
              switch(ui.draggable.attr('rel')){
                case 'add-page-text':
                  var scrollTop = $(window).scrollTop();
                  var randTextID = 'text'+Math.ceil(Math.random()*10000);
                  var pageTextMarkup = $('<div class="page-text" id="'+randTextID+'"><div class="page-text-head">Title</div><div class="page-text-body">Text</div></div>')
                                .css({left:ui.offset.left,top:ui.offset.top-scrollTop})
                                .append('<form action="" class="text-properties-form properties-form">'
                                        +'<div class="object-property"><label for="">ID: </label><input type="text" value="'+randTextID+'"/></div>'
                                        +'<div class="object-property"><label for="">BgImg: </label><input class="edit-text-bg" type="text" value="splash.png"/></div>'
                                        +'<div class="object-property"><label for="">Title: </label><input class="edit-text-title" type="text" value="Title"/></div>'
                                        +'<div class="object-property"><label for="">Text: </label><textarea class="edit-text-body">Text</textarea></div>'
                                        +'<div class="object-property position"><label for="">Position: </label><input type="text" class="left" value="' +ui.offset.left+ '"/>, <input type="text" class="top" value="' +ui.offset.top+ '"/></div>'
                                        +'<div class="object-property dimension"><label for="">Dimension: </label><input type="text" class="width" value=""/> X <input type="text" class="height" value=""/></div>'
                                        +'<div class="remove-object">x</div>'
                                        +'</form>');
                  $(event.target).append(pageTextMarkup);
                  pageTextMarkup.draggableObject().resizableObject();
                  
                break;
                case 'add-image-object':
                  var scrollTop = $(window).scrollTop();
                  var randObjID = 'object'+Math.ceil(Math.random()*10000);
                  var pageTextMarkup = $('<div class="dejavu-pov" id="'+randObjID+'"></div>')
                                .css({left:ui.offset.left-$(event.target).offset().left,top:ui.offset.top-scrollTop})
                                .append('<img src="' +ui.draggable.attr('src')+ '">'
                               +'<form action="" class="object-properties-form properties-form" id="object-property--some-random-ID">'
                               +'<div class="object-property"><label for="">ID: </label>'
                               +'<input type="text" value="'+randObjID+'"/></div>'
                               +'<div class="object-property"><label for="">Image: </label>'
                               +'<input type="text" class="edit-image" value="' +ui.draggable.attr('title')+ '"/></div>'
                               +'<div class="object-property with-slider"><label for="">Dynamix Factor: </label>'
                               +'<div class="d-factor-slider slider"></div>'
                               +'<input type="text" class="edit-d-factor" value="0"/></div>'
                               +'<div class="object-property with-slider"><label for="">Z-index: </label>'
                               +'<div class="z-index-slider slider"></div>'
                               +'<input type="text" class="edit-z-index" value="0" />'
                               +'</div>'
                               +'<div class="object-property position"><label for="">Position: </label>'
                               +'<input type="text" class="left" value="'+ui.offset.left+'"/>, '
                               +'<input type="text" class="top" value="'+ui.offset.top+'"/></div>'
                               +'<div class="remove-object">x</div>        '
                               +'</form>');
                  $(event.target).append(pageTextMarkup);
                  pageTextMarkup.draggableObject().find('.d-factor-slider').dFactorSlider();
                  pageTextMarkup.find('.z-index-slider').zIndexSlider();
                  pageTextMarkup.find('form.properties-form').imageDroppableForm();
                break;
              }
            }
      }
    });
        };
        
        $.fn.zIndexSlider = function() {
          return  this.slider({
            value:this.siblings('input').val(),
            min: 0,
            max: 10,
            step: 1,
            slide: function( event, ui ) {
              $(event.target).siblings('input').val(ui.value );
              $(event.target).parents('.dejavu-pov').css('z-index',ui.value)
            }
          });
        };
        $.fn.dFactorSlider = function() {
          return  this.slider({
            value:this.siblings('input').val(),
            min: -5,
            max: 5,
            step: 0.1,
            slide: function( event, ui ) {
              $(event.target).siblings('input').val(ui.value );
            }
          });
        };
        $.fn.toolbarImages = function(imagesJSON) {
          var imgTag;
          for (var i = 0; i < imagesJSON.length; i++) {
            imgTag = $('<img class="add-object add-image-object" rel="add-image-object" title="'+imagesJSON[i]+'" src="images/'+imagesJSON[i]+'"/>');
            this.append(imgTag);
            imgTag.draggableToolbar();
            // Do something with element i.
          }
          return this;
        };
      })( jQuery );
          
          
          
//  Initializations    
        $(function() {
        dp.Parallax.init();
        dp.Navigation.init();
        dp.Preloader.init();
        
        $('.add-object').draggableToolbar();
        $('.toolbar').resizable({grid: 70}).draggable({snap:window});
        $('.toolbar-inventory').sortable({
          update: function(event, ui){
//            console.log(event);
            $($('.toolbar-inventory').sortable('toArray',{'attribute':'rel'})).each(function(){
              $('body').append($('.content#'+this));
              $('.nav>ul').append($('.nav>ul').find('li[rel='+this+']'));
            });
          }
        });
        
        $('.dejavu-pov,.page-text').draggableObject();
        $('form.properties-form').imageDroppableForm();
        $('.page-text').resizableObject();
        
        
        $('.content>.wrapper').droppablePage();
        
      });
      $(document).ready(function(){
//        Delegate some event handlers for live editing
        $(document).delegate('.save-button','click',function(){
          $thisBtn = $(this);
          var dataObject = {};
          $('.pages-toolbar-inventory>.page-thumb').each(function(i){
            var temp = {};
            var curPageID = $(this).attr('rel');
            dataObject[curPageID] = {};
            temp['weight'] = i;
            temp['id'] = curPageID;
            temp['title'] = $(this).find('.page-thumb-title').html();
            temp['bg'] = $(this).find('.edit-image').val();
            temp['bg-color'] = $(this).find('.edit-bg-color').val();
            temp['bg-repeat'] = $(this).find('.edit-bg-repeat').val();
            temp['factor'] = $(this).find('.edit-factor').val();
            
            temp['position'] = {}
            temp['position']['left'] = $(this).find('.left').val();
            temp['position']['top'] = $(this).find('.top').val();
            dataObject[curPageID] = temp;
            temp = {};
            
            $('#'+curPageID+'>.wrapper>.page-text').each(function(j){
              temp[j] = {};
              temp[j]['id'] = $(this).attr('id');
              temp[j]['title'] = $(this).find('.page-text-head').html();
              temp[j]['text'] = $(this).find('.page-text-body').html();
              temp[j]['text_bg'] = $(this).find('.edit-image').val();
              
              temp[j]['position'] = {};
              temp[j]['position']['left'] = $(this).find('.left').val();
              temp[j]['position']['top'] = $(this).find('.top').val();
              temp[j]['dimension'] = {};
              
              temp[j]['dimension']['width'] = $(this).find('.width').val();
              temp[j]['dimension']['height'] = $(this).find('.height').val();
            });
            dataObject[curPageID]['texts'] = temp;
            temp = {};
            
            $('#'+curPageID+'>.wrapper>.dejavu-pov').each(function(k){
              temp[k] = {};
              temp[k]['id'] = $(this).attr('id');
              temp[k]['img'] = $(this).find('.edit-image').val();
              temp[k]['z-index'] = $(this).find('.edit-z-index').val();
              temp[k]['factor'] = $(this).find('.edit-d-factor').val();
              
              temp[k]['position'] = {};
              temp[k]['position']['left'] = $(this).find('.left').val();
              temp[k]['position']['top'] = $(this).find('.top').val();
            });
            dataObject[curPageID]['objects'] = temp;
            
          });
          $.ajax({
            url:'save.php',
            data:dataObject,
            type:'post',
            success:function(msg){
              $thisBtn.css('background',"#cfc");
              setTimeout(function(){
                $thisBtn.css({'background':"#fff"});
              },2000)
            },
            error:function(msg){
              $thisBtn.css('background',"#fcc");
              setTimeout(function(){
                $thisBtn.css({'background':"#fff"});
              },2000)
            }
          });
        });
        $(document).delegate('.edit-title','keyup',function(){
          var curID = $(this).parents('.page-thumb').attr('rel');
          $('.nav > ul > li[rel='+curID+']').html($(this).val());
          $(this).parents('form').siblings('.page-thumb-title').html($(this).val());
        });
        $(document).delegate('.edit-bg-repeat','change',function(){
          var curID = $(this).parents('.page-thumb').attr('rel');
          $(this).parents('.page-thumb').css('background-repeat',$(this).val());
          $('.content#'+curID).css('background-repeat',$(this).val());
        });
        $(document).delegate('.edit-text-title','keyup',function(){
          $(this).parents('form').siblings('.page-text-head').html($(this).val());
        });
        $(document).delegate('.edit-text-body','keyup',function(){
          $(this).parents('form').siblings('.page-text-body').html($(this).val());
        });
        $(document).delegate('.dejavu-pov,.page-text,.page-thumb','dblclick',function(){
          $(this).find('.properties-form').toggle();
        });
        $(document).delegate('.remove-object','click',function(){
          if(confirm('Are u sure you want to delete?')){
            $(this).parent().parent().remove();
            if($(this).parent('form.properties-form').hasClass('page-properties-form')){
              var curID = $(this).parents('.page-thumb').attr('rel');
              $('.nav > ul > li[rel='+curID+']').remove();
              $('.content#'+curID).remove();
            }
          }
        });
        $(document).delegate('.toolbar-header>.minimize','click',function(){
            $(this).parents('.toolbar').find('.wrapper').slideToggle();
            $(this).toggleClass('active');
        });
        $(document).delegate('.page-thumb.add-new-page','click',function(){
          var randPageID = 'page'+Math.ceil(Math.random()*10000)
          var formTemplate = $(pageThumbFormTemplate).html().format({
            title: randPageID,
            pageID: randPageID,
            left: 0,
            top: 0,
            factor: 0
          });
            
//            Add Thumbnail to page manage toolbar
            var $newPageThumb = $('<div id="page-mgr-'+randPageID+'" rel="'+randPageID+'">').addClass('page-thumb').append('<div class="page-thumb-title">'+randPageID+'</div>').append(formTemplate);
            $('.pages-toolbar-inventory').append($newPageThumb);
            $('#page-mgr-'+randPageID+'>form.properties-form').imageDroppableForm();
//            Add nav menu Item
            $('div.nav>ul').append('<li class="nav-item" id="nav-'+randPageID+'" rel="'+randPageID+'">'+randPageID+'</li>');
            
//            Finally Add page
            var droppableWrapper = $('<div class="wrapper">').droppablePage();
            var newPage = $('<div class="content" id="'+randPageID+'"></div>').append(droppableWrapper);
            $('body').append(newPage);
        });
//        Sliders
        $( ".z-index-slider" ).each(function(){
          $(this).zIndexSlider();
        });
        $( ".d-factor-slider" ).each(function(){
          $(this).dFactorSlider();
        });
        
//        Toolbar Images
        $('.toolbar-images').toolbarImages(imagesJSON);
      });
      
	
    </script>

  </head>
  <body>
    <div id="preload-images">
      <img src="images/logo.png">
      <?php
      
      //preloading all images in images directory
     
      foreach ($images as $img) {
        echo '<img src="images/' . $img . '">';
      }
      ?>                
    </div>
    <div id="header">
      <div class="header-wrapper">
        <div class="nav">
          <div id="logo"><span class="font1">Dreamflight Builder 1.0</span></div>
          <ul>
            <?php 
            $i = 0;
            foreach($pages as $pageID => $page):
              $i++;
              ?>
            <li rel="<?php print $pageID;?>" class="nav-item <?php if ($i == 1) print "active first"; if ($i == count($pages)) print "last"; ?>" id="nav-<?php print $pageID;?>"><?php print $page['title'];?></li>
            <?php endforeach;?>											
          </ul>
          <div class="button save-button">Save</div>
        </div>
        
      </div>	
    </div>
    
    <?php
    
    print render_dynamic_parallax($pages);
    ?>

<!--Pages Manager Toolbar-->
    <div id="pages-toolbar" class="toolbar">
      <div class="pages-toolbar-header toolbar-header clearfix">
        <div class="pages-toolbar-title toolbar-title">Pages</div>
        <div class="minimize">-</div>
      </div>
      <div class="separator"></div>
      <div class="wrapper clearfix">	
        <div class="pages-toolbar-inventory toolbar-inventory">
          <?php 
            $i = 0;
            foreach($pages as $pageID => $page):
              $i++;
              ?>
            <div style="background:url(images/<?php print $page['bg'];?>) <?php print $page['bg-repeat'];?> <?php print $page['bg-color'];?>" id="page-mgr-<?php print $pageID;?>" rel="<?php print $pageID;?>" class="page-thumb">
              <div class="page-thumb-title"><?php print $page['title'];?></div>
               <form action="" class="page-properties-form properties-form" id="page-property--<?php print $pageID;?>">
                  <div class="object-property"><label for="">ID: </label><input class="edit-id" type="text" value="<?php print $pageID;?>"/></div>
                  <div class="object-property"><label for="">Title: </label><input class="edit-title" type="text" value="<?php print $page['title'];?>"/></div>
                  <div class="object-property"><label for="">BgImg: </label><input class="edit-image" type="text" value="<?php print $page['bg'];?>"/></div>
                  <div class="object-property"><label for="">BgColor: </label><input class="edit-bg-color" type="text" value="<?php print $page['bg-color'];?>"/></div>
                  <div class="object-property"><label for="">BgRepeat: </label><select class="edit-bg-repeat">
                      <option value="no-repeat">no-repeat</option>
                      <option value="repeat">repeat</option>
                      <option value="repeat-x">repeat-x</option>
                      <option value="repeat-y">repeat-y</option>
   
                    </select></div>
                  <div class="object-property"><label for="">Factor: </label><input class="edit-factor" type="text" value="<?php print $page['factor'];?>"/></div>
                  <div class="object-property position"><label for="">Position: </label><input type="text" class="left" value="<?php print $page['position']['left'];?>"/>, <input type="text" class="top" value="<?php print $page['position']['top'];?>"/></div>
                  <div class="remove-object">x</div>        
                </form>  
            </div>
            <?php endforeach;?>	
        </div>	
        <div class="page-thumb add-new-page" rel="add-page">Add Page</div> 	
      </div>
    </div>
<!--Assets toolbar -->
    <div id="assets-toolbar" class="toolbar">
      <div class="assets-toolbar-header toolbar-header clearfix">
        <div class="assets-toolbar-title toolbar-title">Assets</div>
        <div class="minimize">-</div>
      </div>
      <div class="separator"></div>
      <div class="wrapper clearfix">	
        <div class="assets-toolbar-inventory toolbar-inventory">
            <div class="add-object add-page-text" rel="add-page-text">Add Page Text</div> 
        </div>	
        <div class="toolbar-images">
        </div>	
      </div>
    </div>
<!--Footer -->
<!--    <div id="footer">
      <div class="wrapper">				
        <div class="footer-text">
            &copy; 2013 Dreamflight Footer 
        </div>	
      </div>
    </div>-->
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