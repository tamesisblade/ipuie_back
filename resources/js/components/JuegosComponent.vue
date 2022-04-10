<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <v-card elevation=0>
                <br>
                <h3 class="text-themecolor" autofocus=true>
                    <i class="mdi mdi-book"></i>Registro Juegos
                </h3>
                <v-card-title>
                    <v-btn color="primary" dark @click="dialog = true; titulo='Nuevo Juego'; juego={}"> <i class="fa fa-plus"></i> &nbsp; Nuevo Registro</v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="juegos" :search="buscar" class="elevation-1">
                    <template v-slot:item.acciones="{item}">
                        <v-btn fab dark small color="indigo" @click="dialog = true; titulo='Editar Juego'; juego = item">
                            <i class="fa fa-pencil"></i>
                        </v-btn>
                        <v-btn fab dark small color="pink" @click="getEliminar(item.idlibro)">
                            <i class="fa fa-trash"></i>
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
    <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card tile>
            <v-toolbar card dark color="primary">
                <span class="headline">
                    <i class="fa fa-pencil"></i> {{titulo}}
                </span>
                <v-spacer></v-spacer>
                <v-btn fab dark small color="pink" @click="dialog = false">
                    <i class="fa fa-times"></i>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-container fluid>
                    <input type="text" v-model="juego.idjuego">
                    <v-text-field v-model="juego.nombre" label="Nombre" required></v-text-field>
                    <v-text-field v-model="juego.descripcion" label="Descripción" required></v-text-field>
                    <v-text-field v-model="juego.carpeta" label="Nombre de la carpeta" required></v-text-field>
                    <!-- <span v-if="errors.nombre" class="error--text">
                        <v-icon color="error">warning</v-icon>
                        {{errors.nombre[0]}}
                    </span> -->
                    <div class="row">
                        <div class="col col-md-6">
                            <label class="">
                                Asignatura
                            </label>
                            <v-select :options="asignaturas" value="idasignatura" :reduce="asignaturas => asignaturas.idasignatura" label="nombreasignatura" v-model="juego.asignatura_idasignatura">
                            </v-select>
                            <!-- <span v-if="errors.area" class="error--text">
                                <v-icon color="error">warning</v-icon>
                                {{errors.area[0]}}
                            </span> -->
                        </div>
                    </div>
                </v-container>
                <v-card-actions class="justify-center">
                    <v-btn @click="setJuego()" color="teal">
                        <v-icon>save</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-app>
</template>

<script>
import swal from 'sweetalert';
export default {
    data: function () {
        return {
            dialog: false,
            juegos: [],
            juego: {},
            errors: [],
            asignaturas: [],
            buscar: '',
            titulo: '',
            headers: [{
                    text: "Nombre",
                    value: "nombre"
                },
                {
                    text: "Descripción",
                    value: "descripcion"
                },
                {
                    text: "Carpeta",
                    value: "carpeta"
                },
                {
                    text: "Asignatura",
                    value: "nombreasignatura"
                },
                {
                    text: 'Acciones',
                    value: 'acciones',
                    class: 'acciones'
                }
            ]
        };
    },
    mounted() {
        this.getJuegos();
        this.getAsignaturas();
    },
    methods: {
        getJuegos() {
            let me = this;
            axios.get("./juegos")
                .then(function (response) {
                    me.juegos = response.data;
                })
                .catch(function (error) {})
        },
        getAsignaturas(){
            let me = this;
            axios.get("./asignaturas")
            .then(function (response) {
                me.asignaturas = response.data;
            })
            .catch(function (error) {
            })
        },
        setJuego(){
            let me = this;
            axios.post("./juegos", me.juego)
            .then(function (response) {
                me.getJuegos();
                me.dialog = false;
                swal("Guardado","","success");
            })
            .catch(function (error) {
            })
        }
    }
};
</script>
