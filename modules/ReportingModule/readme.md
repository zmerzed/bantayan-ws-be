# Kolette Reporting Module

## What?

The reporting module is used for implementing a reporting feature for your baseplate-core laravel application.

## Prerequisites

- PHP 8.1
- baseplate-core (https://gitlab.com/Kolette/baseplate/backend/baseplate-core)
- auth-module (will be auto installed by `lego`) (https://gitlab.com/Kolette/baseplate/backend/auth-module)

## Installation

1. php artisan lego:install ReportingModule
2. Register the module service provider in config/concord.php
   ```php
   'modules' => [
       \Kolette\Reporting\Providers\ModuleServiceProvider::class,
   ],
   ```
3. Apply the `Kolette\Reporting\Models\Traits\CanBeReported` trait to the User model

   ```php
   // modules/AuthModule/Models/User.php

   namespace Kolette\Auth\Models;

   use Kolette\Reporting\Models\Traits\CanBeReported;
   use Illuminate\Foundation\Auth\User as Authenticatable;

   class User extends Authenticatable
   {
       use CanBeReported;
       // ...
   ```

4. php artisan migrate
5. php artisan db:seed --class="Kolette\Reporting\Seeds\ReportCategoriesSeeder"
6. That's it!

## Usage

### Adding more reportable models

To add an eloquent model that can be reported, navigate to `modules/ReportingModule/src/resources/config/box.php` and
add the model's FQCN (Full
Qualified Class Name) into the `reportables` array. By default, the User model is added as a reportable.

Example:

```php
// modules/ReportingModule/src/resources/config/box.php

use Kolette\Auth\Models\User;
use App\Models\Post;

return [
    // ...
    'reportables' => [
        User::class,
        Post::class,
    ],
];
```

After that, apply the `Kolette\Reporting\Models\Traits\CanBeReported` trait to the new reportable model class.

Example:

```php
// app/Models/Post.php

namespace App\Models;

use Kolette\Reporting\Models\Traits\CanBeReported;

class Post extends Model
{
    use CanBeReported;
    // ...
```
