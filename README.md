# Kohana Admin module

## Dependencies
- Auth
- Formo
- KOstache

## Conventions
All admin controllers classes must start with `Controller_Admin_` and extend (at least) `Controller_Admin` in order to forbid unwanted access.

In case you want a *CRUD* controller, you must:
- extend `Controller_Admin_CRUD`
- specify `protected $_model` inside

_If you don't specify the model, it'll be automatically detected from the controller name, e.g. Post for Controller_Admin_Post._




### Permissions
This module relies on default roles_users ACL, but you can override this extending by the `Controller_Admin::check_permissions()` method, e.g.

	public static function check_permissions(Request $request)
	{
		if ( ! something_wrong())
			throw new HTTP_Exception_403('Access denied.');
	}
	
