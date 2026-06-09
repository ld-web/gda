<?php

namespace App\Controller;

use App\Entity\StaplingConfig;
use App\Form\StaplingConfigType;
use App\QueryGenerator\StaplingConfigQueryGenerator;
use App\Repository\StaplingConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stapling/config')]
final class StaplingConfigController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StaplingConfigQueryGenerator $staplingConfigQueryGenerator,
    ) {
    }

    #[Route(name: 'app_stapling_config_index', methods: ['GET'])]
    public function index(): Response
    {
        $staplingConfigRepository = $this->entityManager->getRepository(StaplingConfig::class);

        return $this->render('stapling_config/index.html.twig', [
            'stapling_configs' => $staplingConfigRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stapling_config_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $staplingConfig = new StaplingConfig();
        $form = $this->createForm(StaplingConfigType::class, $staplingConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($staplingConfig);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_stapling_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stapling_config/new.html.twig', [
            'stapling_config' => $staplingConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stapling_config_show', methods: ['GET'])]
    public function show(StaplingConfig $staplingConfig): Response
    {
        return $this->render('stapling_config/show.html.twig', [
            'stapling_config' => $staplingConfig,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stapling_config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StaplingConfig $staplingConfig): Response
    {
        $form = $this->createForm(StaplingConfigType::class, $staplingConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_stapling_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stapling_config/edit.html.twig', [
            'stapling_config' => $staplingConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stapling_config_delete', methods: ['POST'])]
    public function delete(Request $request, StaplingConfig $staplingConfig): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staplingConfig->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($staplingConfig);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_stapling_config_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/generate-query', name: 'app_stapling_config_generate_query', methods: ['GET'])]
    public function generateQuery(Request $request, StaplingConfig $staplingConfig): Response
    {
        $query = $this->staplingConfigQueryGenerator->generate($staplingConfig);

        return $this->render('stapling_config/generate_query.html.twig', [
            'query' => $query->getQuery(),
            'stapling_config' => $staplingConfig,
        ]);
    }
}
