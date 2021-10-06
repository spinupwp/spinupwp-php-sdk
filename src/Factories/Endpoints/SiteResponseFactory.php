<?php

namespace DeliciousBrains\SpinupWp\Factories\Endpoints;

use Faker\Generator;

class SiteResponseFactory extends BaseFactory
{
    public Generator $faker;

    public function siteDefinition(): array
    {
        return [
            'data' => [
                'domain' => $this->faker->domainName(),
            ],
        ];
    }

    /**
     * sites show
     * 'GET /sites/{id}'
     */
    public function getSite(): GeneratedResource
    {
        return $this->process($this->siteDefinition());
    }

    /**
     * sites index
     * 'GET /sites'
     */
    public function listSites(int $count = 3): GeneratedResource
    {
        if ($count < 0) {
            $count = 3;
        }

        $sites = [];

        for ($i = 0; $i < $count; $i++) {
            $sites[] = $this->siteDefinition();
        }

        $responseData = [
            'data' => $sites,
            'pagination' => [
                'previous' => null,
                'next' => null,
                'count' => count($sites),
            ],
        ];

        return $this->process($responseData);
    }
}