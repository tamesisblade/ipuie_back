<template>
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <label class="navbar-brand">
                <b>
                    <img src="img/prolipa.png" alt="homepage" class="dark-logo " style="width:50px" />
                    <img src="img/prolipa.png" alt="homepage" class="light-logo " style="width:50px" />
                </b>
                <span class="font-weight-bold">
                    <strong style="color:white"> Prolipa</strong>
                </span>
            </label>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            </ul>
            <ul class="navbar-nav my-lg-0 remover">
                <template v-if="datosUsuario.id_group == 1 || datosUsuario.id_group == 5">
                    <li class="nav-item dropdown">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <v-flex xs12 class="mt-3">
                                <v-badge color="green" :content="conectados.length" left>
                                    <v-icon large dark>person</v-icon>
                                </v-badge>
                            </v-flex>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                            <ul>
                                <li>
                                    <div class="drop-title">Usuarios conectados</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <template v-for="item in conectados">
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="fa fa-user"></i></div>
                                                <div class="mail-contnet">
                                                    <p>{{item.Institucion}}</p>
                                                    <h5>{{item.nombres}} {{item.apellidos}}</h5>
                                                    <span class="time">Ingreso:</span>
                                                    <span class="time">{{item.fecha}}</span>
                                                </div>
                                            </a>
                                        </template>
                                        <!--<a href="#">
                                        <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a> -->
                                    </div>
                                </li>
                                <li>
                                    <!-- <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a> -->
                                </li>
                            </ul>
                        </div>
                    </li>
                </template>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                <li class="nav-item dropdown">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <v-flex xs12 class="mt-3">
                            <v-badge :color="'green'" left>
                                <template v-slot:badge>
                                    <span>0</span>
                                </template>
                                <v-icon large dark>chat</v-icon>
                            </v-badge>
                        </v-flex>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                        <ul>
                            <li>
                                <div class="drop-title">Mensajes</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <!-- Message -->
                                    <!-- <a href="#">
                                        <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a> -->
                                </div>
                            </li>
                            <li>
                                <!-- <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a> -->
                            </li>
                        </ul>
                    </div>
                </li>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                <li class="nav-item dropdown">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <v-flex xs12 class="mt-3">
                            <v-badge color="green" left>
                                <template v-slot:badge>
                                    <span>0</span>
                                </template>
                                <v-icon large dark>notification_important</v-icon>
                            </v-badge>
                        </v-flex>
                    </a>
                    <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">
                        <ul>
                            <li>
                                <!-- <div class="drop-title">You have 4 new messages</div> -->
                                <div class="drop-title">Notificaciones</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <!-- Message -->
                                    <!-- <a href="#">
                                        <div class="user-img"> <img src="assets/images/users/1.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="user-img"> <img src="assets/images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                        <div class="mail-contnet">
                                            <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="user-img"> <img src="assets/images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                        <div class="mail-contnet">
                                            <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="user-img"> <img src="assets/images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a> -->
                                </div>
                            </li>
                            <li>
                                <!-- <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a> -->
                            </li>
                        </ul>
                    </div>
                </li>
                &nbsp;
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/users/1.jpg" alt="user" class="profile-pic" /></a>
                    <div class="dropdown-menu dropdown-menu-right scale-up">

                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box text-center">
                                    <div class="u-img"><img class="center-block" src="assets/images/users/1.jpg" alt="user">
                                    </div>
                                    <div class="u-text">
                                        <h5 class="text-muted">{{datosUsuario.nombres}} {{datosUsuario.apellidos}}</h5>
                                        <p class="text-muted">{{datosUsuario.nombreInstitucion}}</p>
                                        <p class="text-muted">{{ datosUsuario.email }}</p>
                                        <p class="text-muted">{{datosUsuario.deskripsi}}</p>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider bg-danger"></li>
                            <li>
                                <a class="dropdown-item" @click="cerrarSession">
                                    Salir
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- <v-dialog v-model="dialog" color="transparent" width="743px" height="746px" >
        <v-img src="./images/libro.jpg" class="img-responsive" height="746px" width="743px"></v-img>
    </v-dialog> -->
</header>
</template>

<script>
import io from 'socket.io-client';
import VModal from 'vue-js-modal';
Vue.use(VModal)
export default {
    data: function () {
        return {
            dialog: true,
            datosUsuario: [],
            conectados: [],
            socket: io('https://prolipadigital.com.ec:8001')
        };
    },
    mounted() {
        this.datoUsuario();
        this.$modal.show('hello-world');
        this.socket.on('inicio', (data, msj) => {
            let me = this;
            if (this.datosUsuario.id_group == 0 || this.datosUsuario.id_group == 1) {

                me.playSound();
                me.$alertify.success('Conectado: ' + " " + data.nombres.toUpperCase() + "  " + data.apellidos.toUpperCase() + "   " + "Institución:" + "    " + data.Institucion);
            }
        });
        this.socket.on('salir', (data) => {
            let me = this;
            if (this.datosUsuario.id_group == 0 || this.datosUsuario.id_group == 1) {
                me.playSound();
                me.$alertify.error('Desconectado: ' + " " + data.nombres.toUpperCase() + "  " + data.apellidos.toUpperCase() + "   " + "Institución:" + "    " + data.Institucion);
            }

        });
        this.socket.on('guardarUsr', (data) => {
            if (this.datosUsuario[0].id_group == 0 || this.datosUsuario[0].id_group == 1) {
                this.$alertify.success("Usuario Registrado Cedula:" + data.cedula);
                this.playSound();
            }
        });
        this.socket.on('conectados', (usuario) => {
            this.conectados = usuario;
        });
    },
    methods: {
        removeAllElements(array, elem) {
            var index = array.indexOf(elem);
            array.splice(index, 1);
        },
        async datoUsuario() {
            let me = this;
            var url = "./datosUsuario";
            axios.get(url).then(function (response) {
                    var hoy = new Date();
                    var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
                    var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
                    var respuesta = response.data;
                    me.datosUsuario = response.data[0];
                    console.log(me.datosUsuario);
                    me.socket.emit('sendmensaje', {
                        idusuario: me.datosUsuario.idusuario,
                        nombres: me.datosUsuario.nombres,
                        apellidos: me.datosUsuario.apellidos,
                        email: me.datosUsuario.email,
                        Institucion: me.datosUsuario.nombreInstitucion,
                        fecha: fecha + ' ' + hora,
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        playSound() {
            var sound = 'https://prolipadigital.com.ec/software/PlataformaProlipa/public/sonido/conectado.mp3';
            if (sound) {
                var audio = new Audio(sound);
                audio.play();
            }
        },
        cerrarSession() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    },

};
</script>
