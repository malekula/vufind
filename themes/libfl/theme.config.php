<?php
return array(
    'extends' => 'root',
    'css' => array(
        'compiled.css',
	'libfl.css',
        'print.css:print',
        'statuses.css',
        'flex-fallback.css:lt IE 10', // flex polyfill
    ),
    'js' => array(
        'vendor/base64.js:lt IE 10', // btoa polyfill
        'vendor/jquery.min.js',
        'vendor/bootstrap.min.js',
        'vendor/bootstrap-accessibility.min.js',
        'vendor/validator.min.js',
        'lib/form-attr-polyfill.js', // input[form] polyfill, cannot load conditionally, since we need all versions of IE
        'lib/autocomplete.js',
        'common.js',
        'lightbox.js',
        'browser.js',
    ),
    'less' => array(
        'active' => false,
        'compiled.less'
    ),
    'favicon' => 'vufind-favicon.ico',
    'helpers' => array(
        'factories' => array(
            'flashmessages' => 'VuFind\View\Helper\LIBFL\Factory::getFlashmessages',
            'layoutclass' => 'VuFind\View\Helper\LIBFL\Factory::getLayoutClass',
            'recaptcha' => 'VuFind\View\Helper\LIBFL\Factory::getRecaptcha',
            'recorddataformatter' => 'VuFind\View\Helper\LIBFL\RecordDataFormatterFactory',
            'reporterror' => 'VuFind\View\Helper\LIBFL\Factory::getReporterror',
        ),
        'invokables' => array(
            'highlight' => 'VuFind\View\Helper\LIBFL\Highlight',
            'search' => 'VuFind\View\Helper\LIBFL\Search',
        )
    )
);
