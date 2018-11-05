Usage
=====

To be able to use the bundle, you only need to update the database, in your project run:

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```

The `fh_user_agent` table will then become available.

## Mobile implementation

The client should always send the `User-Agent` header to the application/api. 

To be able to use the version control functionality, this value can be added to the `fh_user_agent` table and combined 
with an action; `permit` or `upgrade`.

### Example

> An app with `User-Agent: com.acme/1.0.0-1234` must not be allowed to use the application as it's too old and 
incompatible with the application/api.

Insert a record in the `fh_user_agent` table with:
`version = com.acme/1.0.0-1234` and `action = upgrade`.

When the app with that version performs a request to the application, it will receive an new header key in the response;
`X-User-Agent-Status: upgrade`. The app must honor this header key and force the user to upgrade their app.

An other scenario would be that a version is explicitly permitted, the value of the header will then be `X-User-Agent-Status: permit`,
this means the app is allowed to use the application/api with that specific `User-Agent`.
