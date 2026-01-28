var uws_pansvgminzoom = 1;
var uws_pansvgmaxzoom = 3;
var uws_pansvgthumbprop = 0.28;
var uws_pansvgzoomthubmnail = 1.3;
var uws_pansvgzoomthubmnail;
var uws_pansvgguttwidth;
var uws_passvgguttheight;
var uws_pansvgzoom;
var uws_pansvgthumb;


var uwsThumbnailViewer = function(options) {
    var getSVGDocument = function(objectElem) {
      var svgDoc = objectElem.contentDocument;
      if (!svgDoc) {
        if (typeof objectElem.getSVGDocument === "function") {
          svgDoc = objectElem.getSVGDocument();
        }
      }
      return svgDoc;
    }
  
    var bindThumbnail = function(uws_pansvgzoom, uws_pansvgthumb, scopeContainerId) {
      if (!window.uws_pansvgzoom && uws_pansvgzoom) {
        window.uws_pansvgzoom = uws_pansvgzoom;
      }
      if (!window.uws_pansvgthumb && uws_pansvgthumb) {
        window.uws_pansvgthumb = uws_pansvgthumb;
      }
      if (!window.uws_pansvgzoom || !window.uws_pansvgthumb) {
        return;
      }
  
      var resizeTimer;
      var interval = 300; //msec
      window.addEventListener('resize', function(event) {
        if (resizeTimer !== false) {
          clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function() {
          window.uws_pansvgzoom.resize();
          window.uws_pansvgthumb.resize();
        }, interval);
      });
  
      window.uws_pansvgzoom.setOnZoom(function(level){
        var uvpanzoom = level;

        if(uvpanzoom - 0.05 < uws_pansvgminzoom){
            document.querySelector(".uwsjs-map-zoomout").classList.add("uwsdisabled");
            setTimeout(function(){uwsPanResize();}, 10);
        }
        else
            document.querySelector(".uwsjs-map-zoomout").classList.remove("uwsdisabled");

        if(uvpanzoom + 0.05 > uws_pansvgmaxzoom)
            document.querySelector(".uwsjs-map-zoomin").classList.add("uwsdisabled");
        else
            document.querySelector(".uwsjs-map-zoomin").classList.remove("uwsdisabled");

        if(uws_pansvgzoomthubmnail < uvpanzoom){
          document.querySelector(".uws-map-view").classList.add("uwsthumbactive");
        }
        else{
            document.querySelector(".uws-map-view").classList.remove("uwsthumbactive");
        }

        window.uws_pansvgthumb.updateThumbScope();
        if (options.onZoom) {
          options.onZoom(window.uws_pansvgzoom, window.uws_pansvgthumb, level);
        }
      });
  
      window.uws_pansvgzoom.setOnPan(function(point) {
        window.uws_pansvgthumb.updateThumbScope();
        if (options.onPan) {
          options.onPan(window.uws_pansvgzoom, window.uws_pansvgthumb, point);
        }
      });
  
      var _updateThumbScope = function(uws_pansvgzoom, uws_pansvgthumb, scope, line1, line2) {
        var mainPanX = uws_pansvgzoom.getPan().x,
            mainPanY = uws_pansvgzoom.getPan().y,
            mainWidth = uws_pansvgzoom.getSizes().width,
            mainHeight = uws_pansvgzoom.getSizes().height,
            mainZoom = uws_pansvgzoom.getSizes().realZoom,
            thumbPanX = uws_pansvgthumb.getPan().x,
            thumbPanY = uws_pansvgthumb.getPan().y,
            thumbZoom = uws_pansvgthumb.getSizes().realZoom;
  
        var thumByMainZoomRatio = thumbZoom / mainZoom;
  
        var scopeX = thumbPanX - mainPanX * thumByMainZoomRatio;
        var scopeY = thumbPanY - mainPanY * thumByMainZoomRatio;
        var scopeWidth = mainWidth * thumByMainZoomRatio;
        var scopeHeight = mainHeight * thumByMainZoomRatio;
  
        scope.setAttribute("x", scopeX + 1);
        scope.setAttribute("y", scopeY + 1);
        scope.setAttribute("width", scopeWidth - 2);
        scope.setAttribute("height", scopeHeight - 2);
        /*
                line1.setAttribute("x1", scopeX + 1);
                line1.setAttribute("y1", scopeY + 1);
                line1.setAttribute("x2", scopeX + 1 + scopeWidth - 2);
                line1.setAttribute("y2", scopeY + 1 + scopeHeight - 2);
                line2.setAttribute("x1", scopeX + 1);
                line2.setAttribute("y1", scopeY + 1 + scopeHeight - 2);
                line2.setAttribute("x2", scopeX + 1 + scopeWidth - 2);
                line2.setAttribute("y2", scopeY + 1);
              */
      };
  
      window.uws_pansvgthumb.updateThumbScope = function() {
        // TODO: Parametrizar estas varibales id del html
        var scope = document.querySelector('.uws-map-thumbview-scope .uwscope');
        var line1 = document.querySelector('.uws-map-thumbview-scope .uwsline1');
        var line2 = document.querySelector('.uws-map-thumbview-scope .uwsline2');
        _updateThumbScope(window.uws_pansvgzoom, window.uws_pansvgthumb, scope, line1, line2);
      }
      window.uws_pansvgthumb.updateThumbScope();
  
      var _updateMainViewPan = function(clientX, clientY, scopeContainer, uws_pansvgzoom, uws_pansvgthumb) {
        var dim = scopeContainer.getBoundingClientRect(),
            mainWidth = uws_pansvgzoom.getSizes().width,
            mainHeight = uws_pansvgzoom.getSizes().height,
            mainZoom = uws_pansvgzoom.getSizes().realZoom,
            thumbWidth = uws_pansvgthumb.getSizes().width,
            thumbHeight = uws_pansvgthumb.getSizes().height,
            thumbZoom = uws_pansvgthumb.getSizes().realZoom;
  
        var thumbPanX = clientX - dim.left - thumbWidth / 2;
        var thumbPanY = clientY - dim.top - thumbHeight / 2;
        var mainPanX = -thumbPanX * mainZoom / thumbZoom;
        var mainPanY = -thumbPanY * mainZoom / thumbZoom;
        uws_pansvgzoom.pan({
          x: mainPanX,
          y: mainPanY
        });
      };
      var updateMainViewPan = function(evt, scopeContainerId) {
        if (evt.which == 0 && evt.button == 0) {
          return false;
        }
        var scopeContainer = document.querySelector(scopeContainerId);
        _updateMainViewPan(evt.clientX, evt.clientY, scopeContainer, window.uws_pansvgzoom, window.thumb);
      }
  
      var scopeContainer = document.querySelector(scopeContainerId);
      scopeContainer.addEventListener('click', function(evt) {
        updateMainViewPan(evt, scopeContainerId);
      });
  
      scopeContainer.addEventListener('mousemove', function(evt) {
        updateMainViewPan(evt, scopeContainerId);
      });
    };
  
    var initMainView = function() {
      var mainViewSVGDoc = getSVGDocument(mainViewObjectElem);
      if (options.onMainViewSVGLoaded) {
        options.onMainViewSVGLoaded(mainViewSVGDoc);
      }
  
      var beforePan = function(oldPan, newPan) {
        var stopHorizontal = false,
            stopVertical = false,
            gutterWidth = uws_pansvgguttwidth,
            gutterHeight = uws_passvgguttheight
        // Computed variables
        ,
        sizes = this.getSizes(),
        leftLimit = -((sizes.viewBox.x + sizes.viewBox.width) * sizes.realZoom) + gutterWidth,
        rightLimit = sizes.width - gutterWidth - (sizes.viewBox.x * sizes.realZoom),
        topLimit = -((sizes.viewBox.y + sizes.viewBox.height) * sizes.realZoom) + gutterHeight,
        bottomLimit = sizes.height - gutterHeight - (sizes.viewBox.y * sizes.realZoom);
        customPan = {};
        customPan.x = Math.max(leftLimit, Math.min(rightLimit, newPan.x));
        customPan.y = Math.max(topLimit, Math.min(bottomLimit, newPan.y));

        return customPan;
      };
  
      //main svg pan
      uws_pansvgzoom = svgPanZoom(options.mainSVGId, {
        zoomEnabled: true,
        controlIconsEnabled: false,
        fit: true,
        center: true,
        minZoom: uws_pansvgminzoom,
        maxZoom: uws_pansvgmaxzoom,
        zoomScaleSensitivity: 0.5,
        mouseWheelZoomEnabled: true,
        customEventsHandler: uvpaneventsHandler,
        /*beforePan: beforePan,*/
      });

      uws_passvgguttheight = document.querySelector(".uws-map-graph").clientHeight - 40;
      uws_pansvgguttwidth = document.querySelector(".uws-map-graph").clientWidth - 40;
  
      bindThumbnail(uws_pansvgzoom, undefined, options.scopeContainerId);
      if (options.onMainViewShown) {
        options.onMainViewShown(mainViewSVGDoc, uws_pansvgzoom);
      }
    };
    var mainViewObjectElem = document.querySelector(options.mainSVGId);
    mainViewObjectElem.addEventListener("load", function() {
      initMainView();
    }, false);
  
    var initThumbView = function() {
      var thumbViewSVGDoc = getSVGDocument(thumbViewObjectElem);
      if (options.onThumbnailSVGLoaded) {
        options.onThumbnailSVGLoaded(thumbViewSVGDoc);
      }
  
      uws_pansvgthumb = svgPanZoom(options.thumbSVGId, {
        fit: true,
        zoomEnabled: false,
        panEnabled: false,
        controlIconsEnabled: false,
        dblClickZoomEnabled: false,
        preventMouseEventsDefault: true,
        center: true,
      });
  
      bindThumbnail(undefined, uws_pansvgthumb, options.scopeContainerId);
      if (options.onThumbnailShown) {
        options.onThumbnailShown(thumbViewSVGDoc, uws_pansvgthumb);
      }
    };

    var thumbViewObjectElem = document.querySelector(options.thumbSVGId);

    thumbViewObjectElem.addEventListener("load", function() {
      initThumbView();
    }, false);
  
    // Se inicializan los controles
    initThumbView();
    initMainView();
  };
  
