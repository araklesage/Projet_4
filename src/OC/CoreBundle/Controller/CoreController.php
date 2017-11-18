<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCCoreBundle:Core:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        // On récupère la session depuis la requête, en argument du contrôleur
        $session = $request->getSession();
        // Et on définit notre message
        $session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible, merci de revenir plus tard.');
        // Enfin, on redirige simplement vers la page d'accueil
        return $this->redirectToRoute('oc_core_home');
        // La méthode longue new RedirectResponse($this->get('router')->generate('oc_core_home')); est parfaitement valable
    }
}
