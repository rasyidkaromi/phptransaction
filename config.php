<?php

// Database
define('DBNAME', 'fliptest');
define('TABLENAME', 'transaction');
define('DBUSERNAME',  'agent');
define('DBPASSWORD',  'agent');
define('DBADDRESS',  'localhost');

// display dev log debug
define('DISPLAY_DEBUG', false );

// server
define('GET_schema',  '/disburse/');
define('POST_schema',  '/disburse');
define('SERVERHOST',  'localhost:8080');


// CUrl
define('CERT',  '/Certificates.pem');
define('USERAGENT', 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0' );
define('CONTENTTYPE', 'application/x-www-form-urlencoded' );
define('PRIVATKEY', 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41' );

// loop
define('LOOPENABLE', true );
define('LOOPTIME', 30 );
define('GENERATETRANS', 100 );

