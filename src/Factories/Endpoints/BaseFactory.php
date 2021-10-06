<?php

namespace DeliciousBrains\SpinupWp\Factories\Endpoints;

use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Psr7\Response;

class BaseFactory
{
    public Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getSite(): GeneratedResource
    {
        $siteData = [
            'data' => [
                'domain' => $this->faker->domainName(),
            ],
        ];

        return $this->process($siteData);
    }

    protected function process(array $data): GeneratedResource
    {
        return new GeneratedResource($data, $this->buildResponse($data));
    }

    /**
     * The response should contain both the source data generated in this class for assertion needs
     * as well as the data set into a GuzzleHttp\Psr7\Response object we can slot into a Guzzle mock
     */
    protected function buildResponse(array $data, int $httpStatusCode = 200): Response
    {
        return new Response($httpStatusCode, [], json_encode($data));
    }
}