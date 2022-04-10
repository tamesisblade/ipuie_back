<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-toolbar flat>
                <v-tabs v-model="tabs" fixed-tabs>
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab @click="getProlipa" class="primary--text">
                        Prolipa
                    </v-tab>

                    <v-tab @click="getDocente" class="primary--text">
                        Docentes
                    </v-tab>

                    <v-tab @click="getEstudiantes" class="primary--text">
                        Estudiantes
                    </v-tab>
                </v-tabs>
            </v-toolbar>
            <v-tabs-items v-model="tabs">
                <v-tab-item>
                    <v-card flat>
                        <v-card-text>
                            <v-card-title>
                                <v-btn v-if="datoUsuario.id_group == 1" color="primary" dark @click="dialog3 = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="prolipa" :search="search" :items-per-page="paginationI" :loading="procesoT" class="elevation-1" loading-text="Por favor espere...">
                                <template v-slot:item.ingreso="{item}">
                                    <v-chip :color="'pink'" v-if="item.p_ingreso == 0" class="text-xs-left" dark><span>No</span></v-chip>
                                    <v-chip :color="'green'" v-else class="text-xs-left" dark><span>Si</span></v-chip>
                                </template>
                                <template v-slot:item.estado="{item}">
                                    <v-btn v-if="item.estado_idEstado == 1" dark small color="success" @click="desactivar(item.idusuario)">Activo</v-btn>
                                    <v-btn v-else dark small color="error" @click="activar(item.idusuario)">Desactivado</v-btn>
                                </template>
                                <template v-slot:item.acciones="{item}">
                                    <v-menu transition="slide-x-transition" bottom right>
                                        <template v-slot:activator="{ on }">
                                            <v-btn class="deep-orange" color="primary" fab dark small v-on="on">
                                                <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="indigo" @click="dialog4 = true ; getEditar(item)"><i class="fas fa-pencil-alt"></i></v-btn>
                                            <v-btn fab dark small color="teal" @click="dialogg = true ; getInformacionDocente(item) ; getHistorialDocente(item)"><i class="fa fa-info"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="primary" @click="dialog2 = true ; getInformacionDocente(item) ; asignatura(item.idusuario); asignatura_area() "><i class="fas fa-cart-plus"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="error" @click="eliminarDocente(item)"><i class="fa fa-trash"></i></v-btn>
                                        </v-list>
                                    </v-menu>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat>
                        <v-card-text>
                            <v-card-title>
                                <v-btn v-if="datoUsuario.id_group == 1" color="primary" dark @click="dialog3 = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="docentes" :search="search" :items-per-page="paginationI" :loading="procesoT" class="elevation-1" loading-text="Por favor espere...">
                                <template v-slot:item.ingreso="{item}">
                                    <v-chip :color="'pink'" v-if="item.p_ingreso == 0" class="text-xs-left" dark><span>No</span></v-chip>
                                    <v-chip :color="'green'" v-else class="text-xs-left" dark><span>Si</span></v-chip>
                                </template>
                                <template v-slot:item.estado="{item}">
                                    <v-btn v-if="item.estado_idEstado == 1" dark small color="success" @click="desactivar(item.idusuario)">Activo</v-btn>
                                    <v-btn v-else dark small color="error" @click="activar(item.idusuario)">Desactivado</v-btn>
                                </template>
                                <template v-slot:item.acciones="{item}">
                                    <v-menu transition="slide-x-transition" bottom right>
                                        <template v-slot:activator="{ on }">
                                            <v-btn class="deep-orange" color="primary" fab dark small v-on="on">
                                                <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="indigo" @click="dialog4 = true ; getEditar(item)"><i class="fas fa-pencil-alt"></i></v-btn>
                                            <v-btn fab dark small color="teal" @click="dialogg = true ; getInformacionDocente(item) ; getHistorialDocente(item)"><i class="fa fa-info"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="primary" @click="dialog2 = true ; getInformacionDocente(item) ; asignatura(item.idusuario); asignatura_area() "><i class="fas fa-cart-plus"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="error" @click="eliminarDocente(item)"><i class="fa fa-trash"></i></v-btn>
                                        </v-list>
                                    </v-menu>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat>
                        <v-card-text>
                            <v-card-title>
                                <v-btn v-if="datoUsuario.id_group == 1" color="primary" dark @click="dialog3 = true"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="e_estudiante" :items="estudiantes" :search="search" :items-per-page="paginationI" :loading="procesoT" class="elevation-1" loading-text="Por favor espere...">
                                <template v-slot:item.ingreso="{item}">
                                    <v-chip :color="'pink'" v-if="item.p_ingreso == 0" class="text-xs-left" dark><span>No</span></v-chip>
                                    <v-chip :color="'green'" v-else class="text-xs-left" dark><span>Si</span></v-chip>
                                </template>
                                <template v-slot:item.estado="{item}">
                                    <v-btn v-if="item.estado_idEstado == 1" dark small color="success" @click="desactivar(item.idusuario)">Activo</v-btn>
                                    <v-btn v-else dark small color="error" @click="activar(item.idusuario)">Desactivado</v-btn>
                                </template>
                                <template v-slot:item.acciones="{item}">
                                    <v-menu transition="slide-x-transition" bottom right>
                                        <template v-slot:activator="{ on }">
                                            <v-btn class="deep-orange" color="primary" fab dark small v-on="on">
                                                <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="indigo" @click="dialog4 = true ; getEditar(item)"><i class="fas fa-pencil-alt"></i></v-btn>
                                            <v-btn fab dark small color="teal" @click="dialogg = true ; getInformacionDocente(item) ; getHistorialDocente(item)"><i class="fa fa-info"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="primary" @click="dialog2 = true ; getInformacionDocente(item) ; asignatura(item.idusuario); asignatura_area() "><i class="fas fa-cart-plus"></i></v-btn>
                                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="error" @click="eliminarDocente(item)"><i class="fa fa-trash"></i></v-btn>
                                        </v-list>
                                    </v-menu>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>

    <!-- Modal Informacion Usuario -->
    <v-dialog v-model="dialogg" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <v-toolbar dark color="primary">
                <v-btn @click="printdocenteHistorial" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn fab dark color="pink" @click="dialogg = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div id="printMeDH" class="container-fluid">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">Información del Docente</h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Cédula: </label> &nbsp;
                                    <label type="text" class="">{{cedula}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Fecha de Registro: </label> &nbsp;
                                    <label type="text" class="">{{date_created}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Nombre: </label> &nbsp;
                                    <label type="text" class="">{{nombres}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Correo: </label> &nbsp;
                                    <label type="text" class="">{{email}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Apellido: </label> &nbsp;
                                    <label type="text" class="">{{apellidos}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Institución: </label> &nbsp;
                                    <label type="text" class="">{{nombreInstitucion}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Usuario: </label> &nbsp;
                                    <label type="text" class="">{{name_usuario}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class="text-left font-weight-bold">Ingreso a la Plataforma: </label> &nbsp;
                                    <label type="text" class="">{{p_ingresom}}</label>
                                </div>
                            </div>
                            <h3 class="box-title">Asignaturas Activas</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <div class="col col-md-12 text-justify">
                                <template v-for="dato in asignaturadocente">
                                    <v-chip class="py-2" v-bind:key="dato.idasiguser" color="green" outlined>{{dato.nombreasignatura}}</v-chip>
                                </template>
                            </div>
                            <h3 class="box-title">Historial de Accesos</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <div class="row">
                                <b-table show-empty stacked="md" :items="historial" :fields="fieldsH" :current-page="currentPage" :per-page="perPagedn" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection">
                                </b-table>
                            </div>
                        </div>
                    </div>
                </div>

            </v-card-text>
        </v-card>
    </v-dialog>
    <!--Fin Modal Informacion Usuario -->

    <!-- Modal Asignar Elementos Usuario -->
    <v-dialog v-model="dialog2" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <v-toolbar dark color="primary">
                <v-btn @click="printdocente" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn fab dark color="pink" @click="dialog2 = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div id="printMeD" class="container-fluid">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">Información del Docente </h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Cédula: </label> &nbsp;
                                    <label type="text" class="">{{cedula}}</label>
                                </div>
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Fecha de Registro: </label> &nbsp;
                                    <label type="text" class="">{{date_created}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Nombre: </label> &nbsp;
                                    <label type="text" class="">{{nombres}}</label>
                                </div>
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Correo: </label> &nbsp;
                                    <label type="text" class="">{{email}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Apellido: </label> &nbsp;
                                    <label type="text" class="">{{apellidos}}</label>
                                </div>
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Institución: </label> &nbsp;
                                    <label type="text" class="">{{nombreInstitucion}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Usuario: </label> &nbsp;
                                    <label type="text" class="">{{name_usuario}}</label>
                                </div>
                                <div class=" col col-md-6">

                                    <label class="text-left font-weight-bold">Ingreso a la Plataforma: </label> &nbsp;
                                    <label type="text" class="">{{p_ingresom}}</label>
                                </div>
                            </div>
                            <h3 class="box-title">Asignaturas Activas</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <div class="col col-md-12 text-justify">
                                <template v-for="dato in asignaturadocente">
                                    <v-chip class="py-2" v-bind:key="dato.idasiguser" color="green" outlined>{{dato.nombreasignatura}}</v-chip>
                                </template>
                            </div>
                            <h3 class="box-title">Asignaturas</h3>
                            <hr class="m-t-0 m-b-40">
                            <v-flex xs12 lg12 mb-3>
                                <div class="row" color="transparent">
                                    <v-spacer></v-spacer>
                                    <v-btn dark color="green" @click="guardarAsignatura">
                                        Guardar
                                    </v-btn>
                                </div>
                                <v-treeview selectable hoverable :items="area" v-model="asignaturadocenteselect"></v-treeview>
                            </v-flex>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!--Fin Modal Asignar Elementos Usuario -->

    <!-- Nuevo Docente -->
    <v-dialog v-model="dialog3" persistent max-width="800px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-user"></i> Docente Nuevo</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="dialog3 = false"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field v-model="cedula" label="Cédula" required></v-text-field>
                            <span v-if="errors.cedula" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.cedula[0]}}
                            </span>
                            <div class="row">
                                <div class="col col-md-6">
                                    <v-text-field v-model="nombres" label="Nombres" required></v-text-field>
                                    <span v-if="errors.nombres" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.nombres[0]}}
                                    </span>
                                </div>
                                <div class="col col-md-6">
                                    <v-text-field v-model="apellidos" label="Apellidos" required></v-text-field>
                                    <span v-if="errors.apellidos" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.apellidos[0]}}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <v-text-field v-model="email" label="Correo" required></v-text-field>
                                    <span v-if="errors.email" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.email[0]}}
                                    </span>
                                </div>
                                <div class="col col-md-6">
                                    <v-text-field v-model="name_usuario" label="Usuario" required></v-text-field>
                                    <span v-if="errors.name_usuario" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.name_usuario[0]}}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-12">
                                    <label class="">Institución</label>
                                    <v-select :options="institucion" value="idInstitucion" :reduce="institucion => institucion.idInstitucion" label="nombreInstitucion" v-model="institucion_idInstitucion">
                                    </v-select>
                                    <span v-if="errors.institucion_idInstitucion" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.institucion_idInstitucion[0]}}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-12">
                                    <label class="">Grupo</label>
                                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="id_group" id="id_group">
                                        <option></option>
                                        <option v-for="item in grupo" v-bind:key="item.id" v-bind:value="item.id">{{ item.deskripsi }}
                                        </option>
                                    </select>
                                    <span v-if="errors.id_group" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.id_group[0]}}
                                    </span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guardarDocente()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Nuevo Docente -->

    <!-- Editar Docente -->
    <v-dialog v-model="dialog4" persistent max-width="800px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-pencil"></i> Editar Docente</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="dialog4 = false"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <input v-model="idusuario" required hidden>
                    <v-text-field v-model="cedulae" label="Cédula" required></v-text-field>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <v-text-field v-model="nombrese" label="Nombres" required></v-text-field>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <v-text-field v-model="apellidose" label="Apellidos" required></v-text-field>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <v-text-field v-model="emaile" label="Correo" required></v-text-field>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <v-text-field v-model="name_usuarioe" label="Usuario" required></v-text-field>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">Institución</label>
                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="institucion_idInstitucione" disabled>
                        <option v-for="item in institucion" v-bind:key="item.idInstitucion" v-bind:value="item.idInstitucion">{{ item.nombreInstitucion }}
                        </option>
                    </select>
                    <v-select :options="institucion" value="idInstitucion" :reduce="institucion => institucion.idInstitucion" label="nombreInstitucion" v-model="institucion_idInstitucione">
                    </v-select>
                </div>
                <div class="form-group">
                    <label class="">Grupo</label>
                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="id_groupe">
                        <option></option>
                        <option v-for="item in grupo" v-bind:key="item.id" v-bind:value="item.id">{{ item.deskripsi }}
                        </option>
                    </select>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click="editarDocente()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Docente -->
