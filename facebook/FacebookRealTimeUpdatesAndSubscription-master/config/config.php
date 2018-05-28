<?php
$basedir = __DIR__ . '/../';

return array(
// Environment
    'php.error_reporting' => E_ALL,
    'php.display_errors'  => true,
    'php.log_errors'      => true,
    'php.error_log'       => $basedir . 'logs/errors.txt',
    'php.date.timezone'   => 'America/New_York',

// SQLite
//  'db.dsn'              => 'sqlite:' . $basedir . 'db/sqlite.db',

// MySQL
    'db.dsn'              => 'mysql:host=localhost;dbname=test',
    'db.username'         => 'dbuser',
    'db.password'         => 'dbpass',

// Application paths
    'path.routes'         => $basedir . 'routes/',
    'path.templates'      => $basedir . 'templates/',

// Facebook
    'facebook.app_id'       => '334959493535208',
    'facebook.app_secret'   => '3579e6434131047512913d63769e1f31',
    'facebook.verify_token' => 'abc123',

// PHPMailer
    'smtp.host'             => 'SMTP-HOST',
    'smtp.port'             => SMTP-PORT,
    'smtp.encryption'       => 'tls', // or 'ssl'
    'smtp.username'         => 'SMTP-USERNAME',
    'smtp.password'         => 'SMTP-PASSWORD',
    'smtp.from_address'     => 'no-reply@example.com',
    'smtp.from_name'        => 'SitePoint Facebook Real-Time Tutorial'
);
