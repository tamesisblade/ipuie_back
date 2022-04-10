<template>
<div class="page-wrapper">
    <div class="container-fluid">
        <v-card-title>
            <h3 class="text-themecolor"><i class="mdi mdi-book"></i> Libros del Docente</h3>
            <v-spacer></v-spacer>
            <v-text-field v-model="buscar" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
        </v-card-title>
        <v-layout row wrap>
            <div class="col-lg-4 col-md-6 animated  zoomInUp " v-for="dato in filteredList" :key="dato.idlibro">
                <div class="py-4">
                    <div class="contenido">
                        <img v-if="dato.weblibro != null " class="card-img-top img-responsive contenido" v-bind:src="'upload/libro/'+dato.weblibro+'/portada.png'" alt="Card image cap">
                        <img v-else class="card-img-top img-responsive contenido" v-bind:src="'img/'+dato.portada" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title text-light">{{dato.nombrelibro}}</h4>
                            <p class="card-text">{{dato.descripcionlibro}}</p>
                            <div class="row">
                                <div class="col-md-4 animated flip">
                                    <b-button v-if="dato.pdfsinguia != null" v-bind:id="dato.idlibro+'1'" v-bind:href="'./upload/pdfsinguia/'+dato.pdfsinguia" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                    <b-tooltip v-bind:target="dato.idlibro+'1'" title="Pdf sin Guía"></b-tooltip>
                                </div>
                                <div class="col -md-4 animated flip">
                                    <b-button v-if="dato.pdfconguia != null" v-bind:id="dato.idlibro+'2'" v-bind:href="'./upload/pdfconguia/'+dato.pdfconguia" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                    <b-tooltip v-bind:target="dato.idlibro+'2'" title="Pdf con Guía"></b-tooltip>
                                </div>
                                <div class="col-md-4 animated flip">
                                    <b-button v-if="dato.guiadidactica != null" v-bind:id="dato.idlibro+'3'" v-bind:href="'./upload/pdfguiadidactica/'+dato.guiadidactica" variant="outline-danger"><i class="mdi mdi-file-pdf"></i></b-button>
                                    <b-tooltip v-bind:target="dato.idlibro+'3'" title="Guía Didáctica"></b-tooltip>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-7 animated flip">
                                    <b-button v-if="dato.weblibro != null" v-bind:id="dato.idlibro+'4'" @click="dialog = true; datolibro(dato.weblibro); posthistorial(dato.idlibro)" variant="outline-success"> Libro Web</b-button>
                                    <b-tooltip v-bind:target="dato.idlibro+'4'" title="Libro Web"></b-tooltip>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </v-layout>
    </div>
    <!-- <div v-if="libros.length === 0">
            <v-progress-circular indeterminate color="primary" class="bottom" />
        </div> -->
    <footer class="footer">soporte@prolipa.com.ec</footer>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <iframe id="t0_iframe" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="display: block; width: 100%; height: 100%; margin-left: auto; margin-right: auto; padding: 0px; top: 0px; position: absolute; left: 0px;" v-bind:src="'./upload/libro/'+nomlibro+'/index.php'"></iframe>
            <v-btn v-bind:class="'btn1-menu'" fab small dark color="red" @click="dialog = false">
                <i class="fa fa-times"></i>
            </v-btn>
        </v-card>
    </v-dialog>
    <v-dialog v-model="dialog2" persistent max-width="600px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-lock"></i> &nbsp; Plantalla de Bloqueo</span>
            </v-toolbar>
            <v-card-text class="center">
                <v-container fluid>
                    <h5><b>Su usuario no se encuentra habilitado para el periodo lectivo.</b></h5>
                </v-container>
            </v-card-text>
            <hr>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text @click="cerrarSession">Salir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="dialog3" persistent max-width="600px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-lock"></i> &nbsp; Cambio de Contraseña</span>
            </v-toolbar>
            <v-card-text class="center">
                <v-container fluid>
                    <div class="row">
                        <v-text-field v-model="password" type="password" label="Contraseña"></v-text-field>
                    </div>
                    <span v-if="errors.password" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.password[0]}}
                    </span>

                    <div class="row">
                        <v-text-field v-model="password_confirmation" type="password" label="Confirmacion de Contraseña"></v-text-field>
                    </div>
                    <span v-if="errors.password_confirmation" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.password_confirmation[0]}}
                    </span>
                </v-container>
            </v-card-text>
            <hr>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="success" text @click="guardarPassword">Guardar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import io from 'socket.io-client';
export default {
    data: function () {
        return {
            libros: [],
            usuario: [],
            pagination: {},
            nomlibro: '',
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
            dialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            dialog2: false,
            dialog3: false,
            password: '',
            password_confirmation: '',
            errors: [],
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    computed: {
        filteredList() {
            return this.libros.filter(post => {
                return post.nombrelibro.toLowerCase().includes(this.buscar.toLowerCase())
            })
        }
    },
    mounted: function mounted() {
        this.datosUsuario();
        this.agregaringreso();

        function salir() {
            dialog = false;
        };
    },
    methods: {
        async datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data[0];
                    me.usuario = response.data;
                    me.getLibro(respuesta);
                    console.log(respuesta);

                    if (me.usuario.length > 0) {
                        me.dialog2 = false;
                        var p_ingreso = response.data[0].p_ingreso
                        if (p_ingreso == '0') {
                            me.dialog3 = true;
                        }
                    } else {
                        me.dialog2 = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        async getLibro(usuario) {
            let me = this;
            var url = "./libro";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.libros = response.data;
                })
                .catch(function (error) {

                });
        },
        async datolibro(libro) {
            this.nomlibro = libro;
        },
        salir: function () {
            dialog = false;
        },
        async agregaringreso() {
            let me = this;
            var url = "./libro/registraringreso";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        async guardarPassword() {

            let me = this;
            axios
                .post("./guardarPassword", {
                    password: me.password,
                    password_confirmation: me.password_confirmation
                })
                .then(function (response) {
                    swal("Contraseña Actualizada", "", "success");
                    me.datosUsuario();
                    me.dialog3 = false;
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });

        },
        async cerrarSession() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        },
        async posthistorial(id) {
            let me = this;
            axios.post("./postHistorialLibro", {
                    idlibro: id
                })
                .then(function (response) {

                })
                .catch(function (error) {})
        }

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
