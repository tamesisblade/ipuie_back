<?php

use Illuminate\Http\Request;

Route::get('posts', 		'PostController@index');
Route::get('post/{slug}', 	'PostController@show');
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('/logout', 'AuthController@logout');
});

Route::group(['middleware' => 'cors'],function(){
    Route::post('addContenidopost', 'CursoController@addContenidoD');
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/perfil_usuario', 'AuthController@register');
    Route::post('/crearUsuario', 'AuthController@crearUsuario');
    Route::post('/editarUsuario', 'AuthController@editarUsuario');
    Route::get('eliminarUsuario/{id}', 'AuthController@eliminarUsuario');
    Route::apiResource('menu','MenuController');
    Route::get('usuarios','UsuarioController@usuariosmisiones');
    Route::post('restaurar', 'UsuarioController@restaurar');
    Route::post('cambio_password', 'UsuarioController@passwordC');


    //CURSOS
    Route::apiResource('cursos','CursosController');
    Route::post('guardar_seccion','CursosController@guardar_seccion');
    Route::get('elimiar_seccion/{id_seccion}','CursosController@elimiar_seccion');
    Route::get('elimiar_curso/{id_curso}','CursosController@elimiar_curso');
    Route::post('inscripcion_curso','CursosController@inscripcion_curso');
    Route::get('curso_estudiante/{id_curso}/{id_estudiante}','CursosController@curso_estudiante');
    Route::get('solicitudes_usuarios','CursosController@solicitudes_usuarios');
    Route::post('procesar_solicitud','CursosController@procesar_solicitud');

    // NOTICIAS
    Route::apiResource('noticias','NoticiasController');
    Route::get('elimiar_noticia/{id_noticia}/{img_noticia}','NoticiasController@elimiar_noticia');

    //CONTACTANOS
    Route::post('enviar_mail','NoticiasController@enviar_mail');

    //EVENTOS
    Route::apiResource('eventos','EventosController');
    Route::get('delete_agenda/{id}','EventosController@delete_agenda');

    //PERSONALIZA HOME
    Route::apiResource('carruseles','CarruselesController');
    Route::get('get_parallax','CarruselesController@get_parallax');
    Route::get('get_lados_cubo','CarruselesController@get_lados_cubo');
    Route::post('guardar_img_cubo','CarruselesController@guardar_img_cubo');
    Route::get('eliminar_img_cubo/{id_cubo}/{img}','CarruselesController@eliminar_img_cubo');
    Route::get('get_footer','CarruselesController@get_footer');
    Route::post('guardar_footer','CarruselesController@guardar_footer');
    Route::get('get_fondo','CarruselesController@get_fondo');
    Route::get('eliminar_carrusel/{id}/{img}','CarruselesController@eliminar_carrusel');

    //ACERCA DE
    Route::get('get_acerca','CarruselesController@get_acerca');
    Route::post('save_get_acerca','CarruselesController@save_get_acerca');

    //TAREAS
    Route::resource('tareas','TareaController');
    Route::resource('archivo','ArchivoController');
    Route::post('dragArchivo','ArchivoController@dragArchivo');
    Route::get('tareaEstudiantePendiente','TareaController@tareaEstudiantePendiente');
    Route::get('tareaEstudianteRealizada','TareaController@tareaEstudianteRealizada');
    Route::post('respuesta','TareaController@respuesta');
    Route::post('editarCalificacionTarea','TareaController@editarCalificacionTarea');
    Route::post('eliminarTareaEstudiante','TareaController@eliminarTareaEstudiante');

    //EVALUACIONES
    Route::apiResource('evaluacion', 'EvaluacionController');
    Route::post('evaluacionesDocente','EvaluacionController@evaluacionesDocente');
    Route::apiResource('pregEvaluacion', 'PregEvaluacionController');
    Route::post('pregEvaluacionGrupo', 'PregEvaluacionController@pregEvaluacionGrupo');
    Route::post('guardarEvaluacion','PregEvaluacionController@guardarEvaluacion');
    Route::get('evaluacionEstudiante/{id}', 'CalificacionEvalController@evaluacionEstudiante');
    Route::get('estudiantesEvalCurso', 'EstudianteController@estudiantesEvalCurso');
    Route::post('evaluacionesEstudianteCurso','EvaluacionController@evaluacionesEstudianteCurso');
    Route::post('guardarRespuesta','CalificacionEvalController@guardarRespuesta');
    Route::apiResource('respEvaluacion', 'CalificacionEvalController');
    Route::get('getRespuestas/{id}','PregEvaluacionController@getRespuestas');
    Route::post('getRespuestasAcum','PregEvaluacionController@getRespuestasAcum');
    Route::post('evalCompleEstCurso','EvaluacionController@evalCompleEstCurso');
    Route::post('pregEvaluacionEstudiante', 'PregEvaluacionController@pregEvaluacionEstudiante');
    Route::get('verEvalCursoExport/{id}','EvaluacionController@verEvalCursoExport');
    Route::get('verCalificacionEval/{id}/{seccion}','EvaluacionController@verCalificacionEval');
    Route::get('eliminar_evaluacion/{id}','EvaluacionController@eliminar_evaluacion');
    Route::get('quitarPregEvaluacion/{id}','PregEvaluacionController@quitarPregEvaluacion');
    Route::post('verifRespEvaluacion', 'CalificacionEvalController@verifRespEvaluacion');
    Route::post('changeCalificacion','EvaluacionController@changeCalificacion');
    Route::post('modificarEvaluacion', 'CalificacionEvalController@modificarEvaluacion');


});
