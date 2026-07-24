# Route Bug Fix Tutorial

This tutorial explains how to check the route issue, which commands to use, and exactly where the fix was applied.

## 1. Problem
We got a Laravel error:

- `MethodNotAllowedHttpException`
- `405 Method Not Allowed`
- The route `/store/profile` was called using `GET`, but the route only accepted `POST`.

## 2. How to check the issue
Open the terminal and go to the project folder:

```bash
cd /home/mahbub/Udemy/udemy-basic-laravel
```

Then check the route list:

```bash
php artisan route:list --name=store.profile
```

### What this command shows
It shows the registered HTTP methods for the route.

For this bug, it showed:

```bash
GET|POST|HEAD   store/profile
```

That means the route now accepts both `GET` and `POST`.

## 3. Which files were edited
The fix was applied in these files:

1. `routes/web.php`
   - Added a route that accepts both `GET` and `POST`.

2. `app/Http/Controllers/AdminController.php`
   - Added logic so that if someone visits the URL with `GET`, it redirects to the edit profile page.

## 4. What was changed
### In `routes/web.php`
The route was changed from a POST-only route to a route that supports both methods:

```php
Route::match(['get', 'post'], '/store/profile', 'StoreProfile')->name('store.profile');
```

### In `app/Http/Controllers/AdminController.php`
Inside the `StoreProfile` method, we added:

```php
if ($request->isMethod('get')) {
    return redirect()->route('edit.profile', Auth::user()->id);
}
```

This makes the route safe for a browser visit or accidental GET request.

## 5. How to verify the fix
Run the route list again:

```bash
php artisan route:list --name=store.profile
```

You should see the route listed as:

```bash
GET|POST|HEAD
```

## 6. Easy explanation of the bug
- A form uses `POST` to submit data.
- But if a browser or link sends `GET` to the same URL, Laravel throws a `405` error.
- The fix makes that route accept `GET` safely and redirect to the edit page.

## 7. Summary
This is the simple flow:

1. Check the route with `php artisan route:list --name=store.profile`
2. Open the affected files
3. Edit the route definition
4. Add safe GET handling in the controller
5. Verify again with the same command

## 8. Useful commands
```bash
cd /home/mahbub/Udemy/udemy-basic-laravel
php artisan route:list --name=store.profile
```

If you want, you can also test the route manually in the browser using:

```text
http://127.0.0.1:8000/store/profile
```
