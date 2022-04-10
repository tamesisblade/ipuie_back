<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="mdi mdi-book"></i>Registro planlector</h3>
                </div>
            </div>
            <div class="card card-body">
                <v-btn color="primary" dark @click="dialog = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="planlectors" :search="search" class="elevation-1" :pagination.sync="paginationI">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="indigo" @click="getEditar(item)"><i class="fa fa-pencil"></i></v-btn>
                        <v-btn fab dark small color="pink" @click="getEliminar(item.idplanlector)"><i class="fa fa-trash"></i></v-btn>
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
                                <input type="text" class="form-control form-control-line nombreplanlector" v-model="nombreplanlector" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionplanlector" v-model="descripcionplanlector" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line zipplanlector" v-model="zipplanlector" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">planlector Web</label>
                                <v-select :options="cplanlector" v-model="webplanlector">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">planlector Exe</label>
                                <v-select :options="cexe" v-model="exeplanlector">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-4">
                                <label class="">Pdf con Guía</label>
                                <v-select :options="cpdfconguia" v-model="pdfconguia">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-4">
                                <label class="">Pdf sin Guía</label>
                                <v-select :options="cpdfsinguia" v-model="pdfsinguia">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-4">
                                <label class="">Guía Didáctica</label>
                                <v-select :options="cpdfguiadidactica" v-model="guiadidactica">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group  col col-md-6">
                                <label class="">Asignatura</label>
                                <v-select :options="asignaturas" value="idasignatura" :reduce="asignatura => asignatura.idasignatura" label="nombreasignatura" v-model="asignatura_idasignatura">
                                </v-select>
                                <!-- <select class="form-control" v-model="asignatura_idasignatura">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in asignaturas" :key="datoc.idasignatura" :value="datoc.idasignatura" v-text="datoc.nombreasignatura.toUpperCase()"></option>
                                </select> -->
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
                                <input type="text" class="form-control form-control-line nombreplanlector" v-model="idplanlectore" hidden>
                                <input type="text" class="form-control form-control-line nombreplanlector" v-model="nombreplanlectore" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionplanlector" v-model="descripcionplanlectore" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line zipplanlector" v-model="zipplanlectore" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">planlector Web</label>
                                <v-select :options="cplanlector" v-model="webplanlectore">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">planlector Exe</label>
                                <v-select :options="cexe" v-model="exeplanlectore">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-4">
                                <label class="">Pdf con Guía</label>
                                <v-select :options="cpdfconguia" v-model="pdfconguiae">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-4">
                                <label class="">Pdf sin Guía</label>
                                <v-select :options="cpdfsinguia" v-model="pdfsinguiae">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-4">
                                <label class="">Guía Didáctica</label>
                                <v-select :options="cpdfguiadidactica" v-model="guiadidacticae">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group  col col-md-6">
                                <label class="">Asignatura</label>
                                <select class="form-control" v-model="asignatura_idasignaturae" disabled>
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="datoc in asignaturas" :key="datoc.idasignatura" :value="datoc.idasignatura" v-text="datoc.nombreasignatura.toUpperCase()"></option>
                                </select>
                                <v-select :options="asignaturas" value="idasignatura" :reduce="asignatura => asignatura.idasignatura" label="nombreasignatura" v-model="asignatura_idasignaturae">
                                </v-select>
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
                    value: 'nombreplanlector',
                },
                {
                    text: 'Descripción',
                    value: 'descripcionplanlector',
                },
                {
                    text: 'Web',
                    value: 'webplanlector',
                },
                {
                    text: 'Digital',
                    value: 'exeplanlector',
                },
                {
                    text: 'PDF sin Guía',
                    value: 'pdfsinguia',
                },
                {
                    text: 'PDF con Guía',
                    value: 'pdfconguia',
                },
                {
                    text: 'Guía Didactica',
                    value: 'guiadidactica',
                },
                {
                    text: 'Zip',
                    value: 'zipplanlector',
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            buscar: '',
            dialog: false,
            planlectors: [],
            asignaturas: [],
            estado: [],
            cplanlector: [],
            cexe: [],
            cpdfguiadidactica: [],
            cpdfconguia: [],
            cpdfsinguia: [],
            // Variables de Nuevo
            nombreplanlector: '',
            descripcionplanlector: '',
            zipplanlector: '',
            webplanlector: '',
            exeplanlector: '',
            pdfconguia: '',
            pdfsinguia: '',
            guiadidactica: '',
            asignatura_idasignatura: '',
            Estado_idEstado: '',
            // Variables de Edicion
            idplanlectore: '',
            nombreplanlectore: '',
            descripcionplanlectore: '',
            zipplanlectore: '',
            webplanlectore: '',
            exeplanlectore: '',
            pdfconguiae: '',
            pdfsinguiae: '',
            guiadidacticae: '',
            asignatura_idasignaturae: '',
            Estado_idEstadoe: '',
            dialog2: false,

        };
    },
    mounted: function mounted() {
        this.getplanlector();
        this.getAsignaturaSelect();
        this.getEstadoSelect();
        this.getcplanlectorSelect();
        this.getcexeSelect();
        this.getpdfguiadidacticaSelect();
        this.getpdfsinguiaSelect();
        this.getpdfconguiaSelect();
    },
    methods: {
        getplanlector() {
            let me = this;
            var url = "./planlectors";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.planlectors = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getInfo(item) {},
        getEditar(item) {
            this.idplanlectore = item.idplanlector;
            this.nombreplanlectore = item.nombreplanlector;
            this.descripcionplanlectore = item.descripcionplanlector;
            this.zipplanlectore = item.zipplanlector;
            this.webplanlectore = item.webplanlector;
            this.exeplanlectore = item.exeplanlector;
            this.pdfconguiae = item.pdfconguia;
            this.pdfsinguiae = item.pdfsinguia;
            this.guiadidacticae = item.guiadidactica;
            this.asignatura_idasignaturae = item.asignatura_idasignatura;
            this.Estado_idEstadoe = item.estado_idEstado;
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
        getcplanlectorSelect() {
            var _this = this;
            axios.get("./Aplanlectordigital").then(function (response) {
                _this.cplanlector = response.data;
            });
        },
        getcexeSelect() {
            var _this = this;
            axios.get("./Aplanlectorexe").then(function (response) {
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
                .post("./planlectorr/registrar", {
                    nombreplanlector: me.nombreplanlector,
                    descripcionplanlector: me.descripcionplanlector,
                    zipplanlector: me.zipplanlector,
                    webplanlector: me.webplanlector,
                    exeplanlector: me.exeplanlector,
                    pdfconguia: me.pdfconguia,
                    pdfsinguia: me.pdfsinguia,
                    guiadidactica: me.guiadidactica,
                    asignatura_idasignatura: me.asignatura_idasignatura,
                    Estado_idEstado: me.Estado_idEstado,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.getplanlector();
                })
                .catch(function (error) {
                    console.log(error);
                });

        },
        setguardare() {
            let me = this;
            axios
                .post("./planlectorr/update", {
                    idplanlector: me.idplanlectore,
                    nombreplanlector: me.nombreplanlectore,
                    descripcionplanlector: me.descripcionplanlectore,
                    zipplanlector: me.zipplanlectore,
                    webplanlector: me.webplanlectore,
                    exeplanlector: me.exeplanlectore,
                    pdfconguia: me.pdfconguiae,
                    pdfsinguia: me.pdfsinguiae,
                    guiadidactica: me.guiadidacticae,
                    asignatura_idasignatura: me.asignatura_idasignaturae,
                    Estado_idEstado: me.Estado_idEstadoe,
                })
                .then(function (response) {
                    swal("Datos Actualizados", "", "success");
                    me.getplanlector();
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
                .post("planlectorr/eliminar", {
                    idplanlector: id
                })
                .then(function (response) {
                    me.getplanlector();
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
