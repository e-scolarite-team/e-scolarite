<?php
 
namespace App\Bundle\FrontOfficeBundle\DependencyInjection\Services;
 
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Bundle\BackOfficeBundle\Entity\Etudiant as Etudiant;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

 
/**
 * Custom authentication success handler
 */
class EtudiantSuccessHandler extends ContainerAware implements AuthenticationSuccessHandlerInterface, LogoutSuccessHandlerInterface
{
 
   /**
    */
   public function __construct()
   {
      
   }

   /**
     * Set container
     *
     * @param ContainerInterface $container
     * 
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }
 
   /**
    * @param Request        $request
    * @param TokenInterface $token
    * @return Response The response to return
    */
   public function onAuthenticationSuccess(Request $request, TokenInterface $token)
   {
      
      $request->getSession()->set('serviceState',$this->container->get('esconfig_manager')->getServiceStatus());
      
      $uri = $this->container->get('router')->generate('notification-etudiant');
 
      return new RedirectResponse($uri);
   }

   public function onLogoutSuccess(Request $request){

      $referer = $request->headers->get('referer');

      $token = $this->container->get('security.context')->getToken();
      if($token){
        $user = $token->getUser();

        if($user instanceof Etudiant){
          $em = $this->container->get('doctrine')->getManager();

          $etudiant = $em->getRepository('AppBackOfficeBundle:Etudiant')->find($user->getId());
          
          $etudiant->setLastVisiteAt(new \DateTime());

          $em->persist($etudiant);
          $em->flush();
        }
      }
      


      return new RedirectResponse($referer);
   }
}