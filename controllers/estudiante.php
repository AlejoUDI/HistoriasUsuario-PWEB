<?php
require_once 'libs/session.php';
require_once 'helpers/Encriptar.php';
class Estudiante extends Controller{

    function __construct(){
      parent::__construct();

      $this->session = new Session();
      $this->session->init();
      if ($this->session->getStatus() === 1 || empty($this->session->get('correo_usuario')) || $this->session->getCurrentRolUser() != 3) {
        exit('<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>¡Error!</strong> Acceso denegado, por favor iniciar sesión.
        </div>". "<script>
         setTimeout(function(){
         window.location="http://localhost/HistoriasUsuario-PWEB/account"}, 1000);
         </script>"

          ');

          $this->view->datos = [];

      }

      $this->view->mensaje= $this->session->getCurrentUser();
      $this->view->confirmacion = "";
      $this->view->datos_perfil = [];
      $this->view->id_correo = "";
      $this->view->cabecera = "";
      $this->view->metodologias = [];
      $this->view->fuentes = [];
        //echo "<p>Nuevo controlador Main</p>";
    }

    function render() {
      $cabecera = "Inicio";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/index');

    }

    function crearhistoria() {
      $cabecera = "Crear Historia de Usuario";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/historiasusuario/crearhistoria');

    }

    function perfil () {
      $cabecera = "";
      $cabecera = "Perfil";
      $this->view->cabecera = $cabecera;
      $id_correo = $this->session->getCurrentUser();
      $datos_perfil = $this->model->loadPerfil($id_correo);
      $this->view->datos_perfil = $datos_perfil;
      $this->view->render('estudiante/perfil');
    }

    function EditarPerfil() {

      $confirmacion = "";
      $nombre = $_POST['NombreEstudiante'];
      $apellido = $_POST['ApellidoEstudiante'];
      $numero_semestre = $_POST['NumeroSemestre'];
      $cedula = $_POST['CedulaEstudiante'];


      if ($this->model->updatePerfil(['NombreEstudiante' => $nombre, 'ApellidoEstudiante' => $apellido, 'NumeroSemestre' => $numero_semestre, 'CedulaEstudiante' => $cedula])) {
        // code...
        $confirmacion = '<div class="alert alert-info" role="alert" ><strong>¡Oye!</strong>Tus datos se actualizaron correctamente.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div> ';
      } else {
        $confirmacion = '<div class="alert alert-danger" role="alert" > <strong> ¡Lo sentimos! </strong> sus datos no pudieron ser actualizados.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div> ';

      }

      $this->view->confirmacion = $confirmacion;
      $this->perfil();


    }

    function clave() {
      $cabecera = "";
      $cabecera = "Ajustes";
      $this->view->cabecera = $cabecera;
     $this->view->render('estudiante/changeClave');

    }

    function ActualizarClave() {

      $confirmacion = "";
      $id_correo = $this->session->getCurrentUser();
      $confirmar_clave = $_POST['confirmar_clave'];
      $clave = $_POST['clave_usuario'];

      if ($clave != $confirmar_clave) {

        $confirmacion = '<div class="alert alert-warning" role="alert" ><strong>¡Ups!</strong> Las contraseñas no coinciden.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>

        </div> ';
      } else {
        $clave = encriptar($clave);
        if ($this->model->updateClave(['clave_usuario' => $clave, 'correo_usuario' => $id_correo ])) {
          $confirmacion = '<div class="alert alert-success" role="alert" ><strong>¡Correcto!</strong> Su contraseña ha sido actualizada correctamente.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>

          </div> ';
        } else {
          $confirmacion = '<div class="alert alert-danger" role="alert" ><strong>¡Lo sentimos!</strong> Ha ocurrido un error inesperado.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>

          </div> ';
        }

      }


      $this->view->confirmacion = $confirmacion;
      $this->clave();

    }

    //Read Metodologia 
    function readMetodologia() {
        $cabecera = "";
        $cabecera = "Metodología";
        $this->view->cabecera = $cabecera;
       /* $metodologias = [];
        $metodologias = $this->model->getByIdMetodologia($id);
        $fuentes = [];
        $fuentes = $this->model->getByIdFuentes($id);
        $this->view->metodologias = $metodologias;
        $this->view->fuentes = $fuentes;*/
        $this->view->render('estudiante/readMetodologia');
    }

    //HISTORIAS DE USUARIO 

    //VISTAS DE INDEX
    function crearActividad() {
      $cabecera = "";
      $cabecera = "Actividad";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/historiasusuario/actividad/index');
    }

    function crearRecurso() {
      $cabecera = "";
      $cabecera = "Recurso";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/historiasusuario/recurso/index');
    }

    function crearModulo() {
      $cabecera = "";
      $cabecera = "Modulo";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/historiasusuario/modulo/index');
    }

    function crearFase() {
      $cabecera = "";
      $cabecera = "Fase";
      $this->view->cabecera = $cabecera;
      $this->view->render('estudiante/historiasusuario/fase/index');
    }



    }

?>
