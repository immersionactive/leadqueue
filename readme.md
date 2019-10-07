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

## TODO

* Immediate stuff:
  * Start working on MappingFields CRUD
    * Should we just have a flat naming string, or allow individual sources & destinations to register their own views/validation/storage logic for field definitions?

* Document the format that destination fields must accept (e.g., if we insert gender as a string, we need to make sure that the field isn't configured as enum('m','f') in the CRM)
* Some way to coerce fields from the USADATA response (e.g., booleans -> "Yes"/"No"/"Unknown")
* Where can users find a webflow site ID?
* Refactor/bugfix: better handling for is_active checkboxes, including the ability to default to true (I can't believe this is so difficult...)
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Can we enforce delete cascade on the lead_source table on the MySQL level? (Maybe not, since it's a polymorphic relationship to multiple tables)
* Soft-deleted lead sources still prevent other lead sources with the same name from being created. Maybe we should just delete stuff outright (or disallow deletion, except by admins).
* Make sure that all CRUD operations are logged
* Create .env.example file
* Build support for dev vs. prod environments into the application
* UX: find a good place for created/updated/deleted timestamps (and make sure they're on all model CRUD pages)
* UX: when there are no records, don't just show an empty table
* UX: use color consistently (e.g., for buttons, icons...)
* Client tabs: hide tabs that the user doesn't have access to
* Logging: tweak all backend controllers to inherit from a superclass that provides a log() method, which automatically records the current user ID and email
* Implement delete/destroy routes for everything
* Make validation messages clearer
* Lead Destinations: display type-specific fields on show route
* Don't forget to release a v1 of immersionactive/propertybase (and pin this project to it)
