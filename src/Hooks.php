<?php
declare( strict_types = 1 );
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */

namespace MediaWiki\Extension\Toolhub;

class Hooks {

	/**
	 * Register external libraries for Scribunto.
	 *
	 * @param string $engine
	 * @param string[] &$extraLibraries
	 */
	public function onScribuntoExternalLibraries(
		string $engine,
		array &$extraLibraries
	): void {
		if ( $engine !== 'lua' ) {
			return;
		}
		$extraLibraries[ 'mw.ext.toolhub' ] = [
			'class' => CoreLibrary::class,
			'deferLoad' => true,
		];
	}

}
