### For troywebconsulting
### Kyle's Library - Setup Instructions

Utilizes:
- Laravel 12
- Inertia JS w/ Vue.js
- Laravel Starter Kit for Vue with Inertia, Ziggy & shadcn (component library)

Prerequisties:
You have PHP 8.3+, Composer, NPM & MySQL / SQLite.

- git clone https://github.com/kyledoesdev/library
- cd library
- composer i
- cp .env.example .env
- Configure your database credentials (sqlite / mysql)
- php artisan migrate --seed (ensure that you seed the database for best experience!!!!)
- php artisan storage:link
- npm i
- npm run dev

There are 2 default users you can log in as. - Or create your own users.
- Librarian:
  - Email: librarian@mail.com
  - Password: password
- Customer:
  - Email: customer@mail.com
  - Passwrod: password

Other Notes:

Database diagram is in /public <br>
app/Commands/RefreshBookList - adds 10 books to the book list
