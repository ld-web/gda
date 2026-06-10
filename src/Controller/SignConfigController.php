<?php

namespace App\Controller;

use App\Entity\SignConfig;
use App\Form\SignConfigType;
use App\QueryGenerator\SignConfigQueryGenerator;
use App\Repository\SignConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sign/config')]
final class SignConfigController extends AbstractController
{
    #[Route(name: 'app_sign_config_index', methods: ['GET'])]
    public function index(SignConfigRepository $signConfigRepository): Response
    {
        return $this->render('sign_config/index.html.twig', [
            'sign_configs' => $signConfigRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sign_config_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signConfig = new SignConfig();
        $form = $this->createForm(SignConfigType::class, $signConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signConfig);
            $entityManager->flush();

            return $this->redirectToRoute('app_sign_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sign_config/new.html.twig', [
            'sign_config' => $signConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sign_config_show', methods: ['GET'])]
    public function show(SignConfig $signConfig): Response
    {
        return $this->render('sign_config/show.html.twig', [
            'sign_config' => $signConfig,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sign_config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SignConfig $signConfig, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignConfigType::class, $signConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sign_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sign_config/edit.html.twig', [
            'sign_config' => $signConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sign_config_delete', methods: ['POST'])]
    public function delete(Request $request, SignConfig $signConfig, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$signConfig->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($signConfig);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sign_config_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/generate-query', name: 'app_sign_config_generate_query', methods: ['GET'])]
    public function generateQuery(
        SignConfig $signConfig,
        SignConfigQueryGenerator $signConfigQueryGenerator
    ): Response {
        $query = $signConfigQueryGenerator->generate($signConfig);

        return $this->render('sign_config/generate_query.html.twig', [
            'query' => $query->getQuery(),
            'sign_config' => $signConfig,
        ]);
    }
}
