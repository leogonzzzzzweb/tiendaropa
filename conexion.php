<?php
    // $host = 'localhost';
    // $user = 'root';
    // $pass = 'novapi89';
    // $base = 'tienda_ropa';

    // $con = mysqli_connect($host,$user,$pass,$base);

    // if($con){
    //     header('location: index.php');
    // }else{
    //     echo 'Error de conexion';
    // }

    $conexion = new mysqli(
        'localhost',
        'root',
        '48668315',
        'tienda_ropa'
    );

    if($conexion -> connect_error){
        die('Error de conexion> '.$conexion-> connect_error);
    }