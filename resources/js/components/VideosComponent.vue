<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">
                        <i class="fab fa-youtube" id="video"></i> Videos del Docente
                    </h3>
                </div>
            </div>
            <v-card>
                <v-card-title>
                    <div class="flex-grow-1"></div>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-3 col-md-3">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <b-embed type="iframe" aspect="16by9" v-bind:src="videourl" allowfullscreen id="video"></b-embed>
                    </div>
                </div>
                <v-data-table :headers="headers" :items="videos" :search="search" class="elevation-1">
                    <template v-slot:item.acciones="{item}">
                            <v-btn class="mx-2" @click="getUrl(item.webvideo)" :href="'#video'" fab dark small color="teal">
                                <v-icon dark>mdi-play</v-icon>
                            </v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
        <footer class="footer">soporte@prolipa.com.ec</footer>
    </div>

    <v-dialog v-model="dialog2" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <h4>
                    <v-banner two-line>
                        <v-avatar slot="icon" color="warning accent-4" size="40">
                            <v-icon icon="mdi-lock" color="white">
                                mdi-lock
                            </v-icon>
                        </v-avatar>
                        &nbsp;<b>Plantalla de Bloqueo</b>
                    </v-banner>
                </h4>
            </v-card-title>
            <v-card-text class="center">
                <h5><b>Su usuario no se encuentra habilitado para el periodo lectivo.</b></h5>
            </v-card-text>
            <hr>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text href="../">Salir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>
import io from 'socket.io-client'
export default {
    data: function () {
        return {
            videos: [],
            videourl: "https://www.youtube.com/embed/DUWPEW63a4c",
            search: '',
            headers: [{
                    value: 'nombrevideo',
                    text: 'Nombre'
                },
                {
                    value: 'descripcionvideo',
                    text: 'Descripción'
                },
                {
                    value: 'nombreasignatura',
                    text: 'Asignatura'
                },
                {
                    text: 'Video',
                    value: 'acciones'
                },
            ],
            dialog2: false,
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    mounted: function mounted() {
        this.datosUsuario();
        new ModalVideo('.js-modal-video');
    },
    methods: {
        datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.usuario = response.data;
                    var respuesta = response.data[0];
                    me.usuario = response.data;
                    me.getVideos(respuesta);
                    if (me.usuario.length > 0) {
                        me.dialog2 = false;
                    } else {
                        me.dialog2 = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getVideos(usuario) {
            let me = this;
            var hoy = new Date();
            var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
            var url = "./video";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.videos = response.data;
                    me.totalRows = response.data.length;
                })
                .catch(function (error) {
                    me.socket.emit('erroresVideo', {
                        idusuario: usuario.idusuario,
                        nombres: usuario.nombres,
                        apellidos: usuario.apellidos,
                        email: usuario.email,
                        institucion: usuario.nombreInstitucion,
                        fecha: fecha + ' ' + hora,
                        error: error
                    });
                });
        },
        print() {
            this.$htmlToPaper('printMe');
        },
        getUrl(url) {
            this.videourl = url;
        },
    }
};
</script>
