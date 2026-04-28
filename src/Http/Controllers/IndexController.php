<?php

namespace FlowArenaModule\ApiReceiveTrigger\Http\Controllers;

use App\Common\Base\Http\Controllers\Controller;
use Modules\WorkflowEngine\Services\Workflow\WorkflowRunner;

use Illuminate\Http\Request;


class IndexController extends Controller
{
    /**
    * Automation processing.
    * @method ALL
    * @routeName automation.api.*
    */
    public function index(Request $request, WorkflowRunner $workflowRunner  )
    {
        try {
           $workflowRunner->executeAutomation( $request->route('_$automationId') );
             return $this->successJsonResponse( ['ok' => true] );
        } catch (\Throwable $exception) {
            $response = $this->failureJsonResponse($exception->getMessage());
        }

        return $response;
    }
}