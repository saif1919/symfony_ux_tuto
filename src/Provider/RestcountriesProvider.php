<?php

namespace App\Provider;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestcountriesProvider
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'RESTCOUNTRIES_API')] private string $url
    ) {}

    public function getEuropeStat(?string $subregion = null)
    {
        if(!is_null($subregion) && $subregion !== ''){
           $this->url = "https://restcountries.com/v3.1/subregion/$subregion?fields=name,region,capital,population,subregion";
        }
        $response = $this->client->request(
            'GET',
            $this->url
        );
        return $response->toArray();
    }
}
