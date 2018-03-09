<template>
    <div class="FilterResultDetails" >
        <div class="FilterResultSimpleDetail" v-on:click="onClick">{{ value.name_enUS }}</div>
        <transition name="fade-details">
            <div v-if="!detail_class.hide" v-bind:class="detail_class">
                <div class="FilterResultsImages">
                    <img v-bind:class="nomalImagesClass" v-bind:src="imageUrl">
                    <img v-bind:class="goldenImagesClass" v-bind:src="ImageUrlGolden">
                </div>
                <table class="FilterResultDetailsTable">
                    <tr v-for="(item, key) in value" >
                        <th>{{ key }}</th>
                        <td>{{ item }}</td>
                    </tr>
                </table>
            </div>        
        </transition>
    </div>
</template>

<script>
    import HearthHeadJson from "../HearthHeadJson"
    export default {
        name: "FilterResultDetails",
        props: ["value"],
        data: function(){
            return {
                detail_class: {
                    FilterResultDetailsDetail: true,
                    hide : true,
                    //image_url : (async () =>{return await this.getImageUrl()})(),
                },

                nomalImagesClass: {
                    hide : this.hasImageUrl
                },

                goldenImagesClass: {
                    hide : this.hasImageUrlGolden
                }
            }
        },

        methods: {
            onClick: function (e){
                this.detail_class.hide =  !this.detail_class.hide
            },
        },
        computed: {
            hhJson: function (){
                return new HearthHeadJson(this.value.name_enUS, this.value.id)
            },

            hasImageUrl : function(){
                return (bool)(this.imageUrl)
            },

            hasImageUrlGolden : function(){
                return (bool)(this.ImageUrlGolden)
            }
        },

        asyncComputed: {
            imageUrl: {
                lazy: true,
                get: async function(){
                    return this.hhJson.getImageUrlFromId(false)
                }
            },

            ImageUrlGolden: {
                lazy: true,
                get: async function(){
                    return this.hhJson.getImageUrlFromId(true)
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
    .FilterResultDetailsDetail.hide,
    .FilterResultsImages>img.hide{
        display: none;
    }

    .FilterResultsImages{
        display: flex;
    }

    .fade-details-enter-active, .fade-details-leave-active {
      transition: opacity .36s;
    }
    .fade-details-enter, .fade-details-leave-to /* .fade-leave-active below version 2.1.8 */ {
      opacity: 0;
    }
</style>