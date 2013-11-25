var dp = {};

dp.Preloader = (function() {
	
  var preloaderWrapper;
	
  var init = function() {
    preloaderWrapper = $(".preloader-wrapper");
    addListener();	
    centerLoader();	
		
    $('body').waitForImages(function() {   
      hidePreloader();
    }, function(loaded, total) {
      var progress = loaded/total,
      width = 100 * progress;

      $(".preloader-info").html("Loading... " + parseInt(progress*100) + "%");
      $(".logo").animate({
        width: width
      },{
        queue: false
      });
    });
  };
	
  var centerLoader = function() {
		
    var windowHeight = $(window).height(),
    windowWidth = $(window).width(),
    preloaderWidth = parseInt(preloaderWrapper.width()),			
    preloaderHeight = parseInt(preloaderWrapper.height()),
    left = windowWidth/2 - preloaderWidth/2,
    top = windowHeight/2 - preloaderHeight/2 - 32;			

    preloaderWrapper.css({
      "left": left,
      "top": top
    });
  };
	
  var addListener = function() {
		
    $(window).resize(centerLoader);
  };
  
  var hidePreloader = function() {
    $(".preloader-info").html("");
		
    setTimeout(function() {

      $("#preloader").animate({
        "opacity": 0
      }, {
        easing: "easeInOutCubic",
        duration: 1000,
        complete: function() {
          $("#preloader").remove();
        }
      });	
						
    }, 1000);
  };
	
  return {
    init: init
  };
}());	

dp.wheelLock = false;

dp.Navigation = (function() {
	
  var scrollLock = false,
  offset =  0;
	
  var init = function() {
    addListener();
  };
	
  var scrollToContent = function(obj, container) {
		var maxScrollTop = $(document).height() - $(window).height();
    var top = 0;

    if (container != null)
      top = $(container).offset().top - offset;
    else {
			
      var navItem = $(obj),
      id = navItem.attr("id"),
      id = id.replace("nav-", ""),
      content = $("#" + id);	
      top = content.offset().top - offset;
      if(top > maxScrollTop)
        top = maxScrollTop;  
    }
		
    animatedScrollTo(top);
  };
	
  var animatedScrollTo = function(top) {
		var px = Math.abs(top-$(document).scrollTop());
    var duration = px*2,	//speed 2px per ms.
    scrollLock = true;
    $("html, body").animate({
      scrollTop: top
    }, {
      duration: duration,
      easing :'linear',
      complete: function() {
        scrollLock = false;
        dp.wheelLock = false;
        checkContentPosition();			
      }
    });
};
	
var checkContentPosition = function() {
		
  var contents = $(".content"),
  scrollOffset = $(window).scrollTop() - 500,
  contentFound = false,
  navItems = $(".nav li"),
  index = 0;		

  var resetNav = function() {
    navItems.removeClass("active");		
  };
		
  if (scrollLock) return; 			
		
  contents.each(function() {
			
    index = index + 1;
			
    var next = $(contents[index]),
    top = $(this).offset().top - offset,
    id = $(next).attr("id");

    if (next.length > 0) {	
				
      if (scrollOffset < 180) {
        resetNav();
        contentFound = true;
        $("#nav-" + $(contents[0]).attr("id")).addClass("active");
        return;
      } else if (scrollOffset > top && scrollOffset > 180) {
        resetNav();
        contentFound = true;
        $("#nav-" + id).addClass("active");
      }
    }
  });
		
  if (contentFound === false) resetNav();
};
	
var addListener = function() {
		 // Navigate through whole pages on mousewheel scroll 
  
	$('body').mousewheel(function(e,a){
    if(!dp.wheelLock){
      dp.wheelLock = true;
      var nextPage;
      if(a < 0){
         nextPage = $(".nav li.active").next();
        if(!nextPage.length ){
          if($(window).height()+$(document).scrollTop()!=$(document).height()){
           dp.wheelLock = false;
           return;
            nextPage = $("<li id='nav-footer'></li>");
           
          }else{
            nextPage = false;
          }
        }
      }
      else if(a > 0){
         nextPage = $(".nav li.active").prev();
         if(!nextPage.length){
              nextPage = false
          }
      }
      if(nextPage){
        e.preventDefault();
        $(document).stop();
        scrollToContent(nextPage);
      }else{
        dp.wheelLock = false;
      }
    }else{
      e.preventDefault();
    }
    
    
  });	
  // scroll
  
  $(window).scroll(checkContentPosition);
  $(document).delegate(".pages-toolbar-inventory>.page-thumb",'click',function() {
    scrollToContent($('.nav li[rel='+$(this).attr('rel')+']'));
  });
  $(document).delegate('.nav li','click',function() {
    scrollToContent(this);
  });
}
return {
  init: init
};
}());


