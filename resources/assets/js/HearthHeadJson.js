const JSON_URL = 'https://hearthstone.services.zam.com/v1/card/bySlug/'
const MEDIA_URL_PREFIX = 'https://media.services.zam.com/v1/media/byName'
const IMAGE_PREFIX = 'https://media.services.zam.com/v1/media/byName/hs/cards/enus/'

const NORMAL_IMAGE_TYPE = 'CARD_IMAGE'
const GOLDEN_IMAGE_TYPE = 'GOLDEN_CARD_IMAGE'
export default class HearthHeadJson {
    constructor(card_name, id = null) {
        this.card_name = card_name
        this.json = null
        this.id = id;
        //await load_json()
    }

    //対象のJsonを読み込む
    async loadJson() {
        const value = this.card_name.toLowerCase().replace(' ', '-');
        const res = await axios.get(JSON_URL + value)
        this.json = res.data
    }

    //idを使用して簡易的な画像Urlを取得する
    async getImageUrlFromId(is_golden = false) {
        if (!this.id)
            return
        const result = (!is_golden)
            ?   `${IMAGE_PREFIX}${this.id}.png` 
            :   `${IMAGE_PREFIX}animated/${this.id}_premium.gif`
        //画像が存在するかheadで確認する
        const head = await axios.head(result)
        if (head.statusText !== 'OK')
            return
        return result
    }

    //jsonから画像urlを読み込む
    async getImageUrl(is_golden = false) {
        if (!this.json) {
            await this.loadJson()
        }
        // console.log(this.json)

        // jsonが返ってこない場合
        if (!this.json || !this.json.media || !this.json.media === []) {
            return;
        }

        //jsonからURLを読み込む
        for (const media of this.json.media) {
            if ((is_golden && media.type === GOLDEN_IMAGE_TYPE) ||
                (!is_golden && media.type === NORMAL_IMAGE_TYPE)) {
                return MEDIA_URL_PREFIX + media.url;
            }
        }
    }

    //jsonからサウンドを読み込み
    async getSoundUrls() {
        if (!this.json) {
            await this.loadJson();
        }
        const result = {}
        for (const media of this.json) {
            if (media.type.indexOf('SOUND') !== -1) {
                result[media.type] = MEDIA_URL_PREFIX + media.url;
            }
        }
        return result;
    }

}