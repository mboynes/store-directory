<?php

/*
	Plugin Name: Store Directory
	Plugin URI: http://boyn.es/store-directory/
	Description: A simple plugin for keeping a store directory
	Version: 0.1
	Author: Matthew Boynes
	Author URI: http://boyn.es/
*/
/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


define( 'WPSD_PATH', dirname( __FILE__ ) );
define( 'WPSD_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );

require_once( WPSD_PATH . '/template-tags.php' );
require_once( WPSD_PATH . '/lib/class-wpsd-post-type.php' );
require_once( WPSD_PATH . '/lib/class-wpsd-post-meta.php' );
require_once( WPSD_PATH . '/lib/class-wpsd-search.php' );
require_once( WPSD_PATH . '/lib/class-wpsd-widget.php' );
