# Kohana Admin module

## Dependencies
- [Auth](http://github.com/kohana/auth)
- [Formo](http://github.com/bmidget/kohana-formo)
- [KOstache](http://github.com/zombor/KOstache)
- [Pagination](http://github.com/kloopko/kohana-pagination)

## Conventions

### Controllers
All admin controller classes must start with `Controller_Admin_` and extend (at least) `Controller_Admin` in order for ACL and templating to work.

In case you want a *CRUD* controller, you must only extend `Controller_Admin_CRUD`. Example:

	class Controller_Admin_Topic extends Controller_Admin_CRUD {
		
		protected $_model = 'post';
		
	}

If you don't specify `$_model`, it'll be automatically detected from the controller name, e.g. Post for Controller_Admin_Post.

### Views
All views must extend `View_Admin_Layout` in order to inherit Bootstrap templating capabilities. 

## Usage
By default you don't need to add anything additionaly for direct model CRUD, just create the controller and that's it. Link will be automatically added to the header using Reflection.

If you need to add some custom actions, you will have to create a view model and template for them as well.


### Permissions
This module relies on default Auth modules ACL (`roles_users` table), but you can override this by extending the `Controller_Admin::check_permissions()` method in your child controller, e.g.

	public static function check_permissions(Request $request)
	{
		if ( ! something_wrong())
			throw new HTTP_Exception_403('Access denied.');
	}
	
