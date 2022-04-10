<template>
<v-app id="inspire">
    <div class="container">
        <v-card-title>
            <h3 class="text-themecolor"><i class="fa fa-window-maximize"></i> Mis Clases</h3>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="dialog = true" dark>Agregar Clases</v-btn>
        </v-card-title>
        <v-layout row class="container">
            <div class="col-lg-3 col-md-6 animated  zoomInUp" v-for="(dato,i) in curso" :key="i">
                <div class="contenido">
                    <v-toolbar dark color="primary">
                        <span class="headline">{{dato.nombre}}</span>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <div class="card-body">
                        <p class="card-title">Sección: {{dato.seccion}}</p>
                        <p class="card-title">Materia: {{dato.materia}}</p>
                        <p class="card-title">Aula: {{dato.aula}}</p>
                        <p class="card-title">Código de la clase : <b>{{dato.codigo}}</b></p>
                        <v-card-actions class="justify-center">
                            <v-btn color="teal" class="animated flip justify-center" block dark @click="dialogc = true; idcurso = dato.idcurso; getTareasEstudiante(); getTareas(); getLibros()" v-bind:id="dato.nombre+'1'">
                                Ver Tareas
                            </v-btn>
                        </v-card-actions>
                    </div>
                </div>
            </div>
        </v-layout>
    </div>
    <footer class="footer">Prolipa © 2019</footer>
    <v-dialog v-model="dialogc" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-spacer></v-spacer>
                <v-btn fab color="pink" @click="dialogc = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-list three-line subheader>
                <v-tabs v-model="tabs" fixed-tabs>
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab>
                        <v-card-text>Tareas</v-card-text>
                    </v-tab>
                    <v-tab class="primary--text">
                        <v-card-text><b>Libros</b></v-card-text>
                    </v-tab>
                    <v-tab>
                        <v-card-text @click="getCalificacion()" >Calificaciones</v-card-text>
                    </v-tab>
                </v-tabs>
                <v-tabs-items v-model="tabs">
                    <v-tab-item>
                        <div class="card-body">
                            <div class="form-body py-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <v-row justify="space-between">
                                            <v-date-picker v-model="date1" @change="getTareas()" :events="arrayEvents" event-color="green lighten-1"></v-date-picker>
                                        </v-row>
                                    </div>
                                    <div class="col-lg-8">
                                        <v-card-title>
                                            <h4>Tareas </h4>
                                            <v-spacer></v-spacer>
                                            <v-btn rounde large dark @click="date1=''; getTareas()">
                                                Mostrar todo
                                            </v-btn>
                                        </v-card-title>
                                        <v-list subheader rounded>
                                            <div v-if="date1 != ''" class="alert alert-warning alert-rounded imagen">
                                                <label for="">Esta tarea finalizara el {{date1}} resuélvala pronto. Presione en <b>MOSTRAR TODO</b> para visualizar todas sus <b>Tareas</b></label>
                                            </div>
                                            <v-list-item v-for="(item, index) in listaTareas" :key="index" @click="">
                                                <v-list-item-avatar>
                                                    <img :src="'./img/tarea.png'">
                                                </v-list-item-avatar>
                                                <v-list-item-content>
                                                    <a v-bind:href="'./'+item.tarea.url" target="_blank">
                                                        <v-list-item-title>{{item.tarea.nombre}}</v-list-item-title>
                                                    </a>
                                                    <v-list-item-title>Descripción: {{item.tarea.descripcion}}</v-list-item-title>
                                                    <v-list-item-title>Fecha de Inicio: {{item.tarea.fecha_inicio}}</v-list-item-title>
                                                    <v-list-item-title>Fecha Final: {{item.tarea.fecha_final}}</v-list-item-title>
                                                    <v-list-item-title v-if="item.total[0].cantidad == 1">
                                                        Tarea enviada para calificar: <a v-bind:href="'./'+item.respuesta[0].url" target="_blank"> {{item.respuesta[0].nombre}} </a>
                                                    </v-list-item-title>
                                                </v-list-item-content>
                                                <v-list-item-icon>
                                                    <v-icon v-if="item.total[0].cantidad == 0" @click="enviarTarea(item.tarea.idtarea,item.tarea.curso_idcurso)" color="green">fa fa-plus</v-icon>
                                                </v-list-item-icon>
                                            </v-list-item>
                                        </v-list>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="card-body">
                            <div class="form-body py-4">
                                <v-card-title>
                                    <h2><i class="fa fa-book"></i> &nbsp; Libros</h2>
                                    <v-spacer></v-spacer>
                                </v-card-title>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </v-layout>
                            </div>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat class="mx-auto">
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="e_calificacion" :items="calificacion" :search="search"></v-data-table>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
            </v-list>
        </v-card>
    </v-dialog>
    <footer class="footer">Prolipa © 2019</footer>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline">
                    <i class="fa fa-plus"></i> &nbsp; Agregar Clase
                </span>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-btn fab color="pink" small @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text class="py-4">
                <v-text-field v-model="codigo" label="Codigo de la Clase" outline></v-text-field>
                <span v-if="errors.codigo" class="error--text">
                    <v-icon color="error">warning</v-icon>
                    {{errors.codigo[0]}}
                </span>
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" @click="guardar"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="tarea" max-width="800px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"><i class="fa fa-plus"></i> Enviar Tarea</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="tarea = false;"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="col col-md-12">
                    <label class="form-control btn btn-primary">
                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;Seleccionar un archivo
                        <input type="file" @change="onFileSelected" name="myfile">
                    </label>
                </div>
                <!-- <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone> -->
            </v-card-text>
            <v-card-actions class="justify-center">
                <v-btn color="teal" fab @click="guardarTarea"><i class="fa fa-save"></i></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="sug" max-width="800px" persistent>
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"> Clases sugeridas</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="sug = false;"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <div class="col col-md-12">
                    <div class="animated  zoomInUp py-4" v-for="(dato,i) in sugerencias" :key="i">
                        <div class="contenido">
                            <v-toolbar dark color="primary">
                                <span class="headline">{{dato.nombre}}</span>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <div class="card-body">
                                <p class="card-title">Sección: {{dato.seccion}}</p>
                                <p class="card-title">Materia: {{dato.materia}}</p>
                                <p class="card-title">Aula: {{dato.aula}}</p>
                                <p class="card-title">Código de la clase : <b>{{dato.codigo}}</b></p>
                                <v-card-actions class="justify-center">
                                    <v-btn color="teal" class="animated flip justify-center" dark @click="guardarClases(dato.codigo);" v-bind:id="dato.nombre+'1'">
                                        Agregar
                                    </v-btn>
                                    <b-tooltip v-bind:target="dato.nombre+'1'" title="Ver curso"></b-tooltip>
                                </v-card-actions>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog v-model="enviando" persistent width="300">
        <v-card color="primary" dark>
            <v-card-text>
                Enviando tarea...
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
</v-app>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import io from 'socket.io-client'
export default {
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            arrayEvents: [],
            // date1: new Date().toISOString().substr(0, 10),
            date1: '',
            clibros: [],
            verlibro: false,
            nomlibro: '',
            enviando: false,
            curso: [],
            dialogc: false,
            tabs: null,
            tareas: [],
            dialog: false,
            codigo: '',
            listaTareas: [],
            tarea: false,
            errors: [],
            idcurso: '',
            idtarea: '',
            sugerencias: [],
            sug: false,
            socket: io('https://prolipadigital.com.ec:8001'),
            dropzoneOptions: {
                url: './addTareaContenido',
                thumbnailWidth: 150,
                maxFilesize: 0.5,
                headers: {
                    "My-Awesome-Header": "header value"
                }
            },
            calificacion: [],
            search: '',
            e_calificacion: [{
                    text: 'Nombre',
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
        this.getSugerencias();
        this.socket.on('notificarEstudiante', (data) => {
            this.getSugerencias();
        });
    },
    methods: {
        async datolibro(libro) {
            this.nomlibro = libro;
        },
        getCurso() {
            let me = this;
            var aux = [];
            var cursos = [];
            axios.get('./estudianteCurso')
                .then(function (res) {
                    me.curso = res.data;
                })
                .catch(function (error) {
                    console.error(err);
                })
        },
        getLibros() {
            let me = this;
            axios.get('./librosCurso?idcurso=' + me.idcurso)
                .then(function (response) {
                    me.clibros = response.data
                })
                .catch(function (error) {})
        },
        getSugerencias() {
            let me = this;
            var cont = 0;
            axios.get('./cursoSugerencias')
                .then(function (response) {
                    var unique = Object.values(response.data.reduce((prev, next) => Object.assign(prev, {
                        [next.idcurso]: next
                    }), {}))
                    me.sugerencias = unique;
                    console.log(me.sugerencias);
                    if (me.sugerencias.length > 0) {
                        me.sug = true;
                    }
                })
                .catch(function (error) {})
        },
        getTareasEstudiante() {
            let me = this;
            axios.get('./tareasEstudiante/' + me.idcurso, {

                })
                .then(function (response) {
                    me.tareas = response.data;
                })
                .catch(function (error) {})
        },
        guardar() {
            let me = this;
            axios.post('./addClase', {
                    codigo: me.codigo
                })
                .then(function (response) {
                    me.dialog = false;
                    swal("Clase Agregada", "", "success");
                    me.getCurso();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                })
        },
        guardarClases(id) {
            let me = this;
            axios.post('./addClase', {
                    codigo: id
                })
                .then(function (response) {
                    me.dialog = false;
                    me.sug = false;
                    swal("Clase Agragada", "", "success");
                    me.getCurso();
                })
                .catch(function (error) {})
        },
        getTareas() {
            let me = this;
            me.arrayEvents = [];
            me.listaTareas = [];
            var url = "./getTareas?idcurso=" + me.idcurso + "&fecha=" + me.date1;
            axios.get(url).then(function (response) {
                    var respuesta = response.data.items;
                    me.listaTareas = response.data.items;
                    me.listaTareas.forEach(element => {
                        let date = new Date(element.tarea.fecha_final).toJSON().slice(0, 10).replace(/-/g, '-')
                        me.arrayEvents.push(date);
                        console.log(date);
                    });
                })
                .catch(function (error) {

                });

        },
        enviarTarea(idtarea, idcurso) {
            this.tarea = true;
            this.idtarea = idtarea;
            this.idcurso = idcurso;
        },
        onFileSelected(event) {
            this.file = event.target.files[0];
            console.log(this.file);
        },
        guardarTarea() {
            let me = this
            me.enviando = true;
            let formData = new FormData();
            console.log(this.file);
            formData.append('archivo', this.file);
            formData.append('idcurso', this.idcurso);
            formData.append('idtarea', this.idtarea);
            axios.post('./addTareaContenido', formData)
                .then(function (response) {
                    swal('Tarea enviada para calificar', '', 'success');
                    me.contendio = false;
                    me.file = '';
                    me.tarea = false;
                    me.getTareas();
                    me.enviando = false;
                })
                .catch(function (error) {
                    me.socket.emit('error', error, me.idcurso);
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                })
        },
        getCalificacion(){
            let me = this;
            axios.get("./calificacion?idcurso="+me.idcurso)
            .then(function (response) {
                me.calificacion=response.data
            })
            .catch(function (error) {
            })
        }
    },
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
</style>
