<?php 
use Laravel\Cashier\Cashier;?>
@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets-bottom')
@include('frontend.partials.sidenavRegisterAndLoginJS')
<script>

    var botmanWidget = {
    title:'Streamly Bot',
    introMessage: 'Hello, I am a Streamly Bot! I am here to assist you and answer all your questions about our products and services!',
    mainColor:'#004E92',
    aboutText:'',
    bubbleBackground:'#004E92',
    headerTextColor: '#fff',
};

</script>

<script>
    !function(t){function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}var n={};e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:o})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/",e(e.s=30)}([function(t,e,n){"use strict";function o(t){return"[object Array]"===C.call(t)}function r(t){return"[object ArrayBuffer]"===C.call(t)}function i(t){return"undefined"!=typeof FormData&&t instanceof FormData}function a(t){return"undefined"!=typeof ArrayBuffer&&ArrayBuffer.isView?ArrayBuffer.isView(t):t&&t.buffer&&t.buffer instanceof ArrayBuffer}function s(t){return"string"==typeof t}function c(t){return"number"==typeof t}function u(t){return void 0===t}function p(t){return null!==t&&"object"==typeof t}function f(t){return"[object Date]"===C.call(t)}function l(t){return"[object File]"===C.call(t)}function d(t){return"[object Blob]"===C.call(t)}function h(t){return"[object Function]"===C.call(t)}function m(t){return p(t)&&h(t.pipe)}function b(t){return"undefined"!=typeof URLSearchParams&&t instanceof URLSearchParams}function v(t){return t.replace(/^\s*/,"").replace(/\s*$/,"")}function g(){return("undefined"==typeof navigator||"ReactNative"!==navigator.product)&&("undefined"!=typeof window&&"undefined"!=typeof document)}function y(t,e){if(null!==t&&void 0!==t)if("object"!=typeof t&&(t=[t]),o(t))for(var n=0,r=t.length;r>n;n++)e.call(null,t[n],n,t);else for(var i in t)Object.prototype.hasOwnProperty.call(t,i)&&e.call(null,t[i],i,t)}function w(){function t(t,n){e[n]="object"==typeof e[n]&&"object"==typeof t?w(e[n],t):t}for(var e={},n=0,o=arguments.length;o>n;n++)y(arguments[n],t);return e}function x(t,e,n){return y(e,function(e,o){t[o]=n&&"function"==typeof e?_(e,n):e}),t}var _=n(3),O=n(12),C=Object.prototype.toString;t.exports={isArray:o,isArrayBuffer:r,isBuffer:O,isFormData:i,isArrayBufferView:a,isString:s,isNumber:c,isObject:p,isUndefined:u,isDate:f,isFile:l,isBlob:d,isFunction:h,isStream:m,isURLSearchParams:b,isStandardBrowserEnv:g,forEach:y,merge:w,extend:x,trim:v}},function(t,e,n){"use strict";function o(){}function r(t,e){var n,r,i,a,s=R;for(a=arguments.length;a-- >2;)U.push(arguments[a]);for(e&&null!=e.children&&(U.length||U.push(e.children),delete e.children);U.length;)if((r=U.pop())&&void 0!==r.pop)for(a=r.length;a--;)U.push(r[a]);else"boolean"==typeof r&&(r=null),(i="function"!=typeof t)&&(null==r?r="":"number"==typeof r?r+="":"string"!=typeof r&&(i=!1)),i&&n?s[s.length-1]+=r:s===R?s=[r]:s.push(r),n=i;var c=new o;return c.nodeName=t,c.children=s,c.attributes=null==e?void 0:e,c.key=null==e?void 0:e.key,void 0!==T.vnode&&T.vnode(c),c}function i(t,e){for(var n in e)t[n]=e[n];return t}function a(t){!t._dirty&&(t._dirty=!0)&&1==M.push(t)&&(T.debounceRendering||P)(s)}function s(){var t,e=M;for(M=[];t=e.pop();)t._dirty&&S(t)}function c(t,e,n){return"string"==typeof e||"number"==typeof e?void 0!==t.splitText:"string"==typeof e.nodeName?!t._componentConstructor&&u(t,e.nodeName):n||t._componentConstructor===e.nodeName}function u(t,e){return t.normalizedNodeName===e||t.nodeName.toLowerCase()===e.toLowerCase()}function p(t){var e=i({},t.attributes);e.children=t.children;var n=t.nodeName.defaultProps;if(void 0!==n)for(var o in n)void 0===e[o]&&(e[o]=n[o]);return e}function f(t,e){var n=e?document.createElementNS("http://www.w3.org/2000/svg",t):document.createElement(t);return n.normalizedNodeName=t,n}function l(t){var e=t.parentNode;e&&e.removeChild(t)}function d(t,e,n,o,r){if("className"===e&&(e="class"),"key"===e);else if("ref"===e)n&&n(null),o&&o(t);else if("class"!==e||r)if("style"===e){if(o&&"string"!=typeof o&&"string"!=typeof n||(t.style.cssText=o||""),o&&"object"==typeof o){if("string"!=typeof n)for(var i in n)i in o||(t.style[i]="");for(var i in o)t.style[i]="number"==typeof o[i]&&!1===F.test(i)?o[i]+"px":o[i]}}else if("dangerouslySetInnerHTML"===e)o&&(t.innerHTML=o.__html||"");else if("o"==e[0]&&"n"==e[1]){var a=e!==(e=e.replace(/Capture$/,""));e=e.toLowerCase().substring(2),o?n||t.addEventListener(e,m,a):t.removeEventListener(e,m,a),(t._listeners||(t._listeners={}))[e]=o}else if("list"!==e&&"type"!==e&&!r&&e in t)h(t,e,null==o?"":o),null!=o&&!1!==o||t.removeAttribute(e);else{var s=r&&e!==(e=e.replace(/^xlink\:?/,""));null==o||!1===o?s?t.removeAttributeNS("http://www.w3.org/1999/xlink",e.toLowerCase()):t.removeAttribute(e):"function"!=typeof o&&(s?t.setAttributeNS("http://www.w3.org/1999/xlink",e.toLowerCase(),o):t.setAttribute(e,o))}else t.className=o||""}function h(t,e,n){try{t[e]=n}catch(t){}}function m(t){return this._listeners[t.type](T.event&&T.event(t)||t)}function b(){for(var t;t=L.pop();)T.afterMount&&T.afterMount(t),t.componentDidMount&&t.componentDidMount()}function v(t,e,n,o,r,i){q++||(H=null!=r&&void 0!==r.ownerSVGElement,z=null!=t&&!("__preactattr_"in t));var a=g(t,e,n,o,i);return r&&a.parentNode!==r&&r.appendChild(a),--q||(z=!1,i||b()),a}function g(t,e,n,o,r){var i=t,a=H;if(null!=e&&"boolean"!=typeof e||(e=""),"string"==typeof e||"number"==typeof e)return t&&void 0!==t.splitText&&t.parentNode&&(!t._component||r)?t.nodeValue!=e&&(t.nodeValue=e):(i=document.createTextNode(e),t&&(t.parentNode&&t.parentNode.replaceChild(i,t),w(t,!0))),i.__preactattr_=!0,i;var s=e.nodeName;if("function"==typeof s)return E(t,e,n,o);if(H="svg"===s||"foreignObject"!==s&&H,s+="",(!t||!u(t,s))&&(i=f(s,H),t)){for(;t.firstChild;)i.appendChild(t.firstChild);t.parentNode&&t.parentNode.replaceChild(i,t),w(t,!0)}var c=i.firstChild,p=i.__preactattr_,l=e.children;if(null==p){p=i.__preactattr_={};for(var d=i.attributes,h=d.length;h--;)p[d[h].name]=d[h].value}return!z&&l&&1===l.length&&"string"==typeof l[0]&&null!=c&&void 0!==c.splitText&&null==c.nextSibling?c.nodeValue!=l[0]&&(c.nodeValue=l[0]):(l&&l.length||null!=c)&&y(i,l,n,o,z||null!=p.dangerouslySetInnerHTML),_(i,e.attributes,p),H=a,i}function y(t,e,n,o,r){var i,a,s,u,p,f=t.childNodes,d=[],h={},m=0,b=0,v=f.length,y=0,x=e?e.length:0;if(0!==v)for(var _=0;v>_;_++){var O=f[_],C=O.__preactattr_,j=x&&C?O._component?O._component.__key:C.key:null;null!=j?(m++,h[j]=O):(C||(void 0!==O.splitText?!r||O.nodeValue.trim():r))&&(d[y++]=O)}if(0!==x)for(var _=0;x>_;_++){u=e[_],p=null;var j=u.key;if(null!=j)m&&void 0!==h[j]&&(p=h[j],h[j]=void 0,m--);else if(!p&&y>b)for(i=b;y>i;i++)if(void 0!==d[i]&&c(a=d[i],u,r)){p=a,d[i]=void 0,i===y-1&&y--,i===b&&b++;break}p=g(p,u,n,o),s=f[_],p&&p!==t&&p!==s&&(null==s?t.appendChild(p):p===s.nextSibling?l(s):t.insertBefore(p,s))}if(m)for(var _ in h)void 0!==h[_]&&w(h[_],!1);for(;y>=b;)void 0!==(p=d[y--])&&w(p,!1)}function w(t,e){var n=t._component;n?B(n):(null!=t.__preactattr_&&t.__preactattr_.ref&&t.__preactattr_.ref(null),!1!==e&&null!=t.__preactattr_||l(t),x(t))}function x(t){for(t=t.lastChild;t;){var e=t.previousSibling;w(t,!0),t=e}}function _(t,e,n){var o;for(o in n)e&&null!=e[o]||null==n[o]||d(t,o,n[o],n[o]=void 0,H);for(o in e)"children"===o||"innerHTML"===o||o in n&&e[o]===("value"===o||"checked"===o?t[o]:n[o])||d(t,o,n[o],n[o]=e[o],H)}function O(t){var e=t.constructor.name;(W[e]||(W[e]=[])).push(t)}function C(t,e,n){var o,r=W[t.name];if(t.prototype&&t.prototype.render?(o=new t(e,n),N.call(o,e,n)):(o=new N(e,n),o.constructor=t,o.render=j),r)for(var i=r.length;i--;)if(r[i].constructor===t){o.nextBase=r[i].nextBase,r.splice(i,1);break}return o}function j(t,e,n){return this.constructor(t,n)}function k(t,e,n,o,r){t._disable||(t._disable=!0,(t.__ref=e.ref)&&delete e.ref,(t.__key=e.key)&&delete e.key,!t.base||r?t.componentWillMount&&t.componentWillMount():t.componentWillReceiveProps&&t.componentWillReceiveProps(e,o),o&&o!==t.context&&(t.prevContext||(t.prevContext=t.context),t.context=o),t.prevProps||(t.prevProps=t.props),t.props=e,t._disable=!1,0!==n&&(1!==n&&!1===T.syncComponentUpdates&&t.base?a(t):S(t,1,r)),t.__ref&&t.__ref(t))}function S(t,e,n,o){if(!t._disable){var r,a,s,c=t.props,u=t.state,f=t.context,l=t.prevProps||c,d=t.prevState||u,h=t.prevContext||f,m=t.base,g=t.nextBase,y=m||g,x=t._component,_=!1;if(m&&(t.props=l,t.state=d,t.context=h,2!==e&&t.shouldComponentUpdate&&!1===t.shouldComponentUpdate(c,u,f)?_=!0:t.componentWillUpdate&&t.componentWillUpdate(c,u,f),t.props=c,t.state=u,t.context=f),t.prevProps=t.prevState=t.prevContext=t.nextBase=null,t._dirty=!1,!_){r=t.render(c,u,f),t.getChildContext&&(f=i(i({},f),t.getChildContext()));var O,j,E=r&&r.nodeName;if("function"==typeof E){var N=p(r);a=x,a&&a.constructor===E&&N.key==a.__key?k(a,N,1,f,!1):(O=a,t._component=a=C(E,N,f),a.nextBase=a.nextBase||g,a._parentComponent=t,k(a,N,0,f,!1),S(a,1,n,!0)),j=a.base}else s=y,O=x,O&&(s=t._component=null),(y||1===e)&&(s&&(s._component=null),j=v(s,r,f,n||!m,y&&y.parentNode,!0));if(y&&j!==y&&a!==x){var A=y.parentNode;A&&j!==A&&(A.replaceChild(j,y),O||(y._component=null,w(y,!1)))}if(O&&B(O),t.base=j,j&&!o){for(var U=t,R=t;R=R._parentComponent;)(U=R).base=j;j._component=U,j._componentConstructor=U.constructor}}if(!m||n?L.unshift(t):_||(t.componentDidUpdate&&t.componentDidUpdate(l,d,h),T.afterUpdate&&T.afterUpdate(t)),null!=t._renderCallbacks)for(;t._renderCallbacks.length;)t._renderCallbacks.pop().call(t);q||o||b()}}function E(t,e,n,o){for(var r=t&&t._component,i=r,a=t,s=r&&t._componentConstructor===e.nodeName,c=s,u=p(e);r&&!c&&(r=r._parentComponent);)c=r.constructor===e.nodeName;return r&&c&&(!o||r._component)?(k(r,u,3,n,o),t=r.base):(i&&!s&&(B(i),t=a=null),r=C(e.nodeName,u,n),t&&!r.nextBase&&(r.nextBase=t,a=null),k(r,u,1,n,o),t=r.base,a&&t!==a&&(a._component=null,w(a,!1))),t}function B(t){T.beforeUnmount&&T.beforeUnmount(t);var e=t.base;t._disable=!0,t.componentWillUnmount&&t.componentWillUnmount(),t.base=null;var n=t._component;n?B(n):e&&(e.__preactattr_&&e.__preactattr_.ref&&e.__preactattr_.ref(null),t.nextBase=e,l(e),O(t),x(e)),t.__ref&&t.__ref(null)}function N(t,e){this._dirty=!0,this.context=e,this.props=t,this.state=this.state||{}}function A(t,e,n){return v(n,t,{},!1,e,!1)}n.d(e,"b",function(){return r}),n.d(e,"a",function(){return N}),n.d(e,"c",function(){return A});var T={},U=[],R=[],P="function"==typeof Promise?Promise.resolve().then.bind(Promise.resolve()):setTimeout,F=/acit|ex(?:s|g|n|p|$)|rph|ows|mnc|ntw|ine[ch]|zoo|^ord/i,M=[],L=[],q=0,H=!1,z=!1,W={};i(N.prototype,{setState:function(t,e){var n=this.state;this.prevState||(this.prevState=i({},n)),i(n,"function"==typeof t?t(n,this.props):t),e&&(this._renderCallbacks=this._renderCallbacks||[]).push(e),a(this)},forceUpdate:function(t){t&&(this._renderCallbacks=this._renderCallbacks||[]).push(t),S(this,2)},render:function(){}})},function(t,e,n){"use strict";function o(t,e){!r.isUndefined(t)&&r.isUndefined(t["Content-Type"])&&(t["Content-Type"]=e)}var r=n(0),i=n(14),a={"Content-Type":"application/x-www-form-urlencoded"},s={adapter:function(){var t;return"undefined"!=typeof XMLHttpRequest?t=n(4):"undefined"!=typeof process&&(t=n(4)),t}(),transformRequest:[function(t,e){return i(e,"Content-Type"),r.isFormData(t)||r.isArrayBuffer(t)||r.isBuffer(t)||r.isStream(t)||r.isFile(t)||r.isBlob(t)?t:r.isArrayBufferView(t)?t.buffer:r.isURLSearchParams(t)?(o(e,"application/x-www-form-urlencoded;charset=utf-8"),""+t):r.isObject(t)?(o(e,"application/json;charset=utf-8"),JSON.stringify(t)):t}],transformResponse:[function(t){if("string"==typeof t)try{t=JSON.parse(t)}catch(t){}return t}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,validateStatus:function(t){return t>=200&&300>t}};s.headers={common:{Accept:"application/json, text/plain, */*"}},r.forEach(["delete","get","head"],function(t){s.headers[t]={}}),r.forEach(["post","put","patch"],function(t){s.headers[t]=r.merge(a)}),t.exports=s},function(t){"use strict";t.exports=function(t,e){return function(){for(var n=Array(arguments.length),o=0;n.length>o;o++)n[o]=arguments[o];return t.apply(e,n)}}},function(t,e,n){"use strict";var o=n(0),r=n(15),i=n(17),a=n(18),s=n(19),c=n(5),u="undefined"!=typeof window&&window.btoa&&window.btoa.bind(window)||n(20);t.exports=function(t){return new Promise(function(e,p){var f=t.data,l=t.headers;o.isFormData(f)&&delete l["Content-Type"];var d=new XMLHttpRequest,h="onreadystatechange",m=!1;if("undefined"==typeof window||!window.XDomainRequest||"withCredentials"in d||s(t.url)||(d=new window.XDomainRequest,h="onload",m=!0,d.onprogress=function(){},d.ontimeout=function(){}),t.auth){l.Authorization="Basic "+u((t.auth.username||"")+":"+(t.auth.password||""))}if(d.open(t.method.toUpperCase(),i(t.url,t.params,t.paramsSerializer),!0),d.timeout=t.timeout,d[h]=function(){if(d&&(4===d.readyState||m)&&(0!==d.status||d.responseURL&&0===d.responseURL.indexOf("file:"))){var n="getAllResponseHeaders"in d?a(d.getAllResponseHeaders()):null;r(e,p,{data:t.responseType&&"text"!==t.responseType?d.response:d.responseText,status:1223===d.status?204:d.status,statusText:1223===d.status?"No Content":d.statusText,headers:n,config:t,request:d}),d=null}},d.onerror=function(){p(c("Network Error",t,null,d)),d=null},d.ontimeout=function(){p(c("timeout of "+t.timeout+"ms exceeded",t,"ECONNABORTED",d)),d=null},o.isStandardBrowserEnv()){var b=n(21),v=(t.withCredentials||s(t.url))&&t.xsrfCookieName?b.read(t.xsrfCookieName):void 0;v&&(l[t.xsrfHeaderName]=v)}if("setRequestHeader"in d&&o.forEach(l,function(t,e){void 0===f&&"content-type"===e.toLowerCase()?delete l[e]:d.setRequestHeader(e,t)}),t.withCredentials&&(d.withCredentials=!0),t.responseType)try{d.responseType=t.responseType}catch(e){if("json"!==t.responseType)throw e}"function"==typeof t.onDownloadProgress&&d.addEventListener("progress",t.onDownloadProgress),"function"==typeof t.onUploadProgress&&d.upload&&d.upload.addEventListener("progress",t.onUploadProgress),t.cancelToken&&t.cancelToken.promise.then(function(t){d&&(d.abort(),p(t),d=null)}),void 0===f&&(f=null),d.send(f)})}},function(t,e,n){"use strict";var o=n(16);t.exports=function(t,e,n,r,i){var a=Error(t);return o(a,e,n,r,i)}},function(t){"use strict";t.exports=function(t){return!(!t||!t.__CANCEL__)}},function(t){"use strict";function e(t){this.message=t}e.prototype.toString=function(){return"Cancel"+(this.message?": "+this.message:"")},e.prototype.__CANCEL__=!0,t.exports=e},,,function(t,e,n){t.exports=n(11)},function(t,e,n){"use strict";function o(t){var e=new a(t),n=i(a.prototype.request,e);return r.extend(n,a.prototype,e),r.extend(n,e),n}var r=n(0),i=n(3),a=n(13),s=n(2),c=o(s);c.Axios=a,c.create=function(t){return o(r.merge(s,t))},c.Cancel=n(7),c.CancelToken=n(27),c.isCancel=n(6),c.all=function(t){return Promise.all(t)},c.spread=n(28),t.exports=c,t.exports.default=c},function(t){function e(t){return!!t.constructor&&"function"==typeof t.constructor.isBuffer&&t.constructor.isBuffer(t)}function n(t){return"function"==typeof t.readFloatLE&&"function"==typeof t.slice&&e(t.slice(0,0))}t.exports=function(t){return null!=t&&(e(t)||n(t)||!!t._isBuffer)}},function(t,e,n){"use strict";function o(t){this.defaults=t,this.interceptors={request:new a,response:new a}}var r=n(2),i=n(0),a=n(22),s=n(23);o.prototype.request=function(t){"string"==typeof t&&(t=i.merge({url:arguments[0]},arguments[1])),t=i.merge(r,{method:"get"},this.defaults,t),t.method=t.method.toLowerCase();var e=[s,void 0],n=Promise.resolve(t);for(this.interceptors.request.forEach(function(t){e.unshift(t.fulfilled,t.rejected)}),this.interceptors.response.forEach(function(t){e.push(t.fulfilled,t.rejected)});e.length;)n=n.then(e.shift(),e.shift());return n},i.forEach(["delete","get","head","options"],function(t){o.prototype[t]=function(e,n){return this.request(i.merge(n||{},{method:t,url:e}))}}),i.forEach(["post","put","patch"],function(t){o.prototype[t]=function(e,n,o){return this.request(i.merge(o||{},{method:t,url:e,data:n}))}}),t.exports=o},function(t,e,n){"use strict";var o=n(0);t.exports=function(t,e){o.forEach(t,function(n,o){o!==e&&o.toUpperCase()===e.toUpperCase()&&(t[e]=n,delete t[o])})}},function(t,e,n){"use strict";var o=n(5);t.exports=function(t,e,n){var r=n.config.validateStatus;n.status&&r&&!r(n.status)?e(o("Request failed with status code "+n.status,n.config,null,n.request,n)):t(n)}},function(t){"use strict";t.exports=function(t,e,n,o,r){return t.config=e,n&&(t.code=n),t.request=o,t.response=r,t}},function(t,e,n){"use strict";function o(t){return encodeURIComponent(t).replace(/%40/gi,"@").replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}var r=n(0);t.exports=function(t,e,n){if(!e)return t;var i;if(n)i=n(e);else if(r.isURLSearchParams(e))i=""+e;else{var a=[];r.forEach(e,function(t,e){null!==t&&void 0!==t&&(r.isArray(t)?e+="[]":t=[t],r.forEach(t,function(t){r.isDate(t)?t=t.toISOString():r.isObject(t)&&(t=JSON.stringify(t)),a.push(o(e)+"="+o(t))}))}),i=a.join("&")}return i&&(t+=(-1===t.indexOf("?")?"?":"&")+i),t}},function(t,e,n){"use strict";var o=n(0),r=["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"];t.exports=function(t){var e,n,i,a={};return t?(o.forEach(t.split("\n"),function(t){if(i=t.indexOf(":"),e=o.trim(t.substr(0,i)).toLowerCase(),n=o.trim(t.substr(i+1)),e){if(a[e]&&r.indexOf(e)>=0)return;a[e]="set-cookie"===e?(a[e]?a[e]:[]).concat([n]):a[e]?a[e]+", "+n:n}}),a):a}},function(t,e,n){"use strict";var o=n(0);t.exports=o.isStandardBrowserEnv()?function(){function t(t){var e=t;return n&&(r.setAttribute("href",e),e=r.href),r.setAttribute("href",e),{href:r.href,protocol:r.protocol?r.protocol.replace(/:$/,""):"",host:r.host,search:r.search?r.search.replace(/^\?/,""):"",hash:r.hash?r.hash.replace(/^#/,""):"",hostname:r.hostname,port:r.port,pathname:"/"===r.pathname.charAt(0)?r.pathname:"/"+r.pathname}}var e,n=/(msie|trident)/i.test(navigator.userAgent),r=document.createElement("a");return e=t(window.location.href),function(n){var r=o.isString(n)?t(n):n;return r.protocol===e.protocol&&r.host===e.host}}():function(){return function(){return!0}}()},function(t){"use strict";function e(){this.message="String contains an invalid character"}function n(t){for(var n,r,i=t+"",a="",s=0,c=o;i.charAt(0|s)||(c="=",s%1);a+=c.charAt(63&n>>8-s%1*8)){if((r=i.charCodeAt(s+=.75))>255)throw new e;n=n<<8|r}return a}var o="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";e.prototype=Error(),e.prototype.code=5,e.prototype.name="InvalidCharacterError",t.exports=n},function(t,e,n){"use strict";var o=n(0);t.exports=o.isStandardBrowserEnv()?function(){return{write:function(t,e,n,r,i,a){var s=[];s.push(t+"="+encodeURIComponent(e)),o.isNumber(n)&&s.push("expires="+new Date(n).toGMTString()),o.isString(r)&&s.push("path="+r),o.isString(i)&&s.push("domain="+i),!0===a&&s.push("secure"),document.cookie=s.join("; ")},read:function(t){var e=document.cookie.match(RegExp("(^|;\\s*)("+t+")=([^;]*)"));return e?decodeURIComponent(e[3]):null},remove:function(t){this.write(t,"",Date.now()-864e5)}}}():function(){return{write:function(){},read:function(){return null},remove:function(){}}}()},function(t,e,n){"use strict";function o(){this.handlers=[]}var r=n(0);o.prototype.use=function(t,e){return this.handlers.push({fulfilled:t,rejected:e}),this.handlers.length-1},o.prototype.eject=function(t){this.handlers[t]&&(this.handlers[t]=null)},o.prototype.forEach=function(t){r.forEach(this.handlers,function(e){null!==e&&t(e)})},t.exports=o},function(t,e,n){"use strict";function o(t){t.cancelToken&&t.cancelToken.throwIfRequested()}var r=n(0),i=n(24),a=n(6),s=n(2),c=n(25),u=n(26);t.exports=function(t){return o(t),t.baseURL&&!c(t.url)&&(t.url=u(t.baseURL,t.url)),t.headers=t.headers||{},t.data=i(t.data,t.headers,t.transformRequest),t.headers=r.merge(t.headers.common||{},t.headers[t.method]||{},t.headers||{}),r.forEach(["delete","get","head","post","put","patch","common"],function(e){delete t.headers[e]}),(t.adapter||s.adapter)(t).then(function(e){return o(t),e.data=i(e.data,e.headers,t.transformResponse),e},function(e){return a(e)||(o(t),e&&e.response&&(e.response.data=i(e.response.data,e.response.headers,t.transformResponse))),Promise.reject(e)})}},function(t,e,n){"use strict";var o=n(0);t.exports=function(t,e,n){return o.forEach(n,function(n){t=n(t,e)}),t}},function(t){"use strict";t.exports=function(t){return/^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(t)}},function(t){"use strict";t.exports=function(t,e){return e?t.replace(/\/+$/,"")+"/"+e.replace(/^\/+/,""):t}},function(t,e,n){"use strict";function o(t){if("function"!=typeof t)throw new TypeError("executor must be a function.");var e;this.promise=new Promise(function(t){e=t});var n=this;t(function(t){n.reason||(n.reason=new r(t),e(n.reason))})}var r=n(7);o.prototype.throwIfRequested=function(){if(this.reason)throw this.reason},o.source=function(){var t;return{token:new o(function(e){t=e}),cancel:t}},t.exports=o},function(t){"use strict";t.exports=function(t){return function(e){return t.apply(null,e)}}},function(t,e,n){"use strict";n.d(e,"e",function(){return o}),n.d(e,"c",function(){return r}),n.d(e,"g",function(){return i}),n.d(e,"h",function(){return a}),n.d(e,"d",function(){return s}),n.d(e,"b",function(){return c}),n.d(e,"f",function(){return u}),n.d(e,"a",function(){return p});var o={position:"fixed",bottom:"20px",right:"20px",zIndex:2147483647,borderRadius:"5px",boxSizing:"content-box",boxShadow:"0px 0px 20px rgba(0, 0, 0, 0.2)",overflow:"hidden"},r={position:"fixed",bottom:"0px",right:"0px",zIndex:2147483647,minWidth:"400px",boxSizing:"content-box",overflow:"hidden",minHeight:"120px"},i={position:"fixed",bottom:"0px",right:"0px",zIndex:2147483647,minWidth:"400px",boxSizing:"content-box",overflow:"hidden",minHeight:"120px"},a={position:"fixed",top:0,right:0,bottom:0,left:0,zIndex:2147483647,width:"100%",height:"100%",overflowY:"visible",boxSizing:"content-box"},s={height:"40px",lineHeight:"30px",fontSize:"20px",display:"flex",justifyContent:"space-between",padding:"5px 0 5px 20px",fontFamily:"Lato, sans-serif",color:"#fff",cursor:"pointer",boxSizing:"content-box",mozBoxSizing:"content-box",webkitBoxSizing:"content-box"},c={display:"flex",justifyContent:"center",position:"absolute",top:"38px",right:"20px",height:"60px",width:"60px",border:0,borderRadius:"50%",boxShadow:"0px 0px 20px rgba(0, 0, 0, 0.2)"},u={display:"block",position:"absolute",top:"46px",right:"20px",height:"52px",width:"52px",border:0,borderRadius:"50%",boxShadow:"0px 0px 20px rgba(0, 0, 0, 0.2)"},p={width:"100%",height:"auto",borderRadius:"999px"}},function(t,e,n){"use strict";function o(t,e){void 0===e&&(e=""),t=t.replace(/[[]/,"\\[").replace(/[]]/,"\\]");var n=RegExp("[\\?&]"+t+"=([^&#]*)"),o=n.exec(document.getElementById("botmanWidget").getAttribute("src"));return null===o?e:decodeURIComponent(o[1].replace(/\+/g," "))}function r(){var t=document.createElement("div");t.id="botmanWidgetRoot",document.getElementsByTagName("body")[0].appendChild(t);var e={};try{e=JSON.parse(o("settings","{}"))}catch(t){}var n=window.botmanWidget||{},r=c({},s.a,e,n),u=r.frameEndpoint;Object(i.c)(Object(i.b)(a.a,{isMobile:500>window.screen.width,iFrameSrc:u,conf:r}),t)}Object.defineProperty(e,"__esModule",{value:!0});var i=n(1),a=n(31),s=n(37),c=this&&this.__assign||Object.assign||function(t){for(var e,n=1,o=arguments.length;o>n;n++){e=arguments[n];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r])}return t};window.attachEvent?window.attachEvent("onload",r):window.addEventListener("load",r,!1)},function(t,e,n){"use strict";var o=n(10),r=n.n(o),i=n(1),a=n(32),s=n(33),c=n(34),u=n(35),p=n(36),f=n(29),l=this&&this.__extends||function(){var t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])};return function(e,n){function o(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(o.prototype=n.prototype,new o)}}(),d=this&&this.__assign||Object.assign||function(t){for(var e,n=1,o=arguments.length;o>n;n++){e=arguments[n];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r])}return t};e.a=function(t){function e(){var e=t.call(this)||this;return e.toggle=function(){var t={pristine:!1,isChatOpen:!e.state.isChatOpen,wasChatOpened:e.state.wasChatOpened};e.state.isChatOpen||e.state.wasChatOpened||(e.props.conf.sendWidgetOpenedEvent&&setTimeout(function(){e.sendOpenEvent()},500),t.wasChatOpened=!0),e.setState(t)},e.state.isChatOpen=!1,e.state.pristine=!0,e.state.wasChatOpened=!1,e}return l(e,t),e.prototype.componentDidMount=function(){window.botmanChatWidget=new p.a(this)},e.prototype.render=function(t,e){var n=t.conf,o=t.isMobile,r=e.isChatOpen,p=e.pristine,l={width:o?n.mobileWidth:n.desktopWidth},h=window.innerHeight-100<n.desktopHeight?window.innerHeight-90:n.desktopHeight;n.wrapperHeight=h;var m;return m=r||!o&&!n.alwaysUseFloatingButton?o?f.h:(r||this.state.wasChatOpened)&&r?d({},f.e,l):d({},f.c):d({},f.g),Object(i.b)("div",{style:m},!o&&!n.alwaysUseFloatingButton||r?(r||this.state.wasChatOpened)&&r?Object(i.b)("div",{style:d({background:n.mainColor},f.d),onClick:this.toggle},Object(i.b)("div",{style:{display:"flex",alignItems:"center",padding:"0px 30px 0px 0px",fontSize:"15px",fontWeight:"normal",color:n.headerTextColor}},n.title),Object(i.b)(u.a,{isOpened:r})):Object(i.b)(c.a,{onClick:this.toggle,conf:n}):Object(i.b)(s.a,{onClick:this.toggle,conf:n}),Object(i.b)("div",{key:"chatframe",style:{display:r?"block":"none",height:o?n.mobileHeight:h}},p?null:Object(i.b)(a.a,d({},this.props))))},e.prototype.open=function(){this.setState({pristine:!1,isChatOpen:!0,wasChatOpened:!0})},e.prototype.close=function(){this.setState({pristine:!1,isChatOpen:!1})},e.prototype.sendOpenEvent=function(){var t=new FormData;t.append("driver","web"),t.append("eventName","widgetOpened"),t.append("eventData",this.props.conf.widgetOpenedEventData),r.a.post(this.props.conf.chatServer,t).then(function(t){(t.data.messages||[]).forEach(function(t){window.botmanChatWidget.writeToMessages(t)})})},e}(i.a)},function(t,e,n){"use strict";var o=n(1),r=this&&this.__extends||function(){var t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])};return function(e,n){function o(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(o.prototype=n.prototype,new o)}}(),i=this&&this.__assign||Object.assign||function(t){for(var e,n=1,o=arguments.length;o>n;n++){e=arguments[n];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r])}return t};e.a=function(t){function e(){return null!==t&&t.apply(this,arguments)||this}return r(e,t),e.prototype.shouldComponentUpdate=function(){return!1},e.prototype.render=function(t){var e=t.iFrameSrc,n=t.isMobile,r=t.conf,a=window.botmanWidget||{},s=encodeURIComponent(JSON.stringify(i({},r,a)));return Object(o.b)("iframe",{id:"chatBotManFrame",src:e+"?conf="+s,width:"100%",height:n?"94%":"100%",frameBorder:"0",allowTransparency:!0,style:"background-color:transparent"})},e}(o.a)},function(t,e,n){"use strict";var o=n(1),r=n(29),i=this&&this.__extends||function(){var t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])};return function(e,n){function o(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(o.prototype=n.prototype,new o)}}(),a=this&&this.__assign||Object.assign||function(t){for(var e,n=1,o=arguments.length;o>n;n++){e=arguments[n];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r])}return t};e.a=function(t){function e(){return null!==t&&t.apply(this,arguments)||this}return i(e,t),e.prototype.render=function(t){var e=t.conf;return Object(o.b)("div",{style:{position:"relative",cursor:"pointer"},onClick:this.props.onClick},Object(o.b)("div",{className:"mobile-closed-message-avatar",style:a({background:e.bubbleBackground},r.f)},""===e.bubbleAvatarUrl?Object(o.b)("svg",{style:{paddingTop:4},fill:"#FFFFFF",height:"24",viewBox:"0 0 24 24",width:"24",xmlns:"http://www.w3.org/2000/svg"},Object(o.b)("path",{d:"M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"}),Object(o.b)("path",{d:"M0 0h24v24H0z",fill:"none"})):-1!==e.bubbleAvatarUrl.indexOf("/")?Object(o.b)("img",{src:e.bubbleAvatarUrl,style:a({},r.a)}):Object(o.b)("div",{style:{display:"flex",alignItems:"center"}},Object(o.b)("br",null),e.bubbleAvatarUrl)))},e}(o.a)},function(t,e,n){"use strict";var o=n(1),r=n(29),i=this&&this.__extends||function(){var t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])};return function(e,n){function o(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(o.prototype=n.prototype,new o)}}(),a=this&&this.__assign||Object.assign||function(t){for(var e,n=1,o=arguments.length;o>n;n++){e=arguments[n];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r])}return t};e.a=function(t){function e(){return null!==t&&t.apply(this,arguments)||this}return i(e,t),e.prototype.render=function(t){var e=t.conf;return Object(o.b)("div",{style:{position:"relative",cursor:"pointer"},onClick:this.props.onClick},Object(o.b)("div",{className:"desktop-closed-message-avatar",style:a({background:e.bubbleBackground},r.b)},""===e.bubbleAvatarUrl?Object(o.b)("svg",{style:{width:"60%",height:"auto"},width:"1792",height:"1792",viewBox:"0 0 1792 1792",xmlns:"http://www.w3.org/2000/svg"},Object(o.b)("path",{d:"M1664 1504v-768q-32 36-69 66-268 206-426 338-51 43-83 67t-86.5 48.5-102.5 24.5h-2q-48 0-102.5-24.5t-86.5-48.5-83-67q-158-132-426-338-37-30-69-66v768q0 13 9.5 22.5t22.5 9.5h1472q13 0 22.5-9.5t9.5-22.5zm0-1051v-24.5l-.5-13-3-12.5-5.5-9-9-7.5-14-2.5h-1472q-13 0-22.5 9.5t-9.5 22.5q0 168 147 284 193 152 401 317 6 5 35 29.5t46 37.5 44.5 31.5 50.5 27.5 43 9h2q20 0 43-9t50.5-27.5 44.5-31.5 46-37.5 35-29.5q208-165 401-317 54-43 100.5-115.5t46.5-131.5zm128-37v1088q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1472q66 0 113 47t47 113z",fill:"#fff"})):-1!==e.bubbleAvatarUrl.indexOf("/")?Object(o.b)("img",{src:e.bubbleAvatarUrl,style:a({},r.a)}):Object(o.b)("div",{style:{display:"flex",alignItems:"center"}},Object(o.b)("br",null),e.bubbleAvatarUrl)))},e}(o.a)},function(t,e,n){"use strict";var o=n(1),r=this&&this.__extends||function(){var t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])};return function(e,n){function o(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(o.prototype=n.prototype,new o)}}();e.a=function(t){function e(){return null!==t&&t.apply(this,arguments)||this}return r(e,t),e.prototype.render=function(t){var e=t.isOpened;return Object(o.b)("div",null,e?Object(o.b)("svg",{style:{marginRight:15,marginTop:6,verticalAlign:"middle"},fill:"#FFFFFF",height:"15",viewBox:"0 0 15 15",width:"15",xmlns:"http://www.w3.org/2000/svg"},Object(o.b)("line",{x1:"1",y1:"15",x2:"15",y2:"1",stroke:"white","stroke-width":"1"}),Object(o.b)("line",{x1:"1",y1:"1",x2:"15",y2:"15",stroke:"white","stroke-width":"1"})):Object(o.b)("svg",{style:{marginRight:15,marginTop:6,verticalAlign:"middle"},fill:"#FFFFFF",height:"24",viewBox:"0 0 24 24",width:"24",xmlns:"http://www.w3.org/2000/svg"},Object(o.b)("path",{d:"M2.582 13.891c-0.272 0.268-0.709 0.268-0.979 0s-0.271-0.701 0-0.969l7.908-7.83c0.27-0.268 0.707-0.268 0.979 0l7.908 7.83c0.27 0.268 0.27 0.701 0 0.969s-0.709 0.268-0.978 0l-7.42-7.141-7.418 7.141z"})))},e}(o.a)},function(t,e){"use strict";e.a=function(){function t(t){this.widget=t}return t.prototype.open=function(){this.widget.open()},t.prototype.close=function(){this.widget.close()},t.prototype.toggle=function(){this.widget.toggle()},t.prototype.isOpen=function(){return!0===this.widget.state.isChatOpen},t.prototype.callChatWidget=function(t){if(this.isOpen())document.getElementById("chatBotManFrame").contentWindow.postMessage(t,"*");else try{this.open(),setTimeout(function(){document.getElementById("chatBotManFrame").contentWindow.postMessage(t,"*")},750)}catch(t){}},t.prototype.writeToMessages=function(t){this.callChatWidget({method:"writeToMessages",params:[t]})},t.prototype.sayAsBot=function(t){this.callChatWidget({method:"sayAsBot",params:[t]})},t.prototype.say=function(t){this.callChatWidget({method:"say",params:[t]})},t.prototype.whisper=function(t){this.callChatWidget({method:"whisper",params:[t]})},t}()},function(t,e,n){"use strict";n.d(e,"a",function(){return o});var o={chatServer:"/botman",frameEndpoint:"/botman/chat",timeFormat:"HH:MM",dateTimeFormat:"m/d/yy HH:MM",title:"BotMan Widget",cookieValidInDays:1,introMessage:"",placeholderText:"Send a message...",displayMessageTime:!0,sendWidgetOpenedEvent:!1,widgetOpenedEventData:"",mainColor:"#408591",headerTextColor:"#333",bubbleBackground:"#408591",bubbleAvatarUrl:"",desktopHeight:450,desktopWidth:370,mobileHeight:"100%",mobileWidth:"300px",videoHeight:160,aboutLink:"https://botman.io",aboutText:"⚡ Powered by BotMan",chatId:"",userId:"",alwaysUseFloatingButton:!1}}]);
