<?php
/**
 * File containing the WebController class
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace AD\AppsoBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Ad\AppsoBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        if ( $request->isMethod('POST') ) 
        {
            $form->handleRequest($request);

            if ($form->isValid()) 
            {
                $message = \Swift_Message::newInstance()
                    ->setSubject( 'Formulaire de contact' )
                    ->setFrom('web@appso.dev')
                    ->setTo('nicolas@agence-differente.fr')
                    ->setBody(
                        $this->renderView(
                            'ADAppsoBundle:contact:mail.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('name')->getData(),
                                'email' => $form->get('email')->getData(),
                                'message' => $form->get('message')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Hemos recibido tu email. ¡Gracias!');

                return $this->redirect( 'ok' );
            }
        }

        return $this->render(
            'ADAppsoBundle:contact:contact.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    public function okAction(Request $request)
    {
        if ( empty( $request->getSession()->getFlashBag()->flashes ) )
        {
            return $this->redirect( 'contact' );
        }
        return $this->render(
            'ADAppsoBundle:contact:ok.html.twig'
        );
    }
}