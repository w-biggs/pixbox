=== Pixbox ===
Contributors: wilsonbiggs
Tags: photo album, photos, gallery, upload, photography
Donate link: https://paypal.me/wbiggs1
Tested up to: 5.1.1
Requires at least: 4.5
Requires PHP: 5.5.38
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A private photo albums plugin.

== Changelog ==
- 0.5.1:
  - FIX: You can now edit album names and parents - not sure how I skipped this, oops.
  - FIX: Deleting an album will no longer throw a "headers already sent" error on PHP5.
- 0.5:
  - ADD: Error and success messages are now rendered as notices.
  - ADD: The "add password" field is now greyed out when the password checkbox isn't checked.
  - ADD: Albums can now be downloaded in batch.
  - ADD: Store user login for an hour so they don't need to re-enter the password each time.
  - ADD: Albums' photos are deleted when the album is deleted.
  - ADD: A link to the Pixbox admin page is now visible in the admin bar on the Pixbox page.
  - ADD: File upload errors are now more descriptive.
  - FIX: Handlers now use !empty instead of isset, so empty textboxes are correctly caught.
  - FIX: Error messages are now escaped before being passed as query strings.
  - FIX: Now using wp_safe_redirect instead of wp_redirect.
- 0.4.3:
  - FIX: Everything should now work correctly when the plugin directory is not /pixbox/. EVERYTHING. (I hope.)
- 0.4.2:
  - FIX: Admin nav links now work correctly when the plugin directory is not /pixbox/.
- 0.4.1:
  - FIX: CSS is now loaded correctly when the plugin directory is not /pixbox/.
  - FIX: Existing query strings are no longer overwritten on the front-end.
- 0.4.0:
  - ADD: Photos can now be deleted.
  - ADD: CSS classes can now be modified on the front-end wrapper class.
  - ADD: Passwords now expire after the given number of days.
- 0.3.2:
  - FIX: Shows drafts in the page settings dropdown.
- 0.3.1:
  - FIX: No longer overrules all page templates if page setting is unset.
- 0.3:
  - Front-end is usable
- 0.2:
  - Back-end is usable
- 0.1:
  - Work in progress