</script>

<!-- <script defer src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> -->
@endsection
@section('stylesheets')
    <style>
 #botmanWidgetRoot>div{
    z-index: 1000 !important;
 }

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
        	
	.css-thjkr7,
	[data-css-thjkr7] {
		position: relative;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 0;
		margin: 0;
		/* background: -webkit-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: -moz-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box; */
		/* background-color: #15101E; */
		background-image: null;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}
    .css-5nata0,
	[data-css-5nata0] {
		width: 100%;
		height: 100%;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		/* padding: 130px 0 44px; */
		position: relative;
		overflow: hidden;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}

	.css-5nata0 .upper-section,
	[data-css-5nata0] .upper-section {
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		width: 100%;
		margin-bottom: 52px;
		-webkit-box-lines: multiple;
		-webkit-flex-wrap: wrap;
		-webkit-box-orient: horizontal;
		-webkit-box-direction: normal;
		-webkit-flex-direction: row;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
	}
	
	.css-5nata0 .upper-section .content,
	[data-css-5nata0] .upper-section .content {
		position: absolute;
		left: 100px;
	}
	
	.css-5nata0 .upper-section .content .title,
	[data-css-5nata0] .upper-section .content .title {
		font-size: 48px;
		font-weight: 800;
		letter-spacing: 0.25px;
		max-width: 856px;
		margin-bottom: 20px;
	}
	
	.css-5nata0 .upper-section .content .sub-title,
	[data-css-5nata0] .upper-section .content .sub-title {
		font-size: 18px;
		line-height: 25px;
		font-weight: 500;
		letter-spacing: 0.19px;
		max-width: 856px;
	}
	
    .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                margin: 26px 0 20px;
                padding: 8px 30px;
                font-size: 16px;
                line-height: 21px;
                background-color: transparent;
                border: 2px solid #FFFFFF;
                border-radius: 20px;
                align-self: flex-start;
                -webkit-align-self: flex-start;
            }

            .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                position: relative;
                right: -35%;
                width: 100%;
                max-width: 1200px;
            }

            .css-5nata0 .upper-section .devices-preview img,[data-css-5nata0] .upper-section .devices-preview img {
                width: 100%;
            }

            .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                width: 100%;
                height: auto;
                padding: 0 36px;
            }

            @media(max-width: 1100px) {
                .css-5nata0,[data-css-5nata0] {
                    padding:100px 0;
                }

                .css-5nata0 .upper-section .content,[data-css-5nata0] .upper-section .content {
                    position: unset;
                    margin-bottom: 60px;
                    display: -webkit-box;
                    display: -moz-box;
                    display: -ms-flexbox;
                    display: -webkit-flex;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    -webkit-box-align: center;
                    -webkit-align-items: center;
                }

                .css-5nata0 .upper-section .content .title br,[data-css-5nata0] .upper-section .content .title br {
                    display: none;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }

                .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                    right: 0;
                }
            }

            @media(max-width: 768px) {
                .css-5nata0 .upper-section .content .title,[data-css-5nata0] .upper-section .content .title {
                    font-size:32px;
                    letter-spacing: 0.34px;
                }

                .css-5nata0 .upper-section .content .sub-title,[data-css-5nata0] .upper-section .content .sub-title {
                    font-size: 16px;
                    letter-spacing: 0.17px;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }
            }

            @media(max-width: 425px) {
                .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                    padding:0 10px;
                }
            }

