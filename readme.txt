=== APH Prism Syntax Highlighter ===
Contributors: agusph
Tags: prism, syntax, highlighter, syntax highlighter
Requires at least: 3.5
Tested up to: 6.1
Stable tag: 1.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bringing Prism into WordPress easily. User-friendly GUI, support both classic editor and block editor. Support tab & back-tab in the code editor.

== Description ==

Prism is one of the most popular syntax highlighters that widely used by a lot of blog or websites, some of them are popular websites, such as Mozilla, drupal, and SitePoint.

One reason why a lot of websites prefer to use this highlighter is because it's ability to do custom builds of the language highlighter and plugins you want to be used

This plugin helps you to do all of those tasks and bring this code highlighter to your WordPress easily, provided with many features and user-friendly GUI, make it handy and easy to use.

** Features. **

* Simple and easy to use
* Support both Classic and Guttenberg block editor
* All possible syntax languages provided by Prism
* Full GUI, don't need to hardcode parameters
* Easy to make a custom build. Choose your own language, theme, and some plugins easily
* Custom container max-height. Useful when displaying long code.
* Easy to add custom CSS. Useful for responsive design.
* Easy to edit code that has been written in tinyMCE text editor (screenshot 4)
* Write and Edit code in place. The textarea of code editor, support tab, and back tab, so you can directly edit or write your code, rather than switch back to your code editor.
* Load script on demand, load javascript and CSS file of prism highlighter only when needed
* Ability to override global options and use individual options for each code block
* Support TinyMCE Visual mode and Text Mode (Quick Tags), it also supports the Quick tags used in the admin comment page (screenshot 5)
* Use similar pattern with <a href="https://wordpress.org/plugins/aph-syntax-highlighter/" title="APH Syntax Highlighter" target="_blank">APH Syntax Highlighter</a> and <a href="https://wordpress.org/plugins/crayon-syntax-highlighter/" title="Crayon Syntax Highlighter" target="_blank">Crayon Syntax Highlighter</a> plugins, so your code will still work using those plugins
* Language added: Light plain and dark plain

== Installation ==

Install & activate the plugin. Modify some options in the settings page to suits your need.

== How To Use ==

After installing the plugin, go to the admin setting Settings > Prism Highlighter to configure the global setting including the highlighter languages you want to load, theme, etc.

In the TinyMCE menu (add or edit post or page), there is a new button that can be used to pop up the code editor, simply copy-paste your code and fill in some options

This new button also can be found at the quick tags of comment editor in the Admin Edit Comments page. This quick tag appears when you edit or reply a comment.

== Screenshots ==

1. Admin Settings
2. Code Editor
3. Edit code on TinyMCE Editor
4. Quick Tags in the Text mode of TinyMCE editor
5. Quick Tags in the admin edit comments page
6. Guttenberg Block

== Changelog ==

= 1.5.2 (February 14, 2023) = 
* Bug fixes: add missing files

= 1.5.1 (February 13, 2023) = 
* Bug fixes

= 1.5.0 (February 13, 2023) = 
* Bug fixes
* Update PrismJs to the lastest version (v1.29.0)
* Add support to PHP 8 (tested on PHP 8.2.0)

= 1.4.1 (Juli 12, 2020) = 
* Bug fixes
* Add rel="language-name" to the <code> output 
* Add Plugin's Custom CSS field in the Block Editor

= 1.4 (April 29, 2020) = 
* Bug fixes
* Add support to Wordpress Guttenberg Block
* Update prism to the lastest version v.1.20 (29 April 2020)

= 1.3.1 (February 21, 2019) = 
* Bug fixes

= 1.3 (January 20, 2019) = 
* Add support to classic editor on Guttenberg
* Update PrismJs to the lastest version (v.1.15), including: core, themes, and components

= 1.2.2 (April 22, 2017) = 
Bug Fixed: Light Plain and Dark Plain is not responsive

= 1.2.1 (April 22, 2017) = 
Minor bug fixed

= 1.2 (March 18, 2017) = 

* Add language: dark plain and light plain
* Move compiled javascript and css files to wp-content/uploads/aphph
* Update prism core

= 1.1.1 (September 26, 2016) = 

* Fix sample responsive CSS Code
* Set default container max-height to 480px

= 1.1 (September 25, 2016) = 

* Ability to set max-height of container. Useful when displaying long code
* Ability to add custom CSS. Useful for responsive design
* Now container element has default class: aphph-container
* Some minor improvements

= 1.0 =

Initial Public Release