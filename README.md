# Humanitix Event Connector

Add humaniticx event metadata to event posts on the website

## Makefile commands

### `make up`

This will start the environment. During startup the db is initialized with the contents of `db.sql`. You always start from that point.

The up command also starts tailing the logs from the wordpress container. You can stop this with `ctrl+c`. Note that **the wordpress container will still be running**.

### `make down`

This will stop the environment.

### `make db`

This will export the current database state to db.sql, so you can commit it. Do this when ever you make a change to the wordpress environment, like adding dummy data posts or reconfiguring the plugin. **Don't forget to commit the changes to db.sql**.

### `make`

This will produce the file `./dist/humanitix-event-connector.zip`. This is the file you upload to the wordpress plugin page during installation.

## Usage

### Installation

1. Install the plugin
2. Add the humanitix `API key` to the plugin settings page
   - The API key [can be found here](https://console.humanitix.com/console/account/advanced/api-key)
   - The plugin settings page can be found under [`Settings > General`](http://localhost:8080/wp-admin/options-general.php)

### Add a humanitix event

1. Create a new post
2. Add the humanitix event id to the post meta field `Humanitix Event ID`
3. Save the post

The post will now respond to the shortcode `[humanitix_event]` and display the event details.
