<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="fa fa-cog"></i> Período Escolar</h3>
                </div>
            </div>
            <v-card>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="periodo" :search="search">
                    <template v-slot:item.estado="{item}">
                        <v-btn v-if="item.estado == 0" @click="activar(item)" color="red" dark>Inactivo</v-btn>
                        <v-btn v-else color="success" @click="desactivar(item)">Activo</v-btn>
                    </template>
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="teal" @click="dialog = true ; getInstituciones(item)"><i class="fa fa-info"></i></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
    <!-- Modal Asignar Elementos Usuario -->
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn @click="printInstituciones" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn fab dark small color="pink"  @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div id="printIns">
                    <h1 class="text-center">{{titulo.toUpperCase()}}</h1>
                    <v-card-title class="acciones">
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search2" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headersI" :items="institucion" :search="search2" class="elevation-1" :pagination.sync="paginationI">
                        <template v-slot:item.acciones="{item}">
                            <v-btn @click="dialog2=true; getPeriodoInstitucion(item.idInstitucion)" fab dark small color="primary">
                                <i class="fa fa-plus"></i>
                            </v-btn>
                        </template>
                    </v-data-table>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!--Fin Modal Asignar Elementos Usuario -->
    <v-dialog v-model="dialog2" max-width="700px" max-heingth="700px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"> Periodos de la Institución</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="dialog2 = false; errors = []"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <div class="row">
                        <div class="col col-md-3">
                            <b>Periodo Escolar</b>
                        </div>
                        <div class="col col-md-6">
                            <v-select :options="periodo" :reduce="periodo => periodo.idperiodoescolar" label="descripcion" v-model="periodoescolar_idperiodoescolar">
                                <template slot="option" slot-scope="option">
                                    {{ option.fecha_inicial+" "+option.fecha_final+" "+option.nombreregion.toUpperCase() }}
                                </template>
                            </v-select>
                        </div>
                        <div class="col col-md-3">
                            <v-btn color="success" @click="guardar(idInstitucion)">Agregar</v-btn>
                        </div>
                    </div>
                    <div class="row">
                        <span v-if="errors.periodo_escolar" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.periodo_escolar[0]}}
                        </span>
                    </div>
                    <v-card-title>
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-data-table :headers="headersP" :items="periodoinstitucion" :search="search3">
                        <template v-slot:item.acciones="{item}">
                            <v-btn fab dark small color="error" @click="eliminarPeriodoInstitucion(item)"> <i class="fa fa-trash"></i> </v-btn>
                        </template>
                    </v-data-table>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            sortDesc: false,
            // Tabla Institucion
            paginationI: {
                rowsPerPage: '10'
            },
            search: '',
            search2: '',
            search3: '',
            headers: [{
                    text: 'Fecha Inicial',
                    value: 'fecha_inicial'
                },
                {
                    text: 'Fecha Final',
                    value: 'fecha_final'
                },
                {
                    text: 'Descripcion',
                    value: 'descripcion'
                },
                {
                    text: 'Region',
                    value: 'nombreregion'
                },
                {
                    text: 'Estado',
                    value: 'estado'
                },
                {
                    text: 'Acciones',
                    value: 'acciones'
                }
            ],
            headersI: [{
                    text: 'Institución',
                    value: 'nombreInstitucion'
                },
                {
                    text: 'Región',
                    value: 'nombreregion'
                },
                {
                    text: 'Ciudad',
                    value: 'nombre'
                },
                {
                    text: 'Asesor',
                    value: 'apellidos'
                },
                {
                    text: 'Base de Datos',
                    value: 'periodoescolar'
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            headersP: [{
                    text: 'Descripción',
                    value: 'descripcion'
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            periodo: [],
            periodoinstitucion: [],
            institucion: [],
            dialog: false,
            dialog2: false,
            titulo: '',
            periodoescolar_idperiodoescolar: '',
            idInstitucion: '',
            errors: [],

        };
    },
    mounted: function mounted() {
        this.getPeriodoSelect();
    },
    methods: {
        getPeriodoSelect() {
            var _this = this;
            axios.get("./periodoSelect").then(function (response) {
                _this.periodo = response.data;
            });
        },
        getPeriodoInstitucion(item) {
            this.idInstitucion = item;
            var _this = this;
            axios.get("./periodoInstitucion?idInstitucion=" + item).then(function (response) {
                _this.periodoinstitucion = response.data;
            });
        },
        activar(item) {
            let me = this;
            axios
                .post("./periodo/activar", {
                    idperiodoescolar: item.idperiodoescolar,
                    estado: "1"
                })
                .then(function (response) {
                    me.getPeriodoSelect();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        desactivar(item) {
            let me = this;
            axios
                .post("./periodo/desactivar", {
                    idperiodoescolar: item.idperiodoescolar,
                    estado: "0"
                })
                .then(function (response) {
                    me.getPeriodoSelect();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getInstituciones(item) {
            let me = this;
            me.titulo = item.descripcion;
            var url = "./Hinstitucion?id=" + item.idperiodoescolar;
            axios
                .get(url).then(function (response) {
                    var respuesta = response.data;
                    me.institucion = response.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        printInstituciones() {
            $(".acciones").hide();
            this.$htmlToPaper('printIns');
            $(".acciones").show();
        },
        guardar() {
            let me = this;
            axios
                .post("./periodoinstitucion/registrar", {
                    idInstitucion: me.idInstitucion,
                    periodo_escolar: me.periodoescolar_idperiodoescolar
                })
                .then(function (response) {
                    me.getPeriodoInstitucion(me.idInstitucion);
                    me.errors = [];
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        eliminarPeriodoInstitucion(item) {
            let me = this;
            var _this = this;
            swal({
                title: "Seguro desea Eliminar ",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios.post("./periodoinstitucion/eliminar", {
                        id: item.id
                    }).then(function (response) {
                        me.getPeriodoInstitucion(item.institucion_idInstitucion);
                        swal("¡Registro Eliminado!", "", "success");
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    swal("¡Cancelado!");
                }
            });
        }
    }
};
</script>
