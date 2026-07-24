# Blog Category Add Error Message Bug

## Problem
When the user clicked the "Insert Blog Category" button without entering any value in the blog category field, the validation message did not appear.

## Root Cause
The jQuery validation plugin was trying to place the error message under the input field, but the input did not have a proper parent wrapper with the `form-group` class.

Because of that, the plugin could not append the error message to the correct place in the HTML.

## Fix Applied
I updated the Blade file and added the `form-group` class to the parent div of the input field.

### Updated HTML
```blade
<div class="col-sm-10 form-group">
    <input name="blog_category" class="form-control" type="text" id="example-text-input">
</div>
```

## Why This Works
The jQuery validation plugin uses the parent element to place the error message. When the parent has `form-group`, the validation error can be shown properly below the input field.

## Result
Now, when the field is left empty and the form is submitted, the message:

```text
Please Enter Blog Category Name
```

appears correctly.

## Simple Summary
- The bug was caused by the missing `form-group` wrapper.
- Adding that wrapper fixed the validation message display.
- The form now shows the required field error properly.
