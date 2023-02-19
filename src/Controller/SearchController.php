<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Enum\SearchTypeEnum;
use Meilisearch\Bundle\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
final class SearchController extends AbstractController
{
    #[Route('/', name: 'app_search', methods: ['GET'])]
    public function globalSearch(Request $request, SearchService $searchService): Response
    {
        $query = trim($request->get('q', ''));
        $page = (int) $request->get('p', 1);
        $type = SearchTypeEnum::tryFrom($request->get('t', SearchTypeEnum::Profiles->value)) ?? SearchTypeEnum::Profiles;
        $searchClass = match ($type) {
            SearchTypeEnum::Profiles => Profile::class,
            default => Profile::class,
        };

        $search = !empty($query) ? $searchService->rawSearch($searchClass, $query, [
            ...SearchTypeEnum::getSearchOptions($type),
            'hitsPerPage' => 10,
            'page' => $page,
            'highlightPreTag' => '<em class="bg-warning dark:bg-secondary">'
        ]) : null;

        return $this->render('search/index.html.twig', [
            'search' => $search,
            'type' => $type->value,
        ]);
    }
}
