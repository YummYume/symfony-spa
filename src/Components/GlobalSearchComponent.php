<?php

namespace App\Components;

use App\Entity\Profile;
use App\Enum\SearchTypeEnum;
use Meilisearch\Bundle\SearchService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('global_search')]
final class GlobalSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(private readonly SearchService $searchService)
    {
    }

    #[ExposeInTemplate]
    public function getSearchResult(): ?array
    {
        if (empty($this->query)) {
            return null;
        }

        $results = [];

        $profiles = $this->searchService->rawSearch(Profile::class, $this->query, [
            ...SearchTypeEnum::getSearchOptions(SearchTypeEnum::Profiles),
            'highlightPreTag' => '<em class="bg-warning dark:bg-secondary">',
            'limit' => 5,
        ]);

        if (!empty($profiles['hits'])) {
            $results['profiles'] = $profiles;
        }

        return $results;
    }
}
