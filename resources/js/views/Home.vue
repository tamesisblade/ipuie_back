<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="mdi mdi-book"></i>Registro Libro</h3>
                </div>
            </div>
            <div class="card card-body">
                <v-btn color="primary" dark @click="dialog = true ; limpiarDatosInstitucion()"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="libros" :search="search" class="elevation-1">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="indigo" @click="getEditar(item)"><i class="fa fa-pencil"></i></v-btn>
                        <v-btn fab dark small color="pink" @click="getEliminar(item.idlibro)"><i class="fa fa-trash"></i></v-btn>
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
                                <input type="text" class="form-control form-control-line nombrelibro" v-model="nombrelibro" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionlibro" v-model="descripcionlibro" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line ziplibro" v-model="ziplibro" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">Libro Web</label>
                                <v-select :options="clibro" v-model="weblibro">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">Libro Exe</label>
                                <v-select :options="cexe" v-model="exelibro">
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
                                <input type="text" class="form-control form-control-line nombrelibro" v-model="idlibroe" hidden>
                                <input type="text" class="form-control form-control-line nombrelibro" v-model="nombrelibroe" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Descripción</label>
                                <input type="text" class="form-control form-control-line descripcionlibro" v-model="descripcionlibroe" require>
                            </div>
                        </div>
                        <div class="col col-md-12">
                            <div class="form-group form-horizontal form-material">
                                <label class="">Nombre ZIP</label>
                                <input type="text" class="form-control form-control-line ziplibro" v-model="ziplibroe" require>
                            </div>
                        </div>
                        <div class="row col col-md-12">
                            <div class="form-group col col-md-6">
                                <label class="">Libro Web</label>
                                <v-select :options="clibro" v-model="weblibroe">
                                </v-select>
                            </div>
                            <div class="form-group  col col-md-6">
                                <label class="">Libro Exe</label>
                                <v-select :options="cexe" v-model="exelibroe">
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
import 'vuetify/dist/vuetify.min.css'
import Vue from 'vue'
import Vuetify from 'vuetify'
Vue.use(VueAlertify);
export default {
    data: function () {
        return {
            search: "",
            headers: [{
                    text: 'Id',
                    value: 'idlibro',
                },
                {
                    text: 'Nombre',
                    value: 'nombrelibro',
                },
                {
                    text: 'Descripción',
                    value: 'descripcionlibro',
                },
                // {
                //     text: 'Web',
                //     value: 'weblibro',
                // },
                // {
                //     text: 'Digital',
                //     value: 'exelibro',
                // },
                // {
                //     text: 'PDF sin Guía',
                //     value: 'pdfsinguia',
                // },
                // {
                //     text: 'PDF con Guía',
                //     value: 'pdfconguia',
                // },
                // {
                //     text: 'Guía Didactica',
                //     value: 'guiadidactica',
                // },
                // {
                //     text: 'Zip',
                //     value: 'ziplibro',
                // },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            buscar: '',
            dialog: false,
            libros: [],
            asignaturas: [],
            estado: [],
            clibro: [],
            cexe: [],
            cpdfguiadidactica: [],
            cpdfconguia: [],
            cpdfsinguia: [],
            // Variables de Nuevo
            nombrelibro: '',
            descripcionlibro: '',
            ziplibro: '',
            weblibro: '',
            exelibro: '',
            pdfconguia: '',
            pdfsinguia: '',
            guiadidactica: '',
            asignatura_idasignatura: '',
            Estado_idEstado: '',
            // Variables de Edicion
            idlibroe: '',
            nombrelibroe: '',
            descripcionlibroe: '',
            ziplibroe: '',
            weblibroe: '',
            exelibroe: '',
            pdfconguiae: '',
            pdfsinguiae: '',
            guiadidacticae: '',
            asignatura_idasignaturae: '',
            Estado_idEstadoe: '',
            dialog2: false,

        };
    },
    mounted: function mounted() {
        this.getLibro();
        this.getAsignaturaSelect();
        this.getEstadoSelect();
        this.getclibroSelect();
        this.getcexeSelect();
        this.getpdfguiadidacticaSelect();
        this.getpdfsinguiaSelect();
        this.getpdfconguiaSelect();
    },
    methods: {
        getLibro() {
            let me = this;
            var url = "./libros";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.libros = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getInfo(item) {},
        getEditar(item) {
            this.idlibroe = item.idlibro;
            this.nombrelibroe = item.nombrelibro;
            this.descripcionlibroe = item.descripcionlibro;
            this.ziplibroe = item.ziplibro;
            this.weblibroe = item.weblibro;
            this.exelibroe = item.exelibro;
            this.pdfconguiae = item.pdfconguia;
            this.pdfsinguiae = item.pdfsinguia;
            this.guiadidacticae = item.guiadidactica;
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
        getclibroSelect() {
            var _this = this;
            axios.get("./Alibro").then(function (response) {
                _this.clibro = response.data;
            });
        },
        getcexeSelect() {
            var _this = this;
            axios.get("./Aexe").then(function (response) {
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
                .post("./libror/registrar", {
                    nombrelibro: me.nombrelibro,
                    descripcionlibro: me.descripcionlibro,
                    ziplibro: me.ziplibro,
                    weblibro: me.weblibro,
                    exelibro: me.exelibro,
                    pdfconguia: me.pdfconguia,
                    pdfsinguia: me.pdfsinguia,
                    guiadidactica: me.guiadidactica,
                    asignatura_idasignatura: me.asignatura_idasignatura,
                    Estado_idEstado: me.Estado_idEstado,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.getLibro();
                })
                .catch(function (error) {
                    console.log(error);
                });

        },
        setguardare() {
            let me = this;
            axios
                .post("./libror/update", {
                    idlibro: me.idlibroe,
                    nombrelibro: me.nombrelibroe,
                    descripcionlibro: me.descripcionlibroe,
                    ziplibro: me.ziplibroe,
                    weblibro: me.weblibroe,
                    exelibro: me.exelibroe,
                    pdfconguia: me.pdfconguiae,
                    pdfsinguia: me.pdfsinguiae,
                    guiadidactica: me.guiadidacticae,
                    asignatura_idasignatura: me.asignatura_idasignaturae,
                    Estado_idEstado: me.Estado_idEstadoe,
                })
                .then(function (response) {
                    swal("Datos Actualizados", "", "success");
                    me.getLibro();
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
                .post("libror/eliminar", {
                    idlibro: id
                })
                .then(function (response) {
                    me.getLibro();
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
