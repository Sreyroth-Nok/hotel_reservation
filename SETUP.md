1. Install Dependencies
--- PHP Dependencies: Run this in your terminal to install all the Laravel framework packages.
``` composer install ```
``` npm install ```

2. Environment Configuration
Update Settings: Open the new .env file in your editor and update your database name, username, and password to match your local setup (e.g., MySQL or PostgreSQL).

3. Generate Application Key
Run the generator:
``` php artisan key:generate ```

4. Database Setup
Run Migrations:
``` php artisan migrate ```

5. Link Storage (Optional)
If the project involves file uploads (like profile pictures), you need to link the private storage folder to the public web folder.
``` php artisan storage:link ```

6. Compile Assets & Start
Start Laravel: Open a new terminal tab and run:
``` php artisan serve ```