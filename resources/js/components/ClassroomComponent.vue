<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-card-title autofocus>
                <h3 class="text-themecolor"><i class="fa fa-window-maximize"></i> Mis Clases</h3>
                <v-spacer></v-spacer>
                <v-btn rounde color="primary" @click="dialog = true" dark>Crear una clase</v-btn>
            </v-card-title>
            <v-layout row wrap>
                <div class="col-lg-4 col-md-6 animated  zoomInUp" v-for="dato in curso" :key="dato.idcurso">
                    <div class="py-4">
                        <div class="contenido">
                            <v-toolbar dark color="primary">
                                <span class="headline">{{dato.nombre}}</span>
                                <v-spacer></v-spacer>
                                <v-menu transition="slide-x-transition" bottom right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn dark icon v-on="on">
                                            <v-icon>more_vert</v-icon>
                                        </v-btn>
                                    </template>

                                    <v-list>
                                        <v-list-tile @click="editar(dato)">
                                            <v-icon>create</v-icon>
                                            <v-list-tile-title>Editar</v-list-tile-title>
                                        </v-list-tile>
                                        <v-list-tile @click="eliminar(dato)">
                                            <v-icon>delete_sweep</v-icon>
                                            <v-list-tile-title>Eliminar</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </v-toolbar>
                            <div class="card-body">
                                <p class="card-title"><b>Paralelo:</b> {{dato.seccion}}</p>
                                <p class="card-title"><b>Materia:</b> {{dato.materia}}</p>
                                <p class="card-title"><b>Aula:</b> {{dato.aula}}</p>
                                <p class="card-title"><b>Código de la clase :</b> <b>{{dato.codigo}}</b></p>
                                <div class="text-center">
                                    <v-btn color="teal" class="animated flip" dark block outlined @click="dialogc = true; datosCurso(dato); getTareas(dato.idcurso); getEstudiantes(dato.codigo); dato.idcurso = idcurso" v-bind:id="dato.nombre+'1'">
                                        Ver Clase
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-layout>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>

    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-plus"></i> Crear una clase</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="dialog = false; limpiar()"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <v-text-field v-model="nombre" label="Nombre de la clase" required></v-text-field>
                <v-text-field v-model="seccion" label="Paralelo" required></v-text-field>
                <v-text-field v-model="materia" label="Materia" required></v-text-field>
                <v-text-field v-model="aula" label="Aula" required></v-text-field>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guadarCurso" fab dark><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="dialog2" persistent max-width="500px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-pencil"></i> Editar clase</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="dialog2 = false; limpiar()"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <input type="text" v-model="idcurso" hidden>
                <v-text-field v-model="nombre" label="Nombre de la clase" required></v-text-field>
                <v-text-field v-model="seccion" label="Paralelo" required></v-text-field>
                <v-text-field v-model="materia" label="Materia" required></v-text-field>
                <v-text-field v-model="aula" label="Aula" required></v-text-field>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guadarCursoEditado" fab dark><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="dialogc" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar flat color="transparent">
                <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
                <div class="flex-grow-1"></div>
                <v-tabs v-model="tabs" fixed-tabs>
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab class="primary--text">
                        <v-card-text><b>Contenidos</b></v-card-text>
                    </v-tab>

                    <v-tab class="primary--text">
                        <v-card-text><b>Libros</b></v-card-text>
                    </v-tab>

                    <v-tab class="primary--text">
                        <v-card-text><b>Tareas</b></v-card-text>
                    </v-tab>

                    <v-tab class="primary--text">
                        <v-card-text><b>Alumnos</b></v-card-text>
                    </v-tab>

                    <v-tab class="primary--text">
                        <v-card-text><b>Calificaciones</b></v-card-text>
                    </v-tab>
                </v-tabs>
                <v-btn icon dark color="pink" @click="dialogc = false; limpiar()">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text three-line subheader>
                <v-tabs-items v-model="tabs">
                    <v-tab-item>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <v-btn rounded @click="getContenidoTodo()" block tile outlined color="dark">
                                            <v-icon left>file</v-icon> Todo
                                        </v-btn>
                                    </div>
                                    <div class="col-md-12">
                                        <v-btn rounded @click="idasignatura = null; getContenido() " block tile outlined color="dark">
                                            <v-icon left>file</v-icon> Mi Contenido
                                        </v-btn>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-warning alert-rounded imagen">
                                            <label for="">Para mostrar contenidos seleccione una <b>ASIGNATURA</b>, o presione en <b>TODO</b> para visualizar todos sus Contenidos.</label>
                                        </div>
                                        <label><b>ASIGNATURAS</b></label>
                                        <v-select :options="asignaturas" @input="getContenido()" :reduce="asignaturas => asignaturas.asignatura_idasignatura" label="nombreasignatura" v-model="idasignatura">
                                            <template slot="option" slot-scope="option">
                                                {{ option.nombreasignatura }}
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col col-md-9">
                                    <div class="form-body py-4">
                                        <vue-dropzone ref="dropzone" @vdropzone-complete="guardar" id="drop1" :options="dropzoneOptions"></vue-dropzone>
                                        <div class="py-4">
                                            <div v-for="item in listaContenido" :key="item.idcontenido" class="alert alert-success alert-rounded imagen">
                                                <i class="fa fa-file"></i>
                                                &nbsp;
                                                <a v-bind:href="'./'+item.url" target="_blank">
                                                    {{item.nombre}}
                                                </a>
                                                &nbsp;
                                                <span>{{item.updated_at}}</span>
                                                <button style="float: right;" v-if="item.curso_idcurso != null" type="button" class="btn btn-danger" @click="quitarContenido(item.idcontenido)">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <v-btn color="teal" fab @click="guardar"><i class="fa fa-save"></i></v-btn>
                                <div class="row">
                                    <div class="col col-md-4">
                                        <v-date-picker v-model="date" @change="getContenido()" class="mt-4"></v-date-picker>
                                    </div>
                                    <div class="col col-md-8">
                                        <v-card-text>
                                            <v-card-title tile>
                                                <v-btn rounde large dark @click="getContenidoTodo()">
                                                    Mostrar Todo
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                                <v-btn rounde large dark @click="contendio = true">
                                                    <i class="fa fa-plus"></i> &nbsp; Añadir Contenido
                                                </v-btn>
                                            </v-card-title>
                                            <v-layout row>
                                                <v-flex xs12>
                                                    <template v-if="listaContenido.length > 0">
                                                        <div v-for="item in listaContenido" :key="item.idcontenido" class="alert alert-success alert-rounded imagen">
                                                            <i class="fa fa-file"></i>
                                                            &nbsp;
                                                            <a v-bind:href="'./'+item.url" target="_blank">
                                                                {{item.nombre}}
                                                            </a>
                                                            &nbsp;
                                                            <span>{{item.updated_at}}</span>
                                                            <button v-if="item.curso_idcurso != null" type="button" class="close" @click="quitarContenido(item.idcontenido)">
                                                                <i class="fa fa-trash bg bg-danger"></i>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <div v-else class="alert alert-warning alert-rounded imagen">
                                                        <label for="">Para buscar contenidos seleccione una fecha en el <b>CALENDARIO</b>, o presione en <b>MOSTRAR TODO</b> para visualizar todos sus <b>Contenidos</b>.</label>
                                                    </div>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                    </div>
                                </div> -->
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="card-body">
                            <div class="form-body py-4">
                                <v-card-title>
                                    <h2><i class="fa fa-book"></i> &nbsp; Libros</h2>
                                    <v-spacer></v-spacer>
                                </v-card-title>
                                <div class="alert alert-warning alert-rounded imagen">
                                    <label for="">Seleccione un libro y presione <b>Agregar</b> para compartir con la clase, el libro seleccionado se reflejará en el perfil del estudiante.</label>
                                </div>
                                <v-card-title>
                                    <label class="">Seleccione:</label>
                                    <v-spacer></v-spacer>
                                    <v-btn rounde large dark @click="guardarLibro()">
                                        <i class="fa fa-plus"></i> &nbsp; Agregar
                                    </v-btn>
                                </v-card-title>
                                <v-select :options="libros" :reduce="libros => libros.idlibro" label="nombrelibro" v-model="idlibro">
                                </v-select>
                                <v-layout row wrap>
                                    <div class="col-lg-4 col-md-6 animated  zoomInUp " v-for="dato in clibros" :key="dato.idlibro">
                                        <div class="py-4">
                                            <div class="contenido">
                                                <img v-if="dato.weblibro != null " class="card-img-top img-responsive contenido" v-bind:src="'upload/libro/'+dato.weblibro+'/portada.png'" alt="Card image cap">
                                                <img v-else class="card-img-top img-responsive contenido" v-bind:src="'img/'+dato.portada" alt="Card image cap">
                                                <div class="card-body">
                                                    <h4 class="card-title text-light">{{dato.nombrelibro}}</h4>
                                                    <p class="card-text">{{dato.descripcionlibro}}</p>
                                                    <div class="py-4">
                                                        <v-btn v-if="dato.weblibro != null" v-bind:id="dato.idlibro+'4'" @click="verlibro = true; datolibro(dato.weblibro)" rounded color="green" outlined block> Libro Web</v-btn>
                                                    </div>
                                                    <div class="py-4">
                                                        <v-btn rounded color="pink" outlined block @click="eliminarLibro(dato.id)" dark>Eliminar</v-btn>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </v-layout>
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="card-body">
                            <div class="form-body py-4">
                                <v-card-title>
                                    <v-btn rounde large dark @click="tarea = true;">
                                        <i class="fa fa-plus"></i> &nbsp; Crear Tarea
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="t_search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
                                </v-card-title>
                                <v-layout row wrap>
                                    <!-- <v-data-table :headers="tareas" :search="t_search" :items="listaTareas" sort-by="calories" class="elevation-1">
                                        <template v-slot:item.archivo="{ item }">
                                            <a class="btn btn-outline-success" v-bind:href="'./'+item.tarea.url" target="_blank">
                                                Ver Tarea
                                            </a>
                                        </template>
                                        <template v-slot:item.entregas="{ item }">
                                            <b-button block variant="outline-primary" @click="mreporte = true; calificar(item.respuesta)">
                                                <b>{{item.total[0].cantidad}}</b>
                                            </b-button>
                                        </template>
                                        <template v-slot:item.actions="{ item }">
                                            <v-btn rounded color="red" @click="quitarTarea(item.tarea.idtarea)" dark>Eliminar</v-btn>
                                        </template>
                                    </v-data-table> -->
                                    <div v-for="item in listaTareas" :key="item.tarea.idtarea" class="col-lg-12 alert alert-success alert-rounded imagen">
                                        <div class="py-4">
                                            <p>
                                                <a v-bind:href="'./'+item.tarea.url" target="_blank">
                                                    {{item.tarea.nombre}}
                                                </a>
                                            </p>
                                            <p><b>Descripción:</b> {{item.tarea.descripcion}}</p>
                                            <p><b>Fecha de Inicio:</b> {{item.tarea.fecha_inicio}} &nbsp; <b>Fecha Final:</b> {{item.tarea.fecha_final}}</p>
                                            <v-btn rounded color="primary" outlined="" block @click="mreporte = true; calificar(item.respuesta)" dark>
                                                <b>Tareas Entregadas: &nbsp; {{item.total[0].cantidad}}</b>
                                            </v-btn>
                                            <br>
                                            <v-btn rounded color="red" block outlined="" @click="quitarTarea(item.tarea.idtarea)" dark>Eliminar</v-btn>
                                        </div>
                                    </div>
                                </v-layout>
                                <!-- <v-layout row>
                                        <v-flex xs12>
                                            <template v-for="item in listaTareas">
                                                <div class="alert alert-success alert-rounded imagen">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <p>
                                                                <a v-bind:href="'./'+item.tarea.url" target="_blank">
                                                                    {{item.tarea.nombre}}
                                                                </a>
                                                            </p>
                                                            <p><b>Descripción:</b> {{item.tarea.descripcion}}</p>
                                                            <p><b>Fecha de Inicio:</b> {{item.tarea.fecha_inicio}} &nbsp; <b>Fecha Final:</b> {{item.tarea.fecha_final}}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="button" class="close" @click="quitarTarea(item.tarea.idtarea)" color="red">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <b-button block variant="outline-primary" @click="mreporte = true; calificar(item.respuesta)">
                                                            <b>Tareas Entregadas: &nbsp; {{item.total[0].cantidad}}</b>
                                                        </b-button>
                                                    </div>
                                                </div>
                                            </template>
                                        </v-flex>
                                    </v-layout> -->
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="card-body">
                            <div class="form-body py-4">
                                <v-list two-line subheader>
                                    <v-card-title>
                                        <h2>Alumnos</h2>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headers" :search="search" :items="listaAlumnos" sort-by="calories" class="elevation-1">
                                        <template v-slot:item.actions="{ item }">
                                            <v-btn rounded color="red" @click="eliminarAlumno(item.codigo,item.id)" dark>Eliminar</v-btn>
                                        </template>
                                    </v-data-table>
                                </v-list>
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat class="mx-auto py-4">
                            <v-list two-line subheader>
                                <v-data-table :headers="tareas" :search="t_search" :items="listaTareas" sort-by="calories" class="elevation-1">
                                    <template v-slot:item.archivo="{ item }">
                                        <a v-bind:href="'./'+item.tarea.url" target="_blank">
                                            {{item.tarea.nombre}}
                                        </a>
                                    </template>
                                    <template v-slot:item.entregas="{ item }">
                                        <b-button block variant="outline-primary" @click="mreporte = true; calificar(item.respuesta)">
                                            <b>{{item.total[0].cantidad}}</b>
                                        </b-button>
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <v-btn rounded color="primary" @click="modalCalifiacion = true;calificaciones(item.tarea.idtarea)" dark>Calificaciones</v-btn>
                                    </template>
                                </v-data-table>
                            </v-list>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="dialogCodigo" max-width="500px">
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-btn icon @click="dialogCodigo = false"><i class="fa fa-times"></i></v-btn>
            </v-card-title>
            <v-card-text>
                <div class="text-center">
                    <h1>{{codigo}}</h1>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
    <!-- Modal Contenido -->
    <v-dialog v-model="contendio" max-width="700px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-book"></i> Agregar Contenido</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="contendio = false;"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <label class="form-control btn btn-primary">
                    <i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;Seleccionar un archivo
                    <input type="file" @change="onFileSelected" name="myfile">
                </label>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" fab @click="guardar"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Modal Crear Nueva Tarea -->
    <v-dialog v-model="tarea" max-width="800px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-plus"></i> Crear Tarea</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="tarea = false;"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="col col-md-6">
                    <label><b>ASIGNATURAS</b></label>
                    <v-select :options="asignaturas" @input="getContenido()" :reduce="asignaturas => asignaturas.asignatura_idasignatura" label="nombreasignatura" v-model="idasignatura">
                        <template slot="option" slot-scope="option">
                            {{ option.nombreasignatura }}
                        </template>
                    </v-select>
                </div>
                <div class="col col-md-6">
                    <label class=""><b>CONTENIDO</b></label>
                    <v-select :options="listaContenido" value="idInstitucion" :reduce="listaContenido => listaContenido.idcontenido" label="nombre" v-model="idcontenido">
                    </v-select>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="finicial" label="Fecha de Inicio" persistent-hint prepend-icon="event" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="finicial" no-title @input="menu1 = false"></v-date-picker>
                        </v-menu>
                        <span v-if="errors.finicial" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.finicial[0]}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="ffinal" label="Fecha Final" persistent-hint prepend-icon="event" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="ffinal" no-title @input="menu2 = false"></v-date-picker>
                        </v-menu>
                        <span v-if="errors.ffinal" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.ffinal[0]}}
                        </span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col col-md-12">
                        <v-text-field v-model="descripcion" label="Descripción" required></v-text-field>
                        <span v-if="errors.descripcion" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.descripcion[0]}}
                        </span>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" fab @click="guardarTarea"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="mreporte" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar flat color="primary">
                <h3 class="text-light"><b>Calificar Tareas</b></h3>
                <div class="flex-grow-1"></div>
                <v-btn dark fab small color="pink" @click="mreporte = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text three-line subheader>
                <v-list subheader rounded>
                    <v-list-item v-for="(item, index) in respuesta" :key="index" @click="">
                        <v-list-item-avatar>
                            <img :src="'./assets/images/users/1.jpg'">
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{item.nombres}} &nbsp; &nbsp; {{item.apellidos}}
                            </v-list-item-title>
                            <v-list-item-title>
                                Tarea Entrega : <a v-bind:href="'./'+item.url" target="_blank">{{item.nombre}}</a>
                            </v-list-item-title>
                            <v-list-item-title>
                                Correo : {{item.email}} &nbsp; &nbsp; Fecha de Entrega : {{item.fecha}}
                            </v-list-item-title>
                            <v-list-item-title>
                                Nota : {{item.nota}} &nbsp; &nbsp; Observación : {{item.observacion}}
                            </v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-btn outlined @click="mcalificar=true; datosCalificacion(item)" dark rounde color="indigo">Calificar</v-btn>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="mcalificar" max-width="800px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-pencil"></i> Calificar</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="mcalificar = false;"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="row">
                    <div class="col-md-12">
                        <v-text-field type="number" v-model="nota" label="Nota" min="0" max="10" step="0.5" required></v-text-field>
                        <span v-if="errors.finicial" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.finicial[0]}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <v-textarea outlined name="input-7-4" v-model="observacion" label="Observación"></v-textarea>
                        <span v-if="errors.finicial" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.finicial[0]}}
                        </span>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" fab @click="guardarCalificacion"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="enviando" persistent width="300">
        <v-card color="primary" dark>
            <v-card-text>
                Guardando...
                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="verlibro" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
        <v-card tile>
            <iframe id="t0_iframe" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="display: block; width: 100%; height: 100%; margin-left: auto; margin-right: auto; padding: 0px; top: 0px; position: absolute; left: 0px;" v-bind:src="'./upload/libro/'+nomlibro+'/index.php'"></iframe>
            <v-btn v-bind:class="'btn1-menu'" fab small dark color="red" @click="verlibro = false">
                <i class="fa fa-times"></i>
            </v-btn>
        </v-card>
    </v-dialog>
    <v-dialog v-model="modalCalifiacion" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar flat color="primary">
                <h3 class="text-light">Calificaciones</h3>
                <div class="flex-grow-1"></div>
                <v-btn dark fab small color="pink" @click="modalCalifiacion = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text three-line subheader>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="e_calificacion" :items="calificacion" :search="search"></v-data-table>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import io from 'socket.io-client';
