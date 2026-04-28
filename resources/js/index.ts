
import { AbstractServiceProvider } from 'virtual:AddOnAbstract';
import { defineAsyncComponent, Component } from 'vue';
import ApiReceiveTrigger from './components/ApiReceiveTrigger';

export default class FrontServiceProvider extends AbstractServiceProvider
{
    /**
     * Returns the addon icon (small image) used for visual identification 
     * in the user interface.
     * @returns string
     */
    getIco():string
    {
        return new URL('./assets/api-receive-trigger-logo.png', import.meta.url).href
    }

    /**
     * Returns a larger collage image representing the addon, typically 
     * used in previews or detailed views.
     * @returns string
     */
    getCollage():string
    {
        return new URL('./assets/api-receive-trigger-collage.png', import.meta.url).href
    }

    /**
     * Returns configuration for workflow graph blocks, including structure, 
     * behavior, and interaction rules.
     * @returns string[]
     */
    getWorkflowBlocks():string[]
    {
        return [
            ApiReceiveTrigger,
        ]
    }

    /**
     * Returns a Vue.js component representing the configuration form with 
     * available addon options.
     * @returns Component
     */
    getOptionsForm(): Component
    {
        return defineAsyncComponent(() =>
            import('./views/OptionsForm.vue')
        )
    }

}