<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-card-title>
                <h3 class="text-themecolor">
                    <i class="fa fa-tachometer"></i> Dashboard
                </h3>
            </v-card-title>
            <div class="row">
                <div class="col-lg-6 col-md-6 animated zoomInUp">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios Registrados</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{usuarior}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 animated zoomInUp">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Instituciones Registradas</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{institucionr}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-university fa-2x text-gray-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-6 col-md-6 animated zoomInUp">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Visitas Usuarios Prolipa</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{usuariop}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-line-chart fa-2x text-gray-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 animated zoomInUp" @click="historial = true">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Visitas Usuarios Instituciones</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{usuariot}}</div>
                                </div>
                                <v-btn block class="mb-0 btn btn-success">Ver Detalle</v-btn block>
                                <div class="col-auto">
                                    <i class="fas fa-line-chart fa-2x text-gray-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="card-body">
                    <template v-for="dato in numInst">
                        <h4 class="small font-weight-bold animated zoomInUp">
                            {{dato.nombre}}
                            <span class="float-right">{{dato.total}} Instituciones Registradas</span>
                        </h4>
                        <div class="progress mb-4 animated zoomInUp">
                            <div class="progress-bar bg-info" role="progressbar" :style="'width:'+ dato.total +'%'" :aria-valuenow="dato.total" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <v-dialog v-model="historial" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card tile>
                <v-toolbar dark color="primary">
                    <v-spacer></v-spacer>
                    <v-btn fab small dark color="red" @click="historial = false">
                        <i class="fa fa-times"></i>
                    </v-btn>
                </v-toolbar>
                <!-- Aqui poner la tabla -->
                <v-list three-line subheader>
                    <div class="card-body">
                        <div class="form-body py-4">
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    <v-menu v-model="ini" :close-on-content-click="false" :nudge-right="40" transition="scale-transition" offset-y min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="dates.ini" label="Fecha Inicial" prepend-icon="event" readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="dates.ini" @change="onChange()" @input="ini = false"></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" sm="6" md="4">
                                    <v-menu v-model="fin" :close-on-content-click="false" :nudge-right="40" transition="scale-transition" offset-y min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="dates.fin" label="Fecha Final" prepend-icon="event" readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="dates.fin" @change="onChange()" @input="fin = false"></v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    <v-label><b>Cantidad de visitas: </b> {{users.length}}</v-label>
                                </v-col>
                            </v-row>
                            <v-data-table :headers="headers" :items="users" :items-per-page="10" class="elevation-1"></v-data-table>
                        </div>
                    </div>
                </v-list>
            </v-card>
        </v-dialog>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
</v-app>
</template>

<script>
export default {
    data() {
        return {
            ini: false,
            fin: false,
            historial: false,
            usuarior: "",
            institucionr: "",
            usuariop: "",
            usuariot: "",
            numInst: [],
            users: [],
            dates: [{
                ini: '',
                fin: ''
            }],
            // header de la tabla
            headers: [{
                    text: 'cedula',
                    align: 'start',
                    sortable: false,
                    value: 'cedula',
                },
                {
                    text: 'Nombres',
                    value: 'nombre'
                },
                {
                    text: 'Apellidos',
                    value: 'apellido'
                },
                {
                    text: 'Fecha de Ingreso',
                    value: 'hora_ingreso_usuario'
                },
                {
                    text: 'IP',
                    value: 'ip'
                },

            ]
        };
    },
    mounted() {
        this.getProlipa();
        this.getInstituciones();
        this.getRegistroI();
        this.getRegistroU();
        this.getnumInstitucion();
    },
    methods: {
        getProlipa() {
            let me = this;
            var url = "./accesoProlipa";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.usuariop = respuesta[0].visitas;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getInstituciones() {
            let me = this;
            var url = "./accesoUsuarios";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.usuariot = respuesta[0].visitas;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getRegistroI() {
            let me = this;
            var url = "./institucionRegistradas";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.institucionr = respuesta[0].dato;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getRegistroU() {
            let me = this;
            var url = "./usuariosRegistrados";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.usuarior = respuesta[0].dato;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getnumInstitucion() {
            let me = this;
            var url = "./numInstituciones";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.numInst = respuesta;
                    console.log(me.numInst);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        onChange() {
            let me = this;
            axios
                .get("./buscar?ini=" + me.dates.ini + "&fin=" + me.dates.fin)
                .then(function (response) {
                    me.users = response.data;
                    console.log(me.users);
                    console.log(response.data);
                })
                .catch(function (error) {});
        }
    }
};
</script>
