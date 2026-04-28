<?php

namespace FlowArenaModule\ApiReceiveTrigger;

use App\Common\AddOns\Contracts\ModuleInterface;
use FlowArenaModule\AddonCommon\Abstracts\AbstractModule;
use Illuminate\Support\Facades\Route;
use FlowArenaModule\ApiReceiveTrigger\Http\Controllers\IndexController;

use Modules\WorkflowEngine\Services\BootAddonService;

class ApiReceiveTriggerModule  extends AbstractModule implements ModuleInterface
{
    /**
     * User-facing addon name displayed in UI and external interfaces.
     * @type string
     */
    protected string $title = "Api Receive Trigger";
   
    /**
     * Unique internal addon alias, used system-wide; must not contain spaces.
     * @type string
     */
    protected string $alias = 'ApiReceiveTrigger';

    /**
     * Programming language in which the addon is implemented and executed.
     * @type string
     */
    protected string $language = "php";
    
    /**
     * Human-readable description explaining what the addon does and its purpose.
     * @type string
     */
    protected string $description = "The 'Api Receive Trigger' addon enables your flow to accept incoming HTTP requests, including GET, POST, PUT, DELETE, and PATCH methods. It acts as an entry point for external systems, allowing you to capture, validate, and forward request data to the next blocks for processing. Ideal as the first step in integrations, webhooks, and real-time data pipelines, it ensures flexible and reliable communication between services.";
 
    /**
     * Defines addon category/usage context (e.g. decision, trigger, database, termination).
     * @type string
     */
    protected string $blockType = "trigger";
    
    /**
     * Path to the TypeScript file defining frontend integration logic.
     * @type string
     */
    protected string $frontServiceProvider = "../resources/js/index.ts";
    
    /**
     * Fully qualified class responsible for processing addon logic.
     * @type string
     */
    protected string $handler = \FlowArenaModule\ApiReceiveTrigger\Handlers\ApiReceiveTriggerHandler::class;
    
    /**
     * Returns absolute path to the frontend TypeScript service provider file or null if not set.
     * @return string|null
     */    
    public function getFrontServiceProvider():string|null 
    {
        return $this->relativePath(__DIR__, $this->frontServiceProvider);
    }

    /**
     * Initializes addon runtime logic, automatically registering and extending Laravel capabilities based on addon requirements and configuration.
     */
    public function boot(BootAddonService $bootAddonService):void
    {
        $bootAddonCollection = $bootAddonService->getAddonAutomations();
        $urlPrefix = config('automation.urlPrefix');
        Route::middleware([ /* 'custom.middleware'  */  ])
            ->prefix( $urlPrefix )
            //->name('automation.api.')
            ->group(function () use ($bootAddonCollection)  {
                foreach($bootAddonCollection AS $bootAddonItem) {
                    $initialAddOnOption = $bootAddonItem->getInitialAutomationAddOnOptions();        
                    if (
                        !empty($initialAddOnOption) 
                        && !empty($initialAddOnOption['method']) 
                        && !empty($initialAddOnOption['url']) 
                    ) {
                        Route::match(
                            [$initialAddOnOption['method']], 
                            $initialAddOnOption['url'], 
                            [IndexController::class, 'index']
                        )->defaults('_$automationId', $bootAddonItem->getId())
                        ->name('automation.api.'. $bootAddonItem->getId());
                    }
                }              
            });
    }    

}
