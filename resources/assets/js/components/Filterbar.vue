<template>
    <div id="Filterbar">
        <input id="Filterbar-input" type="text" v-on:keyup="onKeyup">
        <div id="Filterbar-loading" v-bind:class="loading_class">
            <img src="loading.gif">
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
            // console.log(params)
            if (Object.keys(params).length) {
                // console.log(params);
                let pstr = _.map(params, (v,k) => `${k}=${v}`).join("&")
                //pstr = encodeURIComponent(pstr);
                console.log(pstr)
                this.filterResponse = await axios.get('/api/v2?' + pstr)                
            }
            else{
                this.filterResponse = await axios.get('/api/v2?name=' + value)
            }
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
            
            // 5/3/5のような形式
            const stats = value.match(/([\d]+|\*)\/(\d+|\*)\/([\d\*]+|\*)/)
            if (stats){
                console.log(stats)
                Object.assign(result, {
                    cost : stats[1],
                    attack : stats[2],
                    health : stats[3]
                })
            }

            return result
        }
    },
}
</script>

<style>
    #Filterbar{
        display: flex;
        width: 100%;
        height: 1.2em;
    }
    
    #Filterbar-input {
        width: 70%;
        height: 100%;
        border-radius: 3px;
    }

    #Filterbar-loading.hide {
        display: none;
    }
</style>