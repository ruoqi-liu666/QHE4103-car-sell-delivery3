# Add Car PHP + MySQL Module

This folder keeps the new work separate from `Liu Ruoqi`.

## What it does

- Reuses the existing `sellers` table for seller information.
- Adds a `vehicles` table for car listings.
- Connects each car to one seller through `vehicles.seller_id`.
- Provides pages for seller registration, adding cars, and viewing saved inventory.

## Files

- `database.sql`: Creates `sellers` and `vehicles`
- `register.php`: Register seller information
- `add-car.php`: Add vehicle information for a registered seller
- `inventory.php`: View saved vehicle listings with seller contact details
- `actions/register.php`: Saves seller data
- `actions/add-car.php`: Saves vehicle data
- `includes/db.php`: Database connection
- `includes/app.php`: Shared helper functions

## Local setup

Import `database.sql` into MySQL first.

Then run from the project root:

```powershell
.\tools\php\php.exe -S localhost:8000
```

Open:

```text
http://localhost:8000/Bi%20Qinzhi/add-car.php
```

If your MySQL username or password is not `root` with an empty password, edit `includes/db.php`.

