//https://jscompress.com/
(function(a,d,p){a.fn.backstretch=function(c,b){(c===p||0===c.length)&&a.error("No images were supplied for Backstretch");0===a(d).scrollTop()&&d.scrollTo(0,0);return this.each(function(){var d=a(this),g=d.data("backstretch");if(g){if("string"==typeof c&&"function"==typeof g[c]){g[c](b);return}b=a.extend(g.options,b);g.destroy(!0)}g=new q(this,c,b);d.data("backstretch",g)})};a.backstretch=function(c,b){return a("body").backstretch(c,b).data("backstretch")};a.expr[":"].backstretch=function(c){return a(c).data("backstretch")!==p};a.fn.backstretch.defaults={centeredX:!0,centeredY:!0,duration:5E3,fade:0};var r={left:0,top:0,overflow:"hidden",margin:0,padding:0,height:"100%",width:"100%",zIndex:-999999},s={position:"absolute",display:"none",margin:0,padding:0,border:"none",width:"auto",height:"auto",maxHeight:"none",maxWidth:"none",zIndex:-999999},q=function(c,b,e){this.options=a.extend({},a.fn.backstretch.defaults,e||{});this.images=a.isArray(b)?b:[b];a.each(this.images,function(){a("<img />")[0].src=this});this.isBody=c===document.body;this.$container=a(c);this.$root=this.isBody?l?a(d):a(document):this.$container;c=this.$container.children(".backstretch").first();this.$wrap=c.length?c:a('<div class="backstretch"></div>').css(r).appendTo(this.$container);this.isBody||(c=this.$container.css("position"),b=this.$container.css("zIndex"),this.$container.css({position:"static"===c?"relative":c,zIndex:"auto"===b?0:b,background:"none"}),this.$wrap.css({zIndex:-999998}));this.$wrap.css({position:this.isBody&&l?"fixed":"absolute"});this.index=0;this.show(this.index);a(d).on("resize.backstretch",a.proxy(this.resize,this)).on("orientationchange.backstretch",a.proxy(function(){this.isBody&&0===d.pageYOffset&&(d.scrollTo(0,1),this.resize())},this))};q.prototype={resize:function(){try{var a={left:0,top:0},b=this.isBody?this.$root.width():this.$root.innerWidth(),e=b,g=this.isBody?d.innerHeight?d.innerHeight:this.$root.height():this.$root.innerHeight(),j=e/this.$img.data("ratio"),f;j>=g?(f=(j-g)/2,this.options.centeredY&&(a.top="-"+f+"px")):(j=g,e=j*this.$img.data("ratio"),f=(e-b)/2,this.options.centeredX&&(a.left="-"+f+"px"));this.$wrap.css({width:b,height:g}).find("img:not(.deleteable)").css({width:e,height:j}).css(a)}catch(h){}return this},show:function(c){if(!(Math.abs(c)>this.images.length-1)){var b=this,e=b.$wrap.find("img").addClass("deleteable"),d={relatedTarget:b.$container[0]};b.$container.trigger(a.Event("backstretch.before",d),[b,c]);this.index=c;clearInterval(b.interval);b.$img=a("<img />").css(s).bind("load",function(f){var h=this.width||a(f.target).width();f=this.height||a(f.target).height();a(this).data("ratio",h/f);a(this).fadeIn(b.options.speed||b.options.fade,function(){e.remove();b.paused||b.cycle();a(["after","show"]).each(function(){b.$container.trigger(a.Event("backstretch."+this,d),[b,c])})});b.resize()}).appendTo(b.$wrap);b.$img.attr("src",b.images[c]);return b}},next:function(){return this.show(this.index<this.images.length-1?this.index+1:0)},prev:function(){return this.show(0===this.index?this.images.length-1:this.index-1)},pause:function(){this.paused=!0;return this},resume:function(){this.paused=!1;this.next();return this},cycle:function(){1<this.images.length&&(clearInterval(this.interval),this.interval=setInterval(a.proxy(function(){this.paused||this.next()},this),this.options.duration));return this},destroy:function(c){a(d).off("resize.backstretch orientationchange.backstretch");clearInterval(this.interval);c||this.$wrap.remove();this.$container.removeData("backstretch")}};var l,f=navigator.userAgent,m=navigator.platform,e=f.match(/AppleWebKit\/([0-9]+)/),e=!!e&&e[1],h=f.match(/Fennec\/([0-9]+)/),h=!!h&&h[1],n=f.match(/Opera Mobi\/([0-9]+)/),t=!!n&&n[1],k=f.match(/MSIE ([0-9]+)/),k=!!k&&k[1];l=!((-1<m.indexOf("iPhone")||-1<m.indexOf("iPad")||-1<m.indexOf("iPod"))&&e&&534>e||d.operamini&&"[object OperaMini]"==={}.toString.call(d.operamini)||n&&7458>t||-1<f.indexOf("Android")&&e&&533>e||h&&6>h||"palmGetResource"in d&&e&&534>e||-1<f.indexOf("MeeGo")&&-1<f.indexOf("NokiaBrowser/8.5.0")||k&&6>=k)})(jQuery,window);$.backstretch('css/fd.jpg');(function(){function t(e,t){return[].slice.call((t||document).querySelectorAll(e))}if(!window.addEventListener)return;var e=window.StyleFix={link:function(t){try{if(t.rel!=="stylesheet"||t.hasAttribute("data-noprefix"))return}catch(n){return}var r=t.href||t.getAttribute("data-href"),i=r.replace(/[^\/]+$/,""),s=(/^[a-z]{3,10}:/.exec(i)||[""])[0],o=(/^[a-z]{3,10}:\/\/[^\/]+/.exec(i)||[""])[0],u=/^([^?]*)\??/.exec(r)[1],a=t.parentNode,f=new XMLHttpRequest,l;f.onreadystatechange=function(){f.readyState===4&&l()};l=function(){var n=f.responseText;if(n&&t.parentNode&&(!f.status||f.status<400||f.status>600)){n=e.fix(n,!0,t);if(i){n=n.replace(/url\(\s*?((?:"|')?)(.+?)\1\s*?\)/gi,function(e,t,n){return/^([a-z]{3,10}:|#)/i.test(n)?e:/^\/\//.test(n)?'url("'+s+n+'")':/^\//.test(n)?'url("'+o+n+'")':/^\?/.test(n)?'url("'+u+n+'")':'url("'+i+n+'")'});var r=i.replace(/([\\\^\$*+[\]?{}.=!:(|)])/g,"\\$1");n=n.replace(RegExp("\\b(behavior:\\s*?url\\('?\"?)"+r,"gi"),"$1")}var l=document.createElement("style");l.textContent=n;l.media=t.media;l.disabled=t.disabled;l.setAttribute("data-href",t.getAttribute("href"));a.insertBefore(l,t);a.removeChild(t);l.media=t.media}};try{f.open("GET",r);f.send(null)}catch(n){if(typeof XDomainRequest!="undefined"){f=new XDomainRequest;f.onerror=f.onprogress=function(){};f.onload=l;f.open("GET",r);f.send(null)}}t.setAttribute("data-inprogress","")},styleElement:function(t){if(t.hasAttribute("data-noprefix"))return;var n=t.disabled;t.textContent=e.fix(t.textContent,!0,t);t.disabled=n},styleAttribute:function(t){var n=t.getAttribute("style");n=e.fix(n,!1,t);t.setAttribute("style",n)},process:function(){t('link[rel="stylesheet"]:not([data-inprogress])').forEach(StyleFix.link);t("style").forEach(StyleFix.styleElement);t("[style]").forEach(StyleFix.styleAttribute)},register:function(t,n){(e.fixers=e.fixers||[]).splice(n===undefined?e.fixers.length:n,0,t)},fix:function(t,n,r){for(var i=0;i<e.fixers.length;i++)t=e.fixers[i](t,n,r)||t;return t},camelCase:function(e){return e.replace(/-([a-z])/g,function(e,t){return t.toUpperCase()}).replace("-","")},deCamelCase:function(e){return e.replace(/[A-Z]/g,function(e){return"-"+e.toLowerCase()})}};(function(){setTimeout(function(){t('link[rel="stylesheet"]').forEach(StyleFix.link)},10);document.addEventListener("DOMContentLoaded",StyleFix.process,!1)})()})();(function(e){function t(e,t,r,i,s){e=n[e];if(e.length){var o=RegExp(t+"("+e.join("|")+")"+r,"gi");s=s.replace(o,i)}return s}if(!window.StyleFix||!window.getComputedStyle)return;var n=window.PrefixFree={prefixCSS:function(e,r,i){var s=n.prefix;n.functions.indexOf("linear-gradient")>-1&&(e=e.replace(/(\s|:|,)(repeating-)?linear-gradient\(\s*(-?\d*\.?\d*)deg/ig,function(e,t,n,r){return t+(n||"")+"linear-gradient("+(90-r)+"deg"}));e=t("functions","(\\s|:|,)","\\s*\\(","$1"+s+"$2(",e);e=t("keywords","(\\s|:)","(\\s|;|\\}|$)","$1"+s+"$2$3",e);e=t("properties","(^|\\{|\\s|;)","\\s*:","$1"+s+"$2:",e);if(n.properties.length){var o=RegExp("\\b("+n.properties.join("|")+")(?!:)","gi");e=t("valueProperties","\\b",":(.+?);",function(e){return e.replace(o,s+"$1")},e)}if(r){e=t("selectors","","\\b",n.prefixSelector,e);e=t("atrules","@","\\b","@"+s+"$1",e)}e=e.replace(RegExp("-"+s,"g"),"-");e=e.replace(/-\*-(?=[a-z]+)/gi,n.prefix);return e},property:function(e){return(n.properties.indexOf(e)>=0?n.prefix:"")+e},value:function(e,r){e=t("functions","(^|\\s|,)","\\s*\\(","$1"+n.prefix+"$2(",e);e=t("keywords","(^|\\s)","(\\s|$)","$1"+n.prefix+"$2$3",e);n.valueProperties.indexOf(r)>=0&&(e=t("properties","(^|\\s|,)","($|\\s|,)","$1"+n.prefix+"$2$3",e));return e},prefixSelector:function(e){return e.replace(/^:{1,2}/,function(e){return e+n.prefix})},prefixProperty:function(e,t){var r=n.prefix+e;return t?StyleFix.camelCase(r):r}};(function(){var e={},t=[],r={},i=getComputedStyle(document.documentElement,null),s=document.createElement("div").style,o=function(n){if(n.charAt(0)==="-"){t.push(n);var r=n.split("-"),i=r[1];e[i]=++e[i]||1;while(r.length>3){r.pop();var s=r.join("-");u(s)&&t.indexOf(s)===-1&&t.push(s)}}},u=function(e){return StyleFix.camelCase(e)in s};if(i.length>0)for(var a=0;a<i.length;a++)o(i[a]);else for(var f in i)o(StyleFix.deCamelCase(f));var l={uses:0};for(var c in e){var h=e[c];l.uses<h&&(l={prefix:c,uses:h})}n.prefix="-"+l.prefix+"-";n.Prefix=StyleFix.camelCase(n.prefix);n.properties=[];for(var a=0;a<t.length;a++){var f=t[a];if(f.indexOf(n.prefix)===0){var p=f.slice(n.prefix.length);u(p)||n.properties.push(p)}}n.Prefix=="Ms"&&!("transform"in s)&&!("MsTransform"in s)&&"msTransform"in s&&n.properties.push("transform","transform-origin");n.properties.sort()})();(function(){function i(e,t){r[t]="";r[t]=e;return!!r[t]}var e={"linear-gradient":{property:"backgroundImage",params:"red, teal"},calc:{property:"width",params:"1px + 5%"},element:{property:"backgroundImage",params:"#foo"},"cross-fade":{property:"backgroundImage",params:"url(a.png), url(b.png), 50%"}};e["repeating-linear-gradient"]=e["repeating-radial-gradient"]=e["radial-gradient"]=e["linear-gradient"];var t={initial:"color","zoom-in":"cursor","zoom-out":"cursor",box:"display",flexbox:"display","inline-flexbox":"display",flex:"display","inline-flex":"display",grid:"display","inline-grid":"display","min-content":"width"};n.functions=[];n.keywords=[];var r=document.createElement("div").style;for(var s in e){var o=e[s],u=o.property,a=s+"("+o.params+")";!i(a,u)&&i(n.prefix+a,u)&&n.functions.push(s)}for(var f in t){var u=t[f];!i(f,u)&&i(n.prefix+f,u)&&n.keywords.push(f)}})();(function(){function s(e){i.textContent=e+"{}";return!!i.sheet.cssRules.length}var t={":read-only":null,":read-write":null,":any-link":null,"::selection":null},r={keyframes:"name",viewport:null,document:'regexp(".")'};n.selectors=[];n.atrules=[];var i=e.appendChild(document.createElement("style"));for(var o in t){var u=o+(t[o]?"("+t[o]+")":"");!s(u)&&s(n.prefixSelector(u))&&n.selectors.push(o)}for(var a in r){var u=a+" "+(r[a]||"");!s("@"+u)&&s("@"+n.prefix+u)&&n.atrules.push(a)}e.removeChild(i)})();n.valueProperties=["transition","transition-property"];e.className+=" "+n.prefix;StyleFix.register(n.prefixCSS)})(document.documentElement);
// infos bulles
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
});
// mixed tooltip in various docs (tooltip + modal, same toogle, so put tooltip in data-tooltip)
$('[data-tooltip="tooltip"]').tooltip();
// modal
$('#modalPmnl').on('hidden.bs.modal', function() {
	$(this).data('bs.modal', null);
});
// resize for preview traditionnal redaction
function xx(e, f) {
	$("#_preview").width(e).height(f);
	$(".iframePreview").animate({
		width: e + 5,
		height: f + 5
	}, 500);
	$("#_preview").attr("src", $("#_preview").attr("src"));
}
// iframe auto resizing
function rszifr(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + "px";
}
// archive suppression
function deleteArchive() {
	document.archive_form.elements['action'].value = 'delete';
	document.archive_form.submit();
}
// checkbox to mix lists
$(document).ready(function() {
	$('input[type=checkbox].mx').change(function() {
		if ($('input.mx:checked').size() > 1) {
			$("div#submitMix").show("slow");
			$("input#sbmix").removeAttr('disabled');
		} else {
			$('div#submitMix').hide("slow");
		}
	});
});
(function($) {
	$.fn.equalHeight = function() {
		tallest = 0;
		this.each(function() {
			thisHeight = $(this).height();
			if (thisHeight > tallest)
				tallest = thisHeight;
		});
		this.each(function() {
			$(this).height(tallest);
		});
	}
})(jQuery);
// dynamic table
$("#datatable").dataTable({
	"pageLength": 25
});
$("#datatableU").dataTable({
	"pageLength": 25
});
// new scheduled task highlighted
$(document).ready(function() {
	$("tr#tog").css("background", "#B5E5EF");
});
// fonction table sort
$(document).ready(function() {
	var showText = 'Show';
	var hideText = 'Hide';
	var is_visible = false;
	$('.toggle').prev().append(' <a href="#" class="toggleLink">' + hideText + '</a>');
	$('.toggle').show();
	$('a.toggleLink').click(function() {
		is_visible = !is_visible;
		if ($(this).text() == showText) {
			$(this).text(hideText);
			$(this).parent().next('.toggle').slideDown('slow');
		} else {
			$(this).text(showText);
			$(this).parent().next('.toggle').slideUp('slow');
		}
		return false;
	});
});
// Function notify
/*
https://www.jqueryscript.net/demo/jQuery-Plugin-For-HTML5-Desktop-Push-Notifications-easyNotify-js/
<script>
var myImg = "//www.phpmynewsletter.com/wp-content/uploads/2017/04/cropped-phpmynewsletter_v2.png";
$("form").submit(function(event) {
	event.preventDefault();
	var options = {
		title: $("#title").val(),
		options: {
			body: $("#message").val(),
			icon: myImg,
			lang: 'en-US',
			onClick: myFunction
		}
	};
	console.log(options);
	$("#easyNotify").easyNotify(options);
});
</script>
*/
(function($) {
	$.fn.easyNotify = function(options) {
		var settings = $.extend({
			title: "Notification",
			options: {
			body: "",
			icon: "",
			lang: "fr-FR",
			onClose: "",
			onClick: "",
			onError: ""
			}
		}, options);
		this.init = function() {
			var notify = this;
			if (!("Notification" in window)) {
				alert("This browser does not support desktop notification");
			} else if (Notification.permission === "granted") {
				var notification = new Notification(settings.title, settings.options);
				notification.onclose = function() {
					if (typeof settings.options.onClose == 'function') { 
						settings.options.onClose();
					}
				};
				notification.onclick = function(){
					if (typeof settings.options.onClick == 'function') { 
						settings.options.onClick();
					}
				};
				notification.onerror  = function(){
					if (typeof settings.options.onError == 'function') { 
						settings.options.onError();
					}
				};
			} else if (Notification.permission !== "denied") {
				Notification.requestPermission(function(permission) {
					if (permission === "granted") {
						notify.init();
					}
				});
			}
		};
		this.init();
		return this;
	};
}(jQuery));
(function($) {
	$.extend({
		tablesorter: new function() {
			var parsers = [],widgets = [];
			this.defaults = {
				cssHeader: "header",
				cssAsc: "headerSortUp",
				cssDesc: "headerSortDown",
				cssChildRow: "expand-child",
				sortInitialOrder: "asc",
				sortMultiSortKey: "shiftKey",
				sortForce: null,
				sortAppend: null,
				sortLocaleCompare: true,
				textExtraction: "simple",
				parsers: {},
				widgets: [],
				widgetZebra: {
					css: ["even", "odd"]
				},
				headers: {},
				widthFixed: false,
				cancelSelection: true,
				sortList: [],
				headerList: [],
				dateFormat: "us",
				decimal: '/\.|\,/g',
				onRenderHeader: null,
				selectorHeaders: 'thead th',
				debug: false
			};

			function benchmark(s, d) {
				log(s + "," + (new Date().getTime() - d.getTime()) + "ms");
			}
			this.benchmark = benchmark;

			function log(s) {
				if (typeof console != "undefined" && typeof console.debug != "undefined") {
					console.log(s);
				} else {
					alert(s);
				}
			}

			function buildParserCache(table, $headers) {
				if (table.config.debug) {
					var parsersDebug = "";
				}
				if (table.tBodies.length == 0) return;
				var rows = table.tBodies[0].rows;
				if (rows[0]) {
					var list = [],
						cells = rows[0].cells,
						l = cells.length;
					for (var i = 0; i < l; i++) {
						var p = false;
						if ($.metadata && ($($headers[i]).metadata() && $($headers[i]).metadata().sorter)) {
							p = getParserById($($headers[i]).metadata().sorter);
						} else if ((table.config.headers[i] && table.config.headers[i].sorter)) {
							p = getParserById(table.config.headers[i].sorter);
						}
						if (!p) {
							p = detectParserForColumn(table, rows, -1, i);
						}
						if (table.config.debug) {
							parsersDebug += "column:" + i + " parser:" + p.id + "\n";
						}
						list.push(p);
					}
				}
				if (table.config.debug) {
					log(parsersDebug);
				}
				return list;
			};

			function detectParserForColumn(table, rows, rowIndex, cellIndex) {
				var l = parsers.length,
					node = false,
					nodeValue = false,
					keepLooking = true;
				while (nodeValue == '' && keepLooking) {
					rowIndex++;
					if (rows[rowIndex]) {
						node = getNodeFromRowAndCellIndex(rows, rowIndex, cellIndex);
						nodeValue = trimAndGetNodeText(table.config, node);
						if (table.config.debug) {
							log('Checking if value was empty on row:' + rowIndex);
						}
					} else {
						keepLooking = false;
					}
				}
				for (var i = 1; i < l; i++) {
					if (parsers[i].is(nodeValue, table, node)) {
						return parsers[i];
					}
				}
				return parsers[0];
			}

			function getNodeFromRowAndCellIndex(rows, rowIndex, cellIndex) {
				return rows[rowIndex].cells[cellIndex];
			}

			function trimAndGetNodeText(config, node) {
				return $.trim(getElementText(config, node));
			}

			function getParserById(name) {
				var l = parsers.length;
				for (var i = 0; i < l; i++) {
					if (parsers[i].id.toLowerCase() == name.toLowerCase()) {
						return parsers[i];
					}
				}
				return false;
			}

			function buildCache(table) {
				if (table.config.debug) {
					var cacheTime = new Date();
				}
				var totalRows = (table.tBodies[0] && table.tBodies[0].rows.length) || 0,
					totalCells = (table.tBodies[0].rows[0] && table.tBodies[0].rows[0].cells.length) || 0,
					parsers = table.config.parsers,
					cache = {
						row: [],
						normalized: []
					};
				for (var i = 0; i < totalRows; ++i) {
					var c = $(table.tBodies[0].rows[i]),
						cols = [];
					if (c.hasClass(table.config.cssChildRow)) {
						cache.row[cache.row.length - 1] = cache.row[cache.row.length - 1].add(c);
						continue;
					}
					cache.row.push(c);
					for (var j = 0; j < totalCells; ++j) {
						cols.push(parsers[j].format(getElementText(table.config, c[0].cells[j]), table, c[0].cells[j]));
					}
					cols.push(cache.normalized.length);
					cache.normalized.push(cols);
					cols = null;
				};
				if (table.config.debug) {
					benchmark("Building cache for " + totalRows + " rows:", cacheTime);
				}
				return cache;
			};

			function getElementText(config, node) {
				var text = "";
				if (!node) return "";
				if (!config.supportsTextContent) config.supportsTextContent = node.textContent || false;
				if (config.textExtraction == "simple") {
					if (config.supportsTextContent) {
						text = node.textContent;
					} else {
						if (node.childNodes[0] && node.childNodes[0].hasChildNodes()) {
							text = node.childNodes[0].innerHTML;
						} else {
							text = node.innerHTML;
						}
					}
				} else {
					if (typeof(config.textExtraction) == "function") {
						text = config.textExtraction(node);
					} else {
						text = $(node).text();
					}
				}
				return text;
			}

			function appendToTable(table, cache) {
				if (table.config.debug) {
					var appendTime = new Date()
				}
				var c = cache,
					r = c.row,
					n = c.normalized,
					totalRows = n.length,
					checkCell = (n[0].length - 1),
					tableBody = $(table.tBodies[0]),
					rows = [];
				for (var i = 0; i < totalRows; i++) {
					var pos = n[i][checkCell];
					rows.push(r[pos]);
					if (!table.config.appender) {
						var l = r[pos].length;
						for (var j = 0; j < l; j++) {
							tableBody[0].appendChild(r[pos][j]);
						}
					}
				}
				if (table.config.appender) {
					table.config.appender(table, rows);
				}
				rows = null;
				if (table.config.debug) {
					benchmark("Rebuilt table:", appendTime);
				}
				applyWidget(table);
				setTimeout(function() {
					$(table).trigger("sortEnd");
				}, 0);
			};

			function buildHeaders(table) {
				if (table.config.debug) {
					var time = new Date();
				}
				var meta = ($.metadata) ? true : false;
				var header_index = computeTableHeaderCellIndexes(table);
				$tableHeaders = $(table.config.selectorHeaders, table).each(function(index) {
					this.column = header_index[this.parentNode.rowIndex + "-" + this.cellIndex];
					this.order = formatSortingOrder(table.config.sortInitialOrder);
					this.count = this.order;
					if (checkHeaderMetadata(this) || checkHeaderOptions(table, index)) this.sortDisabled = true;
					if (checkHeaderOptionsSortingLocked(table, index)) this.order = this.lockedOrder = checkHeaderOptionsSortingLocked(table, index);
					if (!this.sortDisabled) {
						var $th = $(this).addClass(table.config.cssHeader);
						if (table.config.onRenderHeader) table.config.onRenderHeader.apply($th);
					}
					table.config.headerList[index] = this;
				});
				if (table.config.debug) {
					benchmark("Built headers:", time);
					log($tableHeaders);
				}
				return $tableHeaders;
			};

			function computeTableHeaderCellIndexes(t) {
				var matrix = [];
				var lookup = {};
				var thead = t.getElementsByTagName('THEAD')[0];
				var trs = thead.getElementsByTagName('TR');
				for (var i = 0; i < trs.length; i++) {
					var cells = trs[i].cells;
					for (var j = 0; j < cells.length; j++) {
						var c = cells[j];
						var rowIndex = c.parentNode.rowIndex;
						var cellId = rowIndex + "-" + c.cellIndex;
						var rowSpan = c.rowSpan || 1;
						var colSpan = c.colSpan || 1
						var firstAvailCol;
						if (typeof(matrix[rowIndex]) == "undefined") {
							matrix[rowIndex] = [];
						}
						for (var k = 0; k < matrix[rowIndex].length + 1; k++) {
							if (typeof(matrix[rowIndex][k]) == "undefined") {
								firstAvailCol = k;
								break;
							}
						}
						lookup[cellId] = firstAvailCol;
						for (var k = rowIndex; k < rowIndex + rowSpan; k++) {
							if (typeof(matrix[k]) == "undefined") {
								matrix[k] = [];
							}
							var matrixrow = matrix[k];
							for (var l = firstAvailCol; l < firstAvailCol + colSpan; l++) {
								matrixrow[l] = "x";
							}
						}
					}
				}
				return lookup;
			}

			function checkCellColSpan(table, rows, row) {
				var arr = [],
					r = table.tHead.rows,
					c = r[row].cells;
				for (var i = 0; i < c.length; i++) {
					var cell = c[i];
					if (cell.colSpan > 1) {
						arr = arr.concat(checkCellColSpan(table, headerArr, row++));
					} else {
						if (table.tHead.length == 1 || (cell.rowSpan > 1 || !r[row + 1])) {
							arr.push(cell);
						}
					}
				}
				return arr;
			};

			function checkHeaderMetadata(cell) {
				if (($.metadata) && ($(cell).metadata().sorter === false)) {
					return true;
				};
				return false;
			}

			function checkHeaderOptions(table, i) {
				if ((table.config.headers[i]) && (table.config.headers[i].sorter === false)) {
					return true;
				};
				return false;
			}

			function checkHeaderOptionsSortingLocked(table, i) {
				if ((table.config.headers[i]) && (table.config.headers[i].lockedOrder)) return table.config.headers[i].lockedOrder;
				return false;
			}

			function applyWidget(table) {
				var c = table.config.widgets;
				var l = c.length;
				for (var i = 0; i < l; i++) {
					getWidgetById(c[i]).format(table);
				}
			}

			function getWidgetById(name) {
				var l = widgets.length;
				for (var i = 0; i < l; i++) {
					if (widgets[i].id.toLowerCase() == name.toLowerCase()) {
						return widgets[i];
					}
				}
			};

			function formatSortingOrder(v) {
				if (typeof(v) != "Number") {
					return (v.toLowerCase() == "desc") ? 1 : 0;
				} else {
					return (v == 1) ? 1 : 0;
				}
			}

			function isValueInArray(v, a) {
				var l = a.length;
				for (var i = 0; i < l; i++) {
					if (a[i][0] == v) {
						return true;
					}
				}
				return false;
			}

			function setHeadersCss(table, $headers, list, css) {
				$headers.removeClass(css[0]).removeClass(css[1]);
				var h = [];
				$headers.each(function(offset) {
					if (!this.sortDisabled) {
						h[this.column] = $(this);
					}
				});
				var l = list.length;
				for (var i = 0; i < l; i++) {
					h[list[i][0]].addClass(css[list[i][1]]);
				}
			}

			function fixColumnWidth(table, $headers) {
				var c = table.config;
				if (c.widthFixed) {
					var colgroup = $('<colgroup>');
					$("tr:first td", table.tBodies[0]).each(function() {
						colgroup.append($('<col>').css('width', $(this).width()));
					});
					$(table).prepend(colgroup);
				};
			}

			function updateHeaderSortCount(table, sortList) {
				var c = table.config,
					l = sortList.length;
				for (var i = 0; i < l; i++) {
					var s = sortList[i],
						o = c.headerList[s[0]];
					o.count = s[1];
					o.count++;
				}
			}

			function multisort(table, sortList, cache) {
				if (table.config.debug) {
					var sortTime = new Date();
				}
				var dynamicExp = "var sortWrapper = function(a,b) {",
					l = sortList.length;
				for (var i = 0; i < l; i++) {
					var c = sortList[i][0];
					var order = sortList[i][1];
					var s = (table.config.parsers[c].type == "text") ? ((order == 0) ? makeSortFunction("text", "asc", c) : makeSortFunction("text", "desc", c)) : ((order == 0) ? makeSortFunction("numeric", "asc", c) : makeSortFunction("numeric", "desc", c));
					var e = "e" + i;
					dynamicExp += "var " + e + " = " + s;
					dynamicExp += "if(" + e + ") { return " + e + "; } ";
					dynamicExp += "else { ";
				}
				var orgOrderCol = cache.normalized[0].length - 1;
				dynamicExp += "return a[" + orgOrderCol + "]-b[" + orgOrderCol + "];";
				for (var i = 0; i < l; i++) {
					dynamicExp += "}; ";
				}
				dynamicExp += "return 0; ";
				dynamicExp += "}; ";
				if (table.config.debug) {
					benchmark("Evaling expression:" + dynamicExp, new Date());
				}
				eval(dynamicExp);
				cache.normalized.sort(sortWrapper);
				if (table.config.debug) {
					benchmark("Sorting on " + sortList.toString() + " and dir " + order + " time:", sortTime);
				}
				return cache;
			};

			function makeSortFunction(type, direction, index) {
				var a = "a[" + index + "]",
					b = "b[" + index + "]";
				if (type == 'text' && direction == 'asc') {
					return "(" + a + " == " + b + " ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : (" + a + " < " + b + ") ? -1 : 1 )));";
				} else if (type == 'text' && direction == 'desc') {
					return "(" + a + " == " + b + " ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : (" + b + " < " + a + ") ? -1 : 1 )));";
				} else if (type == 'numeric' && direction == 'asc') {
					return "(" + a + " === null && " + b + " === null) ? 0 :(" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : " + a + " - " + b + "));";
				} else if (type == 'numeric' && direction == 'desc') {
					return "(" + a + " === null && " + b + " === null) ? 0 :(" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : " + b + " - " + a + "));";
				}
			};

			function makeSortText(i) {
				return "((a[" + i + "] < b[" + i + "]) ? -1 : ((a[" + i + "] > b[" + i + "]) ? 1 : 0));";
			};

			function makeSortTextDesc(i) {
				return "((b[" + i + "] < a[" + i + "]) ? -1 : ((b[" + i + "] > a[" + i + "]) ? 1 : 0));";
			};

			function makeSortNumeric(i) {
				return "a[" + i + "]-b[" + i + "];";
			};

			function makeSortNumericDesc(i) {
				return "b[" + i + "]-a[" + i + "];";
			};

			function sortText(a, b) {
				if (table.config.sortLocaleCompare) return a.localeCompare(b);
				return ((a < b) ? -1 : ((a > b) ? 1 : 0));
			};

			function sortTextDesc(a, b) {
				if (table.config.sortLocaleCompare) return b.localeCompare(a);
				return ((b < a) ? -1 : ((b > a) ? 1 : 0));
			};

			function sortNumeric(a, b) {
				return a - b;
			};

			function sortNumericDesc(a, b) {
				return b - a;
			};

			function getCachedSortType(parsers, i) {
				return parsers[i].type;
			};
			this.construct = function(settings) {
				return this.each(function() {
					if (!this.tHead || !this.tBodies) return;
					var $this, $document, $headers, cache, config, shiftDown = 0,
						sortOrder;
					this.config = {};
					config = $.extend(this.config, $.tablesorter.defaults, settings);
					$this = $(this);
					$.data(this, "tablesorter", config);
					$headers = buildHeaders(this);
					this.config.parsers = buildParserCache(this, $headers);
					cache = buildCache(this);
					var sortCSS = [config.cssDesc, config.cssAsc];
					fixColumnWidth(this);
					$headers.click(function(e) {
						var totalRows = ($this[0].tBodies[0] && $this[0].tBodies[0].rows.length) || 0;
						if (!this.sortDisabled && totalRows > 0) {
							$this.trigger("sortStart");
							var $cell = $(this);
							var i = this.column;
							this.order = this.count++ % 2;
							if (this.lockedOrder) this.order = this.lockedOrder;
							if (!e[config.sortMultiSortKey]) {
								config.sortList = [];
								if (config.sortForce != null) {
									var a = config.sortForce;
									for (var j = 0; j < a.length; j++) {
										if (a[j][0] != i) {
											config.sortList.push(a[j]);
										}
									}
								}
								config.sortList.push([i, this.order]);
							} else {
								if (isValueInArray(i, config.sortList)) {
									for (var j = 0; j < config.sortList.length; j++) {
										var s = config.sortList[j],
											o = config.headerList[s[0]];
										if (s[0] == i) {
											o.count = s[1];
											o.count++;
											s[1] = o.count % 2;
										}
									}
								} else {
									config.sortList.push([i, this.order]);
								}
							};
							setTimeout(function() {
								setHeadersCss($this[0], $headers, config.sortList, sortCSS);
								appendToTable($this[0], multisort($this[0], config.sortList, cache));
							}, 1);
							return false;
						}
					}).mousedown(function() {
						if (config.cancelSelection) {
							this.onselectstart = function() {
								return false
							};
							return false;
						}
					});
					$this.bind("update", function() {
						var me = this;
						setTimeout(function() {
							me.config.parsers = buildParserCache(me, $headers);
							cache = buildCache(me);
						}, 1);
					}).bind("updateCell", function(e, cell) {
						var config = this.config;
						var pos = [(cell.parentNode.rowIndex - 1), cell.cellIndex];
						cache.normalized[pos[0]][pos[1]] = config.parsers[pos[1]].format(getElementText(config, cell), cell);
					}).bind("sorton", function(e, list) {
						$(this).trigger("sortStart");
						config.sortList = list;
						var sortList = config.sortList;
						updateHeaderSortCount(this, sortList);
						setHeadersCss(this, $headers, sortList, sortCSS);
						appendToTable(this, multisort(this, sortList, cache));
					}).bind("appendCache", function() {
						appendToTable(this, cache);
					}).bind("applyWidgetId", function(e, id) {
						getWidgetById(id).format(this);
					}).bind("applyWidgets", function() {
						applyWidget(this);
					});
					if ($.metadata && ($(this).metadata() && $(this).metadata().sortlist)) {
						config.sortList = $(this).metadata().sortlist;
					}
					if (config.sortList.length > 0) {
						$this.trigger("sorton", [config.sortList]);
					}
					applyWidget(this);
				});
			};
			this.addParser = function(parser) {
				var l = parsers.length,
					a = true;
				for (var i = 0; i < l; i++) {
					if (parsers[i].id.toLowerCase() == parser.id.toLowerCase()) {
						a = false;
					}
				}
				if (a) {
					parsers.push(parser);
				};
			};
			this.addWidget = function(widget) {
				widgets.push(widget);
			};
			this.formatFloat = function(s) {
				var i = parseFloat(s);
				return (isNaN(i)) ? 0 : i;
			};
			this.formatInt = function(s) {
				var i = parseInt(s);
				return (isNaN(i)) ? 0 : i;
			};
			this.isDigit = function(s, config) {
				return /^[-+]?\d*$/.test($.trim(s.replace(/[,.']/g, '')));
			};
			this.clearTableBody = function(table) {
				if ($.browser.msie) {
					function empty() {
						while (this.firstChild) this.removeChild(this.firstChild);
					}
					empty.apply(table.tBodies[0]);
				} else {
					table.tBodies[0].innerHTML = "";
				}
			};
		}
	});
	$.fn.extend({
		tablesorter: $.tablesorter.construct
	});
	var ts = $.tablesorter;
	ts.addParser({
		id: "text",
		is: function(s) {
			return true;
		},
		format: function(s) {
			return $.trim(s.toLocaleLowerCase());
		},
		type: "text"
	});
	ts.addParser({
		id: "digit",
		is: function(s, table) {
			var c = table.config;
			return $.tablesorter.isDigit(s, c);
		},
		format: function(s) {
			return $.tablesorter.formatFloat(s);
		},
		type: "numeric"
	});
	ts.addParser({
		id: "currency",
		is: function(s) {
			return /^[£$€?.]/.test(s);
		},
		format: function(s) {
			return $.tablesorter.formatFloat(s.replace(new RegExp(/[£$€]/g), ""));
		},
		type: "numeric"
	});
	ts.addParser({
		id: "ipAddress",
		is: function(s) {
			return /^\d{2,3}[\.]\d{2,3}[\.]\d{2,3}[\.]\d{2,3}$/.test(s);
		},
		format: function(s) {
			var a = s.split("."),
				r = "",
				l = a.length;
			for (var i = 0; i < l; i++) {
				var item = a[i];
				if (item.length == 2) {
					r += "0" + item;
				} else {
					r += item;
				}
			}
			return $.tablesorter.formatFloat(r);
		},
		type: "numeric"
	});
	ts.addParser({
		id: "url",
		is: function(s) {
			return /^(https?|ftp|file):\/\/$/.test(s);
		},
		format: function(s) {
			return jQuery.trim(s.replace(new RegExp(/(https?|ftp|file):\/\//), ''));
		},
		type: "text"
	});
	ts.addParser({
		id: "isoDate",
		is: function(s) {
			return /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(s);
		},
		format: function(s) {
			return $.tablesorter.formatFloat((s != "") ? new Date(s.replace(new RegExp(/-/g), "/")).getTime() : "0");
		},
		type: "numeric"
	});
	ts.addParser({
		id: "percent",
		is: function(s) {
			return /\%$/.test($.trim(s));
		},
		format: function(s) {
			return $.tablesorter.formatFloat(s.replace(new RegExp(/%/g), ""));
		},
		type: "numeric"
	});
	ts.addParser({
		id: "usLongDate",
		is: function(s) {
			return s.match(new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));
		},
		format: function(s) {
			return $.tablesorter.formatFloat(new Date(s).getTime());
		},
		type: "numeric"
	});
	ts.addParser({
		id: "shortDate",
		is: function(s) {
			return /\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/.test(s);
		},
		format: function(s, table) {
			var c = table.config;
			s = s.replace(/\-/g, "/");
			if (c.dateFormat == "us") {
				s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/, "$3/$1/$2");
			} else if (c.dateFormat == "uk") {
				s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/, "$3/$2/$1");
			} else if (c.dateFormat == "dd/mm/yy" || c.dateFormat == "dd-mm-yy") {
				s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2})/, "$1/$2/$3");
			}
			return $.tablesorter.formatFloat(new Date(s).getTime());
		},
		type: "numeric"
	});
	ts.addParser({
		id: "time",
		is: function(s) {
			return /^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/.test(s);
		},
		format: function(s) {
			return $.tablesorter.formatFloat(new Date("2000/01/01 " + s).getTime());
		},
		type: "numeric"
	});
	ts.addParser({
		id: "metadata",
		is: function(s) {
			return false;
		},
		format: function(s, table, cell) {
			var c = table.config,
				p = (!c.parserMetadataName) ? 'sortValue' : c.parserMetadataName;
			return $(cell).metadata()[p];
		},
		type: "numeric"
	});
	ts.addWidget({
		id: "zebra",
		format: function(table) {
			if (table.config.debug) {
				var time = new Date();
			}
			var $tr, row = -1,
				odd;
			$("tr:visible", table.tBodies[0]).each(function(i) {
				$tr = $(this);
				if (!$tr.hasClass(table.config.cssChildRow)) row++;
				odd = (row % 2 == 0);
				$tr.removeClass(table.config.widgetZebra.css[odd ? 0 : 1]).addClass(table.config.widgetZebra.css[odd ? 1 : 0])
			});
			if (table.config.debug) {
				$.tablesorter.benchmark("Applying Zebra widget", time);
			}
		}
	});
})(jQuery);

function checkSMTP() {
	var QC = document.global_config.elements['sending_method'].selectedIndex;
	switch(QC){
		// SMTP
		case 0:
			document.global_config.elements['smtp_host'].disabled = false;
			document.global_config.elements['smtp_host'].value = "";
			document.global_config.elements.smtp_auth[0].checked = "checked";
			document.global_config.elements.smtp_auth[1].checked = "";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_login'].value = "";
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_pass'].value = "";
			document.global_config.elements['smtp_port'].disabled = false;
			document.global_config.elements['smtp_port'].value = "";
		break;
		// SMTP TLS
		case 1:
			document.global_config.elements['smtp_host'].disabled = false;
			document.global_config.elements['smtp_host'].value = "";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		// SMTP SSL
		case 2:
			document.global_config.elements['smtp_host'].disabled = false;
			document.global_config.elements['smtp_host'].value = "";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "465";
		break;
		// LBSMTP
		case 3:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "";
			document.global_config.elements.smtp_auth[0].checked = "checked";
			document.global_config.elements.smtp_auth[1].checked = "";
			document.global_config.elements['smtp_login'].disabled = true;
			document.global_config.elements['smtp_login'].value = "";
			document.global_config.elements['smtp_pass'].disabled = true;
			document.global_config.elements['smtp_pass'].value = "";
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "";
		break;
		// SMTP GMAIL TLS
		case 4:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "smtp.gmail.com";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		// SMTP GMAIL SSL
		case 5:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "smtp.gmail.com";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "465";
		break;
		// PHP_MAIL, INFOMANIAK
		case 6:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "";
			document.global_config.elements.smtp_auth[0].checked = "checked";
			document.global_config.elements.smtp_auth[1].checked = "";
			document.global_config.elements['smtp_login'].disabled = true;
			document.global_config.elements['smtp_pass'].disabled = true;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "";
		break;
		// SMTP MUTU OVH
		case 7:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "ssl0.ovh.net";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		case 8:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "auth.smtp.1and1.fr";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "465";
		break;
		// SMTP MUTU GANDI
		case 9:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "mail.gandi.net";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		// SMTP MUTU ONLINE
		case 10:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "smtpauth.online.net";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		// SMTP MUTU INFOMANIAK
		case 11:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "mail.infomaniak.ch";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "587";
		break;
		// SMTP ONE.COM
		case 12:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "mailout.one.com";
			document.global_config.elements.smtp_auth[0].checked = "checked";
			document.global_config.elements.smtp_auth[1].checked = "";
			document.global_config.elements['smtp_login'].disabled = true;
			document.global_config.elements['smtp_pass'].disabled = true;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "25";
		break;
		// SMTP SSL ONE.COM
		case 13:
			document.global_config.elements['smtp_host'].disabled = true;
			document.global_config.elements['smtp_host'].value = "send.one.com";
			document.global_config.elements.smtp_auth[0].checked = "";
			document.global_config.elements.smtp_auth[1].checked = "checked";
			document.global_config.elements['smtp_login'].disabled = false;
			document.global_config.elements['smtp_pass'].disabled = false;
			document.global_config.elements['smtp_port'].disabled = true;
			document.global_config.elements['smtp_port'].value = "465";
		break;
	}
}