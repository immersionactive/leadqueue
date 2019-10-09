# 


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

* 

## TODO

* Immediate stuff:
  * Start working on MappingFields CRUD
    * Should we just have a flat naming string, or allow individual sources & destinations to register their own views/validation/storage logic for field definitions?

* Document the format that destination fields must accept (e.g., if we insert gender as a string, we need to make sure that the field isn't configured as enum('m','f') in the CRM)
* Some way to coerce fields from the USADATA response (e.g., booleans -> "Yes"/"No"/"Unknown")
* Where can users find a webflow site ID?
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Make sure that all CRUD operations are logged
* Create .env.example file
* Build support for dev vs. prod environments into the application
* Client tabs: hide tabs that the user doesn't have access to
* Logging: tweak all backend controllers to inherit from a superclass that provides a log() method, which automatically records the current user ID and email
* Implement delete/destroy routes for everything
* Make validation messages clearer
* Lead Destinations: display type-specific fields on show route
* Don't forget to release a v1 of immersionactive/propertybase (and pin this project to it)
* Disable public routes
* Make sure that someone (i.e., me) gets notified when a lead fails
* Carefully consider what will happen if a mapping/source/destination gets modified while a lead is still in the queue
* UX:
  * find a good place for created/updated/deleted timestamps (and make sure they're on all model CRUD pages)
  * when there are no records, don't just show an empty table
  * use color consistently (e.g., for buttons, icons...)
  * Add explanatory text throughout, because this is gonna be unintuitive to everyone except me :/
  * Treat model IDS consistently throughout (either always show them, or only show them to certain users)