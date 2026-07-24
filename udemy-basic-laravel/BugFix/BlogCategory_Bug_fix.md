# Blog Category Update Bug Fix

## Issue Summary
While updating a blog category, the application threw an `ArgumentCountError` because the update route was not passing the category ID to the controller method.

Later, after fixing the route, Laravel raised a `MassAssignmentException` because the `BlogCategory` model did not allow the `blog_category` field to be updated through mass assignment.

---

## Problems Faced

### 1) ArgumentCountError
Error:
```php
Too few arguments to function App\Http\Controllers\Home\BlogCategoryController::UpdateBlogCategory(), 1 passed and exactly 2 expected
```

### Cause
The controller method expected an ID:
```php
public function UpdateBlogCategory(Request $request, $id)
```

But the route did not provide the ID correctly.

---

### 2) MassAssignmentException
Error:
```php
Add [blog_category] to fillable property to allow mass assignment on [App\Models\BlogCategory].
```

### Cause
The `BlogCategory` model had an empty `$fillable` array, so Laravel blocked the update.

---

## Fixes Applied

### 1) Fix the update route
The update route was changed to include the category ID:

```php
Route::post('/update/blog/category/{id}', [BlogCategoryController::class, 'UpdateBlogCategory'])->name('update.blog.category');
```

### 2) Update the controller method
The method was updated to accept the ID and use it while updating the record:

```php
public function UpdateBlogCategory(Request $request, $id = null)
{
    $blogCategoryId = $id ?? $request->route('id');

    BlogCategory::findOrFail($blogCategoryId)->update([
        'blog_category' => $request->blog_category,
    ]);
}
```

### 3) Allow mass assignment in the model
The model was updated to allow the `blog_category` field:

```php
protected $fillable = ['blog_category'];
```

---

## Commands Used to Check / Verify

### Check the registered routes
```bash
php artisan route:list | grep 'blog/category'
```

### Clear cached routes
```bash
php artisan route:clear
```

### Clear config cache
```bash
php artisan config:clear
```

### Start the local server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

---

## Final Result
After applying the fixes:
- the route correctly passes the category ID,
- the controller updates the record successfully,
- and Laravel allows the `blog_category` field to be updated.