/* for genre channel items*/

.item1{
    height: 80px;
    width: 80px;
    
    /* background-color:#fff; */
}

.grid-container > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  /* justify-content: center;
  align-items: center; */
  font-size: 2em;
  /* color: #20263f; */
  border-radius: 15px;
      background-color:  #4D5659; 
      text-align: center;
      padding: 10px 0;
}

.grid-container {
/* width: 100%; */
  display: -ms-grid;
  display: grid;
  grid-gap: 40px;
  grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
  grid-auto-rows: 50px;
  justify-items: center;
  
  -webkit-box-pack: space-evenly;
  
      -ms-flex-pack: space-evenly;
  
          justify-content: space-evenly;
  padding: 10px;
    }
    .grid-container > div.item1 > img {
  width: 100%;
  height: 100%;
  -o-object-fit: contain;
     object-fit: contain;
}

/*devices circle*/

.flex-container {
        width: 100%;   
    
  padding: 0;
  margin: 0;
  list-style: none;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
      flex-wrap: wrap;
}

/* Responsive layout - makes a one column-layout instead of two-column layout */
@media (max-width: 800px) {
    
    .flex-container>div {
    text-align: center ;
    -webkit-box-flex: 0;
        -ms-flex: 0 50%;
            flex: 0 50%;
  }
  .flex-container>div:not(:nth-last-child(-n+2))  {
    /* background-color: red; */
    padding-bottom: 5%;
}
}

