<template>
<v-app id="inspire">

    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">
                        <i class="fa fa-file-word-o"></i> Planificaciones del Docente
                    </h3>
                </div>
            </div>
            <v-card>
                <v-card-title>
                    <div class="flex-grow-1"></div>
                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="planificaciones" :search="search" class="elevation-1" :pagination.sync="pagination">
                    <template v-slot:item.acciones="{item}">
                        <v-btn class="mx-2" v-bind:href="'./upload/planificacion/'+item.webplanificacion" fab dark small color="teal">
                            <v-icon dark>mdi-download</v-icon>
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
        <footer class="footer">soporte@prolipa.com.ec</footer>
    </div>
</v-app>
</template>

<script>
import io from 'socket.io-client'
export default {
    data: function () {
        return {
            planificaciones: [],
            search: '',
            headers: [{
                    text: 'Nombre',
                    value: 'nombreplanificacion'
                },
                {
                    text: 'Descripción',
                    value: 'descripcionplanificacion'
                },
                {
                    text: 'Descarga',
                    value: 'acciones'
                }
            ],
            pagination: {
                rowsPerPage: '10'
            },
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    mounted: function mounted() {
        this.datosUsuario();
    },
    methods: {
        datosUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data[0];
                    me.usuario = response.data;
                    me.getplanificacion(respuesta);
                    console.log(respuesta);
                    if (me.usuario.length > 0) {
                        me.dialog2 = false;
                        var p_ingreso = response.data[0].p_ingreso
                        if (p_ingreso == '0') {
                            me.dialog3 = true;
                        }
                    } else {
                        me.dialog2 = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getplanificacion(usuario) {
            let me = this;
            var hoy = new Date();
            var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
            var url = "./planificacion";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.planificaciones = response.data;
                    me.totalRows = response.data.length;
                })
                .catch(function (error) {
                    me.socket.emit('erroresPlanificaciones', {
                        idusuario: usuario.idusuario,
                        nombres: usuario.nombres,
                        apellidos: usuario.apellidos,
                        email: usuario.email,
                        institucion: usuario.nombreInstitucion,
                        fecha: fecha + ' ' + hora,
                        error: error
                    });
                });
        },
        print() {
            this.$htmlToPaper('printMe');
        },
    }
};
</script>
