<template>
    <div id="App">
        <div id="filter-bar">
            <input type="text" v-on:keyup="onKeyup">
            <div id="filter-bar-loading" v-bind:class="loading_class">
                <img src="loading.gif">
            </div>
        </div>
        <FilterResult v-model="filterResult"/>
    </div>
</template>

<script>
    Window._ = Window._ || require('lodash') 
    
    import FilterResult from "./FilterResult"

    export default {
        name : "App",
        data : function (){
            return {
                filterResponse : null,
                filterResult: [],
                oldText : "",
                loading_class : {
                    hide : true,
                },
                
            }
        },
        methods :{
            onKeyup: async function(e){
                // console.log(response)
                await this.getApi(e.target.value)

                this.oldText = e.target.value;
                               
            },
            getApi: _.debounce(async function (value) {

                if (this.filterResponse)
                    this.filterResponse.abort()
                //console.log(e.target.value + " - " + this.oldText)
                this.loading_class.hide = false
                this.filterResponse = await axios.get('/api/v2?name=' + value)
                this.filterResult = this.filterResponse.data;
                this.filterResponse = null
                this.loading_class.hide = true
                //console.log(this.result)
                }, 300)
        },
        components: {
            FilterResult
        }
    }
</script>

<style>
    #filter-bar{
        display: flex;
    }
    
    #filter-bar-loading.hide {
        display: none;
    }
</style>