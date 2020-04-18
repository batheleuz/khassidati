<?php

namespace App\Controller;

use App\Repository\PdfRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class KhassidatiController
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @Route("/khassidati/pdfs", methods={"GET"})
     */
    public function pdfs(PdfRepository $pdfRepository)
    {
        $pdfs = $pdfRepository->findAll();

        return new JsonResponse([
            'success' => true,
            'pdfs' => $this->serializer->normalize($pdfs, 'json'),
        ]);
    }

    /**
     * @Route("/khassidati/pdfs/{type}", methods={"GET"})
     */
    public function pdfsByType(string $type, Request $request, PdfRepository $pdfRepository)
    {
        $limit = (int) $request->get('limit', 50);
        $offset = (int) $request->get('offset', 0);

        $pdfs = $pdfRepository->findBy(['type'=> $type], ['id' => 'DESC'], $limit, $offset);

        return new JsonResponse([
            'success' => true,
            'pdfs' => $this->serializer->normalize($pdfs, 'json'),
        ]);
    }
}