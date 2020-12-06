<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Brief;
use App\Repository\BriefRepository;

class BriefCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $briefRepo;

    public function __construct(BriefRepository $briefRepo)
    {
        $this->briefRepo = $briefRepo;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Brief::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        //dd($operationName,$context);
        if ($operationName == "get_briefs")
        {
            $resourceClass = $this->briefRepo->findAll();
        }
        return $resourceClass;
    }
}