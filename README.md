# user_manager
Single-sign-on provider for Media websites.


## Installation instructions

Download the zip file and place the contents of the "source" folder 
in the desired location on your server. Update the app/config.php file with your
database settings.

If the database is configured correctly the site will automatically create the
required databases and create an initial user with all available permissions


##Adding permissions

Permissions are defined in config.php. User ownership of a permission can be
represented as a boolean value - true means a user has that permission. The permission
description is the only data about the permission stored in the user manager - other sites
must query the authentication server to determine if a user has a permission.


##API

Please read the API doc in the docs folder for more information
