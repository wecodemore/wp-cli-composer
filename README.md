# WP-CLI Composer post-package-install script

Install WP-CLI [using Composer](https://github.com/wp-cli/wp-cli/wiki/Alternative-Install-Methods).
We recommend to use something like Andreys/[@Rarst](https://twitter.com/Rarst) recipe for a
[site stack](http://composer.rarst.net/recipe/site-stack) to get a thoughtful base structure for
your project. Simply add [WP-CLI](wp-cli.org) on top of that:


	"require"      : {
		// ... other software installed to /var/www/wp-content/vendor
		"wp-cli/wp-cli"              : "0.17.*",
        "wecodemore/wp-cli-composer" : "~1.0"
	},

Then setup the script

	"scripts"      : {
		"post-install-package" : [
			"WCM\\WPCLIComposer\\WPCLICommand::postPackageInstall"
		]
	},

Finally you will need to define a pointer to tell the post package installer where wp-cli was
installed to