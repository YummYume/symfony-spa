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
            'highlightPreTag' => '<em class="bg-primary-200 dark:bg-primary-800">',
            'limit' => 5,
        ]);

        if (!empty($profiles['hits'])) {
            $results['profiles'] = [
                'results' => $profiles,
                'nameProperty' => 'username',
                'descProperty' => 'description',
                'slugProperty' => ['slug' => 'slug'],
                'route' => 'app_profile_show',
                'routeParam' => ['slug'],
            ];
        }

        return $results;
    }
}
