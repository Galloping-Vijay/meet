(function(window){var svgSprite='<svg><symbol id="icon-fenxiang" viewBox="0 0 1024 1024"><path d="M521.691221 514.311649l130.169665 0 0 164.454516L972.168006 404.502707 651.860886 128.084167l0 158.965501c-336.348468 5.53404-399.75559 273.888927-411.648454 368.565217l0.704035 0C269.0799 514.675946 521.691221 514.311649 521.691221 514.311649zM790.630417 825.414129 126.348128 825.414129c-25.734104 0-46.667879-20.936845-46.667879-46.666856L79.680249 222.725664c0-25.732058 20.933775-46.666856 46.667879-46.666856l427.910836 0 0 31.112602L126.348128 207.17141c-8.580422 0-15.557324 6.975878-15.557324 15.555278l0 556.021609c0 8.578376 6.976902 15.556301 15.557324 15.556301l664.281265 0c8.579399 0 15.555278-6.976902 15.555278-15.556301L806.184671 665.881716l31.111578 0 0 112.865557C837.297272 804.477284 816.360428 825.414129 790.630417 825.414129z"  ></path></symbol><symbol id="icon-zan" viewBox="0 0 1024 1024"><path d="M992 644c0-36-12-60-36-72 12-12 36-48 36-84 0-54-54-96-138-96h-216c36-102 30-234-18-306-24-42-60-54-78-54-84 0-84 78-84 102 0 84-12 126-30 162-12 18-84 96-162 96h-138c-60 0-96 48-96 114l36 390c6 60 36 96 96 96h84c18 0 36-12 48-24 12-6 18-12 24-12 6 0 18 12 42 18 18 12 48 18 84 18h300c102 0 150-36 150-102 0-18-6-30-12-42 42-12 78-36 78-78 0-18-6-42-18-48 6 0 48-36 48-78v0 0 0zM236 956h-84c-24 0-42-24-48-48l-36-408c0-30 24-66 60-66v0h144v498c-6 6-12 6-18 12s-12 12-18 12v0 0 0zM890 698h-6v36c6 0 18 6 18 36 0 36-42 48-84 48v0 36c18 6 18 18 18 36 0 36-30 66-108 66h-306c-12 0-42-6-60-18-18-6-42-24-54-30v-480c78-24 144-90 156-120 18-42 24-84 24-180 0-48 18-54 36-54 18 0 42 18 54 36 36 60 48 186 0 288v36h276c48 0 96 24 96 54v12c0 0 0 30-18 42-12 12-24 12-24 12v36c0 0 18 0 24 6 12 6 18 24 18 42 0 24-36 60-60 60v0 0 0z" fill="#007aff" ></path></symbol></svg>';var script=function(){var scripts=document.getElementsByTagName("script");return scripts[scripts.length-1]}();var shouldInjectCss=script.getAttribute("data-injectcss");var ready=function(fn){if(document.addEventListener){if(~["complete","loaded","interactive"].indexOf(document.readyState)){setTimeout(fn,0)}else{var loadFn=function(){document.removeEventListener("DOMContentLoaded",loadFn,false);fn()};document.addEventListener("DOMContentLoaded",loadFn,false)}}else if(document.attachEvent){IEContentLoaded(window,fn)}function IEContentLoaded(w,fn){var d=w.document,done=false,init=function(){if(!done){done=true;fn()}};var polling=function(){try{d.documentElement.doScroll("left")}catch(e){setTimeout(polling,50);return}init()};polling();d.onreadystatechange=function(){if(d.readyState=="complete"){d.onreadystatechange=null;init()}}}};var before=function(el,target){target.parentNode.insertBefore(el,target)};var prepend=function(el,target){if(target.firstChild){before(el,target.firstChild)}else{target.appendChild(el)}};function appendSvg(){var div,svg;div=document.createElement("div");div.innerHTML=svgSprite;svgSprite=null;svg=div.getElementsByTagName("svg")[0];if(svg){svg.setAttribute("aria-hidden","true");svg.style.position="absolute";svg.style.width=0;svg.style.height=0;svg.style.overflow="hidden";prepend(svg,document.body)}}if(shouldInjectCss&&!window.__iconfont__svg__cssinject__){window.__iconfont__svg__cssinject__=true;try{document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>")}catch(e){console&&console.log(e)}}ready(appendSvg)})(window)