

import { AbstractFlowElements } from 'virtual:AddOnAbstract';
import { WorkflowTypes } from 'virtual:AddOnMiscellaneous';
import img from "../assets/api-receive-trigger-logo.png";


export default class ApiReceiveTrigger extends AbstractFlowElements implements WorkflowTypes.FlowArenaMicroservicesInterface 
{
    /**
     * Unique node shape identifier used by @antv/x6 to define 
     * visual representation and behavior.
     */
    static readonly nodeShape = 'ApiReceiveTrigger';

    /**
     * Defines the default number of expansion points available 
     * when the node is initialized.
     */
    readonly defaultNumberOfExpansionPoint:number = 1;
    
    /**
     * Specifies the maximum allowed number of expansion points 
     * that can be added to the node.
     */
    readonly maximumNumberOfExpansionPoint:number = 1;

    /**
     * Specifies the minimum required number of expansion points 
     * that must exist on the node.
     */
    readonly minimumNumberOfExpansionPoint:number = 1;
    
    /**
     * List of required keys that cannot be removed to preserve 
     * essential node configuration.
     */
    protected keyDeletionForbidden = ['if'];

    /**
     * Returns the display label used to identify the node within 
     * the UI or workflow editor.
     * @returns string
     */
    protected getLabel():string {
        return 'Api Receive';
    }

    /**
     * Returns the node schema describing its structure, appearance, 
     * and interaction rules in @antv/x6.
     * @returns object
     */
    protected getNodeDefinition() :object
    {
        return {
            inherit: 'rect',
            width: 200,
            height: 60,
            markup: [
                { tagName: 'rect', selector: 'body', },
                { tagName: 'circle', selector: 'iconBg', },
                { tagName: 'image', selector: 'icon', },
                { tagName: 'text', selector: 'label', },
            ],
            attrs: {
                body: {
                    rx: 10,
                    ry: 10,
                    fill: '#1dd5ee', 
                    stroke: '#0c8392',
                    strokeWidth: 2,
                },
                iconBg: {
                    cx: 2,
                    cy: 2,
                    r: 18,
                    fill: '#f0f0f0', 
                    stroke: '#979797',
                    strokeWidth: 2,
                },
                icon: {
                    'xlink:href': img,
                    width: 60,
                    height: 60,
                    x: -27,
                    y: -25,
                },
                label: {
                    text: this.getLabel(),
                    fontSize: 14,
                    fill: '#000',
                    refX: '50%',
                    refY: '50%',
                    textAnchor: 'middle',
                    textVerticalAnchor: 'middle',
                },
            },
        };
    }
}

