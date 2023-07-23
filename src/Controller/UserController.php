<?php

namespace App\Controller;

use App\Api\ApiResponse;
use App\Entity\User;
use App\QueryParams\UserQueryParams;
use App\Validator\UserQueryParamsValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    use ApiResponse;

    public function index(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $queryParams = new UserQueryParams([
            'is_active' => $request->query->get('is_active'),
            'is_member' => $request->query->get('is_member'),
            'last_login_start_at' => $request->query->get('last_login_start_at'),
            'last_login_end_at' => $request->query->get('last_login_end_at'),
            'user_type' => array_filter(explode(',', $request->query->get('user_type'))),
        ]);
        // Use a validator to validate parameters
        $validator = new UserQueryParamsValidator();
        $errors = $validator->validate($queryParams);

        // If there is a parameter validation error, return an error message
        if (!empty($errors)) {
            return ApiResponse::error('Invalid parameters', 400, $errors);
        }
        $queryBuilder = $entityManager->getRepository(User::class)->findByQueryParams($queryParams);
        // Use Doctrine's Paginator to paginate query results
        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $doctrinePaginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize)->getResult();
        // get total count
        $totalItems = $doctrinePaginator->count();
        // Convert the paginated results to an array
        $users = $queryBuilder->getResult();
        $items = [];
        foreach ($users as $user) {
            $items[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'is_active' => $user->getIsActive(),
                'is_member' => $user->getIsMember(),
                'last_login_at' => $user->getLastLoginAt(),
                'user_type' => $user->getUserType(),
            ];
        }
        $data = [
            'totalItems' => $totalItems,
            'currentPage' => $page,
            'pageSize' => $pageSize,
            'items' => $items,
        ];

        return ApiResponse::success($data);
    }
}
