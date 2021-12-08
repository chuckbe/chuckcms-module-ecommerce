<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Ingenico;

use Ingenico\Connect\Sdk

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;
use Str;
use ChuckEcommerce;
use ChuckSite;

class IngenicoController extends Controller
{
    private $apiKeyId;
    private $apiSecret;
    private $apiEndpoint;
    private $integrator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiKeyId $apiKeyId, ApiSecret $apiSecret, ApiEndpoint $apiEndpoint, Integrator $integrator)
    {
        $communicatorConfiguration = new CommunicatorConfiguration($apiKeyId, $apiSecret, $apiEndpoint, $integrator);
        $connection = new DefaultConnection();
        $communicator = new Communicator($connection, $communicatorConfiguration);
        $client = new Client($communicator);
    }

    public function index()
    {
        
    }
}
