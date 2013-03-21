=== Soliloquy Lite Responsive Image Slider ===
Contributors: griffinjt
Tags: slider, slider plugin, responsive, responsive slider, image slider, image slider plugin, responsive slider plugin, responsive image slider, responsive image slider plugin, custom post types, slideshow, responsive slideshow, slideshow plugin, responsive slideshow plugin, rotator, image rotator, responsive rotator, jquery slider, javascript slider, jquery rotator, javascript rotator, picture slider, photo slider, photo rotator, shortcode, template tag
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: trunk
License: GNU General Public License v2.0 or later

The best responsive WordPress image slider plugin. Made lite and free.

== Description ==

Soliloquy, the [best responsive WordPress slider plugin](http://soliloquywp.com/pricing/?utm_source=orgrepo&utm_medium=link&utm_campaign=Soliloquy%2BLite), is now available in its Lite form! Soliloquy is audited by Mark Jaquith, lead developer of WordPress, for security and features the easiest to use and most performance optimized code for an image slider plugin. By utilizing custom post types, Soliloquy allows you to create an infinite number of responsive image sliders with an infinite number of images in each slider with a few clicks of the mouse.

**Note: This is the lite version of the image slider. Want even more slider features, including complete embedded video support for YouTube and Vimeo, widgets, and access to exclusive Addons (such as full lightbox support and image filters)? [Click here to purchase the best responsive WordPress slider plugin now!](http://soliloquywp.com/pricing/?utm_source=orgrepo&utm_medium=link&utm_campaign=Soliloquy%2BLite).**

= Features =

* Usage of custom post types to create an unlimited number of image sliders.
* Drag-and-drop image uploading, sorting and saving.
* Completely SEO optimized with the ability to specify alt and title tags for your images within the slider.
* Completely responsive (with touch enabled swiping).
* Shortcode and template tags handy so that you can insert your image slider anywhere in your theme.
* Metadata editing for each image, including image titles, alt tags and full HTML captions.
* Smart JS/CSS loading so that assets only load on pages where a slider is present.
* Media uploader button to easily insert your image slider into your posts/pages from the WYSIWYG editor.

**Note: This image slider plugin is provided as-is. Only critical bug fixes, future compatibility with WordPress versions and routine maintenance will be addressed in plugin updates. If you want support or access to more slider features, [consider purchasing a support license](http://soliloquywp.com/pricing/?utm_source=orgrepo&utm_medium=link&utm_campaign=Soliloquy%2BLite).**

== Installation ==

1. Install Soliloquy Lite either via the WordPress.org plugin directory, or by uploading the files to your server.
2. Activate Soliloquy Lite.
3. Navigate to the Soliloquy tab at the bottom of your admin menu and click "Add New" to begin creating your new slider.
4. Salivate for new features and [purchase the full version of Soliloquy](http://soliloquywp.com/pricing/?utm_source=orgrepo&utm_medium=link&utm_campaign=Soliloquy%2BLite)!

== Frequently Asked Questions ==

= I'd like access to more features. How can I get them? =

You can get access to more features, Addons and support by [visiting the Soliloquy website and purchasing a support license](http://soliloquywp.com/pricing/?utm_source=orgrepo&utm_medium=link&utm_campaign=Soliloquy%2BLite). Purchasing a support license gets you access to the full version of Soliloquy, automatic updates and support. Purchasing a developer support license gets you all the aforementioned plus exclusive access to Soliloquy Addons.

== Screenshots ==

1. Main Soliloquy screen.
2. Soliloquy Add/Edit screen with images loaded.
3. Soliloquy Thickbox upload screen.
4. Check out the Media insert button on your WYSIWYG editor.
5. Easily select a slider to insert into your post or page.
6. The final result.

== Notes ==

As of v1.3.0, Soliloquy Lite has been brought to parity with Soliloquy in terms of HTML and class/ID structure. Parity has also been brought when updating default post meta fields that will be available once an upgrade is made. In light of this, namespacing is now implemented in Soliloquy Lite. **This means that any CSS customizations made to Soliloquy Lite will be lost when you upgrade to v1.3.0.** I've included a conversion chart below so that you can see what classes/IDs have changed so that you can update your CSS styles accordingly. I'm available to help in the transition, so please feel free to submit a support thread regarding this and I will help you out. Again, take note of the conversion chart below and utilize it before upgrading.

**Note** Any instances on {slider_id} refer to the numeric slider ID, e.g. #soliloquy-container-922 where 922 is the ID of the slider. Any instances of {$i} refer to the current slide number in the slide, e.g. #soliloquy-287-item-2, where 2 is slide #2 in the slider.

* #flex-container-{slider_id} > #soliloquy-container-{slider_id}
* .flex-container > .soliloquy-container
* .flex-viewport > .soliloquy-viewport
* #flexslider-{slider_id} > #soliloquy-{slider_id}
* .flexslider > .soliloquy
* #flexslider-list-{slider_id} > #soliloquy-list-{slider_id}
* .slides > .soliloquy-slides (this change was made to prevent errant instantiation from other scripts)
* #flexslider-{slider_id}-item-{$i} > #soliloquy-{slider_id}-item-{$i}
* .flexslider-item > .soliloquy-item
* .flex-active-slide > .soliloquy-active-slide
* .flex-caption > .soliloquy-caption-inside
* .flex-control-nav > .soliloquy-control-nav
* .flex-control-paging > .soliloquy-control-paging
* .flex-active > .soliloquy-active
* .flex-direction-nav > .soliloquy-direction-nav
* .flex-prev > .soliloquy-prev
* .flex-next > .soliloquy-next.flex-disabled > .soliloquy-disabled
* .flex-pauseplay > .soliloquy-pauseplay
* .flex-pause > .soliloquy-pause
* .flex-play > .soliloquy-play

== Changelog ==

= 1.4.2 =
* Bug fixes and enhancements

= 1.4.1 =
* Fixed a bug with the dynamic size calculation when using a loading icon for the slider

= 1.4.0 =
* Added ability to display a loading icon to fix content jumping on slider loading (retina ready too!)
* Bug fixes and enhancements

= 1.3.0 =
* Compatibility updates with WP 3.5
* Updates to bring parity between Soliloquy Lite and Soliloquy
* Shiny new media button that integrates seamlessly with the new Add Media button
* Default post meta fields are now set when upgrading from Soliloquy Lite to Soliloquy
* Bug fixes and enhancements

= 1.2.0 =
* Updated to fix some MS issues
* General bug fixes and enhancements

= 1.1.0 =
* Updated icons and such to reflect new branding

= 1.0.0 =
* Initial release