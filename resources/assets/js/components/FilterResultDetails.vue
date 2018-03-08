<template>
    <div class="FilterResultDetails" v-on:click="onClick">
        {{ value.name_enUS }}
        <img v-bind:src="imageUrl">
        <table v-bind:class="table_class">
            <tr v-for="(item, key) in value" >
                <th>{{ key }}</th>
                <td>{{ item }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    import HearthHeadJson from "../HearthHeadJson"
    export default {
        name: "FilterResultDetails",
        props: ["value"],
        data: function(){
            return {
                table_class: {
                    FilterResultDetailsTable: true,
                    hide : true,
                    //image_url : (async () =>{return await this.getImageUrl()})(),
                }
            }
        },

        methods: {
            onClick: function (e){
                this.table_class.hide =  !this.table_class.hide
            },
        },
        computed: {
            hhJson: function (){
                return new HearthHeadJson(this.value.name_enUS)
            }
        },

        asyncComputed: {
            imageUrl: {
                lazy: true,
                get: async function(){
                return await this.hhJson.getImageUrl(false)
                // return result;
                }
            }
            
        }
    }
</script>

<style>
    .FilterResultDetailsTable{
        border: solid 1px #99F; 
    }

    .FilterResultDetailsTable>tr, .FilterResultDetailsTable>tr>th, .FilterResultDetailsTable>tr>td{
        border: solid 1px #ddd;
    }
    .FilterResultDetailsTable.hide{
        display: none;
    }
</style>