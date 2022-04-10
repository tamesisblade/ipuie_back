<template>
<v-app id="inspire">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center form-group">
                    <h3 class="text-themecolor"><i class="fa fa-comments-o"></i> Chat</h3>
                </div>
            </div>
            <v-card height="600">
                <v-layout>
                    <v-card-text>
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title class="text-xs-center">Mensajes</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                        </v-toolbar>
                        <div class="chat-rbox">
                            <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                <div class="chat-list p-20" style="overflow: auto; width: auto; height: 343px;">
                                    <template v-for="item in mensajes">
                                        <div class="row">
                                            <span>{{item.texto}}</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="card-body b-t">
                            <div class="row">
                                <v-text-field v-model="mensaje" outline clearable label="Message" type="text">
                                    <template v-slot:append-outer>
                                        <v-menu style="top: -12px" offset-y>
                                            <v-card>
                                                <v-card-text class="pa-4">
                                                    <v-btn large flat color="primary">
                                                        <v-icon left>mdi-target</v-icon>Click me
                                                    </v-btn>
                                                </v-card-text>
                                            </v-card>
                                        </v-menu>
                                    </template>
                                </v-text-field>
                                <v-btn fab dark color="primary" @click="enviarMensaje">
                                    <v-icon>send</v-icon>
                                </v-btn>
                            </div>
                        </div>
                    </v-card-text>

                    <v-navigation-drawer v-model="drawer"  absolute dark temporary>
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title class="text-xs-center">Contactos</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn icon>
                                <v-icon>search</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-list subheader>
                            <v-subheader>Activos</v-subheader>
                            <v-list-tile v-for="item in conectados" :key="item.title" avatar @click="">
                                <v-list-tile-avatar>
                                    <img src="assets/images/users/1.jpg" alt="user-img" class="img-circle center-block">
                                </v-list-tile-avatar>

                                <v-list-tile-content>
                                    {{item.nombres}}
                                </v-list-tile-content>

                                <v-list-tile-action>
                                    <small class="text-center text-success">Activo</small>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                    </v-navigation-drawer>
                </v-layout>
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
            datosUsuario: [],
            conectados: [],
            socket: io('https://prolipadigital.com.ec:8001'),
            mensaje: '',
            mensajes: [],
            drawer: null,
        };
    },
    mounted: function mounted() {
        this.datoUsuario();
        this.socket.on('conectados', (usuario) => {
            this.conectados = usuario;
        });
    },
    methods: {
        datoUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.datosUsuario = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        enviarMensaje() {
            let me = this;
            var mensaje = new Object();
            mensaje.texto = me.mensaje;
            me.mensajes.push(mensaje);
            me.mensaje = '';

        }
    }

}
</script>
