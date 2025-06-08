=== Paragraph Typewriter Effect ===
Contributors: sergeyvladimirovich
Tags: animation, typewriter, text effect
Requires at least: 5.6
Tested up to: 6.8
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add realistic typewriter animation to your content paragraphs.

== Description ==
**EN** | [RU](#russian-version)

Adds realistic typewriter animation to paragraphs with sequential display/erase effect.

= How It Works Technically =
1. Splits content into paragraphs using `wpautop()`
2. Uses JavaScript recursion with dynamic timeouts:
   - Type speed: 30-80ms/character (randomized)
   - Pause before erase: 2000ms 
   - Erase speed: 10ms/character
3. Pure CSS blinking cursor animation
4. Conflict-free initialization check

[RU](#russian-version)

== Installation ==
1. Install via WordPress admin panel
2. Activate the plugin
3. Configure in Settings → Paragraph Typewriter

== Frequently Asked Questions ==
= How to change animation speed? =
Use `ptw_speed` filter in child theme.

= Does it work with Gutenberg? =
Yes, compatible with both Classic and Block editors.

== Screenshots ==
1. Animation in progress
2. Settings panel

== Credits ==
Developed by Sergey_Vladimirovich with coding assistance from DeepSeek Chat AI.

== Changelog ==
= 1.0 =
* Initial release

<a name="russian-version"></a>
== Description ==
Adds a typewriter effect with sequential output and erasing of paragraphs.

= How the code works =
1. Splits content into paragraphs via `wpautop()`
2. Uses recursive JavaScript:
- Typing speed: 30-80ms/character (randomized)
- Pause before erasing: 2000ms
- Erase speed: 10ms/character
3. CSS animation of the blinking cursor
4. Checking for initialization to avoid conflicts

== Installation ==
1. Install via WordPress admin panel
2. Activate the plugin
3. Configure in "Settings → Typewriter Effect"

== FAQ ==
= How to change the speed? =
Use the `running_text_speed` filter in a child theme.

= Does it work with Gutenberg? =
Yes, compatible with classic and block editors.

== Thanks ==
Developed by Sergey Vladimirovich with technical support from DeepSeek Chat AI.

== Changelog ==
== Upgrade Notice ==
= 1.0.1=
* First stable version
* Gutenberg support
* Speed ​​settings via filters

== Privacy ==
This plugin does not collect any user data.
