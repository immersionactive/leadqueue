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

* Where can users find a webflow site ID?
* Refactor: Lead source create routes should probably pass the config type in the querystring, not as a route segment (to avoid potential route collisions)
* Refactor: merge StoreClientRequest and UpdateClientRequest?
* Refactor/bugfix: better handling for is_active checkboxes, including the ability to default to true (I can't believe this is so difficult...)
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Can we enforce delete cascade on the lead_source table on the MySQL level? (Maybe not, since it's a polymorphic relationship to multiple tables)
* Soft-deleted lead sources still prevent other lead sources with the same name from being created. Maybe we should just delete stuff outright (or disallow deletion, except by admins).
* Make sure that all CRUD operations are logged
