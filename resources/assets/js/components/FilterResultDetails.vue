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
    .hide{
        display: none;
    }
</style>