# 

## TODO

* Log CRUD events
* Check out [the throttle middleware](https://laravel.com/docs/6.x/routing#rate-limiting) for rate-limiting
* Can we enforce delete cascade on the lead_source table on the MySQL level? (Maybe not, since it's a polymorphic relationship to multiple tables)
* Lead source create routes should probably pass the config type in the querystring, not as a route segment (to avoid potential route collisions)
