<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-card-title>
                <h3 class="text-themecolor"><i class="fa fa-file"></i> Guías del Docente</h3>
                <v-spacer></v-spacer>
                <v-text-field v-model="buscar" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-layout row wrap>
                <div class="col-lg-3 col-md-6 animated  zoomInUp" v-for="dato in filteredList" :key="dato.idguia">
                    <div class="py-2">
                        <div class="contenido">
                            <img v-if="dato.webguia != null" class="card-img-top img-responsive contenido" v-bind:src="'upload/guia/'+dato.webguia+'/portada.png'" alt="Card image cap">
                            <img v-else class="card-img-top img-responsive contenido" v-bind:src="'upload/guia/portada.png'" alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title text-light">{{dato.nombreguia}}</h4>
                                <p class="card-text">{{dato.descripcionguia}}</p>
                                <div class="row col col-md-12 text-center">
                                    <div class="col col-md-4 animated flip">
                                        <b-button v-if="dato.pdfsinguia != null" v-bind:id="dato.idguia+'1'" v-bind:href="'./upload/pdfsinguia/'+dato.pdfsinguia" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                        <b-tooltip v-bind:target="dato.idguia+'1'" title="Pdf sin Guía"></b-tooltip>
                                    </div>
                                    <div class="col col-md-4 animated flip">
                                        <b-button v-if="dato.pdfconguia != null" v-bind:id="dato.idguia+'2'" v-bind:href="'./upload/pdfconguia/'+dato.pdfconguia" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                        <b-tooltip v-bind:target="dato.idguia+'2'" title="Pdf con Guía"></b-tooltip>
                                    </div>
                                    <div class="col col-md-4 animated flip">
                                        <b-button v-if="dato.guiadidactica != null" v-bind:id="dato.idguia+'3'" v-bind:href="'./upload/pdfguiadidactica/'+dato.guiadidactica" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                        <b-tooltip v-bind:target="dato.idguia+'3'" title="Guía Didáctica"></b-tooltip>
                                    </div>
                                </div>
                                <br>
                                <div class="row col col-md-12 text-center">
                                    <div class="col col-md-6 animated flip">
                                        <!-- <b-button v-if="dato.webguia != null" v-bind:id="dato.idguia+'4'" v-bind:href="'./upload/guia/'+dato.webguia+'/'" variant="outline-success"> guia Web</div></b-button>
                                        <b-tooltip v-bind:target="dato.idguia+'4'" title="guia Web"></b-tooltip> -->
                                        <!-- <a href="#modal-01" class="demo01 btn btn-info btn-rounded" @click="datoguia(dato.webguia)">guia Web</a> -->
                                    </div>
                                    <!-- <div class="col col-md-6 animated flip">
                                    <b-button v-if="dato.exeguia != null" v-bind:id="dato.idguia+'5'" v-bind:href="'./upload/guiasexe/'+dato.exeguia" variant="outline-success"><i class="fa fa-download"></i> Digital</b-button>
                                    <b-tooltip v-bind:target="dato.idguia+'5'" title="guia EXE"></b-tooltip>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-layout>
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
import io from 'socket.io-client';
export default {
    data: function () {
        return {
            guias: [],
            pagination: {},
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorCategoria: 0,
            errorMostrarMsjCategoria: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombreInstitucion',
            buscar: '',
            dialog2: false,
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    mounted: function mounted() {
        this.datosUsuario();
    },
    computed: {
        filteredList() {
            return this.guias.filter(post => {
                return post.nombreguia.toLowerCase().includes(this.buscar.toLowerCase())
            })
        }
    },
    methods: {
        datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data[0];
                    me.usuario = response.data;
                    me.getGuia(respuesta);
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
        getGuia(usuario) {
            let me = this;
            var hoy = new Date();
            var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
            var url = "./guia";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.guias = response.data;
                })
                .catch(function (error) {
                    me.socket.emit('erroresGuias', {
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
        datolibro(libro) {
            libro = '<iframe id="t0_iframe" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="display: block; width: 100%; height: 100%;  margin-left: auto; margin-right: auto; padding: 0px; top: 0px; position: fixed; left: 0px;" src="./upload/libro/' +
                libro + '/index.php"></iframe>'
            $(".libro").append(libro);
        },
    }
};
</script>

<style>
.contenido {
    border-radius: 30px 30px 30px 30px;
    -moz-border-radius: 30px 30px 30px 30px;
    -webkit-border-radius: 30px 30px 30px 30px;
    background: #272c33;
    color: #ffffff;
}
</style>
