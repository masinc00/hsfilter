const JSON_URL = 'https://hearthstone.services.zam.com/v1/card/bySlug/'
const MEDIA_URL_PREFIX = 'https://media.services.zam.com/v1/media/byName'
export default class HearthHeadJson{
    constructor(card_name){
        this.card_name = card_name
        this.json = null
        //await load_json()
    }

    async loadJson(){
        const value = this.card_name.toLowerCase().replace(' ', '-');
        const res = await axios.get(JSON_URL + value)
        this.json = res.data
    }

    //
    async getImageUrl(is_golden = false){
        if (!this.json){
            await this.loadJson()
        }
        // console.log(this.json)
        
        // jsonが返ってこない場合
        if (!this.json || !this.json.media || !this.json.media === []){
            return;
        }
        const normal_image_type = 'CARD_IMAGE'
        const golden_image_type = 'GOLDEN_CARD_IMAGE'
        for (const media of this.json.media){
            if ((is_golden && media.type === golden_image_type) ||
                (!is_golden && media.type === normal_image_type) ){
                return  MEDIA_URL_PREFIX + media.url;
            }
        }
    }

    async getSoundUrls(){
        if (!this.json){
            await this.loadJson();
        }
        const result = {}
        for (const media of this.json){
            if (media.type.indexOf('SOUND') !== -1){
               result[media.type] = MEDIA_URL_PREFIX + media.url; 
            }
        }
        return result;
    }

}