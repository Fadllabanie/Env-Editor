# EnvEditor

| Version  | Features     |
| :---:   | :---: |
| V.1 | Edit env file   |


**EnvEditor** is a Laravel package that allows you to easily view and edit your `.env` file through a web interface.

## Features
- View and edit `.env` variables in a form-based interface.
- Add new key-value pairs.
- Remove existing keys.
- IP restriction for security.
- Username/password authentication.

## Installation

Follow these steps to install the package into your Laravel project:

1. Install the package via Composer:

```bash
composer require fadllabanie/env-editor
```

2. Publish the package configuration and views:

```bash
php artisan vendor:publish --tag=config --provider="Fadllabanie\EnvEditor\EnvEditorServiceProvider"
```
This will create the `config/env-editor` php file and publish the views required for the UI.

3. Add the following environment variables to your `.env` file:
```bash
ENV_EDITOR_ENABLE=true
ENV_EDITOR_USERNAME=admin
ENV_EDITOR_PASSWORD=password
ENV_EDITOR_WHITE_IPS_LIST=127.0.0.1
```

4. Optionally, update the configuration in `config/env-editor` php to customize the `username`, `password`, and `IPs` restrictions.

# Usage
1. Access the EnvEditor Interface: Visit the route `/env-editor/login` to log in and access the interface.

2. Authentication: Use the username and password specified in the `.env` file (or the default ones set in `config/env-editor.php`).

3. Edit .env File: Once authenticated, you can view the current `.env` file, modify existing variables, or add new key-value pairs.

4. Logout: You can log out from the interface at any time using the logout button.

# Security

1. IP Restriction: You can restrict access by IP using the `ENV_EDITOR_WHITE_IPS_LIST` environment variable. Add a comma-separated list of allowed IP addresses.

2. Session Time: The session expires after <strong>2 minutes</strong> by default.

# Configuration
After publishing the configuration file, you can modify the following settings in `config/env-editor.php`:

```bash 
return [
    'env-editor-enable' => env('ENV_EDITOR_ENABLE', true),
    'white_ips_list' => env('ENV_EDITOR_WHITE_IPS_LIST', ['127.0.0.1']),
    'username' => env('ENV_EDITOR_USERNAME', 'admin'),
    'password' => env('ENV_EDITOR_PASSWORD', 'password')
];
```

# Clearing Config Cache

you make changes to the `.env` file or `config/env-editor.php`, make sure to clear the config cache to reflect the updates:

```bash 
php artisan config:clear
```

# License
This package is open-source and licensed under the MIT License.


### How to Use This:
1. **Copy the text** to a `README.md` file in the root of your package directory.
2. **Customize** any parts (like the IPs, username, or password) according to your specific setup.
3. **Include instructions** for how to install, configure, and use the package.

Let me know if you need any changes or further clarifications!
