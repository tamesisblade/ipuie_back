<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-card-title>
                <h3 class="text-themecolor"><i class="fa fa-university"></i> Institución</h3>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card>
                <v-card-title>
                    <v-btn v-if="datoUsuario.id_group == 1" color="primary" dark @click="dialog2 = true ; limpiarDatosInstitucion()">Nueva Institución</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="printInstituciones" fab dark color="primary">
                        <i class="fa fa-print"></i>
                    </v-btn>
                </v-card-title>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <div id="printIns">
                    <v-data-table :headers="headers" :items="institucion" :search="search" class="elevation-1" :items-per-page="paginationI" :loading="procesoT" loading-text="Por favor espere...">
                        <template v-slot:item.acciones="{item}">
                            <v-btn v-if="datoUsuario.id_group == 8" fab dark small color="indigo" @click="getInstitucionDato(item) ; editarInstitucion = true"><i class="fa fa-pencil"></i></v-btn>
                            <v-btn v-if="datoUsuario.id_group == 1" fab dark small color="indigo" @click="getInstitucionDato(item) ; editarInstitucion = true"><i class="fa fa-pencil"></i></v-btn>
                            <v-btn v-if="datoUsuario.idusuario == 3801" fab dark small color="indigo" @click="getInstitucionDato(item) ; editarInstitucion = true"><i class="fa fa-pencil"></i></v-btn>
                            <v-btn v-if="datoUsuario.idusuario == 3802" fab dark small color="indigo" @click="getInstitucionDato(item) ; editarInstitucion = true"><i class="fa fa-pencil"></i></v-btn>
                            <v-btn v-if="datoUsuario.idusuario == 3761" fab dark small color="indigo" @click="getInstitucionDato(item) ; editarInstitucion = true"><i class="fa fa-pencil"></i></v-btn>
                            <v-btn fab dark small color="teal" @click="dialog = true ; getDocentes(item.idInstitucion) ; getInformacion(item)"><i class="fa fa-info"></i></v-btn>
                            <v-btn fab dark small color="cyan" @click="dialog3 = true ; getHistorial(item.idInstitucion) ; getInformacion(item); getReporte(item.idInstitucion)"><i class="fa fa-address-card-o"></i></v-btn>
                        </template>
                    </v-data-table>
                </div>
            </v-card>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
    <!-- Modal Informacion -->
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn @click="print" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>

                <v-btn fab small dark color="pink" @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>

            </v-toolbar>
            <v-card-text>
                <div id="printMe" class="container-fluid">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">Información de la institución</h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Nombre: </label> &nbsp;
                                    <label type="text" class="">{{nombreInstitucion.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Dirección: </label> &nbsp;
                                    <label type="text" class="">{{direccionInstitucion.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Región: </label> &nbsp;
                                    <label type="text" class="">{{nombreregion.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Ciudad: </label> &nbsp;
                                    <label type="text" class="">{{nombreciudad.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Vendedor: </label> &nbsp;
                                    <label type="text" class="">{{nombrevendedor.toUpperCase()+" "+apellidovendedor.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Teléfono: </label> &nbsp;
                                    <label type="text" class="">{{telefonoInstitucion}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Solicitud: </label> &nbsp;
                                    <label type="text" class="">{{solicitudInstitucion}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Periodo Escolar: </label> &nbsp;
                                    <label type="text" class="">{{periodoescolar}}</label>
                                </div>
                            </div>
                            <h3 class="box-title">Docentes</h3>
                            <hr class="m-t-0 m-b-40">
                            <!--/row-->
                            <v-card-title class="acciones">
                                <div class="flex-grow-1"></div>
                                <v-text-field v-model="buscarDocente" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="TablaDocente" :items="docentes" :items-per-page="paginationI" :search="buscarDocente" :loading="Tproceso">
                                <template v-slot:item.acciones="{item}">
                                    <v-btn class="acciones" v-if="datoUsuario.id_group == 1" fab dark small color="indigo" @click="dialog10 = true ; getEditar(item)"><i class="fas fa-pencil-alt"></i></v-btn>
                                    <button class="acciones btn btn-info" @click="dialogg = true ; getInformacionDocente(item) ; asignatura(item.idusuario) ; getHistorialDocente(item.idusuario)"><i class="fa fa-info"></i></button>
                                    <v-btn class="acciones" v-if="datoUsuario.id_group == 1" fab dark small color="primary" @click="dialog9 = true ; getInformacionDocente(item) ; asignatura(item.idusuario) "><i class="fas fa-cart-plus"></i></v-btn>
                                </template>
                            </v-data-table>

                            <!-- Modal Informacion Usuario -->
                            <v-dialog v-model="dialogg" fullscreen hide-overlay transition="dialog-bottom-transition">
                                <v-card tile>
                                    <v-toolbar card dark color="primary">
                                        <v-btn @click="printdocente" fab dark color="teal">
                                            <i class="fa fa-print"></i>
                                        </v-btn>
                                        <v-spacer></v-spacer>

                                        <v-btn fab dark color="pink" @click="dialogg = false">
                                            <i class="fa fa-times"></i>
                                        </v-btn>

                                    </v-toolbar>
                                    <v-card-text>
                                        <div id="printMeD" class="container-fluid">
                                            <div class="card-body">
                                                <div class="form-body">
                                                    <h3 class="box-title">Información del docente</h3>
                                                    <hr class="m-t-0 m-b-40">
                                                    <div class="row">
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Cédula: </label> &nbsp;
                                                            <label type="text" class="">{{cedula}}</label>
                                                        </div>
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Fecha de Registro: </label> &nbsp;
                                                            <label type="text" class="">{{date_created.toUpperCase()}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Nombre: </label> &nbsp;
                                                            <label type="text" class="">{{nombres.toUpperCase()}}</label>
                                                        </div>
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Correo: </label> &nbsp;
                                                            <label type="text" class="">{{email}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Apellido: </label> &nbsp;
                                                            <label type="text" class="">{{apellidos.toUpperCase()}}</label>
                                                        </div>
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Institución: </label> &nbsp;
                                                            <label type="text" class="">{{nombreInstitucion.toUpperCase()}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Usuario: </label> &nbsp;
                                                            <label type="text" class="">{{name_usuario.toUpperCase()}}</label>
                                                        </div>
                                                        <div class="col col-md-6">
                                                            <label class=" text-left font-weight-bold">Ingreso a la Plataforma: </label> &nbsp;
                                                            <label type="text" class="">{{p_ingreso}}</label>
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
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!--Fin Modal Informacion Institucion-->

    <!-- Modal Accesos -->
    <v-dialog v-model="dialog3" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn @click="printAcceso" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>

                <v-btn fab dark color="pink" @click="dialog3 = false">
                    <i class="fa fa-times"></i>
                </v-btn>

            </v-toolbar>
            <v-card-text>
                <div id="printAcceso">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">Información de la institución</h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Nombre :</label> &nbsp;
                                    <label type="text" class="">{{nombreInstitucion.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Dirección :</label> &nbsp;
                                    <label type="text" class="">{{direccionInstitucion.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Región :</label> &nbsp;
                                    <label type="text" class="">{{nombreregion.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Ciudad :</label> &nbsp;
                                    <label type="text" class="">{{nombreciudad.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Vendedor :</label> &nbsp;
                                    <label type="text" class="">{{nombrevendedor.toUpperCase()+" "+apellidovendedor.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Teléfono :</label> &nbsp;
                                    <label type="text" class="">{{telefonoInstitucion}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Solicitud :</label> &nbsp;
                                    <label type="text" class="">{{solicitudInstitucion}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Periodo Escolar :</label> &nbsp;
                                    <label type="text" class="">{{periodoescolar}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">TOTAL DE INGRESOS :</label> &nbsp;
                                    <label type="text" class=""><b>{{historialacc.length}}</b></label>
                                </div>
                            </div>
                            <v-tabs dark>
                                <v-tab class="acciones">
                                    Historial Visitas
                                </v-tab>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <b-table show-empty stacked="md" :items="reported" :fields="fieldsD" :current-page="currentPage" :per-page="perPagedn" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection">
                                            </b-table>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                                <v-tab>
                                    Historial de Accesos
                                </v-tab>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <div class="row">
                                                <b-table show-empty stacked="md" :items="historialacc" :fields="fieldsHH" :current-page="currentPage" :per-page="perPagedn" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection">
                                                </b-table>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!-- Editar Institucion -->
    <v-dialog v-model="editarInstitucion" persistent max-width="900px">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <span class="headline">
                    <i class="fa fa-university"></i> &nbsp; Editar
                </span>
                <v-spacer></v-spacer>
                <v-btn fab dark color="pink" @click="editarInstitucion = false ; errors = []">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="form-group form-horizontal form-material">
                    <label class="">Nombre</label>
                    <input type="text" class="form-control form-control-line" v-model="nombreInstitucion" require>
                    <input type="text" class="form-control form-control-line" v-model="idInstitucion" hidden>
                    <span v-if="errors.nombre" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.nombre[0]}}
                    </span>
                </div>
                <div class="form-group form-horizontal form-material">
                    <label class="">Teléfono</label>
                    <input type="text" class="form-control form-control-line" v-model="telefonoInstitucion" require>
                    <span v-if="errors.telefono" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.telefono[0]}}
                    </span>
                </div>
                <div class="form-group form-horizontal form-material">
                    <label class="">Dirección</label>
                    <input type="text" class="form-control form-control-line" v-model="direccionInstitucion" require>
                    <span v-if="errors.direccion" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.direccion[0]}}
                    </span>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Solicitud</label>
                        <input type="text" class="form-control form-control-line" v-model="solicitudInstitucion" require>
                        <span v-if="errors.solicitud" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.solicitud[0]}}
                        </span>
                    </div>
                    <div class="form-group col col-md-6">
                        <label class="">Vendedor</label>
                        <v-select :options="selectVendedor" :reduce="selectVendedor => selectVendedor.cedula" label="nombre" v-model="vendedorInstitucionn">
                            <template slot="option" slot-scope="option">
                                {{ option.nombre }}
                            </template>
                        </v-select>
                        <span v-if="errors.vendedor" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.vendedor[0]}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col col-md-6">
                        <label class="">Ciudad</label>
                        <v-select :options="ciudad" :reduce="ciudad => ciudad.idciudad" label="nombre" v-model="idciudad">
                            <template slot="option" slot-scope="option">
                                {{ option.nombre.toUpperCase() }}
                            </template>
                        </v-select>
                        <span v-if="errors.ciudad" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.ciudad[0]}}
                        </span>
                    </div>
                    <div class="form-group col col-md-6">
                        <label class="">Región</label>
                        <v-select :options="region" :reduce="region => region.idregion" label="nombreregion" v-model="region_idregion">
                            <template slot="option" slot-scope="option">
                                {{ option.nombreregion.toUpperCase() }}
                            </template>
                        </v-select>
                        <span v-if="errors.region" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.region[0]}}
                        </span>
                    </div>
                    <div class="form-horizontal form-material col col-md-6">
                        <label for="">Fecha de Registro</label>
                        <input class="form-control" type="text" name="" value="" v-model="fecha_registro" disabled>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guardar"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Nueva Institucion -->

    <!-- Nueva Institucion -->
    <v-dialog v-model="dialog2" persistent max-width="900px">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <span class="headline">
                    <i class="fa fa-university"></i> &nbsp; Nueva Institución
                </span>
                <v-spacer></v-spacer>

                <v-btn fab small dark color="pink" @click="dialog2 = false">
                    <i class="fa fa-times"></i>
                </v-btn>

            </v-toolbar>
            <v-card-text>
                <div class="card-body">
                    <v-text-field v-model="nombreInstitucion" label="Nombre" required></v-text-field>
                    <span v-if="errors.nombre" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.nombre[0]}}
                    </span>
                    <div class="row">
                        <div class="col col-md-6">
                            <label>
                                Región
                            </label>
                            <div class="col-md-9">
                                <v-select :options="region" label="nombreregion" v-model="region_idregion">
                                    <template slot="option" slot-scope="option">
                                        {{ option.nombreregion.toUpperCase() }}
                                    </template>
                                </v-select>
                            </div>
                            <span v-if="errors.region" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.region[0]}}
                            </span>
                        </div>
                        <div class="col col-md-6">
                            <label>
                                Ciudad
                            </label>
                            <v-select class="col col-md-9" :options="ciudad" label="nombre" v-model="ciudad_id">
                                <template slot="option" slot-scope="option">
                                    {{ option.nombre.toUpperCase() }}
                                </template>
                            </v-select>
                            <span v-if="errors.ciudad" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.ciudad[0]}}
                            </span>
                        </div>
                    </div>
                    <v-text-field v-model="direccionInstitucion" label="Dirección" required></v-text-field>
                    <span v-if="errors.direccion" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.direccion[0]}}
                    </span>
                    <!--/row-->
                    <div class="row">
                        <div class="col col-md-6">
                            <v-text-field v-model="solicitudInstitucion" label="Solicitud" required></v-text-field>
                            <span v-if="errors.solicitud" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.solicitud[0]}}
                            </span>
                        </div>
                        <div class="col col-md-6">
                            <v-text-field v-model="telefonoInstitucion" label="Teléfono" required></v-text-field>
                            <span v-if="errors.telefono" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.telefono[0]}}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6">
                            <label>Vendedor</label>
                            <div class="col-md-12">
                                <v-select :options="usuario" label="apellidos" v-model="vendedorInstitucion">
                                    <template slot="option" slot-scope="option">
                                        {{ option.cedula+" "+option.nombres.toUpperCase()+" "+option.apellidos.toUpperCase() }}
                                    </template>
                                </v-select>
                            </div>
                            <span v-if="errors.vendedor" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.vendedor[0]}}
                            </span>
                        </div>

                        <div class="col col-md-6">
                            <div class="row">
                                <div class="col col-md-6">
                                    <v-icon color="green" @click="dialog8 = true">add</v-icon>
                                    &nbsp; Periodo Escolar
                                </div>
                                <div class="col col-md-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-12">
                                    <v-select :options="periodo" label="descripcion" v-model="periodoescolar_idperiodoescolar">
                                        <template slot="option" slot-scope="option">
                                            {{ option.fecha_inicial+" "+option.fecha_final+" "+option.nombreregion.toUpperCase() }}
                                        </template>
                                    </v-select>
                                    <span v-if="errors.periodo_escolar" class="error--text">
                                        <v-icon color="error">warning</v-icon>
                                        {{errors.periodo_escolar[0]}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guardarInstitucion()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Nueva Institucion -->

    <!-- Nuevo Docente -->
    <v-dialog v-model="dialog4" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-user"></i> Docente Nuevo</span>
            </v-card-title>
            <v-card-text>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <label class="">Cedula</label>
                    <input type="text" class="form-control form-control-line" v-model="cedula" require>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Nombres</label>
                        <input type="text" class="form-control form-control-line" v-model="nombres" require>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Apellidos</label>
                        <input type="text" class="form-control form-control-line" v-model="apellidos" require>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Correo</label>
                        <input type="email" class="form-control form-control-line" v-model="email" require>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Usuario</label>
                        <input type="text" class="form-control form-control-line" v-model="name_usuario" require>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">Grupo</label>
                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="id_group" id="id_group">
                        <option></option>
                        <option v-for="item in grupo" v-bind:key="item.id" v-bind:value="item.id">{{ item.deskripsi }}
                        </option>
                    </select>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="dialog4 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click="guardarDocente()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Nuevo Docente -->
    <!-- Editar Docente -->
    <v-dialog v-model="dialog5" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-user"></i> Editar Docente</span>
            </v-card-title>
            <v-card-text>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <label class="">Cedula</label>
                    <input type="text" class="form-control form-control-line" v-model="cedulae" require>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Nombres</label>
                        <input type="text" class="form-control form-control-line" v-model="nombrese" require>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Apellidos</label>
                        <input type="text" class="form-control form-control-line" v-model="apellidose" require>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Correo</label>
                        <input type="email" class="form-control form-control-line" v-model="emaile" require>
                    </div>
                    <div class="form-group form-horizontal form-material col col-md-6">
                        <label class="">Usuario</label>
                        <input type="text" class="form-control form-control-line" v-model="USERNAMEe" require>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">Grupo</label>
                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="id_groupe" id="id_group">
                        <option></option>
                        <option v-for="item in grupo" v-bind:key="item.id" v-bind:value="item.id">{{ item.deskripsi }}
                        </option>
                    </select>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="dialog5 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click=" guardareditato()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Docente -->
    <!-- Nueva Region -->
    <v-dialog v-model="dialog6" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-map"></i> Nueva Región</span>
            </v-card-title>
            <v-card-text>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <label class="">Nombre de la Region</label>
                    <input type="text" class="form-control form-control-line" v-model="nombreregion" require>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="dialog6 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click=" guardarregion()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Docente -->
    <!-- Nueva Ciudad -->
    <v-dialog v-model="dialog7" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-map-marker"></i> Nueva Ciudad</span>
            </v-card-title>
            <v-card-text>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <label class="">Provincia</label>
                    <input type="text" class="form-control form-control-line" v-model="nombre" require>
                </div>
                <div class="form-group form-horizontal form-material row col col-md-6">
                    <label class="">Nombre de la Ciudad</label>
                    <input type="text" class="form-control form-control-line" v-model="nombre" require>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="dialog7 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click="guardarregion()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Docente -->

    <!-- Nuevo Periodo -->
    <v-dialog v-model="dialog8" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-level-up"></i> Nuevo Periodo</span>
            </v-card-title>
            <v-card-text>
                <div class="col col-md-12 row">
                    <div class="form-group form-horizontal form-material row col col-md-6">
                        <label class="">Fecha Inicial</label>
                        <input type="date" class="form-control form-control-line" v-model="fecha_inicial" require>
                    </div>
                    <div class="form-group form-horizontal form-material row col col-md-6">
                        <label class="">Fecha Final</label>
                        <input type="date" class="form-control form-control-line" v-model="fecha_final" require>
                    </div>
                </div>
                <div class="form-group form-horizontal form-material row col col-md-12">
                    <label class="">Region</label>
                    <select class="form-control" v-model="region_idregionp">
                        <option value="0" disabled>Seleccione</option>
                        <option v-for="datoc in region" :key="datoc.idregion" :value="datoc.idregion" v-text="datoc.nombreregion"></option>
                    </select>
                </div>
                <div class="form-group form-horizontal form-material row col col-md-12">
                    <label class="">Descripción</label>
                    <input type="text" class="form-control form-control-line" v-model="descripcion" require>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn fab small color="pink" @click="dialog8 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click=" guardarPeriodo()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Modal Asignar Elementos Usuario -->
    <v-dialog v-model="dialog9" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn @click="printdocente" fab dark color="teal">
                    <i class="fa fa-print"></i>
                </v-btn>
                <v-spacer></v-spacer>

                <v-btn fab dark color="pink" @click="dialog9 = false">
                    <i class="fa fa-times"></i>
                </v-btn>

            </v-toolbar>
            <v-card-text>
                <div id="printMeD" class="container-fluid">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="box-title">Información del Docente</h3>
                            <hr class="m-t-0 m-b-40">
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Cédula: </label> &nbsp;
                                    <label type="text" class="">{{cedula}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Fecha de Registro: </label> &nbsp;
                                    <label type="text" class="">{{date_created.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Nombre: </label> &nbsp;
                                    <label type="text" class="">{{nombres.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Correo: </label> &nbsp;
                                    <label type="text" class="">{{email}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Apellido: </label> &nbsp;
                                    <label type="text" class="">{{apellidos.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Institución: </label> &nbsp;
                                    <label type="text" class="">{{nombreInstitucion.toUpperCase()}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Usuario: </label> &nbsp;
                                    <label type="text" class="">{{name_usuario.toUpperCase()}}</label>
                                </div>
                                <div class="col col-md-6">
                                    <label class=" text-left font-weight-bold">Ingreso a la Plataforma: </label> &nbsp;
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

    <!-- Editar Docente -->
    <v-dialog v-model="dialog10" max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline"><i class="fa fa-user"></i> Docente Editar</span>
            </v-card-title>
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
                        <v-text-field v-model="USERNAMEe" label="Usuario" required></v-text-field>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">Institución</label>
                    <select class="selectpicker form-control" data-show-subtext="true" data-live-search="true" v-model="institucion_idInstitucione" disabled>
                        <option v-for="item in institucion" v-bind:key="item.idInstitucion" v-bind:value="item.idInstitucion">{{ item.nombreInstitucion.toUpperCase() }}
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
                <v-btn fab small color="pink" @click="dialog10 = false"><i class="fa fa-times"></i></v-btn>
                <v-spacer></v-spacer>
                <v-btn fab small color="teal" @click="editarDocente()"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Fin Editar Docente -->

</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            asignaturadocenteselect: [],
            reported: [],
            selectVendedor: [],
            editarInstitucion: false,
            sortBy: 'idInstitucion',
            sortDesc: false,
            // Tabla Institucion
            paginationI: 10,
            headers: [{
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
                    value: 'nombres',
                    value: 'apellidos'
                },
                {
                    text: 'Solicitud',
                    value: 'solicitudInstitucion'
                },
                {
                    text: 'Acciones',
                    class: 'acciones',
                    value: 'acciones'
                }
            ],
            headersu: [{
                    text: 'Cedula',
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
                    value: 'name_usuario',
                },
                {
                    text: 'Correo',
                    value: 'email'
                },
                {
                    text: 'Acciones',
                },
            ],
            search: '',
            fieldsd: [
                // { key: 'numero', label:'Nº', sortable: true },
                {
                    key: 'cedula',
                    label: 'Cédula',
                    sortable: true
                },
                {
                    key: 'nombres',
                    label: 'Nombre',
                    sortable: true
                },
                {
                    key: 'apellidos',
                    label: 'Apellido',
                    sortable: true
                },
                {
                    key: 'name_usuario',
                    label: 'Usuario',
                    sortable: true
                },
                {
                    key: 'email',
                    label: 'Correo',
                    sortable: true
                },
                {
                    key: 'actions',
                    label: 'Acciones',
                    sortable: true,
                    class: 'acciones'
                },
            ],
            buscarDocente: '',
            TablaDocente: [
                // { key: 'numero', label:'Nº', sortable: true },
                {
                    text: 'Cédula',
                    value: 'cedula',
                },
                {
                    text: 'Nombre',
                    value: 'nombres',
                },
                {
                    text: 'Apellido',
                    value: 'apellidos',
                },
                {
                    text: 'Usuario',
                    value: 'name_usuario',
                },
                {
                    text: 'Correo',
                    value: 'email',
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                },
            ],
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
            fieldsHH: [
                // { key: 'numero', label:'Nº', sortable: true },
                {
                    key: 'cedula',
                    label: 'Cédula',
                    sortable: true
                },
                {
                    key: 'nombres',
                    label: 'Nombre',
                    sortable: true
                },
                {
                    key: 'apellidos',
                    label: 'Apellido',
                    sortable: true
                },
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
            fieldsD: [
                // { key: 'numero', label:'Nº', sortable: true },
                {
                    key: 'cedula',
                    label: 'Cédula',
                    sortable: true
                },
                {
                    key: 'nombre',
                    label: 'Nombre',
                    sortable: true
                },
                {
                    key: 'visitas',
                    label: 'Visitas',
                    sortable: true
                }
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
            perPaged: 10,
            fecha_registro: "",
            idInstitucion: "",
            nombreInstitucion: "",
            telefonoInstitucion: "",
            direccionInstitucion: "",
            solicitudInstitucion: "",
            vendedorInstitucion: "",
            vendedorInstitucionn: "",
            periodoescolar: "",
            nombreregion: "",
            nombreciudad: "",
            ciudad_id: "",
            idciudad: "",
            region_idregion: "",
            // variables nuevo perido
            fecha_inicial: "",
            fecha_final: "",
            region_idregionp: "",
            descripcion: "",
            // fin variables nuevo perido
            nombrevendedor: "",
            apellidovendedor: "",
            cedula: "",
            nombres: "",
            apellidos: "",
            email: "",
            name_usuario: "",
            id_group: "",
            cedulae: "",
            nombrese: "",
            apellidose: "",
            emaile: "",
            USERNAMEe: "",
            id_groupe: "",
            date_created: "",
            p_ingreso: "",
            p_ingresom: "",
            indice: 0,
            pagination: {},
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorCategoria: 0,
            offset: 3,
            criterio: 'nombreInstitucion',
            buscar: '',
            dialogg: false,
            dialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            acciones: false,
            dialog2: false,
            dialog3: false,
            dialog4: false,
            dialog5: false,
            dialog6: false,
            dialog7: false,
            dialog8: false,
            dialog9: false,
            dialog10: false,
            nombre: "",
            periodoescolar_idperiodoescolar: "",
            idnuevo: "",
            institucion_idInstitucion: "",
            institucion_idInstitucione: "",
            idusuario: "",
            institucion: [],
            procesoT: false,
            instituciondato: [],
            ciudad: [],
            region: [],
            usuario: [],
            docentes: [],
            Tproceso: false,
            usuariodato: [],
            asignaturadocente: [],
            grupo: [],
            periodo: [],
            errorMostrarMsjCategoria: [],
            historial: [],
            historialacc: [],
            usuarionuevo: [],
            asignaturadocente: [],
            asignaturaguarda: [],
            asignaturasnuevas: [],
            area: [],
            asignaturas: [],
            errors: [],
            datoUsuario: [],
        };
    },

    mounted: function mounted() {
        this.datosUsuario();
        this.getInstitucion();
        this.getCiudadSelect();
        this.getRegionSelect();
        this.getUsuarioSelect();
        this.getGrupoSelect();
        this.getPeriodoSelect();
        this.getAsignaturaSelect();
        this.getAreaSelect();
        this.getVendedor();
    },
    methods: {
        getVendedor() {
            let me = this;
            var url = "./getvendedor";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.selectVendedor = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getInstitucion() {
            let me = this;
            me.procesoT = true;
            var url = "./institucion";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.institucion = response.data;
                    me.procesoT = false;
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        getAsignaturaSelect() {
            var _this = this;
            axios.get("./asignaturaSelect").then(function (response) {
                _this.asignaturas = response.data;
            });
        },
        getAreaSelect() {
            var _this = this;
            axios.get("./areaSelect").then(function (response) {
                _this.area = response.data;
            });
        },
        getInstitucionDato(item) {
            let me = this;
            me.vendedorInstitucionn = item.vendedorInstitucion;
            if (item.nombreInstitucion != null) {
                me.nombreInstitucion = item.nombreInstitucion;
            }
            if (item.telefonoInstitucion != null) {
                me.telefonoInstitucion = item.telefonoInstitucion;
            }
            if (item.direccionInstitucion != null) {
                me.direccionInstitucion = item.direccionInstitucion;
            }
            if (item.solicitudInstitucion != null) {
                me.solicitudInstitucion = item.solicitudInstitucion;
            }
            if (item.vendedorInstitucion != null) {
                me.vendedorInstitucionn = item.vendedorInstitucion;
            }
            if (item.ciudad_id != null) {
                me.idciudad = item.ciudad_id;
            }
            if (item.region_idregion != null) {
                me.region_idregion = item.region_idregion;
            }
            if (item.idInstitucion != null) {
                me.idInstitucion = item.idInstitucion;
            }
            if (item.fecha_registro != null) {
                me.fecha_registro = item.fecha_registro;
            }
            if (item.nombre != null) {
                me.nombreciudad = item.nombre;
            }
            if (item.nombreregion != null) {
                me.nombreregion = item.nombreregion;
            }
            if (item.nombres != null) {
                me.nombrevendedor = item.nombres;
            }
            if (item.apellidos != null) {
                me.apellidovendedor = item.apellidos;
            }
            if (item.periodoescolar != null) {
                me.periodoescolar = item.periodoescolar;
            }

        },
        getInformacion(item) {
            let me = this;
            me.idusuario = item.idusuario;
            if (item.nombreInstitucion != null) {
                me.nombreInstitucion = item.nombreInstitucion;
            }
            if (item.telefonoInstitucion != null) {
                me.telefonoInstitucion = item.telefonoInstitucion;
            }
            if (item.direccionInstitucion != null) {
                me.direccionInstitucion = item.direccionInstitucion;
            }
            if (item.solicitudInstitucion != null) {
                me.solicitudInstitucion = item.solicitudInstitucion;
            }
            if (item.vendedorInstitucion != null) {
                me.vendedorInstitucionn = item.vendedorInstitucion;
            }
            if (item.ciudad_id != null) {
                me.idciudad = item.ciudad_id;
            }
            if (item.region_idregion != null) {
                me.region_idregion = item.region_idregion;
            }
            if (item.idInstitucion != null) {
                me.idInstitucion = item.idInstitucion;
            }
            if (item.fecha_registro != null) {
                me.fecha_registro = item.fecha_registro;
            }
            if (item.nombre != null) {
                me.nombreciudad = item.nombre;
            }
            if (item.nombreregion != null) {
                me.nombreregion = item.nombreregion;
            }
            if (item.nombres != null) {
                me.nombrevendedor = item.nombres;
            }
            if (item.apellidos != null) {
                me.apellidovendedor = item.apellidos;
            }
            if (item.periodoescolar != null) {
                me.periodoescolar = item.periodoescolar;
            }
        },
        getInformacionDocente(item) {
            let me = this;
            me.idusuario = item.idusuario;

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
        getHistorialDocente(idusuario) {
            let me = this;
            var url = "./historial?idusuario=" + idusuario;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.historial = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getHistorial(idInstitucion) {
            let me = this;
            var url = "./historialI?idInstitucion=" + idInstitucion;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.historialacc = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getGrupoSelect() {
            var _this = this;
            axios.get("./rolSelect").then(function (response) {
                _this.grupo = response.data;
            });
        },
        getPeriodoSelect() {
            var _this = this;
            axios.get("./periodoSelect").then(function (response) {
                _this.periodo = response.data;
            });
        },
        asignatura(idusuario) {
            var _this = this;
            let me = this;
            $("#idusuario").val(idusuario);
            var url = "./asignaturadocente?idusuario=" + idusuario;
            axios.get(url).then(function (response) {
                    _this.asignaturadocente = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        getCiudadSelect() {
            var _this = this;
            axios.get("./ciudadSelect").then(function (response) {
                _this.ciudad = response.data;
            });
        },
        getRegionSelect() {
            var _this = this;
            axios.get("./regionSelect").then(function (response) {
                _this.region = response.data;
            });
        },
        getUsuarioSelect() {
            let me = this;
            axios.get("./usuarioSelect").then(function (response) {
                var usuario = response.data;
                me.usuario = usuario;
            });
        },
        getDocentes(idinstitucion) {
            let me = this;
            me.Tproceso = true;
            axios.get("./docentes?idInstitucion=" + idinstitucion).then(function (response) {
                me.docentes = response.data;
                me.Tproceso = false;
            });
        },
        guardarDocente() {
            let me = this;
            var docente = Object();
            docente.cedula = me.cedula;
            docente.nombres = me.nombres;
            docente.apellidos = me.apellidos;
            docente.name_usuario = me.name_usuario;
            docente.email = me.email;
            docente.id_group = me.id_group;
            this.usuarionuevo.push(docente);
        },
        print() {
            $(".acciones").hide();
            this.$htmlToPaper('printMe');
            $(".acciones").show();
        },
        printAcceso() {
            $(".acciones").hide();
            this.$htmlToPaper('printAcceso');
            $(".acciones").show();
        },
        printdocente() {
            $(".acciones").hide();
            this.$htmlToPaper('printMeD');
            $(".acciones").show();
        },
        printInstituciones() {
            $(".acciones").hide();
            this.$htmlToPaper('printIns');
            $(".acciones").show();
        },
        deleteDocente(item) {
            const index = this.usuarionuevo.indexOf(item)
            this.usuarionuevo.splice(index, 1)
        },
        guardareditato() {
            Object.assign(this.usuarionuevo[this.editedIndex], this.editedItem);
        },
        guardarPeriodo() {
            let me = this;
            axios
                .post("./periodo/registrar", {
                    fecha_inicial: this.fecha_inicial,
                    fecha_final: this.fecha_final,
                    region_idregion: this.region_idregionp,
                    descripcion: this.descripcion
                })
                .then(function (response) {
                    me.dialog8 = false;
                    swal("Registro Guardado", "", "success");
                    me.fecha_inicial = "";
                    me.fecha_final = "";
                    me.region_idregionp = "";
                    me.descripcion = "";
                    me.getPeriodoSelect();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        limpiarDatosInstitucion() {
            this.nombreInstitucion = "";
            this.direccionInstitucion = "";
            this.region_idregion = "";
            this.ciudad_id = "";
            this.vendedorInstitucion = "";
            this.telefonoInstitucion = "";
            this.solicitudInstitucion = "";
            this.periodoescolar_idperiodoescolar = "";
        },
        guardarInstitucion() {
            let me = this;
            axios
                .post("./institucion/save", {
                    nombre: me.nombreInstitucion,
                    direccion: me.direccionInstitucion,
                    telefono: me.telefonoInstitucion,
                    vendedor: me.vendedorInstitucion.cedula,
                    region: me.region_idregion.idregion,
                    solicitud: me.solicitudInstitucion,
                    ciudad: me.ciudad_id.idciudad,
                    periodo_escolar: me.periodoescolar_idperiodoescolar.idperiodoescolar
                })
                .then(function (response) {
                    me.dialog2 = false;
                    swal("Registro Guardado", "", "success");
                    me.getInstitucion();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });

        },
        guardar: function (event) {
            let me = this;
            axios
                .post("./institucion/registrar", {
                    id: this.idInstitucion,
                    nombre: this.nombreInstitucion,
                    telefono: this.telefonoInstitucion,
                    direccion: this.direccionInstitucion,
                    solicitud: this.solicitudInstitucion,
                    vendedor: this.vendedorInstitucionn,
                    region: this.region_idregion,
                    ciudad: this.idciudad
                })
                .then(function (response) {
                    me.editarInstitucion = false;
                    swal("Datos Guardados", "", "success");
                    me.getInstitucion(1, "", "nombreInstitucion");
                    me.errors = [];
                })
                .catch(function (error) {
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
            this.USERNAMEe = item.name_usuario;
            this.institucion_idInstitucione = item.institucion_idInstitucion;
            this.id_groupe = item.id_group;
        },
        editarDocente() {
            let me = this;
            axios.post("./usuarios/editar?email=" + me.emaile + "&usuario=" + me.USERNAMEe + "&cedula=" + me.cedulae, {
                idusuario: me.idusuario,
                cedula: me.cedulae,
                nombres: me.nombrese,
                apellidos: me.apellidose,
                email: me.emaile,
                name_usuario: me.USERNAMEe,
                id_group: me.id_groupe,
                institucion_idInstitucion: me.institucion_idInstitucione
            }).then(function (response) {
                me.dialog10 = false;
                me.idusuario = "";
                me.cedulae = "";
                me.apellidose = "";
                me.nombrese = "";
                me.emaile = "";
                me.USERNAMEe = "";
                me.id_groupe = "";
                me.institucion_idInstitucione = "";
                swal("Usuario Actualizado", " " + me.cedula + me.nombres, "success");
                me.getUsuario();
            }).catch(function (error) {
                console.log(error);
                swal("", error, "error");
            });
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
        getReporte(id) {
            let me = this;
            axios.get("./getReporteVisitas?idInstitucion=" + id)
                .then(function (response) {
                    me.reported = response.data;
                })
                .catch(function (error) {})

        }
    }
};
</script>
