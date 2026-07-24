# Profile Picture Upload Bug Fix Tutorial

This document explains the profile image problem, how it was fixed, and how to verify it.

## 1. Problem
When trying to change the profile picture, Laravel threw this error:

```text
Illuminate\Database\QueryException
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'profile_image' in 'field list'
```

This happened because the code was trying to save the uploaded image filename into the `users` table, but the `profile_image` column did not exist in the database.

## 2. Root Cause
The controller was executing code similar to this:

```php
$data->profile_image = $filename;
$data->save();
```

But the database schema did not have a `profile_image` column.

## 3. Fix Applied
A migration was created to add the missing column to the `users` table.

### Migration file
The fix was added in:

- `database/migrations/2026_07_13_091559_add_profile_image_to_users_table.php`

### Migration content
The migration adds a nullable column:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('profile_image')->nullable()->after('password');
});
```

## 4. Command used to fix it
Run this command in the project folder:

```bash
cd /home/mahbub/Udemy/udemy-basic-laravel
php artisan migrate
```

This applies the new database change.

## 5. Why this fixes the issue
After the migration, the database can accept the uploaded profile image filename and save it safely in the `users` table.

## 6. What you should do now
1. Open the profile page
2. Choose a new image
3. Click update
4. Confirm that the image uploads successfully

## 7. Helpful check
If you want to check whether the column exists, you can run:

```bash
php artisan migrate
```

If the migration already ran successfully, the database is ready.

## 8. Summary
- The error was caused by a missing database column.
- The fix was to add `profile_image` to the `users` table.
- The migration was run successfully.
- The image upload should now work.

## 9. Simple explanation
Think of it like this:

- Your code says: “save the profile image name in the database”
- But the database says: “I do not have a place for that information”
- So the migration creates that place

That is why the upload issue is now resolved.
