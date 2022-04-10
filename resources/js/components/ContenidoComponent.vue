<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="fa fa-file"></i> Contenido</h3>
                </div>
            </div>
            <v-card>
                <v-card-title>
                    <v-btn color="info" @click="nuevo = true; errors = []">Nuevo</v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="b_contenido" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="e_contenido" :items="contenidos" :search="b_contenido">
                    <template v-slot:item.acciones="{item}">
                            <v-btn icon @click="editar(item)" color="info" dark><i class="fa fa-pencil"></i></v-btn>
                            <v-btn icon @click="eliminar(item.idcontenido)" color="red" dark><i class="fa fa-trash"></i></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
    <v-dialog v-model="nuevo" max-width="700px" max-heingth="700px">
        <v-card>
            <v-toolbar dark color="primary">
                <span class="headline"> Nuevo Contenido</span>
                <v-spacer></v-spacer>
                <v-btn fab small color="pink" @click="nuevo = false; errors = []"><i class="fa fa-times"></i></v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <div class="col col-md-12">
                        <input type="text" v-model="contenido.idcontenido" hidden>
                        <v-text-field v-model="contenido.nombre" label="Nombre del Archivo" required></v-text-field>
                        <span v-if="errors.nombre" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.nombre[0]}}
                        </span>
                    </div>
                    <div class="col col-md-12">
                        <label for="">Unidad</label>
                        <v-select :options="unidad" :reduce="unidad => unidad.id" label="nombre" v-model="contenido.unidad">
                        </v-select>
                        <span v-if="errors.asignatura" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.asignatura[0]}}
                        </span>
                    </div>
                    <div class="col col-md-12">
                        <label for="">Asignatura</label>
                        <v-select :options="asignaturas" :reduce="asignaturas => asignaturas.idasignatura" label="nombreasignatura" v-model="contenido.idasignatura">
                        </v-select>
                        <span v-if="errors.asignatura" class="error--text">
                            <v-icon color="error">warning</v-icon>
                            {{errors.asignatura[0]}}
                        </span>
                    </div>
                    <br>
                    <br>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn fab small color="teal" @click="guardarContenido()"><i class="fa fa-save"></i></v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-app>
</template>
<script>
export default {
    data: function () {
        return {

            contenido: {},
            unidad:[
                {id:1,nombre:'Unidad 1'},
                {id:2,nombre:'Unidad 2'},
                {id:3,nombre:'Unidad 3'},
                {id:4,nombre:'Unidad 4'},
                {id:5,nombre:'Unidad 5'},
                {id:6,nombre:'Unidad 6'},
                {id:7,nombre:'Unidad 7'},
                {id:8,nombre:'Unidad 8'},
            ],
            contenidos: [],
            nuevo: false,
            asignaturas: [],
            errors: [],
            b_contenido: '',
            nombre: '',
            idasignatura: '',
            e_contenido: [{
                    text: 'Nombre',
                    value: 'nombre'
                },
                {
                    text: 'Url',
                    value: 'url'
                },
                {
                    text: 'Asignatura',
                    value: 'asignatura'
                },
                {
                    text: 'Unidad',
                    value: 'unidad'
                },
                {
                    text: 'Acciones',
                    value:'acciones'
                }
            ],
        }
    },
    mounted() {
        this.getContenido();
        this.getasignaturas();

    },
    methods: {
        getContenido() {
            let me = this;
            axios.get("./contenido")
                .then(function (response) {
                    me.contenidos = response.data
                })
                .catch(function (error) {})
        },
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
        guardarContenido() {
            let me = this;
            axios.post('./contenido', me.contenido)
                .then(function (response) {
                    swal('Guardado', '', 'success');
                    me.nuevo = false;
                    me.nombre = '';
                    me.idasignatura = '';
                    me.getContenido();
                })
                .catch(function (error) {
                    if (error.response.status == 422) {
                        me.errors = error.response.data.errors;
                    }
                })
        },
        editar(item){
            console.log(item);
            this.contenido.idcontenido = item.idcontenido;
            this.contenido.nombre = item.nombre;
            this.contenido.unidad = item.unidad;
            this.contenido.idasignatura = item.idasignatura;
            this.nuevo = true;
        },
        eliminar(id) {
            let me = this;
            swal({
                title: "Seguro desea eliminar el registro?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios.delete("./contenido/"+id)
                    .then(res => {
                        me.getContenido();
                        swal('Registro Eliminado',res.data[0],'success');
                    })
                    .catch(err => {
                        console.error(err); 
                    })
                } else {
                    swal("Cancelado!");
                }
            });
        }
    },
}
</script>
