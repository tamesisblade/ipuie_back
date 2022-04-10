<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3 mt-3" v-for="item in list">
                <router-link class="card-header" :to="{name: 'post', params: {slug: item.slug}}" v-text="item.title"></router-link>

                <div class="card-body">
                    <p class="card-text" v-text="item.nombres"></p>
                </div>
            </div>

            <infinite-loading @distance="1" @infinite="infiniteHandler">
                <div slot="no-more">--</div>
                <div slot="spinner">Cargando...</div>
                <div slot="no-results">Sin resultados</div>
            </infinite-loading>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                list: [],
                page: 0
            }
        },
        methods: {
            infiniteHandler($state) {
                this.page++
                let url = 'http://50.30.36.168:8000/api/infante?page='+this.page

                axios.get(url)
                .then(response => {
                    let posts = response.data.infante.data
                    console.log(posts)
                    if(posts.length){
                        this.list = this.list.concat(posts)
                        $state.loaded()
                    }else{
                        $state.complete()
                    }
                })
            }
        }
    }
</script>
