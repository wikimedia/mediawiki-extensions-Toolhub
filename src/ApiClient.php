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

use MediaWiki\Http\HttpRequestFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Toolhub API client.
 *
 * @copyright © 2022 Wikimedia Foundation and contributors
 */
class ApiClient implements LoggerAwareInterface {

	/** @var HttpRequestFactory */
	private $requestFactory;
	/** @var string */
	private $baseUrl;
	/** @var LoggerInterface */
	private $logger;

	/**
	 * Constructor.
	 *
	 * @param HttpRequestFactory $requestFactory
	 * @param string $baseUrl
	 */
	public function __construct(
		HttpRequestFactory $requestFactory,
		string $baseUrl
	) {
		$this->requestFactory = $requestFactory;
		$this->baseUrl = $baseUrl;
		$this->logger = new NullLogger;
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get info for a specific tool.
	 *
	 * @param string $name Name of the tool
	 * @return array
	 */
	public function getToolByName( $name ) {
		$escName = urlencode( $name );
		$req = "{$this->baseUrl}/api/tools/{$escName}/";
		$resp = $this->requestFactory->get( $req, [], __METHOD__ );
		if ( $resp === null ) {
			// FIXME: what should happen here?
			return [ 'error' => 'Got a null response so BOOM!' ];
		}
		return json_decode( $resp, true );
	}
}
