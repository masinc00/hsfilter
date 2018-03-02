<template>
    <div id="FilterResult">
        <ul>
            <li v-for="item in value" v-bind:data-index="value.indexOf(item)"  v-on:click="onClick($event, item)">
                {{ item.name_enus }}
                <FilterResultDetails v-bind:value="item"/>
            </li>         
        </ul>
    </div>
</template>

<script>
    import FilterResultDetails from "./FilterResultDetails"

    export default{
        
        name : "FilterResult",
        props : ["value"],
        methods : {
            onClick : function (e, item) {
                //todo: 詳細のトグルを作成
                //console.log(item)
            }
        },
        components: {
            FilterResultDetails
        }

        
    }
</script>

<style>
</style>