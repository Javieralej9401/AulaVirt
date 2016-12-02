<?php 
namespace AVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeguridadController extends Controller
{
    //Abstraerlo en una clase, controlador nose...
    private function enviarEmail($emailMsj){
       try {
         
          $mailer = $this->get('mailer');
          $message1 = new \Swift_Message();
          $message = $mailer->createMessage()
              ->setSubject($emailMsj["Subject"])
              ->setFrom($emailMsj["From"])
              ->setTo($emailMsj["To"])
              ->setBody(
                  $emailMsj["Body"]
                  
              )
          ;
          $mailer->send($message);
          $spool = $mailer->getTransport()->getSpool();
          $transport = $this->get('swiftmailer.transport.real');
          $spool->flushQueue($transport);

       }catch (\Exception  $e) {
          //Enviar a pagina de error.
          return $this->redirect($this->getRequest()->headers->get('referer'));
       }   
    } 


    private function crearOlvideClaveForm(){
        return    $this->createFormBuilder()
              ->setAction($this->generateUrl('app_olvidoClave'))
              ->setMethod('POST')
              ->add("usuarioEmailInput","text")
              ->add('submit', 'submit', array('label' => 'Enviar'))
              ->getForm()
           ;
    }
    public function loginAction(Request $request){
           $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
           $lastUsername = $authenticationUtils->getLastUsername();

           $formRecuperarClave= $this->crearOlvideClaveForm();
           return $this->render(
            'seguridad/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'formRecuperarClave'=> $formRecuperarClave->createView(),
                )
            );
   
    }

    public function olvideClaveAction(Request $request){

       $formRecuperarClave= $this->crearOlvideClaveForm();
       $formRecuperarClave->handleRequest($request);

        $data = $formRecuperarClave->getData();  
        $emailUsuario= $data["usuarioEmailInput"];

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AVBundle:Usuario')->findOneBy(array("email"=>$emailUsuario));

        //Envio simple de la contraseña al usuario

        if($entity){

           $claveUsuario= $entity->getClave();
           $UsuarioEmail =$entity->getEmail();

           $emailMsj= array(

              "Subject"=> "( Aula virtual Facyt ) Recuperar clave de ingreso",
              "From"=>  'edu1455@gmail.com',
              "To"=> $UsuarioEmail,
              "Body"=> "Tu clave de ingreso es: ".$claveUsuario,
           );


           $this->enviarEmail($emailMsj);

        }else{

          return $this->redirect($this->getRequest()->headers->get('referer'));
        }
 
        return $this->render('AVBundle:EnvioEmailController:Enviar.html.twig', array(
                "mensaje"=> "Mensaje Enviado :P :P"
        ));    
    }
    public function validarLoginAction(){
       
    }
}