<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class TryCatchErrorLogger
{
    /**
     * @var UserRepositoryInterface
     */
    protected Log $logger;

    public function __construct(Log $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * The errorLog() function will serve the purpose of
     * logging only errors into our laravel.log file
     * 
     * return
     */
    public function errorLog(\Exception $e)
    {
        Log::error($e->getFile().' '.$e->getLine().': '.$e->getMessage());
    }
    
    /**
     * The infoLog() function will serve the purpose of
     * logging only errors into our laravel.log file
     * 
     * return
     */
    public function infoLog(\Exception $e)
    {
        Log::info($e->getFile().' '.$e->getLine().': '.$e->getMessage());
    }
    
    /**
     * The warningLog() function will serve the purpose of
     * logging only errors into our laravel.log file
     * 
     * return
     */
    public function warningLog(\Exception $e)
    {
        Log::warning($e->getFile().' '.$e->getLine().': '.$e->getMessage());
    }
}