.space-evenly { 
  -webkit-box-pack: space-evenly; 
      -ms-flex-pack: space-evenly; 
          justify-content: space-evenly; 
}
    .hovicon {
        display: inline-block;
        font-size: 45px;
        line-height: 90px;
        cursor: pointer;
        margin: 20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        text-align: center;
        position: relative;
        text-decoration: none;
        z-index: 1;
        color: #fff;
    }
    
.hovicon:after {
    pointer-events: none;
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    content:'';
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}
.hovicon:before {
    speak: none;
    font-size: 48px;
    line-height: 90px;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    display: block;
    -webkit-font-smoothing: antialiased;
}
/* Effect 1 */
.hovicon.effect-1 {
    background: rgba(255, 255, 255, 0.1);
    -webkit-transition: background 0.2s, color 0.2s;
    -moz-transition: background 0.2s, color 0.2s;
    transition: background 0.2s, color 0.2s;
}
.hovicon.effect-1:after {
    top: -7px;
    left: -7px;
    padding: 7px;
    box-shadow: 0 0 0 4px #FF712F;
    -webkit-transition: -webkit-transform 0.2s, opacity 0.2s;
    -webkit-transform: scale(1);
    -moz-transition: -moz-transform 0.2s, opacity 0.2s;
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transition: transform 0.2s, opacity 0.2s;
    transform: scale(1);
    opacity: 0;
    
}
/* Effect 1a */
 .hovicon.effect-1.sub-a:hover {
    background: #000428;
    
}
.hovicon.effect-1.sub-a:hover i {
    
}
.hovicon.effect-1.sub-a:hover:after {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transform: scale(1);
    opacity: 1;
}

