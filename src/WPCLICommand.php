<?php

namespace WCM\WPCLIComposer;

use Composer\Script\Event;
use Composer\IO\IOInterface;

class WPCLICommand
{
	/**
	 * Append the WP-CLI auto-completion bash script to the users `~/.bash_profile`
	 * @param Event $event
	 * @return bool
	 */
	public static function postPackageInstall( Event $event )
	{
		$io = $event->getIO();
		if ( $io->askConfirmation( '<info>Do you want to append WP-CLI bash autocomplete to `.bash_profile?`?</info> [<comment>Y/n</comment>] ', false ) )
		{
			$package = $event
				->getComposer()
				->getPackage();
			$extra = $package->getExtra();

			if ( ! isset( $extra['bash-profile-dir'] ) )
			{
				$io->write( '<info>You must set "extra" : { "bash-profile-dir" } in your `composer.json` file. Skipping.</info>' );
				return false;
			}

			$search = 'wp-cli/wp-cli';
			$requires     = array_keys( $package->getRequires() );
			$requires_dev = array_keys( $package->getDevRequires() );
			if (
				! isset( $requires[ $search ] )
				XOR ! isset( $requires_dev[ $search ] )
				)
			{
				$io->write( '<info>This package is a dependency of `wp-cli/wp-cli`. Skipping.</info>' );
				return false;
			}

			$target = self::getDir( $io, $extra['bash-profile-dir'] );
			$target = "{$target}/.bash_profile";

			$source = "\n".file_get_contents( __DIR__.'/../ci/.wpcli_profile' );
			if (
				(
					file_exists( $target )
					AND false === strpos( file_get_contents( $target ), $source )
				)
				OR ! file_exists( $target )
				)
			{
				file_put_contents(
					$target,
					$source,
					FILE_APPEND
				);
				$io->write( '<info>Successfully appended WP-CLI auto-completion to your bash profile.</info>' );
			}
			else
			{
				$io->write( '<info>No need to append anything to your bash profile.</info>' );
			}

			return true;
		}
		return false;
	}

	/**
	 * Check if the `.bash_profile` root dir exists. Prompt for a new one if it does not exist.
	 * @param IOInterface $io
	 * @param string      $dir
	 * @return mixed
	 */
	public static function getDir( IOInterface $io, $dir )
	{
		if ( ! is_dir( $dir ) )
		{
			$io->write( sprintf(
				'<info>The `.bash_profile` specified location specified in the composer.json file: %s does not exist.</info>',
				$dir
			) );
			$dir = $io->ask( '<info>Please provide the root directory for your `.bash_profile` file:</info> ', $dir );
			return self::getDir( $io, $dir );
		}

		return $dir;
	}
}