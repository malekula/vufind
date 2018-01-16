/*global grecaptcha, isPhoneNumberValid */
/*exported VuFind, htmlEncode, deparam, moreFacets, lessFacets, getUrlRoot, phoneNumberFormHandler, recaptchaOnLoad, resetCaptcha, bulkFormHandler */

// IE 9< console polyfill
window.console = window.console || {log: function polyfillLog() {}};

var BR = (function VuFind() {
  var ci = 'lalal';

  return {
    ci: ci
  };
})();
