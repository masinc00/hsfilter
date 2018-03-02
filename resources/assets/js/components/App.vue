<template>
    <div id="App">
        <div id="filter-bar">
            <input type="text" v-on:keyup="onKeyup">
            <div id="filter-bar-loading">
                <img src="loading.gif">
            </div>
        </div>
        <div id="filter-result">
            <ul>
                <li v-for="item in filterResult">
                    {{ item.name_enus }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    Window._ = Window._ || require('lodash') 
    export default {
        name : "App",
        data : function (){
            return {
                filterResponse : null,
                filterResult: [],
                oldText : "",
                
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
                this.filterResponse = await axios.get('/api/v2?name=' + value)
                this.filterResult = this.filterResponse.data;
                this.filterResponse = null
                //console.log(this.result)
                }, 300)
        }
    }
</script>

<style>
    #filter-bar{
        display: flex;
    }
</style>