# Laravel Static DB Data

Sync static data between code and db.

## Problem Use Case

Say you have db data that is static-ish (never changes at runtime) that has business logic directly dependent on it. 

For example a db table `plans` where each user has a `plan_id` indicating their current subscription plan.

id | name
--- | ---
1 | Free
2 | Premium
3 | Pro

The laravel application will need to have code that behaves differently depending on that data. For example:

```php
$user = Auth::user();
if($user->plan_id !== 2){
    // deny access to feature requiring Premium plan
}

if($user->plan_id !== 3){
    // deny access to feature requiring Pro plan
}

```

## Using Static DB Data

### Model 

Static data requires a model.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public $timestamps = false;
}
```

### StaticDBData class

The `Plans` class represents the static data that the application requires to function correctly. This file is the source of truth for this data. It will be referenced in code and used to sync the data to the db.

```php
namespace App\Models\StaticDBData;

use UnstoppableCarl\StaticDBData\StaticDBData;

class Plans extends StaticDBData
{
    public const FREE = 'Free';
    public const FREE_ID = 1;

    public const PREMIUM = 'Premium';
    public const PREMIUM_ID = 2;

    public const PRO = 'Pro';
    public const PRO_ID = 3;

    protected $primaryKey = 'id';

    public function data(): array
    {
        return [
            [
                'id' => self::FREE_ID,
                'name' => self::FREE,
            ],
            [
                'id' => self::PREMIUM_ID,
                'name' => self::PREMIUM,
            ],
            [
                'id' => self::PRO_ID,
                'name' => self::PRO,
            ],
        ];
    }
}
```

### Seeder

The seeder syncs data from the `Plans` class to the db. 

The seeder uses `$model->query()->updateOrCreate([$primaryKey => $rowId], $rowData);` internally.

```php
<?php

namespace Database\Seeders;

use UnstoppableCarl\StaticDBData\Concerns\SeedsFromStaticData;
use App\Models\Plan;
use App\Models\StaticDBData\Plans;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    use SeedsFromStaticData;
    
    public function __construct(Plans $staticData, Plan $model)
    {
        $this->staticData = $staticData;
        $this->model = $model;
    }
}
```

### In Practice

The seeder can be run every deploy as it only updates data never deleting existing ids (or run manually if required).

Once setup you can safely rely on the data in the `Plans` class to be identical in the db at runtime. 

For example, you could register gates with very clear code never having to query the db for plan data.

```php
use App\Models\StaticDBData\Plans;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

// all users have access
Gate::define('featureAlpha', function (User $user) {
    return true;
});

Gate::define('featureBeta', function (User $user) {
    $allowed = [
        Plans::PREMIUM_ID,
        Plans::PRO_ID,
    ];

    if (!in_array($user->plan_id, $allowed)) {
        return Response::deny('You are not authorized to use featureBeta. Upgrade to a Premium or Pro account for access.');
    }

    return true;
});

Gate::define('featureGama', function (User $user) {

    if ($user->plan_id !== Plans::PRO_ID) {
        return Response::deny('You are not authorized to use featureGama. Upgrade to a Pro account for access.');
    }

    return true;
});
```

## Solution Requirements
There are many ways to handle this problem but none met all of my requirements. These were my solution requirements.

 - keep ids in code and db in sync
 - use foreign key integrity in the db using normal integer ids
 - improve code readability when using the static data
 - cleanly organize the data in code
 - avoid querying the db for data that does not change at runtime
 - be able to easily understand the data in the db without the matching code
