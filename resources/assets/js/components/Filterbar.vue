<template>
    <div id="Filterbar">
        <input type="text" v-on:keyup="onKeyup">
        <div id="Filterbar-loading" v-bind:class="loading_class">
            <img src="loading.gif">
        </div>
    </div>
</template>

<script>
export default {
    name: "Filterbar",
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
            if (this.oldText === value)
                return
            if (this.filterResponse)
                this.filterResponse.abort()
            //console.log(e.target.value + " - " + this.oldText)
            this.loading_class.hide = false
            this.filterResponse = await axios.get('/api/v2?name=' + value)
            this.filterResult = this.filterResponse.data;
            this.filterResponse = null
            this.loading_class.hide = true

            // console.log("filterbar", this.filterResult)
            this.$emit("onGetApi", this.filterResult)           
            //console.log(this.result)
            }, 300)
    },
}
</script>

<style>
    #Filterbar{
        display: flex;
    }
    
    #Filterbar-loading.hide {
        display: none;
    }
</style>