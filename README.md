# WP-CLI Bash Autocomplete

Usage as Composer post-package-install script. Adds bash autocompletion when WP-CLI gets installed
using Composer. This package mostly is a convenience package that should make the build process
easier.

## How To

Install WP-CLI [using Composer](https://github.com/wp-cli/wp-cli/wiki/Alternative-Install-Methods).
We recommend to use something like Andreys/"[@Rarst](https://twitter.com/Rarst)" recipe for a
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

	"extra":        {
		"wordpress-install-dir": "wp",
		"wp-cli-bin":            "foo"
	}

## FAQ

> **Q:** Shall I install it from GitHub or from Packagist?

> **A:** The package is on Packagist and auto updated from GitHub instantly (using WebHooks).

> **Q:** What version should I refer to in my `composer.json`?

> **A:** We use [semantic versioning](http://semver.org/), so you will want to stay up to date with major versions.

> **Q:** Should I visit Vienna?

> **A:** Yes. You won't regret it. Ping me and I'll grab a coffee with you.