# Online Learning Platform

This repository contains a PHP-based learning platform for English and Math exercises, worksheets, and games.

## Project structure

- `*.php` - server-side pages and actions
- `images/` - image assets
- `files/` - static downloadable files
- `uploads/` - runtime file uploads (ignored by Git)
- `db.php` - database connection

## Notes

- `uploads/` is excluded from Git because it contains user-uploaded files.
- If you want to push this repository to GitHub, make sure the `.gitignore` file is included.

## Getting started

1. Place the project in your local web server root.
2. Configure `db.php` with your database settings.
3. Access `index.php` in your browser.
