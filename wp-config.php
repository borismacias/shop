<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'clubtac1_shop');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'root');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '_PeF=c)bD54Oy1TVM}H;>5*O%;|.r]JE6_jXnPj^$(CvJB7oCrmz.zoO..+J>!1&'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_KEY', '[n>~7uYfQ4~5*YU!C88k<o:Z2sRO:0 F4W7|-p,iPe/%`A di4=b5=7!+%zy}#I7'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_KEY', 'J .cx_&XZMK-mdP?p*fcug:uV18lB@$-/m^{@u-Q^2MO4^`u9~a!&8E^:0Hs$biI'); // Cambia esto por tu frase aleatoria.
define('NONCE_KEY', 'l*cus Q/xC~XDAvJ{3%yDo1.%D--2,7BZXa]OGsKk%r:Q/|9583Kx|mO/KMN|{ts'); // Cambia esto por tu frase aleatoria.
define('AUTH_SALT', 'P:[H^1&wVbJ2eM(>^H+=wXmgM+Ss}|_9-z73}mOFIbao6li>+.hgbh%,chKr6nTZ'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_SALT', '+vx7vxbX1QynOQpq.u,*#?-Rv+GzI<qPK-4@W_b>v/H)]+%pDa% rv(?t.o*;Nt/'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_SALT', '|wilp.~9p)[rMd*3vKv+*#z!X3o1Joy/RyIxzBY8o<3qiG.8v}O(H>hL.ahc-{|g'); // Cambia esto por tu frase aleatoria.
define('NONCE_SALT', 'hd|A4^P~4=:N=c(kLQrE|F+qn6qXS8k9,Lf+,6{9;xR}-SfkwFrwKx$:z ]>$yN;'); // Cambia esto por tu frase aleatoria.

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'cte_';

/**
 * Idioma de WordPress.
 *
 * Cambia lo siguiente para tener WordPress en tu idioma. El correspondiente archivo MO
 * del lenguaje elegido debe encontrarse en wp-content/languages.
 * Por ejemplo, instala ca_ES.mo copiándolo a wp-content/languages y define WPLANG como 'ca_ES'
 * para traducir WordPress al catalán.
 */
define('WPLANG', 'es_ES');

/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/*
	Changing site url
*/

// define('WP_HOME','localhost');
// define('WP_SITEURL','localhost');


/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

