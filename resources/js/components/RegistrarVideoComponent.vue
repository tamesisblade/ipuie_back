<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="mdi mdi-book"></i>Registro Video</h3>
                </div>
            </div>
            <div class="card card-body">
                <v-btn color="primary" dark @click="dialog = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="videos" :search="search" class="elevation-1" :pagination.sync="paginationI">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="indigo" @click="getEditar(item)"><i class="fa fa-pencil"></i></v-btn>
                        <v-btn fab dark small color="pink" @click="getEliminar(item.idvideo)"><i class="fa fa-trash"></i></v-btn>
                    </template>
                </v-data-table>
            </div>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
    <!-- Nuevo Registro -->
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-spacer></v-spacer>
                <v-btn fab dark color="pink" @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="card-body">
                    <div class="form-body">
                        <h3 class="box-title">Datos del Registro</h3>
                        <hr class="m-t-0 m-b-40">
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre</label>
                                <input type="text" class="form-control form-control-line nombrevideo" v-model="nombrevideo" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionvideo" v-model="descripcionvideo" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">video Web</label>
                                <input type="text" class="form-control form-control-line descripcionvideo" v-model="webvideo" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group  col col-md-6">
                                <label class="">Asignatura</label>
                                <select class="form-control" v-model="asignatura_idasignatura">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in asignaturas" :key="datoc.idasignatura" :value="datoc.idasignatura" v-text="datoc.nombreasignatura.toUpperCase()"></option>
                                </select>
                            </div>
                            <div class="form-group col col-md-6">
                                <label class="">Estado</label>
                                <select class="form-control" v-model="Estado_idEstado">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in estado" :key="datoc.idEstado" :value="datoc.idEstado" v-text="datoc.nombreestado.toUpperCase()"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col col-md-12 text-center">
                            <v-btn @click="setguardar()" fab color="teal">
                                <v-icon>save</v-icon>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!-- Fin Nuevo Registro -->
    <!-- Editar Registro -->
    <v-dialog v-model="dialog2" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-spacer></v-spacer>
                <v-btn fab dark color="pink" @click="dialog2 = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="card-body">
                    <div class="form-body">
                        <h3 class="box-title">Datos del Registro</h3>
                        <hr class="m-t-0 m-b-40">
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre</label>
                                <input type="text" class="form-control form-control-line nombrevideo" v-model="idvideoe" hidden>
                                <input type="text" class="form-control form-control-line nombrevideo" v-model="nombrevideoe" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionvideo" v-model="descripcionvideoe" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">video Web</label>
                                <input type="text" class="form-control form-control-line descripcionvideo" v-model="webvideoe" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group  col col-md-6">
                                <label class="">Asignatura</label>
                                <select class="form-control" v-model="asignatura_idasignaturae">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in asignaturas" :key="datoc.idasignatura" :value="datoc.idasignatura" v-text="datoc.nombreasignatura.toUpperCase()"></option>
                                </select>
                            </div>
                            <div class="form-group col col-md-6">
                                <label class="">Estado</label>
                                <select class="form-control" v-model="Estado_idEstadoe">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in estado" :key="datoc.idEstado" :value="datoc.idEstado" v-text="datoc.nombreestado.toUpperCase()"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col col-md-12 text-center">
                            <v-btn @click="setguardare()" fab color="teal">
                                <v-icon>save</v-icon>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Registro -->
