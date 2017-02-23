<?php
namespace Netsensia\Authentication\Api\Client\Common\Guzzle;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Guzzle Client with our own defaults.
 *
 * Class Client
 */
class Client extends GuzzleClient {
    
    public function __construct(array $config = []) {

        parent::__construct( $config );
    }

} // class
