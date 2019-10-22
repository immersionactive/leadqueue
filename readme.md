# Conductor

## Installation

TODO. Don't forget to document: adding USADATA API credentials to .env; setting up cron

## Seeding test data

To seed the application with test data suitable for development purposes, run this Artisan command:

    php artisan db:seed --class=TestSeeder

This creates a client, lead source, lead destination, mapping, and related records.

## Lead sources

### Currently supported

* **[Gravity Forms Webhooks Add-On](https://www.gravityforms.com/add-ons/webhooks/):**

### Planned for the future

* **[Webflow Webhooks](https://webflow.com/feature/create-webhooks-from-project-settings):**

## Lead destinations

### Currently supported

* **[PropertyBase](https://www.propertybase.com/)**

### Planned for the future

* **Infusionsoft**
* **Lasso**
* One more – HubSpot?

## Lead statuses

In chronological order:

* `new`: The lead, but has not yet been appended.

* `appended`

* `append_failed`

* `destination_failed`

* `complete`

## TODO

### Essential

* IMPORTANT! The route admin.infusionsoft.authorize-destination isn't login-protected (because the middleware causes weird problems). Need to sort this out...
* Propertybase (or, at least, Noble's Pond's Propertybase account) doesn't seem to have separate "First Name" and "Last Name" fields - just a "Name" field. Dammit.
* POSSIBLE BIG PROBLEM: If we insert a Contact into Propertybase that already exist, and our insertion has a different/blank value for a standard contact field, will it overwrite/blank out the existing field values? (I think I read that Propertybase intelligently inserts/updates, instead of just doing a blind insert)
* Create custom fields for appends in Propertybase (done in sandbox [except for gender, if we need a custom field for that]; scheduled call with Rob Dudley to discuss for production)
* Make sure that appends work when a DestinationAppend has a blank contact_field_name, and that insertions work when a MappingField's destination has a blank contact_field_name (may need to tweak the logic)
* Finish building Propertybase insertion logic (need access to Propertybase first)
* Don't forget to release a v1 of immersionactive/propertybase (and pin this project to it)
* Don't forget to release a v1 of *this project* :)
* Make sure that someone (i.e., me) gets notified when a lead fails
* Make sure that permissions are properly enforced for everything
* Make sure that the Strategist role has appropriate permissions
* When a client is deactivated, also skip processing their leads (right now we only honor a deactivated *mapping*)

### Important

* Seems that morphTo() relations don't get deleted when the parent record gets deleted (which makes sense, since this deletion is ordinarily enforced on the MySQL level). This can lead to sensitive info (e.g., API keys on destination_config rows) being left in the DB. Does Laravel offer a way to automatically delete morphTo relations, or must we do it manually?
* Create .env.example file
* Don't allow destination_append.append_output_slug to be edited after the destination_append has been created? (I think this could mess things up if a user changes the append_output after a lead has already entered the queue...or something. users should be able to edit the destination field, but not the append_output.)
* Logging:
  * Make sure that all CRUD operations are logged
  * Create a custom Log class that automatically records the current user ID and email (see my question on Reddit)

### Uncategorized

* Documentation
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Build support for dev vs. prod environments into the application? maybe not
* Carefully consider what will happen if a mapping/source/destination gets modified while a lead is still in the queue
* UX:
  * You lose context once you drill too deep into a Client record (e.g., MappingFields and - to a lesser extent - DestinationAppends). How to solve?
  * find a good place for created/updated/deleted timestamps (and make sure they're on all model CRUD pages)
  * when there are no records, don't just show an empty table
  * use color consistently (e.g., for buttons, icons...)
  * Add explanatory text throughout, because this is gonna be unintuitive to everyone except me :/
  * Display model IDs to (only) users with the viewids permission (except for lead IDs, which should always be visible)
  * Where can users find a Webflow site ID? explain this on the appropriate page
  * Basic branding (e.g., replace logo)
  * Make validation messages clearer
  * Make the messages in delete confirmation modals consistent (I think that some say "Are you sure you want to do this?", while others specifically say "Are you sure you want to delete this record?")

### Long-term refactoring

* Change DestinationConfigType and SourceConfigType to be singletons, not static classes (for the sake of performance - e.g., so destination client APIs only have to be constructed once)
* Refactor the namespacing of the various source/destination packages
* Refactor the various source/destination packages to have their own composer.json files
* Capture *all* input data (e.g., Gravity Forms fields) for every lead that enters the system?
* Don't create SourceFieldConfigs for LeadSources that aren't being pushed into the destination system
* Don't create DestinationAppendConfigs for DestinationAppends that aren't being pushed into the destination system (actually, there will be no DestinationAppends anyway, once we start capturing all appended data by default)
* Move SourceFieldConfigs from the Mapping to the LeadSource (maybe? this contradicts the below)
* Move DestinationAppendConfigs to the Mapping (maybe? this contradicts the above)
* Lead processing: more intelligent exception handling (differentiate between fatal and non-fatal exceptions)
* Better permission control: test whether a user has access to view/edit a specific record (e.g., client 73), not just the general type (e.g., clients)
* Make it (much) easier to configure a standard mapping
* I've been assuming that each request to the USADATA API will return exactly one Person, Household, or Place. Is that an accurate assumption? Could one Person be associated with multiple Households or Places? Could a single Person request even return multiple documents?
* In some situations, we need to ability to push fixed values into certain destination fields. For example, for Noble's Pond's Propertybase account, leads need to be inserted with the "ContactType__c" field set to "Prospect", in order to trigger the appropriate workflows and make the contact show up in the right place.
* In some cases, I think that some CRMs (such as Propertybase) will overwrite an *existing* contact, instead of creating a new one (e.g., if the email address already exists on a contact record). Need to think about how to account for this.
