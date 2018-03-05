<template>
    <div class="FilterResultDetails" v-on:click="onClick">
        {{ value.name_enus }}
        <table v-bind:class="visible">
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
                visible: "hide"
            }
        },

        methods: {
            onClick: function (e){
                if (this.visible === "hide"){
                    this.visible = "";
                } 
                else {
                    this.visible = "hide"
                }
            }
        }   
    }
</script>

<style>
    .hide{
        display: none;
    }
</style>