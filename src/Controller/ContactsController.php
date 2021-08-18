<?php

namespace App\Controller;

use App\Entity\Contacts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods:'GET')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'ContatPage',
        ]);
    }
    #[Route('/contact', name: 'contactStore', methods: 'POST')]
    public function contactStore(Request $request, MailerInterface $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contact=new Contacts();
        $contact->setName($request->get('name'));
        $contact->setFirstname($request->get('firstname'));
        $contact->setEmail($request->get('email'));
        $contact->setPhone($request->get('phone'));
        $contact->setObject($request->get('object'));
        $entityManager->persist($contact);
        $entityManager->flush();

        $email = (new Email())
            ->from('webmaster@monsite.fr')
            ->to($contact->getEmail())

            ->subject($contact->getObject())
            ->text($request->get('message'));


        $mailer->send($email);
        return $this->redirectToRoute('homepage');
        // dd($contact);
    }
    #[Route('/office', name: 'office', methods:'GET')]
    public function contactList(): Response
    {
        $contacts= $this->getDoctrine()->getRepository(Contacts::class)->findAll();
        return $this->render('pages/office.html.twig', [
            'controller_name' => 'ContatPage',
            'contacts'=>$contacts
        ]);
    }


}
