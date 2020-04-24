<?php

namespace App\Controller;

use App\Entity\Pdf;
use App\Repository\PdfRepository;
use App\Tools\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAjaxController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ajax/pdf", methods={"POST"})
     */
    public function addPdf(Request $request, FileUploader $fileUploader)
    {
        $name = $request->get('name');
        $type = $request->get('type');
        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file);

        $pdf = (new Pdf())
            ->setName($name)
            ->setFileName($fileName)
            ->setType($type)
            ->setFileUrl('uploads/'.$fileName)
        ;

        $this->entityManager->persist($pdf);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @Route("/ajax/pdf/{pdfId}", methods={"DELETE"})
     */
    public function removePdf(
        int $pdfId,
        PdfRepository $pdfRepository,
        FileUploader $fileUploader
    ) {
        $pdf = $pdfRepository->find($pdfId);

        try {
            $this->entityManager->remove($pdf);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            return new JsonResponse([
                'success' => false,
            ]);
        }
        try {
            $fileUploader->deleteFile($pdf->getFileName());
        } catch (\Exception $exception) {
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
