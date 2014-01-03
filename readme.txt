=== Store Directory ===
Contributors: mboynes
Tags: store, location, maps, directory, google maps
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple, flexible plugin for keeping a store/location directory and searching for them by radius.

== Description ==

This plugin adds a store/location directory to your WordPress site. Once you add stores, you can add the Store Search widget to your widget area or you can add the form to your theme manually.

This plugin is intentionally light and simple. It's very flexible and is intended to be something on which you can build.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add stores via the "Stores" menu in the left.
4. Add the Store Search widget to your toolbar, or add the search form to your theme via `wpsd_the_store_search_form()`

== Changelog ==

= 0.1 =
Brand new plugin. Enjoy!

== Filters ==

= wpsd_radius_options =

Param: `array( 5, 10, 25, 50, 100 )`

This filter allows you to modify the options in the radius dropdown in the store search form. Values are in miles by default.

= wpsd_register_post_type =

Param: `true`

This filter allows you to disable the `store` post type altogether. To do so, simply add `add_filter( 'wpsd_register_post_type', '__return_false' )` to your theme or plugin.

= wpsd_post_type_args =

This filter gives you the ability to modify the arguments passed to `register_post_type()` for the 'store' post type. See [register_post_type in the Codex](http://codex.wordpress.org/Function_Reference/register_post_type) for valid arguments and values.

Param:

`array(
	'public'      => true,
	'has_archive' => true,
	'supports'    => array( 'title' ),
	'labels'      => array(
		'name'                => __( 'Stores', 'store-directory' ),
		'singular_name'       => __( 'Store', 'store-directory' ),
		'all_items'           => __( 'Stores', 'store-directory' ),
		'new_item'            => __( 'New store', 'store-directory' ),
		'add_new'             => __( 'Add New', 'store-directory' ),
		'add_new_item'        => __( 'Add New store', 'store-directory' ),
		'edit_item'           => __( 'Edit store', 'store-directory' ),
		'view_item'           => __( 'View store', 'store-directory' ),
		'search_items'        => __( 'Search stores', 'store-directory' ),
		'not_found'           => __( 'No stores found', 'store-directory' ),
		'not_found_in_trash'  => __( 'No stores found in trash', 'store-directory' ),
		'parent_item_colon'   => __( 'Parent store', 'store-directory' ),
		'menu_name'           => __( 'Stores', 'store-directory' ),
	)
)`

= wpsd_automap =

Param: `true`

By default, a Google Map is added to the store archive views and store singular views using the `'loop_start'` action. This may not be desirable in all circumstances, especially if you loop through your posts multiple times. To disable this, add `add_filter( 'wpsd_automap', '__return_false' )` to your theme or plugin. If you choose to disable this, see the `wpsd_the_map()` tempalte tag to manually display the map.

== Template Tags ==

= wpsd_the_store_search_form() =

Output the store search form. If you choose not to use the provided widget, this lets you add the form wherever you'd like.

= wpsd_the_map() =

Param: `$posts` array An array of posts to map. Each entry in the array should contain the following keys:

* `'name' => ` The marker title.
* `'address' => ` The marker address.
* `'latitude' => ` The latitude of the point.
* `'longitude' => ` The longitude of the point.
* `'distance' => ` The distance of the point from the center (optional, not presently used).

See `WPSD_Search::get_mappable_data()` for an example.

Param: `$lat` float The latitude of the center point for the map.
Param: `$long` float The longitude of the center point for the map.

Output a Google Map with the given points (posts) centered around the given latitude and longitude.

== Other Notes for Developers ==

Beyond the above, pretty much everything in this plugin is modifiable by editing the properties of the singleton classes. Here are some examples:

* To change the post type, e.g. to be `'location'` instead of store, you can set it by calling `WPSD_Post_Type()->post_type = 'location';` from your theme or plugin (ideally during `after_setup_theme` at a priority higher than 10).
* To change the units from miles to kilometers, `WPSD_Post_Type()->units = 'km';`
