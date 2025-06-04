<?php

namespace App\External\MockApiPlate\Data;

use App\External\PlateMockApi\Dto\PlateLookupResponseDto;
use App\External\MockApiPlate\Mapper\PlateLookupMapper;

/**
 * Simule un fournisseur de données véhicule à partir d’une plaque.
 * Ce service agit comme un client d’API fictive avec une flotte en mémoire.
 */
class MockPlateDataProvider
{
    public function findByPlate($plate)
    {

        foreach ($this->getFleet() as $vehicle) {
            if ($vehicle['immat'] === $plate) {
                return PlateLookupMapper::fromArray($vehicle);
            }
        }

        return PlateLookupMapper::fromArray(['immat' => $plate]);
    }

    /**
     * Flotte simulée de 10 véhicules.
     */
    private function getFleet(): array
    {
        return [
            ['immat' => 'AA123AA', 'brand' => 'RENAULT', 'model' => 'CLIO', 'version' => '1.5 DCI', 'energy' => 'DIESEL', 'power' => 85, 'fiscal_power' => 4, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'CITADINE', 'weight_kg' => 1050, 'color' => 'ROUGE', 'registration_date' => '2016-05-12'],
            ['immat' => 'BB456BB', 'brand' => 'PEUGEOT', 'model' => '208', 'version' => 'PURETECH 100', 'energy' => 'ESSENCE', 'power' => 100, 'fiscal_power' => 5, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'CITADINE', 'weight_kg' => 980, 'color' => 'BLEU', 'registration_date' => '2019-07-23'],
            ['immat' => 'CC789CC', 'brand' => 'VOLKSWAGEN', 'model' => 'GOLF', 'version' => '1.6 TDI', 'energy' => 'DIESEL', 'power' => 115, 'fiscal_power' => 6, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'COMPACTE', 'weight_kg' => 1200, 'color' => 'GRIS', 'registration_date' => '2018-03-15'],
            ['immat' => 'DD321DD', 'brand' => 'HONDA', 'model' => 'CIVIC', 'version' => '1.8 I-VTEC', 'energy' => 'ESSENCE', 'power' => 140, 'fiscal_power' => 7, 'gearbox' => 'AUTOMATIQUE', 'doors' => 5, 'seats' => 5, 'body_type' => 'BERLINE', 'weight_kg' => 1250, 'color' => 'NOIR', 'registration_date' => '2017-11-09'],
            ['immat' => 'EE654EE', 'brand' => 'FIAT', 'model' => '500', 'version' => '1.2 69 CH', 'energy' => 'ESSENCE', 'power' => 69, 'fiscal_power' => 4, 'gearbox' => 'MANUELLE', 'doors' => 3, 'seats' => 4, 'body_type' => 'MINICITADINE', 'weight_kg' => 900, 'color' => 'BLANC', 'registration_date' => '2020-06-01'],
            ['immat' => 'FF987FF', 'brand' => 'AUDI', 'model' => 'A3', 'version' => '2.0 TDI', 'energy' => 'DIESEL', 'power' => 150, 'fiscal_power' => 8, 'gearbox' => 'AUTOMATIQUE', 'doors' => 5, 'seats' => 5, 'body_type' => 'COMPACTE', 'weight_kg' => 1350, 'color' => 'ARGENT', 'registration_date' => '2015-01-20'],
            ['immat' => 'GG159GG', 'brand' => 'MITSUBISHI', 'model' => 'ASX', 'version' => '1.6 MIVEC', 'energy' => 'ESSENCE', 'power' => 117, 'fiscal_power' => 6, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'SUV', 'weight_kg' => 1300, 'color' => 'VERT', 'registration_date' => '2014-09-17'],
            ['immat' => 'HH753HH', 'brand' => 'MERCEDES', 'model' => 'A180', 'version' => 'CDI 109', 'energy' => 'DIESEL', 'power' => 109, 'fiscal_power' => 5, 'gearbox' => 'AUTOMATIQUE', 'doors' => 5, 'seats' => 5, 'body_type' => 'COMPACTE', 'weight_kg' => 1280, 'color' => 'NOIR', 'registration_date' => '2016-04-30'],
            ['immat' => 'II357II', 'brand' => 'CITROEN', 'model' => 'C3', 'version' => '1.2 PURETECH', 'energy' => 'ESSENCE', 'power' => 82, 'fiscal_power' => 4, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'CITADINE', 'weight_kg' => 950, 'color' => 'GRIS CLAIR', 'registration_date' => '2018-08-12'],
            ['immat' => 'JJ951JJ', 'brand' => 'SKODA', 'model' => 'OCTAVIA', 'version' => '1.6 TDI 105', 'energy' => 'DIESEL', 'power' => 105, 'fiscal_power' => 5, 'gearbox' => 'MANUELLE', 'doors' => 5, 'seats' => 5, 'body_type' => 'BERLINE', 'weight_kg' => 1220, 'color' => 'BLEU MARINE', 'registration_date' => '2013-12-05'],
        ];
    }
}