# Lineage II Database Website

A modern PHP/MySQL website for managing and displaying Lineage II game data. This project consists of two main sections:

1. **Public Section**: A frontend for players to browse and search game information.
2. **Admin Section**: A backend interface for administrators to manage the database content.

## Project Structure

```
-website
--includes        (shared PHP includes)
--assets
---css            (stylesheets)
---js             (JavaScript files)
---img            (images)
--pages           (public pages)
---weapons
---armor
---items
---maps
----map-list.php
----map-detail.php
--admin
---includes       (admin-specific includes)
---pages          (admin pages)
----weapons
-----admin-weapon-list.php
-----admin-weapon-detail.php
```

## Features

### Public Section

- Responsive card-based layout
- Detailed information on weapons, armor, items, monsters, maps, and more
- List and detail views for each category
- Search functionality
- Modern design with clean navigation

### Admin Section

- Dashboard with database statistics
- CRUD operations for all database tables
- Input validation and error handling
- Intuitive interface for database management
- Secure authentication (placeholder implemented)

## Setup Instructions

1. **Database Configuration**:
   - Import the database schema from `l1j_remastered.sql`
   - Configure database connection in `includes/db_connect.php`

2. **Web Server Configuration**:
   - Set up a web server (Apache, Nginx, etc.) with PHP support
   - Configure the web server to point to the project directory
   - Ensure PHP 7.2+ is installed with MySQL/MySQLi support

3. **Adjust Base URLs**:
   - Edit `includes/config.php` to set the correct base URLs for your environment

4. **File Permissions**:
   - Ensure appropriate read/write permissions for the web server user

## Technologies Used

- PHP 7.2+
- MySQL/MariaDB
- HTML5
- CSS3
- JavaScript (vanilla)
- FontAwesome for icons

## Color Scheme

This project uses a strict color palette:

```css
--text: #ffffff;
--background: #030303;
--primary: #080808;
--secondary: #0a0a0a;
--accent: #f94b1f;
```

## Main Categories

The database covers the following main categories:

- Weapons
- Armor
- Items
- Monsters
- Maps
- Magic Dolls
- NPCs
- Skills
- Polymorph

## Development Guidelines

- No inline CSS or JavaScript
- Separate concerns between public and admin sections
- Follow naming conventions for files
- Use prepared statements for database queries
- Sanitize all input data

## Sample Pages

- **Public**: `index.php`, `pages/maps/map-list.php`, `pages/maps/map-detail.php`
- **Admin**: `admin/index.