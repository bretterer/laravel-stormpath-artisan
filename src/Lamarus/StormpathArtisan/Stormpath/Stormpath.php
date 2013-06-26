<?php namespace Lamarus\StormpathArtisan\Stormpath;


use Guzzle\Http\Client;
use Config;

abstract class Stormpath {

	/**
     * CURL Client
     *
     * @var object
     */
	protected $client;

	/**
     * Tenants
     *
     * @var object
     */
	protected $tenants;

	/**
     * Constructor
     *
     */
    public function __construct()
    {
        
        if(Config::get('stormpath-artisan::id') == '' || Config::get('stormpath-artisan:secret') == '') {
        } else {      
            $this->client = new Client('https://api.stormpath.com/v1',array(
                'request.options' => array(
                    'auth'    => array(Config::get('stormpath-artisan::id'), Config::get('stormpath-artisan::secret'), 'Basic')
                )
            ));

            $this->tenants = $this->client->get('tenants/current')->send()->json();
        }
    }


}