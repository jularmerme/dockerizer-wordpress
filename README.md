# ADK Dockerized Wordpress

Set up a local Linux/Apache/Mysql/PHP/Wordpress environment that mirrors ADK's Skylight server.

```
Apache: 2.4
MySQL: 5.5
PHP: 7.2.12
Wordpress: 4.9.8
Composer
```

Start a new project with the included `adk` theme, or migrate an existing repository to docker.

__You should not be tracking commits intended for another project on this repo.__

## Getting Started

If you're starting a new project, or migrating an existing project, make sure you remove the `.git` folder and initialize git for **your new repo**.

### Initialize Repo & Version Control:

```bash
$ rm -rf .git
$ git init
```

## Setting up the Database Connection

Create `envfile.txt` by cloning `envfile.local.txt`.  This file will contain your database environment variables.

```bash
$ cp envfile.local.txt envfile.txt
```

Fill out the Remote Filesystem username/host combination and folder path in the newly created envfile.txt. This is necessary to download plugins and media folders.

Create an additional envfile for each database connection.  __These files should not be tracked in version control.__

#### `envfile.local.txt`

```
LOCALHOST=mysql
MYSQL_DATABASE=adksite
MYSQL_USER=admin
MYSQL_PASSWORD=password
MYSQL_ROOT_PASSWORD=pass


REMOTE_FS_USER=alpha
REMOTE_FS_HOST=pch.adkalpha.com

# NO TRAILING SLASH
# IF SITE IS ON ADKALPHA USE THE FOLLOWING PATH AND JUST REPLACE {PROJECT_FOLDER_NAME}
REMOTE_FS_WP_PATH=/var/www/vhosts/adkalpha.com/staging/{PROJECT_FOLDER_NAME}
```

#### `envfile.staging.txt` / `envfile.production.txt`

```
REMOTE_DB_HOST=database.host.com
REMOTE_DB_NAME=database_name
REMOTE_DB_USER=user
REMOTE_DB_PASSWORD=password
```

#### `web/code/config/wp-config.php`

Wordpress will use the connection values defined in the envfiles to connect to the database.  This has already been set up in `wp-config.php`:

```
/** The name of the database for WordPress */
define('DB_NAME', getenv('MYSQL_DATABASE'));
/** MySQL database username */
define('DB_USER', getenv('MYSQL_USER'));
/** MySQL database password */
define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
/** MySQL hostname */
define('DB_HOST', getenv('LOCALHOST'));
```

A sample `wp-config.php` is included. Edit this file as needed.

## Database Management

Bash scripts to manage the database of this docker set up are included in the `/commands` directory. Run the following commands from the root directory of the repo.

#### Sync

`bash commands/sync-db.sh`

Import a remote database into your docker mysql instance (local database). Make sure your `envfiles` are properly set up.

1. Choose your environment by editing `docker-compose.yml` and uncomment the line within the mysql container that relates to the production or staging environment (depending on which you are syncing from). You will only need to uncomment __one__ of these lines.

```
#- ./envfile.staging.txt
#- ./envfile.production.txt
```

2. Restart your docker containers.

3. Run the `sync-db.sh` script:

```
bash commands/sync-db.sh
```

#### Import (requires SQL Dump)

`bash commands/import-db.sh <PATH TO YOUR SQL DUMP>`

Keep your large database dump files outside of this repo for security reasons and also in order to minimize the build time for Docker.

#### Backup:

`bash commands/backup-db.sh`

Create a **time-stamped backup** of your *current database*.

This should create a new time-stamped backup on the `mysql/mounted/backups` folder.

#### MYSQL error logs:

These can be viewed inside the **mysql/mounted/logs/** folder.

## Set and Connect to an Environment:

This repo comes out of the box with a `envfile.local.txt` which can be used for local development. Run the following command to start working locally:

`bash commands/change-env.sh local`

#### Change Environment Example:

`bash commands/change-env.sh production`

`change-env.sh` will simply copy your environment credentials to the `envfile.txt` which is read by docker.

#### How do I connect or open my local database in my SQL client?

This Docker instance comes out of the box with **phpmyadmin** on port 8888.
If you want to use any MySQL client, connect to your database as you normally do and set the host value to your localhost since MySQL is running on the default port **3306**.

## Start the Docker Containers

Before you try to start the containers, make sure that all your settings steps are done.

```
git clone the repo
cd <Your cloned folder>
docker-compose up --build
```

 If you are using a mac, ensure you are sharing the project folder in the Docker sharing tab under preferences.

 If you go to **http://localhost:8000/** you should see a **phpinfo** index.

## Wordpress Installation

- `web/code/wp-content`

The Wordpress core files and install are configured in the `Dockerfile` inside the web folder. Add the site theme, plugins, uploads, etc to the `wp-content` directory.

For new sites, plugins and uploads folders are ignored from the repo to avoid being tracked. There are two bash script commands that allow for downloading these two directories from the dev or staging server. These commands are:

`bash commands/sync-plugins.sh`
`bash commands/sync-uploads.sh`

## Wordpress Theme

The `adk` starter theme has been included in this repo. If you are migrating an existing project, __please remove this theme__.  Otherwise, please read the theme's Readme to begin a new project.

[ADK Theme Readme](https://bitbucket.org/adkgroup/dockerized-wordpress/src/master/web/code/wp-content/themes/adk/README.md)
# dockerizer-wordpress
