<?php


$host="localhost";
$usuario="root";
$password="";
$basededatos="api";


$conexion= new mysqli($host,$usuario,$password,$basededatos);

if($conexion->connect_error ){

    die("Conexion establecida  ".$conexion->connect_error);

}
header("Content-Type: application/json");

$metodo=$_SERVER['REQUEST_METHOD'];
//print_r($metodo);
$path=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
$buscarId= explode('/', $path);
$id = ($path!=='/') ? end($buscarId):null;

    switch($metodo){

        //CONSULTA SELECT
        case 'GET':
            //echo " Consulta de registros - GET";
            consulta($conexion,$id);
            break;

        //CONSULTA INSERT
        case 'POST':

            //echo " Insert de registros - POST";
            insertar($conexion);
            break;

        //CONSULTA UPDATE
        case 'PUT':
            echo " Actualización de registros - PUT";
            actualizar($conexion,$id);  
            break;

        //CONSULTA DELETE
        case 'DELETE':

            borrar($conexion,$id);
            echo " Borrado de registros - DELETE";
            break;

            default:
            echo " Metodo no permitido";
            break;
    }


    function consulta($conexion,$id){
        $sql=($id===null) ? "SELECT * FROM usuarios": "SELECT * FROM usuarios WHERE id= $id";
        $resultado= $conexion->query($sql);

        if($resultado){
            $datos= array();

            while($fila=$resultado->fetch_assoc()){
                $datos[]=$fila;
            }

            echo json_encode($datos);

        }

    }

    function insertar($conexion){
        
        $dato= json_decode(file_get_contents('php://input'),true);
        $nombre= $dato['nombre'];
        print_r($nombre);
        
        $sql= "INSERT INTO usuarios (nombre) VALUES ('$nombre') ";
        $resultado=$conexion->query($sql);

        if($resultado){

            $dato['id']= $conexion->insert_id;
            echo json_encode($dato);
        }else{
            echo json_encode(array('error'=>'Error al crear usuario'));
        }
    }
    function borrar($conexion, $id){

           // echo "El id a borrar es ".$id;

            $sql= "DELETE FROM usuarios WHERE id= $id";
        $resultado=$conexion->query($sql);

        if($resultado){

            $dato['id']= $conexion->insert_id;
            echo json_encode(array('mensaje'=>'Usuario Eliminado'));
        }else{
            echo json_encode(array('error'=>'Error al borrar usuario'));
        }
    }
    function actualizar($conexion, $id){

        $dato= json_decode(file_get_contents('php://input'),true);
        $nombre= $dato['nombre'];

        $sql= "UPDATE  usuarios SET nombre='$nombre' WHERE id= $id";
        $resultado=$conexion->query($sql);

        if($resultado){

            $dato['id']= $conexion->insert_id;
            echo json_encode(array('mensaje'=>'Usuario Actualizado'));
        }else{
            echo json_encode(array('error'=>'Error al actualizar al usuario'));
        }

        //echo "el ID a editar es ".$id." con el dato ".$nombre;
    }


?>