</v-app>
</template>

<script>
import io from 'socket.io-client'
export default {
    data: function () {
        return {
            tabs: null,
            procesoT: false,
            paginationI: 10,
            dialogg: false,
            asignaturadocente: [],
            asignaturaguarda: [],
            asignaturasnuevas: [],
            usuario: [],
            prolipa:[],
            docentes:[],
            estudiantes:[],
            institucion: [],
            grupo: [],
            area: [],
            asignaturas: [],
            institucion_idInstitucion: "",
            id_group: "",
            // Variables de edicion de Docente
            cedulae: "",
            nombrese: "",
            apellidose: "",
            emaile: "",
            name_usuarioe: "",
            institucion_idInstitucione: "",
            id_groupe: "",
            pagination: {},
            search: "",
            headers: [{
                    text: 'Cédula',
                    value: 'cedula'
                },
                {
                    text: 'Nombre',
                    value: 'nombres'
                },
                {
                    text: 'Apellido',
                    value: 'apellidos'
                },
                {
                    text: 'Usuario',
                    value: 'name_usuario'
                },
                {
                    text: 'Correo',
                    value: 'email'
                },
                {
                    text: 'Ingreso al Sistema',
                    value: 'ingreso'
                },
                {
                    text: 'Estado',
                    value: 'estado'
                },
                {
                    text: 'Acciones',
                    class: 'acciones',
                    value: 'acciones'
                },
            ],
            e_estudiante: [{
                    text: 'Cédula',
                    value: 'cedula'
                },
                {
                    text: 'Nombre',
                    value: 'nombres'
                },
                {
                    text: 'Apellido',
                    value: 'apellidos'
                },
                {
                    text: 'Usuario',
                    value: 'name_usuario'
                },
                {
                    text: 'Institución',
                    value: 'nombreInstitucion'
                },
                {
                    text: 'Acciones',
                    class: 'acciones',
                    value: 'acciones'
                },
            ],
            cedula: "",
            nombres: "",
            apellidos: "",
            email: "",
            name_usuario: "",
            date_created: "",
            nombreInstitucion: "",
            p_ingreso: "",
            p_ingresom: "",
            historial: [],
            fieldsH: [
                // { key: 'numero', label:'Nº', sortable: true },
                {
                    key: 'ip',
                    label: 'IP',
                    sortable: true
                },
                {
                    key: 'navegador',
                    label: 'Navegador',
                    sortable: true
                },
                {
                    key: 'hora_ingreso_usuario',
                    label: 'Registro de Ingreso',
                    sortable: true
                },
            ],
            pageOptions: [5, 10, 15],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            sortBy: null,
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            perPagedn: 1000,
            dialog2: false,
            dialog3: false,
            dialog4: false,
            idusuario: "",
            errors: [],
            socket: io('https://prolipadigital.com.ec:8001'),
            datoUsuario: [],
            asignaturadocenteselect: []
        };
    },

    mounted: function mounted() {
        this.datosUsuario();
        this.getProlipa();
        this.getInstitucionSelect();
        this.getGrupoSelect();
    },
    methods: {
        getProlipa() {
            let me = this;
            this.procesoT = true;
            var url = "./prolipa";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.prolipa = respuesta;
                    me.procesoT = false;
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        getDocente() {
            let me = this;
            this.procesoT = true;
            var url = "./docente";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.docentes = respuesta;
                    me.procesoT = false;
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        getEstudiantes() {
            let me = this;
            this.procesoT = true;
            var url = "./estudiantes";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.estudiantes = respuesta;
                    me.procesoT = false;
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        getInstitucionSelect() {
            var _this = this;
            axios.get("./institucionSelect").then(function (response) {
                _this.institucion = response.data;
            });
        },
        getGrupoSelect() {
            var _this = this;
            axios.get("./rolSelect").then(function (response) {
                _this.grupo = response.data;
            });
        },
        getAreaSelect() {
            let me = this;
            axios.get("./areaSelect").then(function (response) {
                me.area = response.data.items;
                console.log(me.area);
            });
        },
        getAsignaturaSelect() {
            let me = this;
            axios.get("./asignaturaSelect").then(function (response) {
                me.asignaturas = response.data;
            });
        },
        registrarUsuario() {
            let me = this;
            axios.post("./usuarios/registrar", {
                cedula: this.cedula,
                nombres: this.nombres,
                apellidos: this.apellidos,
                email: this.email,
                name_usuario: this.name_usuario,
                institucion_idinstitucion: this.institucion_idInstitucion,
                id_group: this.id_group
            }).then(function (response) {
                me.getUsuario(1, '', 'cedula');
                $("#nuevo").modal("hide");
            }).catch(function (error) {
                console.log(error);
            });
        },
        listaAsignaturas(idasignatura) {
            var idusuario = $("#idusuario").val()
            $("#" + idasignatura).removeAttr('checked');
            $("#" + idasignatura).hide();
            $("." + idasignatura).hide();
        },
        guardarAsignatura() {
            let me = this;
            axios.post("./asignatura/rasignaturadocente", {
                usuario_idusuario: me.idusuario,
                asignaturas: me.asignaturadocenteselect,
            }).then(function (response) {
                me.asignatura(me.idusuario);
                me.asignaturaguarda = [];
                me.getAreaSelect();
            }).catch(function (error) {
                console.log(error);
            });
        },
        eliminarAsignatura(item, nombre, idusuario) {
            let me = this;
            var _this = this;
            swal({
                title: "Seguro desea Eliminar " + nombre + " ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios.post("./asignatura/eliminar", {
                        asignatura_idasignatura: item
                    }).then(function (response) {
                        me.asignatura(idusuario);
                        swal("¡Registro Eliminado!", "", "success");
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    swal("¡Cancelado!");
                }
            });
        },
        getInformacionDocente(item) {
            let me = this;
            me.idusuario = item.idusuario;
            me.asignatura(item.idusuario);
            if (item.cedula != null) {
                me.cedula = item.cedula;
            }
            if (item.nombres != null) {
                me.nombres = item.nombres;
            }
            if (item.apellidos != null) {
                me.apellidos = item.apellidos;
            }
            if (item.email != null) {
                me.email = item.email;
            }
            if (item.name_usuario != null) {
                me.name_usuario = item.name_usuario;
            }
            if (item.p_ingreso != null) {
                if (item.p_ingreso == 0) {
                    me.p_ingresom = "NO";
                    console.log(me.p_ingreso);
                } else {
                    me.p_ingresom = "SI";
                }
            }
            if (item.date_created != null) {
                me.date_created = item.date_created;
            }
            if (item.nombreInstitucion != null) {
                me.nombreInstitucion = item.nombreInstitucion;
            }

        },
        asignatura(idusuario) {
            let me = this;
            var url = "./asignaturadocente?idusuario=" + idusuario;
            axios.get(url).then(function (response) {
                    me.asignaturadocente = response.data;
                    me.asignaturadocenteselect = [];
                    response.data.forEach(element => {
                        me.asignaturadocenteselect.push(element.idasignatura);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        asignatura_area() {
            let me = this;
            me.getAreaSelect();
            me.getAsignaturaSelect();
        },
        getHistorialDocente(item) {
            let me = this;
            var url = "./historial?idusuario=" + item.idusuario;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.historial = response.data;
                    console.log(respuesta);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        printdocente() {
            $(".acciones").hide();
            this.$htmlToPaper('printMeD');
            $(".acciones").show();
        },
        printdocenteHistorial() {
            $(".acciones").hide();
            this.$htmlToPaper('printMeDH');
            $(".acciones").show();
        },
        guardarDocente() {
            let me = this;
            axios.post("./usuarios/registrar?email=" + me.email + "&usuario=" + me.name_usuario + "&cedula=" + me.cedula, {
                cedula: me.cedula,
                nombres: me.nombres,
                apellidos: me.apellidos,
                email: me.email,
                name_usuario: me.name_usuario,
                id_group: me.id_group,
                institucion_idInstitucion: me.institucion_idInstitucion
            }).then(function (response) {
                me.dialog3 = false;
                swal("Usuario Registrado", " " + me.cedula + me.nombres, "success");
                me.getUsuario();
                me.socket.emit('GuardarUsuario', {
                    cedula: me.cedula,
                    nombres: me.nombres,
                    apellidos: me.apellidos,
                    email: me.email,
                    name_usuario: me.name_usuario,
                    id_group: me.id_group,
                    institucion_idInstitucion: me.institucion_idInstitucion
                });
                me.cedula = "";
                me.apellidos = "";
                me.nombres = "";
                me.email = "";
                me.name_usuario = "";
                me.id_group = "";
                me.institucion_idInstitucion = "";
            }).catch(function (error) {
                if (error.response.status == 422) {
                    me.errors = error.response.data.errors;
                }
            });
        },
        getEditar(item) {
            this.idusuario = item.idusuario;
            this.cedulae = item.cedula;
            this.nombrese = item.nombres;
            this.apellidose = item.apellidos;
            this.emaile = item.email;
            this.name_usuarioe = item.name_usuario;
            this.institucion_idInstitucione = item.institucion_idInstitucion;
            this.id_groupe = item.id_group;
        },
        editarDocente() {
            let me = this;
            axios.post("./usuarios/editar?email=" + me.emaile + "&usuario=" + me.name_usuarioe + "&cedula=" + me.cedulae, {
                idusuario: me.idusuario,
                cedula: me.cedulae,
                nombres: me.nombrese,
                apellidos: me.apellidose,
                email: me.emaile,
                name_usuario: me.name_usuarioe,
                id_group: me.id_groupe,
                institucion_idInstitucion: me.institucion_idInstitucione
            }).then(function (response) {
                me.dialog4 = false;
                me.idusuario = "";
                me.cedulae = "";
                me.apellidose = "";
                me.nombrese = "";
                me.emaile = "";
                me.name_usuarioe = "";
                me.id_groupe = "";
                me.institucion_idInstitucione = "";
                swal("Usuario Actualizado", " " + me.cedula + me.nombres, "success");
                me.getUsuario();
            }).catch(function (error) {
                console.log(error);
                swal("", error, "error");
            });
        },
        activar(idusuario) {
            let me = this;
            var url = "./usuarioActivar?idusuario=" + idusuario;
            axios.get(url).then(function (response) {
                    me.getUsuario();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        desactivar(idusuario) {
            let me = this;
            var url = "./usuarioDesactivar?idusuario=" + idusuario;
            axios.get(url).then(function (response) {
                    me.getUsuario();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.datoUsuario = response.data[0];
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        eliminarDocente(item) {
            let me = this;
            swal({
                title: "Seguro desea eliminar el usuario ?" + item.email,
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios
                        .post("usuario/eliminar", {
                            idusuario: item.idusuario
                        })
                        .then(function (response) {
                            me.getUsuario();
                            swal("Registro Eliminado!", {
                                icon: "success"
                            });
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {
                    swal("Cancelado!");
                }
            });
        }
    }
};
</script>
