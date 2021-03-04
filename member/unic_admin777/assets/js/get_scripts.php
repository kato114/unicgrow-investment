/*! jQuery v1.11.1 | (c) 2005, 2014 jQuery Foundation, Inc. | jquery.org/license */
!function(a,b){"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){var c=[],d=c.slice,e=c.concat,f=c.push,g=c.indexOf,h={},i=h.toString,j=h.hasOwnProperty,k={},l="1.11.1",m=function(a,b){return new m.fn.init(a,b)},n=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,o=/^-ms-/,p=/-([\da-z])/gi,q=function(a,b){return b.toUpperCase()};m.fn=m.prototype={jquery:l,constructor:m,selector:"",length:0,toArray:function(){return d.call(this)},get:function(a){return null!=a?0>a?this[a+this.length]:this[a]:d.call(this)},pushStack:function(a){var b=m.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a,b){return m.each(this,a,b)},map:function(a){return this.pushStack(m.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(d.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},end:function(){return this.prevObject||this.constructor(null)},push:f,sort:c.sort,splice:c.splice},m.extend=m.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||m.isFunction(g)||(g={}),h===i&&(g=this,h--);i>h;h++)if(null!=(e=arguments[h]))for(d in e)a=g[d],c=e[d],g!==c&&(j&&c&&(m.isPlainObject(c)||(b=m.isArray(c)))?(b?(b=!1,f=a&&m.isArray(a)?a:[]):f=a&&m.isPlainObject(a)?a:{},g[d]=m.extend(j,f,c)):void 0!==c&&(g[d]=c));return g},m.extend({expando:"jQuery"+(l+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===m.type(a)},isArray:Array.isArray||function(a){return"array"===m.type(a)},isWindow:function(a){return null!=a&&a==a.window},isNumeric:function(a){return!m.isArray(a)&&a-parseFloat(a)>=0},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},isPlainObject:function(a){var b;if(!a||"object"!==m.type(a)||a.nodeType||m.isWindow(a))return!1;try{if(a.constructor&&!j.call(a,"constructor")&&!j.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}if(k.ownLast)for(b in a)return j.call(a,b);for(b in a);return void 0===b||j.call(a,b)},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?h[i.call(a)]||"object":typeof a},globalEval:function(b){b&&m.trim(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(o,"ms-").replace(p,q)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b,c){var d,e=0,f=a.length,g=r(a);if(c){if(g){for(;f>e;e++)if(d=b.apply(a[e],c),d===!1)break}else for(e in a)if(d=b.apply(a[e],c),d===!1)break}else if(g){for(;f>e;e++)if(d=b.call(a[e],e,a[e]),d===!1)break}else for(e in a)if(d=b.call(a[e],e,a[e]),d===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(n,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(r(Object(a))?m.merge(c,"string"==typeof a?[a]:a):f.call(c,a)),c},inArray:function(a,b,c){var d;if(b){if(g)return g.call(b,a,c);for(d=b.length,c=c?0>c?Math.max(0,d+c):c:0;d>c;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,b){var c=+b.length,d=0,e=a.length;while(c>d)a[e++]=b[d++];if(c!==c)while(void 0!==b[d])a[e++]=b[d++];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;g>f;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,f=0,g=a.length,h=r(a),i=[];if(h)for(;g>f;f++)d=b(a[f],f,c),null!=d&&i.push(d);else for(f in a)d=b(a[f],f,c),null!=d&&i.push(d);return e.apply([],i)},guid:1,proxy:function(a,b){var c,e,f;return"string"==typeof b&&(f=a[b],b=a,a=f),m.isFunction(a)?(c=d.call(arguments,2),e=function(){return a.apply(b||this,c.concat(d.call(arguments)))},e.guid=a.guid=a.guid||m.guid++,e):void 0},now:function(){return+new Date},support:k}),m.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(a,b){h["[object "+b+"]"]=b.toLowerCase()});function r(a){var b=a.length,c=m.type(a);return"function"===c||m.isWindow(a)?!1:1===a.nodeType&&b?!0:"array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a}var s=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+-new Date,v=a.document,w=0,x=0,y=gb(),z=gb(),A=gb(),B=function(a,b){return a===b&&(l=!0),0},C="undefined",D=1<<31,E={}.hasOwnProperty,F=[],G=F.pop,H=F.push,I=F.push,J=F.slice,K=F.indexOf||function(a){for(var b=0,c=this.length;c>b;b++)if(this[b]===a)return b;return-1},L="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",M="[\\x20\\t\\r\\n\\f]",N="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",O=N.replace("w","w#"),P="\\["+M+"*("+N+")(?:"+M+"*([*^$|!~]?=)"+M+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+O+"))|)"+M+"*\\]",Q=":("+N+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+P+")*)|.*)\\)|)",R=new RegExp("^"+M+"+|((?:^|[^\\\\])(?:\\\\.)*)"+M+"+$","g"),S=new RegExp("^"+M+"*,"+M+"*"),T=new RegExp("^"+M+"*([>+~]|"+M+")"+M+"*"),U=new RegExp("="+M+"*([^\\]'\"]*?)"+M+"*\\]","g"),V=new RegExp(Q),W=new RegExp("^"+O+"$"),X={ID:new RegExp("^#("+N+")"),CLASS:new RegExp("^\\.("+N+")"),TAG:new RegExp("^("+N.replace("w","w*")+")"),ATTR:new RegExp("^"+P),PSEUDO:new RegExp("^"+Q),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+M+"*(even|odd|(([+-]|)(\\d*)n|)"+M+"*(?:([+-]|)"+M+"*(\\d+)|))"+M+"*\\)|)","i"),bool:new RegExp("^(?:"+L+")$","i"),needsContext:new RegExp("^"+M+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+M+"*((?:-\\d)?\\d*)"+M+"*\\)|)(?=[^-]|$)","i")},Y=/^(?:input|select|textarea|button)$/i,Z=/^h\d$/i,$=/^[^{]+\{\s*\[native \w/,_=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,ab=/[+~]/,bb=/'|\\/g,cb=new RegExp("\\\\([\\da-f]{1,6}"+M+"?|("+M+")|.)","ig"),db=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)};try{I.apply(F=J.call(v.childNodes),v.childNodes),F[v.childNodes.length].nodeType}catch(eb){I={apply:F.length?function(a,b){H.apply(a,J.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function fb(a,b,d,e){var f,h,j,k,l,o,r,s,w,x;if((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,d=d||[],!a||"string"!=typeof a)return d;if(1!==(k=b.nodeType)&&9!==k)return[];if(p&&!e){if(f=_.exec(a))if(j=f[1]){if(9===k){if(h=b.getElementById(j),!h||!h.parentNode)return d;if(h.id===j)return d.push(h),d}else if(b.ownerDocument&&(h=b.ownerDocument.getElementById(j))&&t(b,h)&&h.id===j)return d.push(h),d}else{if(f[2])return I.apply(d,b.getElementsByTagName(a)),d;if((j=f[3])&&c.getElementsByClassName&&b.getElementsByClassName)return I.apply(d,b.getElementsByClassName(j)),d}if(c.qsa&&(!q||!q.test(a))){if(s=r=u,w=b,x=9===k&&a,1===k&&"object"!==b.nodeName.toLowerCase()){o=g(a),(r=b.getAttribute("id"))?s=r.replace(bb,"\\$&"):b.setAttribute("id",s),s="[id='"+s+"'] ",l=o.length;while(l--)o[l]=s+qb(o[l]);w=ab.test(a)&&ob(b.parentNode)||b,x=o.join(",")}if(x)try{return I.apply(d,w.querySelectorAll(x)),d}catch(y){}finally{r||b.removeAttribute("id")}}}return i(a.replace(R,"$1"),b,d,e)}function gb(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function hb(a){return a[u]=!0,a}function ib(a){var b=n.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function jb(a,b){var c=a.split("|"),e=a.length;while(e--)d.attrHandle[c[e]]=b}function kb(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||D)-(~a.sourceIndex||D);if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function lb(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function mb(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function nb(a){return hb(function(b){return b=+b,hb(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function ob(a){return a&&typeof a.getElementsByTagName!==C&&a}c=fb.support={},f=fb.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},m=fb.setDocument=function(a){var b,e=a?a.ownerDocument||a:v,g=e.defaultView;return e!==n&&9===e.nodeType&&e.documentElement?(n=e,o=e.documentElement,p=!f(e),g&&g!==g.top&&(g.addEventListener?g.addEventListener("unload",function(){m()},!1):g.attachEvent&&g.attachEvent("onunload",function(){m()})),c.attributes=ib(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=ib(function(a){return a.appendChild(e.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=$.test(e.getElementsByClassName)&&ib(function(a){return a.innerHTML="<div class='a'></div><div class='a i'></div>",a.firstChild.className="i",2===a.getElementsByClassName("i").length}),c.getById=ib(function(a){return o.appendChild(a).id=u,!e.getElementsByName||!e.getElementsByName(u).length}),c.getById?(d.find.ID=function(a,b){if(typeof b.getElementById!==C&&p){var c=b.getElementById(a);return c&&c.parentNode?[c]:[]}},d.filter.ID=function(a){var b=a.replace(cb,db);return function(a){return a.getAttribute("id")===b}}):(delete d.find.ID,d.filter.ID=function(a){var b=a.replace(cb,db);return function(a){var c=typeof a.getAttributeNode!==C&&a.getAttributeNode("id");return c&&c.value===b}}),d.find.TAG=c.getElementsByTagName?function(a,b){return typeof b.getElementsByTagName!==C?b.getElementsByTagName(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){return typeof b.getElementsByClassName!==C&&p?b.getElementsByClassName(a):void 0},r=[],q=[],(c.qsa=$.test(e.querySelectorAll))&&(ib(function(a){a.innerHTML="<select msallowclip=''><option selected=''></option></select>",a.querySelectorAll("[msallowclip^='']").length&&q.push("[*^$]="+M+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+M+"*(?:value|"+L+")"),a.querySelectorAll(":checked").length||q.push(":checked")}),ib(function(a){var b=e.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+M+"*[*^$|!~]?="),a.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=$.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&ib(function(a){c.disconnectedMatch=s.call(a,"div"),s.call(a,"[s!='']:x"),r.push("!=",Q)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=$.test(o.compareDocumentPosition),t=b||$.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===e||a.ownerDocument===v&&t(v,a)?-1:b===e||b.ownerDocument===v&&t(v,b)?1:k?K.call(k,a)-K.call(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,f=a.parentNode,g=b.parentNode,h=[a],i=[b];if(!f||!g)return a===e?-1:b===e?1:f?-1:g?1:k?K.call(k,a)-K.call(k,b):0;if(f===g)return kb(a,b);c=a;while(c=c.parentNode)h.unshift(c);c=b;while(c=c.parentNode)i.unshift(c);while(h[d]===i[d])d++;return d?kb(h[d],i[d]):h[d]===v?-1:i[d]===v?1:0},e):n},fb.matches=function(a,b){return fb(a,null,null,b)},fb.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(U,"='$1']"),!(!c.matchesSelector||!p||r&&r.test(b)||q&&q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return fb(b,n,null,[a]).length>0},fb.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},fb.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&E.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},fb.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},fb.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=fb.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=fb.selectors={cacheLength:50,createPseudo:hb,match:X,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(cb,db),a[3]=(a[3]||a[4]||a[5]||"").replace(cb,db),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||fb.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&fb.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return X.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&V.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(cb,db).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+M+")"+a+"("+M+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||typeof a.getAttribute!==C&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=fb.attr(d,a);return null==e?"!="===b:b?(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e+" ").indexOf(c)>-1:"|="===b?e===c||e.slice(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h;if(q){if(f){while(p){l=b;while(l=l[p])if(h?l.nodeName.toLowerCase()===r:1===l.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){k=q[u]||(q[u]={}),j=k[a]||[],n=j[0]===w&&j[1],m=j[0]===w&&j[2],l=n&&q.childNodes[n];while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if(1===l.nodeType&&++m&&l===b){k[a]=[w,n,m];break}}else if(s&&(j=(b[u]||(b[u]={}))[a])&&j[0]===w)m=j[1];else while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if((h?l.nodeName.toLowerCase()===r:1===l.nodeType)&&++m&&(s&&((l[u]||(l[u]={}))[a]=[w,m]),l===b))break;return m-=e,m===d||m%d===0&&m/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||fb.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?hb(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=K.call(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:hb(function(a){var b=[],c=[],d=h(a.replace(R,"$1"));return d[u]?hb(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),!c.pop()}}),has:hb(function(a){return function(b){return fb(a,b).length>0}}),contains:hb(function(a){return function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:hb(function(a){return W.test(a||"")||fb.error("unsupported lang: "+a),a=a.replace(cb,db).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return Z.test(a.nodeName)},input:function(a){return Y.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:nb(function(){return[0]}),last:nb(function(a,b){return[b-1]}),eq:nb(function(a,b,c){return[0>c?c+b:c]}),even:nb(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:nb(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:nb(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:nb(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=lb(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=mb(b);function pb(){}pb.prototype=d.filters=d.pseudos,d.setFilters=new pb,g=fb.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){(!c||(e=S.exec(h)))&&(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=T.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(R," ")}),h=h.slice(c.length));for(g in d.filter)!(e=X[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?fb.error(a):z(a,i).slice(0)};function qb(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function rb(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=x++;return b.first?function(b,c,f){while(b=b[d])if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j=[w,f];if(g){while(b=b[d])if((1===b.nodeType||e)&&a(b,c,g))return!0}else while(b=b[d])if(1===b.nodeType||e){if(i=b[u]||(b[u]={}),(h=i[d])&&h[0]===w&&h[1]===f)return j[2]=h[2];if(i[d]=j,j[2]=a(b,c,g))return!0}}}function sb(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function tb(a,b,c){for(var d=0,e=b.length;e>d;d++)fb(a,b[d],c);return c}function ub(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(!c||c(f,d,e))&&(g.push(f),j&&b.push(h));return g}function vb(a,b,c,d,e,f){return d&&!d[u]&&(d=vb(d)),e&&!e[u]&&(e=vb(e,f)),hb(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||tb(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:ub(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=ub(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?K.call(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=ub(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):I.apply(g,r)})}function wb(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=rb(function(a){return a===b},h,!0),l=rb(function(a){return K.call(b,a)>-1},h,!0),m=[function(a,c,d){return!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d))}];f>i;i++)if(c=d.relative[a[i].type])m=[rb(sb(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;f>e;e++)if(d.relative[a[e].type])break;return vb(i>1&&sb(m),i>1&&qb(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(R,"$1"),c,e>i&&wb(a.slice(i,e)),f>e&&wb(a=a.slice(e)),f>e&&qb(a))}m.push(c)}return sb(m)}function xb(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,m,o,p=0,q="0",r=f&&[],s=[],t=j,u=f||e&&d.find.TAG("*",k),v=w+=null==t?1:Math.random()||.1,x=u.length;for(k&&(j=g!==n&&g);q!==x&&null!=(l=u[q]);q++){if(e&&l){m=0;while(o=a[m++])if(o(l,g,h)){i.push(l);break}k&&(w=v)}c&&((l=!o&&l)&&p--,f&&r.push(l))}if(p+=q,c&&q!==p){m=0;while(o=b[m++])o(r,s,g,h);if(f){if(p>0)while(q--)r[q]||s[q]||(s[q]=G.call(i));s=ub(s)}I.apply(i,s),k&&!f&&s.length>0&&p+b.length>1&&fb.uniqueSort(i)}return k&&(w=v,j=t),r};return c?hb(f):f}return h=fb.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=wb(b[c]),f[u]?d.push(f):e.push(f);f=A(a,xb(e,d)),f.selector=a}return f},i=fb.select=function(a,b,e,f){var i,j,k,l,m,n="function"==typeof a&&a,o=!f&&g(a=n.selector||a);if(e=e||[],1===o.length){if(j=o[0]=o[0].slice(0),j.length>2&&"ID"===(k=j[0]).type&&c.getById&&9===b.nodeType&&p&&d.relative[j[1].type]){if(b=(d.find.ID(k.matches[0].replace(cb,db),b)||[])[0],!b)return e;n&&(b=b.parentNode),a=a.slice(j.shift().value.length)}i=X.needsContext.test(a)?0:j.length;while(i--){if(k=j[i],d.relative[l=k.type])break;if((m=d.find[l])&&(f=m(k.matches[0].replace(cb,db),ab.test(j[0].type)&&ob(b.parentNode)||b))){if(j.splice(i,1),a=f.length&&qb(j),!a)return I.apply(e,f),e;break}}}return(n||h(a,o))(f,b,!p,e,ab.test(a)&&ob(b.parentNode)||b),e},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=ib(function(a){return 1&a.compareDocumentPosition(n.createElement("div"))}),ib(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||jb("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&ib(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||jb("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),ib(function(a){return null==a.getAttribute("disabled")})||jb(L,function(a,b,c){var d;return c?void 0:a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),fb}(a);m.find=s,m.expr=s.selectors,m.expr[":"]=m.expr.pseudos,m.unique=s.uniqueSort,m.text=s.getText,m.isXMLDoc=s.isXML,m.contains=s.contains;var t=m.expr.match.needsContext,u=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,v=/^.[^:#\[\.,]*$/;function w(a,b,c){if(m.isFunction(b))return m.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return m.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(v.test(b))return m.filter(b,a,c);b=m.filter(b,a)}return m.grep(a,function(a){return m.inArray(a,b)>=0!==c})}m.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?m.find.matchesSelector(d,a)?[d]:[]:m.find.matches(a,m.grep(b,function(a){return 1===a.nodeType}))},m.fn.extend({find:function(a){var b,c=[],d=this,e=d.length;if("string"!=typeof a)return this.pushStack(m(a).filter(function(){for(b=0;e>b;b++)if(m.contains(d[b],this))return!0}));for(b=0;e>b;b++)m.find(a,d[b],c);return c=this.pushStack(e>1?m.unique(c):c),c.selector=this.selector?this.selector+" "+a:a,c},filter:function(a){return this.pushStack(w(this,a||[],!1))},not:function(a){return this.pushStack(w(this,a||[],!0))},is:function(a){return!!w(this,"string"==typeof a&&t.test(a)?m(a):a||[],!1).length}});var x,y=a.document,z=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,A=m.fn.init=function(a,b){var c,d;if(!a)return this;if("string"==typeof a){if(c="<"===a.charAt(0)&&">"===a.charAt(a.length-1)&&a.length>=3?[null,a,null]:z.exec(a),!c||!c[1]&&b)return!b||b.jquery?(b||x).find(a):this.constructor(b).find(a);if(c[1]){if(b=b instanceof m?b[0]:b,m.merge(this,m.parseHTML(c[1],b&&b.nodeType?b.ownerDocument||b:y,!0)),u.test(c[1])&&m.isPlainObject(b))for(c in b)m.isFunction(this[c])?this[c](b[c]):this.attr(c,b[c]);return this}if(d=y.getElementById(c[2]),d&&d.parentNode){if(d.id!==c[2])return x.find(a);this.length=1,this[0]=d}return this.context=y,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):m.isFunction(a)?"undefined"!=typeof x.ready?x.ready(a):a(m):(void 0!==a.selector&&(this.selector=a.selector,this.context=a.context),m.makeArray(a,this))};A.prototype=m.fn,x=m(y);var B=/^(?:parents|prev(?:Until|All))/,C={children:!0,contents:!0,next:!0,prev:!0};m.extend({dir:function(a,b,c){var d=[],e=a[b];while(e&&9!==e.nodeType&&(void 0===c||1!==e.nodeType||!m(e).is(c)))1===e.nodeType&&d.push(e),e=e[b];return d},sibling:function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c}}),m.fn.extend({has:function(a){var b,c=m(a,this),d=c.length;return this.filter(function(){for(b=0;d>b;b++)if(m.contains(this,c[b]))return!0})},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=t.test(a)||"string"!=typeof a?m(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&m.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?m.unique(f):f)},index:function(a){return a?"string"==typeof a?m.inArray(this[0],m(a)):m.inArray(a.jquery?a[0]:a,this):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(m.unique(m.merge(this.get(),m(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function D(a,b){do a=a[b];while(a&&1!==a.nodeType);return a}m.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return m.dir(a,"parentNode")},parentsUntil:function(a,b,c){return m.dir(a,"parentNode",c)},next:function(a){return D(a,"nextSibling")},prev:function(a){return D(a,"previousSibling")},nextAll:function(a){return m.dir(a,"nextSibling")},prevAll:function(a){return m.dir(a,"previousSibling")},nextUntil:function(a,b,c){return m.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return m.dir(a,"previousSibling",c)},siblings:function(a){return m.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return m.sibling(a.firstChild)},contents:function(a){return m.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:m.merge([],a.childNodes)}},function(a,b){m.fn[a]=function(c,d){var e=m.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=m.filter(d,e)),this.length>1&&(C[a]||(e=m.unique(e)),B.test(a)&&(e=e.reverse())),this.pushStack(e)}});var E=/\S+/g,F={};function G(a){var b=F[a]={};return m.each(a.match(E)||[],function(a,c){b[c]=!0}),b}m.Callbacks=function(a){a="string"==typeof a?F[a]||G(a):m.extend({},a);var b,c,d,e,f,g,h=[],i=!a.once&&[],j=function(l){for(c=a.memory&&l,d=!0,f=g||0,g=0,e=h.length,b=!0;h&&e>f;f++)if(h[f].apply(l[0],l[1])===!1&&a.stopOnFalse){c=!1;break}b=!1,h&&(i?i.length&&j(i.shift()):c?h=[]:k.disable())},k={add:function(){if(h){var d=h.length;!function f(b){m.each(b,function(b,c){var d=m.type(c);"function"===d?a.unique&&k.has(c)||h.push(c):c&&c.length&&"string"!==d&&f(c)})}(arguments),b?e=h.length:c&&(g=d,j(c))}return this},remove:function(){return h&&m.each(arguments,function(a,c){var d;while((d=m.inArray(c,h,d))>-1)h.splice(d,1),b&&(e>=d&&e--,f>=d&&f--)}),this},has:function(a){return a?m.inArray(a,h)>-1:!(!h||!h.length)},empty:function(){return h=[],e=0,this},disable:function(){return h=i=c=void 0,this},disabled:function(){return!h},lock:function(){return i=void 0,c||k.disable(),this},locked:function(){return!i},fireWith:function(a,c){return!h||d&&!i||(c=c||[],c=[a,c.slice?c.slice():c],b?i.push(c):j(c)),this},fire:function(){return k.fireWith(this,arguments),this},fired:function(){return!!d}};return k},m.extend({Deferred:function(a){var b=[["resolve","done",m.Callbacks("once memory"),"resolved"],["reject","fail",m.Callbacks("once memory"),"rejected"],["notify","progress",m.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return m.Deferred(function(c){m.each(b,function(b,f){var g=m.isFunction(a[b])&&a[b];e[f[1]](function(){var a=g&&g.apply(this,arguments);a&&m.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f[0]+"With"](this===d?c.promise():this,g?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?m.extend(a,d):d}},e={};return d.pipe=d.then,m.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=d.call(arguments),e=c.length,f=1!==e||a&&m.isFunction(a.promise)?e:0,g=1===f?a:m.Deferred(),h=function(a,b,c){return function(e){b[a]=this,c[a]=arguments.length>1?d.call(arguments):e,c===i?g.notifyWith(b,c):--f||g.resolveWith(b,c)}},i,j,k;if(e>1)for(i=new Array(e),j=new Array(e),k=new Array(e);e>b;b++)c[b]&&m.isFunction(c[b].promise)?c[b].promise().done(h(b,k,c)).fail(g.reject).progress(h(b,j,i)):--f;return f||g.resolveWith(k,c),g.promise()}});var H;m.fn.ready=function(a){return m.ready.promise().done(a),this},m.extend({isReady:!1,readyWait:1,holdReady:function(a){a?m.readyWait++:m.ready(!0)},ready:function(a){if(a===!0?!--m.readyWait:!m.isReady){if(!y.body)return setTimeout(m.ready);m.isReady=!0,a!==!0&&--m.readyWait>0||(H.resolveWith(y,[m]),m.fn.triggerHandler&&(m(y).triggerHandler("ready"),m(y).off("ready")))}}});function I(){y.addEventListener?(y.removeEventListener("DOMContentLoaded",J,!1),a.removeEventListener("load",J,!1)):(y.detachEvent("onreadystatechange",J),a.detachEvent("onload",J))}function J(){(y.addEventListener||"load"===event.type||"complete"===y.readyState)&&(I(),m.ready())}m.ready.promise=function(b){if(!H)if(H=m.Deferred(),"complete"===y.readyState)setTimeout(m.ready);else if(y.addEventListener)y.addEventListener("DOMContentLoaded",J,!1),a.addEventListener("load",J,!1);else{y.attachEvent("onreadystatechange",J),a.attachEvent("onload",J);var c=!1;try{c=null==a.frameElement&&y.documentElement}catch(d){}c&&c.doScroll&&!function e(){if(!m.isReady){try{c.doScroll("left")}catch(a){return setTimeout(e,50)}I(),m.ready()}}()}return H.promise(b)};var K="undefined",L;for(L in m(k))break;k.ownLast="0"!==L,k.inlineBlockNeedsLayout=!1,m(function(){var a,b,c,d;c=y.getElementsByTagName("body")[0],c&&c.style&&(b=y.createElement("div"),d=y.createElement("div"),d.style.cssText="position:absolute;border:0;width:0;height:0;top:0;left:-9999px",c.appendChild(d).appendChild(b),typeof b.style.zoom!==K&&(b.style.cssText="display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1",k.inlineBlockNeedsLayout=a=3===b.offsetWidth,a&&(c.style.zoom=1)),c.removeChild(d))}),function(){var a=y.createElement("div");if(null==k.deleteExpando){k.deleteExpando=!0;try{delete a.test}catch(b){k.deleteExpando=!1}}a=null}(),m.acceptData=function(a){var b=m.noData[(a.nodeName+" ").toLowerCase()],c=+a.nodeType||1;return 1!==c&&9!==c?!1:!b||b!==!0&&a.getAttribute("classid")===b};var M=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,N=/([A-Z])/g;function O(a,b,c){if(void 0===c&&1===a.nodeType){var d="data-"+b.replace(N,"-$1").toLowerCase();if(c=a.getAttribute(d),"string"==typeof c){try{c="true"===c?!0:"false"===c?!1:"null"===c?null:+c+""===c?+c:M.test(c)?m.parseJSON(c):c}catch(e){}m.data(a,b,c)}else c=void 0}return c}function P(a){var b;for(b in a)if(("data"!==b||!m.isEmptyObject(a[b]))&&"toJSON"!==b)return!1;return!0}function Q(a,b,d,e){if(m.acceptData(a)){var f,g,h=m.expando,i=a.nodeType,j=i?m.cache:a,k=i?a[h]:a[h]&&h;
if(k&&j[k]&&(e||j[k].data)||void 0!==d||"string"!=typeof b)return k||(k=i?a[h]=c.pop()||m.guid++:h),j[k]||(j[k]=i?{}:{toJSON:m.noop}),("object"==typeof b||"function"==typeof b)&&(e?j[k]=m.extend(j[k],b):j[k].data=m.extend(j[k].data,b)),g=j[k],e||(g.data||(g.data={}),g=g.data),void 0!==d&&(g[m.camelCase(b)]=d),"string"==typeof b?(f=g[b],null==f&&(f=g[m.camelCase(b)])):f=g,f}}function R(a,b,c){if(m.acceptData(a)){var d,e,f=a.nodeType,g=f?m.cache:a,h=f?a[m.expando]:m.expando;if(g[h]){if(b&&(d=c?g[h]:g[h].data)){m.isArray(b)?b=b.concat(m.map(b,m.camelCase)):b in d?b=[b]:(b=m.camelCase(b),b=b in d?[b]:b.split(" ")),e=b.length;while(e--)delete d[b[e]];if(c?!P(d):!m.isEmptyObject(d))return}(c||(delete g[h].data,P(g[h])))&&(f?m.cleanData([a],!0):k.deleteExpando||g!=g.window?delete g[h]:g[h]=null)}}}m.extend({cache:{},noData:{"applet ":!0,"embed ":!0,"object ":"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},hasData:function(a){return a=a.nodeType?m.cache[a[m.expando]]:a[m.expando],!!a&&!P(a)},data:function(a,b,c){return Q(a,b,c)},removeData:function(a,b){return R(a,b)},_data:function(a,b,c){return Q(a,b,c,!0)},_removeData:function(a,b){return R(a,b,!0)}}),m.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=m.data(f),1===f.nodeType&&!m._data(f,"parsedAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=m.camelCase(d.slice(5)),O(f,d,e[d])));m._data(f,"parsedAttrs",!0)}return e}return"object"==typeof a?this.each(function(){m.data(this,a)}):arguments.length>1?this.each(function(){m.data(this,a,b)}):f?O(f,a,m.data(f,a)):void 0},removeData:function(a){return this.each(function(){m.removeData(this,a)})}}),m.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=m._data(a,b),c&&(!d||m.isArray(c)?d=m._data(a,b,m.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=m.queue(a,b),d=c.length,e=c.shift(),f=m._queueHooks(a,b),g=function(){m.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return m._data(a,c)||m._data(a,c,{empty:m.Callbacks("once memory").add(function(){m._removeData(a,b+"queue"),m._removeData(a,c)})})}}),m.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?m.queue(this[0],a):void 0===b?this:this.each(function(){var c=m.queue(this,a,b);m._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&m.dequeue(this,a)})},dequeue:function(a){return this.each(function(){m.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=m.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=m._data(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var S=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,T=["Top","Right","Bottom","Left"],U=function(a,b){return a=b||a,"none"===m.css(a,"display")||!m.contains(a.ownerDocument,a)},V=m.access=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===m.type(c)){e=!0;for(h in c)m.access(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,m.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(m(a),c)})),b))for(;i>h;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f},W=/^(?:checkbox|radio)$/i;!function(){var a=y.createElement("input"),b=y.createElement("div"),c=y.createDocumentFragment();if(b.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",k.leadingWhitespace=3===b.firstChild.nodeType,k.tbody=!b.getElementsByTagName("tbody").length,k.htmlSerialize=!!b.getElementsByTagName("link").length,k.html5Clone="<:nav></:nav>"!==y.createElement("nav").cloneNode(!0).outerHTML,a.type="checkbox",a.checked=!0,c.appendChild(a),k.appendChecked=a.checked,b.innerHTML="<textarea>x</textarea>",k.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue,c.appendChild(b),b.innerHTML="<input type='radio' checked='checked' name='t'/>",k.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,k.noCloneEvent=!0,b.attachEvent&&(b.attachEvent("onclick",function(){k.noCloneEvent=!1}),b.cloneNode(!0).click()),null==k.deleteExpando){k.deleteExpando=!0;try{delete b.test}catch(d){k.deleteExpando=!1}}}(),function(){var b,c,d=y.createElement("div");for(b in{submit:!0,change:!0,focusin:!0})c="on"+b,(k[b+"Bubbles"]=c in a)||(d.setAttribute(c,"t"),k[b+"Bubbles"]=d.attributes[c].expando===!1);d=null}();var X=/^(?:input|select|textarea)$/i,Y=/^key/,Z=/^(?:mouse|pointer|contextmenu)|click/,$=/^(?:focusinfocus|focusoutblur)$/,_=/^([^.]*)(?:\.(.+)|)$/;function ab(){return!0}function bb(){return!1}function cb(){try{return y.activeElement}catch(a){}}m.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,n,o,p,q,r=m._data(a);if(r){c.handler&&(i=c,c=i.handler,e=i.selector),c.guid||(c.guid=m.guid++),(g=r.events)||(g=r.events={}),(k=r.handle)||(k=r.handle=function(a){return typeof m===K||a&&m.event.triggered===a.type?void 0:m.event.dispatch.apply(k.elem,arguments)},k.elem=a),b=(b||"").match(E)||[""],h=b.length;while(h--)f=_.exec(b[h])||[],o=q=f[1],p=(f[2]||"").split(".").sort(),o&&(j=m.event.special[o]||{},o=(e?j.delegateType:j.bindType)||o,j=m.event.special[o]||{},l=m.extend({type:o,origType:q,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&m.expr.match.needsContext.test(e),namespace:p.join(".")},i),(n=g[o])||(n=g[o]=[],n.delegateCount=0,j.setup&&j.setup.call(a,d,p,k)!==!1||(a.addEventListener?a.addEventListener(o,k,!1):a.attachEvent&&a.attachEvent("on"+o,k))),j.add&&(j.add.call(a,l),l.handler.guid||(l.handler.guid=c.guid)),e?n.splice(n.delegateCount++,0,l):n.push(l),m.event.global[o]=!0);a=null}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,n,o,p,q,r=m.hasData(a)&&m._data(a);if(r&&(k=r.events)){b=(b||"").match(E)||[""],j=b.length;while(j--)if(h=_.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o){l=m.event.special[o]||{},o=(d?l.delegateType:l.bindType)||o,n=k[o]||[],h=h[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),i=f=n.length;while(f--)g=n[f],!e&&q!==g.origType||c&&c.guid!==g.guid||h&&!h.test(g.namespace)||d&&d!==g.selector&&("**"!==d||!g.selector)||(n.splice(f,1),g.selector&&n.delegateCount--,l.remove&&l.remove.call(a,g));i&&!n.length&&(l.teardown&&l.teardown.call(a,p,r.handle)!==!1||m.removeEvent(a,o,r.handle),delete k[o])}else for(o in k)m.event.remove(a,o+b[j],c,d,!0);m.isEmptyObject(k)&&(delete r.handle,m._removeData(a,"events"))}},trigger:function(b,c,d,e){var f,g,h,i,k,l,n,o=[d||y],p=j.call(b,"type")?b.type:b,q=j.call(b,"namespace")?b.namespace.split("."):[];if(h=l=d=d||y,3!==d.nodeType&&8!==d.nodeType&&!$.test(p+m.event.triggered)&&(p.indexOf(".")>=0&&(q=p.split("."),p=q.shift(),q.sort()),g=p.indexOf(":")<0&&"on"+p,b=b[m.expando]?b:new m.Event(p,"object"==typeof b&&b),b.isTrigger=e?2:3,b.namespace=q.join("."),b.namespace_re=b.namespace?new RegExp("(^|\\.)"+q.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=d),c=null==c?[b]:m.makeArray(c,[b]),k=m.event.special[p]||{},e||!k.trigger||k.trigger.apply(d,c)!==!1)){if(!e&&!k.noBubble&&!m.isWindow(d)){for(i=k.delegateType||p,$.test(i+p)||(h=h.parentNode);h;h=h.parentNode)o.push(h),l=h;l===(d.ownerDocument||y)&&o.push(l.defaultView||l.parentWindow||a)}n=0;while((h=o[n++])&&!b.isPropagationStopped())b.type=n>1?i:k.bindType||p,f=(m._data(h,"events")||{})[b.type]&&m._data(h,"handle"),f&&f.apply(h,c),f=g&&h[g],f&&f.apply&&m.acceptData(h)&&(b.result=f.apply(h,c),b.result===!1&&b.preventDefault());if(b.type=p,!e&&!b.isDefaultPrevented()&&(!k._default||k._default.apply(o.pop(),c)===!1)&&m.acceptData(d)&&g&&d[p]&&!m.isWindow(d)){l=d[g],l&&(d[g]=null),m.event.triggered=p;try{d[p]()}catch(r){}m.event.triggered=void 0,l&&(d[g]=l)}return b.result}},dispatch:function(a){a=m.event.fix(a);var b,c,e,f,g,h=[],i=d.call(arguments),j=(m._data(this,"events")||{})[a.type]||[],k=m.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){h=m.event.handlers.call(this,a,j),b=0;while((f=h[b++])&&!a.isPropagationStopped()){a.currentTarget=f.elem,g=0;while((e=f.handlers[g++])&&!a.isImmediatePropagationStopped())(!a.namespace_re||a.namespace_re.test(e.namespace))&&(a.handleObj=e,a.data=e.data,c=((m.event.special[e.origType]||{}).handle||e.handler).apply(f.elem,i),void 0!==c&&(a.result=c)===!1&&(a.preventDefault(),a.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,b){var c,d,e,f,g=[],h=b.delegateCount,i=a.target;if(h&&i.nodeType&&(!a.button||"click"!==a.type))for(;i!=this;i=i.parentNode||this)if(1===i.nodeType&&(i.disabled!==!0||"click"!==a.type)){for(e=[],f=0;h>f;f++)d=b[f],c=d.selector+" ",void 0===e[c]&&(e[c]=d.needsContext?m(c,this).index(i)>=0:m.find(c,this,null,[i]).length),e[c]&&e.push(d);e.length&&g.push({elem:i,handlers:e})}return h<b.length&&g.push({elem:this,handlers:b.slice(h)}),g},fix:function(a){if(a[m.expando])return a;var b,c,d,e=a.type,f=a,g=this.fixHooks[e];g||(this.fixHooks[e]=g=Z.test(e)?this.mouseHooks:Y.test(e)?this.keyHooks:{}),d=g.props?this.props.concat(g.props):this.props,a=new m.Event(f),b=d.length;while(b--)c=d[b],a[c]=f[c];return a.target||(a.target=f.srcElement||y),3===a.target.nodeType&&(a.target=a.target.parentNode),a.metaKey=!!a.metaKey,g.filter?g.filter(a,f):a},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,b){var c,d,e,f=b.button,g=b.fromElement;return null==a.pageX&&null!=b.clientX&&(d=a.target.ownerDocument||y,e=d.documentElement,c=d.body,a.pageX=b.clientX+(e&&e.scrollLeft||c&&c.scrollLeft||0)-(e&&e.clientLeft||c&&c.clientLeft||0),a.pageY=b.clientY+(e&&e.scrollTop||c&&c.scrollTop||0)-(e&&e.clientTop||c&&c.clientTop||0)),!a.relatedTarget&&g&&(a.relatedTarget=g===a.target?b.toElement:g),a.which||void 0===f||(a.which=1&f?1:2&f?3:4&f?2:0),a}},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==cb()&&this.focus)try{return this.focus(),!1}catch(a){}},delegateType:"focusin"},blur:{trigger:function(){return this===cb()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return m.nodeName(this,"input")&&"checkbox"===this.type&&this.click?(this.click(),!1):void 0},_default:function(a){return m.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}},simulate:function(a,b,c,d){var e=m.extend(new m.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?m.event.trigger(e,null,b):m.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},m.removeEvent=y.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){var d="on"+b;a.detachEvent&&(typeof a[d]===K&&(a[d]=null),a.detachEvent(d,c))},m.Event=function(a,b){return this instanceof m.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?ab:bb):this.type=a,b&&m.extend(this,b),this.timeStamp=a&&a.timeStamp||m.now(),void(this[m.expando]=!0)):new m.Event(a,b)},m.Event.prototype={isDefaultPrevented:bb,isPropagationStopped:bb,isImmediatePropagationStopped:bb,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=ab,a&&(a.preventDefault?a.preventDefault():a.returnValue=!1)},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=ab,a&&(a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0)},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=ab,a&&a.stopImmediatePropagation&&a.stopImmediatePropagation(),this.stopPropagation()}},m.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){m.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return(!e||e!==d&&!m.contains(d,e))&&(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),k.submitBubbles||(m.event.special.submit={setup:function(){return m.nodeName(this,"form")?!1:void m.event.add(this,"click._submit keypress._submit",function(a){var b=a.target,c=m.nodeName(b,"input")||m.nodeName(b,"button")?b.form:void 0;c&&!m._data(c,"submitBubbles")&&(m.event.add(c,"submit._submit",function(a){a._submit_bubble=!0}),m._data(c,"submitBubbles",!0))})},postDispatch:function(a){a._submit_bubble&&(delete a._submit_bubble,this.parentNode&&!a.isTrigger&&m.event.simulate("submit",this.parentNode,a,!0))},teardown:function(){return m.nodeName(this,"form")?!1:void m.event.remove(this,"._submit")}}),k.changeBubbles||(m.event.special.change={setup:function(){return X.test(this.nodeName)?(("checkbox"===this.type||"radio"===this.type)&&(m.event.add(this,"propertychange._change",function(a){"checked"===a.originalEvent.propertyName&&(this._just_changed=!0)}),m.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1),m.event.simulate("change",this,a,!0)})),!1):void m.event.add(this,"beforeactivate._change",function(a){var b=a.target;X.test(b.nodeName)&&!m._data(b,"changeBubbles")&&(m.event.add(b,"change._change",function(a){!this.parentNode||a.isSimulated||a.isTrigger||m.event.simulate("change",this.parentNode,a,!0)}),m._data(b,"changeBubbles",!0))})},handle:function(a){var b=a.target;return this!==b||a.isSimulated||a.isTrigger||"radio"!==b.type&&"checkbox"!==b.type?a.handleObj.handler.apply(this,arguments):void 0},teardown:function(){return m.event.remove(this,"._change"),!X.test(this.nodeName)}}),k.focusinBubbles||m.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){m.event.simulate(b,a.target,m.event.fix(a),!0)};m.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=m._data(d,b);e||d.addEventListener(a,c,!0),m._data(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=m._data(d,b)-1;e?m._data(d,b,e):(d.removeEventListener(a,c,!0),m._removeData(d,b))}}}),m.fn.extend({on:function(a,b,c,d,e){var f,g;if("object"==typeof a){"string"!=typeof b&&(c=c||b,b=void 0);for(f in a)this.on(f,b,c,a[f],e);return this}if(null==c&&null==d?(d=b,c=b=void 0):null==d&&("string"==typeof b?(d=c,c=void 0):(d=c,c=b,b=void 0)),d===!1)d=bb;else if(!d)return this;return 1===e&&(g=d,d=function(a){return m().off(a),g.apply(this,arguments)},d.guid=g.guid||(g.guid=m.guid++)),this.each(function(){m.event.add(this,a,d,c,b)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,m(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return(b===!1||"function"==typeof b)&&(c=b,b=void 0),c===!1&&(c=bb),this.each(function(){m.event.remove(this,a,c,b)})},trigger:function(a,b){return this.each(function(){m.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?m.event.trigger(a,b,c,!0):void 0}});function db(a){var b=eb.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}var eb="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",fb=/ jQuery\d+="(?:null|\d+)"/g,gb=new RegExp("<(?:"+eb+")[\\s/>]","i"),hb=/^\s+/,ib=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,jb=/<([\w:]+)/,kb=/<tbody/i,lb=/<|&#?\w+;/,mb=/<(?:script|style|link)/i,nb=/checked\s*(?:[^=]|=\s*.checked.)/i,ob=/^$|\/(?:java|ecma)script/i,pb=/^true\/(.*)/,qb=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,rb={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],area:[1,"<map>","</map>"],param:[1,"<object>","</object>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:k.htmlSerialize?[0,"",""]:[1,"X<div>","</div>"]},sb=db(y),tb=sb.appendChild(y.createElement("div"));rb.optgroup=rb.option,rb.tbody=rb.tfoot=rb.colgroup=rb.caption=rb.thead,rb.th=rb.td;function ub(a,b){var c,d,e=0,f=typeof a.getElementsByTagName!==K?a.getElementsByTagName(b||"*"):typeof a.querySelectorAll!==K?a.querySelectorAll(b||"*"):void 0;if(!f)for(f=[],c=a.childNodes||a;null!=(d=c[e]);e++)!b||m.nodeName(d,b)?f.push(d):m.merge(f,ub(d,b));return void 0===b||b&&m.nodeName(a,b)?m.merge([a],f):f}function vb(a){W.test(a.type)&&(a.defaultChecked=a.checked)}function wb(a,b){return m.nodeName(a,"table")&&m.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function xb(a){return a.type=(null!==m.find.attr(a,"type"))+"/"+a.type,a}function yb(a){var b=pb.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function zb(a,b){for(var c,d=0;null!=(c=a[d]);d++)m._data(c,"globalEval",!b||m._data(b[d],"globalEval"))}function Ab(a,b){if(1===b.nodeType&&m.hasData(a)){var c,d,e,f=m._data(a),g=m._data(b,f),h=f.events;if(h){delete g.handle,g.events={};for(c in h)for(d=0,e=h[c].length;e>d;d++)m.event.add(b,c,h[c][d])}g.data&&(g.data=m.extend({},g.data))}}function Bb(a,b){var c,d,e;if(1===b.nodeType){if(c=b.nodeName.toLowerCase(),!k.noCloneEvent&&b[m.expando]){e=m._data(b);for(d in e.events)m.removeEvent(b,d,e.handle);b.removeAttribute(m.expando)}"script"===c&&b.text!==a.text?(xb(b).text=a.text,yb(b)):"object"===c?(b.parentNode&&(b.outerHTML=a.outerHTML),k.html5Clone&&a.innerHTML&&!m.trim(b.innerHTML)&&(b.innerHTML=a.innerHTML)):"input"===c&&W.test(a.type)?(b.defaultChecked=b.checked=a.checked,b.value!==a.value&&(b.value=a.value)):"option"===c?b.defaultSelected=b.selected=a.defaultSelected:("input"===c||"textarea"===c)&&(b.defaultValue=a.defaultValue)}}m.extend({clone:function(a,b,c){var d,e,f,g,h,i=m.contains(a.ownerDocument,a);if(k.html5Clone||m.isXMLDoc(a)||!gb.test("<"+a.nodeName+">")?f=a.cloneNode(!0):(tb.innerHTML=a.outerHTML,tb.removeChild(f=tb.firstChild)),!(k.noCloneEvent&&k.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||m.isXMLDoc(a)))for(d=ub(f),h=ub(a),g=0;null!=(e=h[g]);++g)d[g]&&Bb(e,d[g]);if(b)if(c)for(h=h||ub(a),d=d||ub(f),g=0;null!=(e=h[g]);g++)Ab(e,d[g]);else Ab(a,f);return d=ub(f,"script"),d.length>0&&zb(d,!i&&ub(a,"script")),d=h=e=null,f},buildFragment:function(a,b,c,d){for(var e,f,g,h,i,j,l,n=a.length,o=db(b),p=[],q=0;n>q;q++)if(f=a[q],f||0===f)if("object"===m.type(f))m.merge(p,f.nodeType?[f]:f);else if(lb.test(f)){h=h||o.appendChild(b.createElement("div")),i=(jb.exec(f)||["",""])[1].toLowerCase(),l=rb[i]||rb._default,h.innerHTML=l[1]+f.replace(ib,"<$1></$2>")+l[2],e=l[0];while(e--)h=h.lastChild;if(!k.leadingWhitespace&&hb.test(f)&&p.push(b.createTextNode(hb.exec(f)[0])),!k.tbody){f="table"!==i||kb.test(f)?"<table>"!==l[1]||kb.test(f)?0:h:h.firstChild,e=f&&f.childNodes.length;while(e--)m.nodeName(j=f.childNodes[e],"tbody")&&!j.childNodes.length&&f.removeChild(j)}m.merge(p,h.childNodes),h.textContent="";while(h.firstChild)h.removeChild(h.firstChild);h=o.lastChild}else p.push(b.createTextNode(f));h&&o.removeChild(h),k.appendChecked||m.grep(ub(p,"input"),vb),q=0;while(f=p[q++])if((!d||-1===m.inArray(f,d))&&(g=m.contains(f.ownerDocument,f),h=ub(o.appendChild(f),"script"),g&&zb(h),c)){e=0;while(f=h[e++])ob.test(f.type||"")&&c.push(f)}return h=null,o},cleanData:function(a,b){for(var d,e,f,g,h=0,i=m.expando,j=m.cache,l=k.deleteExpando,n=m.event.special;null!=(d=a[h]);h++)if((b||m.acceptData(d))&&(f=d[i],g=f&&j[f])){if(g.events)for(e in g.events)n[e]?m.event.remove(d,e):m.removeEvent(d,e,g.handle);j[f]&&(delete j[f],l?delete d[i]:typeof d.removeAttribute!==K?d.removeAttribute(i):d[i]=null,c.push(f))}}}),m.fn.extend({text:function(a){return V(this,function(a){return void 0===a?m.text(this):this.empty().append((this[0]&&this[0].ownerDocument||y).createTextNode(a))},null,a,arguments.length)},append:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=wb(this,a);b.appendChild(a)}})},prepend:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=wb(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},remove:function(a,b){for(var c,d=a?m.filter(a,this):this,e=0;null!=(c=d[e]);e++)b||1!==c.nodeType||m.cleanData(ub(c)),c.parentNode&&(b&&m.contains(c.ownerDocument,c)&&zb(ub(c,"script")),c.parentNode.removeChild(c));return this},empty:function(){for(var a,b=0;null!=(a=this[b]);b++){1===a.nodeType&&m.cleanData(ub(a,!1));while(a.firstChild)a.removeChild(a.firstChild);a.options&&m.nodeName(a,"select")&&(a.options.length=0)}return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return m.clone(this,a,b)})},html:function(a){return V(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a)return 1===b.nodeType?b.innerHTML.replace(fb,""):void 0;if(!("string"!=typeof a||mb.test(a)||!k.htmlSerialize&&gb.test(a)||!k.leadingWhitespace&&hb.test(a)||rb[(jb.exec(a)||["",""])[1].toLowerCase()])){a=a.replace(ib,"<$1></$2>");try{for(;d>c;c++)b=this[c]||{},1===b.nodeType&&(m.cleanData(ub(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=arguments[0];return this.domManip(arguments,function(b){a=this.parentNode,m.cleanData(ub(this)),a&&a.replaceChild(b,this)}),a&&(a.length||a.nodeType)?this:this.remove()},detach:function(a){return this.remove(a,!0)},domManip:function(a,b){a=e.apply([],a);var c,d,f,g,h,i,j=0,l=this.length,n=this,o=l-1,p=a[0],q=m.isFunction(p);if(q||l>1&&"string"==typeof p&&!k.checkClone&&nb.test(p))return this.each(function(c){var d=n.eq(c);q&&(a[0]=p.call(this,c,d.html())),d.domManip(a,b)});if(l&&(i=m.buildFragment(a,this[0].ownerDocument,!1,this),c=i.firstChild,1===i.childNodes.length&&(i=c),c)){for(g=m.map(ub(i,"script"),xb),f=g.length;l>j;j++)d=i,j!==o&&(d=m.clone(d,!0,!0),f&&m.merge(g,ub(d,"script"))),b.call(this[j],d,j);if(f)for(h=g[g.length-1].ownerDocument,m.map(g,yb),j=0;f>j;j++)d=g[j],ob.test(d.type||"")&&!m._data(d,"globalEval")&&m.contains(h,d)&&(d.src?m._evalUrl&&m._evalUrl(d.src):m.globalEval((d.text||d.textContent||d.innerHTML||"").replace(qb,"")));i=c=null}return this}}),m.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){m.fn[a]=function(a){for(var c,d=0,e=[],g=m(a),h=g.length-1;h>=d;d++)c=d===h?this:this.clone(!0),m(g[d])[b](c),f.apply(e,c.get());return this.pushStack(e)}});var Cb,Db={};function Eb(b,c){var d,e=m(c.createElement(b)).appendTo(c.body),f=a.getDefaultComputedStyle&&(d=a.getDefaultComputedStyle(e[0]))?d.display:m.css(e[0],"display");return e.detach(),f}function Fb(a){var b=y,c=Db[a];return c||(c=Eb(a,b),"none"!==c&&c||(Cb=(Cb||m("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement),b=(Cb[0].contentWindow||Cb[0].contentDocument).document,b.write(),b.close(),c=Eb(a,b),Cb.detach()),Db[a]=c),c}!function(){var a;k.shrinkWrapBlocks=function(){if(null!=a)return a;a=!1;var b,c,d;return c=y.getElementsByTagName("body")[0],c&&c.style?(b=y.createElement("div"),d=y.createElement("div"),d.style.cssText="position:absolute;border:0;width:0;height:0;top:0;left:-9999px",c.appendChild(d).appendChild(b),typeof b.style.zoom!==K&&(b.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1",b.appendChild(y.createElement("div")).style.width="5px",a=3!==b.offsetWidth),c.removeChild(d),a):void 0}}();var Gb=/^margin/,Hb=new RegExp("^("+S+")(?!px)[a-z%]+$","i"),Ib,Jb,Kb=/^(top|right|bottom|left)$/;a.getComputedStyle?(Ib=function(a){return a.ownerDocument.defaultView.getComputedStyle(a,null)},Jb=function(a,b,c){var d,e,f,g,h=a.style;return c=c||Ib(a),g=c?c.getPropertyValue(b)||c[b]:void 0,c&&(""!==g||m.contains(a.ownerDocument,a)||(g=m.style(a,b)),Hb.test(g)&&Gb.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f)),void 0===g?g:g+""}):y.documentElement.currentStyle&&(Ib=function(a){return a.currentStyle},Jb=function(a,b,c){var d,e,f,g,h=a.style;return c=c||Ib(a),g=c?c[b]:void 0,null==g&&h&&h[b]&&(g=h[b]),Hb.test(g)&&!Kb.test(b)&&(d=h.left,e=a.runtimeStyle,f=e&&e.left,f&&(e.left=a.currentStyle.left),h.left="fontSize"===b?"1em":g,g=h.pixelLeft+"px",h.left=d,f&&(e.left=f)),void 0===g?g:g+""||"auto"});function Lb(a,b){return{get:function(){var c=a();if(null!=c)return c?void delete this.get:(this.get=b).apply(this,arguments)}}}!function(){var b,c,d,e,f,g,h;if(b=y.createElement("div"),b.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",d=b.getElementsByTagName("a")[0],c=d&&d.style){c.cssText="float:left;opacity:.5",k.opacity="0.5"===c.opacity,k.cssFloat=!!c.cssFloat,b.style.backgroundClip="content-box",b.cloneNode(!0).style.backgroundClip="",k.clearCloneStyle="content-box"===b.style.backgroundClip,k.boxSizing=""===c.boxSizing||""===c.MozBoxSizing||""===c.WebkitBoxSizing,m.extend(k,{reliableHiddenOffsets:function(){return null==g&&i(),g},boxSizingReliable:function(){return null==f&&i(),f},pixelPosition:function(){return null==e&&i(),e},reliableMarginRight:function(){return null==h&&i(),h}});function i(){var b,c,d,i;c=y.getElementsByTagName("body")[0],c&&c.style&&(b=y.createElement("div"),d=y.createElement("div"),d.style.cssText="position:absolute;border:0;width:0;height:0;top:0;left:-9999px",c.appendChild(d).appendChild(b),b.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute",e=f=!1,h=!0,a.getComputedStyle&&(e="1%"!==(a.getComputedStyle(b,null)||{}).top,f="4px"===(a.getComputedStyle(b,null)||{width:"4px"}).width,i=b.appendChild(y.createElement("div")),i.style.cssText=b.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",i.style.marginRight=i.style.width="0",b.style.width="1px",h=!parseFloat((a.getComputedStyle(i,null)||{}).marginRight)),b.innerHTML="<table><tr><td></td><td>t</td></tr></table>",i=b.getElementsByTagName("td"),i[0].style.cssText="margin:0;border:0;padding:0;display:none",g=0===i[0].offsetHeight,g&&(i[0].style.display="",i[1].style.display="none",g=0===i[0].offsetHeight),c.removeChild(d))}}}(),m.swap=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e};var Mb=/alpha\([^)]*\)/i,Nb=/opacity\s*=\s*([^)]*)/,Ob=/^(none|table(?!-c[ea]).+)/,Pb=new RegExp("^("+S+")(.*)$","i"),Qb=new RegExp("^([+-])=("+S+")","i"),Rb={position:"absolute",visibility:"hidden",display:"block"},Sb={letterSpacing:"0",fontWeight:"400"},Tb=["Webkit","O","Moz","ms"];function Ub(a,b){if(b in a)return b;var c=b.charAt(0).toUpperCase()+b.slice(1),d=b,e=Tb.length;while(e--)if(b=Tb[e]+c,b in a)return b;return d}function Vb(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=m._data(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&U(d)&&(f[g]=m._data(d,"olddisplay",Fb(d.nodeName)))):(e=U(d),(c&&"none"!==c||!e)&&m._data(d,"olddisplay",e?c:m.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}function Wb(a,b,c){var d=Pb.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function Xb(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=m.css(a,c+T[f],!0,e)),d?("content"===c&&(g-=m.css(a,"padding"+T[f],!0,e)),"margin"!==c&&(g-=m.css(a,"border"+T[f]+"Width",!0,e))):(g+=m.css(a,"padding"+T[f],!0,e),"padding"!==c&&(g+=m.css(a,"border"+T[f]+"Width",!0,e)));return g}function Yb(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=Ib(a),g=k.boxSizing&&"border-box"===m.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=Jb(a,b,f),(0>e||null==e)&&(e=a.style[b]),Hb.test(e))return e;d=g&&(k.boxSizingReliable()||e===a.style[b]),e=parseFloat(e)||0}return e+Xb(a,b,c||(g?"border":"content"),d,f)+"px"}m.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=Jb(a,"opacity");return""===c?"1":c}}}},cssNumber:{columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":k.cssFloat?"cssFloat":"styleFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=m.camelCase(b),i=a.style;if(b=m.cssProps[h]||(m.cssProps[h]=Ub(i,h)),g=m.cssHooks[b]||m.cssHooks[h],void 0===c)return g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b];if(f=typeof c,"string"===f&&(e=Qb.exec(c))&&(c=(e[1]+1)*e[2]+parseFloat(m.css(a,b)),f="number"),null!=c&&c===c&&("number"!==f||m.cssNumber[h]||(c+="px"),k.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),!(g&&"set"in g&&void 0===(c=g.set(a,c,d)))))try{i[b]=c}catch(j){}}},css:function(a,b,c,d){var e,f,g,h=m.camelCase(b);return b=m.cssProps[h]||(m.cssProps[h]=Ub(a.style,h)),g=m.cssHooks[b]||m.cssHooks[h],g&&"get"in g&&(f=g.get(a,!0,c)),void 0===f&&(f=Jb(a,b,d)),"normal"===f&&b in Sb&&(f=Sb[b]),""===c||c?(e=parseFloat(f),c===!0||m.isNumeric(e)?e||0:f):f}}),m.each(["height","width"],function(a,b){m.cssHooks[b]={get:function(a,c,d){return c?Ob.test(m.css(a,"display"))&&0===a.offsetWidth?m.swap(a,Rb,function(){return Yb(a,b,d)}):Yb(a,b,d):void 0},set:function(a,c,d){var e=d&&Ib(a);return Wb(a,c,d?Xb(a,b,d,k.boxSizing&&"border-box"===m.css(a,"boxSizing",!1,e),e):0)}}}),k.opacity||(m.cssHooks.opacity={get:function(a,b){return Nb.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=m.isNumeric(b)?"alpha(opacity="+100*b+")":"",f=d&&d.filter||c.filter||"";c.zoom=1,(b>=1||""===b)&&""===m.trim(f.replace(Mb,""))&&c.removeAttribute&&(c.removeAttribute("filter"),""===b||d&&!d.filter)||(c.filter=Mb.test(f)?f.replace(Mb,e):f+" "+e)}}),m.cssHooks.marginRight=Lb(k.reliableMarginRight,function(a,b){return b?m.swap(a,{display:"inline-block"},Jb,[a,"marginRight"]):void 0}),m.each({margin:"",padding:"",border:"Width"},function(a,b){m.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+T[d]+b]=f[d]||f[d-2]||f[0];return e}},Gb.test(a)||(m.cssHooks[a+b].set=Wb)}),m.fn.extend({css:function(a,b){return V(this,function(a,b,c){var d,e,f={},g=0;if(m.isArray(b)){for(d=Ib(a),e=b.length;e>g;g++)f[b[g]]=m.css(a,b[g],!1,d);return f}return void 0!==c?m.style(a,b,c):m.css(a,b)},a,b,arguments.length>1)},show:function(){return Vb(this,!0)},hide:function(){return Vb(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){U(this)?m(this).show():m(this).hide()})}});function Zb(a,b,c,d,e){return new Zb.prototype.init(a,b,c,d,e)}m.Tween=Zb,Zb.prototype={constructor:Zb,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(m.cssNumber[c]?"":"px")
},cur:function(){var a=Zb.propHooks[this.prop];return a&&a.get?a.get(this):Zb.propHooks._default.get(this)},run:function(a){var b,c=Zb.propHooks[this.prop];return this.pos=b=this.options.duration?m.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Zb.propHooks._default.set(this),this}},Zb.prototype.init.prototype=Zb.prototype,Zb.propHooks={_default:{get:function(a){var b;return null==a.elem[a.prop]||a.elem.style&&null!=a.elem.style[a.prop]?(b=m.css(a.elem,a.prop,""),b&&"auto"!==b?b:0):a.elem[a.prop]},set:function(a){m.fx.step[a.prop]?m.fx.step[a.prop](a):a.elem.style&&(null!=a.elem.style[m.cssProps[a.prop]]||m.cssHooks[a.prop])?m.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},Zb.propHooks.scrollTop=Zb.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},m.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},m.fx=Zb.prototype.init,m.fx.step={};var $b,_b,ac=/^(?:toggle|show|hide)$/,bc=new RegExp("^(?:([+-])=|)("+S+")([a-z%]*)$","i"),cc=/queueHooks$/,dc=[ic],ec={"*":[function(a,b){var c=this.createTween(a,b),d=c.cur(),e=bc.exec(b),f=e&&e[3]||(m.cssNumber[a]?"":"px"),g=(m.cssNumber[a]||"px"!==f&&+d)&&bc.exec(m.css(c.elem,a)),h=1,i=20;if(g&&g[3]!==f){f=f||g[3],e=e||[],g=+d||1;do h=h||".5",g/=h,m.style(c.elem,a,g+f);while(h!==(h=c.cur()/d)&&1!==h&&--i)}return e&&(g=c.start=+g||+d||0,c.unit=f,c.end=e[1]?g+(e[1]+1)*e[2]:+e[2]),c}]};function fc(){return setTimeout(function(){$b=void 0}),$b=m.now()}function gc(a,b){var c,d={height:a},e=0;for(b=b?1:0;4>e;e+=2-b)c=T[e],d["margin"+c]=d["padding"+c]=a;return b&&(d.opacity=d.width=a),d}function hc(a,b,c){for(var d,e=(ec[b]||[]).concat(ec["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function ic(a,b,c){var d,e,f,g,h,i,j,l,n=this,o={},p=a.style,q=a.nodeType&&U(a),r=m._data(a,"fxshow");c.queue||(h=m._queueHooks(a,"fx"),null==h.unqueued&&(h.unqueued=0,i=h.empty.fire,h.empty.fire=function(){h.unqueued||i()}),h.unqueued++,n.always(function(){n.always(function(){h.unqueued--,m.queue(a,"fx").length||h.empty.fire()})})),1===a.nodeType&&("height"in b||"width"in b)&&(c.overflow=[p.overflow,p.overflowX,p.overflowY],j=m.css(a,"display"),l="none"===j?m._data(a,"olddisplay")||Fb(a.nodeName):j,"inline"===l&&"none"===m.css(a,"float")&&(k.inlineBlockNeedsLayout&&"inline"!==Fb(a.nodeName)?p.zoom=1:p.display="inline-block")),c.overflow&&(p.overflow="hidden",k.shrinkWrapBlocks()||n.always(function(){p.overflow=c.overflow[0],p.overflowX=c.overflow[1],p.overflowY=c.overflow[2]}));for(d in b)if(e=b[d],ac.exec(e)){if(delete b[d],f=f||"toggle"===e,e===(q?"hide":"show")){if("show"!==e||!r||void 0===r[d])continue;q=!0}o[d]=r&&r[d]||m.style(a,d)}else j=void 0;if(m.isEmptyObject(o))"inline"===("none"===j?Fb(a.nodeName):j)&&(p.display=j);else{r?"hidden"in r&&(q=r.hidden):r=m._data(a,"fxshow",{}),f&&(r.hidden=!q),q?m(a).show():n.done(function(){m(a).hide()}),n.done(function(){var b;m._removeData(a,"fxshow");for(b in o)m.style(a,b,o[b])});for(d in o)g=hc(q?r[d]:0,d,n),d in r||(r[d]=g.start,q&&(g.end=g.start,g.start="width"===d||"height"===d?1:0))}}function jc(a,b){var c,d,e,f,g;for(c in a)if(d=m.camelCase(c),e=b[d],f=a[c],m.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=m.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function kc(a,b,c){var d,e,f=0,g=dc.length,h=m.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=$b||fc(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:m.extend({},b),opts:m.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:$b||fc(),duration:c.duration,tweens:[],createTween:function(b,c){var d=m.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;for(jc(k,j.opts.specialEasing);g>f;f++)if(d=dc[f].call(j,a,k,j.opts))return d;return m.map(k,hc,j),m.isFunction(j.opts.start)&&j.opts.start.call(a,j),m.fx.timer(m.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}m.Animation=m.extend(kc,{tweener:function(a,b){m.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");for(var c,d=0,e=a.length;e>d;d++)c=a[d],ec[c]=ec[c]||[],ec[c].unshift(b)},prefilter:function(a,b){b?dc.unshift(a):dc.push(a)}}),m.speed=function(a,b,c){var d=a&&"object"==typeof a?m.extend({},a):{complete:c||!c&&b||m.isFunction(a)&&a,duration:a,easing:c&&b||b&&!m.isFunction(b)&&b};return d.duration=m.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in m.fx.speeds?m.fx.speeds[d.duration]:m.fx.speeds._default,(null==d.queue||d.queue===!0)&&(d.queue="fx"),d.old=d.complete,d.complete=function(){m.isFunction(d.old)&&d.old.call(this),d.queue&&m.dequeue(this,d.queue)},d},m.fn.extend({fadeTo:function(a,b,c,d){return this.filter(U).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=m.isEmptyObject(a),f=m.speed(b,c,d),g=function(){var b=kc(this,m.extend({},a),f);(e||m._data(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=m.timers,g=m._data(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&cc.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));(b||!c)&&m.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=m._data(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=m.timers,g=d?d.length:0;for(c.finish=!0,m.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),m.each(["toggle","show","hide"],function(a,b){var c=m.fn[b];m.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(gc(b,!0),a,d,e)}}),m.each({slideDown:gc("show"),slideUp:gc("hide"),slideToggle:gc("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){m.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),m.timers=[],m.fx.tick=function(){var a,b=m.timers,c=0;for($b=m.now();c<b.length;c++)a=b[c],a()||b[c]!==a||b.splice(c--,1);b.length||m.fx.stop(),$b=void 0},m.fx.timer=function(a){m.timers.push(a),a()?m.fx.start():m.timers.pop()},m.fx.interval=13,m.fx.start=function(){_b||(_b=setInterval(m.fx.tick,m.fx.interval))},m.fx.stop=function(){clearInterval(_b),_b=null},m.fx.speeds={slow:600,fast:200,_default:400},m.fn.delay=function(a,b){return a=m.fx?m.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},function(){var a,b,c,d,e;b=y.createElement("div"),b.setAttribute("className","t"),b.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",d=b.getElementsByTagName("a")[0],c=y.createElement("select"),e=c.appendChild(y.createElement("option")),a=b.getElementsByTagName("input")[0],d.style.cssText="top:1px",k.getSetAttribute="t"!==b.className,k.style=/top/.test(d.getAttribute("style")),k.hrefNormalized="/a"===d.getAttribute("href"),k.checkOn=!!a.value,k.optSelected=e.selected,k.enctype=!!y.createElement("form").enctype,c.disabled=!0,k.optDisabled=!e.disabled,a=y.createElement("input"),a.setAttribute("value",""),k.input=""===a.getAttribute("value"),a.value="t",a.setAttribute("type","radio"),k.radioValue="t"===a.value}();var lc=/\r/g;m.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=m.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,m(this).val()):a,null==e?e="":"number"==typeof e?e+="":m.isArray(e)&&(e=m.map(e,function(a){return null==a?"":a+""})),b=m.valHooks[this.type]||m.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=m.valHooks[e.type]||m.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(lc,""):null==c?"":c)}}}),m.extend({valHooks:{option:{get:function(a){var b=m.find.attr(a,"value");return null!=b?b:m.trim(m.text(a))}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],!(!c.selected&&i!==e||(k.optDisabled?c.disabled:null!==c.getAttribute("disabled"))||c.parentNode.disabled&&m.nodeName(c.parentNode,"optgroup"))){if(b=m(c).val(),f)return b;g.push(b)}return g},set:function(a,b){var c,d,e=a.options,f=m.makeArray(b),g=e.length;while(g--)if(d=e[g],m.inArray(m.valHooks.option.get(d),f)>=0)try{d.selected=c=!0}catch(h){d.scrollHeight}else d.selected=!1;return c||(a.selectedIndex=-1),e}}}}),m.each(["radio","checkbox"],function(){m.valHooks[this]={set:function(a,b){return m.isArray(b)?a.checked=m.inArray(m(a).val(),b)>=0:void 0}},k.checkOn||(m.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})});var mc,nc,oc=m.expr.attrHandle,pc=/^(?:checked|selected)$/i,qc=k.getSetAttribute,rc=k.input;m.fn.extend({attr:function(a,b){return V(this,m.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){m.removeAttr(this,a)})}}),m.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(a&&3!==f&&8!==f&&2!==f)return typeof a.getAttribute===K?m.prop(a,b,c):(1===f&&m.isXMLDoc(a)||(b=b.toLowerCase(),d=m.attrHooks[b]||(m.expr.match.bool.test(b)?nc:mc)),void 0===c?d&&"get"in d&&null!==(e=d.get(a,b))?e:(e=m.find.attr(a,b),null==e?void 0:e):null!==c?d&&"set"in d&&void 0!==(e=d.set(a,c,b))?e:(a.setAttribute(b,c+""),c):void m.removeAttr(a,b))},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(E);if(f&&1===a.nodeType)while(c=f[e++])d=m.propFix[c]||c,m.expr.match.bool.test(c)?rc&&qc||!pc.test(c)?a[d]=!1:a[m.camelCase("default-"+c)]=a[d]=!1:m.attr(a,c,""),a.removeAttribute(qc?c:d)},attrHooks:{type:{set:function(a,b){if(!k.radioValue&&"radio"===b&&m.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}}}),nc={set:function(a,b,c){return b===!1?m.removeAttr(a,c):rc&&qc||!pc.test(c)?a.setAttribute(!qc&&m.propFix[c]||c,c):a[m.camelCase("default-"+c)]=a[c]=!0,c}},m.each(m.expr.match.bool.source.match(/\w+/g),function(a,b){var c=oc[b]||m.find.attr;oc[b]=rc&&qc||!pc.test(b)?function(a,b,d){var e,f;return d||(f=oc[b],oc[b]=e,e=null!=c(a,b,d)?b.toLowerCase():null,oc[b]=f),e}:function(a,b,c){return c?void 0:a[m.camelCase("default-"+b)]?b.toLowerCase():null}}),rc&&qc||(m.attrHooks.value={set:function(a,b,c){return m.nodeName(a,"input")?void(a.defaultValue=b):mc&&mc.set(a,b,c)}}),qc||(mc={set:function(a,b,c){var d=a.getAttributeNode(c);return d||a.setAttributeNode(d=a.ownerDocument.createAttribute(c)),d.value=b+="","value"===c||b===a.getAttribute(c)?b:void 0}},oc.id=oc.name=oc.coords=function(a,b,c){var d;return c?void 0:(d=a.getAttributeNode(b))&&""!==d.value?d.value:null},m.valHooks.button={get:function(a,b){var c=a.getAttributeNode(b);return c&&c.specified?c.value:void 0},set:mc.set},m.attrHooks.contenteditable={set:function(a,b,c){mc.set(a,""===b?!1:b,c)}},m.each(["width","height"],function(a,b){m.attrHooks[b]={set:function(a,c){return""===c?(a.setAttribute(b,"auto"),c):void 0}}})),k.style||(m.attrHooks.style={get:function(a){return a.style.cssText||void 0},set:function(a,b){return a.style.cssText=b+""}});var sc=/^(?:input|select|textarea|button|object)$/i,tc=/^(?:a|area)$/i;m.fn.extend({prop:function(a,b){return V(this,m.prop,a,b,arguments.length>1)},removeProp:function(a){return a=m.propFix[a]||a,this.each(function(){try{this[a]=void 0,delete this[a]}catch(b){}})}}),m.extend({propFix:{"for":"htmlFor","class":"className"},prop:function(a,b,c){var d,e,f,g=a.nodeType;if(a&&3!==g&&8!==g&&2!==g)return f=1!==g||!m.isXMLDoc(a),f&&(b=m.propFix[b]||b,e=m.propHooks[b]),void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){var b=m.find.attr(a,"tabindex");return b?parseInt(b,10):sc.test(a.nodeName)||tc.test(a.nodeName)&&a.href?0:-1}}}}),k.hrefNormalized||m.each(["href","src"],function(a,b){m.propHooks[b]={get:function(a){return a.getAttribute(b,4)}}}),k.optSelected||(m.propHooks.selected={get:function(a){var b=a.parentNode;return b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex),null}}),m.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){m.propFix[this.toLowerCase()]=this}),k.enctype||(m.propFix.enctype="encoding");var uc=/[\t\r\n\f]/g;m.fn.extend({addClass:function(a){var b,c,d,e,f,g,h=0,i=this.length,j="string"==typeof a&&a;if(m.isFunction(a))return this.each(function(b){m(this).addClass(a.call(this,b,this.className))});if(j)for(b=(a||"").match(E)||[];i>h;h++)if(c=this[h],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(uc," "):" ")){f=0;while(e=b[f++])d.indexOf(" "+e+" ")<0&&(d+=e+" ");g=m.trim(d),c.className!==g&&(c.className=g)}return this},removeClass:function(a){var b,c,d,e,f,g,h=0,i=this.length,j=0===arguments.length||"string"==typeof a&&a;if(m.isFunction(a))return this.each(function(b){m(this).removeClass(a.call(this,b,this.className))});if(j)for(b=(a||"").match(E)||[];i>h;h++)if(c=this[h],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(uc," "):"")){f=0;while(e=b[f++])while(d.indexOf(" "+e+" ")>=0)d=d.replace(" "+e+" "," ");g=a?m.trim(d):"",c.className!==g&&(c.className=g)}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):this.each(m.isFunction(a)?function(c){m(this).toggleClass(a.call(this,c,this.className,b),b)}:function(){if("string"===c){var b,d=0,e=m(this),f=a.match(E)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else(c===K||"boolean"===c)&&(this.className&&m._data(this,"__className__",this.className),this.className=this.className||a===!1?"":m._data(this,"__className__")||"")})},hasClass:function(a){for(var b=" "+a+" ",c=0,d=this.length;d>c;c++)if(1===this[c].nodeType&&(" "+this[c].className+" ").replace(uc," ").indexOf(b)>=0)return!0;return!1}}),m.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){m.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),m.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}});var vc=m.now(),wc=/\?/,xc=/(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;m.parseJSON=function(b){if(a.JSON&&a.JSON.parse)return a.JSON.parse(b+"");var c,d=null,e=m.trim(b+"");return e&&!m.trim(e.replace(xc,function(a,b,e,f){return c&&b&&(d=0),0===d?a:(c=e||b,d+=!f-!e,"")}))?Function("return "+e)():m.error("Invalid JSON: "+b)},m.parseXML=function(b){var c,d;if(!b||"string"!=typeof b)return null;try{a.DOMParser?(d=new DOMParser,c=d.parseFromString(b,"text/xml")):(c=new ActiveXObject("Microsoft.XMLDOM"),c.async="false",c.loadXML(b))}catch(e){c=void 0}return c&&c.documentElement&&!c.getElementsByTagName("parsererror").length||m.error("Invalid XML: "+b),c};var yc,zc,Ac=/#.*$/,Bc=/([?&])_=[^&]*/,Cc=/^(.*?):[ \t]*([^\r\n]*)\r?$/gm,Dc=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,Ec=/^(?:GET|HEAD)$/,Fc=/^\/\//,Gc=/^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,Hc={},Ic={},Jc="*/".concat("*");try{zc=location.href}catch(Kc){zc=y.createElement("a"),zc.href="",zc=zc.href}yc=Gc.exec(zc.toLowerCase())||[];function Lc(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(E)||[];if(m.isFunction(c))while(d=f[e++])"+"===d.charAt(0)?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function Mc(a,b,c,d){var e={},f=a===Ic;function g(h){var i;return e[h]=!0,m.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function Nc(a,b){var c,d,e=m.ajaxSettings.flatOptions||{};for(d in b)void 0!==b[d]&&((e[d]?a:c||(c={}))[d]=b[d]);return c&&m.extend(!0,a,c),a}function Oc(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===e&&(e=a.mimeType||b.getResponseHeader("Content-Type"));if(e)for(g in h)if(h[g]&&h[g].test(e)){i.unshift(g);break}if(i[0]in c)f=i[0];else{for(g in c){if(!i[0]||a.converters[g+" "+i[0]]){f=g;break}d||(d=g)}f=f||d}return f?(f!==i[0]&&i.unshift(f),c[f]):void 0}function Pc(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}m.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:zc,type:"GET",isLocal:Dc.test(yc[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Jc,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":m.parseJSON,"text xml":m.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?Nc(Nc(a,m.ajaxSettings),b):Nc(m.ajaxSettings,a)},ajaxPrefilter:Lc(Hc),ajaxTransport:Lc(Ic),ajax:function(a,b){"object"==typeof a&&(b=a,a=void 0),b=b||{};var c,d,e,f,g,h,i,j,k=m.ajaxSetup({},b),l=k.context||k,n=k.context&&(l.nodeType||l.jquery)?m(l):m.event,o=m.Deferred(),p=m.Callbacks("once memory"),q=k.statusCode||{},r={},s={},t=0,u="canceled",v={readyState:0,getResponseHeader:function(a){var b;if(2===t){if(!j){j={};while(b=Cc.exec(f))j[b[1].toLowerCase()]=b[2]}b=j[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===t?f:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return t||(a=s[c]=s[c]||a,r[a]=b),this},overrideMimeType:function(a){return t||(k.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>t)for(b in a)q[b]=[q[b],a[b]];else v.always(a[v.status]);return this},abort:function(a){var b=a||u;return i&&i.abort(b),x(0,b),this}};if(o.promise(v).complete=p.add,v.success=v.done,v.error=v.fail,k.url=((a||k.url||zc)+"").replace(Ac,"").replace(Fc,yc[1]+"//"),k.type=b.method||b.type||k.method||k.type,k.dataTypes=m.trim(k.dataType||"*").toLowerCase().match(E)||[""],null==k.crossDomain&&(c=Gc.exec(k.url.toLowerCase()),k.crossDomain=!(!c||c[1]===yc[1]&&c[2]===yc[2]&&(c[3]||("http:"===c[1]?"80":"443"))===(yc[3]||("http:"===yc[1]?"80":"443")))),k.data&&k.processData&&"string"!=typeof k.data&&(k.data=m.param(k.data,k.traditional)),Mc(Hc,k,b,v),2===t)return v;h=k.global,h&&0===m.active++&&m.event.trigger("ajaxStart"),k.type=k.type.toUpperCase(),k.hasContent=!Ec.test(k.type),e=k.url,k.hasContent||(k.data&&(e=k.url+=(wc.test(e)?"&":"?")+k.data,delete k.data),k.cache===!1&&(k.url=Bc.test(e)?e.replace(Bc,"$1_="+vc++):e+(wc.test(e)?"&":"?")+"_="+vc++)),k.ifModified&&(m.lastModified[e]&&v.setRequestHeader("If-Modified-Since",m.lastModified[e]),m.etag[e]&&v.setRequestHeader("If-None-Match",m.etag[e])),(k.data&&k.hasContent&&k.contentType!==!1||b.contentType)&&v.setRequestHeader("Content-Type",k.contentType),v.setRequestHeader("Accept",k.dataTypes[0]&&k.accepts[k.dataTypes[0]]?k.accepts[k.dataTypes[0]]+("*"!==k.dataTypes[0]?", "+Jc+"; q=0.01":""):k.accepts["*"]);for(d in k.headers)v.setRequestHeader(d,k.headers[d]);if(k.beforeSend&&(k.beforeSend.call(l,v,k)===!1||2===t))return v.abort();u="abort";for(d in{success:1,error:1,complete:1})v[d](k[d]);if(i=Mc(Ic,k,b,v)){v.readyState=1,h&&n.trigger("ajaxSend",[v,k]),k.async&&k.timeout>0&&(g=setTimeout(function(){v.abort("timeout")},k.timeout));try{t=1,i.send(r,x)}catch(w){if(!(2>t))throw w;x(-1,w)}}else x(-1,"No Transport");function x(a,b,c,d){var j,r,s,u,w,x=b;2!==t&&(t=2,g&&clearTimeout(g),i=void 0,f=d||"",v.readyState=a>0?4:0,j=a>=200&&300>a||304===a,c&&(u=Oc(k,v,c)),u=Pc(k,u,v,j),j?(k.ifModified&&(w=v.getResponseHeader("Last-Modified"),w&&(m.lastModified[e]=w),w=v.getResponseHeader("etag"),w&&(m.etag[e]=w)),204===a||"HEAD"===k.type?x="nocontent":304===a?x="notmodified":(x=u.state,r=u.data,s=u.error,j=!s)):(s=x,(a||!x)&&(x="error",0>a&&(a=0))),v.status=a,v.statusText=(b||x)+"",j?o.resolveWith(l,[r,x,v]):o.rejectWith(l,[v,x,s]),v.statusCode(q),q=void 0,h&&n.trigger(j?"ajaxSuccess":"ajaxError",[v,k,j?r:s]),p.fireWith(l,[v,x]),h&&(n.trigger("ajaxComplete",[v,k]),--m.active||m.event.trigger("ajaxStop")))}return v},getJSON:function(a,b,c){return m.get(a,b,c,"json")},getScript:function(a,b){return m.get(a,void 0,b,"script")}}),m.each(["get","post"],function(a,b){m[b]=function(a,c,d,e){return m.isFunction(c)&&(e=e||d,d=c,c=void 0),m.ajax({url:a,type:b,dataType:e,data:c,success:d})}}),m.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){m.fn[b]=function(a){return this.on(b,a)}}),m._evalUrl=function(a){return m.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})},m.fn.extend({wrapAll:function(a){if(m.isFunction(a))return this.each(function(b){m(this).wrapAll(a.call(this,b))});if(this[0]){var b=m(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&1===a.firstChild.nodeType)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){return this.each(m.isFunction(a)?function(b){m(this).wrapInner(a.call(this,b))}:function(){var b=m(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=m.isFunction(a);return this.each(function(c){m(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){m.nodeName(this,"body")||m(this).replaceWith(this.childNodes)}).end()}}),m.expr.filters.hidden=function(a){return a.offsetWidth<=0&&a.offsetHeight<=0||!k.reliableHiddenOffsets()&&"none"===(a.style&&a.style.display||m.css(a,"display"))},m.expr.filters.visible=function(a){return!m.expr.filters.hidden(a)};var Qc=/%20/g,Rc=/\[\]$/,Sc=/\r?\n/g,Tc=/^(?:submit|button|image|reset|file)$/i,Uc=/^(?:input|select|textarea|keygen)/i;function Vc(a,b,c,d){var e;if(m.isArray(b))m.each(b,function(b,e){c||Rc.test(a)?d(a,e):Vc(a+"["+("object"==typeof e?b:"")+"]",e,c,d)});else if(c||"object"!==m.type(b))d(a,b);else for(e in b)Vc(a+"["+e+"]",b[e],c,d)}m.param=function(a,b){var c,d=[],e=function(a,b){b=m.isFunction(b)?b():null==b?"":b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(void 0===b&&(b=m.ajaxSettings&&m.ajaxSettings.traditional),m.isArray(a)||a.jquery&&!m.isPlainObject(a))m.each(a,function(){e(this.name,this.value)});else for(c in a)Vc(c,a[c],b,e);return d.join("&").replace(Qc,"+")},m.fn.extend({serialize:function(){return m.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=m.prop(this,"elements");return a?m.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!m(this).is(":disabled")&&Uc.test(this.nodeName)&&!Tc.test(a)&&(this.checked||!W.test(a))}).map(function(a,b){var c=m(this).val();return null==c?null:m.isArray(c)?m.map(c,function(a){return{name:b.name,value:a.replace(Sc,"\r\n")}}):{name:b.name,value:c.replace(Sc,"\r\n")}}).get()}}),m.ajaxSettings.xhr=void 0!==a.ActiveXObject?function(){return!this.isLocal&&/^(get|post|head|put|delete|options)$/i.test(this.type)&&Zc()||$c()}:Zc;var Wc=0,Xc={},Yc=m.ajaxSettings.xhr();a.ActiveXObject&&m(a).on("unload",function(){for(var a in Xc)Xc[a](void 0,!0)}),k.cors=!!Yc&&"withCredentials"in Yc,Yc=k.ajax=!!Yc,Yc&&m.ajaxTransport(function(a){if(!a.crossDomain||k.cors){var b;return{send:function(c,d){var e,f=a.xhr(),g=++Wc;if(f.open(a.type,a.url,a.async,a.username,a.password),a.xhrFields)for(e in a.xhrFields)f[e]=a.xhrFields[e];a.mimeType&&f.overrideMimeType&&f.overrideMimeType(a.mimeType),a.crossDomain||c["X-Requested-With"]||(c["X-Requested-With"]="XMLHttpRequest");for(e in c)void 0!==c[e]&&f.setRequestHeader(e,c[e]+"");f.send(a.hasContent&&a.data||null),b=function(c,e){var h,i,j;if(b&&(e||4===f.readyState))if(delete Xc[g],b=void 0,f.onreadystatechange=m.noop,e)4!==f.readyState&&f.abort();else{j={},h=f.status,"string"==typeof f.responseText&&(j.text=f.responseText);try{i=f.statusText}catch(k){i=""}h||!a.isLocal||a.crossDomain?1223===h&&(h=204):h=j.text?200:404}j&&d(h,i,j,f.getAllResponseHeaders())},a.async?4===f.readyState?setTimeout(b):f.onreadystatechange=Xc[g]=b:b()},abort:function(){b&&b(void 0,!0)}}}});function Zc(){try{return new a.XMLHttpRequest}catch(b){}}function $c(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}m.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(a){return m.globalEval(a),a}}}),m.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),m.ajaxTransport("script",function(a){if(a.crossDomain){var b,c=y.head||m("head")[0]||y.documentElement;return{send:function(d,e){b=y.createElement("script"),b.async=!0,a.scriptCharset&&(b.charset=a.scriptCharset),b.src=a.url,b.onload=b.onreadystatechange=function(a,c){(c||!b.readyState||/loaded|complete/.test(b.readyState))&&(b.onload=b.onreadystatechange=null,b.parentNode&&b.parentNode.removeChild(b),b=null,c||e(200,"success"))},c.insertBefore(b,c.firstChild)},abort:function(){b&&b.onload(void 0,!0)}}}});var _c=[],ad=/(=)\?(?=&|$)|\?\?/;m.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=_c.pop()||m.expando+"_"+vc++;return this[a]=!0,a}}),m.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(ad.test(b.url)?"url":"string"==typeof b.data&&!(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&ad.test(b.data)&&"data");return h||"jsonp"===b.dataTypes[0]?(e=b.jsonpCallback=m.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(ad,"$1"+e):b.jsonp!==!1&&(b.url+=(wc.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||m.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,_c.push(e)),g&&m.isFunction(f)&&f(g[0]),g=f=void 0}),"script"):void 0}),m.parseHTML=function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||y;var d=u.exec(a),e=!c&&[];return d?[b.createElement(d[1])]:(d=m.buildFragment([a],b,e),e&&e.length&&m(e).remove(),m.merge([],d.childNodes))};var bd=m.fn.load;m.fn.load=function(a,b,c){if("string"!=typeof a&&bd)return bd.apply(this,arguments);var d,e,f,g=this,h=a.indexOf(" ");return h>=0&&(d=m.trim(a.slice(h,a.length)),a=a.slice(0,h)),m.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(f="POST"),g.length>0&&m.ajax({url:a,type:f,dataType:"html",data:b}).done(function(a){e=arguments,g.html(d?m("<div>").append(m.parseHTML(a)).find(d):a)}).complete(c&&function(a,b){g.each(c,e||[a.responseText,b,a])}),this},m.expr.filters.animated=function(a){return m.grep(m.timers,function(b){return a===b.elem}).length};var cd=a.document.documentElement;function dd(a){return m.isWindow(a)?a:9===a.nodeType?a.defaultView||a.parentWindow:!1}m.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=m.css(a,"position"),l=m(a),n={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=m.css(a,"top"),i=m.css(a,"left"),j=("absolute"===k||"fixed"===k)&&m.inArray("auto",[f,i])>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),m.isFunction(b)&&(b=b.call(a,c,h)),null!=b.top&&(n.top=b.top-h.top+g),null!=b.left&&(n.left=b.left-h.left+e),"using"in b?b.using.call(a,n):l.css(n)}},m.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){m.offset.setOffset(this,a,b)});var b,c,d={top:0,left:0},e=this[0],f=e&&e.ownerDocument;if(f)return b=f.documentElement,m.contains(b,e)?(typeof e.getBoundingClientRect!==K&&(d=e.getBoundingClientRect()),c=dd(f),{top:d.top+(c.pageYOffset||b.scrollTop)-(b.clientTop||0),left:d.left+(c.pageXOffset||b.scrollLeft)-(b.clientLeft||0)}):d},position:function(){if(this[0]){var a,b,c={top:0,left:0},d=this[0];return"fixed"===m.css(d,"position")?b=d.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),m.nodeName(a[0],"html")||(c=a.offset()),c.top+=m.css(a[0],"borderTopWidth",!0),c.left+=m.css(a[0],"borderLeftWidth",!0)),{top:b.top-c.top-m.css(d,"marginTop",!0),left:b.left-c.left-m.css(d,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||cd;while(a&&!m.nodeName(a,"html")&&"static"===m.css(a,"position"))a=a.offsetParent;return a||cd})}}),m.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,b){var c=/Y/.test(b);m.fn[a]=function(d){return V(this,function(a,d,e){var f=dd(a);return void 0===e?f?b in f?f[b]:f.document.documentElement[d]:a[d]:void(f?f.scrollTo(c?m(f).scrollLeft():e,c?e:m(f).scrollTop()):a[d]=e)},a,d,arguments.length,null)}}),m.each(["top","left"],function(a,b){m.cssHooks[b]=Lb(k.pixelPosition,function(a,c){return c?(c=Jb(a,b),Hb.test(c)?m(a).position()[b]+"px":c):void 0})}),m.each({Height:"height",Width:"width"},function(a,b){m.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){m.fn[d]=function(d,e){var f=arguments.length&&(c||"boolean"!=typeof d),g=c||(d===!0||e===!0?"margin":"border");return V(this,function(b,c,d){var e;return m.isWindow(b)?b.document.documentElement["client"+a]:9===b.nodeType?(e=b.documentElement,Math.max(b.body["scroll"+a],e["scroll"+a],b.body["offset"+a],e["offset"+a],e["client"+a])):void 0===d?m.css(b,c,g):m.style(b,c,d,g)},b,f?d:void 0,f,null)}})}),m.fn.size=function(){return this.length},m.fn.andSelf=m.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return m});var ed=a.jQuery,fd=a.$;return m.noConflict=function(b){return a.$===m&&(a.$=fd),b&&a.jQuery===m&&(a.jQuery=ed),m},typeof b===K&&(a.jQuery=a.$=m),m});
;



/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * This object handles ajax requests for pages. It also
 * handles the reloading of the main menu and scripts.
 */
var AJAX = {
    /**
     * @var bool active Whether we are busy
     */
    active: false,
    /**
     * @var object source The object whose event initialized the request
     */
    source: null,
    /**
     * @var object xhr A reference to the ajax request that is currently running
     */
    xhr: null,
    /**
     * @var object lockedTargets, list of locked targets
     */
    lockedTargets: {},
    /**
     * @var function Callback to execute after a successful request
     *               Used by PMA_commonFunctions from common.js
     */
    _callback: function () {},
    /**
     * @var bool _debug Makes noise in your Firebug console
     */
    _debug: false,
    /**
     * @var object $msgbox A reference to a jQuery object that links to a message
     *                     box that is generated by PMA_ajaxShowMessage()
     */
    $msgbox: null,
    /**
     * Given the filename of a script, returns a hash to be
     * used to refer to all the events registered for the file
     *
     * @param key string key The filename for which to get the event name
     *
     * @return int
     */
    hash: function (key) {
        /* http://burtleburtle.net/bob/hash/doobs.html#one */
        key += "";
        var len = key.length, hash = 0, i = 0;
        for (; i < len; ++i) {
            hash += key.charCodeAt(i);
            hash += (hash << 10);
            hash ^= (hash >> 6);
        }
        hash += (hash << 3);
        hash ^= (hash >> 11);
        hash += (hash << 15);
        return Math.abs(hash);
    },
    /**
     * Registers an onload event for a file
     *
     * @param file string   file The filename for which to register the event
     * @param func function func The function to execute when the page is ready
     *
     * @return self For chaining
     */
    registerOnload: function (file, func) {
        var eventName = 'onload_' + AJAX.hash(file);
        $(document).bind(eventName, func);
        if (this._debug) {
            console.log(
                // no need to translate
                "Registered event " + eventName + " for file " + file
            );
        }
        return this;
    },
    /**
     * Registers a teardown event for a file. This is useful to execute functions
     * that unbind events for page elements that are about to be removed.
     *
     * @param string   file The filename for which to register the event
     * @param function func The function to execute when
     *                      the page is about to be torn down
     *
     * @return self For chaining
     */
    registerTeardown: function (file, func) {
        var eventName = 'teardown_' + AJAX.hash(file);
        $(document).bind(eventName, func);
        if (this._debug) {
            console.log(
                // no need to translate
                "Registered event " + eventName + " for file " + file
            );
        }
        return this;
    },
    /**
     * Called when a page has finished loading, once for every
     * file that registered to the onload event of that file.
     *
     * @param string file The filename for which to fire the event
     *
     * @return void
     */
    fireOnload: function (file) {
        var eventName = 'onload_' + AJAX.hash(file);
        $(document).trigger(eventName);
        if (this._debug) {
            console.log(
                // no need to translate
                "Fired event " + eventName + " for file " + file
            );
        }
    },
    /**
     * Called just before a page is torn down, once for every
     * file that registered to the teardown event of that file.
     *
     * @param string file The filename for which to fire the event
     *
     * @return void
     */
    fireTeardown: function (file) {
        var eventName = 'teardown_' + AJAX.hash(file);
        $(document).triggerHandler(eventName);
        if (this._debug) {
            console.log(
                // no need to translate
                "Fired event " + eventName + " for file " + file
            );
        }
    },
    /**
     * function to handle lock page mechanism
     *
     * @param event the event object
     *
     * @return void
     */
    lockPageHandler: function(event) {
        //Don't lock on enter.
        if (0 == event.charCode) {
            return;
        }

        var lockId = $(this).data('lock-id');
        if (typeof lockId === 'undefined') {
            return;
        }
        /*
         * @todo Fix Code mirror does not give correct full value (query)
         * in textarea, it returns only the change in content.
         */
        var newHash = null;
        if (event.data.value == 1) {
            newHash = AJAX.hash($(this).val());
        } else {
            newHash = AJAX.hash($(this).is(":checked"));
        }
        var oldHash = $(this).data('val-hash');
        // Set lock if old value != new value
        // otherwise release lock
        if (oldHash !== newHash) {
            AJAX.lockedTargets[lockId] = true;
        } else {
            delete AJAX.lockedTargets[lockId];
        }
        // Show lock icon if locked targets is not empty.
        // otherwise remove lock icon
        if (!jQuery.isEmptyObject(AJAX.lockedTargets)) {
            $('#lock_page_icon').html(PMA_getImage('s_lock.png',PMA_messages.strLockToolTip).toString());
        } else {
            $('#lock_page_icon').html('');
        }
    },
    /**
     * resets the lock
     *
     * @return void
     */
    resetLock: function() {
        AJAX.lockedTargets = {};
        $('#lock_page_icon').html('');
    },
    handleMenu: {
        replace: function (content) {
            $('#floating_menubar').html(content)
                // Remove duplicate wrapper
                // TODO: don't send it in the response
                .children().first().remove();
            $('#topmenu').menuResizer(PMA_mainMenuResizerCallback);
        }
    },
    /**
     * Event handler for clicks on links and form submissions
     *
     * @param object e Event data
     *
     * @return void
     */
    requestHandler: function (event) {
        // In some cases we don't want to handle the request here and either
        // leave the browser deal with it natively (e.g: file download)
        // or leave an existing ajax event handler present elsewhere deal with it
        var href = $(this).attr('href');
        if (typeof event != 'undefined' && (event.shiftKey || event.ctrlKey)) {
            return true;
        } else if ($(this).attr('target')) {
            return true;
        } else if ($(this).hasClass('ajax') || $(this).hasClass('disableAjax')) {
            //reset the lockedTargets object, as specified AJAX operation has finished
            AJAX.resetLock();
            return true;
        } else if (href && href.match(/^#/)) {
            return true;
        } else if (href && href.match(/^mailto/)) {
            return true;
        } else if ($(this).hasClass('ui-datepicker-next') ||
            $(this).hasClass('ui-datepicker-prev')
        ) {
            return true;
        }

        if (typeof event != 'undefined') {
            event.preventDefault();
            event.stopImmediatePropagation();
        }

        //triggers a confirm dialog if:
        //the user has performed some operations on loaded page
        //the user clicks on some link, (won't trigger for buttons)
        //the click event is not triggered by script
        if (typeof event !== 'undefined' && event.type === 'click' &&
            event.isTrigger !== true &&
            !jQuery.isEmptyObject(AJAX.lockedTargets) &&
            confirm(PMA_messages.strConfirmNavigation) === false
        ) {
            return false;
        }
        AJAX.resetLock();
        var isLink = !! href || false;
        var previousLinkAborted = false;

        if (AJAX.active === true) {
            // Cancel the old request if abortable, when the user requests
            // something else. Otherwise silently bail out, as there is already
            // a request well in progress.
            if (AJAX.xhr) {
                //In case of a link request, attempt aborting
                AJAX.xhr.abort();
                if(AJAX.xhr.status === 0 && AJAX.xhr.statusText === 'abort') {
                    //If aborted
                    AJAX.$msgbox = PMA_ajaxShowMessage(PMA_messages.strAbortedRequest);
                    AJAX.active = false;
                    AJAX.xhr = null;
                    previousLinkAborted = true;
                } else {
                    //If can't abort
                    return false;
                }
            } else {
                //In case submitting a form, don't attempt aborting
                return false;
            }
        }

        AJAX.source = $(this);

        $('html, body').animate({scrollTop: 0}, 'fast');

        var url = isLink ? href : $(this).attr('action');
        var params = 'ajax_request=true&ajax_page_request=true';
        if (! isLink) {
            params += '&' + $(this).serialize();
        }
        if (! (history && history.pushState)) {
            // Add a list of menu hashes that we have in the cache to the request
            params += PMA_MicroHistory.menus.getRequestParam();
        }

        if (AJAX._debug) {
            console.log("Loading: " + url); // no need to translate
        }

        if (isLink) {
            AJAX.active = true;
            AJAX.$msgbox = PMA_ajaxShowMessage();
            //Save reference for the new link request
            AJAX.xhr = $.get(url, params, AJAX.responseHandler);
            if (history && history.pushState) {
                var state = {
                    url : href
                };
                if (previousLinkAborted) {
                    //hack: there is already an aborted entry on stack
                    //so just modify the aborted one
                    history.replaceState(state, null, href);
                } else {
                    history.pushState(state, null, href);
                }
            }
        } else {
            /**
             * Manually fire the onsubmit event for the form, if any.
             * The event was saved in the jQuery data object by an onload
             * handler defined below. Workaround for bug #3583316
             */
            var onsubmit = $(this).data('onsubmit');
            // Submit the request if there is no onsubmit handler
            // or if it returns a value that evaluates to true
            if (typeof onsubmit !== 'function' || onsubmit.apply(this, [event])) {
                AJAX.active = true;
                AJAX.$msgbox = PMA_ajaxShowMessage();
                $.post(url, params, AJAX.responseHandler);
            }
        }
    },
    /**
     * Called after the request that was initiated by this.requestHandler()
     * has completed successfully or with a caught error. For completely
     * failed requests or requests with uncaught errors, see the .ajaxError
     * handler at the bottom of this file.
     *
     * To refer to self use 'AJAX', instead of 'this' as this function
     * is called in the jQuery context.
     *
     * @param object e Event data
     *
     * @return void
     */
    responseHandler: function (data) {
        if (typeof data === 'undefined' || data === null) {
            return;
        }
        if (typeof data.success != 'undefined' && data.success) {
            $('html, body').animate({scrollTop: 0}, 'fast');
            PMA_ajaxRemoveMessage(AJAX.$msgbox);

            if (data._redirect) {
                PMA_ajaxShowMessage(data._redirect, false);
                AJAX.active = false;
                AJAX.xhr = null;
                return;
            }

            AJAX.scriptHandler.reset(function () {
                if (data._reloadNavigation) {
                    PMA_reloadNavigation();
                }
                if (data._title) {
                    $('title').replaceWith(data._title);
                }
                if (data._menu) {
                    if (history && history.pushState) {
                        var state = {
                            url : data._selflink,
                            menu : data._menu
                        };
                        history.replaceState(state, null);
                        AJAX.handleMenu.replace(data._menu);
                    } else {
                        PMA_MicroHistory.menus.replace(data._menu);
                        PMA_MicroHistory.menus.add(data._menuHash, data._menu);
                    }
                } else if (data._menuHash) {
                    if (! (history && history.pushState)) {
                        PMA_MicroHistory.menus.replace(PMA_MicroHistory.menus.get(data._menuHash));
                    }
                }
                if (data._disableNaviSettings) {
                    PMA_disableNaviSettings();
                }
                else {
                    PMA_ensureNaviSettings(data._selflink);
                }

                // Remove all containers that may have
                // been added outside of #page_content
                $('body').children()
                    .not('#pma_navigation')
                    .not('#floating_menubar')
                    .not('#page_nav_icons')
                    .not('#page_content')
                    .not('#selflink')
                    .not('#pma_header')
                    .not('#pma_footer')
                    .not('#pma_demo')
                    .not('#pma_console_container')
                    .not('#prefs_autoload')
                    .remove();
                // Replace #page_content with new content
                if (data.message && data.message.length > 0) {
                    $('#page_content').replaceWith(
                        "<div id='page_content'>" + data.message + "</div>"
                    );
                    PMA_highlightSQL($('#page_content'));
                    checkNumberOfFields();
                }

                if (data._selflink) {
                    var source = data._selflink.split('?')[0];
                    //Check for faulty links
                    $selflink_replace = {
                        "import.php": "tbl_sql.php",
                        "tbl_chart.php": "sql.php",
                        "tbl_gis_visualization.php": "sql.php"
                    };
                    if ($selflink_replace[source]) {
                        var replacement = $selflink_replace[source];
                        data._selflink = data._selflink.replace(source, replacement);
                    }
                    $('#selflink').find('> a').attr('href', data._selflink);
                }
                if (data._params) {
                    PMA_commonParams.setAll(data._params);
                }
                if (data._scripts) {
                    AJAX.scriptHandler.load(data._scripts);
                }
                if (data._selflink && data._scripts && data._menuHash && data._params) {
                    if (! (history && history.pushState)) {
                        PMA_MicroHistory.add(
                            data._selflink,
                            data._scripts,
                            data._menuHash,
                            data._params,
                            AJAX.source.attr('rel')
                        );
                    }
                }
                if (data._displayMessage) {
                    $('#page_content').prepend(data._displayMessage);
                    PMA_highlightSQL($('#page_content'));
                }

                $('#pma_errors').remove();

                var msg = '';
                if(data._errSubmitMsg){
                    msg = data._errSubmitMsg;
                }
                if (data._errors) {
                    $('<div/>', {id : 'pma_errors'})
                        .insertAfter('#selflink')
                        .append(data._errors);
                    // bind for php error reporting forms (bottom)
                    $("#pma_ignore_errors_bottom").bind("click", function(e) {
                        e.preventDefault();
                        PMA_ignorePhpErrors();
                    });
                    $("#pma_ignore_all_errors_bottom").bind("click", function(e) {
                        e.preventDefault();
                        PMA_ignorePhpErrors(false);
                    });
                    // In case of 'sendErrorReport'='always'
                    // submit the hidden error reporting form.
                    if (data._sendErrorAlways == '1' &&
                        data._stopErrorReportLoop != '1'
                    ) {
                        $("#pma_report_errors_form").submit();
                        PMA_ajaxShowMessage(PMA_messages.phpErrorsBeingSubmitted, false);
                        $('html, body').animate({scrollTop:$(document).height()}, 'slow');
                    } else if (data._promptPhpErrors) {
                        // otherwise just prompt user if it is set so.
                        msg = msg + PMA_messages.phpErrorsFound;
                        // scroll to bottom where all the errors are displayed.
                        $('html, body').animate({scrollTop:$(document).height()}, 'slow');
                    }
                }
                PMA_ajaxShowMessage(msg, false);
                // bind for php error reporting forms (popup)
                $("#pma_ignore_errors_popup").bind("click", function() {
                    PMA_ignorePhpErrors();
                });
                $("#pma_ignore_all_errors_popup").bind("click", function() {
                    PMA_ignorePhpErrors(false);
                });

                if (typeof AJAX._callback === 'function') {
                    AJAX._callback.call();
                }
                AJAX._callback = function () {};
            });

        } else {
            PMA_ajaxShowMessage(data.error, false);
            AJAX.active = false;
            AJAX.xhr = null;
            PMA_handleRedirectAndReload(data);
            if (data.fieldWithError) {
                $(':input.error').removeClass("error");
                $('#'+data.fieldWithError).addClass("error");
            }
        }
    },
    /**
     * This object is in charge of downloading scripts,
     * keeping track of what's downloaded and firing
     * the onload event for them when the page is ready.
     */
    scriptHandler: {
        /**
         * @var array _scripts The list of files already downloaded
         */
        _scripts: [],
        /**
         * @var string _scriptsVersion version of phpMyAdmin from which the
         *                             scripts have been loaded
         */
        _scriptsVersion: null,
        /**
         * @var array _scriptsToBeLoaded The list of files that
         *                               need to be downloaded
         */
        _scriptsToBeLoaded: [],
        /**
         * @var array _scriptsToBeFired The list of files for which
         *                              to fire the onload event
         */
        _scriptsToBeFired: [],
        /**
         * Records that a file has been downloaded
         *
         * @param string file The filename
         * @param string fire Whether this file will be registering
         *                    onload/teardown events
         *
         * @return self For chaining
         */
        add: function (file, fire) {
            this._scripts.push(file);
            if (fire) {
                // Record whether to fire any events for the file
                // This is necessary to correctly tear down the initial page
                this._scriptsToBeFired.push(file);
            }
            return this;
        },
        /**
         * Download a list of js files in one request
         *
         * @param array files An array of filenames and flags
         *
         * @return void
         */
        load: function (files, callback) {
            var self = this;
            // Clear loaded scripts if they are from another version of phpMyAdmin.
            // Depends on common params being set before loading scripts in responseHandler
            if (self._scriptsVersion == null) {
            	self._scriptsVersion = PMA_commonParams.get('PMA_VERSION');
            } else if (self._scriptsVersion != PMA_commonParams.get('PMA_VERSION')) {
            	self._scripts = [];
            	self._scriptsVersion = PMA_commonParams.get('PMA_VERSION');
            }
            self._scriptsToBeLoaded = [];
            self._scriptsToBeFired = [];
            for (var i in files) {
                self._scriptsToBeLoaded.push(files[i].name);
                if (files[i].fire) {
                    self._scriptsToBeFired.push(files[i].name);
                }
            }
            // Generate a request string
            var request = [];
            var needRequest = false;
            for (var index in self._scriptsToBeLoaded) {
                var script = self._scriptsToBeLoaded[index];
                // Only for scripts that we don't already have
                if ($.inArray(script, self._scripts) == -1) {
                    needRequest = true;
                    this.add(script);
                    request.push("scripts%5B%5D=" + script);
                }
            }
            request.push("call_done=1");
            request.push("v=" + encodeURIComponent(PMA_commonParams.get('PMA_VERSION')));
            // Download the composite js file, if necessary
            if (needRequest) {
                this.appendScript("js/get_scripts.js.php?" + request.join("&"));
            } else {
                self.done(callback);
            }
        },
        /**
         * Called whenever all files are loaded
         *
         * @return void
         */
        done: function (callback) {
            if($.isFunction(callback)) {
                callback();
            }
            if (typeof ErrorReport !== 'undefined') {
                ErrorReport.wrap_global_functions();
            }
            for (var i in this._scriptsToBeFired) {
                AJAX.fireOnload(this._scriptsToBeFired[i]);
            }
            AJAX.active = false;
        },
        /**
         * Appends a script element to the head to load the scripts
         *
         * @return void
         */
        appendScript: function (url) {
            var head = document.head || document.getElementsByTagName('head')[0];
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = url;
            script.async = false;
            head.appendChild(script);
        },
        /**
         * Fires all the teardown event handlers for the current page
         * and rebinds all forms and links to the request handler
         *
         * @param function callback The callback to call after resetting
         *
         * @return void
         */
        reset: function (callback) {
            for (var i in this._scriptsToBeFired) {
                AJAX.fireTeardown(this._scriptsToBeFired[i]);
            }
            this._scriptsToBeFired = [];
            /**
             * Re-attach a generic event handler to clicks
             * on pages and submissions of forms
             */
            $(document).off('click', 'a').on('click', 'a', AJAX.requestHandler);
            $(document).off('submit', 'form').on('submit', 'form', AJAX.requestHandler);
            if (! (history && history.pushState)) {
                PMA_MicroHistory.update();
            }
            callback();
        }
    }
};

function markAllRows(container_id)
{

    $("#" + container_id).find("input:checkbox:enabled").prop('checked', true)
    .trigger("change")
    .parents("tr").addClass("marked");
    return true;
}

/**
 * marks all rows and selects its first checkbox inside the given element
 * the given element is usually a table or a div containing the table or tables
 *
 * @param container    DOM element
 */
function unMarkAllRows(container_id)
{

    $("#" + container_id).find("input:checkbox:enabled").prop('checked', false)
    .trigger("change")
    .parents("tr").removeClass("marked");
    return true;
}

/**
 * Checks/unchecks all checkbox in given container (f.e. a form, fieldset or div)
 *
 * @param string   container_id  the container id
 * @param boolean  state         new value for checkbox (true or false)
 * @return boolean  always true
 */
function setCheckboxes(container_id, state)
{

    $("#" + container_id).find("input:checkbox").prop('checked', state);
    return true;
} // end of the 'setCheckboxes()' function

/**
  * Checks/unchecks all options of a <select> element
  *
  * @param string   the form name
  * @param string   the element name
  * @param boolean  whether to check or to uncheck options
  *
  * @return boolean  always true
  */
function setSelectOptions(the_form, the_select, do_check)
{
    $("form[name='" + the_form + "'] select[name='" + the_select + "']").find("option").prop('selected', do_check);
    return true;
} // end of the 'setSelectOptions()' function

/**
 * Sets current value for query box.
 */
function setQuery(query)
{
    if (codemirror_editor) {
        codemirror_editor.setValue(query);
        codemirror_editor.focus();
    } else {
        document.sqlform.sql_query.value = query;
        document.sqlform.sql_query.focus();
    }
}

/**
 * Handles 'Simulate query' button on SQL query box.
 *
 * @return void
 */
function PMA_handleSimulateQueryButton()
{
    var update_re = new RegExp('^\\s*UPDATE\\s+((`[^`]+`)|([A-Za-z0-9_$]+))\\s+SET\\s', 'i');
    var delete_re = new RegExp('^\\s*DELETE\\s+FROM\\s', 'i');
    var query = '';

    if (codemirror_editor) {
        query = codemirror_editor.getValue();
    } else {
        query = $('#sqlquery').val();
    }

    var $simulateDml = $('#simulate_dml');
    if (update_re.test(query) || delete_re.test(query)) {
        if (! $simulateDml.length) {
            $('#button_submit_query')
            .before('<input type="button" id="simulate_dml"' +
                'tabindex="199" value="' +
                PMA_messages.strSimulateDML +
                '" />');
        }
    } else {
        if ($simulateDml.length) {
            $simulateDml.remove();
        }
    }
}

/**
  * Create quick sql statements.
  *
  */
function insertQuery(queryType)
{
    if (queryType == "clear") {
        setQuery('');
        return;
    } else if (queryType == "format") {
        if (codemirror_editor) {
            $('#querymessage').html(PMA_messages.strFormatting +
                '&nbsp;<img class="ajaxIcon" src="' +
                pmaThemeImage + 'ajax_clock_small.gif" alt="">');
            var href = 'db_sql_format.php';
            var params = {
                'ajax_request': true,
                'token': PMA_commonParams.get('token'),
                'sql': codemirror_editor.getValue()
            };
            $.ajax({
                type: 'POST',
                url: href,
                data: params,
                success: function (data) {
                    if (data.success) {
                        codemirror_editor.setValue(data.sql);
                    }
                    $('#querymessage').html('');
                }
            });
        }
        return;
    } else if (queryType == "saved") {
        if ($.cookie('auto_saved_sql')) {
            setQuery($.cookie('auto_saved_sql'));
        } else {
            PMA_ajaxShowMessage(PMA_messages.strNoAutoSavedQuery);
        }
        return;
    }

    var query = "";
    var myListBox = document.sqlform.dummy;
    var table = document.sqlform.table.value;

    if (myListBox.options.length > 0) {
        sql_box_locked = true;
        var columnsList = "";
        var valDis = "";
        var editDis = "";
        var NbSelect = 0;
        for (var i = 0; i < myListBox.options.length; i++) {
            NbSelect++;
            if (NbSelect > 1) {
                columnsList += ", ";
                valDis += ",";
                editDis += ",";
            }
            columnsList += myListBox.options[i].value;
            valDis += "[value-" + NbSelect + "]";
            editDis += myListBox.options[i].value + "=[value-" + NbSelect + "]";
        }
        if (queryType == "selectall") {
            query = "SELECT * FROM `" + table + "` WHERE 1";
        } else if (queryType == "select") {
            query = "SELECT " + columnsList + " FROM `" + table + "` WHERE 1";
        } else if (queryType == "insert") {
            query = "INSERT INTO `" + table + "`(" + columnsList + ") VALUES (" + valDis + ")";
        } else if (queryType == "update") {
            query = "UPDATE `" + table + "` SET " + editDis + " WHERE 1";
        } else if (queryType == "delete") {
            query = "DELETE FROM `" + table + "` WHERE 1";
        }
        setQuery(query);
        sql_box_locked = false;
    }
}


/**
  * Inserts multiple fields.
  *
  */
function insertValueQuery()
{
    var myQuery = document.sqlform.sql_query;
    var myListBox = document.sqlform.dummy;

    if (myListBox.options.length > 0) {
        sql_box_locked = true;
        var columnsList = "";
        var NbSelect = 0;
        for (var i = 0; i < myListBox.options.length; i++) {
            if (myListBox.options[i].selected) {
                NbSelect++;
                if (NbSelect > 1) {
                    columnsList += ", ";
                }
                columnsList += myListBox.options[i].value;
            }
        }

        /* CodeMirror support */
        if (codemirror_editor) {
            codemirror_editor.replaceSelection(columnsList);
        //IE support
        } else if (document.selection) {
            myQuery.focus();
            var sel = document.selection.createRange();
            sel.text = columnsList;
            document.sqlform.insert.focus();
        }
        //MOZILLA/NETSCAPE support
        else if (document.sqlform.sql_query.selectionStart || document.sqlform.sql_query.selectionStart == "0") {
            var startPos = document.sqlform.sql_query.selectionStart;
            var endPos = document.sqlform.sql_query.selectionEnd;
            var SqlString = document.sqlform.sql_query.value;

            myQuery.value = SqlString.substring(0, startPos) + columnsList + SqlString.substring(endPos, SqlString.length);
        } else {
            myQuery.value += columnsList;
        }
        sql_box_locked = false;
    }
}

/**
 * Updates the input fields for the parameters based on the query
 */
function updateQueryParameters() {

    if ($('#parameterized').is(':checked')) {
        var query = codemirror_editor ? codemirror_editor.getValue() : $('#sqlquery').val();

        var allParameters = query.match(/:[a-zA-Z0-9_]+/g);
         var parameters = [];
         // get unique parameters
         if (allParameters) {
             $.each(allParameters, function(i, parameter){
                 if ($.inArray(parameter, parameters) === -1) {
                     parameters.push(parameter);
                 }
             });
         }

         var $temp = $('<div />');
         $temp.append($('#parametersDiv').children());
         $('#parametersDiv').empty();

         $.each(parameters, function (i, parameter) {
             var paramName = parameter.substring(1);
             var $param = $temp.find('#paramSpan_' + paramName );
             if (! $param.length) {
                 $param = $('<span class="parameter" id="paramSpan_' + paramName + '" />');
                 $('<label for="param_' + paramName + '" />').text(parameter).appendTo($param);
                 $('<input type="text" name="parameters[' + parameter + ']" id="param_' + paramName + '" />').appendTo($param);
             }
             $('#parametersDiv').append($param);
         });
    } else {
        $('#parametersDiv').empty();
    }
}

/**
 * Add a date/time picker to each element that needs it
 * (only when jquery-ui-timepicker-addon.js is loaded)
 */
function addDateTimePicker() {
    if ($.timepicker !== undefined) {
        $('input.timefield, input.datefield, input.datetimefield').each(function () {

            var decimals = $(this).parent().attr('data-decimals');
            var type = $(this).parent().attr('data-type');

            var showMillisec = false;
            var showMicrosec = false;
            var timeFormat = 'HH:mm:ss';
            // check for decimal places of seconds
            if (decimals > 0 && type.indexOf('time') != -1){
                if (decimals > 3) {
                    showMillisec = true;
                    showMicrosec = true;
                    timeFormat = 'HH:mm:ss.lc';
                } else {
                    showMillisec = true;
                    timeFormat = 'HH:mm:ss.l';
                }
            }
            PMA_addDatepicker($(this), type, {
                showMillisec: showMillisec,
                showMicrosec: showMicrosec,
                timeFormat: timeFormat
            });
        });
    }
}

/**
  * Refresh/resize the WYSIWYG scratchboard
  */
function refreshLayout()
{
    var $elm = $('#pdflayout');
    var orientation = $('#orientation_opt').val();
    var paper = 'A4';
    var $paperOpt = $('#paper_opt');
    if ($paperOpt.length == 1) {
        paper = $paperOpt.val();
    }
    var posa = 'y';
    var posb = 'x';
    if (orientation == 'P') {
        posa = 'x';
        posb = 'y';
    }
    $elm.css('width', pdfPaperSize(paper, posa) + 'px');
    $elm.css('height', pdfPaperSize(paper, posb) + 'px');
}

/**
 * Initializes positions of elements.
 */
function TableDragInit() {
    $('.pdflayout_table').each(function () {
        var $this = $(this);
        var number = $this.data('number');
        var x = $('#c_table_' + number + '_x').val();
        var y = $('#c_table_' + number + '_y').val();
        $this.css('left', x + 'px');
        $this.css('top', y + 'px');
        /* Make elements draggable */
        $this.draggable({
            containment: "parent",
            drag: function (evt, ui) {
                var number = $this.data('number');
                $('#c_table_' + number + '_x').val(parseInt(ui.position.left, 10));
                $('#c_table_' + number + '_y').val(parseInt(ui.position.top, 10));
            }
        });
    });
}

/**
 * Resets drag and drop positions.
 */
function resetDrag() {
    $('.pdflayout_table').each(function () {
        var $this = $(this);
        var x = $this.data('x');
        var y = $this.data('y');
        $this.css('left', x + 'px');
        $this.css('top', y + 'px');
    });
}

/**
 * User schema handlers.
 */
$(function () {
    /* Move in scratchboard on manual change */
    $(document).on('change', '.position-change', function () {
        var $this = $(this);
        var $elm = $('#table_' + $this.data('number'));
        $elm.css($this.data('axis'), $this.val() + 'px');
    });
    /* Refresh on paper size/orientation change */
    $(document).on('change', '.paper-change', function () {
        var $elm = $('#pdflayout');
        if ($elm.css('visibility') == 'visible') {
            refreshLayout();
            TableDragInit();
        }
    });
    /* Show/hide the WYSIWYG scratchboard */
    $(document).on('click', '#toggle-dragdrop', function () {
        var $elm = $('#pdflayout');
        if ($elm.css('visibility') == 'hidden') {
            refreshLayout();
            TableDragInit();
            $elm.css('visibility', 'visible');
            $elm.css('display', 'block');
            $('#showwysiwyg').val('1');
        } else {
            $elm.css('visibility', 'hidden');
            $elm.css('display', 'none');
            $('#showwysiwyg').val('0');
        }
    });
    /* Reset scratchboard */
    $(document).on('click', '#reset-dragdrop', function () {
        resetDrag();
    });
});

/**
 * Returns paper sizes for a given format
 */
function pdfPaperSize(format, axis)
{
    switch (format.toUpperCase()) {
    case '4A0':
        if (axis == 'x') {
            return 4767.87;
        } else {
            return 6740.79;
        }
        break;
    case '2A0':
        if (axis == 'x') {
            return 3370.39;
        } else {
            return 4767.87;
        }
        break;
    case 'A0':
        if (axis == 'x') {
            return 2383.94;
        } else {
            return 3370.39;
        }
        break;
    case 'A1':
        if (axis == 'x') {
            return 1683.78;
        } else {
            return 2383.94;
        }
        break;
    case 'A2':
        if (axis == 'x') {
            return 1190.55;
        } else {
            return 1683.78;
        }
        break;
    case 'A3':
        if (axis == 'x') {
            return 841.89;
        } else {
            return 1190.55;
        }
        break;
    case 'A4':
        if (axis == 'x') {
            return 595.28;
        } else {
            return 841.89;
        }
        break;
    case 'A5':
        if (axis == 'x') {
            return 419.53;
        } else {
            return 595.28;
        }
        break;
    case 'A6':
        if (axis == 'x') {
            return 297.64;
        } else {
            return 419.53;
        }
        break;
    case 'A7':
        if (axis == 'x') {
            return 209.76;
        } else {
            return 297.64;
        }
        break;
    case 'A8':
        if (axis == 'x') {
            return 147.40;
        } else {
            return 209.76;
        }
        break;
    case 'A9':
        if (axis == 'x') {
            return 104.88;
        } else {
            return 147.40;
        }
        break;
    case 'A10':
        if (axis == 'x') {
            return 73.70;
        } else {
            return 104.88;
        }
        break;
    case 'B0':
        if (axis == 'x') {
            return 2834.65;
        } else {
            return 4008.19;
        }
        break;
    case 'B1':
        if (axis == 'x') {
            return 2004.09;
        } else {
            return 2834.65;
        }
        break;
    case 'B2':
        if (axis == 'x') {
            return 1417.32;
        } else {
            return 2004.09;
        }
        break;
    case 'B3':
        if (axis == 'x') {
            return 1000.63;
        } else {
            return 1417.32;
        }
        break;
    case 'B4':
        if (axis == 'x') {
            return 708.66;
        } else {
            return 1000.63;
        }
        break;
    case 'B5':
        if (axis == 'x') {
            return 498.90;
        } else {
            return 708.66;
        }
        break;
    case 'B6':
        if (axis == 'x') {
            return 354.33;
        } else {
            return 498.90;
        }
        break;
    case 'B7':
        if (axis == 'x') {
            return 249.45;
        } else {
            return 354.33;
        }
        break;
    case 'B8':
        if (axis == 'x') {
            return 175.75;
        } else {
            return 249.45;
        }
        break;
    case 'B9':
        if (axis == 'x') {
            return 124.72;
        } else {
            return 175.75;
        }
        break;
    case 'B10':
        if (axis == 'x') {
            return 87.87;
        } else {
            return 124.72;
        }
        break;
    case 'C0':
        if (axis == 'x') {
            return 2599.37;
        } else {
            return 3676.54;
        }
        break;
    case 'C1':
        if (axis == 'x') {
            return 1836.85;
        } else {
            return 2599.37;
        }
        break;
    case 'C2':
        if (axis == 'x') {
            return 1298.27;
        } else {
            return 1836.85;
        }
        break;
    case 'C3':
        if (axis == 'x') {
            return 918.43;
        } else {
            return 1298.27;
        }
        break;
    case 'C4':
        if (axis == 'x') {
            return 649.13;
        } else {
            return 918.43;
        }
        break;
    case 'C5':
        if (axis == 'x') {
            return 459.21;
        } else {
            return 649.13;
        }
        break;
    case 'C6':
        if (axis == 'x') {
            return 323.15;
        } else {
            return 459.21;
        }
        break;
    case 'C7':
        if (axis == 'x') {
            return 229.61;
        } else {
            return 323.15;
        }
        break;
    case 'C8':
        if (axis == 'x') {
            return 161.57;
        } else {
            return 229.61;
        }
        break;
    case 'C9':
        if (axis == 'x') {
            return 113.39;
        } else {
            return 161.57;
        }
        break;
    case 'C10':
        if (axis == 'x') {
            return 79.37;
        } else {
            return 113.39;
        }
        break;
    case 'RA0':
        if (axis == 'x') {
            return 2437.80;
        } else {
            return 3458.27;
        }
        break;
    case 'RA1':
        if (axis == 'x') {
            return 1729.13;
        } else {
            return 2437.80;
        }
        break;
    case 'RA2':
        if (axis == 'x') {
            return 1218.90;
        } else {
            return 1729.13;
        }
        break;
    case 'RA3':
        if (axis == 'x') {
            return 864.57;
        } else {
            return 1218.90;
        }
        break;
    case 'RA4':
        if (axis == 'x') {
            return 609.45;
        } else {
            return 864.57;
        }
        break;
    case 'SRA0':
        if (axis == 'x') {
            return 2551.18;
        } else {
            return 3628.35;
        }
        break;
    case 'SRA1':
        if (axis == 'x') {
            return 1814.17;
        } else {
            return 2551.18;
        }
        break;
    case 'SRA2':
        if (axis == 'x') {
            return 1275.59;
        } else {
            return 1814.17;
        }
        break;
    case 'SRA3':
        if (axis == 'x') {
            return 907.09;
        } else {
            return 1275.59;
        }
        break;
    case 'SRA4':
        if (axis == 'x') {
            return 637.80;
        } else {
            return 907.09;
        }
        break;
    case 'LETTER':
        if (axis == 'x') {
            return 612.00;
        } else {
            return 792.00;
        }
        break;
    case 'LEGAL':
        if (axis == 'x') {
            return 612.00;
        } else {
            return 1008.00;
        }
        break;
    case 'EXECUTIVE':
        if (axis == 'x') {
            return 521.86;
        } else {
            return 756.00;
        }
        break;
    case 'FOLIO':
        if (axis == 'x') {
            return 612.00;
        } else {
            return 936.00;
        }
        break;
    } // end switch

    return 0;
}

/**
 * Get checkbox for foreign key checks
 *
 * @return string
 */
function getForeignKeyCheckboxLoader() {
    var html = '';
    html    += '<div>';
    html    += '<div class="load-default-fk-check-value">';
    html    += PMA_getImage('ajax_clock_small.gif');
    html    += '</div>';
    html    += '</div>';
    return html;
}

function loadForeignKeyCheckbox() {
    // Load default foreign key check value
    var params = {
        'ajax_request': true,
        'token': PMA_commonParams.get('token'),
        'server': PMA_commonParams.get('server'),
        'get_default_fk_check_value': true
    };
    $.get('sql.php', params, function (data) {
        var html = '<input type="hidden" name="fk_checks" value="0" />' +
            '<input type="checkbox" name="fk_checks" id="fk_checks"' +
            (data.default_fk_check_value ? ' checked="checked"' : '') + ' />' +
            '<label for="fk_checks">' + PMA_messages.strForeignKeyCheck + '</label>';
        $('.load-default-fk-check-value').replaceWith(html);
    });
}

function getJSConfirmCommonParam(elem) {
    return {
        'is_js_confirmed' : 1,
        'ajax_request' : true,
        'fk_checks': $(elem).find('#fk_checks').is(':checked') ? 1 : 0
    };
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('click', "a.inline_edit_sql");
    $(document).off('click', "input#sql_query_edit_save");
    $(document).off('click', "input#sql_query_edit_discard");
    $('input.sqlbutton').unbind('click');
    if (codemirror_editor) {
        codemirror_editor.off('blur');
    } else {
        $(document).off('blur', '#sqlquery');
    }
    $(document).off('change', '#parameterized');
    $('#sqlquery').unbind('keydown');
    $('#sql_query_edit').unbind('keydown');

    if (codemirror_inline_editor) {
        // Copy the sql query to the text area to preserve it.
        $('#sql_query_edit').text(codemirror_inline_editor.getValue());
        $(codemirror_inline_editor.getWrapperElement()).unbind('keydown');
        codemirror_inline_editor.toTextArea();
        codemirror_inline_editor = false;
    }
    if (codemirror_editor) {
        $(codemirror_editor.getWrapperElement()).unbind('keydown');
    }
});

/**
 * Jquery Coding for inline editing SQL_QUERY
 */
AJAX.registerOnload('functions.js', function () {
    // If we are coming back to the page by clicking forward button
    // of the browser, bind the code mirror to inline query editor.
    bindCodeMirrorToInlineEditor();
    $(document).on('click', "a.inline_edit_sql", function () {
        if ($('#sql_query_edit').length) {
            // An inline query editor is already open,
            // we don't want another copy of it
            return false;
        }

        var $form = $(this).prev('form');
        var sql_query  = $form.find("input[name='sql_query']").val().trim();
        var $inner_sql = $(this).parent().prev().find('code.sql');
        var old_text   = $inner_sql.html();

        var new_content = "<textarea name=\"sql_query_edit\" id=\"sql_query_edit\">" + sql_query + "</textarea>\n";
        new_content    += getForeignKeyCheckboxLoader();
        new_content    += "<input type=\"submit\" id=\"sql_query_edit_save\" class=\"button btnSave\" value=\"" + PMA_messages.strGo + "\"/>\n";
        new_content    += "<input type=\"button\" id=\"sql_query_edit_discard\" class=\"button btnDiscard\" value=\"" + PMA_messages.strCancel + "\"/>\n";
        var $editor_area = $('div#inline_editor');
        if ($editor_area.length === 0) {
            $editor_area = $('<div id="inline_editor_outer"></div>');
            $editor_area.insertBefore($inner_sql);
        }
        $editor_area.html(new_content);
        loadForeignKeyCheckbox();
        $inner_sql.hide();

        bindCodeMirrorToInlineEditor();
        return false;
    });

    $(document).on('click', "input#sql_query_edit_save", function () {
        $(".success").hide();
        //hide already existing success message
        var sql_query;
        if (codemirror_inline_editor) {
            codemirror_inline_editor.save();
            sql_query = codemirror_inline_editor.getValue();
        } else {
            sql_query = $(this).parent().find('#sql_query_edit').val();
        }
        var fk_check = $(this).parent().find('#fk_checks').is(':checked');

        var $form = $("a.inline_edit_sql").prev('form');
        var $fake_form = $('<form>', {action: 'import.php', method: 'post'})
                .append($form.find("input[name=server], input[name=db], input[name=table], input[name=token]").clone())
                .append($('<input/>', {type: 'hidden', name: 'show_query', value: 1}))
                .append($('<input/>', {type: 'hidden', name: 'is_js_confirmed', value: 0}))
                .append($('<input/>', {type: 'hidden', name: 'sql_query', value: sql_query}))
                .append($('<input/>', {type: 'hidden', name: 'fk_checks', value: fk_check ? 1 : 0}));
        if (! checkSqlQuery($fake_form[0])) {
            return false;
        }
        $fake_form.appendTo($('body')).submit();
    });

    $(document).on('click', "input#sql_query_edit_discard", function () {
        var $divEditor = $('div#inline_editor_outer');
        $divEditor.siblings('code.sql').show();
        $divEditor.remove();
    });

    $('input.sqlbutton').click(function (evt) {
        insertQuery(evt.target.id);
        PMA_handleSimulateQueryButton();
        return false;
    });

    $(document).on('change', '#parameterized', updateQueryParameters);

    var $inputUsername = $('#input_username');
    if ($inputUsername) {
        if ($inputUsername.val() === '') {
            $inputUsername.focus();
        } else {
            $('#input_password').focus();
        }
    }
});

/**
 * "inputRead" event handler for CodeMirror SQL query editors for autocompletion
 */
function codemirrorAutocompleteOnInputRead(instance) {
    if (!sql_autocomplete_in_progress
        && (!instance.options.hintOptions.tables || !sql_autocomplete)) {

        if (!sql_autocomplete) {
            // Reset after teardown
            instance.options.hintOptions.tables = false;
            instance.options.hintOptions.defaultTable = '';

            sql_autocomplete_in_progress = true;

            var href = 'db_sql_autocomplete.php';
            var params = {
                'ajax_request': true,
                'token': PMA_commonParams.get('token'),
                'server': PMA_commonParams.get('server'),
                'db': PMA_commonParams.get('db'),
                'no_debug': true
            };

            var columnHintRender = function(elem, self, data) {
                $('<div class="autocomplete-column-name">')
                    .text(data.columnName)
                    .appendTo(elem);
                $('<div class="autocomplete-column-hint">')
                    .text(data.columnHint)
                    .appendTo(elem);
            };

            $.ajax({
                type: 'POST',
                url: href,
                data: params,
                success: function (data) {
                    if (data.success) {
                        var tables = $.parseJSON(data.tables);
                        sql_autocomplete_default_table = PMA_commonParams.get('table');
                        sql_autocomplete = [];
                        for (var table in tables) {
                            if (tables.hasOwnProperty(table)) {
                                var columns = tables[table];
                                table = {
                                    text: table,
                                    columns: []
                                };
                                for (var column in columns) {
                                    if (columns.hasOwnProperty(column)) {
                                        var displayText = columns[column].Type;
                                        if (columns[column].Key == 'PRI') {
                                            displayText += ' | Primary';
                                        } else if (columns[column].Key == 'UNI') {
                                            displayText += ' | Unique';
                                        }
                                        table.columns.push({
                                            text: column,
                                            displayText: column + " | " +  displayText,
                                            columnName: column,
                                            columnHint: displayText,
                                            render: columnHintRender
                                        });
                                    }
                                }
                            }
                            sql_autocomplete.push(table);
                        }
                        instance.options.hintOptions.tables = sql_autocomplete;
                        instance.options.hintOptions.defaultTable = sql_autocomplete_default_table;
                    }
                },
                complete: function () {
                    sql_autocomplete_in_progress = false;
                }
            });
        }
        else {
            instance.options.hintOptions.tables = sql_autocomplete;
            instance.options.hintOptions.defaultTable = sql_autocomplete_default_table;
        }
    }
    if (instance.state.completionActive) {
        return;
    }
    var cur = instance.getCursor();
    var token = instance.getTokenAt(cur);
    var string = '';
    if (token.string.match(/^[.`\w@]\w*$/)) {
        string = token.string;
    }
    if (string.length > 0) {
        CodeMirror.commands.autocomplete(instance);
    }
}

/**
 * Remove autocomplete information before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    sql_autocomplete = false;
    sql_autocomplete_default_table = '';
});

/**
 * Binds the CodeMirror to the text area used to inline edit a query.
 */
function bindCodeMirrorToInlineEditor() {
    var $inline_editor = $('#sql_query_edit');
    if ($inline_editor.length > 0) {
        if (typeof CodeMirror !== 'undefined') {
            var height = $inline_editor.css('height');
            codemirror_inline_editor = PMA_getSQLEditor($inline_editor);
            codemirror_inline_editor.getWrapperElement().style.height = height;
            codemirror_inline_editor.refresh();
            codemirror_inline_editor.focus();
            $(codemirror_inline_editor.getWrapperElement())
                .bind('keydown', catchKeypressesFromSqlInlineEdit);
        } else {
            $inline_editor
                .focus()
                .bind('keydown', catchKeypressesFromSqlInlineEdit);
        }
    }
}

function catchKeypressesFromSqlInlineEdit(event) {
    // ctrl-enter is 10 in chrome and ie, but 13 in ff
    if (event.ctrlKey && (event.keyCode == 13 || event.keyCode == 10)) {
        $("#sql_query_edit_save").trigger('click');
    }
}

/**
 * Adds doc link to single highlighted SQL element
 */
function PMA_doc_add($elm, params)
{
    if (typeof mysqli_doc_template == 'undefined') {
        return;
    }

    var url = PMA_sprintf(
        decodeURIComponent(mysqli_doc_template),
        params[0]
    );
    if (params.length > 1) {
        url += '#' + params[1];
    }
    var content = $elm.text();
    $elm.text('');
    $elm.append('<a target="mysqli_doc" class="cm-sql-doc" href="' + url + '">' + content + '</a>');
}

/**
 * Generates doc links for keywords inside highlighted SQL
 */
function PMA_doc_keyword(idx, elm)
{
    var $elm = $(elm);
    /* Skip already processed ones */
    if ($elm.find('a').length > 0) {
        return;
    }
    var keyword = $elm.text().toUpperCase();
    var $next = $elm.next('.cm-keyword');
    if ($next) {
        var next_keyword = $next.text().toUpperCase();
        var full = keyword + ' ' + next_keyword;

        var $next2 = $next.next('.cm-keyword');
        if ($next2) {
            var next2_keyword = $next2.text().toUpperCase();
            var full2 = full + ' ' + next2_keyword;
            if (full2 in mysqli_doc_keyword) {
                PMA_doc_add($elm, mysqli_doc_keyword[full2]);
                PMA_doc_add($next, mysqli_doc_keyword[full2]);
                PMA_doc_add($next2, mysqli_doc_keyword[full2]);
                return;
            }
        }
        if (full in mysqli_doc_keyword) {
            PMA_doc_add($elm, mysqli_doc_keyword[full]);
            PMA_doc_add($next, mysqli_doc_keyword[full]);
            return;
        }
    }
    if (keyword in mysqli_doc_keyword) {
        PMA_doc_add($elm, mysqli_doc_keyword[keyword]);
    }
}

/**
 * Generates doc links for builtins inside highlighted SQL
 */
function PMA_doc_builtin(idx, elm)
{
    var $elm = $(elm);
    var builtin = $elm.text().toUpperCase();
    if (builtin in mysqli_doc_builtin) {
        PMA_doc_add($elm, mysqli_doc_builtin[builtin]);
    }
}

/**
 * Higlights SQL using CodeMirror.
 */
function PMA_highlightSQL($base)
{
    var $elm = $base.find('code.sql');
    $elm.each(function () {
        var $sql = $(this);
        var $pre = $sql.find('pre');
        /* We only care about visible elements to avoid double processing */
        if ($pre.is(":visible")) {
            var $highlight = $('<div class="sql-highlight cm-s-default"></div>');
            $sql.append($highlight);
            if (typeof CodeMirror != 'undefined') {
                CodeMirror.runMode($sql.text(), 'text/x-mysql', $highlight[0]);
                $pre.hide();
                $highlight.find('.cm-keyword').each(PMA_doc_keyword);
                $highlight.find('.cm-builtin').each(PMA_doc_builtin);
            }
        }
    });
}

/**
 * Updates an element containing code.
 *
 * @param jQuery Object $base base element which contains the raw and the
 *                            highlighted code.
 *
 * @param string htmlValue    code in HTML format, displayed if code cannot be
 *                            highlighted
 *
 * @param string rawValue     raw code, used as a parameter for highlighter
 *
 * @return bool               whether content was updated or not
 */
function PMA_updateCode($base, htmlValue, rawValue)
{
    var $code = $base.find('code');
    if ($code.length == 0) {
        return false;
    }

    // Determines the type of the content and appropriate CodeMirror mode.
    var type = '', mode = '';
    if  ($code.hasClass('json')) {
        type = 'json';
        mode = 'application/json';
    } else if ($code.hasClass('sql')) {
        type = 'sql';
        mode = 'text/x-mysql';
    } else if ($code.hasClass('xml')) {
        type = 'xml';
        mode = 'application/xml';
    } else {
        return false;
    }

    // Element used to display unhighlighted code.
    var $notHighlighted = $('<pre>' + htmlValue + '</pre>');

    // Tries to highlight code using CodeMirror.
    if (typeof CodeMirror != 'undefined') {
        var $highlighted = $('<div class="' + type + '-highlight cm-s-default"></div>');
        CodeMirror.runMode(rawValue, mode, $highlighted[0]);
        $notHighlighted.hide();
        $code.html('').append($notHighlighted, $highlighted[0]);
    } else {
        $code.html('').append($notHighlighted);
    }

    return true;
}

/**
 * Show a message on the top of the page for an Ajax request
 *
 * Sample usage:
 *
 * 1) var $msg = PMA_ajaxShowMessage();
 * This will show a message that reads "Loading...". Such a message will not
 * disappear automatically and cannot be dismissed by the user. To remove this
 * message either the PMA_ajaxRemoveMessage($msg) function must be called or
 * another message must be show with PMA_ajaxShowMessage() function.
 *
 * 2) var $msg = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
 * This is a special case. The behaviour is same as above,
 * just with a different message
 *
 * 3) var $msg = PMA_ajaxShowMessage('The operation was successful');
 * This will show a message that will disappear automatically and it can also
 * be dismissed by the user.
 *
 * 4) var $msg = PMA_ajaxShowMessage('Some error', false);
 * This will show a message that will not disappear automatically, but it
 * can be dismissed by the user after he has finished reading it.
 *
 * @param string  message     string containing the message to be shown.
 *                              optional, defaults to 'Loading...'
 * @param mixed   timeout     number of milliseconds for the message to be visible
 *                              optional, defaults to 5000. If set to 'false', the
 *                              notification will never disappear
 * @return jQuery object       jQuery Element that holds the message div
 *                              this object can be passed to PMA_ajaxRemoveMessage()
 *                              to remove the notification
 */
function PMA_ajaxShowMessage(message, timeout)
{
    /**
     * @var self_closing Whether the notification will automatically disappear
     */
    var self_closing = true;
    /**
     * @var dismissable Whether the user will be able to remove
     *                  the notification by clicking on it
     */
    var dismissable = true;
    // Handle the case when a empty data.message is passed.
    // We don't want the empty message
    if (message === '') {
        return true;
    } else if (! message) {
        // If the message is undefined, show the default
        message = PMA_messages.strLoading;
        dismissable = false;
        self_closing = false;
    } else if (message == PMA_messages.strProcessingRequest) {
        // This is another case where the message should not disappear
        dismissable = false;
        self_closing = false;
    }
    // Figure out whether (or after how long) to remove the notification
    if (timeout === undefined) {
        timeout = 5000;
    } else if (timeout === false) {
        self_closing = false;
    }
    // Create a parent element for the AJAX messages, if necessary
    if ($('#loading_parent').length === 0) {
        $('<div id="loading_parent"></div>')
        .prependTo("#page_content");
    }
    // Update message count to create distinct message elements every time
    ajax_message_count++;
    // Remove all old messages, if any
    $("span.ajax_notification[id^=ajax_message_num]").remove();
    /**
     * @var    $retval    a jQuery object containing the reference
     *                    to the created AJAX message
     */
    var $retval = $(
            '<span class="ajax_notification" id="ajax_message_num_' +
            ajax_message_count +
            '"></span>'
    )
    .hide()
    .appendTo("#loading_parent")
    .html(message)
    .show();
    // If the notification is self-closing we should create a callback to remove it
    if (self_closing) {
        $retval
        .delay(timeout)
        .fadeOut('medium', function () {
            if ($(this).is(':data(tooltip)')) {
                $(this).tooltip('destroy');
            }
            // Remove the notification
            $(this).remove();
        });
    }
    // If the notification is dismissable we need to add the relevant class to it
    // and add a tooltip so that the users know that it can be removed
    if (dismissable) {
        $retval.addClass('dismissable').css('cursor', 'pointer');
        /**
         * Add a tooltip to the notification to let the user know that (s)he
         * can dismiss the ajax notification by clicking on it.
         */
        PMA_tooltip(
            $retval,
            'span',
            PMA_messages.strDismiss
        );
    }
    PMA_highlightSQL($retval);

    return $retval;
}

/**
 * Removes the message shown for an Ajax operation when it's completed
 *
 * @param jQuery object   jQuery Element that holds the notification
 *
 * @return nothing
 */
function PMA_ajaxRemoveMessage($this_msgbox)
{
    if ($this_msgbox !== undefined && $this_msgbox instanceof jQuery) {
        $this_msgbox
        .stop(true, true)
        .fadeOut('medium');
        if ($this_msgbox.is(':data(tooltip)')) {
            $this_msgbox.tooltip('destroy');
        } else {
            $this_msgbox.remove();
        }
    }
}

/**
 * Requests SQL for previewing before executing.
 *
 * @param jQuery Object $form Form containing query data
 *
 * @return void
 */
function PMA_previewSQL($form)
{
    var form_url = $form.attr('action');
    var form_data = $form.serialize() +
        '&do_save_data=1' +
        '&preview_sql=1' +
        '&ajax_request=1';
    var $msgbox = PMA_ajaxShowMessage();
    $.ajax({
        type: 'POST',
        url: form_url,
        data: form_data,
        success: function (response) {
            PMA_ajaxRemoveMessage($msgbox);
            if (response.success) {
                var $dialog_content = $('<div/>')
                    .append(response.sql_data);
                var button_options = {};
                button_options[PMA_messages.strClose] = function () {
                    $(this).dialog('close');
                };
                var $response_dialog = $dialog_content.dialog({
                    minWidth: 550,
                    maxHeight: 400,
                    modal: true,
                    buttons: button_options,
                    title: PMA_messages.strPreviewSQL,
                    close: function () {
                        $(this).remove();
                    },
                    open: function () {
                        // Pretty SQL printing.
                        PMA_highlightSQL($(this));
                    }
                });
            } else {
                PMA_ajaxShowMessage(response.message);
            }
        },
        error: function () {
            PMA_ajaxShowMessage(PMA_messages.strErrorProcessingRequest);
        }
    });
}

/**
 * check for reserved keyword column name
 *
 * @param jQuery Object $form Form
 *
 * @returns true|false
 */

function PMA_checkReservedWordColumns($form) {
    var is_confirmed = true;
    $.ajax({
        type: 'POST',
        url: "tbl_structure.php",
        data: $form.serialize() + '&reserved_word_check=1',
        success: function (data) {
            if (typeof data.success != 'undefined' && data.success === true) {
                is_confirmed = confirm(data.message);
            }
        },
        async:false
    });
    return is_confirmed;
}

// This event only need to be fired once after the initial page load
$(function () {
    /**
     * Allows the user to dismiss a notification
     * created with PMA_ajaxShowMessage()
     */
    $(document).on('click', 'span.ajax_notification.dismissable', function () {
        PMA_ajaxRemoveMessage($(this));
    });
    /**
     * The below two functions hide the "Dismiss notification" tooltip when a user
     * is hovering a link or button that is inside an ajax message
     */
    $(document).on('mouseover', 'span.ajax_notification a, span.ajax_notification button, span.ajax_notification input', function () {
        if ($(this).parents('span.ajax_notification').is(':data(tooltip)')) {
            $(this).parents('span.ajax_notification').tooltip('disable');
        }
    });
    $(document).on('mouseout', 'span.ajax_notification a, span.ajax_notification button, span.ajax_notification input', function () {
        if ($(this).parents('span.ajax_notification').is(':data(tooltip)')) {
            $(this).parents('span.ajax_notification').tooltip('enable');
        }
    });
});

/**
 * Hides/shows the "Open in ENUM/SET editor" message, depending on the data type of the column currently selected
 */
function PMA_showNoticeForEnum(selectElement)
{
    var enum_notice_id = selectElement.attr("id").split("_")[1];
    enum_notice_id += "_" + (parseInt(selectElement.attr("id").split("_")[2], 10) + 1);
    var selectedType = selectElement.val();
    if (selectedType == "ENUM" || selectedType == "SET") {
        $("p#enum_notice_" + enum_notice_id).show();
    } else {
        $("p#enum_notice_" + enum_notice_id).hide();
    }
}

/*
 * Creates a Profiling Chart with jqplot. Used in sql.js
 * and in server_status_monitor.js
 */
function PMA_createProfilingChartJqplot(target, data)
{
    return $.jqplot(target, [data],
        {
            seriesDefaults: {
                renderer: $.jqplot.PieRenderer,
                rendererOptions: {
                    showDataLabels:  true
                }
            },
            highlighter: {
                show: true,
                tooltipLocation: 'se',
                sizeAdjust: 0,
                tooltipAxes: 'pieref',
                useAxesFormatters: false,
                formatString: '%s, %.9Ps'
            },
            legend: {
                show: true,
                location: 'e',
                rendererOptions: {numberColumns: 2}
            },
            // from http://tango.freedesktop.org/Tango_Icon_Theme_Guidelines#Color_Palette
            seriesColors: [
                '#fce94f',
                '#fcaf3e',
                '#e9b96e',
                '#8ae234',
                '#729fcf',
                '#ad7fa8',
                '#ef2929',
                '#eeeeec',
                '#888a85',
                '#c4a000',
                '#ce5c00',
                '#8f5902',
                '#4e9a06',
                '#204a87',
                '#5c3566',
                '#a40000',
                '#babdb6',
                '#2e3436'
            ]
        }
    );
}

/**
 * Formats a profiling duration nicely (in us and ms time).
 * Used in server_status_monitor.js
 *
 * @param  integer    Number to be formatted, should be in the range of microsecond to second
 * @param  integer    Accuracy, how many numbers right to the comma should be
 * @return string     The formatted number
 */
function PMA_prettyProfilingNum(num, acc)
{
    if (!acc) {
        acc = 2;
    }
    acc = Math.pow(10, acc);
    if (num * 1000 < 0.1) {
        num = Math.round(acc * (num * 1000 * 1000)) / acc + 'µ';
    } else if (num < 0.1) {
        num = Math.round(acc * (num * 1000)) / acc + 'm';
    } else {
        num = Math.round(acc * num) / acc;
    }

    return num + 's';
}


/**
 * Formats a SQL Query nicely with newlines and indentation. Depends on Codemirror and MySQL Mode!
 *
 * @param string      Query to be formatted
 * @return string      The formatted query
 */
function PMA_SQLPrettyPrint(string)
{
    if (typeof CodeMirror == 'undefined') {
        return string;
    }

    var mode = CodeMirror.getMode({}, "text/x-mysql");
    var stream = new CodeMirror.StringStream(string);
    var state = mode.startState();
    var token, tokens = [];
    var output = '';
    var tabs = function (cnt) {
        var ret = '';
        for (var i = 0; i < 4 * cnt; i++) {
            ret += " ";
        }
        return ret;
    };

    // "root-level" statements
    var statements = {
        'select': ['select', 'from', 'on', 'where', 'having', 'limit', 'order by', 'group by'],
        'update': ['update', 'set', 'where'],
        'insert into': ['insert into', 'values']
    };
    // don't put spaces before these tokens
    var spaceExceptionsBefore = {';': true, ',': true, '.': true, '(': true};
    // don't put spaces after these tokens
    var spaceExceptionsAfter = {'.': true};

    // Populate tokens array
    var str = '';
    while (! stream.eol()) {
        stream.start = stream.pos;
        token = mode.token(stream, state);
        if (token !== null) {
            tokens.push([token, stream.current().toLowerCase()]);
        }
    }

    var currentStatement = tokens[0][1];

    if (! statements[currentStatement]) {
        return string;
    }
    // Holds all currently opened code blocks (statement, function or generic)
    var blockStack = [];
    // Holds the type of block from last iteration (the current is in blockStack[0])
    var previousBlock;
    // If a new code block is found, newBlock contains its type for one iteration and vice versa for endBlock
    var newBlock, endBlock;
    // How much to indent in the current line
    var indentLevel = 0;
    // Holds the "root-level" statements
    var statementPart, lastStatementPart = statements[currentStatement][0];

    blockStack.unshift('statement');

    // Iterate through every token and format accordingly
    for (var i = 0; i < tokens.length; i++) {
        previousBlock = blockStack[0];

        // New block => push to stack
        if (tokens[i][1] == '(') {
            if (i < tokens.length - 1 && tokens[i + 1][0] == 'statement-verb') {
                blockStack.unshift(newBlock = 'statement');
            } else if (i > 0 && tokens[i - 1][0] == 'builtin') {
                blockStack.unshift(newBlock = 'function');
            } else {
                blockStack.unshift(newBlock = 'generic');
            }
        } else {
            newBlock = null;
        }

        // Block end => pop from stack
        if (tokens[i][1] == ')') {
            endBlock = blockStack[0];
            blockStack.shift();
        } else {
            endBlock = null;
        }

        // A subquery is starting
        if (i > 0 && newBlock == 'statement') {
            indentLevel++;
            output += "\n" + tabs(indentLevel) + tokens[i][1] + ' ' + tokens[i + 1][1].toUpperCase() + "\n" + tabs(indentLevel + 1);
            currentStatement = tokens[i + 1][1];
            i++;
            continue;
        }

        // A subquery is ending
        if (endBlock == 'statement' && indentLevel > 0) {
            output += "\n" + tabs(indentLevel);
            indentLevel--;
        }

        // One less indentation for statement parts (from, where, order by, etc.) and a newline
        statementPart = statements[currentStatement].indexOf(tokens[i][1]);
        if (statementPart != -1) {
            if (i > 0) {
                output += "\n";
            }
            output += tabs(indentLevel) + tokens[i][1].toUpperCase();
            output += "\n" + tabs(indentLevel + 1);
            lastStatementPart = tokens[i][1];
        }
        // Normal indentation and spaces for everything else
        else {
            if (! spaceExceptionsBefore[tokens[i][1]] &&
               ! (i > 0 && spaceExceptionsAfter[tokens[i - 1][1]]) &&
               output.charAt(output.length - 1) != ' ') {
                output += " ";
            }
            if (tokens[i][0] == 'keyword') {
                output += tokens[i][1].toUpperCase();
            } else {
                output += tokens[i][1];
            }
        }

        // split columns in select and 'update set' clauses, but only inside statements blocks
        if ((lastStatementPart == 'select' || lastStatementPart == 'where'  || lastStatementPart == 'set') &&
            tokens[i][1] == ',' && blockStack[0] == 'statement') {

            output += "\n" + tabs(indentLevel + 1);
        }

        // split conditions in where clauses, but only inside statements blocks
        if (lastStatementPart == 'where' &&
            (tokens[i][1] == 'and' || tokens[i][1] == 'or' || tokens[i][1] == 'xor')) {

            if (blockStack[0] == 'statement') {
                output += "\n" + tabs(indentLevel + 1);
            }
            // Todo: Also split and or blocks in newlines & indentation++
            //if (blockStack[0] == 'generic')
             //   output += ...
        }
    }
    return output;
}

/**
 * jQuery function that uses jQueryUI's dialogs to confirm with user. Does not
 *  return a jQuery object yet and hence cannot be chained
 *
 * @param string      question
 * @param string      url           URL to be passed to the callbackFn to make
 *                                  an Ajax call to
 * @param function    callbackFn    callback to execute after user clicks on OK
 * @param function    openCallback  optional callback to run when dialog is shown
 */

jQuery.fn.PMA_confirm = function (question, url, callbackFn, openCallback) {
    var confirmState = PMA_commonParams.get('confirm');
    if (! confirmState) {
        // user does not want to confirm
        if ($.isFunction(callbackFn)) {
            callbackFn.call(this, url);
            return true;
        }
    }
    if (PMA_messages.strDoYouReally === '') {
        return true;
    }

    /**
     * @var    button_options  Object that stores the options passed to jQueryUI
     *                          dialog
     */
    var button_options = [
        {
            text: PMA_messages.strOK,
            'class': 'submitOK',
            click: function () {
                $(this).dialog("close");
                if ($.isFunction(callbackFn)) {
                    callbackFn.call(this, url);
                }
            }
        },
        {
            text: PMA_messages.strCancel,
            'class': 'submitCancel',
            click: function () {
                $(this).dialog("close");
            }
        }
    ];

    $('<div/>', {'id': 'confirm_dialog', 'title': PMA_messages.strConfirm})
    .prepend(question)
    .dialog({
        buttons: button_options,
        close: function () {
            $(this).remove();
        },
        open: openCallback,
        modal: true
    });
};

/**
 * jQuery function to sort a table's body after a new row has been appended to it.
 * Also fixes the even/odd classes of the table rows at the end.
 *
 * @param string      text_selector   string to select the sortKey's text
 *
 * @return jQuery Object for chaining purposes
 */
jQuery.fn.PMA_sort_table = function (text_selector) {
    return this.each(function () {

        /**
         * @var table_body  Object referring to the table's <tbody> element
         */
        var table_body = $(this);
        /**
         * @var rows    Object referring to the collection of rows in {@link table_body}
         */
        var rows = $(this).find('tr').get();

        //get the text of the field that we will sort by
        $.each(rows, function (index, row) {
            row.sortKey = $.trim($(row).find(text_selector).text().toLowerCase());
        });

        //get the sorted order
        rows.sort(function (a, b) {
            if (a.sortKey < b.sortKey) {
                return -1;
            }
            if (a.sortKey > b.sortKey) {
                return 1;
            }
            return 0;
        });

        //pull out each row from the table and then append it according to it's order
        $.each(rows, function (index, row) {
            $(table_body).append(row);
            row.sortKey = null;
        });

        //Re-check the classes of each row
        $(this).find('tr:odd')
        .removeClass('even').addClass('odd')
        .end()
        .find('tr:even')
        .removeClass('odd').addClass('even');
    });
};

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('submit', "#create_table_form_minimal.ajax");
    $(document).off('submit', "form.create_table_form.ajax");
    $(document).off('click', "form.create_table_form.ajax input[name=submit_num_fields]");
    $(document).off('keyup', "form.create_table_form.ajax input");
});

/**
 * jQuery coding for 'Create Table'.  Used on db_operations.php,
 * db_structure.php and db_tracking.php (i.e., wherever
 * libraries/display_create_table.lib.php is used)
 *
 * Attach Ajax Event handlers for Create Table
 */
AJAX.registerOnload('functions.js', function () {
    /**
     * Attach event handler for submission of create table form (save)
     */
    $(document).on('submit', "form.create_table_form.ajax", function (event) {
        event.preventDefault();

        /**
         * @var    the_form    object referring to the create table form
         */
        var $form = $(this);

        /*
         * First validate the form; if there is a problem, avoid submitting it
         *
         * checkTableEditForm() needs a pure element and not a jQuery object,
         * this is why we pass $form[0] as a parameter (the jQuery object
         * is actually an array of DOM elements)
         */

        if (checkTableEditForm($form[0], $form.find('input[name=orig_num_fields]').val())) {
            PMA_prepareForAjaxRequest($form);
            if (PMA_checkReservedWordColumns($form)) {
                PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
                //User wants to submit the form
                $.post($form.attr('action'), $form.serialize() + "&do_save_data=1", function (data) {
                    if (typeof data !== 'undefined' && data.success === true) {
                        $('#properties_message')
                         .removeClass('error')
                         .html('');
                        PMA_ajaxShowMessage(data.message);
                        // Only if the create table dialog (distinct panel) exists
                        var $createTableDialog = $("#create_table_dialog");
                        if ($createTableDialog.length > 0) {
                            $createTableDialog.dialog("close").remove();
                        }
                        $('#tableslistcontainer').before(data.formatted_sql);

                        /**
                         * @var tables_table    Object referring to the <tbody> element that holds the list of tables
                         */
                        var tables_table = $("#tablesForm").find("tbody").not("#tbl_summary_row");
                        // this is the first table created in this db
                        if (tables_table.length === 0) {
                            PMA_commonActions.refreshMain(
                                PMA_commonParams.get('opendb_url')
                            );
                        } else {
                            /**
                             * @var curr_last_row   Object referring to the last <tr> element in {@link tables_table}
                             */
                            var curr_last_row = $(tables_table).find('tr:last');
                            /**
                             * @var curr_last_row_index_string   String containing the index of {@link curr_last_row}
                             */
                            var curr_last_row_index_string = $(curr_last_row).find('input:checkbox').attr('id').match(/\d+/)[0];
                            /**
                             * @var curr_last_row_index Index of {@link curr_last_row}
                             */
                            var curr_last_row_index = parseFloat(curr_last_row_index_string);
                            /**
                             * @var new_last_row_index   Index of the new row to be appended to {@link tables_table}
                             */
                            var new_last_row_index = curr_last_row_index + 1;
                            /**
                             * @var new_last_row_id String containing the id of the row to be appended to {@link tables_table}
                             */
                            var new_last_row_id = 'checkbox_tbl_' + new_last_row_index;

                            data.new_table_string = data.new_table_string.replace(/checkbox_tbl_/, new_last_row_id);
                            //append to table
                            $(data.new_table_string)
                             .appendTo(tables_table);

                            //Sort the table
                            $(tables_table).PMA_sort_table('th');

                            // Adjust summary row
                            PMA_adjustTotals();
                        }

                        //Refresh navigation as a new table has been added
                        PMA_reloadNavigation();
                        // Redirect to table structure page on creation of new table
                        var params_12 = 'ajax_request=true&ajax_page_request=true';
                        if (! (history && history.pushState)) {
                            params_12 += PMA_MicroHistory.menus.getRequestParam();
                        }
                        tblStruct_url = 'tbl_structure.php?server=' + data._params.server +
                            '&db='+ data._params.db + '&token=' + data._params.token +
                            '&goto=db_structure.php&table=' + data._params.table + '';
                        $.get(tblStruct_url, params_12, AJAX.responseHandler);
                    } else {
                        PMA_ajaxShowMessage(
                            '<div class="error">' + data.error + '</div>',
                            false
                        );
                    }
                }); // end $.post()
            }
        } // end if (checkTableEditForm() )
    }); // end create table form (save)

    /**
     * Attach event handler for create table form (add fields)
     */
    $(document).on('click', "form.create_table_form.ajax input[name=submit_num_fields]", function (event) {
        event.preventDefault();
        /**
         * @var    the_form    object referring to the create table form
         */
        var $form = $(this).closest('form');

        if (!checkFormElementInRange(this.form, 'added_fields', PMA_messages.strLeastColumnError, 1)) {
            return;
        }

        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        PMA_prepareForAjaxRequest($form);

        //User wants to add more fields to the table
        $.post($form.attr('action'), $form.serialize() + "&submit_num_fields=1", function (data) {
            if (typeof data !== 'undefined' && data.success) {
                var $pageContent = $("#page_content");
                $pageContent.html(data.message);
                PMA_highlightSQL($pageContent);
                PMA_verifyColumnsProperties();
                PMA_hideShowConnection($('.create_table_form select[name=tbl_storage_engine]'));
                PMA_ajaxRemoveMessage($msgbox);
            } else {
                PMA_ajaxShowMessage(data.error);
            }
        }); //end $.post()
    }); // end create table form (add fields)

    $(document).on('keydown', "form.create_table_form.ajax input[name=added_fields]", function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            event.stopImmediatePropagation();
            $(this)
                .closest('form')
                .find('input[name=submit_num_fields]')
                .click();
        }
    });
    $("input[value=AUTO_INCREMENT]").change(function(){
        if (this.checked) {
            var col = /\d/.exec($(this).attr('name'));
            col = col[0];
            var $selectFieldKey = $('select[name="field_key[' + col + ']"]');
            if ($selectFieldKey.val() === 'none_'+col) {
                $selectFieldKey.val('primary_'+col).change();
            }
        }
    });
    $('body')
    .off('click', 'input.preview_sql')
    .on('click', 'input.preview_sql', function () {
        var $form = $(this).closest('form');
        PMA_previewSQL($form);
    });
});


/**
 * Validates the password field in a form
 *
 * @see    PMA_messages.strPasswordEmpty
 * @see    PMA_messages.strPasswordNotSame
 * @param  object $the_form The form to be validated
 * @return bool
 */
function PMA_checkPassword($the_form)
{
    // Did the user select 'no password'?
    if ($the_form.find('#nopass_1').is(':checked')) {
        return true;
    } else {
        var $pred = $the_form.find('#select_pred_password');
        if ($pred.length && ($pred.val() == 'none' || $pred.val() == 'keep')) {
            return true;
        }
    }

    var $password = $the_form.find('input[name=pma_pw]');
    var $password_repeat = $the_form.find('input[name=pma_pw2]');
    var alert_msg = false;

    if ($password.val() === '') {
        alert_msg = PMA_messages.strPasswordEmpty;
    } else if ($password.val() != $password_repeat.val()) {
        alert_msg = PMA_messages.strPasswordNotSame;
    }

    if (alert_msg) {
        alert(alert_msg);
        $password.val('');
        $password_repeat.val('');
        $password.focus();
        return false;
    }
    return true;
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('click', '#change_password_anchor.ajax');
});
/**
 * Attach Ajax event handlers for 'Change Password' on index.php
 */
AJAX.registerOnload('functions.js', function () {

    /**
     * Attach Ajax event handler on the change password anchor
     */
    $(document).on('click', '#change_password_anchor.ajax', function (event) {
        event.preventDefault();

        var $msgbox = PMA_ajaxShowMessage();

        /**
         * @var button_options  Object containing options to be passed to jQueryUI's dialog
         */
        var button_options = {};
        button_options[PMA_messages.strGo] = function () {

            event.preventDefault();

            /**
             * @var $the_form    Object referring to the change password form
             */
            var $the_form = $("#change_password_form");

            if (! PMA_checkPassword($the_form)) {
                return false;
            }

            /**
             * @var this_value  String containing the value of the submit button.
             * Need to append this for the change password form on Server Privileges
             * page to work
             */
            var this_value = $(this).val();

            var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
            $the_form.append('<input type="hidden" name="ajax_request" value="true" />');

            $.post($the_form.attr('action'), $the_form.serialize() + '&change_pw=' + this_value, function (data) {
                if (typeof data === 'undefined' || data.success !== true) {
                    PMA_ajaxShowMessage(data.error, false);
                    return;
                }

                var $pageContent = $("#page_content");
                $pageContent.prepend(data.message);
                PMA_highlightSQL($pageContent);
                $("#change_password_dialog").hide().remove();
                $("#edit_user_dialog").dialog("close").remove();
                PMA_ajaxRemoveMessage($msgbox);
            }); // end $.post()
        };

        button_options[PMA_messages.strCancel] = function () {
            $(this).dialog('close');
        };
        $.get($(this).attr('href'), {'ajax_request': true}, function (data) {
            if (typeof data === 'undefined' || !data.success) {
                PMA_ajaxShowMessage(data.error, false);
                return;
            }

            $('<div id="change_password_dialog"></div>')
                .dialog({
                    title: PMA_messages.strChangePassword,
                    width: 600,
                    close: function (ev, ui) {
                        $(this).remove();
                    },
                    buttons: button_options,
                    modal: true
                })
                .append(data.message);
            // for this dialog, we remove the fieldset wrapping due to double headings
            $("fieldset#fieldset_change_password")
                .find("legend").remove().end()
                .find("table.noclick").unwrap().addClass("some-margin")
                .find("input#text_pma_pw").focus();
            displayPasswordGenerateButton();
            $('#fieldset_change_password_footer').hide();
            PMA_ajaxRemoveMessage($msgbox);
            $('#change_password_form').bind('submit', function (e) {
                e.preventDefault();
                $(this)
                    .closest('.ui-dialog')
                    .find('.ui-dialog-buttonpane .ui-button')
                    .first()
                    .click();
            });
        }); // end $.get()
    }); // end handler for change password anchor
}); // end $() for Change Password

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('change', "select.column_type");
    $(document).off('change', "select.default_type");
    $(document).off('change', "select.virtuality");
    $(document).off('change', 'input.allow_null');
    $(document).off('change', '.create_table_form select[name=tbl_storage_engine]');
});
/**
 * Toggle the hiding/showing of the "Open in ENUM/SET editor" message when
 * the page loads and when the selected data type changes
 */
AJAX.registerOnload('functions.js', function () {
    // is called here for normal page loads and also when opening
    // the Create table dialog
    PMA_verifyColumnsProperties();
    //
    // needs on() to work also in the Create Table dialog
    $(document).on('change', "select.column_type", function () {
        PMA_showNoticeForEnum($(this));
    });
    $(document).on('change', "select.default_type", function () {
        PMA_hideShowDefaultValue($(this));
    });
    $(document).on('change', "select.virtuality", function () {
        PMA_hideShowExpression($(this));
    });
    $(document).on('change', 'input.allow_null', function () {
        PMA_validateDefaultValue($(this));
    });
    $(document).on('change', '.create_table_form select[name=tbl_storage_engine]', function () {
        PMA_hideShowConnection($(this));
    });
});

/**
 * If the chosen storage engine is FEDERATED show connection field. Hide otherwise
 *
 * @param $engine_selector storage engine selector
 */
function PMA_hideShowConnection($engine_selector)
{
    var $connection = $('.create_table_form input[name=connection]');
    var index = $connection.parent('td').index() + 1;
    var $labelTh = $connection.parents('tr').prev('tr').children('th:nth-child(' + index + ')');
    if ($engine_selector.val() != 'FEDERATED') {
        $connection
            .prop('disabled', true)
            .parent('td').hide();
        $labelTh.hide();
    } else {
        $connection
            .prop('disabled', false)
            .parent('td').show();
        $labelTh.show();
    }
}

/**
 * If the column does not allow NULL values, makes sure that default is not NULL
 */
function PMA_validateDefaultValue($null_checkbox)
{
    if (! $null_checkbox.prop('checked')) {
        var $default = $null_checkbox.closest('tr').find('.default_type');
        if ($default.val() == 'NULL') {
            $default.val('NONE');
        }
    }
}

/**
 * function to populate the input fields on picking a column from central list
 *
 * @param string  input_id input id of the name field for the column to be populated
 * @param integer offset of the selected column in central list of columns
 */
function autoPopulate(input_id, offset)
{
    var db = PMA_commonParams.get('db');
    var table = PMA_commonParams.get('table');
    input_id = input_id.substring(0, input_id.length - 1);
    $('#' + input_id + '1').val(central_column_list[db + '_' + table][offset].col_name);
    var col_type = central_column_list[db + '_' + table][offset].col_type.toUpperCase();
    $('#' + input_id + '2').val(col_type);
    var $input3 = $('#' + input_id + '3');
    $input3.val(central_column_list[db + '_' + table][offset].col_length);
    if(col_type === 'ENUM' || col_type === 'SET') {
        $input3.next().show();
    } else {
        $input3.next().hide();
    }
    var col_default = central_column_list[db + '_' + table][offset].col_default.toUpperCase();
    var $input4 = $('#' + input_id + '4');
    if (col_default !== '' && col_default !== 'NULL' && col_default !== 'CURRENT_TIMESTAMP') {
        $input4.val("USER_DEFINED");
        $input4.next().next().show();
        $input4.next().next().val(central_column_list[db + '_' + table][offset].col_default);
    } else {
        $input4.val(central_column_list[db + '_' + table][offset].col_default);
        $input4.next().next().hide();
    }
    $('#' + input_id + '5').val(central_column_list[db + '_' + table][offset].col_collation);
    var $input6 = $('#' + input_id + '6');
    $input6.val(central_column_list[db + '_' + table][offset].col_attribute);
    if(central_column_list[db + '_' + table][offset].col_extra === 'on update CURRENT_TIMESTAMP') {
        $input6.val(central_column_list[db + '_' + table][offset].col_extra);
    }
    if(central_column_list[db + '_' + table][offset].col_extra.toUpperCase() === 'AUTO_INCREMENT') {
        $('#' + input_id + '9').prop("checked",true).change();
    } else {
        $('#' + input_id + '9').prop("checked",false);
    }
    if(central_column_list[db + '_' + table][offset].col_isNull !== '0') {
        $('#' + input_id + '7').prop("checked",true);
    } else {
        $('#' + input_id + '7').prop("checked",false);
    }
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('click', "a.open_enum_editor");
    $(document).off('click', "input.add_value");
    $(document).off('click', "#enum_editor td.drop");
    $(document).off('click', 'a.central_columns_dialog');
});
/**
 * @var $enum_editor_dialog An object that points to the jQuery
 *                          dialog of the ENUM/SET editor
 */
var $enum_editor_dialog = null;
/**
 * Opens the ENUM/SET editor and controls its functions
 */
AJAX.registerOnload('functions.js', function () {
    $(document).on('click', "a.open_enum_editor", function () {
        // Get the name of the column that is being edited
        var colname = $(this).closest('tr').find('input:first').val();
        var title;
        var i;
        // And use it to make up a title for the page
        if (colname.length < 1) {
            title = PMA_messages.enum_newColumnVals;
        } else {
            title = PMA_messages.enum_columnVals.replace(
                /%s/,
                '"' + escapeHtml(decodeURIComponent(colname)) + '"'
            );
        }
        // Get the values as a string
        var inputstring = $(this)
            .closest('td')
            .find("input")
            .val();
        // Escape html entities
        inputstring = $('<div/>')
            .text(inputstring)
            .html();
        // Parse the values, escaping quotes and
        // slashes on the fly, into an array
        var values = [];
        var in_string = false;
        var curr, next, buffer = '';
        for (i = 0; i < inputstring.length; i++) {
            curr = inputstring.charAt(i);
            next = i == inputstring.length ? '' : inputstring.charAt(i + 1);
            if (! in_string && curr == "'") {
                in_string = true;
            } else if (in_string && curr == "\\" && next == "\\") {
                buffer += "&#92;";
                i++;
            } else if (in_string && next == "'" && (curr == "'" || curr == "\\")) {
                buffer += "&#39;";
                i++;
            } else if (in_string && curr == "'") {
                in_string = false;
                values.push(buffer);
                buffer = '';
            } else if (in_string) {
                buffer += curr;
            }
        }
        if (buffer.length > 0) {
            // The leftovers in the buffer are the last value (if any)
            values.push(buffer);
        }
        var fields = '';
        // If there are no values, maybe the user is about to make a
        // new list so we add a few for him/her to get started with.
        if (values.length === 0) {
            values.push('', '', '', '');
        }
        // Add the parsed values to the editor
        var drop_icon = PMA_getImage('b_drop.png');
        for (i = 0; i < values.length; i++) {
            fields += "<tr><td>" +
                   "<input type='text' value='" + values[i] + "'/>" +
                   "</td><td class='drop'>" +
                   drop_icon +
                   "</td></tr>";
        }
        /**
         * @var dialog HTML code for the ENUM/SET dialog
         */
        var dialog = "<div id='enum_editor'>" +
                   "<fieldset>" +
                    "<legend>" + title + "</legend>" +
                    "<p>" + PMA_getImage('s_notice.png') +
                    PMA_messages.enum_hint + "</p>" +
                    "<table class='values'>" + fields + "</table>" +
                    "</fieldset><fieldset class='tblFooters'>" +
                    "<table class='add'><tr><td>" +
                    "<div class='slider'></div>" +
                    "</td><td>" +
                    "<form><div><input type='submit' class='add_value' value='" +
                    PMA_sprintf(PMA_messages.enum_addValue, 1) +
                    "'/></div></form>" +
                    "</td></tr></table>" +
                    "<input type='hidden' value='" + // So we know which column's data is being edited
                    $(this).closest('td').find("input").attr("id") +
                    "' />" +
                    "</fieldset>" +
                    "</div>";
        /**
         * @var  Defines functions to be called when the buttons in
         * the buttonOptions jQuery dialog bar are pressed
         */
        var buttonOptions = {};
        buttonOptions[PMA_messages.strGo] = function () {
            // When the submit button is clicked,
            // put the data back into the original form
            var value_array = [];
            $(this).find(".values input").each(function (index, elm) {
                var val = elm.value.replace(/\\/g, '\\\\').replace(/'/g, "''");
                value_array.push("'" + val + "'");
            });
            // get the Length/Values text field where this value belongs
            var values_id = $(this).find("input[type='hidden']").val();
            $("input#" + values_id).val(value_array.join(","));
            $(this).dialog("close");
        };
        buttonOptions[PMA_messages.strClose] = function () {
            $(this).dialog("close");
        };
        // Show the dialog
        var width = parseInt(
            (parseInt($('html').css('font-size'), 10) / 13) * 340,
            10
        );
        if (! width) {
            width = 340;
        }
        $enum_editor_dialog = $(dialog).dialog({
            minWidth: width,
            maxHeight: 450,
            modal: true,
            title: PMA_messages.enum_editor,
            buttons: buttonOptions,
            open: function () {
                // Focus the "Go" button after opening the dialog
                $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:first').focus();
            },
            close: function () {
                $(this).remove();
            }
        });
        // slider for choosing how many fields to add
        $enum_editor_dialog.find(".slider").slider({
            animate: true,
            range: "min",
            value: 1,
            min: 1,
            max: 9,
            slide: function (event, ui) {
                $(this).closest('table').find('input[type=submit]').val(
                    PMA_sprintf(PMA_messages.enum_addValue, ui.value)
                );
            }
        });
        // Focus the slider, otherwise it looks nearly transparent
        $('a.ui-slider-handle').addClass('ui-state-focus');
        return false;
    });

    $(document).on('click', 'a.central_columns_dialog', function (e) {
        var href = "db_central_columns.php";
        var db = PMA_commonParams.get('db');
        var table = PMA_commonParams.get('table');
        var maxRows = $(this).data('maxrows');
        var pick = $(this).data('pick');
        if (pick !== false) {
            pick = true;
        }
        var params = {
            'ajax_request' : true,
            'token' : PMA_commonParams.get('token'),
            'server' : PMA_commonParams.get('server'),
            'db' : PMA_commonParams.get('db'),
            'cur_table' : PMA_commonParams.get('table'),
            'getColumnList':true
        };
        var colid = $(this).closest('td').find("input").attr("id");
        var fields = '';
        if (! (db + '_' + table in central_column_list)) {
            central_column_list.push(db + '_' + table);
            $.ajax({
                type: 'POST',
                url: href,
                data: params,
                success: function (data) {
                    central_column_list[db + '_' + table] = $.parseJSON(data.message);
                },
                async:false
            });
        }
        var i = 0;
        var list_size = central_column_list[db + '_' + table].length;
        var min = (list_size <= maxRows) ? list_size : maxRows;
        for (i = 0; i < min; i++) {

            fields += '<tr><td><div><span style="font-weight:bold">' +
                escapeHtml(central_column_list[db + '_' + table][i].col_name) +
                '</span><br><span style="color:gray">' + central_column_list[db + '_' + table][i].col_type;

            if (central_column_list[db + '_' + table][i].col_attribute !== '') {
                fields += '(' + escapeHtml(central_column_list[db + '_' + table][i].col_attribute) + ') ';
            }
            if (central_column_list[db + '_' + table][i].col_length !== '') {
                fields += '(' + escapeHtml(central_column_list[db + '_' + table][i].col_length) +') ';
            }
            fields += escapeHtml(central_column_list[db + '_' + table][i].col_extra) + '</span>' +
                '</div></td>';
            if (pick) {
                fields += '<td><input class="pick" style="width:100%" type="submit" value="' +
                    PMA_messages.pickColumn + '" onclick="autoPopulate(\'' + colid + '\',' + i + ')"/></td>';
            }
            fields += '</tr>';
        }
        var result_pointer = i;
        var search_in = '<input type="text" class="filter_rows" placeholder="' + PMA_messages.searchList + '">';
        if (fields === '') {
            fields = PMA_sprintf(PMA_messages.strEmptyCentralList, "'" + db + "'");
            search_in = '';
        }
        var seeMore = '';
        if (list_size > maxRows) {
            seeMore = "<fieldset class='tblFooters' style='text-align:center;font-weight:bold'>" +
                "<a href='#' id='seeMore'>" + PMA_messages.seeMore + "</a></fieldset>";
        }
        var central_columns_dialog = "<div style='max-height:400px'>" +
            "<fieldset>" +
            search_in +
            "<table id='col_list' style='width:100%' class='values'>" + fields + "</table>" +
            "</fieldset>" +
            seeMore +
            "</div>";

        var width = parseInt(
            (parseInt($('html').css('font-size'), 10) / 13) * 500,
            10
        );
        if (! width) {
            width = 500;
        }
        var buttonOptions = {};
        var $central_columns_dialog = $(central_columns_dialog).dialog({
            minWidth: width,
            maxHeight: 450,
            modal: true,
            title: PMA_messages.pickColumnTitle,
            buttons: buttonOptions,
            open: function () {
                $('#col_list').on("click", ".pick", function (){
                    $central_columns_dialog.remove();
                });
                $(".filter_rows").on("keyup", function () {
                    $.uiTableFilter($("#col_list"), $(this).val());
                });
                $("#seeMore").click(function() {
                    fields = '';
                    min = (list_size <= maxRows + result_pointer) ? list_size : maxRows + result_pointer;
                    for (i = result_pointer; i < min; i++) {

                        fields += '<tr><td><div><span style="font-weight:bold">' +
                            central_column_list[db + '_' + table][i].col_name +
                            '</span><br><span style="color:gray">' +
                            central_column_list[db + '_' + table][i].col_type;

                        if (central_column_list[db + '_' + table][i].col_attribute !== '') {
                            fields += '(' + central_column_list[db + '_' + table][i].col_attribute + ') ';
                        }
                        if (central_column_list[db + '_' + table][i].col_length !== '') {
                            fields += '(' + central_column_list[db + '_' + table][i].col_length + ') ';
                        }
                        fields += central_column_list[db + '_' + table][i].col_extra + '</span>' +
                            '</div></td>';
                        if (pick) {
                            fields += '<td><input class="pick" style="width:100%" type="submit" value="' +
                                PMA_messages.pickColumn + '" onclick="autoPopulate(\'' + colid + '\',' + i + ')"/></td>';
                        }
                        fields += '</tr>';
                    }
                    $("#col_list").append(fields);
                    result_pointer = i;
                    if (result_pointer === list_size) {
                        $('.tblFooters').hide();
                    }
                    return false;
                });
                $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:first').focus();
            },
            close: function () {
                $('#col_list').off("click", ".pick");
                $(".filter_rows").off("keyup");
                $(this).remove();
            }
        });
        return false;
    });

   // $(document).on('click', 'a.show_central_list',function(e) {

   // });
    // When "add a new value" is clicked, append an empty text field
    $(document).on('click', "input.add_value", function (e) {
        e.preventDefault();
        var num_new_rows = $enum_editor_dialog.find("div.slider").slider('value');
        while (num_new_rows--) {
            $enum_editor_dialog.find('.values')
                .append(
                    "<tr style='display: none;'><td>" +
                    "<input type='text' />" +
                    "</td><td class='drop'>" +
                    PMA_getImage('b_drop.png') +
                    "</td></tr>"
                )
                .find('tr:last')
                .show('fast');
        }
    });

    // Removes the specified row from the enum editor
    $(document).on('click', "#enum_editor td.drop", function () {
        $(this).closest('tr').hide('fast', function () {
            $(this).remove();
        });
    });
});

/**
 * Ensures indexes names are valid according to their type and, for a primary
 * key, lock index name to 'PRIMARY'
 * @param string   form_id  Variable which parses the form name as
 *                            the input
 * @return boolean  false    if there is no index form, true else
 */
function checkIndexName(form_id)
{
    if ($("#" + form_id).length === 0) {
        return false;
    }

    // Gets the elements pointers
    var $the_idx_name = $("#input_index_name");
    var $the_idx_choice = $("#select_index_choice");

    // Index is a primary key
    if ($the_idx_choice.find("option:selected").val() == 'PRIMARY') {
        $the_idx_name.val('PRIMARY');
        $the_idx_name.prop("disabled", true);
    }

    // Other cases
    else {
        if ($the_idx_name.val() == 'PRIMARY') {
            $the_idx_name.val("");
        }
        $the_idx_name.prop("disabled", false);
    }

    return true;
} // end of the 'checkIndexName()' function

AJAX.registerTeardown('functions.js', function () {
    $(document).off('click', '#index_frm input[type=submit]');
});
AJAX.registerOnload('functions.js', function () {
    /**
     * Handler for adding more columns to an index in the editor
     */
    $(document).on('click', '#index_frm input[type=submit]', function (event) {
        event.preventDefault();
        var rows_to_add = $(this)
            .closest('fieldset')
            .find('.slider')
            .slider('value');

        var tempEmptyVal = function () {
            $(this).val('');
        };

        var tempSetFocus = function () {
            if ($(this).find("option:selected").val() === '') {
                return true;
            }
            $(this).closest("tr").find("input").focus();
        };

        while (rows_to_add--) {
            var $indexColumns = $('#index_columns');
            var $newrow = $indexColumns
                .find('tbody > tr:first')
                .clone()
                .appendTo(
                    $indexColumns.find('tbody')
                );
            $newrow.find(':input').each(tempEmptyVal);
            // focus index size input on column picked
            $newrow.find('select').change(tempSetFocus);
        }
    });
});

function indexEditorDialog(url, title, callback_success, callback_failure)
{
    /*Remove the hidden dialogs if there are*/
    var $editIndexDialog = $('#edit_index_dialog');
    if ($editIndexDialog.length !== 0) {
        $editIndexDialog.remove();
    }
    var $div = $('<div id="edit_index_dialog"></div>');

    /**
     * @var button_options Object that stores the options
     *                     passed to jQueryUI dialog
     */
    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        /**
         * @var    the_form    object referring to the export form
         */
        var $form = $("#index_frm");
        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        PMA_prepareForAjaxRequest($form);
        //User wants to submit the form
        $.post($form.attr('action'), $form.serialize() + "&do_save_data=1", function (data) {
            var $sqlqueryresults = $(".sqlqueryresults");
            if ($sqlqueryresults.length !== 0) {
                $sqlqueryresults.remove();
            }
            if (typeof data !== 'undefined' && data.success === true) {
                PMA_ajaxShowMessage(data.message);
                var $resultQuery = $('.result_query');
                if ($resultQuery.length) {
                    $resultQuery.remove();
                }
                if (data.sql_query) {
                    $('<div class="result_query"></div>')
                        .html(data.sql_query)
                        .prependTo('#page_content');
                    PMA_highlightSQL($('#page_content'));
                }
                $(".result_query .notice").remove();
                $resultQuery.prepend(data.message);
                /*Reload the field form*/
                $("#table_index").remove();
                $("<div id='temp_div'><div>")
                    .append(data.index_table)
                    .find("#table_index")
                    .insertAfter("#index_header");
                var $editIndexDialog = $("#edit_index_dialog");
                if ($editIndexDialog.length > 0) {
                    $editIndexDialog.dialog("close");
                }
                $('div.no_indexes_defined').hide();
                if (callback_success) {
                    callback_success();
                }
                PMA_reloadNavigation();
            } else {
                var $temp_div = $("<div id='temp_div'><div>").append(data.error);
                var $error;
                if ($temp_div.find(".error code").length !== 0) {
                    $error = $temp_div.find(".error code").addClass("error");
                } else {
                    $error = $temp_div;
                }
                if (callback_failure) {
                    callback_failure();
                }
                PMA_ajaxShowMessage($error, false);
            }
        }); // end $.post()
    };
    button_options[PMA_messages.strPreviewSQL] = function () {
        // Function for Previewing SQL
        var $form = $('#index_frm');
        PMA_previewSQL($form);
    };
    button_options[PMA_messages.strCancel] = function () {
        $(this).dialog('close');
    };
    var $msgbox = PMA_ajaxShowMessage();
    $.get("tbl_indexes.php", url, function (data) {
        if (typeof data !== 'undefined' && data.success === false) {
            //in the case of an error, show the error message returned.
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);
            // Show dialog if the request was successful
            $div
            .append(data.message)
            .dialog({
                title: title,
                width: 450,
                height: 350,
                open: PMA_verifyColumnsProperties,
                modal: true,
                buttons: button_options,
                close: function () {
                    $(this).remove();
                }
            });
            $div.find('.tblFooters').remove();
            showIndexEditDialog($div);
        }
    }); // end $.get()
}

function showIndexEditDialog($outer)
{
    checkIndexType();
    checkIndexName("index_frm");
    var $indexColumns = $('#index_columns');
    $indexColumns.find('td').each(function () {
        $(this).css("width", $(this).width() + 'px');
    });
    $indexColumns.find('tbody').sortable({
        axis: 'y',
        containment: $indexColumns.find("tbody"),
        tolerance: 'pointer'
    });
    PMA_showHints($outer);
    PMA_init_slider();
    // Add a slider for selecting how many columns to add to the index
    $outer.find('.slider').slider({
        animate: true,
        value: 1,
        min: 1,
        max: 16,
        slide: function (event, ui) {
            $(this).closest('fieldset').find('input[type=submit]').val(
                PMA_sprintf(PMA_messages.strAddToIndex, ui.value)
            );
        }
    });
    $('div.add_fields').removeClass('hide');
    // focus index size input on column picked
    $outer.find('table#index_columns select').change(function () {
        if ($(this).find("option:selected").val() === '') {
            return true;
        }
        $(this).closest("tr").find("input").focus();
    });
    // Focus the slider, otherwise it looks nearly transparent
    $('a.ui-slider-handle').addClass('ui-state-focus');
    // set focus on index name input, if empty
    var input = $outer.find('input#input_index_name');
    input.val() || input.focus();
}

/**
 * Function to display tooltips that were
 * generated on the PHP side by PMA_Util::showHint()
 *
 * @param object $div a div jquery object which specifies the
 *                    domain for searching for tooltips. If we
 *                    omit this parameter the function searches
 *                    in the whole body
 **/
function PMA_showHints($div)
{
    if ($div === undefined || ! $div instanceof jQuery || $div.length === 0) {
        $div = $("body");
    }
    $div.find('.pma_hint').each(function () {
        PMA_tooltip(
            $(this).children('img'),
            'img',
            $(this).children('span').html()
        );
    });
}

AJAX.registerOnload('functions.js', function () {
    PMA_showHints();
});

function PMA_mainMenuResizerCallback() {
    // 5 px margin for jumping menu in Chrome
    return $(document.body).width() - 5;
}
// This must be fired only once after the initial page load
$(function () {
    // Initialise the menu resize plugin
    $('#topmenu').menuResizer(PMA_mainMenuResizerCallback);
    // register resize event
    $(window).resize(function () {
        $('#topmenu').menuResizer('resize');
    });
});

/**
 * Get the row number from the classlist (for example, row_1)
 */
function PMA_getRowNumber(classlist)
{
    return parseInt(classlist.split(/\s+row_/)[1], 10);
}

/**
 * Changes status of slider
 */
function PMA_set_status_label($element)
{
    var text;
    if ($element.css('display') == 'none') {
        text = '+ ';
    } else {
        text = '- ';
    }
    $element.closest('.slide-wrapper').prev().find('span').text(text);
}

/**
 * var  toggleButton  This is a function that creates a toggle
 *                    sliding button given a jQuery reference
 *                    to the correct DOM element
 */
var toggleButton = function ($obj) {
    // In rtl mode the toggle switch is flipped horizontally
    // so we need to take that into account
    var right;
    if ($('span.text_direction', $obj).text() == 'ltr') {
        right = 'right';
    } else {
        right = 'left';
    }
    /**
     *  var  h  Height of the button, used to scale the
     *          background image and position the layers
     */
    var h = $obj.height();
    $('img', $obj).height(h);
    $('table', $obj).css('bottom', h - 1);
    /**
     *  var  on   Width of the "ON" part of the toggle switch
     *  var  off  Width of the "OFF" part of the toggle switch
     */
    var on  = $('td.toggleOn', $obj).width();
    var off = $('td.toggleOff', $obj).width();
    // Make the "ON" and "OFF" parts of the switch the same size
    // + 2 pixels to avoid overflowed
    $('td.toggleOn > div', $obj).width(Math.max(on, off) + 2);
    $('td.toggleOff > div', $obj).width(Math.max(on, off) + 2);
    /**
     *  var  w  Width of the central part of the switch
     */
    var w = parseInt(($('img', $obj).height() / 16) * 22, 10);
    // Resize the central part of the switch on the top
    // layer to match the background
    $('table td:nth-child(2) > div', $obj).width(w);
    /**
     *  var  imgw    Width of the background image
     *  var  tblw    Width of the foreground layer
     *  var  offset  By how many pixels to move the background
     *               image, so that it matches the top layer
     */
    var imgw = $('img', $obj).width();
    var tblw = $('table', $obj).width();
    var offset = parseInt(((imgw - tblw) / 2), 10);
    // Move the background to match the layout of the top layer
    $obj.find('img').css(right, offset);
    /**
     *  var  offw    Outer width of the "ON" part of the toggle switch
     *  var  btnw    Outer width of the central part of the switch
     */
    var offw = $('td.toggleOff', $obj).outerWidth();
    var btnw = $('table td:nth-child(2)', $obj).outerWidth();
    // Resize the main div so that exactly one side of
    // the switch plus the central part fit into it.
    $obj.width(offw + btnw + 2);
    /**
     *  var  move  How many pixels to move the
     *             switch by when toggling
     */
    var move = $('td.toggleOff', $obj).outerWidth();
    // If the switch is initialized to the
    // OFF state we need to move it now.
    if ($('div.container', $obj).hasClass('off')) {
        if (right == 'right') {
            $('div.container', $obj).animate({'left': '-=' + move + 'px'}, 0);
        } else {
            $('div.container', $obj).animate({'left': '+=' + move + 'px'}, 0);
        }
    }
    // Attach an 'onclick' event to the switch
    $('div.container', $obj).click(function () {
        if ($(this).hasClass('isActive')) {
            return false;
        } else {
            $(this).addClass('isActive');
        }
        var $msg = PMA_ajaxShowMessage();
        var $container = $(this);
        var callback = $('span.callback', this).text();
        var operator, url, removeClass, addClass;
        // Perform the actual toggle
        if ($(this).hasClass('on')) {
            if (right == 'right') {
                operator = '-=';
            } else {
                operator = '+=';
            }
            url = $(this).find('td.toggleOff > span').text();
            removeClass = 'on';
            addClass = 'off';
        } else {
            if (right == 'right') {
                operator = '+=';
            } else {
                operator = '-=';
            }
            url = $(this).find('td.toggleOn > span').text();
            removeClass = 'off';
            addClass = 'on';
        }
        $.post(url, {'ajax_request': true}, function (data) {
            if (typeof data !== 'undefined' && data.success === true) {
                PMA_ajaxRemoveMessage($msg);
                $container
                .removeClass(removeClass)
                .addClass(addClass)
                .animate({'left': operator + move + 'px'}, function () {
                    $container.removeClass('isActive');
                });
                eval(callback);
            } else {
                PMA_ajaxShowMessage(data.error, false);
                $container.removeClass('isActive');
            }
        });
    });
};

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $('div.container').unbind('click');
});
/**
 * Initialise all toggle buttons
 */
AJAX.registerOnload('functions.js', function () {
    $('div.toggleAjax').each(function () {
        var $button = $(this).show();
        $button.find('img').each(function () {
            if (this.complete) {
                toggleButton($button);
            } else {
                $(this).load(function () {
                    toggleButton($button);
                });
            }
        });
    });
});

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('change', 'select.pageselector');
    $(document).off('click', 'a.formLinkSubmit');
    $('#update_recent_tables').unbind('ready');
    $('#sync_favorite_tables').unbind('ready');
});

AJAX.registerOnload('functions.js', function () {

    /**
     * Autosubmit page selector
     */
    $(document).on('change', 'select.pageselector', function (event) {
        event.stopPropagation();
        // Check where to load the new content
        if ($(this).closest("#pma_navigation").length === 0) {
            // For the main page we don't need to do anything,
            $(this).closest("form").submit();
        } else {
            // but for the navigation we need to manually replace the content
            PMA_navigationTreePagination($(this));
        }
    });

    /**
     * Load version information asynchronously.
     */
    if ($('li.jsversioncheck').length > 0) {
        $.getJSON('version_check.php', {'server' : PMA_commonParams.get('server')}, PMA_current_version);
    }

    if ($('#is_git_revision').length > 0) {
        setTimeout(PMA_display_git_revision, 10);
    }

    /**
     * Slider effect.
     */
    PMA_init_slider();

    /**
     * Enables the text generated by PMA_Util::linkOrButton() to be clickable
     */
    $(document).on('click', 'a.formLinkSubmit', function (e) {
        if (! $(this).hasClass('requireConfirm')) {
            submitFormLink($(this));
            return false;
        }
    });

    var $updateRecentTables = $('#update_recent_tables');
    if ($updateRecentTables.length) {
        $.get(
            $updateRecentTables.attr('href'),
            {no_debug: true},
            function (data) {
                if (typeof data !== 'undefined' && data.success === true) {
                    $('#pma_recent_list').html(data.list);
                }
            }
        );
    }

    // Sync favorite tables from localStorage to pmadb.
    if ($('#sync_favorite_tables').length) {
        $.ajax({
            url: $('#sync_favorite_tables').attr("href"),
            cache: false,
            type: 'POST',
            data: {
                favorite_tables: (isStorageSupported('localStorage') && typeof window.localStorage.favorite_tables !== 'undefined')
                    ? window.localStorage.favorite_tables
                    : '',
                no_debug: true
            },
            success: function (data) {
                // Update localStorage.
                if (isStorageSupported('localStorage')) {
                    window.localStorage.favorite_tables = data.favorite_tables;
                }
                $('#pma_favorite_list').html(data.list);
            }
        });
    }
}); // end of $()

/**
 * Submits the form placed in place of a link due to the excessive url length
 *
 * @param $link anchor
 * @returns {Boolean}
 */
function submitFormLink($link)
{
    if ($link.attr('href').indexOf('=') != -1) {
        var data = $link.attr('href').substr($link.attr('href').indexOf('#') + 1).split('=', 2);
        $link.parents('form').append('<input type="hidden" name="' + data[0] + '" value="' + data[1] + '"/>');
    }
    $link.parents('form').submit();
}

/**
 * Initializes slider effect.
 */
function PMA_init_slider()
{
    $('div.pma_auto_slider').each(function () {
        var $this = $(this);
        if ($this.data('slider_init_done')) {
            return;
        }
        var $wrapper = $('<div>', {'class': 'slide-wrapper'});
        $wrapper.toggle($this.is(':visible'));
        $('<a>', {href: '#' + this.id, "class": 'ajax'})
            .text($this.attr('title'))
            .prepend($('<span>'))
            .insertBefore($this)
            .click(function () {
                var $wrapper = $this.closest('.slide-wrapper');
                var visible = $this.is(':visible');
                if (!visible) {
                    $wrapper.show();
                }
                $this[visible ? 'hide' : 'show']('blind', function () {
                    $wrapper.toggle(!visible);
                    $wrapper.parent().toggleClass("print_ignore", visible);
                    PMA_set_status_label($this);
                });
                return false;
            });
        $this.wrap($wrapper);
        $this.removeAttr('title');
        PMA_set_status_label($this);
        $this.data('slider_init_done', 1);
    });
}

/**
 * Initializes slider effect.
 */
AJAX.registerOnload('functions.js', function () {
    PMA_init_slider();
});

/**
 * Restores sliders to the state they were in before initialisation.
 */
AJAX.registerTeardown('functions.js', function () {
    $('div.pma_auto_slider').each(function () {
        var $this = $(this);
        $this.removeData();
        $this.parent().replaceWith($this);
        $this.parent().children('a').remove();
    });
});

/**
 * Creates a message inside an object with a sliding effect
 *
 * @param msg    A string containing the text to display
 * @param $obj   a jQuery object containing the reference
 *                 to the element where to put the message
 *                 This is optional, if no element is
 *                 provided, one will be created below the
 *                 navigation links at the top of the page
 *
 * @return bool   True on success, false on failure
 */
function PMA_slidingMessage(msg, $obj)
{
    if (msg === undefined || msg.length === 0) {
        // Don't show an empty message
        return false;
    }
    if ($obj === undefined || ! $obj instanceof jQuery || $obj.length === 0) {
        // If the second argument was not supplied,
        // we might have to create a new DOM node.
        if ($('#PMA_slidingMessage').length === 0) {
            $('#page_content').prepend(
                '<span id="PMA_slidingMessage" ' +
                'style="display: inline-block;"></span>'
            );
        }
        $obj = $('#PMA_slidingMessage');
    }
    if ($obj.has('div').length > 0) {
        // If there already is a message inside the
        // target object, we must get rid of it
        $obj
        .find('div')
        .first()
        .fadeOut(function () {
            $obj
            .children()
            .remove();
            $obj
            .append('<div>' + msg + '</div>');
            // highlight any sql before taking height;
            PMA_highlightSQL($obj);
            $obj.find('div')
                .first()
                .hide();
            $obj
            .animate({
                height: $obj.find('div').first().height()
            })
            .find('div')
            .first()
            .fadeIn();
        });
    } else {
        // Object does not already have a message
        // inside it, so we simply slide it down
        $obj.width('100%')
            .html('<div>' + msg + '</div>');
        // highlight any sql before taking height;
        PMA_highlightSQL($obj);
        var h = $obj
            .find('div')
            .first()
            .hide()
            .height();
        $obj
        .find('div')
        .first()
        .css('height', 0)
        .show()
        .animate({
                height: h
            }, function () {
            // Set the height of the parent
            // to the height of the child
                $obj
                .height(
                    $obj
                    .find('div')
                    .first()
                    .height()
                );
            });
    }
    return true;
} // end PMA_slidingMessage()

/**
 * Attach CodeMirror2 editor to SQL edit area.
 */
AJAX.registerOnload('functions.js', function () {
    var $elm = $('#sqlquery');
    if ($elm.length > 0) {
        if (typeof CodeMirror != 'undefined') {
            codemirror_editor = PMA_getSQLEditor($elm);
            codemirror_editor.focus();
            codemirror_editor.on("blur", updateQueryParameters);
        } else {
            // without codemirror
            $elm.focus()
                .bind('blur', updateQueryParameters);
        }
    }
    PMA_highlightSQL($('body'));
});
AJAX.registerTeardown('functions.js', function () {
    if (codemirror_editor) {
        $('#sqlquery').text(codemirror_editor.getValue());
        codemirror_editor.toTextArea();
        codemirror_editor = false;
    }
});
AJAX.registerOnload('functions.js', function () {
    // initializes all lock-page elements lock-id and
    // val-hash data property
    $('#page_content form.lock-page textarea, ' +
            '#page_content form.lock-page input[type="text"], '+
            '#page_content form.lock-page input[type="number"], '+
            '#page_content form.lock-page select').each(function (i) {
        $(this).data('lock-id', i);
        // val-hash is the hash of default value of the field
        // so that it can be compared with new value hash
        // to check whether field was modified or not.
        $(this).data('val-hash', AJAX.hash($(this).val()));
    });

    // initializes lock-page elements (input types checkbox and radio buttons)
    // lock-id and val-hash data property
    $('#page_content form.lock-page input[type="checkbox"], ' +
            '#page_content form.lock-page input[type="radio"]').each(function (i) {
        $(this).data('lock-id', i);
        $(this).data('val-hash', AJAX.hash($(this).is(":checked")));
    });
});
/**
 * jQuery plugin to cancel selection in HTML code.
 */
(function ($) {
    $.fn.noSelect = function (p) { //no select plugin by Paulo P.Marinas
        var prevent = (p === null) ? true : p;
        var is_msie = navigator.userAgent.indexOf('MSIE') > -1 || !!window.navigator.userAgent.match(/Trident.*rv\:11\./);
        var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
        var is_safari = navigator.userAgent.indexOf("Safari") > -1;
        var is_opera = navigator.userAgent.indexOf("Presto") > -1;
        if (prevent) {
            return this.each(function () {
                if (is_msie || is_safari) {
                    $(this).bind('selectstart', function () {
                        return false;
                    });
                } else if (is_firefox) {
                    $(this).css('MozUserSelect', 'none');
                    $('body').trigger('focus');
                } else if (is_opera) {
                    $(this).bind('mousedown', function () {
                        return false;
                    });
                } else {
                    $(this).attr('unselectable', 'on');
                }
            });
        } else {
            return this.each(function () {
                if (is_msie || is_safari) {
                    $(this).unbind('selectstart');
                } else if (is_firefox) {
                    $(this).css('MozUserSelect', 'inherit');
                } else if (is_opera) {
                    $(this).unbind('mousedown');
                } else {
                    $(this).removeAttr('unselectable');
                }
            });
        }
    }; //end noSelect
})(jQuery);

/**
 * jQuery plugin to correctly filter input fields by value, needed
 * because some nasty values may break selector syntax
 */
(function ($) {
    $.fn.filterByValue = function (value) {
        return this.filter(function () {
            return $(this).val() === value;
        });
    };
})(jQuery);

/**
 * Return value of a cell in a table.
 */
function PMA_getCellValue(td) {
    var $td = $(td);
    if ($td.is('.null')) {
        return '';
    } else if ((! $td.is('.to_be_saved')
        || $td.is('.set'))
        && $td.data('original_data')
    ) {
        return $td.data('original_data');
    } else {
        return $td.text();
    }
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $(document).off('click', 'a.themeselect');
    $(document).off('change', '.autosubmit');
    $('a.take_theme').unbind('click');
});

AJAX.registerOnload('functions.js', function () {
    /**
     * Theme selector.
     */
    $(document).on('click', 'a.themeselect', function (e) {
        window.open(
            e.target,
            'themes',
            'left=10,top=20,width=510,height=350,scrollbars=yes,status=yes,resizable=yes'
            );
        return false;
    });

    /**
     * Automatic form submission on change.
     */
    $(document).on('change', '.autosubmit', function (e) {
        $(this).closest('form').submit();
    });

    /**
     * Theme changer.
     */
    $('a.take_theme').click(function (e) {
        var what = this.name;
        if (window.opener && window.opener.document.forms.setTheme.elements.set_theme) {
            window.opener.document.forms.setTheme.elements.set_theme.value = what;
            window.opener.document.forms.setTheme.submit();
            window.close();
            return false;
        }
        return true;
    });
});

/**
 * Print button
 */
function printPage()
{
    // Do print the page
    if (typeof(window.print) != 'undefined') {
        window.print();
    }
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function () {
    $('input#print').unbind('click');
    $(document).off('click', 'a.create_view.ajax');
    $(document).off('keydown', '#createViewDialog input, #createViewDialog select');
    $(document).off('change', '#fkc_checkbox');
});

AJAX.registerOnload('functions.js', function () {
    $('input#print').click(printPage);
    /**
     * Ajaxification for the "Create View" action
     */
    $(document).on('click', 'a.create_view.ajax', function (e) {
        e.preventDefault();
        PMA_createViewDialog($(this));
    });
    /**
     * Attach Ajax event handlers for input fields in the editor
     * and used to submit the Ajax request when the ENTER key is pressed.
     */
    if ($('#createViewDialog').length !== 0) {
        $(document).on('keydown', '#createViewDialog input, #createViewDialog select', function (e) {
            if (e.which === 13) { // 13 is the ENTER key
                e.preventDefault();

                // with preventing default, selection by <select> tag
                // was also prevented in IE
                $(this).blur();

                $(this).closest('.ui-dialog').find('.ui-button:first').click();
            }
        }); // end $(document).on()
    }

    syntaxHighlighter = PMA_getSQLEditor($('textarea[name="view[as]"]'));

});

function PMA_createViewDialog($this)
{
    var $msg = PMA_ajaxShowMessage();
    var syntaxHighlighter = null;
    $.get($this.attr('href') + '&ajax_request=1&ajax_dialog=1', function (data) {
        if (typeof data !== 'undefined' && data.success === true) {
            PMA_ajaxRemoveMessage($msg);
            var buttonOptions = {};
            buttonOptions[PMA_messages.strGo] = function () {
                if (typeof CodeMirror !== 'undefined') {
                    syntaxHighlighter.save();
                }
                $msg = PMA_ajaxShowMessage();
                $.get('view_create.php', $('#createViewDialog').find('form').serialize(), function (data) {
                    PMA_ajaxRemoveMessage($msg);
                    if (typeof data !== 'undefined' && data.success === true) {
                        $('#createViewDialog').dialog("close");
                        $('.result_query').html(data.message);
                        PMA_reloadNavigation();
                    } else {
                        PMA_ajaxShowMessage(data.error, false);
                    }
                });
            };
            buttonOptions[PMA_messages.strClose] = function () {
                $(this).dialog("close");
            };
            var $dialog = $('<div/>').attr('id', 'createViewDialog').append(data.message).dialog({
                width: 600,
                minWidth: 400,
                modal: true,
                buttons: buttonOptions,
                title: PMA_messages.strCreateView,
                close: function () {
                    $(this).remove();
                }
            });
            // Attach syntax highlighted editor
            syntaxHighlighter = PMA_getSQLEditor($dialog.find('textarea'));
            $('input:visible[type=text]', $dialog).first().focus();
        } else {
            PMA_ajaxShowMessage(data.error);
        }
    });
}

/**
 * Makes the breadcrumbs and the menu bar float at the top of the viewport
 */
$(function () {
    if ($("#floating_menubar").length && $('#PMA_disable_floating_menubar').length === 0) {
        var left = $('html').attr('dir') == 'ltr' ? 'left' : 'right';
        $("#floating_menubar")
            .css('margin-' + left, $('#pma_navigation').width() + $('#pma_navigation_resizer').width())
            .css(left, 0)
            .css({
                'position': 'fixed',
                'top': 0,
                'width': '100%',
                'z-index': 99
            })
            .append($('#serverinfo'))
            .append($('#topmenucontainer'));
        // Allow the DOM to render, then adjust the padding on the body
        setTimeout(function () {
            $('body').css(
                'padding-top',
                $('#floating_menubar').outerHeight(true)
            );
            $('#topmenu').menuResizer('resize');
        }, 4);
    }
});

/**
 * Scrolls the page to the top if clicking the serverinfo bar
 */
$(function () {
    $(document).delegate("#serverinfo, #goto_pagetop", "click", function (event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 'fast');
    });
});

var checkboxes_sel = "input.checkall:checkbox:enabled";
/**
 * Watches checkboxes in a form to set the checkall box accordingly
 */
var checkboxes_changed = function () {
    var $form = $(this.form);
    // total number of checkboxes in current form
    var total_boxes = $form.find(checkboxes_sel).length;
    // number of checkboxes checked in current form
    var checked_boxes = $form.find(checkboxes_sel + ":checked").length;
    var $checkall = $form.find("input.checkall_box");
    if (total_boxes == checked_boxes) {
        $checkall.prop({checked: true, indeterminate: false});
    }
    else if (checked_boxes > 0) {
        $checkall.prop({checked: true, indeterminate: true});
    }
    else {
        $checkall.prop({checked: false, indeterminate: false});
    }
};
$(document).on("change", checkboxes_sel, checkboxes_changed);

$(document).on("change", "input.checkall_box", function () {
    var is_checked = $(this).is(":checked");
    $(this.form).find(checkboxes_sel).prop("checked", is_checked)
    .parents("tr").toggleClass("marked", is_checked);
});

/**
 * Watches checkboxes in a sub form to set the sub checkall box accordingly
 */
var sub_checkboxes_changed = function () {
    var $form = $(this).parent().parent();
    // total number of checkboxes in current sub form
    var total_boxes = $form.find(checkboxes_sel).length;
    // number of checkboxes checked in current sub form
    var checked_boxes = $form.find(checkboxes_sel + ":checked").length;
    var $checkall = $form.find("input.sub_checkall_box");
    if (total_boxes == checked_boxes) {
        $checkall.prop({checked: true, indeterminate: false});
    }
    else if (checked_boxes > 0) {
        $checkall.prop({checked: true, indeterminate: true});
    }
    else {
        $checkall.prop({checked: false, indeterminate: false});
    }
};
$(document).on("change", checkboxes_sel + ", input.checkall_box:checkbox:enabled", sub_checkboxes_changed);

$(document).on("change", "input.sub_checkall_box", function () {
    var is_checked = $(this).is(":checked");
    var $form = $(this).parent().parent();
    $form.find(checkboxes_sel).prop("checked", is_checked)
    .parents("tr").toggleClass("marked", is_checked);
});

/**
 * Toggles row colors of a set of 'tr' elements starting from a given element
 *
 * @param $start Starting element
 */
function toggleRowColors($start)
{
    for (var $curr_row = $start; $curr_row.length > 0; $curr_row = $curr_row.next()) {
        if ($curr_row.hasClass('odd')) {
            $curr_row.removeClass('odd').addClass('even');
        } else if ($curr_row.hasClass('even')) {
            $curr_row.removeClass('even').addClass('odd');
        }
    }
}

/**
 * Formats a byte number to human-readable form
 *
 * @param bytes the bytes to format
 * @param optional subdecimals the number of digits after the point
 * @param optional pointchar the char to use as decimal point
 */
function formatBytes(bytes, subdecimals, pointchar) {
    if (!subdecimals) {
        subdecimals = 0;
    }
    if (!pointchar) {
        pointchar = '.';
    }
    var units = ['B', 'KiB', 'MiB', 'GiB'];
    for (var i = 0; bytes > 1024 && i < units.length; i++) {
        bytes /= 1024;
    }
    var factor = Math.pow(10, subdecimals);
    bytes = Math.round(bytes * factor) / factor;
    bytes = bytes.toString().split('.').join(pointchar);
    return bytes + ' ' + units[i];
}

AJAX.registerOnload('functions.js', function () {
    /**
     * Opens pma more themes link in themes browser, in new window instead of popup
     * This way, we don't break HTML validity
     */
    $("a._blank").prop("target", "_blank");
    /**
     * Reveal the login form to users with JS enabled
     * and focus the appropriate input field
     */
    var $loginform = $('#loginform');
    if ($loginform.length) {
        $loginform.find('.js-show').show();
        if ($('#input_username').val()) {
            $('#input_password').focus();
        } else {
            $('#input_username').focus();
        }
    }
});

/**
 * Dynamically adjust the width of the boxes
 * on the table and db operations pages
 */
(function () {
    function DynamicBoxes() {
        var $boxContainer = $('#boxContainer');
        if ($boxContainer.length) {
            var minWidth = $boxContainer.data('box-width');
            var viewport = $(window).width() - $('#pma_navigation').width();
            var slots = Math.floor(viewport / minWidth);
            $boxContainer.children()
            .each(function () {
                if (viewport < minWidth) {
                    $(this).width(minWidth);
                } else {
                    $(this).css('width', ((1 /  slots) * 100) + "%");
                }
            })
            .removeClass('clearfloat')
            .filter(':nth-child(' + slots + 'n+1)')
            .addClass('clearfloat');
        }
    }
    AJAX.registerOnload('functions.js', function () {
        DynamicBoxes();
    });
    $(function () {
        $(window).resize(DynamicBoxes);
    });
})();

/**
 * Formats timestamp for display
 */
function PMA_formatDateTime(date, seconds) {
    var result = $.datepicker.formatDate('yy-mm-dd', date);
    var timefmt = 'HH:mm';
    if (seconds) {
        timefmt = 'HH:mm:ss';
    }
    return result + ' ' + $.datepicker.formatTime(
        timefmt, {
            hour: date.getHours(),
            minute: date.getMinutes(),
            second: date.getSeconds()
        }
    );
}

/**
 * Check than forms have less fields than max allowed by PHP.
 */
function checkNumberOfFields() {
    if (typeof maxInputVars === 'undefined') {
        return false;
    }
    if (false === maxInputVars) {
        return false;
    }
    $('form').each(function() {
        var nbInputs = $(this).find(':input').length;
        if (nbInputs > maxInputVars) {
            var warning = PMA_sprintf(PMA_messages.strTooManyInputs, maxInputVars);
            PMA_ajaxShowMessage(warning);
            return false;
        }
        return true;
    });

    return true;
}

/**
 * Ignore the displayed php errors.
 * Simply removes the displayed errors.
 *
 * @param  clearPrevErrors whether to clear errors stored
 *             in $_SESSION['prev_errors'] at server
 *
 */
function PMA_ignorePhpErrors(clearPrevErrors){
    if (typeof(clearPrevErrors) === "undefined" ||
        clearPrevErrors === null
    ) {
        str = false;
    }
    // send AJAX request to error_report.php with send_error_report=0, exception_type=php & token.
    // It clears the prev_errors stored in session.
    if(clearPrevErrors){
        var $pmaReportErrorsForm = $('#pma_report_errors_form');
        $pmaReportErrorsForm.find('input[name="send_error_report"]').val(0); // change send_error_report to '0'
        $pmaReportErrorsForm.submit();
    }

    // remove displayed errors
    var $pmaErrors = $('#pma_errors');
    $pmaErrors.fadeOut( "slow");
    $pmaErrors.remove();
}

/**
 * checks whether browser supports web storage
 *
 * @param type the type of storage i.e. localStorage or sessionStorage
 *
 * @returns bool
 */
function isStorageSupported(type)
{
    try {
        window[type].setItem('PMATest', 'test');
        // Check whether key-value pair was set successfully
        if (window[type].getItem('PMATest') === 'test') {
            // Supported, remove test variable from storage
            window[type].removeItem('PMATest');
            return true;
        }
    } catch(error) {
        // Not supported
        PMA_ajaxShowMessage(PMA_messages.strNoLocalStorage, false);
    }
    return false;
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function(){
    $(document).off('keydown', 'form input, form textarea, form select');
});

AJAX.registerOnload('functions.js', function () {
    /**
     * Handle 'Ctrl/Alt + Enter' form submits
     */
    $('form input, form textarea, form select').on('keydown', function(e){
        if((e.ctrlKey && e.which == 13) || (e.altKey && e.which == 13)) {
            $form = $(this).closest('form');
            if (! $form.find('input[type="submit"]') ||
                ! $form.find('input[type="submit"]').click()
            ) {
                $form.submit();
            }
        }
    });
});

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('functions.js', function(){
    $(document).off('change', 'input[type=radio][name="pw_hash"]');
});

AJAX.registerOnload('functions.js', function(){
    /*
     * Display warning regarding SSL when sha256_password
     * method is selected
     * Used in user_password.php (Change Password link on index.php)
     */
    $(document).on("change", 'select#select_authentication_plugin_cp', function() {
        if (this.value === 'sha256_password') {
            $('#ssl_reqd_warning_cp').show();
        } else {
            $('#ssl_reqd_warning_cp').hide();
        }
    });
});
;

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * function used in or for navigation panel
 *
 * @package phpMyAdmin-Navigation
 */

/**
 * Loads child items of a node and executes a given callback
 *
 * @param isNode
 * @param $expandElem expander
 * @param callback    callback function
 *
 * @returns void
 */
function loadChildNodes(isNode, $expandElem, callback) {

    var $destination = null;
    var params = null;

    if (isNode) {
        if (!$expandElem.hasClass('expander')) {
            return;
        }
        $destination = $expandElem.closest('li');
        params = {
            aPath: $expandElem.find('span.aPath').text(),
            vPath: $expandElem.find('span.vPath').text(),
            pos: $expandElem.find('span.pos').text(),
            pos2_name: $expandElem.find('span.pos2_name').text(),
            pos2_value: $expandElem.find('span.pos2_value').text(),
            searchClause: '',
            searchClause2: ''
        };
        if ($expandElem.closest('ul').hasClass('search_results')) {
            params.searchClause = PMA_fastFilter.getSearchClause();
            params.searchClause2 = PMA_fastFilter.getSearchClause2($expandElem);
        }
    } else {
        $destination = $('#pma_navigation_tree_content');
        params = {
            aPath: $expandElem.attr('aPath'),
            vPath: $expandElem.attr('vPath'),
            pos: $expandElem.attr('pos'),
            pos2_name: '',
            pos2_value: '',
            searchClause: '',
            searchClause2: ''
        };
    }

    var url = $('#pma_navigation').find('a.navigation_url').attr('href');
    $.get(url, params, function (data) {
        if (typeof data !== 'undefined' && data.success === true) {
            $destination.find('div.list_container').remove(); // FIXME: Hack, there shouldn't be a list container there
            if (isNode) {
                $destination.append(data.message);
                $expandElem.addClass('loaded');
            } else {
                $destination.html(data.message);
                $destination.children()
                    .first()
                    .css({
                        border: '0px',
                        margin: '0em',
                        padding : '0em'
                    })
                    .slideDown('slow');
            }
            if (data._errors) {
                var $errors = $(data._errors);
                if ($errors.children().length > 0) {
                    $('#pma_errors').replaceWith(data._errors);
                }
            }
            if (callback && typeof callback == 'function') {
                callback(data);
            }
        } else if(data.redirect_flag == "1") {
            if (window.location.href.indexOf('?') === -1) {
                window.location.href += '?session_expired=1';
            } else {
                window.location.href += '&session_expired=1';
            }
            window.location.reload();
        } else {
            var $throbber = $expandElem.find('img.throbber');
            $throbber.hide();
            var $icon = $expandElem.find('img.ic_b_plus');
            $icon.show();
            PMA_ajaxShowMessage(data.error, false);
        }
    });
}

/**
 * Collapses a node in navigation tree.
 *
 * @param $expandElem expander
 *
 * @returns void
 */
function collapseTreeNode($expandElem) {
    var $children = $expandElem.closest('li').children('div.list_container');
    var $icon = $expandElem.find('img');
    if ($expandElem.hasClass('loaded')) {
        if ($icon.is('.ic_b_minus')) {
            $icon.removeClass('ic_b_minus').addClass('ic_b_plus');
            $children.slideUp('fast');
        }
    }
    $expandElem.blur();
    $children.promise().done(navTreeStateUpdate);
}

/**
 * Traverse the navigation tree backwards to generate all the actual
 * and virtual paths, as well as the positions in the pagination at
 * various levels, if necessary.
 *
 * @return Object
 */
function traverseNavigationForPaths() {
    var params = {
        pos: $('#pma_navigation_tree').find('div.dbselector select').val()
    };
    if ($('#navi_db_select').length) {
        return params;
    }
    var count = 0;
    $('#pma_navigation_tree').find('a.expander:visible').each(function () {
        if ($(this).find('img').is('.ic_b_minus') &&
            $(this).closest('li').find('div.list_container .ic_b_minus').length === 0
        ) {
            params['n' + count + '_aPath'] = $(this).find('span.aPath').text();
            params['n' + count + '_vPath'] = $(this).find('span.vPath').text();

            var pos2_name = $(this).find('span.pos2_name').text();
            if (! pos2_name) {
                pos2_name = $(this)
                    .parent()
                    .parent()
                    .find('span.pos2_name:last')
                    .text();
            }
            var pos2_value = $(this).find('span.pos2_value').text();
            if (! pos2_value) {
                pos2_value = $(this)
                    .parent()
                    .parent()
                    .find('span.pos2_value:last')
                    .text();
            }

            params['n' + count + '_pos2_name'] = pos2_name;
            params['n' + count + '_pos2_value'] = pos2_value;

            params['n' + count + '_pos3_name'] = $(this).find('span.pos3_name').text();
            params['n' + count + '_pos3_value'] = $(this).find('span.pos3_value').text();
            count++;
        }
    });
    return params;
}

/**
 * Executed on page load
 */
$(function () {
    if (! $('#pma_navigation').length) {
        // Don't bother running any code if the navigation is not even on the page
        return;
    }

    // Do not let the page reload on submitting the fast filter
    $(document).on('submit', '.fast_filter', function (event) {
        event.preventDefault();
    });

    // Fire up the resize handlers
    new ResizeHandler();

    /**
     * opens/closes (hides/shows) tree elements
     * loads data via ajax
     */
    $(document).on('click', '#pma_navigation_tree a.expander', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var $icon = $(this).find('img');
        if ($icon.is('.ic_b_plus')) {
            expandTreeNode($(this));
        } else {
            collapseTreeNode($(this));
        }
    });

    /**
     * Register event handler for click on the reload
     * navigation icon at the top of the panel
     */
    $(document).on('click', '#pma_navigation_reload', function (event) {
        event.preventDefault();
        // reload icon object
        var $icon = $(this).find('img');
        // source of the hidden throbber icon
        var icon_throbber_src = $('#pma_navigation').find('.throbber').attr('src');
        // source of the reload icon
        var icon_reload_src = $icon.attr('src');
        // replace the source of the reload icon with the one for throbber
        $icon.attr('src', icon_throbber_src);
        PMA_reloadNavigation();
        // after one second, put back the reload icon
        setTimeout(function () {
            $icon.attr('src', icon_reload_src);
        }, 1000);
    });

    $(document).on("change", '#navi_db_select',  function (event) {
        if (! $(this).val()) {
            PMA_commonParams.set('db', '');
            PMA_reloadNavigation();
        }
        $(this).closest('form').trigger('submit');
    });

    /**
     * Register event handler for click on the collapse all
     * navigation icon at the top of the navigation tree
     */
    $(document).on('click', '#pma_navigation_collapse', function (event) {
        event.preventDefault();
        $('#pma_navigation_tree').find('a.expander').each(function() {
            var $icon = $(this).find('img');
            if ($icon.is('.ic_b_minus')) {
                $(this).click();
            }
        });
    });

    /**
     * Register event handler to toggle
     * the 'link with main panel' icon on mouseenter.
     */
    $(document).on('mouseenter', '#pma_navigation_sync', function (event) {
        event.preventDefault();
        var synced = $('#pma_navigation_tree').hasClass('synced');
        var $img = $('#pma_navigation_sync').children('img');
        if (synced) {
            $img.removeClass('ic_s_link').addClass('ic_s_unlink');
        } else {
            $img.removeClass('ic_s_unlink').addClass('ic_s_link');
        }
    });

    /**
     * Register event handler to toggle
     * the 'link with main panel' icon on mouseout.
     */
    $(document).on('mouseout', '#pma_navigation_sync', function (event) {
        event.preventDefault();
        var synced = $('#pma_navigation_tree').hasClass('synced');
        var $img = $('#pma_navigation_sync').children('img');
        if (synced) {
            $img.removeClass('ic_s_unlink').addClass('ic_s_link');
        } else {
            $img.removeClass('ic_s_link').addClass('ic_s_unlink');
        }
    });

    /**
     * Register event handler to toggle
     * the linking with main panel behavior
     */
    $(document).on('click', '#pma_navigation_sync', function (event) {
        event.preventDefault();
        var synced = $('#pma_navigation_tree').hasClass('synced');
        var $img = $('#pma_navigation_sync').children('img');
        if (synced) {
            $img
                .removeClass('ic_s_unlink')
                .addClass('ic_s_link')
                .attr('alt', PMA_messages.linkWithMain)
                .attr('title', PMA_messages.linkWithMain);
            $('#pma_navigation_tree')
                .removeClass('synced')
                .find('li.selected')
                .removeClass('selected');
        } else {
            $img
                .removeClass('ic_s_link')
                .addClass('ic_s_unlink')
                .attr('alt', PMA_messages.unlinkWithMain)
                .attr('title', PMA_messages.unlinkWithMain);
            $('#pma_navigation_tree').addClass('synced');
            PMA_showCurrentNavigation();
        }
    });

    /**
     * Bind all "fast filter" events
     */
    $(document).on('click', '#pma_navigation_tree li.fast_filter span', PMA_fastFilter.events.clear);
    $(document).on('focus', '#pma_navigation_tree li.fast_filter input.searchClause', PMA_fastFilter.events.focus);
    $(document).on('blur', '#pma_navigation_tree li.fast_filter input.searchClause', PMA_fastFilter.events.blur);
    $(document).on('keyup', '#pma_navigation_tree li.fast_filter input.searchClause', PMA_fastFilter.events.keyup);
    $(document).on('mouseover', '#pma_navigation_tree li.fast_filter input.searchClause', PMA_fastFilter.events.mouseover);

    /**
     * Ajax handler for pagination
     */
    $(document).on('click', '#pma_navigation_tree div.pageselector a.ajax', function (event) {
        event.preventDefault();
        PMA_navigationTreePagination($(this));
    });

    /**
     * Node highlighting
     */
    $(document).on(
        'mouseover',
        '#pma_navigation_tree.highlight li:not(.fast_filter)',
        function () {
            if ($('li:visible', this).length === 0) {
                $(this).addClass('activePointer');
            }
        }
    );
    $(document).on(
        'mouseout',
        '#pma_navigation_tree.highlight li:not(.fast_filter)',
        function () {
            $(this).removeClass('activePointer');
        }
    );

    /** Create a Routine, Trigger or Event */
    $(document).on('click', 'li.new_procedure a.ajax, li.new_function a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('routine');
        dialog.editorDialog(1, $(this));
    });
    $(document).on('click', 'li.new_trigger a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('trigger');
        dialog.editorDialog(1, $(this));
    });
    $(document).on('click', 'li.new_event a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('event');
        dialog.editorDialog(1, $(this));
    });

    /** Edit Routines, Triggers or Events */
    $(document).on('click', 'li.procedure > a.ajax, li.function > a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('routine');
        dialog.editorDialog(0, $(this));
    });
    $(document).on('click', 'li.trigger > a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('trigger');
        dialog.editorDialog(0, $(this));
    });
    $(document).on('click', 'li.event > a.ajax', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('event');
        dialog.editorDialog(0, $(this));
    });

    /** Execute Routines */
    $(document).on('click', 'li.procedure div a.ajax img,' +
        ' li.function div a.ajax img', function (event) {
        event.preventDefault();
        var dialog = new RTE.object('routine');
        dialog.executeDialog($(this).parent());
    });
    /** Export Triggers and Events */
    $(document).on('click', 'li.trigger div:eq(1) a.ajax img,' +
        ' li.event div:eq(1) a.ajax img', function (event) {
        event.preventDefault();
        var dialog = new RTE.object();
        dialog.exportDialog($(this).parent());
    });

    /** New index */
    $(document).on('click', '#pma_navigation_tree li.new_index a.ajax', function (event) {
        event.preventDefault();
        var url = $(this).attr('href').substr(
            $(this).attr('href').indexOf('?') + 1
        ) + '&ajax_request=true';
        var title = PMA_messages.strAddIndex;
        indexEditorDialog(url, title);
    });

    /** Edit index */
    $(document).on('click', 'li.index a.ajax', function (event) {
        event.preventDefault();
        var url = $(this).attr('href').substr(
            $(this).attr('href').indexOf('?') + 1
        ) + '&ajax_request=true';
        var title = PMA_messages.strEditIndex;
        indexEditorDialog(url, title);
    });

    /** New view */
    $(document).on('click', 'li.new_view a.ajax', function (event) {
        event.preventDefault();
        PMA_createViewDialog($(this));
    });

    /** Hide navigation tree item */
    $(document).on('click', 'a.hideNavItem.ajax', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href') + '&ajax_request=true',
            success: function (data) {
                if (typeof data !== 'undefined' && data.success === true) {
                    PMA_reloadNavigation();
                } else {
                    PMA_ajaxShowMessage(data.error);
                }
            }
        });
    });

    /** Display a dialog to choose hidden navigation items to show */
    $(document).on('click', 'a.showUnhide.ajax', function (event) {
        event.preventDefault();
        var $msg = PMA_ajaxShowMessage();
        $.get($(this).attr('href') + '&ajax_request=1', function (data) {
            if (typeof data !== 'undefined' && data.success === true) {
                PMA_ajaxRemoveMessage($msg);
                var buttonOptions = {};
                buttonOptions[PMA_messages.strClose] = function () {
                    $(this).dialog("close");
                };
                $('<div/>')
                    .attr('id', 'unhideNavItemDialog')
                    .append(data.message)
                    .dialog({
                        width: 400,
                        minWidth: 200,
                        modal: true,
                        buttons: buttonOptions,
                        title: PMA_messages.strUnhideNavItem,
                        close: function () {
                            $(this).remove();
                        }
                    });
            } else {
                PMA_ajaxShowMessage(data.error);
            }
        });
    });

    /** Show a hidden navigation tree item */
    $(document).on('click', 'a.unhideNavItem.ajax', function (event) {
        event.preventDefault();
        var $tr = $(this).parents('tr');
        var $msg = PMA_ajaxShowMessage();
        $.ajax({
            url: $(this).attr('href') + '&ajax_request=true',
            success: function (data) {
                PMA_ajaxRemoveMessage($msg);
                if (typeof data !== 'undefined' && data.success === true) {
                    $tr.remove();
                    PMA_reloadNavigation();
                } else {
                    PMA_ajaxShowMessage(data.error);
                }
            }
        });
    });

    // Add/Remove favorite table using Ajax.
    $(document).on("click", ".favorite_table_anchor", function (event) {
        event.preventDefault();
        $self = $(this);
        var anchor_id = $self.attr("id");
        if($self.data("favtargetn") !== null) {
            if($('a[data-favtargets="' + $self.data("favtargetn") + '"]').length > 0)
            {
                $('a[data-favtargets="' + $self.data("favtargetn") + '"]').trigger('click');
                return;
            }
        }

        $.ajax({
            url: $self.attr('href'),
            cache: false,
            type: 'POST',
            data: {
                favorite_tables: (isStorageSupported('localStorage') && typeof window.localStorage.favorite_tables !== 'undefined')
                    ? window.localStorage.favorite_tables
                    : ''
            },
            success: function (data) {
                if (data.changes) {
                    $('#pma_favorite_list').html(data.list);
                    $('#' + anchor_id).parent().html(data.anchor);
                    PMA_tooltip(
                        $('#' + anchor_id),
                        'a',
                        $('#' + anchor_id).attr("title")
                    );
                    // Update localStorage.
                    if (isStorageSupported('localStorage')) {
                        window.localStorage.favorite_tables = data.favorite_tables;
                    }
                } else {
                    PMA_ajaxShowMessage(data.message);
                }
            }
        });
    });
});

AJAX.registerOnload('navigation.js', function () {
    // Check if session storage is supported
    if (isStorageSupported('sessionStorage')) {
        var storage = window.sessionStorage;
        // remove tree from storage if Navi_panel config form is submitted
        $(document).on('submit', 'form.config-form', function(event) {
            storage.removeItem('navTreePaths');
        });
        // Initialize if no previous state is defined
        if ($('#pma_navigation_tree_content').length &&
            typeof storage.navTreePaths === 'undefined'
        ) {
            navTreeStateUpdate();
        } else if (PMA_commonParams.get('server') === storage.server &&
            PMA_commonParams.get('token') === storage.token
        ) {
            // Reload the tree to the state before page refresh
            PMA_reloadNavigation(null, JSON.parse(storage.navTreePaths));
        }
    }
});

/**
 * updates the tree state in sessionStorage
 *
 * @returns void
 */
function navTreeStateUpdate() {
    // update if session storage is supported
    if (isStorageSupported('sessionStorage')) {
        var storage = window.sessionStorage;
        // try catch necessary here to detect whether
        // content to be stored exceeds storage capacity
        try {
            storage.setItem('navTreePaths', JSON.stringify(traverseNavigationForPaths()));
            storage.setItem('server', PMA_commonParams.get('server'));
            storage.setItem('token', PMA_commonParams.get('token'));
        } catch(error) {
            // storage capacity exceeded & old navigation tree
            // state is no more valid, so remove it
            storage.removeItem('navTreePaths');
            storage.removeItem('server');
            storage.removeItem('token');
        }
    }
}

/**
 * Expands a node in navigation tree.
 *
 * @param $expandElem expander
 * @param callback    callback function
 *
 * @returns void
 */
function expandTreeNode($expandElem, callback) {
    var $children = $expandElem.closest('li').children('div.list_container');
    var $icon = $expandElem.find('img');
    if ($expandElem.hasClass('loaded')) {
        if ($icon.is('.ic_b_plus')) {
            $icon.removeClass('ic_b_plus').addClass('ic_b_minus');
            $children.slideDown('fast');
        }
        if (callback && typeof callback == 'function') {
            callback.call();
        }
        $children.promise().done(navTreeStateUpdate);
    } else {
        var $throbber = $('#pma_navigation').find('.throbber')
            .first()
            .clone()
            .css({visibility: 'visible', display: 'block'})
            .click(false);
        $icon.hide();
        $throbber.insertBefore($icon);

        loadChildNodes(true, $expandElem, function (data) {
            if (typeof data !== 'undefined' && data.success === true) {
                var $destination = $expandElem.closest('li');
                $icon.removeClass('ic_b_plus').addClass('ic_b_minus');
                $children = $destination.children('div.list_container');
                $children.slideDown('fast');
                if ($destination.find('ul > li').length == 1) {
                    $destination.find('ul > li')
                        .find('a.expander.container')
                        .click();
                }
                if (callback && typeof callback == 'function') {
                    callback.call();
                }
                PMA_showFullName($destination);
            } else {
                PMA_ajaxShowMessage(data.error, false);
            }
            $icon.show();
            $throbber.remove();
            $children.promise().done(navTreeStateUpdate);
        });
    }
    $expandElem.blur();
}

/**
 * Auto-scrolls the newly chosen database
 *
 * @param  object   $element    The element to set to view
 * @param  boolean  $forceToTop Whether to force scroll to top
 *
 */
function scrollToView($element, $forceToTop) {
    var $container = $('#pma_navigation_tree_content');
    var elemTop = $element.offset().top - $container.offset().top;
    var textHeight = 20;
    var scrollPadding = 20; // extra padding from top of bottom when scrolling to view
    if (elemTop < 0 || $forceToTop) {
        $container.stop().animate({
            scrollTop: elemTop + $container.scrollTop() - scrollPadding
        });
    } else if (elemTop + textHeight > $container.height()) {
        $container.stop().animate({
            scrollTop: elemTop + textHeight - $container.height() + $container.scrollTop() + scrollPadding
        });
    }
}

/**
 * Expand the navigation and highlight the current database or table/view
 *
 * @returns void
 */
function PMA_showCurrentNavigation() {
    var db = PMA_commonParams.get('db');
    var table = PMA_commonParams.get('table');
    $('#pma_navigation_tree')
        .find('li.selected')
        .removeClass('selected');
    if (db) {
        var $dbItem = findLoadedItem(
            $('#pma_navigation_tree').find('> div'), db, 'database', !table
        );
        if ($('#navi_db_select').length &&
            $('option:selected', $('#navi_db_select')).length
        ) {
            if (! PMA_selectCurrentDb()) {
                return;
            }
            // If loaded database in navigation is not same as current one
            if ($('#pma_navigation_tree_content').find('span.loaded_db:first').text()
                !== $('#navi_db_select').val()
            ) {
                loadChildNodes(false, $('option:selected', $('#navi_db_select')), function (data) {
                    handleTableOrDb(table, $('#pma_navigation_tree_content'));
                    var $children = $('#pma_navigation_tree_content').children('div.list_container');
                    $children.promise().done(navTreeStateUpdate);
                });
            } else {
                handleTableOrDb(table, $('#pma_navigation_tree_content'));
            }
        } else if ($dbItem) {
            var $expander = $dbItem.children('div:first').children('a.expander');
            // if not loaded or loaded but collapsed
            if (! $expander.hasClass('loaded') ||
                $expander.find('img').is('.ic_b_plus')
            ) {
                expandTreeNode($expander, function () {
                    handleTableOrDb(table, $dbItem);
                });
            } else {
                handleTableOrDb(table, $dbItem);
            }
        }
    } else if ($('#navi_db_select').length && $('#navi_db_select').val()) {
        $('#navi_db_select').val('').hide().trigger('change');
    }
    PMA_showFullName($('#pma_navigation_tree'));

    function handleTableOrDb(table, $dbItem) {
        if (table) {
            loadAndHighlightTableOrView($dbItem, table);
        } else {
            var $container = $dbItem.children('div.list_container');
            var $tableContainer = $container.children('ul').children('li.tableContainer');
            if ($tableContainer.length > 0) {
                var $expander = $tableContainer.children('div:first').children('a.expander');
                $tableContainer.addClass('selected');
                expandTreeNode($expander, function () {
                    scrollToView($dbItem, true);
                });
            } else {
                scrollToView($dbItem, true);
            }
        }
    }

    function findLoadedItem($container, name, clazz, doSelect) {
        var ret = false;
        $container.children('ul').children('li').each(function () {
            var $li = $(this);
            // this is a navigation group, recurse
            if ($li.is('.navGroup')) {
                var $container = $li.children('div.list_container');
                var $childRet = findLoadedItem(
                    $container, name, clazz, doSelect
                );
                if ($childRet) {
                    ret = $childRet;
                    return false;
                }
            } else { // this is a real navigation item
                // name and class matches
                if (((clazz && $li.is('.' + clazz)) || ! clazz) &&
                        $li.children('a').text() == name) {
                    if (doSelect) {
                        $li.addClass('selected');
                    }
                    // taverse up and expand and parent navigation groups
                    $li.parents('.navGroup').each(function () {
                        var $cont = $(this).children('div.list_container');
                        if (! $cont.is(':visible')) {
                            $(this)
                                .children('div:first')
                                .children('a.expander')
                                .click();
                        }
                    });
                    ret = $li;
                    return false;
                }
            }
        });
        return ret;
    }

    function loadAndHighlightTableOrView($dbItem, itemName) {
        var $container = $dbItem.children('div.list_container');
        var $expander;
        var $whichItem = isItemInContainer($container, itemName, 'li.table, li.view');
        //If item already there in some container
        if ($whichItem) {
            //get the relevant container while may also be a subcontainer
            var $relatedContainer = $whichItem.closest('li.subContainer').length
                ? $whichItem.closest('li.subContainer')
                : $dbItem;
            $whichItem = findLoadedItem(
                $relatedContainer.children('div.list_container'),
                itemName, null, true
            );
            //Show directly
            showTableOrView($whichItem, $relatedContainer.children('div:first').children('a.expander'));
        //else if item not there, try loading once
        } else {
            var $sub_containers = $dbItem.find('.subContainer');
            //If there are subContainers i.e. tableContainer or viewContainer
            if($sub_containers.length > 0) {
                var $containers = [];
                $sub_containers.each(function (index) {
                    $containers[index] = $(this);
                    $expander = $containers[index]
                        .children('div:first')
                        .children('a.expander');
                    if (! $expander.hasClass('loaded')) {
                        loadAndShowTableOrView($expander, $containers[index], itemName);
                    }
                });
            // else if no subContainers
            } else {
                $expander = $dbItem
                    .children('div:first')
                    .children('a.expander');
                if (! $expander.hasClass('loaded')) {
                    loadAndShowTableOrView($expander, $dbItem, itemName);
                }
            }
        }
    }

    function loadAndShowTableOrView($expander, $relatedContainer, itemName) {
        loadChildNodes(true, $expander, function (data) {
            var $whichItem = findLoadedItem(
                $relatedContainer.children('div.list_container'),
                itemName, null, true
            );
            if ($whichItem) {
                showTableOrView($whichItem, $expander);
            }
        });
    }

    function showTableOrView($whichItem, $expander) {
        expandTreeNode($expander, function (data) {
            if ($whichItem) {
                scrollToView($whichItem, false);
            }
        });
    }

    function isItemInContainer($container, name, clazz)
    {
        var $whichItem = null;
        $items = $container.find(clazz);
        var found = false;
        $items.each(function () {
            if ($(this).children('a').text() == name) {
                $whichItem = $(this);
                return false;
            }
        });
        return $whichItem;
    }
}

/**
 * Disable navigation panel settings
 *
 * @return void
 */
function PMA_disableNaviSettings() {
    $('#pma_navigation_settings_icon').addClass('hide');
    $('#pma_navigation_settings').remove();
}

/**
 * Ensure that navigation panel settings is properly setup.
 * If not, set it up
 *
 * @return void
 */
function PMA_ensureNaviSettings(selflink) {
    $('#pma_navigation_settings_icon').removeClass('hide');

    if (!$('#pma_navigation_settings').length) {
        var params = {
            getNaviSettings: true
        };
        var url = $('#pma_navigation').find('a.navigation_url').attr('href');
        $.post(url, params, function (data) {
            if (typeof data !== 'undefined' && data.success) {
                $('#pma_navi_settings_container').html(data.message);
                setupRestoreField();
                setupValidation();
                setupConfigTabs();
                $('#pma_navigation_settings').find('form').attr('action', selflink);
            } else {
                PMA_ajaxShowMessage(data.error);
            }
        });
    } else {
        $('#pma_navigation_settings').find('form').attr('action', selflink);
    }
}

/**
 * Reloads the whole navigation tree while preserving its state
 *
 * @param  function     the callback function
 * @param  Object       stored navigation paths
 *
 * @return void
 */
function PMA_reloadNavigation(callback, paths) {
    var params = {
        reload: true,
        no_debug: true
    };
    paths = paths || traverseNavigationForPaths();
    $.extend(params, paths);
    if ($('#navi_db_select').length) {
        params.db = PMA_commonParams.get('db');
        requestNaviReload(params);
        return;
    }
    requestNaviReload(params);

    function requestNaviReload(params) {
        var url = $('#pma_navigation').find('a.navigation_url').attr('href');
        $.post(url, params, function (data) {
            if (typeof data !== 'undefined' && data.success) {
                $('#pma_navigation_tree').html(data.message).children('div').show();
                if ($('#pma_navigation_tree').hasClass('synced')) {
                    PMA_selectCurrentDb();
                    PMA_showCurrentNavigation();
                }
                // Fire the callback, if any
                if (typeof callback === 'function') {
                    callback.call();
                }
                navTreeStateUpdate();
            } else {
                PMA_ajaxShowMessage(data.error);
            }
        });
    }
}

function PMA_selectCurrentDb() {
    var $naviDbSelect = $('#navi_db_select');

    if (!$naviDbSelect.length) {
        return false;
    }

    if (PMA_commonParams.get('db')) { // db selected
        $naviDbSelect.show();
    }

    $naviDbSelect.val(PMA_commonParams.get('db'));
    return $naviDbSelect.val() === PMA_commonParams.get('db');

}

/**
 * Handles any requests to change the page in a branch of a tree
 *
 * This can be called from link click or select change event handlers
 *
 * @param object $this A jQuery object that points to the element that
 * initiated the action of changing the page
 *
 * @return void
 */
function PMA_navigationTreePagination($this) {
    var $msgbox = PMA_ajaxShowMessage();
    var isDbSelector = $this.closest('div.pageselector').is('.dbselector');
    var url, params;
    if ($this[0].tagName == 'A') {
        url = $this.attr('href');
        params = 'ajax_request=true';
    } else { // tagName == 'SELECT'
        url = 'navigation.php';
        params = $this.closest("form").serialize() + '&ajax_request=true';
    }
    var searchClause = PMA_fastFilter.getSearchClause();
    if (searchClause) {
        params += '&searchClause=' + encodeURIComponent(searchClause);
    }
    if (isDbSelector) {
        params += '&full=true';
    } else {
        var searchClause2 = PMA_fastFilter.getSearchClause2($this);
        if (searchClause2) {
            params += '&searchClause2=' + encodeURIComponent(searchClause2);
        }
    }
    $.post(url, params, function (data) {
        if (typeof data !== 'undefined' && data.success) {
            PMA_ajaxRemoveMessage($msgbox);
            if (isDbSelector) {
                var val = PMA_fastFilter.getSearchClause();
                $('#pma_navigation_tree')
                    .html(data.message)
                    .children('div')
                    .show();
                if (val) {
                    $('#pma_navigation_tree')
                        .find('li.fast_filter input.searchClause')
                        .val(val);
                }
            } else {
                var $parent = $this.closest('div.list_container').parent();
                var val = PMA_fastFilter.getSearchClause2($this);
                $this.closest('div.list_container').html(
                    $(data.message).children().show()
                );
                if (val) {
                    $parent.find('li.fast_filter input.searchClause').val(val);
                }
                $parent.find('span.pos2_value:first').text(
                    $parent.find('span.pos2_value:last').text()
                );
                $parent.find('span.pos3_value:first').text(
                    $parent.find('span.pos3_value:last').text()
                );
            }
        } else {
            PMA_ajaxShowMessage(data.error);
            PMA_handleRedirectAndReload(data);
        }
        navTreeStateUpdate();
    });
}

/**
 * @var ResizeHandler Custom object that manages the resizing of the navigation
 *
 * XXX: Must only be ever instanciated once
 * XXX: Inside event handlers the 'this' object is accessed as 'event.data.resize_handler'
 */
var ResizeHandler = function () {
    /**
     * @var int panel_width Used by the collapser to know where to go
     *                      back to when uncollapsing the panel
     */
    this.panel_width = 0;
    /**
     * @var string left Used to provide support for RTL languages
     */
    this.left = $('html').attr('dir') == 'ltr' ? 'left' : 'right';
    /**
     * Adjusts the width of the navigation panel to the specified value
     *
     * @param int pos Navigation width in pixels
     *
     * @return void
     */
    this.setWidth = function (pos) {
        var $resizer = $('#pma_navigation_resizer');
        var resizer_width = $resizer.width();
        var $collapser = $('#pma_navigation_collapser');
        $('#pma_navigation').width(pos);
        $('body').css('margin-' + this.left, pos + 'px');
        $("#floating_menubar, #pma_console")
            .css('margin-' + this.left, (pos + resizer_width) + 'px');
        $resizer.css(this.left, pos + 'px');
        if (pos === 0) {
            $collapser
                .css(this.left, pos + resizer_width)
                .html(this.getSymbol(pos))
                .prop('title', PMA_messages.strShowPanel);
        } else {
            $collapser
                .css(this.left, pos)
                .html(this.getSymbol(pos))
                .prop('title', PMA_messages.strHidePanel);
        }
        setTimeout(function () {
            $(window).trigger('resize');
        }, 4);
    };
    /**
     * Returns the horizontal position of the mouse,
     * relative to the outer side of the navigation panel
     *
     * @param int pos Navigation width in pixels
     *
     * @return void
     */
    this.getPos = function (event) {
        var pos = event.pageX;
        var windowWidth = $(window).width();
        var windowScroll = $(window).scrollLeft();
        pos = pos - windowScroll;
        if (this.left != 'left') {
            pos = windowWidth - event.pageX;
        }
        if (pos < 0) {
            pos = 0;
        } else if (pos + 100 >= windowWidth) {
            pos = windowWidth - 100;
        } else {
            this.panel_width = 0;
        }
        return pos;
    };
    /**
     * Returns the HTML code for the arrow symbol used in the collapser
     *
     * @param int width The width of the panel
     *
     * @return string
     */
    this.getSymbol = function (width) {
        if (this.left == 'left') {
            if (width === 0) {
                return '&rarr;';
            } else {
                return '&larr;';
            }
        } else {
            if (width === 0) {
                return '&larr;';
            } else {
                return '&rarr;';
            }
        }
    };
    /**
     * Event handler for initiating a resize of the panel
     *
     * @param object e Event data (contains a reference to resizeHandler)
     *
     * @return void
     */
    this.mousedown = function (event) {
        event.preventDefault();
        $(document)
            .bind('mousemove', {'resize_handler': event.data.resize_handler},
                $.throttle(event.data.resize_handler.mousemove, 4))
            .bind('mouseup', {'resize_handler': event.data.resize_handler},
                event.data.resize_handler.mouseup);
        $('body').css('cursor', 'col-resize');
    };
    /**
     * Event handler for terminating a resize of the panel
     *
     * @param object e Event data (contains a reference to resizeHandler)
     *
     * @return void
     */
    this.mouseup = function (event) {
        $('body').css('cursor', '');
        $.cookie('pma_navi_width', event.data.resize_handler.getPos(event));
        $('#topmenu').menuResizer('resize');
        $(document)
            .unbind('mousemove')
            .unbind('mouseup');
    };
    /**
     * Event handler for updating the panel during a resize operation
     *
     * @param object e Event data (contains a reference to resizeHandler)
     *
     * @return void
     */
    this.mousemove = function (event) {
        event.preventDefault();
        var pos = event.data.resize_handler.getPos(event);
        event.data.resize_handler.setWidth(pos);
        if ($('.sticky_columns').length !== 0) {
            handleAllStickyColumns();
        }
    };
    /**
     * Event handler for collapsing the panel
     *
     * @param object e Event data (contains a reference to resizeHandler)
     *
     * @return void
     */
    this.collapse = function (event) {
        event.preventDefault();
        var panel_width = event.data.resize_handler.panel_width;
        var width = $('#pma_navigation').width();
        if (width === 0 && panel_width === 0) {
            panel_width = 240;
        }
        event.data.resize_handler.setWidth(panel_width);
        event.data.resize_handler.panel_width = width;
    };
    /**
     * Event handler for resizing the navigation tree height on window resize
     *
     * @return void
     */
    this.treeResize = function (event) {
        var $nav        = $("#pma_navigation"),
            $nav_tree   = $("#pma_navigation_tree"),
            $nav_header = $("#pma_navigation_header"),
            $nav_tree_content = $("#pma_navigation_tree_content");
        $nav_tree.height($nav.height() - $nav_header.height());
        if ($nav_tree_content.length > 0) {
            $nav_tree_content.height($nav_tree.height() - $nav_tree_content.position().top);
        } else {
            //TODO: in fast filter search response there is no #pma_navigation_tree_content, needs to be added in php
            $nav_tree.css({
                'overflow-y': 'auto'
            });
        }
        // Set content bottom space beacuse of console
        $('body').css('margin-bottom', $('#pma_console').height() + 'px');
    };
    /* Initialisation section begins here */
    if ($.cookie('pma_navi_width')) {
        // If we have a cookie, set the width of the panel to its value
        var pos = Math.abs(parseInt($.cookie('pma_navi_width'), 10) || 0);
        this.setWidth(pos);
        $('#topmenu').menuResizer('resize');
    }
    // Register the events for the resizer and the collapser
    $(document).on('mousedown', '#pma_navigation_resizer', {'resize_handler': this}, this.mousedown);
    $(document).on('click', '#pma_navigation_collapser', {'resize_handler': this}, this.collapse);

    // Add the correct arrow symbol to the collapser
    $('#pma_navigation_collapser').html(this.getSymbol($('#pma_navigation').width()));
    // Fix navigation tree height
    $(window).on('resize', this.treeResize);
    // need to call this now and then, browser might decide
    // to show/hide horizontal scrollbars depending on page content width
    setInterval(this.treeResize, 2000);
    this.treeResize();
}; // End of ResizeHandler

/**
 * @var object PMA_fastFilter Handles the functionality that allows filtering
 *                            of the items in a branch of the navigation tree
 */
var PMA_fastFilter = {
    /**
     * Construct for the asynchronous fast filter functionality
     *
     * @param object $this        A jQuery object pointing to the list container
     *                            which is the nearest parent of the fast filter
     * @param string searchClause The query string for the filter
     *
     * @return new PMA_fastFilter.filter object
     */
    filter: function ($this, searchClause) {
        /**
         * @var object $this A jQuery object pointing to the list container
         *                   which is the nearest parent of the fast filter
         */
        this.$this = $this;
        /**
         * @var bool searchClause The query string for the filter
         */
        this.searchClause = searchClause;
        /**
         * @var object $clone A clone of the original contents
         *                    of the navigation branch before
         *                    the fast filter was applied
         */
        this.$clone = $this.clone();
        /**
         * @var object xhr A reference to the ajax request that is currently running
         */
        this.xhr = null;
        /**
         * @var int timeout Used to delay the request for asynchronous search
         */
        this.timeout = null;

        var $filterInput = $this.find('li.fast_filter input.searchClause');
        if ($filterInput.length !== 0 &&
            $filterInput.val() !== '' &&
            $filterInput.val() != $filterInput[0].defaultValue
        ) {
            this.request();
        }
    },
    /**
     * Gets the query string from the database fast filter form
     *
     * @return string
     */
    getSearchClause: function () {
        var retval = '';
        var $input = $('#pma_navigation_tree')
            .find('li.fast_filter.db_fast_filter input.searchClause');
        if ($input.length && $input.val() != $input[0].defaultValue) {
            retval = $input.val();
        }
        return retval;
    },
    /**
     * Gets the query string from a second level item's fast filter form
     * The retrieval is done by trasversing the navigation tree backwards
     *
     * @return string
     */
    getSearchClause2: function ($this) {
        var $filterContainer = $this.closest('div.list_container');
        var $filterInput = $([]);
        if ($filterContainer
            .find('li.fast_filter:not(.db_fast_filter) input.searchClause')
            .length !== 0) {
            $filterInput = $filterContainer
                .find('li.fast_filter:not(.db_fast_filter) input.searchClause');
        }
        var searchClause2 = '';
        if ($filterInput.length !== 0 &&
            $filterInput.first().val() != $filterInput[0].defaultValue
        ) {
            searchClause2 = $filterInput.val();
        }
        return searchClause2;
    },
    /**
     * @var hash events A list of functions that are bound to DOM events
     *                  at the top of this file
     */
    events: {
        focus: function (event) {
            var $obj = $(this).closest('div.list_container');
            if (! $obj.data('fastFilter')) {
                $obj.data(
                    'fastFilter',
                    new PMA_fastFilter.filter($obj, $(this).val())
                );
            }
            if ($(this).val() == this.defaultValue) {
                $(this).val('');
            } else {
                $(this).select();
            }
        },
        blur: function (event) {
            if ($(this).val() === '') {
                $(this).val(this.defaultValue);
            }
            var $obj = $(this).closest('div.list_container');
            if ($(this).val() == this.defaultValue && $obj.data('fastFilter')) {
                $obj.data('fastFilter').restore();
            }
        },
        mouseover: function (event) {
            var message = '';
            if ($(this).closest('li.fast_filter').is('.db_fast_filter')) {
                message = PMA_messages.strHoverDbFastFilter;
            } else {
                var node_type = $(this).siblings("input[name='pos2_name']").val();
                var node_name = PMA_messages.strTables;
                if (node_type == 'views') {
                    node_name = PMA_messages.strViews;
                } else if (node_type == 'procedures') {
                    node_name = PMA_messages.strProcedures;
                } else if (node_type == 'functions') {
                    node_name = PMA_messages.strFunctions;
                } else if (node_type == 'events') {
                    node_name = PMA_messages.strEvents;
                }
                message = PMA_sprintf(PMA_messages.strHoverFastFilter, node_name);
            }
            PMA_tooltip($(this), 'input', message);
        },
        keyup: function (event) {
            var $obj = $(this).closest('div.list_container');
            var str = '';
            if ($(this).val() != this.defaultValue && $(this).val() !== '') {
                $obj.find('div.pageselector').hide();
                str = $(this).val();
            }

            /**
             * FIXME at the server level a value match is done while on
             * the client side it is a regex match. These two should be aligned
             */

            // regex used for filtering.
            var regex;
            try {
                regex = new RegExp(str, 'i');
            } catch (err) {
                return;
            }

            // this is the div that houses the items to be filtered by this filter.
            var outerContainer;
            if ($(this).closest('li.fast_filter').is('.db_fast_filter')) {
                outerContainer = $('#pma_navigation_tree_content');
            } else {
                outerContainer = $obj;
            }

            // filters items that are directly under the div as well as grouped in
            // groups. Does not filter child items (i.e. a database search does
            // not filter tables)
            var item_filter = function($curr) {
                $curr.children('ul').children('li.navGroup').each(function() {
                    $(this).children('div.list_container').each(function() {
                        item_filter($(this)); // recursive
                    });
                });
                $curr.children('ul').children('li').children('a').not('.container').each(function() {
                    if (regex.test($(this).text())) {
                        $(this).parent().show().removeClass('hidden');
                    } else {
                        $(this).parent().hide().addClass('hidden');
                    }
                });
            };
            item_filter(outerContainer);

            // hides containers that does not have any visible children
            var container_filter = function ($curr) {
                $curr.children('ul').children('li.navGroup').each(function() {
                    var $group = $(this);
                    $group.children('div.list_container').each(function() {
                        container_filter($(this)); // recursive
                    });
                    $group.show().removeClass('hidden');
                    if ($group.children('div.list_container').children('ul')
                            .children('li').not('.hidden').length === 0) {
                        $group.hide().addClass('hidden');
                    }
                });
            };
            container_filter(outerContainer);

            if ($(this).val() != this.defaultValue && $(this).val() !== '') {
                if (! $obj.data('fastFilter')) {
                    $obj.data(
                        'fastFilter',
                        new PMA_fastFilter.filter($obj, $(this).val())
                    );
                } else {
                    if (event.keyCode == 13) {
                        $obj.data('fastFilter').update($(this).val());
                    }
                }
            } else if ($obj.data('fastFilter')) {
                $obj.data('fastFilter').restore(true);
            }
        },
        clear: function (event) {
            event.stopPropagation();
            // Clear the input and apply the fast filter with empty input
            var filter = $(this).closest('div.list_container').data('fastFilter');
            if (filter) {
                filter.restore();
            }
            var value = $(this).prev()[0].defaultValue;
            $(this).prev().val(value).trigger('keyup');
        }
    }
};
/**
 * Handles a change in the search clause
 *
 * @param string searchClause The query string for the filter
 *
 * @return void
 */
PMA_fastFilter.filter.prototype.update = function (searchClause) {
    if (this.searchClause != searchClause) {
        this.searchClause = searchClause;
        this.request();
    }
};
/**
 * After a delay of 250mS, initiates a request to retrieve search results
 * Multiple calls to this function will always abort the previous request
 *
 * @return void
 */
PMA_fastFilter.filter.prototype.request = function () {
    var self = this;
    if (self.$this.find('li.fast_filter').find('img.throbber').length === 0) {
        self.$this.find('li.fast_filter').append(
            $('<div class="throbber"></div>').append(
                $('#pma_navigation_content')
                    .find('img.throbber')
                    .clone()
                    .css({visibility: 'visible', display: 'block'})
            )
        );
    }
    if (self.xhr) {
        self.xhr.abort();
    }
    var url = $('#pma_navigation').find('a.navigation_url').attr('href');
    var params = self.$this.find('> ul > li > form.fast_filter').first().serialize();
    if (self.$this.find('> ul > li > form.fast_filter:first input[name=searchClause]').length === 0) {
        var $input = $('#pma_navigation_tree').find('li.fast_filter.db_fast_filter input.searchClause');
        if ($input.length && $input.val() != $input[0].defaultValue) {
            params += '&searchClause=' + encodeURIComponent($input.val());
        }
    }
    self.xhr = $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: params,
        complete: function (jqXHR, status) {
            if (status != 'abort') {
                var data = $.parseJSON(jqXHR.responseText);
                self.$this.find('li.fast_filter').find('div.throbber').remove();
                if (data && data.results) {
                    self.swap.apply(self, [data.message]);
                }
            }
        }
    });
};
/**
 * Replaces the contents of the navigation branch with the search results
 *
 * @param string list The search results
 *
 * @return void
 */
PMA_fastFilter.filter.prototype.swap = function (list) {
    this.$this
        .html($(list).html())
        .children()
        .show()
        .end()
        .find('li.fast_filter input.searchClause')
        .val(this.searchClause);
    this.$this.data('fastFilter', this);
};
/**
 * Restores the navigation to the original state after the fast filter is cleared
 *
 * @param bool focus Whether to also focus the input box of the fast filter
 *
 * @return void
 */
PMA_fastFilter.filter.prototype.restore = function (focus) {
    if(this.$this.children('ul').first().hasClass('search_results')) {
        this.$this.html(this.$clone.html()).children().show();
        this.$this.data('fastFilter', this);
        if (focus) {
            this.$this.find('li.fast_filter input.searchClause').focus();
        }
    }
    this.searchClause = '';
    this.$this.find('div.pageselector').show();
    this.$this.find('div.throbber').remove();
};

/**
 * Show full name when cursor hover and name not shown completely
 *
 * @param object $containerELem Container element
 *
 * @return void
 */
function PMA_showFullName($containerELem) {

    $containerELem.find('.hover_show_full').mouseenter(function() {
        /** mouseenter */
        var $this = $(this);
        var thisOffset = $this.offset();
        if($this.text() === '') {
            return;
        }
        var $parent = $this.parent();
        if(  ($parent.offset().left + $parent.outerWidth())
           < (thisOffset.left + $this.outerWidth()))
        {
            var $fullNameLayer = $('#full_name_layer');
            if($fullNameLayer.length === 0)
            {
                $('body').append('<div id="full_name_layer" class="hide"></div>');
                $('#full_name_layer').mouseleave(function() {
                    /** mouseleave */
                    $(this).addClass('hide')
                           .removeClass('hovering');
                }).mouseenter(function() {
                    /** mouseenter */
                    $(this).addClass('hovering');
                });
                $fullNameLayer = $('#full_name_layer');
            }
            $fullNameLayer.removeClass('hide');
            $fullNameLayer.css({left: thisOffset.left, top: thisOffset.top});
            $fullNameLayer.html($this.clone());
            setTimeout(function() {
                if(! $fullNameLayer.hasClass('hovering'))
                {
                    $fullNameLayer.trigger('mouseleave');
                }
            }, 200);
        }
    });
}
;

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * @fileoverview    function used for index manipulation pages
 * @name            Table Structure
 *
 * @requires    jQuery
 * @requires    jQueryUI
 * @required    js/functions.js
 */

/**
 * Hides/shows the inputs and submits appropriately depending
 * on whether the index type chosen is 'SPATIAL' or not.
 */
function checkIndexType()
{
    /**
     * @var Object Dropdown to select the index choice.
     */
    var $select_index_choice = $('#select_index_choice');
    /**
     * @var Object Dropdown to select the index type.
     */
    var $select_index_type = $('#select_index_type');
    /**
     * @var Object Table header for the size column.
     */
    var $size_header = $('#index_columns').find('thead tr th:nth-child(2)');
    /**
     * @var Object Inputs to specify the columns for the index.
     */
    var $column_inputs = $('select[name="index[columns][names][]"]');
    /**
     * @var Object Inputs to specify sizes for columns of the index.
     */
    var $size_inputs = $('input[name="index[columns][sub_parts][]"]');
    /**
     * @var Object Footer containg the controllers to add more columns
     */
    var $add_more = $('#index_frm').find('.add_more');

    if ($select_index_choice.val() == 'SPATIAL') {
        // Disable and hide the size column
        $size_header.hide();
        $size_inputs.each(function () {
            $(this)
                .prop('disabled', true)
                .parent('td').hide();
        });

        // Disable and hide the columns of the index other than the first one
        var initial = true;
        $column_inputs.each(function () {
            $column_input = $(this);
            if (! initial) {
                $column_input
                    .prop('disabled', true)
                    .parent('td').hide();
            } else {
                initial = false;
            }
        });

        // Hide controllers to add more columns
        $add_more.hide();
    } else {
        // Enable and show the size column
        $size_header.show();
        $size_inputs.each(function () {
            $(this)
                .prop('disabled', false)
                .parent('td').show();
        });

        // Enable and show the columns of the index
        $column_inputs.each(function () {
            $(this)
                .prop('disabled', false)
                .parent('td').show();
        });

        // Show controllers to add more columns
        $add_more.show();
    }

    if ($select_index_choice.val() == 'SPATIAL' ||
            $select_index_choice.val() == 'FULLTEXT') {
        $select_index_type.val('').prop('disabled', true);
    } else {
        $select_index_type.prop('disabled', false)
    }
}

/**
 * Sets current index information into form parameters.
 *
 * @param array  source_array Array containing index columns
 * @param string index_choice Choice of index
 *
 * @return void
 */
function PMA_setIndexFormParameters(source_array, index_choice)
{
    if (index_choice == 'index') {
        $('input[name="indexes"]').val(JSON.stringify(source_array));
    } else {
        $('input[name="' + index_choice + '_indexes"]').val(JSON.stringify(source_array));
    }
}

/**
 * Removes a column from an Index.
 *
 * @param string col_index Index of column in form
 *
 * @return void
 */
function PMA_removeColumnFromIndex(col_index)
{
    // Get previous index details.
    var previous_index = $('select[name="field_key[' + col_index + ']"]')
        .attr('data-index');
    if (previous_index.length) {
        previous_index = previous_index.split(',');
        var source_array = PMA_getIndexArray(previous_index[0]);
        if (source_array == null) {
            return;
        }

        // Remove column from index array.
        var source_length = source_array[previous_index[1]].columns.length;
        for (var i=0; i<source_length; i++) {
            if (source_array[previous_index[1]].columns[i].col_index == col_index) {
                source_array[previous_index[1]].columns.splice(i, 1);
            }
        }

        // Remove index completely if no columns left.
        if (source_array[previous_index[1]].columns.length === 0) {
            source_array.splice(previous_index[1], 1);
        }

        // Update current index details.
        $('select[name="field_key[' + col_index + ']"]').attr('data-index', '');
        // Update form index parameters.
        PMA_setIndexFormParameters(source_array, previous_index[0].toLowerCase());
    }
}

/**
 * Adds a column to an Index.
 *
 * @param array  source_array Array holding corresponding indexes
 * @param string array_index  Index of an INDEX in array
 * @param string index_choice Choice of Index
 * @param string col_index    Index of column on form
 *
 * @return void
 */
function PMA_addColumnToIndex(source_array, array_index, index_choice, col_index)
{
    if (col_index >= 0) {
        // Remove column from other indexes (if any).
        PMA_removeColumnFromIndex(col_index);
    }
    var index_name = $('input[name="index[Key_name]"]').val();
    var index_comment = $('input[name="index[Index_comment]"]').val();
    var key_block_size = $('input[name="index[Key_block_size]"]').val();
    var parser = $('input[name="index[Parser]"]').val();
    var index_type = $('select[name="index[Index_type]"]').val();
    var columns = [];
    $('#index_columns').find('tbody').find('tr').each(function () {
        // Get columns in particular order.
        var col_index = $(this).find('select[name="index[columns][names][]"]').val();
        var size = $(this).find('input[name="index[columns][sub_parts][]"]').val();
        columns.push({
            'col_index': col_index,
            'size': size
        });
    });

    // Update or create an index.
    source_array[array_index] = {
        'Key_name': index_name,
        'Index_comment': index_comment,
        'Index_choice': index_choice.toUpperCase(),
        'Key_block_size': key_block_size,
        'Parser': parser,
        'Index_type': index_type,
        'columns': columns
    };

    // Display index name (or column list)
    var displayName = index_name;
    if (displayName == '') {
        var columnNames = [];
        $.each(columns, function () {
            columnNames.push($('input[name="field_name[' +  this.col_index + ']"]').val());
        });
        displayName = '[' + columnNames.join(', ') + ']';
    }
    $.each(columns, function () {
        var id = 'index_name_' + this.col_index + '_8';
        var $name = $('#' + id);
        if ($name.length == 0) {
            $name = $('<a id="' + id + '" href="#" class="ajax show_index_dialog"></a>');
            $name.insertAfter($('select[name="field_key[' + this.col_index + ']"]'));
        }
        var $text = $('<small>').text(displayName);
        $name.html($text);
    });

    if (col_index >= 0) {
        // Update index details on form.
        $('select[name="field_key[' + col_index + ']"]')
            .attr('data-index', index_choice + ',' + array_index);
    }
    PMA_setIndexFormParameters(source_array, index_choice.toLowerCase());
}

/**
 * Get choices list for a column to create a composite index with.
 *
 * @param string index_choice Choice of index
 * @param array  source_array Array hodling columns for particular index
 *
 * @return jQuery Object
 */
function PMA_getCompositeIndexList(source_array, col_index)
{
    // Remove any previous list.
    if ($('#composite_index_list').length) {
        $('#composite_index_list').remove();
    }

    // Html list.
    var $composite_index_list = $(
        '<ul id="composite_index_list">' +
        '<div>' + PMA_messages.strCompositeWith + '</div>' +
        '</ul>'
    );

    // Add each column to list available for composite index.
    var source_length = source_array.length;
    var already_present = false;
    for (var i=0; i<source_length; i++) {
        var sub_array_len = source_array[i].columns.length;
        var column_names = [];
        for (var j=0; j<sub_array_len; j++) {
            column_names.push(
                $('input[name="field_name[' + source_array[i].columns[j].col_index + ']"]').val()
            );

            if (col_index == source_array[i].columns[j].col_index) {
                already_present = true;
            }
        }

        $composite_index_list.append(
            '<li>' +
            '<input type="radio" name="composite_with" ' +
            (already_present ? 'checked="checked"' : '') +
            ' id="composite_index_' + i + '" value="' + i + '">' +
            '<label for="composite_index_' + i + '">' + column_names.join(', ') +
            '</lablel>' +
            '</li>'
        );
    }

    return $composite_index_list;
}

/**
 * Shows 'Add Index' dialog.
 *
 * @param array  source_array   Array holding particluar index
 * @param string array_index    Index of an INDEX in array
 * @param array  target_columns Columns for an INDEX
 * @param string col_index      Index of column on form
 * @param object index          Index detail object
 *
 * @return void
 */
function PMA_showAddIndexDialog(source_array, array_index, target_columns, col_index, index)
{
    // Prepare post-data.
    var $table = $('input[name="table"]');
    var table = $table.length > 0 ? $table.val() : '';
    var post_data = {
        token: $('input[name="token"]').val(),
        server:  $('input[name="server"]').val(),
        db: $('input[name="db"]').val(),
        table: table,
        ajax_request: 1,
        create_edit_table: 1,
        index: index
    };

    var columns = {};
    for (var i=0; i<target_columns.length; i++) {
        var column_name = $('input[name="field_name[' + target_columns[i] + ']"]').val();
        var column_type = $('select[name="field_type[' + target_columns[i] + ']"]').val().toLowerCase();
        columns[column_name] = [column_type, target_columns[i]];
    }
    post_data.columns = JSON.stringify(columns);

    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        var is_missing_value = false;
        $('select[name="index[columns][names][]"]').each(function () {
            if ($(this).val() === '') {
                is_missing_value = true;
            }
        });

        if (! is_missing_value) {
            PMA_addColumnToIndex(
                source_array,
                array_index,
                index.Index_choice,
                col_index
            );
        } else {
            PMA_ajaxShowMessage(
                '<div class="error"><img src="themes/dot.gif" title="" alt=""' +
                ' class="icon ic_s_error" /> ' + PMA_messages.strMissingColumn +
                ' </div>', false
            );

            return false;
        }

        $(this).dialog('close');
    };
    button_options[PMA_messages.strCancel] = function () {
        if (col_index >= 0) {
            // Handle state on 'Cancel'.
            var $select_list = $('select[name="field_key[' + col_index + ']"]');
            if (! $select_list.attr('data-index').length) {
                $select_list.find('option[value*="none"]').attr('selected', 'selected');
            } else {
                var previous_index = $select_list.attr('data-index').split(',');
                $select_list.find('option[value*="' + previous_index[0].toLowerCase() + '"]')
                    .attr('selected', 'selected');
            }
        }
        $(this).dialog('close');
    };
    var $msgbox = PMA_ajaxShowMessage();
    $.post("tbl_indexes.php", post_data, function (data) {
        if (data.success === false) {
            //in the case of an error, show the error message returned.
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);
            // Show dialog if the request was successful
            var $div = $('<div/>');
            $div
            .append(data.message)
            .dialog({
                title: PMA_messages.strAddIndex,
                width: 450,
                minHeight: 250,
                open: function () {
                    checkIndexName("index_frm");
                    PMA_showHints($div);
                    PMA_init_slider();
                    $('#index_columns').find('td').each(function () {
                        $(this).css("width", $(this).width() + 'px');
                    });
                    $('#index_columns').find('tbody').sortable({
                        axis: 'y',
                        containment: $("#index_columns").find("tbody"),
                        tolerance: 'pointer'
                    });
                    // We dont need the slider at this moment.
                    $(this).find('fieldset.tblFooters').remove();
                },
                modal: true,
                buttons: button_options,
                close: function () {
                    $(this).remove();
                }
            });
        }
    });
}

/**
 * Creates a advanced index type selection dialog.
 *
 * @param array  source_array Array holding a particular type of indexes
 * @param string index_choice Choice of index
 * @param string col_index    Index of new column on form
 *
 * @return void
 */
function PMA_indexTypeSelectionDialog(source_array, index_choice, col_index)
{
    var $single_column_radio = $('<input type="radio" id="single_column" name="index_choice"' +
        ' checked="checked">' +
        '<label for="single_column">' + PMA_messages.strCreateSingleColumnIndex + '</label>');
    var $composite_index_radio = $('<input type="radio" id="composite_index"' +
        ' name="index_choice">' +
        '<label for="composite_index">' + PMA_messages.strCreateCompositeIndex + '</label>');
    var $dialog_content = $('<fieldset id="advance_index_creator"></fieldset>');
    $dialog_content.append('<legend>' + index_choice.toUpperCase() + '</legend>');


    // For UNIQUE/INDEX type, show choice for single-column and composite index.
    $dialog_content.append($single_column_radio);
    $dialog_content.append($composite_index_radio);

    var button_options = {};
    // 'OK' operation.
    button_options[PMA_messages.strGo] = function () {
        if ($('#single_column').is(':checked')) {
            var index = {
                'Key_name': (index_choice == 'primary' ? 'PRIMARY' : ''),
                'Index_choice': index_choice.toUpperCase()
            };
            PMA_showAddIndexDialog(source_array, (source_array.length), [col_index], col_index, index);
        }

        if ($('#composite_index').is(':checked')) {
            if ($('input[name="composite_with"]').length !== 0 && $('input[name="composite_with"]:checked').length === 0
            ) {
                PMA_ajaxShowMessage(
                    '<div class="error"><img src="themes/dot.gif" title=""' +
                    ' alt="" class="icon ic_s_error" /> ' +
                    PMA_messages.strFormEmpty +
                    ' </div>',
                    false
                );
                return false;
            }

            var array_index = $('input[name="composite_with"]:checked').val();
            var source_length = source_array[array_index].columns.length;
            var target_columns = [];
            for (var i=0; i<source_length; i++) {
                target_columns.push(source_array[array_index].columns[i].col_index);
            }
            target_columns.push(col_index);

            PMA_showAddIndexDialog(source_array, array_index, target_columns, col_index,
             source_array[array_index]);
        }

        $(this).remove();
    };
    button_options[PMA_messages.strCancel] = function () {
        // Handle state on 'Cancel'.
        var $select_list = $('select[name="field_key[' + col_index + ']"]');
        if (! $select_list.attr('data-index').length) {
            $select_list.find('option[value*="none"]').attr('selected', 'selected');
        } else {
            var previous_index = $select_list.attr('data-index').split(',');
            $select_list.find('option[value*="' + previous_index[0].toLowerCase() + '"]')
                .attr('selected', 'selected');
        }
        $(this).remove();
    };
    var $dialog = $('<div/>').append($dialog_content).dialog({
        minWidth: 525,
        minHeight: 200,
        modal: true,
        title: PMA_messages.strAddIndex,
        resizable: false,
        buttons: button_options,
        open: function () {
            $('#composite_index').on('change', function () {
                if ($(this).is(':checked')) {
                    $dialog_content.append(PMA_getCompositeIndexList(source_array, col_index));
                }
            });
            $('#single_column').on('change', function () {
                if ($(this).is(':checked')) {
                    if ($('#composite_index_list').length) {
                        $('#composite_index_list').remove();
                    }
                }
            });
        },
        close: function () {
            $('#composite_index').off('change');
            $('#single_column').off('change');
            $(this).remove();
        }
    });
}

/**
 * Returns the array of indexes based on the index choice
 *
 * @param index_choice index choice
 */
function PMA_getIndexArray(index_choice)
{
    var source_array = null;

    switch (index_choice.toLowerCase()) {
    case 'primary':
        source_array = primary_indexes;
        break;
    case 'unique':
        source_array = unique_indexes;
        break;
    case 'index':
        source_array = indexes;
        break;
    case 'fulltext':
        source_array = fulltext_indexes;
        break;
    case 'spatial':
        source_array = spatial_indexes;
        break;
    default:
        return null;
    }
    return source_array;
}


/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('indexes.js', function () {
    $(document).off('click', '#save_index_frm');
    $(document).off('click', '#preview_index_frm');
    $(document).off('change', '#select_index_choice');
    $(document).off('click', 'a.drop_primary_key_index_anchor.ajax');
    $(document).off('click', "#table_index tbody tr td.edit_index.ajax, #indexes .add_index.ajax");
    $(document).off('click', '#index_frm input[type=submit]');
    $('body').off('change', 'select[name*="field_key"]');
    $(document).off('click', '.show_index_dialog');
});

/**
 * @description <p>Ajax scripts for table index page</p>
 *
 * Actions ajaxified here:
 * <ul>
 * <li>Showing/hiding inputs depending on the index type chosen</li>
 * <li>create/edit/drop indexes</li>
 * </ul>
 */
AJAX.registerOnload('indexes.js', function () {
    // Re-initialize variables.
    primary_indexes = [];
    unique_indexes = [];
    indexes = [];
    fulltext_indexes = [];
    spatial_indexes = [];

    // for table creation form
    var $engine_selector = $('.create_table_form select[name=tbl_storage_engine]');
    if ($engine_selector.length) {
        PMA_hideShowConnection($engine_selector);
    }

    var $form = $("#index_frm");
    if ($form.length > 0) {
        showIndexEditDialog($form);
    }

    $(document).on('click', '#save_index_frm', function (event) {
        event.preventDefault();
        var $form = $("#index_frm");
        var submitData = $form.serialize() + '&do_save_data=1&ajax_request=true&ajax_page_request=true';
        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        AJAX.source = $form;
        $.post($form.attr('action'), submitData, AJAX.responseHandler);
    });

    $(document).on('click', '#preview_index_frm', function (event) {
        event.preventDefault();
        PMA_previewSQL($('#index_frm'));
    });

    $(document).on('change', '#select_index_choice', function (event) {
        event.preventDefault();
        checkIndexType();
        checkIndexName("index_frm");
    });

    /**
     * Ajax Event handler for 'Drop Index'
     */
    $(document).on('click', 'a.drop_primary_key_index_anchor.ajax', function (event) {
        event.preventDefault();
        var $anchor = $(this);
        /**
         * @var $curr_row    Object containing reference to the current field's row
         */
        var $curr_row = $anchor.parents('tr');
        /** @var    Number of columns in the key */
        var rows = $anchor.parents('td').attr('rowspan') || 1;
        /** @var    Rows that should be hidden */
        var $rows_to_hide = $curr_row;
        for (var i = 1, $last_row = $curr_row.next(); i < rows; i++, $last_row = $last_row.next()) {
            $rows_to_hide = $rows_to_hide.add($last_row);
        }

        var question = escapeHtml(
            $curr_row.children('td')
                .children('.drop_primary_key_index_msg')
                .val()
        );

        $anchor.PMA_confirm(question, $anchor.attr('href'), function (url) {
            var $msg = PMA_ajaxShowMessage(PMA_messages.strDroppingPrimaryKeyIndex, false);
            $.get(url, {'is_js_confirmed': 1, 'ajax_request': true}, function (data) {
                if (typeof data !== 'undefined' && data.success === true) {
                    PMA_ajaxRemoveMessage($msg);
                    var $table_ref = $rows_to_hide.closest('table');
                    if ($rows_to_hide.length == $table_ref.find('tbody > tr').length) {
                        // We are about to remove all rows from the table
                        $table_ref.hide('medium', function () {
                            $('div.no_indexes_defined').show('medium');
                            $rows_to_hide.remove();
                        });
                        $table_ref.siblings('div.notice').hide('medium');
                    } else {
                        // We are removing some of the rows only
                        toggleRowColors($rows_to_hide.last().next());
                        $rows_to_hide.hide("medium", function () {
                            $(this).remove();
                        });
                    }
                    if ($('.result_query').length) {
                        $('.result_query').remove();
                    }
                    if (data.sql_query) {
                        $('<div class="result_query"></div>')
                            .html(data.sql_query)
                            .prependTo('#structure_content');
                        PMA_highlightSQL($('#page_content'));
                    }
                    PMA_commonActions.refreshMain(false, function () {
                        $("a.ajax[href^=#indexes]").click();
                    });
                    PMA_reloadNavigation();
                } else {
                    PMA_ajaxShowMessage(PMA_messages.strErrorProcessingRequest + " : " + data.error, false);
                }
            }); // end $.get()
        }); // end $.PMA_confirm()
    }); //end Drop Primary Key/Index

    /**
     *Ajax event handler for index edit
    **/
    $(document).on('click', "#table_index tbody tr td.edit_index.ajax, #indexes .add_index.ajax", function (event) {
        event.preventDefault();
        var url, title;
        if ($(this).find("a").length === 0) {
            // Add index
            var valid = checkFormElementInRange(
                $(this).closest('form')[0],
                'added_fields',
                'Column count has to be larger than zero.'
            );
            if (! valid) {
                return;
            }
            url = $(this).closest('form').serialize();
            title = PMA_messages.strAddIndex;
        } else {
            // Edit index
            url = $(this).find("a").attr("href");
            if (url.substring(0, 16) == "tbl_indexes.php?") {
                url = url.substring(16, url.length);
            }
            title = PMA_messages.strEditIndex;
        }
        url += "&ajax_request=true";
        indexEditorDialog(url, title, function () {
            // refresh the page using ajax
            PMA_commonActions.refreshMain(false, function () {
                $("a.ajax[href^=#indexes]").click();
            });
        });
    });

    /**
     * Ajax event handler for advanced index creation during table creation
     * and column addition.
     */
    $('body').on('change', 'select[name*="field_key"]', function () {
        // Index of column on Table edit and create page.
        var col_index = /\d+/.exec($(this).attr('name'));
        col_index = col_index[0];
        // Choice of selected index.
        var index_choice = /[a-z]+/.exec($(this).val());
        index_choice = index_choice[0];
        // Array containing corresponding indexes.
        var source_array = null;

        if (index_choice == 'none') {
            PMA_removeColumnFromIndex(col_index);
            return false;
        }

        // Select a source array.
        var source_array = PMA_getIndexArray(index_choice);
        if (source_array == null) {
            return;
        }

        if (source_array.length === 0) {
            var index = {
                'Key_name': (index_choice == 'primary' ? 'PRIMARY' : ''),
                'Index_choice': index_choice.toUpperCase()
            };
            PMA_showAddIndexDialog(source_array, 0, [col_index], col_index, index);
        } else {
            if (index_choice == 'primary') {
                var array_index = 0;
                var source_length = source_array[array_index].columns.length;
                var target_columns = [];
                for (var i=0; i<source_length; i++) {
                    target_columns.push(source_array[array_index].columns[i].col_index);
                }
                target_columns.push(col_index);

                PMA_showAddIndexDialog(source_array, array_index, target_columns, col_index,
                 source_array[array_index]);
            } else {
                // If there are multiple columns selected for an index, show advanced dialog.
                PMA_indexTypeSelectionDialog(source_array, index_choice, col_index);
            }
        }
    });

    $(document).on('click', '.show_index_dialog', function (e) {
        e.preventDefault();

        // Get index details.
        var previous_index = $(this).prev('select')
            .attr('data-index')
            .split(',');

        var index_choice = previous_index[0];
        var array_index  = previous_index[1];

        var source_array = PMA_getIndexArray(index_choice);
        var source_length = source_array[array_index].columns.length;

        var target_columns = [];
        for (var i = 0; i < source_length; i++) {
            target_columns.push(source_array[array_index].columns[i].col_index);
        }

        PMA_showAddIndexDialog(source_array, array_index, target_columns, -1, source_array[array_index]);
    })
});
;

