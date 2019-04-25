import { extendMoment } from 'moment-range'

window._ = require('lodash');
window.moment = extendMoment(require('moment'));
window.moment.locale('ru')
window.Cookies = require('js-cookie');
// window.algolia = require('algoliasearch')(process.env.MIX_ALGOLIA_APP_ID, process.env.MIX_ALGOLIA_SECRET);

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.apiUrl = (...paths) => process.env.MIX_API_URL + paths.join('/')

window.clone = (obj) => {
  return JSON.parse(JSON.stringify(obj))
}

// кол-во смс
!function(){var $,SmsCounter;window.SmsCounter=SmsCounter=function(){function SmsCounter(){}SmsCounter.gsm7bitChars="@£$¥èéùìòÇ\\nØø\\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\\\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";SmsCounter.gsm7bitExChar="\\^{}\\\\\\[~\\]|€";SmsCounter.gsm7bitRegExp=RegExp("^["+SmsCounter.gsm7bitChars+"]*$");SmsCounter.gsm7bitExRegExp=RegExp("^["+SmsCounter.gsm7bitChars+SmsCounter.gsm7bitExChar+"]*$");SmsCounter.gsm7bitExOnlyRegExp=RegExp("^[\\"+SmsCounter.gsm7bitExChar+"]*$");SmsCounter.GSM_7BIT="GSM_7BIT";SmsCounter.GSM_7BIT_EX="GSM_7BIT_EX";SmsCounter.UTF16="UTF16";SmsCounter.messageLength={GSM_7BIT:160,GSM_7BIT_EX:160,UTF16:70};SmsCounter.multiMessageLength={GSM_7BIT:153,GSM_7BIT_EX:153,UTF16:67};SmsCounter.count=function(text){var count,encoding,length,messages,per_message,remaining;encoding=this.detectEncoding(text);length=text.length;if(encoding===this.GSM_7BIT_EX){length+=this.countGsm7bitEx(text)}per_message=this.messageLength[encoding];if(length>per_message){per_message=this.multiMessageLength[encoding]}messages=Math.ceil(length/per_message);remaining=per_message*messages-length;if(remaining == 0 && messages == 0){remaining = per_message; }return count={encoding:encoding,length:length,per_message:per_message,remaining:remaining,messages:messages}};SmsCounter.detectEncoding=function(text){switch(false){case text.match(this.gsm7bitRegExp)==null:return this.GSM_7BIT;case text.match(this.gsm7bitExRegExp)==null:return this.GSM_7BIT_EX;default:return this.UTF16}};SmsCounter.countGsm7bitEx=function(text){var char2,chars;chars=function(){var _i,_len,_results;_results=[];for(_i=0,_len=text.length;_i<_len;_i++){char2=text[_i];if(char2.match(this.gsm7bitExOnlyRegExp)!=null){_results.push(char2)}}return _results}.call(this);return chars.length};return SmsCounter}();if(typeof jQuery!=="undefined"&&jQuery!==null){$=jQuery;$.fn.countSms=function(target){var count_sms,input;input=this;target=$(target);count_sms=function(){var count,k,v,_results;count=SmsCounter.count(input.val());_results=[];for(k in count){v=count[k];_results.push(target.find("."+k).text(v))}return _results};this.on("keyup",count_sms);return count_sms()}}}.call(this);

window.uniqid = function() {
  var S4 = function() {
     return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
  };
  return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

window.queryString = (obj, appendQuestionMark = true) => {
  if (obj === null) {
    return ''
  }
  return (appendQuestionMark ? '?' : '') + Object.entries(obj).map(e => e[0] + '=' + e[1]).join('&')
}

window.colorLog = (message, color = 'PaleVioletRed') => console.log('%c' + message, `color:${color}`) 


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
