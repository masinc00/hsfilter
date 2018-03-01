((async) => {

    const sleepAsync = msec => new Promise(resolve => setTimeout(resolve, msec))

    const filterbar = new Vue({
        el: '#filter-bar',
        data: {
            text: "fire",
            json: "",
        },

        watch: {
            text: function(text) {
                this.get_api(text);
            }
        },

        methods: {
            get_api: _.debounce(function(text) {
                //console.log(this)
                const vm = this

                axios.get('api/v2?name=' + text)
                    .then(function(responce) {
                        console.log(responce)
                        vm.json = responce.data
                    })
                    .catch(function(err) {
                        //error
                    })
            }, 300)

        }
    })

    /*
        const filterbar = document.getElementById("filterbar")
        const resultlist = document.getElementById("resultlist")
        const loading = document.querySelector(".loading")

        function nowloading(enable){
            loading.style.display =  enable ? 'inline' : 'none';
        }
        
        function show_result_element(json){
            //子エレメントをすべて削除
            while (resultlist.firstChild) resultlist.removeChild(resultlist.firstChild);

            for (let key in json) {
                const value = json[key];
                const eli = document.createElement("li");
                const div = document.createElement("div");
                div.setAttribute("class", "result-item");
                div.setAttribute("data-key", key);
                div.textContent = value.name_enus;
                div.addEventListener("click", (e) =>{
                    //todo: テーブル内要素をクリックしたときにも閉じるように対応する。
                    let table = e.target.querySelector(".result-item-detail");
                    //既にtableが作成されている。
                    if (table){
                        if (table.style.display === "none"){
                            table.style.display = "block";
                        }
                        else{
                            table.style.display = "none";
                        }
                        return;
                    }
                    const key = e.target.getAttribute("data-key")
                    const value = json[key];

                    table = document.createElement("table")
                    table.setAttribute("class", "result-item-detail")
                    table.style.display = "block"
                    table.style.opacity = 1;
                    for (let json_key in value)
                    {
                        const json_value = value[json_key];
                        const tr = document.createElement("tr");
                        const th = document.createElement("th");
                        const td = document.createElement("td");

                        tr.appendChild(th);
                        tr.appendChild(td);

                        th.textContent = json_key;
                        td.textContent = json_value;

                        table.appendChild(tr);
                    }
                    e.target.appendChild(table);
                    //console.log(value);
                })
                eli.appendChild(div);
                resultlist.appendChild(eli);
            }
        }

        axhr = new AsyncXhr();
        filterbar.addEventListener("keyup", async (e) => {
            const value = e.target.value;
            await sleepAsync(300);
            if (value !== e.target.value) {
                return;
            }
            nowloading(false);
            axhr.request.abort();
            nowloading(true);
            const response = await axhr.get(`api/v2?name=${value}`)
            const json = await JSON.parse(response);
            //console.log(json)
            show_result_element(json);

            nowloading(false);
        });
    */
})();