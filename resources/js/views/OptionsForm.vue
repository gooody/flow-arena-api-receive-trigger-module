<script setup lang="ts">
/**
 * ----------------------------------------------------------
 * Imports
 * ----------------------------------------------------------
 */
import { ref, onMounted, } from 'vue';

/**
 * ----------------------------------------------------------
 * Options
 * ----------------------------------------------------------
 */
defineOptions({
  name: 'OptionsForm'
});

/**
 * ----------------------------------------------------------
 * Props / Emits
 * ----------------------------------------------------------
 */
interface Props {
    addOnUses:any,
}
 
const props = defineProps<Props>();

/**
 * ----------------------------------------------------------
 * State (refs/reactive)
 * ----------------------------------------------------------
 */

const form = ref({
    method: '',
    path:{},
    query:{},
    body:{},    
});


const methodOptions = [
    {value: 'get', text: 'GET'},
    {value: 'post', text: 'POST'},
    {value: 'put', text: 'PUT'},
    {value: 'delete', text: 'DELETE'},
    {value: 'patch', text: 'PATCH'},
]


/**
 * ----------------------------------------------------------
 * Methods
 * ----------------------------------------------------------
 */

const onVueMounted = () => {
    props.addOnUses.getOptions(form.value)
}

const getParamTypes = () => ['path', 'query', 'body'];

const getUrl = (short:boolean = false) => {
    
    let paths = [];
    for (let key in form.value.path) {
        const path = form.value.path[key]
        paths.push(`{${path.name||''}}`)
    }

    let querys = [];
    if (!short) {
        for (let key in form.value.query) {
            const query = form.value.query[key];
            querys.push(`${query.name||''}=test`)
        }
    }
    
    const uniqid:string = props.addOnUses.getAutomation().uniqid

    let urlArr = [
        ...(short ? [] : [
                import.meta.env.VITE_AUTOMATION_URL,
                import.meta.env.VITE_AUTOMATION_URL_PREFIX,
            ]),
        uniqid,
        ...paths,
        querys.length ? `?${querys.join('&')}` : '',
    ]
    return `${urlArr.join('/')}`
}

const getMethodUpperCase = () => form.value?.method?.toUpperCase();


const addParam = (paramType:string) => {
    const key = `param-${Date.now()}`;
    form.value[paramType][key] = {  }
}

const changeParamField = (paramType:string, paramKey:string, field:string, value:string) => {
    form.value[paramType][paramKey][field] = value;

    if('path' ==  paramType && 'name' == field) {
        props.addOnUses.updateOption( ['url'], getUrl(true) );
    }
    
    props.addOnUses.updateOption(
        [paramType, paramKey, field],
        value
    );
}

const changeField = (key:string[], value:string) => {

    props.addOnUses.updateOption( key, `${value}`);
}

const deleteParam = (paramType:string, paramKey:string) => {
    if ( form.value[paramType][paramKey] ) {
        delete form.value[paramType][paramKey];
    }

    props.addOnUses.deleteOption( [paramType, paramKey, 'name'] );
    props.addOnUses.deleteOption( [paramType, paramKey, 'required'] );
}


/**
 * ----------------------------------------------------------
 * Lifecycle
 * ----------------------------------------------------------
 */
onMounted(onVueMounted)


</script>

<template>
    <b-col>
        <b-row>
            <b-col md="12"  class="mb-2">
                <BAlert :model-value="true" variant="info" >
                    <h6>
                        <BBadge>
                            {{getMethodUpperCase()}}:
                        </BBadge>  
                        <span class="url-alert">
                            {{ getUrl() }}
                        </span>                        
                    </h6>
                </BAlert>
                
            </b-col>
        </b-row>

        <b-row>
            <b-col md="12"  class="mb-2">
                <BFormSelect
                    v-model="form.method"
                    :options="methodOptions"
                    @change="changeField(['method'], form.method)"
                />
            </b-col>
        </b-row>
        
        <b-row class="mt-2">
            <b-col 
                v-for="paramType in getParamTypes()"
            >
                <b-row class="mt-2">
                    <b-col md="10">
                        <h5 v-if="'path' == paramType"> Path params</h5>
                        <h5 v-if="'query' == paramType">Query params </h5>
                        <h5 v-if="'body' == paramType"> Body params </h5>
                    </b-col>
                    <b-col md="2" class="">
                        <BButton @click="addParam(paramType)" variant="success" > 
                            <fa icon="circle-plus" /> 
                        </BButton>                        
                    </b-col>
                </b-row>

                <b-row v-for="(paramData, paramKey) in form[paramType]" class="mt-2">
                    <b-col md="10">
                        <BFormInput 
                            v-model="paramData.name" 
                            @change="changeParamField(paramType, paramKey, 'name', paramData.name)"
                            placeholder="Enter param name" 
                        />
                    </b-col>

                    <b-col md="2" class="d-grid gap-2">
                        <BButton 
                            @click="deleteParam(paramType, paramKey)"
                            variant="outline-warning"
                        >
                            <fa icon="circle-minus" /> 
                        </BButton>
                    </b-col>

                    <b-col md="10">
                        <BFormCheckbox
                                v-if="'path' !== paramType"
                                v-model="paramData.required"
                                value="1"
                                unchecked-value="0"
                                switch
                                @update:model-value="changeParamField(paramType, paramKey, 'required', $event)"
                            >
                                required
                            </BFormCheckbox>
                    </b-col>      
                    <hr/>              
                </b-row>                
            </b-col>
        </b-row>
    </b-col>
</template>
<style lang="scss">
.url-alert {
    margin: 0px 0 0 10px;
}
</style>




