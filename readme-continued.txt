- **Admin**: `admin/index.php`, `admin/pages/maps/admin-map-list.php`, `admin/pages/maps/admin-map-detail.php`

## Authentication

Note: The current implementation contains placeholder authentication. In a production environment, you should implement proper authentication and authorization:

- Secure login system with password hashing
- Session management
- Role-based access control
- CSRF protection
- Input validation and sanitization

## File Structure Details

### Core Files

- `includes/config.php` - Global configuration settings
- `includes/db_connect.php` - Database connection and query functions
- `includes/header.php` and `includes/footer.php` - Public section layout components
- `admin/includes/admin-config.php` - Admin-specific configuration
- `admin/includes/admin-header.php` and `admin/includes/admin-footer.php` - Admin section layout components

### CSS Files

- `assets/css/style.css` - Main stylesheet for public section
- `admin/assets/css/admin-style.css` - Admin section stylesheet

### JavaScript Files

- `assets/js/main.js` - Public section functionality
- `admin/assets/js/admin.js` - Admin section functionality

### Utility Functions

- Category-specific utility files (e.g., `includes/map-functions.php`)
- Common functions in configuration files

## Extending the Project

To add a new category to the database:

1. **Create Database Functions**:
   - Create a new utility file (e.g., `includes/category-functions.php`) with CRUD functions

2. **Create Public Pages**:
   - Create list view (`pages/category/category-list.php`)
   - Create detail view (`pages/category/category-detail.php`)

3. **Create Admin Pages**:
   - Create admin list view (`admin/pages/category/admin-category-list.php`)
   - Create admin detail view (`admin/pages/category/admin-category-detail.php`)

4. **Update Navigation**:
   - Add new category to navigation menus in header files

## Performance Considerations

- Database indexes are crucial for performance
- Consider pagination for large datasets
- Cache frequently accessed data
- Optimize images before uploading

## Security Considerations

- Validate and sanitize all user inputs
- Use prepared statements for database queries
- Implement proper authentication and authorization
- Keep software updated
- Apply principle of least privilege

## License

This project is intended for educational purposes. Game data and assets belong to their respective owners.

## Contributors

- [Your Name] - Initial development