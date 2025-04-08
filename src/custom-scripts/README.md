# Custom Scripts Plugin

> For Onlygames.com.ar

Create, Edit and Delete blocks of code that will be appended to the page footer. 
Manage them through an enable/disabled `boolean` value

## Form Submission

Wordpress handle custom form submission through the <form> action `admin-post.php`. It's required to register a custom action with the `admin_post_` prefix and then pass a callback to it.

Finally, the <form> tag should have this custom action name as the `action` attribute value

```php
<form action="admin-post.php?action=admin_posts_my_custom_action" />
```

### Form Action

Passing a hidden field with the `name` and `id` as `'action'` it's required to make each form work as expected

```php
define('ACTION_NAME', 'my_submit_action');
<input type="hidden" id="action" name="action" value="<?= ACTION_NAME ?>">
```

## Edit Entity

Entity information is passed as default form input values in script-form.php on **each form field**. This means there's no need to pass an `$entity` object while rendering the Edit Form page