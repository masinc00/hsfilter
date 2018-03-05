<template>
    <div class="FilterResultDetails" v-on:click="onClick">
        {{ value.name_enus }}
        <table v-bind:class="table_class">
            <tr v-for="(item, key) in value" >
                <th>{{ key }}</th>
                <td>{{ item }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        name: "FilterResultDetails",
        props: ["value"],
        data: function(){
            return {
                table_class: {
                    FilterResultDetailsTable: true,
                    hide : true
                }
            }
        },

        methods: {
            onClick: function (e){
                this.table_class.hide =  !this.table_class.hide
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