function uwsAddMapZoom(){
    uws_pansvgzoom = "";
    uws_pansvgthumb = "";

    document.querySelector(".uwsjs-map-zoomout").classList.add("uwsdisabled");
    document.querySelector(".uwsjs-map-zoomin").classList.remove("uwsdisabled");

    let uvmapviewheight = document.querySelector(".uws-map-graph").clientHeight;
    let uvmapviewwidth = document.querySelector(".uws-map-graph").clientWidth;

    let uvmapthumbviewheight = uvmapviewheight * uws_pansvgthumbprop;
    let uvmapthumbviewwidth = uvmapviewwidth * uws_pansvgthumbprop;

    let uvmapthumbviewobj = document.querySelector(".uws-map-thumbview");
    uvmapthumbviewobj.style.height = uvmapthumbviewheight + "px";
    uvmapthumbviewobj.style.width = uvmapthumbviewwidth + "px";

    setTimeout(function(){
        uwsThumbnailViewer({
            mainSVGId: '.uws-map-graph svg',
            thumbSVGId: '.uws-map-thumbmap svg',
            scopeContainerId: '.uws-map-thumbview-scope'
        });
    }, 500);

    window.onresize = uwsPanResize;
}

function uwsPanResize(){
  if(typeof(uws_pansvgzoom) == "object"){
    let uvmapviewheight = document.querySelector(".uws-map-graph").clientHeight;
    let uvmapviewwidth = document.querySelector(".uws-map-graph").clientWidth;

    let uvmapthumbviewheight = uvmapviewheight * uws_pansvgthumbprop;
    let uvmapthumbviewwidth = uvmapviewwidth * uws_pansvgthumbprop;

    let uvmapthumbviewobj = document.querySelector(".uws-map-thumbview");
    uvmapthumbviewobj.style.height = uvmapthumbviewheight + "px";
    uvmapthumbviewobj.style.width = uvmapthumbviewwidth + "px";

    uws_passvgguttheight = document.querySelector(".uws-map-graph").clientHeight - 40;
    uws_pansvgguttwidth = document.querySelector(".uws-map-graph").clientWidth - 40;

    uws_pansvgzoom.resize();
    uws_pansvgzoom.fit();
    uws_pansvgzoom.center();

    setTimeout(function(){
        uws_pansvgthumb.resize();
        uws_pansvgthumb.fit();
        uws_pansvgthumb.center();
    }, 500);
  }
}

