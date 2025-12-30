<?php

namespace App\Service\Public;

use App\DTO\Vehicle\CreateVehicleDto;
use App\Mapper\VehicleMapper;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Ce service est responsable de la communication avec une API externe (réelle ou mock)
 * pour récupérer les informations d'un véhicule à partir de sa plaque.
 */
class VehicleLookupService
{
    private const MOCK_API_BASE_URL = 'http://localhost:8001';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly VehicleMapper $mapper,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Looks up vehicle information by license plate using a mock API.
     *
     * Sends a GET request to the mock API endpoint with the provided plate number.
     * If the API returns a 404 status code, returns null.
     * Otherwise, maps the API response data to a CreateVehicleDto object.
     * Logs any exceptions that occur during the process and rethrows them.
     *
     * @param string $plate The license plate number to look up.
     * @return CreateVehicleDto|null The mapped vehicle data, or null if not found.
     * @throws \Exception If an error occurs during the API request or data mapping.
     */
    public function lookupByPlate(string $plate)
    {
        try {

            $url = self::MOCK_API_BASE_URL . '/mock/plate-lookup/' . urlencode($plate);

            $response = $this->client->request('GET', $url);

            $StatusCode = $response->getStatusCode();

            if ($StatusCode === 404) {
                return null;
            }

            $responseData = $response->toArray();

            return $this->mapper->fromApiToCreateVehicleDto($responseData['data']);

        } catch (\Exception $e) {
            $this->logger->error(
                'API failed.',
                [
                    'plate' => $plate,
                    'error' => $e->getMessage()
                ]
            );
            throw $e;
        }
    }

}