<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

/*
define( 'SMTP_USER',   'amaayed@gmail.com' );    // Username to use for SMTP authentication
define( 'SMTP_PASS',   '*********' );       // Password to use for SMTP authentication
define( 'SMTP_HOST',   'smtp.gmail.com' );    // The hostname of the mail server
define( 'SMTP_FROM',   'amaayed@gmail.com' ); // SMTP From email address
define( 'SMTP_NAME',   'Ayed' );    // SMTP From name
define( 'SMTP_PORT',   '587' );                   // SMTP port number - likely to be 25, 465 or 587
define( 'SMTP_SECURE', 'tls' );                 // Encryption system to use - ssl or tls
define( 'SMTP_AUTH',    true );                 // Use SMTP authentication (true|false)
define( 'SMTP_DEBUG',   1 );                      // for debugging purposes only set to 1 or 2
*/

define( 'SMTP_USER',   'AKIAIZVBXFA7UKVMU4HQ' );    // Username to use for SMTP authentication
define( 'SMTP_PASS',   'BLKm9NXLrprmwkWR+Gl1lihhaB6dh/dv2yW1lcSrgq5x' );       // Password to use for SMTP authentication
define( 'SMTP_HOST',   'email-smtp.eu-west-1.amazonaws.com' );    // The hostname of the mail server
define( 'SMTP_FROM',   'no-reply@ontexglobal.com' ); // SMTP From email address
define( 'SMTP_NAME',   'dev_odf' );    // SMTP From name
define( 'SMTP_PORT',   '587' );                   // SMTP port number - likely to be 25, 465 or 587
define( 'SMTP_SECURE', 'tls' );                 // Encryption system to use - ssl or tls
define( 'SMTP_AUTH',    true );                 // Use SMTP authentication (true|false)
define( 'SMTP_DEBUG',   1 );                      // for debugging purposes only set to 1 or 2


include_once __DIR__ . '/wp-config.common.php';
// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
$DB_NAME = getenv("CUSTOMCONNSTR_DB_NAME");
$DB_USER = getenv("CUSTOMCONNSTR_DB_USER");
$DB_PASSWORD = getenv("CUSTOMCONNSTR_DB_PASSWORD");
$DB_HOST = getenv("CUSTOMCONNSTR_DB_HOST");
if($DB_NAME!="" && $DB_USER!="" && $DB_PASSWORD!="" && $DB_HOST!=""){
	define('DB_NAME', $DB_NAME);
	define('DB_USER', $DB_USER);
	define('DB_PASSWORD', $DB_PASSWORD);
	define('DB_HOST', $DB_HOST);
}else{
	define('DB_NAME', 'app_brandwebsite_database_preprod');
	define('DB_USER', 'mysqladmin@app-shared-dbserver01');
	define('DB_PASSWORD', 'Azerty007');
	define('DB_HOST', 'app-shared-dbserver01.mysql.database.azure.com');
}
/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');
/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');
/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8x5 wewLXaIUmC6>a^*lF/nrT>mSMr(Gnke5EOr;w;-|Gq; rd aMmXS!@y]hhN<');
define('SECURE_AUTH_KEY',  '4FaLX.AUa=],}>({gQ1x8+bxLGN<srfJ4 ijMOj0mdWjZ mNC pTqcj0%eGBy|BI');
define('LOGGED_IN_KEY',    'YpjuI}<a<5<: xn,>4K uCr-0Dt/]sLnKySpx5>z{*mV(},E]Y.jyLqajIH50^FZ');
define('NONCE_KEY',        ',X >AXAR%@]_tT6wdLhB]Cs.i$H?=f7!([Y;Zt[HXfij.Xaug9}ud58P1&s*<VLI');
define('AUTH_SALT',        'VOk9o1}/l@2~zyJ$R56cepHqVz&-5`flH=+J>#Wd,u6PjmW*PO#wYm+`v$C^eY_!');
define('SECURE_AUTH_SALT', '1ZN!#Pf/<sdgm =g5LS`?I3@??F#=@3uQ_0l+g_CdcT7^o$LY/E%5rL8BG.i{*u_');
define('LOGGED_IN_SALT',   '-3u.j_~(_CM5T%RoilX|oOUYicT2b0Li_bkjLeg/:#z<g1g.;/3d<Z+~1{K&P6db');
define('NONCE_SALT',       'IpKPVBBt#+f>t?OgQ]8ZWEiWWpREp(oI|PK$+,X,W85]=##iI745x63JK%SJgJv&');
/**#@-*/
/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';
/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define('VP_ENVIRONMENT', 'default');
/* C’est tout, ne touchez pas à ce qui suit ! */
/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
@ini_set('log_errors', 1);
@ini_set('display_errors', 0); /* enable or disable public display of errors (use 'On' or 'Off') */
@ini_set('error_log', dirname(__FILE__) . '/wp-content/logs/php-errors.log'); /* path to server-writable log file */
@ini_set( 'error_reporting', E_ALL ^ E_NOTICE );