function uwsMapZoomIn(){
    uws_pansvgzoom.zoomIn();
}
function uwsMapZoomOut(){
    uws_pansvgzoom.zoomOut();
}

/* Hammer Pan Script */
var uvpaneventsHandler;

uvpaneventsHandler = {
    haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel']
, init: function(options) {
    var instance = options.instance
        , initialScale = 1
        , pannedX = 0
        , pannedY = 0

    // Init Hammer
    // Listen only for pointer and touch events
    this.hammer = Hammer(options.svgElement, {
        inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
    })

    // Enable pinch
    this.hammer.get('pinch').set({enable: true})

    // Handle double tap
    this.hammer.on('doubletap', function(ev){
        instance.zoomIn()
    })

    // Handle pan
    this.hammer.on('panstart panmove', function(ev){
        // On pan start reset panned variables
        if (ev.type === 'panstart') {
        pannedX = 0
        pannedY = 0
        }

        // Pan only the difference
        instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY})
        pannedX = ev.deltaX
        pannedY = ev.deltaY
    })

    // Handle pinch
    this.hammer.on('pinchstart pinchmove', function(ev){
        //console.log(ev);
        //alert(ev);

        // On pinch start remember initial zoom
        if (ev.type === 'pinchstart') {
          initialScale = instance.getZoom()
          instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
        }

        instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
    })

    // Prevent moving the page on some devices when panning over SVG
    options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
  }

, destroy: function(){
    this.hammer.destroy()
    }
}