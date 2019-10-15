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

* Leads CSV download: mapping field input values need human-readable headers
* Finish building Propertybase insertion logic (need access to Propertybase first)
* Don't forget to release a v1 of immersionactive/propertybase (and pin this project to it)
* Don't forget to release a v1 of *this project* :)
* Make sure that someone (i.e., me) gets notified when a lead fails
* Make sure that permissions are properly enforced for everything
* Make sure that the Strategist role has appropriate permissions

### Important

* Create .env.example file
* Disable public routes
* Don't allow destination_append.append_output_slug to be edited after the destination_append has been created? (I think this could mess things up if a user changes the append_output after a lead has already entered the queue...or something. users should be able to edit the destination field, but not the append_output.)
* Logging:
  * Make sure that all CRUD operations are logged
  * Create a custom Log class that automatically records the current user ID and email (see my question on Reddit)

### Nice to Have

* Change DestinationConfigType and SourceConfigType to be singletons, not static classes (for the sake of performance - e.g., so destination client APIs only have to be constructed once)

### Uncategorized

* Documentation
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Build support for dev vs. prod environments into the application?
* Client tabs: hide tabs that the user doesn't have access to
* Carefully consider what will happen if a mapping/source/destination gets modified while a lead is still in the queue
* UX:
  * find a good place for created/updated/deleted timestamps (and make sure they're on all model CRUD pages)
  * when there are no records, don't just show an empty table
  * use color consistently (e.g., for buttons, icons...)
  * Add explanatory text throughout, because this is gonna be unintuitive to everyone except me :/
  * Treat model IDs consistently throughout (either always show them, or only show them to certain users)
  * Where can users find a webflow site ID? explain this on the appropriate page
  * Basic branding (e.g., replace logo)
  * Make validation messages clearer
  * Make the messages in delete confirmation modals consistent (I think that some say "Are you sure you want to do this?", while others specifically say "Are you sure you want to delete this record?")
