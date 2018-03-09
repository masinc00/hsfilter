<template>
    <div id="Filterbar">
        <input id="Filterbar-input" type="text" v-on:keyup="onKeyup" placeholder="検索文字列を入力してください">
        <div id="Filterbar-loading" v-bind:class="loading_class">
            <img src="loading.gif">
        </div>
        <div id="Filterbar-collectible-box">
        <label id="Filterbar-collectible-label" for="Filterbar-collectible-check"> コレクション可能カードのみ:</label>
        <input type="checkbox" id="Filterbar-collectible-check" v-model="isCollectible">
        </div>        
    </div>
</template>

<script>
Window._ = Window._ || require('lodash');
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
            isCollectible : true,
        }
    },
    methods :{

        onKeyup: async function(e){
            // console.log(e)
            await this.getApi(e.target.value)
            
            this.oldText = e.target.value;
                            
        },

        //Apiから必要なjsonファイルを取得する
        getApi: _.debounce(async function (value) {
            //準備処理
            // if (this.oldText === value)
                // return
            //前のリクエストが継続している場合終了する
            if (this.filterResponse)
                this.filterResponse.abort()
            //console.log(e.target.value + " - " + this.oldText)

            //ローディングイメージを隠さない
            this.loading_class.hide = false
            
            //実際の処理
            const params = this.convertInputData(value)
            if (params.length === 0){
                return;
            }
  
            let pstr = _.map(params, (v,k) => `${k}=${v}`).join("&")
            //pstr = encodeURIComponent(pstr);
            console.log(pstr)
            this.filterResponse = await axios.get('/api/v2?' + pstr)                

            this.filterResult = this.filterResponse.data;
            // console.log(this.filterResult);
            //終了処理
            this.filterResponse = null
            this.loading_class.hide = true

            //親にデータを取得したことを通知
            // console.log("filterbar", this.filterResult)
            this.$emit("onGetApi", this.filterResult)           
            //console.log(this.result)
            }, 300),

        //特殊な入力データを加工する
        convertInputData: function (value){
            let result = {}
            result.collectible = Number(this.isCollectible);
            // stats 5/3/5のような形式
            const stats = value.match(/([\d]+|\*)\/(\d+|\*)\/([\d\*]+|\*)/)
            if (stats){
                console.log(stats)
                Object.assign(result, {
                    cost : stats[1],
                    attack : stats[2],
                    health : stats[3]
                })
            }
            //不明な形式は名前に突っ込んでおく
            else{
                result.name = value;
            }
            return result
        }
    },
}
</script>

<style lang="scss">
    #Filterbar{
        display: flex;
        width: 100%;
        height: 1.2em;
    }
    
    #Filterbar-input {
        width: 70%;
        height: 100%;
        border-radius: 2px;
    }

    #Filterbar-loading.hide {
        display: none;
    }

    #Filterbar-collectible-box{
        position: relative;
    }
    #Filterbar-collectible-label{
        font-size:90%;
    }
    #Filterbar-collectible-check{
        display: inline;
        vertical-align: bottom;
    }
</style>