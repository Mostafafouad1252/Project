# Typing Speed Test Application

A web-based typing speed test application that allows users to test and improve their typing speed and accuracy. The application features multiple difficulty levels, progress tracking, and a leaderboard system.

## Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript (ES6+)
- Font Awesome 6.0.0 (for icons)

### Backend
- PHP 7.4
- MySQL 5.7
- Apache 2.4

### Development Environment
- XAMPP 8.0.0 or higher
- Web Browser (Chrome, Firefox, Edge, or Safari)

## Features

- User authentication (login/register)
- Multiple difficulty levels (Easy, Medium, Hard)
- Real-time WPM (Words Per Minute) calculation
- Accuracy tracking
- Progress history
- Global leaderboard
- Dark/Light theme support
- Responsive design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP (recommended for easy setup)

## Installation

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache and MySQL services from XAMPP Control Panel

2. **Clone/Download the Project**
   - Place the project files in your XAMPP's htdocs directory:
     ```
     C:\xampp\htdocs\Typing speed 1
     ```

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `typing_speed_db`
   - Import the `database.sql` file from the project directory
   - The database will be automatically created with the required tables

4. **Configuration**
   - The database connection settings are already configured in `config.php`
   - Default settings are:
     - Host: localhost
     - Username: root
     - Password: (empty)
     - Database: typing_speed_db

## Running the Application

1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open your web browser and navigate to:
   ```
   http://localhost/Typing%20speed%201
   ```

## Project Structure

```
Typing speed 1/
├── index.php              # Home page
├── login.php             # Login page
├── register.php          # Registration page
├── typing_test.php       # Main typing test page
├── profile.php           # User profile page
├── leaderboard.php       # Global leaderboard
├── logout.php            # Logout handler
├── config.php            # Database configuration
├── database.sql          # Database structure
├── style.css             # Main stylesheet
├── script.js             # Typing test functionality
└── theme.js              # Theme toggle functionality
```

## Usage

1. **Registration/Login**
   - New users can register with a username, email, and password
   - Existing users can log in with their credentials

2. **Taking a Test**
   - Select difficulty level (Easy, Medium, Hard)
   - Click "Start Test" to begin
   - Type the displayed text
   - Results will show WPM and accuracy
   - Test history is saved automatically

3. **Viewing Progress**
   - Check your profile page for test history
   - View your best scores and average performance
   - Compare with other users on the leaderboard

4. **Theme Toggle**
   - Click the sun/moon icon to switch between light and dark themes
   - Theme preference is saved automatically

## Troubleshooting

1. **Database Connection Issues**
   - Ensure MySQL service is running in XAMPP
   - Verify database credentials in `config.php`
   - Check if database `typing_speed_db` exists

2. **Page Not Found**
   - Verify Apache service is running
   - Check if files are in correct directory
   - Ensure correct URL is being used

3. **Permission Issues**
   - Make sure XAMPP has proper permissions
   - Check file permissions in project directory

## Support

For any issues or questions, please contact the developer.

## License

This project is created for educational purposes. 