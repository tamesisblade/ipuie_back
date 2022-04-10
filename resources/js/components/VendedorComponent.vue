<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">
                        <i class="fa fa-users"></i> Vendedores
                    </h3>
                </div>
            </div>
            <div class="card card-body">
                <!-- <button type="buttonk" class="btn btn-primary" data-toggle="modal" data-target="#nuevo">Nuevo Usuario</button> -->
                <v-btn @click="printInstituciones" fab dark color="primary">
                    <i class="fa fa-print"></i>
                </v-btn>
                <br>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <div id="printIns">
                    <v-data-table :headers="headers" :items="usuario" :search="search" class="elevation-1" :items-per-page="paginationI">
                        <template v-slot:item.ingreso="{item}">
                            <v-chip :color="'green'" v-if="item.p_ingreso == 0" class="text-xs-left" dark><span>No</span></v-chip>
                            <v-chip :color="'green'" v-else class="text-xs-left" dark><span>Si</span></v-chip>
                        </template>
                        <template v-slot:item.acciones="{item}">
                            <td class="justify-center layout px-0 acciones">
                                <v-btn fab dark small color="primary" @click="getInstitucionSelect(item); dialog=true"><i class="fa fa-university"></i></v-btn>
                                <v-btn fab dark small color="success" @click="onexport(item.cedula,item.nombres,item.apellidos); reportemd = true"><i class="fa fa-file-excel-o"></i></v-btn>
                            </td>
                        </template>
                    </v-data-table>
                </div>

                <!-- Modal Insituciones -->
                <!-- <div id="institucion" class="modal fade modal-fullscreen " tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title" style="color:white" id="exampleModalLabel"><i class="fa fa-university"></i> Instituciones del Vendedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Salir">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="printMe" class="modal-body">
                                    <h3 class="font-weight-bold">INFORMACIÓN DEL VENDEDOR</h3>
                                    
                                    <h3 class="font-weight-bold">INSTITUCIONES</h3>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
                                    <button @click="print" class="btn btn-info"><i class="fa fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card tile>
                        <v-toolbar card dark color="primary">
                            <v-btn @click="printIV" fab dark color="teal"><i class="fa fa-print"></i></v-btn>
                            <v-spacer></v-spacer>
                            <v-btn fab dark color="pink" @click="dialog = false; usuarioinfo = []">
                                <i class="fa fa-times"></i>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <div id="printMeIV">
                                <div class="row page-titles">
                                    <div class="col-md-12">
                                        <h3 class="">INFORMACIÓN DEL VENDEDOR</h3>
                                        <hr>
                                        <div class="row" v-for="datou in usuarioinfo" :key="datou.idusuario">
                                            <div class="col-md-12">
                                                <p><label class="font-weight-bold">Cédula:</label> {{ datou.cedula }}</p>
                                                <p><label class="font-weight-bold">Nombre:</label> <span v-text="datou.nombres.toUpperCase()"></span> <span v-text="datou.apellidos.toUpperCase()"></span></p>
                                                <p><label class="font-weight-bold">Correo:</label> {{ datou.email}}</p>
                                            </div>
                                        </div>
                                        <h3 class="">INSTITUCIONES</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <v-data-table :headers="h_institucion" :items="usuariodato" class="elevation-1" :items-per-page="paginationI">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left"><span v-text="props.item.institucion"></span></td>
                                            <td class="text-xs-left"><span v-text="props.item.region"></span></td>
                                            <td class="text-xs-left"><span v-text="props.item.ciudad"></span></td>
                                            <td class="text-xs-left"><span v-text="props.item.solicitud"></span></td>
                                            <td class="text-xs-left"><span v-text="props.item.periodo"></span></td>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="reportemd"  fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card tile>
                        <v-toolbar card dark color="primary">
                            <v-btn @click="print" fab dark color="teal"><i class="fa fa-print"></i></v-btn>
                            <v-spacer></v-spacer>
                            <v-btn fab dark color="pink" small @click="reportemd = false;">
                                <i class="fa fa-times"></i>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <div id="printMe">
                                <template v-for="item in reporte">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <h3 class="box-title">Información de la institución</h3>
                                            <hr class="m-t-0 m-b-40">
                                            <div class="row">
                                                <div class="col col-md-6">
                                                    <div class="form-group row">
                                                        <label class="control-label text-left col-md-3 font-weight-bold">Nombre</label>
                                                        <div class="col-md-9">
                                                            <label type="text" class="control-label">{{item.institucion.nombre}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col col-md-6">
                                                    <div class="form-group row">
                                                        <label class="control-label text-left col-md-3 font-weight-bold">Docentes</label>
                                                        <div class="col-md-9">
                                                            <label type="text" class="control-label">{{item.institucion.docentes}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col col-md-6">
                                                    <div class="form-group row">
                                                        <label class="control-label text-left col-md-3 font-weight-bold">Total de Visitas</label>
                                                        <div class="col-md-9">
                                                            <label type="text" class="control-label">{{item.institucion.visitas}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="box-title">Docentes</h3>
                                            <hr class="m-t-0 m-b-40">
                                            <!--/row-->
                                            <v-card-title>
                                                <div class="flex-grow-1"></div>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-data-table :headers="tabla" :items="item.docentes" :items-per-page="paginationd" class="elevation-1">
                                                    <template v-slot:items="props">
                                                        <td>{{ props.item.cedula }}</td>
                                                        <td>{{ props.item.nombre }}</td>
                                                        <td>{{props.item.visitas}}</td>
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-dialog>

            </div>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            reportemd: false,
            paginationI: 10,
            paginationd: 100,
            search: '',
            cedulaVendedor: '',
            asignaturadocente: [],
            asignaturaguarda: [],
            asignaturasnuevas: [],
            usuario: [],
            usuariodato: [],
            institucion: [],
            usuarioinfo: [],
            institucionesVendedor: [],
            grupo: [],
            area: [],
            asignaturas: [],
            cedula: "",
            nombres: "",
            apellidos: "",
            email: "",
            name_usuario: "",
            institucion_idInstitucion: "",
            id_group: "",
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorCategoria: 0,
            errorMostrarMsjCategoria: [],
            offset: 3,
            criterio: 'cedula',
            buscar: '',
            output: null,
            reporte: [],
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
                    text: 'Ingreso a la Plataforma',
                    value: 'ingreso'
                },
                {
                    text: 'Reporte',
                    value: 'acciones',
                    class: 'text-center acciones'
                }
            ],
            h_institucion: [{
                    text: 'Institución',
                    value: 'institucion'
                },
                {
                    text: 'Región',
                    value: 'region'
                },
                {
                    text: 'Ciudad',
                    value: 'ciudad'
                },
                {
                    text: 'Solicitud',
                    value: 'solicitud'
                },
                {
                    text: 'Período Escolar',
                    value: 'periodo'
                },
            ],
            tabla: [{
                    text: 'Cédula',
                    value: 'cedula'
                },
                {
                    text: 'Docente',
                    value: 'nombre'
                },
                {
                    text: 'Número de visitas',
                    value: 'visitas'
                }
            ],
            dialog: false,
        };
    },

    mounted: function mounted() {
        this.getUsuario();
    },
    methods: {
        async getUsuario() {
            let me = this;
            var url = "./vendedor";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.usuario = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                    location.reload();
                });
        },
        async getInstitucionSelect(item) {
            var _this = this;
            let me = this;
            var usuario = new Object();
            usuario.idusuario = item.idusuario;
            usuario.nombres = item.nombres;
            usuario.apellidos = item.apellidos;
            usuario.cedula = item.cedula;
            usuario.email = item.email;
            me.usuarioinfo.push(usuario);
            var url = "./institucion/vendedor?cedula=" + item.cedula;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.usuariodato = respuesta;
            })

        },
        print() {
            this.$htmlToPaper('printMe');
        },
        printIV() {
            this.$htmlToPaper('printMeIV');
        },
        printInstituciones() {
            $(".acciones").hide();
            this.$htmlToPaper('printIns');
            $(".acciones").show();
        },
        async fetchData() {
            const response = await axios.get('./getReporte?cedula=' + this.cedulaVendedor);
            console.log(response);
            return response.data;
        },
        async onexport(cedula, nombre, apellido) { // On Click Excel download button
            let me = this;
            axios.get('./getCompleto?cedula=' + cedula)
                .then(function (response) {
                    console.log(response.data);
                    me.reporte = response.data.items;
                    // var animalWS = XLSX.utils.json_to_sheet(response.data)
                    // // var pokemonWS = XLSX.utils.json_to_sheet(this.Datas.pokemons)
                    // var wb = XLSX.utils.book_new() // make Workbook of Excel
                    // XLSX.utils.book_append_sheet(wb, animalWS, 'Instituciones') // sheetAName is name of Worksheet
                    // // XLSX.utils.book_append_sheet(wb, pokemonWS, 'pokemons')
                    // // export Excel file
                    // XLSX.writeFile(wb, nombre + ' ' + apellido + '.xlsx') // name of the file is 'book.xlsx'
                })
                .catch(function (error) {})

        }

    }
};
</script>
