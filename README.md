## PHP Databaseless User Login and Registration System

Written in PHP this script uses JSON to store user details. It has two user roles: admin and user.
I have setup one user for each role with default values. Users and admins can edit their user 
details from the 'edit profile' link in their dashboard.

**User**: Admin\
**Password**: Admin123!

**User**: User\
**Password**: User123!

You can add, edit and delete users through the JSON file, or users can register on the website itself.
The script will check to see if the email address has already been used. Each role has a default avatar
that each user or admin can change in their dashboard. You can have as many users or admins as you need.

The admin user can change the styling of the website through the "Site Settings" function in their dashboard.
This will change the styling across the website for all users. They can use Bootstrap 5 defaults, or they can 
override Bootstrap default colors. Admins can also change the font and font sizes globally.