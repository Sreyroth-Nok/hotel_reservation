# Login Credentials

## Issue Fixed
The login was not working because passwords in the database were stored as plain text instead of being hashed with bcrypt. This has been fixed.

## Available User Accounts

### Admin Account
- **Username:** `sreyroth`
- **Password:** `roth123`
- **Role:** Admin

### Staff Account
- **Username:** `test1`
- **Password:** `test123`
- **Role:** Staff

### Admin Account (Alternative)
- **Username:** `admin`
- **Password:** (Already hashed - use existing password)
- **Role:** Admin

### Staff Account
- **Username:** `staff`
- **Password:** (Already hashed - use existing password)
- **Role:** Staff

## What Was Fixed

1. **Password Hashing**: All plain text passwords have been hashed using bcrypt
2. **User Model**: Updated to use `'hashed'` cast for password field (automatic hashing)
3. **Login Controller**: Enhanced to support "remember me" functionality
4. **Authentication**: Now properly authenticates users and redirects to dashboard based on role

## Testing Login

1. Go to `/login`
2. Enter username: `sreyroth`
3. Enter password: `roth123`
4. Click "Sign In"
5. You should be redirected to the dashboard

## Notes

- All new users created through the UserController will have their passwords automatically hashed
- The password field now uses Laravel's `'hashed'` cast for automatic hashing
- Authentication uses username instead of email