circle {
  fill: white;
  fill-opacity: 0;
  stroke: #FF712F;
  stroke-width: 2px;
  transition: stroke-opacity 0.8s, stroke-width 0.8s, transform 0.8s;
  transform-origin: 50% 50%;
  stroke-opacity: 0;
}

circle:hover {
  stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
}

.circle
{ width:140px;
    height:140px;
    border-radius:70px;
    font-size:20px;
    color:#fff;
    line-height:100px;
    text-align:center;
    background:#000;
    }
    #roku{

      background-image: url("../frontend/img/newicon/roku.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #amazonfiretv{

      background-image: url("../frontend/img/newicon/andriodtv.png");

/*background-size: cover;*/
background-size: 100px;
background-repeat: no-repeat;
background-position: center;
    }
    #appletv{

        background-image: url("../frontend/img/newicon/appletv.png");

/*background-size: cover;*/
background-size: 60px;
background-repeat: no-repeat;
background-position: center;
    }
    #iostv{

      background-image: url("../frontend/images/s/phones-and-tablets/apple_logo_360.png");

/*background-size: cover;*/
background-size: 50px;
background-repeat: no-repeat;
background-position: center;
    } 
     #android{

      background-image: url("../frontend/img/newicon/android.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #samsungtv{

      background-image: url("../frontend/img/newicon/smarttv.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }

    .circle:hover .innerCircle{
      stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
    }
	
    </style>
@endsection
@section('content')
    {{-- {{$errors}} --}}
    {{-- @if($errors->any())
         <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif --}}

    

	<!-- Masthead-->
	<header class="masthead">
	    <div class="container">
            <p style=" text-transform: uppercase;"><strong>Your alternative to Cable!</strong></p>
	        <div class="masthead-heading">
                <img src="{{ asset('frontend/img/streamlylogo5.png') }}"  loading="lazy" alt="logo of streamly" class="img-fluid" />
                </div>
	       
            <p ><strong>Watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's </strong> </p>
            <p>STREAMLY good channels. STREAMLY flexible terms. STREAMLY fair pricing!</p>
	        <a class="btn btn-primary btn-xl js-scroll-trigger " onclick="openNavReg()" href="javascript:void(0);">START TODAY</a>
	    </div>
	</header>


    @include('frontend.partials.sidenavRegisterAndLogin')

{{--   
	<div class="modal fade" id="resetPassword">
        
	    <div class="modal-dialog">
			<div class="modal-content"><h3 class="login_txt" style="
                padding-left: 20px;
                padding-top: 20px;
            ">Forget password</h3>
				<div class="modal-body text-center">
					@if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          {{-- <form action="{{ url('/forgotpassword') }}" method="POST"> --}}
             {{-- <form method="POST" action="{{ route('password.email') }}"> --}}
            {!! csrf_field() !!}
						{{-- <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="control-label">Send reset password link at                        </label>
							
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
						</div>


						<div class="text-center">
							<button type="submit" class="btn btn-lg login-btn" >
								Email me!
							</button> --}}
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						{{-- </div>
					</form>
				</div>

			</div>
	    </div>
	</div> --}}
    <style>
    
details > summary {
    list-style-type: none;
}

details > summary::-webkit-details-marker {
    display: none;
}

details > summary::before {
    content: '\002630';
}
details[open] > summary::before {
    content: '\2715';
}</style>

	<!-- Select Plan-->
<section class="page-section ">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Plans</h2>
           
            <div class="row">
                <div class="col-centered" id="tab-buttons">

 @foreach ($plankey as $plank) 
 <?php
    if($plank == "per_unit"){
        $title = "Basic Plan";}
    else{
        $title = $plank;}
?>
 @if ($loop->first)  
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn" data-toggle="collapse">{{Str::title($title)}}</a>
 @else
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn collapsed" data-toggle="collapse">{{Str::title($title)}}</a>
 @endif
 @endforeach
                    
                </div>
            </div>
        </div>
   <div class=" " id="accordion">
        @foreach($plansdetails as $keyp => $singlePlansDetails)
        @if ($loop->first)  
        <div id="{{$keyp}}" class=" collapse  text-center show" data-parent="#accordion">
        <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
           @foreach ($singlePlansDetails as $singlePlansDetail)
            
           @component('components.plan',['singlePlansDetail' => $singlePlansDetail,'type'=>'per_unit'])
		
		@endcomponent
              @endforeach</div>
        </div> 
   
    @else

    <div id="{{$keyp}}" class=" collapse  text-center " data-parent="#accordion">
        <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
           @foreach ($singlePlansDetails as $singlePlansDetail)
            
           @component('components.plan',['singlePlansDetail' => $singlePlansDetail,'type'=>'tiered'])
		
		@endcomponent
              @endforeach</div>
        </div>
@endif
    @endforeach
</div>
    </div>
</section>
<style>.custom-row .row{
    margin-top: 2rem;
}
.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		.faq-btn{
			background: #004E92 0% 0% no-repeat padding-box;
			border: 0.25px solid #707070;
			border-radius: 18px;
			color: white;
			padding: 0.4rem 1rem;
		}

		.faq-btn.collapsed {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			color: #5E5E5E;
		}

		#accordion-child .card {
		    margin-top: 5px;
		}

		[data-toggle="collapse"] .fa:before {   
		  content: "\f107";
		}

		[data-toggle="collapse"].collapsed .fa:before {
		  content: "\f106";
		}
        #tab-buttons>a { display:block;
  float:left;
  cursor:pointer;
  color:#666;
  font:400 15px 'pt sans',sans-serif;
  
  padding:0 15px;
  line-height:40px !important;
  border: 2px solid #FF712F;}
  #tab-buttons>a:not(.collapsed){
  background:#FF712F;
  color:#FFFFFF;
  position:relative;
  box-shadow:0 0 10px rgba(0,0,0,0.5);
  
    /* width: 163px;
    height: 41px; */

}

</style>

<section class="page-section bg-light">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Channels</h2>
            </div>
      
<div class="row ">
<div class="col-centered"><ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

@foreach ($Genrekey as $genre) 
 @if ($loop->first)  

 <li class="nav-item">
    <a class="btn btn-sm btn-default active" id="pills-{{$genre}}-tab" data-toggle="pill" href="#pills-{{$genre}}" role="tab" aria-controls="pills-{{$genre}}" aria-selected="true">{{$genre}}</a>
  </li>
 @else
 <li class="nav-item">
    <a class="btn btn-sm btn-default" id="pills-{{$genre}}-tab" data-toggle="pill" href="#pills-{{$genre}}" role="tab" aria-controls="pills-{{$genre}}" aria-selected="false">{{$genre}}</a>
  </li>
 @endif
 @endforeach
</ul></div>
</div>
<div class="tab-content row col-centered " id="pills-tabContent">

@foreach($pagechannel as $key => $user)
        @if ($loop->first)  
        <div class="tab-pane fade show active col-sm-12 no-gutters" id="pills-{{$key}}" role="tabpanel" aria-labelledby="pills-{{$key}}-tab"><div class="h"> <div class="grid-container">@foreach ($user as $channeldet2)
            <div class="item1"> 
                <img  class="lazy" src="../uploads/channellogo/notfound.png" data-src="../uploads/channellogo/{{  $channeldet2->image}}" loading="lazy" alt="Logo of {{$channeldet2->CallSign}}">
            </div>  @endforeach</div></div></div>
        <!-- <div id="{{$key}}" class="col-sm-12 no-gutters collapse  text-center show" data-parent="#accordion">
            
        </div>  -->
   
    @else
    <div class="tab-pane fade col-sm-12 no-gutters" id="pills-{{$key}}" role="tabpanel" aria-labelledby="pills-{{$key}}-tab"><div class="h"><div class="grid-container">@foreach ($user as $channeldet2)
        <div class="item1"> 
            <img class="lazy" src="../uploads/channellogo/notfound.png" data-src="../uploads/channellogo/{{  $channeldet2->image}}" loading="lazy" alt="Logo of {{$channeldet2->CallSign}}">
        </div>  @endforeach</div></div></div>
    <!-- <div id="{{$key}}" class="col-sm-12 no-gutters collapse  text-center " data-parent="#accordion">
        
    </div>  -->
@endif
    @endforeach
</div>
    </div>
</section>
      <section class="page-section " id="portfolio">

    <div class="css-thjkr7">
        <div class="css-5nata0">
            <div class="upper-section">
                <div class="content">
                    <div class="title">Watch on all
                        <br> your devices.</div>
                    <div class="sub-title">Download the free Streamly apps for all your devices.
                        <br> Watch what you want, when you want.</div>
                    <div  ><a class="btn  btn-see-all-plan btn-xl" href="{{ url('/devices') }}" style=" text-transform: uppercase;">See all apps and devices</a></div>
                </div>
                {{-- <div class="content"><div class="title">Watch on all <br> your devices.</div><div class="sub-title">Download the free fuboTV apps for all your devices. <br> Watch what you want, when you want.</div>
                <div role="button" aria-label="" class="apps css-q9bl1b"><a href="/welcome/apps">See all apps and devices</a></div></div> --}}
                <div class="devices-preview"><img class="lazy img-fluid" data-src="{{url('../frontend/img/devices3.png')}}" 
  
alt="image of tv guide"
></div>
            </div>
        </div>

        <div class="flex-container space-evenly">
            
            <div><svg class = "circle" id="roku" expanded = "true" height = "200px" width = "200px" >
            <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
            </svg></div>
              
              <div><svg class = "circle" id="amazonfiretv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="appletv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="iostv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="android" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="samsungtv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div> </div>
    </div>
    </section>
<!--     
<section class="page-section " >
<div class="container"><div class="accordion" id="accordion1">

@foreach($Genrekey as $keyer =>$genrewe)
<?php 
?>
@component('components.card.card', ['cardHeadingId' => 'heading'.$keyer, 'cardCollapseId' => 'collapse'.$keyer, 'parentId' => 'accordion1','index'=>$keyer])
  @slot('cardTitle')
  my card title #{{$keyer}}
  @endslot
  
  @slot('cardBody')
    <strong> this the content of my card{{$keyer}} body</strong> 
  @endslot
  
@endcomponent
@endforeach
</div></div>
</section> -->
<style>
 #pills-tab>.nav-item>a{
  position:relative;
  box-shadow:0 0 10px rgba(0,0,0,0.5);
  font:400 15px 'pt sans',sans-serif;
  color:#666;
  padding:0 15px;
  background-color:#fff;
  line-height:40px !important;
  border: 2px solid #FF712F;
}
#pills-tab>.nav-item>a.active { display:block;
  float:left;
  cursor:pointer;
  color:#fff;
  background:#FF712F;
  border: 2px solid #FF712F;}
 
    </style>
@endsection