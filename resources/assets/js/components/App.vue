<template>
    <div id="App">
        <Filterbar v-on:onGetApi="onGetApi" />
        <FilterResult v-model="filterResult"/>
    </div>
</template>

<script>
    import Filterbar from "./Filterbar"
    import FilterResult from "./FilterResult"

    export default {
        name : "App",
        data : function (){
                return {
                    filterResult : [],
                }
        },
        methods :{
            onGetApi : function (result){
                // console.log("App", result)
                this.filterResult = result
            }
        },
        components: {
            Filterbar,
            FilterResult
        }
    }
</script>

<style>

</style>