<?php

namespace App\Controller\Api\User;


use App\DTO\User\ChangePasswordDto;
use App\DTO\User\CreateSellerDto;
use App\DTO\User\SellerResponseDto;
use App\DTO\User\UpdateSellerDto;
use App\Entity\User\Seller;
use App\Service\User\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[OA\Tag(name: 'Sellers')]
#[Route('/api/sellers', name: 'api_sellers_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }


    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(
        summary: "Create a new seller account",
        description: "Registers a new user with the 'seller' role."
    )]
    #[OA\RequestBody(
        description: "Data needed to create a new seller.",
        required: true,
        content: new Model(type: CreateSellerDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: "Seller created successfully. Returns the new seller's public data.",
        content: new Model(type: SellerResponseDto::class)
    )]
    #[OA\Response(
        response: 409,
        description: "Conflict. The email address is already in use.",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Data conflict'),
                new OA\Property(property: 'message', type: 'string', example: 'This email is already used.')
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Validation Error. The data provided in the request body is invalid (e.g., blank field, invalid email format)."
    )]
    #[OA\Response(
        response: 400,
        description: "Bad Request. A generic error occurred."
    )]
    public function create(#[MapRequestPayload] CreateSellerDto $dto): JsonResponse
    {
        try {
            $responseDto = $this->userService->createSeller($dto);
            return new JsonResponse($responseDto, Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException) {
            return new JsonResponse([
                'error' => 'Data conflict',
                'message' => 'This email is already used.'
            ], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An unexpected error occurred',
                'message' => 'Could not create seller.',
                'debug' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }



    #[Route('/profile', name: 'profile', methods: ['GET'])]
    #[Security(name: 'bearerAuth')]
    #[OA\Get(
        summary: "Get current seller's profile",
        description: "Retrieves the public profile data of the currently authenticated seller, including their vehicles."
    )]
    #[OA\Response(
        response: 200,
        description: "Returns the seller's profile.",
        content: new Model(type: SellerResponseDto::class)
    )]
    #[OA\Response(response: 403, description: "Access Denied (not authenticated).")]
    public function getProfile(#[CurrentUser] ?Seller $seller): JsonResponse
    {
        if (!$seller) {
            return new JsonResponse(['message' => 'Access denied.'], Response::HTTP_FORBIDDEN);
        }

        $responseDto = $this->userService->getSeller($seller);
        return new JsonResponse($responseDto);
    }



    #[Route('/profile', name: 'update', methods: ['PUT'])]
    #[Security(name: 'bearerAuth')]
    #[OA\Put(
        summary: "Update current seller's profile",
        description: "Allows the authenticated seller to update their own profile information."
    )]
    #[OA\RequestBody(
        description: "The seller data to update. Only non-null fields will be updated.",
        required: true,
        content: new Model(type: UpdateSellerDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: "Profile updated successfully.",
        content: new Model(type: SellerResponseDto::class)
    )]
    #[OA\Response(response: 403, description: "Access Denied (not authenticated).")]
    #[OA\Response(response: 409, description: "Conflict. The new email is already in use.")]
    #[OA\Response(response: 422, description: "Validation error. The request body is invalid.")]
    public function update(
        #[CurrentUser] ?Seller $seller,
        #[MapRequestPayload] UpdateSellerDto $dto
    ): JsonResponse {
        if (!$seller) {
            return new JsonResponse(['message' => 'Access denied.'], Response::HTTP_FORBIDDEN);
        }

        try {
            $responseDto = $this->userService->updateSeller($seller, $dto);
            return new JsonResponse($responseDto);
        } catch (UniqueConstraintViolationException) {
            return new JsonResponse([
                'error' => 'Data conflict',
                'message' => 'This email is already in use.'
            ], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An unexpected error occurred',
                'message' => 'Could not update profile.',
                'debug' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }



    #[Route('/profile', name: 'delete', methods: ['DELETE'])]
    #[Security(name: 'bearerAuth')]
    #[OA\Delete(
        summary: "Delete current seller's account",
        description: "Allows the authenticated seller to permanently delete their own account and all associated data."
    )]
    #[OA\Response(response: 204, description: "Account deleted successfully.")]
    #[OA\Response(response: 403, description: "Access Denied (not authenticated).")]
    public function delete(#[CurrentUser] ?Seller $seller): JsonResponse
    {
        if (!$seller) {
            return new JsonResponse(['message' => 'Access denied.'], Response::HTTP_FORBIDDEN);
        }

        try {
            $this->userService->deleteSeller($seller);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    #[Route('/profile/change-password', name: 'change_password', methods: ['POST'])]
    #[Security(name: 'bearerAuth')]
    #[OA\Post(
        summary: "Change current seller's password",
        description: "Allows the authenticated seller to change their password."
    )]
    #[OA\RequestBody(
        description: "Requires the current password for verification and the new password.",
        required: true,
        content: new Model(type: ChangePasswordDto::class)
    )]
    #[OA\Response(
        response: 200,
        description: "Password changed successfully.",
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', example: 'Password successfully changed.')]
        )
    )]
    #[OA\Response(response: 400, description: "Bad Request (e.g., current password does not match).")]
    #[OA\Response(response: 403, description: "Access Denied (not authenticated).")]
    #[OA\Response(response: 422, description: "Validation Error (e.g., new password is too short).")]
    public function changePassword(
        #[CurrentUser] ?Seller $seller,
        #[MapRequestPayload] ChangePasswordDto $dto
    ): JsonResponse {
        if (!$seller) {
            return new JsonResponse(['message' => 'Access denied.'], Response::HTTP_FORBIDDEN);
        }

        try {
            $this->userService->changePassword($seller, $dto);
            return new JsonResponse(['message' => 'Password successfully changed.']);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}