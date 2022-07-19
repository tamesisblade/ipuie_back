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
    Route::get('mis_cursos/{id_usuario}','CursosController@mis_cursos');
    Route::get('progreso_curso_usuario/{id_usuario}/{id_curso}','CursosController@progreso_curso_usuario');

    // NOTICIAS
    Route::apiResource('noticias','NoticiasController');
    Route::get('elimiar_noticia/{id_noticia}/{img_noticia}','NoticiasController@elimiar_noticia');

    // BLOGS
    Route::apiResource('blogs','BlogsController');
    Route::get('elimiar_blog/{id_blog}/{img_blog}','BlogsController@elimiar_blog');
    Route::get('get_categorias','BlogsController@get_categorias');
    Route::post('guardar_categoria','BlogsController@guardar_categoria');
    Route::get('get_blogs_categoria/{id_categoria}','BlogsController@get_blogs_categoria');
    Route::get('eliminar_categoria/{id_categoria}','BlogsController@eliminar_categoria');
    Route::get('guardar_valoracion_blog/{id_blog}/{usuario}/{valoracion}','BlogsController@guardar_valoracion_blog');

    //CONTACTANOS
    Route::post('enviar_mail','NoticiasController@enviar_mail');

    //EVENTOS
    Route::apiResource('eventos','EventosController');
    Route::get('delete_agenda/{id}','EventosController@delete_agenda');
    Route::get('ver_inscritos/{id}','EventosController@ver_inscritos');
    Route::post('inscripcion_evento','EventosController@inscripcion_evento');

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
    Route::get('get_acerca/{id}','CarruselesController@get_acerca');
    Route::post('save_get_acerca','CarruselesController@save_get_acerca');

    //TAREAS
    Route::resource('tareas','TareaController');
    Route::resource('archivo','ArchivoController');
    Route::post('dragArchivo','ArchivoController@dragArchivo');
    Route::get('tareaEstudiantePendiente','TareaController@tareaEstudiantePendiente');
    Route::get('tareaEstudianteRealizada','TareaController@tareaEstudianteRealizada');
    Route::post('guardar_tarea','TareaController@guardar_tarea');
    Route::post('editarCalificacionTarea','TareaController@editarCalificacionTarea');
    Route::post('eliminarTareaEstudiante','TareaController@eliminarTareaEstudiante');
    Route::get('reporte_curso_tareas/{id_curso}','TareaController@reporte_curso_tareas');

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
    Route::get('verEvalCursoExport/{id}/{seccion}','EvaluacionController@verEvalCursoExport');
    Route::get('verCalificacionEval/{id}/{seccion}','EvaluacionController@verCalificacionEval');
    Route::get('eliminar_evaluacion/{id}','EvaluacionController@eliminar_evaluacion');
    Route::get('quitarPregEvaluacion/{id}','PregEvaluacionController@quitarPregEvaluacion');
    Route::post('verifRespEvaluacion', 'CalificacionEvalController@verifRespEvaluacion');
    Route::post('changeCalificacion','EvaluacionController@changeCalificacion');
    Route::post('modificarEvaluacion', 'CalificacionEvalController@modificarEvaluacion');
    Route::get('reporte_curso_evaluaciones/{id_curso}', 'EvaluacionController@reporte_curso_evaluaciones');

    // DOCUMENTOS MINISTERIALES
    Route::apiResource('documentos_ministeriales', 'DocumentosMinisterialesController');
    Route::get('eliminarDocumento/{id_documento}/{imagen}', 'DocumentosMinisterialesController@eliminarDocumento');
});
