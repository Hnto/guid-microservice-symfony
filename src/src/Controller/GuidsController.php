<?php

namespace App\Controller;

use App\Entity\Guid;
use App\Responses\JsonApiResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuidsController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list()
    {
        $guidRepository = $this->getDoctrine()->getRepository(Guid::class);
        
        /** @var Guid[] $guids */
        $guids = $guidRepository->findBy([
            'status' => Guid::GUID_STATUS_ISSUED
        ]);

        $data = [];
        foreach ($guids as $guid) {
            $data[] = [
                'guid' => $guid->getValue(),
                'status' => $guid->getStatus(),
                'created_at' => !empty($guid->getCreatedAt()) ? $guid->getCreatedAt()->format('d-m-Y H:i:s') : null,
            ];
        }

        return new JsonApiResponse($data);
    }

    /**
     * @param string $guid
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show(string $guid)
    {
        $guidRepository = $this->getDoctrine()->getRepository(Guid::class);

        /** @var Guid $guid */
        $guid = $guidRepository->find($guid);

        if (null === $guid) {
            throw new NotFoundHttpException('Requested guid was not found');
        }

        return new JsonApiResponse([
            'guid' => $guid->getValue(),
            'assigned_to' => $guid->getAssignedTo(),
            'status' => $guid->getStatus(),
            'created_at' => !empty($guid->getCreatedAt()) ? $guid->getCreatedAt()->format('d-m-Y H:i:s') : null,
            'assigned_at' => !empty($guid->getAssignedAt()) ? $guid->getAssignedAt()->format('d-m-Y H:i:s') : null,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create()
    {
        $guid = new Guid();

        $guid->setCreatedAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($guid);

        $entityManager->flush();

        return new JsonApiResponse(
            [
                'guid' => $guid->getValue(),
                'status' => $guid->getStatus(),
                'created_at' => $guid->getCreatedAt()->format('d-m-Y H:i:s'),
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $guidRepository = $entityManager->getRepository(Guid::class);

        $postData = new ArrayCollection((array) json_decode($request->getContent(), true));

        try {
            /** @var Guid $guid */
            $guid = $guidRepository->find($postData->get('guid'));
        } catch (\Exception $exception){
            throw new BadRequestHttpException('Invalid request.');
        }

        if (null === $guid) {
            throw new NotFoundHttpException('Could not find provided guid.');
        }

        if ($guid->isAssigned()) {
            throw new ConflictHttpException('Guid is already assigned.');
        }

        $guid->assignTo($postData['assign_to']);
        $guid->setAssignedAt(new \DateTime());

        $entityManager->persist($guid);
        $entityManager->flush();

        return new JsonApiResponse([
            'guid' => $guid->getValue(),
            'status' => $guid->getStatus(),
            'assigned_to' => $guid->getAssignedTo(),
        ]);
    }
}
