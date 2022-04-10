<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="mdi mdi-book"></i>Registro de Asignaturas</h3>
                </div>
            </div>
            <v-card>
                <v-btn color="primary" dark @click="dialog = true; me.limpiar()"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="asignaturas" :search="search" class="elevation-1" :items-per-page="paginationI">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark icon color="indigo" @click="getEditar(item); editarAsignatura = true"><i class="fa fa-pencil"></i></v-btn>
                        <v-btn fab dark icon color="pink" @click="getEliminar(item.idasignatura)"><i class="fa fa-trash"></i></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </div>
    <!-- Nuevo Registro -->
    <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <span class="headline">
                    <i class="fas fa-layer-group"></i> Nueva Asignatura
                </span>
                <v-spacer></v-spacer>
                <v-btn fab dark small color="pink" @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <v-text-field v-model="nombreasignatura" label="Nombre de la Asignatura" required></v-text-field>
                    <span v-if="errors.nombre" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.nombre[0]}}
                    </span>
                    <div class="row">
                        <div class="col col-md-6">
                            <label class="">
                                Área
                                <v-btn @click="nuevaArea=true" flat icon color="green">
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </label>
                            <v-select :options="areasS" value="idarea" :reduce="area => area.idarea" label="nombrearea" v-model="area_idarea">
                            </v-select>
                            <span v-if="errors.area" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.area[0]}}
                            </span>
                        </div>
                        <div class="col col-md-6">
                            <label class="">
                                Nivel
                                <v-btn @click="nuevoNivel=true" flat icon color="green">
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </label>
                            <v-select :options="nivelsS" value="idnivel" :reduce="nivel => nivel.idnivel" label="nombrenivel" v-model="nivel_idnivel">
                            </v-select>
                            <span v-if="errors.nivel" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.nivel[0]}}
                            </span>
                        </div>
                    </div>
                </v-container>
                <v-card-actions class="justify-center">
                    <v-btn @click="setguardar()" color="teal">
                        <v-icon>save</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!-- Fin Nuevo Registro -->

    <v-dialog v-model="editarAsignatura" persistent max-width="800px">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <span class="headline">
                    <i class="fa fa-pencil"></i> Editar Asignatura
                </span>
                <v-spacer></v-spacer>
                <v-btn fab dark small color="pink" @click="editarAsignatura = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <v-text-field v-model="nombreasignatura" label="Nombre de la Asignatura" required></v-text-field>
                    <span v-if="errors.nombre" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.nombre[0]}}
                    </span>
                    <div class="row">
                        <div class="col col-md-6">
                            <label class="">
                                Área
                                <v-btn @click="nuevaArea=true" flat icon color="green">
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </label>
                            <v-select :options="areasS" value="idarea" :reduce="area => area.idarea" label="nombrearea" v-model="area_idarea">
                            </v-select>
                            <span v-if="errors.area" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.area[0]}}
                            </span>
                        </div>
                        <div class="col col-md-6">
                            <label class="">
                                Nivel
                                <v-btn @click="nuevoNivel=true" flat icon color="green">
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </label>
                            <v-select :options="nivelsS" value="idnivel" :reduce="nivel => nivel.idnivel" label="nombrenivel" v-model="nivel_idnivel">
                            </v-select>
                            <span v-if="errors.nivel" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.nivel[0]}}
                            </span>
                        </div>
                    </div>
                </v-container>
                <v-card-actions class="justify-center">
                    <v-btn @click="updateguardar()" color="teal">
                        <v-icon>save</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-dialog>

    <v-dialog v-model="nuevaArea" max-width="500px">
        <v-card>
            <v-card-title>
                <h3 class="box-title">Nueva Area</h3>
                <hr class="m-t-0 m-b-40">
            </v-card-title>
            <v-card-text>
                <div class="form-group">
                    <v-text-field v-model="nombrearea" label="Nombre de la Area" required></v-text-field>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="nuevaArea = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click=" guardarArea()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="nuevoNivel" max-width="500px">
        <v-card>
            <v-card-title>
                <h3 class="box-title">Nuevo Nivel</h3>
                <hr class="m-t-0 m-b-40">
            </v-card-title>
            <v-card-text>
                <div class="form-group">
                    <v-text-field v-model="nombrenivel" label="Nombre del nivel" required></v-text-field>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="nuevoNivel = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click=" guardarNivel()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            search: "",
            paginationI: 10,
            headers: [{
                    text: 'Nombre',
                    value: 'nombreasignatura',
                },
                {
                    text: 'Area',
                    value: 'nombrearea',
                },
                {
                    text: 'Nivel',
                    value: 'nombrenivel',
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ],
            asignaturas: [],
            nivelsS: [],
            areasS: [],
            dialog: false,
            nuevaArea: false,
            nuevoNivel: false,
            editarAsignatura: false,
            nombreasignatura: '',
            area_idarea: '',
            nivel_idnivel: '',
            nombrenivel: '',
            nombrearea: '',
            idasignatura:'',
            errors: []
        };
    },
    mounted: function mounted() {
        this.getasignaturas();
        this.getAreaSelect();
        this.getNivelSelect();
    },
    methods: {
        getasignaturas() {
            let me = this;
            var url = "./asignaturas";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.asignaturas = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getAreaSelect() {
            var _this = this;
            axios.get("./areaS").then(function (response) {
                _this.areasS = response.data;
            });
        },
        getNivelSelect() {
            var _this = this;
            axios.get("./nivelSelect").then(function (response) {
                _this.nivelsS = response.data;
            });
        },
        setguardar() {
            let me = this;
            axios
                .post("./asignaturas", {
                    nombre: me.nombreasignatura,
                    area: me.area_idarea,
                    nivel: me.nivel_idnivel,
                })
                .then(function (response) {
                    me.getasignaturas();
                    me.dialog = false;
                    swal("Datos Guardados","", "success");
                    me.limpiar();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        guardarArea() {
            let me = this;
            axios
                .post("./areas/registrar", {
                    nombrearea: me.nombrearea,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.editarAsignatura = false;
                    me.getAreaSelect();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        guardarNivel() {
            let me = this;
            axios
                .post("./niveles/registrar", {
                    nombrenivel: me.nombrenivel,
                })
                .then(function (response) {
                    swal("Datos Guardados", "", "success");
                    me.getNivelSelect();
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
                .post("asignaturas/eliminar", {
                    idasignatura: id
                })
                .then(function (response) {
                    me.getasignaturas();
                    swal("Registro Eliminado!", {
                        icon: "success"
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getEditar(item) {
            let me = this;
            me.idasignatura = item.idasignatura;
            me.nombreasignatura = item.nombreasignatura;
            me.area_idarea = item.area_idarea;
            me.nivel_idnivel = item.nivel_idnivel;
        },
        limpiar() {
            let me = this;
            me.idasignatura = '';
            me.nombreasignatura = '';
            me.area_idarea = '';
            me.nivel_idnivel = '';
        },
        updateguardar() {
            let me = this;
            console.log(me.idasignatura);
            axios.patch('./asignaturas/'+me.idasignatura, {
                    id:me.idasignatura,
                    nombre: me.nombreasignatura,
                    area: me.area_idarea,
                    nivel: me.nivel_idnivel
                })
                .then(function (response) {
                    swal("Datos Guardatos","","success");
                    me.getasignaturas();
                    me.editarAsignatura = false;
                    me.limpiar();
                })
                .catch(function (error) {})
        }
    }
};
</script>