import swal from 'sweetalert';
export default {
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            dropzoneOptions: {
                url: './addContenido',
                thumbnailWidth: 150,
                maxFilesize: 10.5,
                dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Subir Contenido",
                headers: {
                    "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                },
                addRemoveLinks: true
            },
            modalCalifiacion: false,
            idasignatura: '',
            nomlibro: '',
            menu1: false,
            menu2: false,
            verlibro: false,
            clibros: [],
            idlibro: '',
            libros: '',
            date: '',
            search: '',
            t_search: '',
            enviando: false,
            mcalificar: false,
            respuesta: [],
            s_act: [],
            mreporte: false,
            weblibro: '',
            tarea: false,
            contendio: false,
            dialog: false,
            dialog2: false,
            nombre: '',
            seccion: '',
            materia: '',
            codigo: '',
            aula: '',
            curso: [],
            idcurso: '',
            dialogc: false,
            tabs: null,
            dialogCodigo: false,
            comp: false,
            libros: [],
            asignaturas: [],
            area: [],
            asignatura: [],
            librosAsignados: [],
            listaContenido: [],
            listaTareas: [],
            auxlibros: [],
            actividades: [],
            idarea: '',
            idcontenido: '',
            finicial: '',
            ffinal: '',
            descripcion: '',
            errors: [],
            listaAlumnos: [],
            nota: '',
            idtarea: '',
            observacion: '',
            tareaid: '',
            headers: [{
                    text: 'Nombre',
                    class: 'text-center',
                    value: 'nombres',
                },
                {
                    text: 'Apellidos',
                    class: 'text-center',
                    value: 'apellidos',
                },
                {
                    text: 'Correo',
                    class: 'text-center',
                    value: 'email',
                },
                {
                    text: 'Eliminar',
                    class: 'text-center',
                    value: 'actions'
                },
            ],
            tareas: [{
                    text: 'Descripción',
                    class: 'text-center',
                    value: 'tarea.descripcion',
                },
                {
                    text: 'Tarea',
                    class: 'text-center',
                    value: 'archivo',
                },
                {
                    text: 'Fecha de Inicio',
                    class: 'text-center',
                    value: 'tarea.fecha_inicio',
                },
                {
                    text: 'Fecha de Entrega',
                    class: 'text-center',
                    value: 'tarea.fecha_final'
                },
                {
                    text: 'Tareas Entregadas',
                    class: 'text-center',
                    value: 'entregas'
                },
                {
                    text: 'Acciones',
                    class: 'text-center',
                    value: 'actions'
                },
            ],
            socket: io('https://prolipadigital.com.ec:8001'),
            calificacion: [],
            search: '',
            e_calificacion: [
                {
                    text: 'Nombres',
                    value: 'nombres',
                },
                {
                    text: 'Apellidos',
                    value: 'apellidos',
                },
                {
                    text: 'Correo',
                    value: 'email',
                },
                {
                    text: 'Tarea Entregada',
                    value: 'nombre',
                },
                {
                    text: 'Fecha de Entrega',
                    value: 'fecha'
                },
                {
                    text: 'Observación',
                    value: 'observacion'
                },
                {
                    text: 'Nota',
                    value: 'nota'
                }
            ],
        }
    },
    mounted() {
        this.getCurso();
        this.getLibro();
        this.getAsignaturas();
    },
    methods: {
        async datolibro(libro) {
            this.nomlibro = libro;
        },
        async getLibro() {
            let me = this;
            var url = "./librosEstudiante";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.libros = response.data;
                    console.log(me.libros);
                })
                .catch(function (error) {

                });
        },
        async getAsignaturas() {
            let me = this;
            var url = "./libro";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.asignaturas = response.data;
                    console.log(me.asignaturas);
                })
                .catch(function (error) {

                });
        },
        async getCurso() {
            let me = this;
            var url = "./curso";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.curso = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        async guadarCurso() {
            let me = this;
            axios
                .post("./cursoGuaradar", {
                    nombre: me.nombre,
                    seccion: me.seccion,
                    materia: me.materia,
                    aula: me.aula
                })
                .then(function (response) {
                    console.log(response.data)
                    me.socket.emit('cursoCreado', response.data);
                    me.dialog = false;
                    me.limpiar();
                    swal("Datos Guardados", "", "success");
                    me.getCurso();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        limpiar() {
            let me = this;
            me.nombre = '';
            me.seccion = '';
            me.materia = '';
            me.aula = '';
            me.codigo = '';
            me.comp = false;
        },
        editar(dato) {
            let me = this;
            me.idcurso = dato.idcurso;
            me.nombre = dato.nombre;
            me.seccion = dato.seccion;
            me.materia = dato.materia;
            me.aula = dato.aula;
            me.dialog2 = true;
        },
        eliminar(dato) {
            let me = this;
            swal({
                title: "Seguro desea Eliminar " + dato.nombre + " ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios.post("./eliminarCurso", {
                        idcurso: dato.idcurso
                    }).then(function (response) {
                        me.getCurso();
                        swal("¡Registro Eliminado!", "", "success");
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    swal("¡Cancelado!");
                }
            });
        },
        async eliminarAlumno(dato, id) {
            let me = this;
            swal({
                title: "Seguro desea Eliminar?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios.post("./eliminarAlumno", {
                        codigo: dato,
                        id: id,
                    }).then(function (response) {
                        me.getEstudiantes(dato);
                        swal("¡Registro Eliminado!", "", "success");
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    swal("¡Cancelado!");
                }
            });
        },
        async guadarCursoEditado() {
            let me = this;
            axios
                .post("./cursoGuaradarEditado", {
                    idcurso: me.idcurso,
                    nombre: me.nombre,
                    seccion: me.seccion,
                    materia: me.materia,
                    aula: me.aula
                })
                .then(function (response) {
                    me.dialog2 = false;
                    me.limpiar();
                    swal("Datos Guardados", "", "success");
                    me.getCurso();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        datosCurso(dato) {
            let me = this;
            me.idcurso = dato.idcurso;
            me.nombre = dato.nombre;
            me.seccion = dato.seccion;
            me.materia = dato.materia;
            me.aula = dato.aula;
            me.codigo = dato.codigo;
            me.getContenido();
            me.getLibros();
        },
        async getContenido() {
            let me = this;
            var url = "./getContenido?idcurso=" + me.idcurso + "&idasignatura=" + me.idasignatura;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.listaContenido = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getContenidoTodo() {
            let me = this;
            var url = "./getContenidoTodo?idcurso=" + me.idcurso + "&todo=1";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.listaContenido = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getEstudiantes(codigo) {
            let me = this;
            var url = "./getEstudiantes?codigo=" + codigo;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.listaAlumnos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getTareas(id) {
            let me = this;
            var url = "./getTareasDocentes?idcurso=" + id;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.listaTareas = response.data.items;
                    console.log(me.listaTareas);
                })
                .catch(function (error) {

                });

        },
        guardar(file) {
            let me = this
            let formData = new FormData();
            me.enviando = true;
            console.log(file);
            formData.append('archivo', file);
            formData.append('idcurso', this.idcurso);
            axios.post('./addContenido', formData)
                .then(function (response) {
                    me.contendio = false;
                    me.enviando = false;
                    me.getContenido();
                    me.file = '';
                    me.$refs.dropzone.removeAllFiles();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                })
        },
        quitarContenido(id) {
            let me = this;
            axios
                .get("./eliminarContenido?id=" + id)
                .then(function (response) {
                    swal("Dato Eliminado", "", "success");
                    me.getContenido();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        onFileSelected(event) {
            this.file = event.target.files[0];
            console.log(this.file);
        },
        guardarTarea() {
            let me = this;
            axios.post("./guardarTarea", {
                    idcontenido: me.idcontenido,
                    finicial: me.finicial,
                    ffinal: me.ffinal,
                    descripcion: me.descripcion,
                    idcurso: me.idcurso
                })
                .then(function (response) {
                    swal('Guardado', '', 'success');
                    me.tarea = false;
                    me.idcontenido = '';
                    me.finicial = '';
                    me.ffinal = '';
                    me.descripcion = '';
                    me.getTareas(me.idcurso);
                })
                .catch(function (error) {})
        },
        quitarTarea(id) {
            let me = this;

            axios
                .get("./eliminarTarea?id=" + id)
                .then(function (response) {
                    swal("Dato Eliminado", "", "success");
                    me.getTareas(me.idcurso);
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                });
        },
        calificar(respuesta) {
            let me = this;
            me.respuesta = respuesta;
        },
        datosCalificacion(item) {
            let me = this;
            me.idtarea = item.id;
            me.tareaid = item.tarea_idtarea;
        },
        guardarCalificacion() {
            let me = this;
            axios.post("./postCalificacion", {
                    id: me.idtarea,
                    idtarea: me.tareaid,
                    nota: me.nota,
                    observacion: me.observacion
                })
                .then(function (response) {
                    me.respuesta = response.data;
                    me.mcalificar = false;
                    swal("Calificación guardada", "", "success");
                })
                .catch(function (error) {})
        },
        guardarLibro() {
            let me = this;
            axios.post('./postLibroCurso', {
                    idlibro: me.idlibro,
                    idcurso: me.idcurso
                })
                .then(function (response) {
                    swal("Datos Guardado", "", "success");
                    me.getLibros();
                })
                .catch(function (error) {})

        },
        getLibros() {
            let me = this;
            axios.get('./librosCurso?idcurso=' + me.idcurso)
                .then(function (response) {
                    me.clibros = response.data
                })
                .catch(function (error) {})
        },
        eliminarLibro(id) {
            let me = this;
            axios.get('./librosCursoEliminar?id=' + id)
                .then(function (response) {
                    swal("Eliminado", "", "error");
                    me.getLibros();
                })
                .catch(function (error) {})
        },
        calificaciones(idtarea) {
            let me = this;
            axios.get("./calificacion?idtarea=" + idtarea)
                .then(function (response) {
                    me.calificacion = response.data
                })
                .catch(function (error) {})
        }

    }
}
</script>

<style>
.contenido {
    border-radius: 30px 30px 30px 30px;
    -moz-border-radius: 30px 30px 30px 30px;
    -webkit-border-radius: 30px 30px 30px 30px;
    /* background: #272c33; */
    /* color: #ffffff; */
}

.imagen {
    border-radius: 30px 30px 30px 30px;
    -moz-border-radius: 30px 30px 30px 30px;
    -webkit-border-radius: 30px 30px 30px 30px;
}
</style>
