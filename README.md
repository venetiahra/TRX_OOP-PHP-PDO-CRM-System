# TRX Clawd Fortress (PHP Edition with Client Portal)

This package adds a **dedicated client portal** so each client can:
- open a separate PHP login page
- edit personal information (name, email, contact number, address)
- view company data saved under the same company name
- change the client portal password

## Admin features included
- admin login/logout
- admin registration
- admin password change
- dashboard with charts
- client CRUD
- search + pagination
- export client list to PDF
- assign/update client portal credentials

## Main client portal files
- `public/client_login.php`
- `public/client_dashboard.php`
- `public/client_profile.php`
- `public/client_change_password.php`
- `public/client_logout.php`
- `public/manage_portal.php` (admin only)

## Setup
1. Copy the project folder to your server root.
2. Import `database/trx_clawd_fortress.sql` into phpMyAdmin.
3. Open admin login:
   `http://localhost/TRX_Clawd_Fortress_Client_Portal/public/`
4. Open client portal:
   `http://localhost/TRX_Clawd_Fortress_Client_Portal/public/client_login.php`

## Sample logins
### Admin
- `admin / admin123`

### Client portal
- `rafael.client / admin123`
- `patricia.client / admin123`
