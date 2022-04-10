<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="mdi mdi-book"></i>Registro Material</h3>
                </div>
            </div>
            <div class="card card-body">
                <v-btn color="primary" dark @click="dialog = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="materiales" :search="search" class="elevation-1" :pagination.sync="paginationI">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="indigo" @click="getEditar(item)"><i class="fa fa-pencil"></i></v-btn>
                        <v-btn fab dark small color="pink" @click="getEliminar(item.idmaterial)"><i class="fa fa-trash"></i></v-btn>
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
                                <input type="text" class="form-control form-control-line nombrematerial" v-model="nombrematerial" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionmaterial" v-model="descripcionmaterial" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line zipmaterial" v-model="zipmaterial" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">material Web</label>
                                <input type="text" class="form-control form-control-line zipmaterial" v-model="webmaterial" require>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">material Exe</label>
                                <v-select :options="cexe" v-model="exematerial">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
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
                                <input type="text" class="form-control form-control-line nombrematerial" v-model="idmateriale" hidden>
                                <input type="text" class="form-control form-control-line nombrematerial" v-model="nombremateriale" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionmaterial" v-model="descripcionmateriale" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line zipmaterial" v-model="zipmateriale" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">material Web</label>
                                <input type="text" class="form-control form-control-line zipmaterial" v-model="webmateriale" require>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">material Exe</label>
                                <v-select :options="cexe" v-model="exemateriale">
                                </v-select>
                            </div>
                        </div>
                        <div class="row col col-md-12">
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
                    value: 'nombrematerial',
                },
                {
                    text: 'Descripción',
                    value: 'descripcionmaterial',
                },
                {
                    text: 'Web',
                    value: 'webmaterial',
                },
                {
                    text: 'Digital',
                    value: 'exematerial',
                },
                {
                    text: 'Zip',
                    value: 'zipmaterial',
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            buscar: '',
            dialog: false,
            materiales: [],
            asignaturas: [],
            estado: [],
            cmaterial: [],
            cexe: [],
            cpdfguiadidactica: [],
            cpdfconguia: [],
            cpdfsinguia: [],
            // Variables de Nuevo
            nombrematerial: '',
            descripcionmaterial: '',
            zipmaterial: '',
            webmaterial: '',
            exematerial: '',
            pdfconguia: '',
            pdfsinguia: '',
            guiadidactica: '',
            asignatura_idasignatura: '',
            Estado_idEstado: '',
            // Variables de Edicion
            idmateriale: '',
            nombremateriale: '',
            descripcionmateriale: '',
            zipmateriale: '',
            webmateriale: '',
            exemateriale: '',
            pdfconguiae: '',
            pdfsinguiae: '',
            guiadidacticae: '',
            asignatura_idasignaturae: '',
            Estado_idEstadoe: '',
            dialog2: false,

        };
    },
    mounted: function mounted() {
        this.getmaterial();
        this.getAsignaturaSelect();
        this.getEstadoSelect();
        this.getcexeSelect();
    },
    methods: {
        getmaterial() {
            let me = this;
            var url = "./materiales";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.materiales = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getInfo(item) {},
        getEditar(item) {
            this.idmateriale = item.idmaterial;
            this.nombremateriale = item.nombrematerial;
            this.descripcionmateriale = item.descripcionmaterial;
            this.zipmateriale = item.zipmaterial;
            this.webmateriale = item.webmaterial;
            this.exemateriale = item.exematerial;
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
            axios.get("./Amaterialexe").then(function (response) {
                _this.cexe = response.data;
            });
        },
        setguardar() {
            let me = this;
            axios
                .post("./materialr/registrar", {
                    nombrematerial: me.nombrematerial,
                    descripcionmaterial: me.descripcionmaterial,
                    zipmaterial: me.zipmaterial,
                    webmaterial: me.webmaterial,
                    exematerial: me.exematerial,
                    Estado_idEstado: me.Estado_idEstado,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.getmaterial();
                })
                .catch(function (error) {
                    console.log(error);
                });

        },
        setguardare() {
            let me = this;
            axios
                .post("./materialr/update", {
                    idmaterial: me.idmateriale,
                    nombrematerial: me.nombremateriale,
                    descripcionmaterial: me.descripcionmateriale,
                    zipmaterial: me.zipmateriale,
                    webmaterial: me.webmateriale,
                    exematerial: me.exemateriale,
                    Estado_idEstado: me.Estado_idEstadoe,
                })
                .then(function (response) {
                    swal("Datos Actualizados", "", "success");
                    me.getmaterial();
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
                .post("materialr/eliminar", {
                    idmaterial: id
                })
                .then(function (response) {
                    me.getmaterial();
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
