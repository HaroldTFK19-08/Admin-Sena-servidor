<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modo práctica
    |--------------------------------------------------------------------------
    |
    | El proyecto AdminSena es un ejercicio académico, por lo que la API y las
    | rutas web están abiertas: no se aplica middleware de autenticación ni de
    | rol en las definiciones de ruta.
    |
    | Cuando este valor es `true`, los controladores tampoco bloquean las
    | operaciones por rol y el alcance de cada usuario solo se aplica si el
    | cliente envía un token Bearer válido.
    |
    | Ponlo en `false` (API_PRACTICE_MODE=false) para reactivar la protección
    | por rol dentro de los controladores.
    |
    */

    'practice_mode' => env('API_PRACTICE_MODE', true),

];
