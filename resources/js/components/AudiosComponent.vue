<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="fa fa-music"></i> Audios del Docente</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 animated  zoomInUp" v-for="dato in libros" :key="dato.idlibro">
                    <div class="card">
                        <img v-if="dato.weblibro != null" class="card-img-top img-responsive" v-bind:src="'upload/libro/'+dato.weblibro+'/portada.png'" alt="Card image cap">
                        <img v-else class="card-img-top img-responsive" v-bind:src="'upload/libro/portada.png'" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title">{{dato.nombrelibro}}</h4>
                            <div class="row">
                                <div class="col col-md-4 animated flip">
                                    <button class="btn btn-success" @click="getidAsignatura(dato.asignatura_idasignatura)" v-b-modal.modal-center>Listar Audios</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <b-modal id="modal-center" size="xl" centered title="Lista de Audios">
                <v-card-title>
                    <div class="flex-grow-1"></div>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="audios" :search="search" class="elevation-1" :pagination.sync="pagination">
                    <template v-slot:item.audio="{item}">
                        <audio v-bind:src="'upload/audios/'+item.nombreasignatura+'/descarga/'+item.webaudio+'.mp3'" preload="auto" controls></audio>
                    </template>
                    <template v-slot:item.descarga="{item}">
                        <v-btn class="mx-2" v-bind:href="'upload/audios/'+item.nombreasignatura+'/descarga/'+item.webaudio+'.mp3'" v-bind:download="item.webaudio+'.mp3'" fab dark small color="teal">
                            <v-icon dark>mdi-download</v-icon>
                        </v-btn>
                    </template>
                </v-data-table>
            </b-modal>
        </div>
        <footer class="footer">soporte@prolipa.com.ec</footer>
    </div>
    <v-dialog v-model="dialog" persistent max-width="600px">
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
            libros: [],
            pagination: {},
            audios: [],
            sortBy: 'idaudio',
            sortDesc: false,
            search: '',
            headers: [{
                    value: 'webaudio',
                    text: 'Nombre',
                },
                {
                    value:'audio',
                    text: 'Audio'
                },
                {
                    value:'descarga',
                    text: 'Descarga'
                },
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 30, 60, 120],
            sortBy: null,
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            dialog: false,
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    computed: {
        sortOptions() {
            return this.fields
                .filter(f => f.sortable)
                .map(f => {
                    return {
                        text: f.label,
                        value: f.key
                    }
                })
        }
    },
    mounted: function mounted() {
        this.datosUsuario();
        this.totalRows = this.audios.length;
    },
    methods: {
        datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data[0];
                    me.usuario = response.data;
                    me.getAudio(respuesta);
                    if (me.usuario.length > 0) {
                        me.dialog2 = false;
                    } else {
                        me.dialog2 = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        getAudio(usuario) {
            let me = this;
            var hoy = new Date();
            var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
            var url = "./libroA";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.libros = response.data;
                    console.log(response.data);
                })
                .catch(function (error) {
                    me.socket.emit('erroresAudio', {
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
        getidAsignatura(idasignatura) {
            let me = this;
            var url = "./audios?idasignatura=" + idasignatura;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.audios = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    }
};
</script>
