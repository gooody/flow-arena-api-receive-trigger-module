<?php
namespace FlowArenaModule\ApiReceiveTrigger\Handlers;

use App\Common\AddOns\Contracts\HandlerInterface;
use App\Common\AddOns\Contracts\StepProcessingContextInterface;

use Illuminate\Http\Request;

/**
 * Handles incoming API requests (GET, POST, PUT, DELETE, PATCH) and 
 * prepares them for further processing within the system.
 * 
 * Class ApiReceiveTriggerHandler
 * @package FlowArenaModule\ApiReceiveTrigger
 * @property Request $request
 */
class ApiReceiveTriggerHandler implements HandlerInterface
{
    private $options = [];

    function __construct(
        private Request $request
    )
    {}

    public function execute(StepProcessingContextInterface $stepProcessingContext) :void
    {
        $this->options = $stepProcessingContext->getOptions();
        
        $stepProcessingContext->busPublish([
            'body' => $this->getBodyData(),
            'query' => $this->getQueryData(),
            'path' => $this->getPathData(),
        ]);
    }


    private function getBodyData()
    {
        if (!isset($this->options['body'])) {
            return [];
        }
            
        $validateArr = [];
        foreach ($this->options['body'] AS $bodyItem){
            if (empty($bodyItem['name'])) {
                continue;
            }

            $validateArr[$bodyItem['name']] = 
                $bodyItem['required'] 
                    ? 'required|string'
                    : 'string';
        }

        return $this->request->validate($validateArr);
    }

    private function getQueryData()
    {
        if (!isset($this->options['query'])) {
            return [];
        }

        $validateArr = [];
        foreach ($this->options['query'] AS $queryItem){
            if (empty($queryItem['name'])) {
                continue;
            }

            $validateArr[$queryItem['name']] = 
                $queryItem['required'] 
                    ? 'required|string'
                    : 'string';
        }

        return $this->request->validate($validateArr);
    }

    private function getPathData()
    {
        $paths = [];
        foreach ($this->options['path']??[] AS $queryItem) {
            $name = $queryItem['name'];
            $paths[$name] = $this->request->route($name);
        }
       
        return $paths;
    }


}