</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            search: "",
            paginationI: {
                rowsPerPage: '10'
            },
            headers: [{
                    text: 'Nombre',
                    value: 'nombrevideo',
                },
                {
                    text: 'Descripción',
                    value: 'descripcionvideo',
                },
                {
                    text: 'Web',
                    value: 'webvideo',
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            buscar: '',
            dialog: false,
            videos: [],
            asignaturas: [],
            estado: [],
            cvideo: [],
            cexe: [],
            cpdfguiadidactica: [],
            cpdfconguia: [],
            cpdfsinguia: [],
            // Variables de Nuevo
            nombrevideo: '',
            descripcionvideo: '',
            zipvideo: '',
            webvideo: '',
            exevideo: '',
            pdfconguia: '',
            pdfsinguia: '',
            guiadidactica: '',
            asignatura_idasignatura: '',
            Estado_idEstado: '',
            // Variables de Edicion
            idvideoe: '',
            nombrevideoe: '',
            descripcionvideoe: '',
            zipvideoe: '',
            webvideoe: '',
            exevideoe: '',
            pdfconguiae: '',
            pdfsinguiae: '',
            guiadidacticae: '',
            asignatura_idasignaturae: '',
            Estado_idEstadoe: '',
            dialog2: false,

        };
    },
    mounted: function mounted() {
        this.getvideo();
        this.getAsignaturaSelect();
        this.getEstadoSelect();
    },
    methods: {
        getvideo() {
            let me = this;
            var url = "./videos";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.videos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getInfo(item) {},
        getEditar(item) {
            this.idvideoe = item.idvideo;
            this.nombrevideoe = item.nombrevideo;
            this.descripcionvideoe = item.descripcionvideo;
            this.webvideoe = item.webvideo;
            this.asignatura_idasignaturae = item.asignatura_idasignatura;
            this.Estado_idEstadoe = item.Estado_idEstado;
            this.dialog2 = true;
        },
        getAsignaturaSelect() {
            var _this = this;
            axios.get("./asignaturaSelect").then(function (response) {
                _this.asignaturas = response.data;
            });
        },
        getEstadoSelect() {
            var _this = this;
            axios.get("./estadoSelect").then(function (response) {
                _this.estado = response.data;
            });
        },
        getcexeSelect() {
            var _this = this;
            axios.get("./Avideoexe").then(function (response) {
                _this.cexe = response.data;
            });
        },
        getpdfguiadidacticaSelect() {
            var _this = this;
            axios.get("./Apdfguiadidactica").then(function (response) {
                _this.cpdfguiadidactica = response.data;
            });
        },
        getpdfsinguiaSelect() {
            var _this = this;
            axios.get("./Apdfsinguia").then(function (response) {
                _this.cpdfsinguia = response.data;
            });
        },
        getpdfconguiaSelect() {
            var _this = this;
            axios.get("./Apdfconguia").then(function (response) {
                _this.cpdfconguia = response.data;
            });
        },
        setguardar() {
            let me = this;
            axios
                .post("./videor/registrar", {
                    nombrevideo: me.nombrevideo,
                    descripcionvideo: me.descripcionvideo,
                    webvideo: me.webvideo,
                    asignatura_idasignatura: me.asignatura_idasignatura,
                    Estado_idEstado: me.Estado_idEstado,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.getvideo();
                })
                .catch(function (error) {
                    console.log(error);
                });

        },
        setguardare() {
            let me = this;
            axios
                .post("./videor/update", {
                    idvideo: me.idvideoe,
                    nombrevideo: me.nombrevideoe,
                    descripcionvideo: me.descripcionvideoe,
                    webvideo: me.webvideoe,
                    asignatura_idasignatura: me.asignatura_idasignaturae,
                    Estado_idEstado: me.Estado_idEstadoe,
                })
                .then(function (response) {
                    swal("Datos Actualizados", "", "success");
                    me.getvideo();
                })
                .catch(function (error) {
                    console.log(error);
                });

        },
        getEliminar(item) {
            let me = this;
            swal({
                title: "Seguro desea eliminar el registro?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    me.eliminarf(item);
                } else {
                    swal("Cancelado!");
                }
            });
        },
        eliminarf(id) {
            let me = this;
            axios
                .post("videor/eliminar", {
                    idvideo: id
                })
                .then(function (response) {
                    me.getvideo();
                    swal("Registro Eliminado!", {
                        icon: "success"
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    }
};
</script>
