//キャンセル機構を備えた非同期のxhrをシンプルに扱うためのクラス

class AsyncXhr{

    constructor(){
        this.request = new XMLHttpRequest();
    }

    get(url) {
        const request = this.request; //Promise のthisから読み込むために必要
        return new Promise((resolve, reject) => {
                            this.request.open('GET', url);
            this.request.onload = function () {
                if (request.status === 200) {
                    resolve(request.response);
                } else {
                    reject(Error('didn\'t load successfully; error code:' + request.statusText));
                }
            };
            this.request.onerror = function () {
                reject(Error('There was a network error.'));
            };
            this.request.send();
        });
    }
}