# Home Slide Data Insert / Upload Bug Fix Tutorial

This document explains how the Home Slide backend upload bug was checked, diagnosed, and fixed in this Laravel project.

## 1. Problem Summary

When submitting the Home Slide update from the backend, Laravel threw this exception:

```text
Intervention\Image\Exceptions\NotWritableException
Can't write image to path. Directory does not exist.
```

The exception came from the Home Slider update controller when trying to save the uploaded image file.

---

## 2. Error Location

The failing line was inside:

- `app/Http/Controllers/Home/HomeSliderController.php`

Specifically at:

```php
Image::read($image)->resize(600, 600)->save('upload/home_slide/' . $name_gen);
```

This indicated that Laravel/Intervention Image was trying to write the image into a directory that was not available at the expected filesystem location.

---

## 3. Files Involved

### Main controller
- `app/Http/Controllers/Home/HomeSliderController.php`

### Related upload folders
- `upload/home_slide/`
- `public/upload/home_slide/`

### Supporting frontend layout
- `resources/views/frontend/main_master.blade.php`

---

## 4. How the issue was checked

The following commands were used to investigate.

### 4.1 Inspect the controller code

Command used:

```powershell
Get-Content .\app\Http\Controllers\Home\HomeSliderController.php
```

Purpose:
- Confirm how the uploaded image was being saved
- Verify whether the controller used a proper file path or a relative path that could break on the local environment

Observed issue:
- The code used:

```php
Image::read($image)->resize(600, 600)->save('upload/home_slide/' . $name_gen);
```

This is a relative path and is not safe for the public webroot flow used by Laravel in this project.

---

### 4.2 Check whether the target upload folder exists

Command used:

```powershell
Get-ChildItem .\upload -Force | Select-Object Name,FullName
Get-ChildItem .\upload\home_slide -Force -ErrorAction SilentlyContinue | Select-Object Name,FullName
```

Purpose:
- Confirm whether the storage path used by the controller existed on disk

Observed result:
- `upload/home_slide` existed in the repository root view, but the public webroot path used by the server was not the same as the path the controller was targeting safely.

---

### 4.3 Search for similar image save patterns in other controllers

Command used:

```powershell
rg -n "public_path\(|upload/home_slide|upload/home_about|save\('upload" .\app -g '*.php'
```

Purpose:
- Compare the slider upload pattern with other controllers using the same image saving approach

Observed result:
- Several controllers used the same relative save pattern for uploads
- This confirms the error is not unique to the Home Slider controller and is a path-handling issue common across image upload logic

---

### 4.4 Check public upload directories

Command used:

```powershell
Get-ChildItem .\public -Force | Select-Object Name
Get-ChildItem .\public\upload -Force -ErrorAction SilentlyContinue | Select-Object Name
Get-ChildItem .\public\upload\home_slide -Force -ErrorAction SilentlyContinue | Select-Object Name
```

Purpose:
- Confirm the actual public-facing upload directory used by web requests

Observed issue:
- `public/upload/home_slide` was missing
- The controller was attempting to save into a path that the public webroot could not safely resolve

---

## 5. Root Cause

The root cause was an incorrect write destination for uploaded images.

The code originally tried to write the file as:

```php
'upload/home_slide/' . $name_gen
```

That is a relative path, and in this environment the Laravel app expects the public asset write path to be anchored under the `public` directory.

Because the `public/upload/home_slide` directory did not exist, Intervention Image threw:

```text
Can't write image to path. Directory does not exist.
```

---

## 6. Exact Fix Applied

The HomeSliderController upload logic was updated to use the real public path.

### Old code

```php
$image = $request->file('home_slide');
$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
Image::read($image)->resize(600, 600)->save('upload/home_slide/' . $name_gen);
$save_url = 'upload/home_slide/' . $name_gen;
```

### New code

```php
$image = $request->file('home_slide');
$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
$destinationPath = public_path('upload/home_slide');

if (!is_dir($destinationPath)) {
    mkdir($destinationPath, 0755, true);
}

Image::read($image)->resize(600, 600)->save($destinationPath . '/' . $name_gen);
$save_url = 'upload/home_slide/' . $name_gen;
```

### Why this fix works

- `public_path('upload/home_slide')` points to the correct public webroot destination
- `mkdir(..., true)` creates the folder automatically if it is missing
- The uploaded file is now saved where Laravel can serve it from the browser-facing public directory

---

## 7. Additional File-system Adjustment

The folder needed to exist under the public webroot before saving. The following directory was created:

```powershell
New-Item -ItemType Directory -Path .\public\upload\home_slide -Force | Out-Null
```

This ensures the image can be written successfully during the backend update submission flow.

---

## 8. Validation Commands

### 8.1 PHP syntax check

Command used:

```powershell
wsl -e php -l /home/mahbub/Udemy/udemy-basic-laravel/app/Http/Controllers/Home/HomeSliderController.php
```

Expected result:

```text
No syntax errors detected
```

---

### 8.2 Confirm public directory exists

Command used:

```powershell
Get-ChildItem .\public\upload\home_slide -Force -ErrorAction SilentlyContinue | Select-Object Name
```

Expected result:
- Directory exists and is writable by the app

---

## 9. Final Result

After applying the fix:

- the slider image save target is now the correct public path
- the missing directory is created automatically when needed
- the `NotWritableException` should no longer occur on the backend Home Slide update submission

---

## 10. Reminder for Similar Bugs

The same pattern may exist in other controllers that save uploaded images into a relative path like:

```php
upload/blog/...
upload/portfolio/...
upload/home_about/...
upload/multi/...
```

Those should be checked and normalized to use `public_path(...)` where appropriate, especially when the image is expected to be served publicly.
