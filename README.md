![CircleCI Test Status: Master Branch](https://circleci.com/gh/wecodemore/wp-cli-composer/tree/master.svg?style=shield&circle-token=8ed7e3862c3aa5e9b02558be9679cc87881eb59d)

# WP-CLI Bash Autocomplete

Usage as Composer post-package-install script. Adds bash autocompletion when WP-CLI gets installed
using Composer. **This package** mostly **is a convenience package that should make the build process
easier**. 

This package needs 20 seconds to set up. Zero maintenance.

## How To

Install WP-CLI [using Composer](https://github.com/wp-cli/wp-cli/wiki/Alternative-Install-Methods).
your project. Simply add [WP-CLI](wp-cli.org) on top of that:

```json
"require"      : {
	"wp-cli/wp-cli"              : "~1.0",
	"wecodemore/wp-cli-composer" : "~1.0"
},
```

Then setup the script

```
"scripts"      : {
	"post-install-cmd" : [
		"WCM\\WPCLIComposer\\WPCLICommand::postPackageInstall"
	]
},
```

Finally you will need to define a pointer to tell the post package installer where wp-cli was
installed to. In most cases this simply will be your users home directory/`~`, but you can
define custom locations as well.

```
"extra" : {
	"wordpress-install-dir" : "wp",
	"bash-profile-dir"      : "/home/youruser"
}
```

## FAQ

#### **Q:** Shall I install it from GitHub or from Packagist?

**A:** The package is on Packagist and auto updated from GitHub instantly (using WebHooks).

#### **Q:** If I ran this twice by accident, do I then have the scripts appended twice?

**A:** No, the script is smart enough to care about that and appends itself only once.

#### **Q:** What happens if I'm not sure and the bash profile location is probably wrong?

**A:** The script does only check if the directory exists and not if 
you got a `.bash_profile` file there. If there is none, it will attempt to create one for you.

#### **Q:** What version should I refer to in my `composer.json`?

**A:** We use [semantic versioning](http://semver.org/), so you will want to stay up to date with major versions.

#### **Q:** Should I visit Vienna?

**A:** Yes. You won't regret it. Ping me and I'll grab a coffee with you.