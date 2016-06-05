=== YITH WooCommerce Advanced Reviews ===

Contributors: yithemes
Tags: reviews, woocommerce, products, themes, yit, yith, e-commerce, shop, advanced reviews, reviews attachments, rating summary, product comment, review replies, advanced comments, product comments, vote review, vote comment, amazon, amazon style, amazon reviews, review report, review reports, most voted reviews, best reviews, rate review, rate product
Requires at least: 4.0
Tested up to: 4.3
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= Version 1.2.2 - RELEASED: NOV 26, 2015 =

* Fixed: review not submitted when "Ratings are required to leave a review" option is not set

= Version 1.2.1 - RELEASED: NOV 16, 2015 =

* Update: YITH plugin framework loading starts on plugins_loaded instead of after_setup_theme
* Added: review_label CSS class on ywar-product-reviews.php template file
* Fixed: edit reviews fails
* Fixed: user cannot edit if reply funtionality was not set

= Version 1.2.0 - RELEASED: NOV 05, 2015 =

* Update: YITH plugin framework
* Update: add CSS class "clearfix" on single review template
* Added: optionally limit a verified customer from submitting multiple reviews for the same product
* Added: user can edit a previous review
* Fix: don't show "Reply" button if the user has not permission to write a review
* Update: changes to ywar-product-reviews.php template for stopping multiple reviews from a single verified customer
* Update: in ywar-product-reviews.php template all elements are wrapped inside a div with id "ywar_reviews"
* Added: author information on back end reviews table.

= Version 1.1.14 - RELEASED: OCT 14, 2015 =

* Fix: name of the user not shown if the reviews is submitted by a guest not logged in.

= Version 1.1.13 - RELEASED: OCT 07, 2015 =

* Update: text-domain changed to yith-woocommerce-advanced-reviews.

= Version 1.1.12 - RELEASED: SEP 23, 2015 =

* Added: improved query performance for low resources server.
* Fix: sometimes items was not shown clicking on a view on reviews back end page.

= Version 1.1.11 - RELEASED: SEP 21, 2015 =

* Added: admins can reply to review from site front end even if woocommerce setting - Only allow reviews from "verified owners" - is checked.
* Fix: replies from admins written from site front end are shown without moderation.
* Added: check for empty content before trying to submit a review

= Version 1.1.10 - RELEASED: SEP 03, 2015 =

* Fix: CSS issue on pages other than "Reviews" page.

= Version 1.1.9 - RELEASED: AUG 28, 2015 =

* Fix: Review average rating was calculated including also unapproved reviews.

= Version 1.1.8 - RELEASED: AUG 27, 2015 =

* Tweak: update YITH Plugin framework.

= Version 1.1.7 - RELEASED: JUL 20, 2015 =

* Fix: blog comments conflict.

= Version 1.1.6 - RELEASED: JUL 17, 2015 =

* Fix: wrong product average rating.

= Version 1.1.5 - RELEASED: JUL 14, 2015 =

* Fix: review title not shown.

= Version 1.1.4 - RELEASED: MAY 21 , 2015 =

* Added: new filter before showing review content elements.

= Version 1.1.3 - RELEASED: MAY 12 , 2015 =

* Fix: when the review author is unknown, it was shown admin user as content author.

= Version 1.1.2 - RELEASED: MAY 11 , 2015 =

* New: Custom template are fully overwritable from theme files.

= Version 1.1.1 - RELEASED: MAY 07 , 2015 =

* Fixed: Call to undefined function session_status for previous PHP version.

= Version 1.1.0 - RELEASED: MAY 06 , 2015 =

* Added: advanced reviews custom post type.
* Added: you can set the reviews as featured, in this way these will be showed before the others and highlighted compared to a normal review
* Added: a report to view the statistics about the value of the reviews, with minimum, maximum and average rate.
* Added: allow users to report inappropriate contents.
* Added: hide temporarily a review if this receives a number of inappropriate reports higher than a customized value
* Added: check the review status from a single page, choosing if a review is approved, featured, inappropriate, with blocked answers and so on.
* Added: filter the reviews by status or update the status with bulk actions
* Added: sort reviews by received rates, both positive and negative.

= Version 1.0.11 - RELEASED: APR 10, 2015 =

* Fixed: some string was not correctly translated.

= Version 1.0.10 - RELEASED: APR 09, 2015 =

* Added: new option let admin to manually approve reviews before they are shown on product page.

= Version 1.0.9 - RELEASED: MAR 05, 2015 =

* Added: support WPML
* Fixed: Minor bugs
* Added : new option for allowing anonymous users to vote the reviews.
* New: admins can interact with reviews from product page on back-end.

= Version 1.0.8 - RELEASED: FEB 26, 2015 =

* Fixed: adding a rating to a reviews failed after a "reply to" attempt.

= Version 1.0.7 - RELEASED: FEB 12, 2015 =

* Added: Woocommerce 2.3 support
* Tweak: String translation

= Version 1.0.6 - RELEASED: FEB 06, 2015 =

* Tweak: Buttons with WooCommerce style
* Fixed: "Load more" button style strong appearance
* Tweak: Review summary overwritten by theme
* Fixed: Css issues
* Fixed: Duplicate load more button
* Fixed: Submit form disappears after Reply to

= Version 1.0.5 - RELEASED: FEB 04, 2015 =

* Tweak: Plugin core framework

= Version 1.0.4 - RELEASED: FEB 02, 2015 =

* Fixed: Minor bugs

= Version 1.0.3 - RELEASED: Jan 30, 2015 =

* Tweak: Plugin core framework
* Tweak: Theme integration

= Version 1.0.0 - RELEASED: Jan 07, 2015 =

* Initial release