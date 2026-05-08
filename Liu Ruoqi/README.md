# QHE4103-car-sell-use-AI-

Seller registration module for the QHE4103 Online Car Sale project, converted to PHP + MySQL.

## Pages

- `register.php`: Seller registration, saved to MySQL
- `register.html`: Redirect helper for old links

## Backend

- `database.sql`: Creates the `veluxe_motors` database and `sellers` table
- `includes/db.php`: MySQL connection settings
- `includes/app.php`: Shared escaping, redirects, and page-message helpers
- `actions/register.php`: Handles seller registration

## Local setup

From the project root:

```powershell
.\php.cmd -S localhost:8000
```

Then open:

```text
http://localhost:8000/Liu%20Ruoqi/register.php
```

Before using the pages, import `database.sql` into MySQL. If your MySQL username or password is not `root` with an empty password, edit `includes/db.php`.
