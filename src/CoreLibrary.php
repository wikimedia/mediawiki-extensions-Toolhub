<?php
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

use array_unshift;
use is_array;
use MediaWiki\MediaWikiServices;
use preg_match;
use Scribunto_LuaLibraryBase;

/**
 * Toolhub API client for Scribunto.
 *
 * @copyright © 2022 Wikimedia Foundation and contributors
 */
class CoreLibrary extends Scribunto_LuaLibraryBase {

	/**
	 * Register the library.
	 *
	 * @return array Lua package
	 */
	public function register() {
		$lib = [
			'getTool' => [ $this, 'getTool' ],
		];
		$settings = [];
		return $this->getEngine()->registerInterface(
			__DIR__ . '/mw.ext.toolhub.lua',
			$lib,
			$settings
		);
	}

	/**
	 * Cast a value to a Lua compatible form.
	 *
	 * @param mixed $val
	 * @return mixed
	 */
	private function toLua( $val ) {
		return [ $this->asLuaValue( $val ) ];
	}

	/**
	 * Convert a PHP value to a Lua compatible form.
	 *
	 * @param mixed $val
	 * @return mixed
	 */
	private function asLuaValue( $val ) {
		$type = $this->getLuaType( $val );
		if (
			$type === 'nil' ||
			$type === 'function' ||
			preg_match( '/^PHP .*/', $val )
		) {
			return null;
		}
		if ( is_array( $val ) ) {
			foreach ( $val as $key => $value ) {
				/* $val[ $key ] = $this->toLua( $value ); */
				$val[ $key ] = $this->asLuaValue( $value );
			}
			array_unshift( $val, '' );
			unset( $val[0] );
		}
		return $val;
	}

	/**
	 * Get info for a specific tool.
	 *
	 * @param string $name Name of the tool
	 * @return array
	 */
	public function getTool( $name ) {
		// FIXME: validate args in PHP so we can simplify the lua side
		// FIXME: toolhub base url should be a config var
		$req = "https://toolhub.wikimedia.org/api/tools/{$name}/";
		$http = MediaWikiServices::getInstance()->getHttpRequestFactory();
		$resp = $http->get( $req, [], __METHOD__ );
		if ( $resp === null ) {
			// FIXME: what should happen here?
			return [ 'error' => 'Got a null response so BOOM!' ];
		}
		// FIXME: cache non-negative results
		return $this->toLua( json_decode( $resp, true ) );
	}
}
