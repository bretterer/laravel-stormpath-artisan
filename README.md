This Laravel 4 package provides a variety of commands to help you in development communicate with the Stormpath API.  These commands include:

- `stormpath:application`


## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `lamarus/stormpath-artisan`.

    "require": {
		"laravel/framework": "4.0.*",
		"lamarus/stormpath-artisan": "dev-master"
	}

Next, update Composer from the Terminal:

    composer update

Once this operation completes, the next step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

    'Lamarus\StormpathArtisan\StormpathArtisanServiceProvider'

The final step for setting up the Stormpath commands is to publish the config so you can set your key and secret.  Run the following from the Terminal:
    
    php artisan config:publish lamarus/stormpath-artisan

After you have done this go to `app/config/packages/lamarus/stormpath-artisan` and edit the `config.php` file to include your key and secret.


That's it! You're all set to go. Run the `artisan` command from the Terminal to see the new `stormpath` commands.

    php artisan


## FAQ

Q.  HELP!  Im getting an eror when running php artisan stormpath: `"Call to a member function get() on a non-object"`

A.  Make sure you run `php artisan config:publish lamarus/stormpath-artisan` and include your key and secret in `app/config/packages/lamarus/stormpath-artisan/config.php`
