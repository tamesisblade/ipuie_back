<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">
                        <i class="fa fa-trash"></i> Papelera
                    </h3>
                </div>
            </div>
            <v-card>
                <v-expansion-panel popout>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <div><i class="fa fa-users"></i> &nbsp; Usuarios Eliminados</div>
                        </template>
                        <v-card>
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="usuario" :search="search" class="elevation-1" :pagination.sync="paginationI">
                                <template v-slot:items="props">
                                    <td class="text-xs-left">{{props.item.cedula}}</td>
                                    <td class="text-xs-left"><span v-if="props.item.nombres != null" v-text="props.item.nombres.toUpperCase()"></span></td>
                                    <td class="text-xs-left"><span v-if="props.item.apellidos != null" v-text="props.item.apellidos.toUpperCase()"></span></td>
                                    <td class="text-xs-left"><span v-text="props.item.name_usuario"></span></td>
                                    <td class="text-xs-left"><span v-text="props.item.email"></span></td>
                                    <td v-if="props.item.p_ingreso == 0" class="text-xs-left"><span>No</span></td>
                                    <td v-else class="text-xs-left"><span>Si</span></td>
                                    <td class="text-xs-left">
                                        <v-btn fab dark small color="teal" @click="restaurar(props.item)"><i class="fa fa-history"></i></v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-card>
        </div>
        <footer class="footer">Prolipa © 2019</footer>
    </div>
</v-app>
</template>

<script>
export default {
    data: function () {
        return {
            paginationI: {
                rowsPerPage: '10'
            },
            usuario: [],
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
                    value: 'p_ingreso'
                },
                {
                    text: 'Restaurar',
                    class: 'acciones'
                },
            ],
        };
    },
    mounted: function mounted() {
        this.getUsuario();
    },
    methods: {
        getUsuario() {
            let me = this;
            var url = "./usuario/papelera";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.usuario = respuesta;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
        restaurar(item){
            let me = this;
            swal({
                title: "Seguro desea restaurar ?" + item.email,
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    axios
                        .post("usuario/restaurar", {
                            idusuario: item.idusuario
                        })
                        .then(function (response) {
                            me.getUsuario();
                            swal("Registro Restaurado!", {
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

}
</script>
