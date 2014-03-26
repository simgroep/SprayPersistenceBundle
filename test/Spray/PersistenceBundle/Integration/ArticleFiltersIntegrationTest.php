<?php

namespace Spray\PersistenceBundle\Integration;

use DateInterval;
use DateTime;
use Spray\PersistenceBundle\EntityFilter\Common\Ascending;
use Spray\PersistenceBundle\EntityFilter\Common\Descending;
use Spray\PersistenceBundle\Integration\TestAssets\EntityFilter\ArticlesCurrentlyPublished;
use Spray\PersistenceBundle\Integration\TestAssets\EntityFilter\ArticlesPublishedSince;

/**
 * ArticleFiltersIntegrationTest
 */
class ArticleFiltersIntegrationTest extends AbstractFilterIntegrationTestCase
{
    const ARTICLE_CLASS = 'Spray\PersistenceBundle\Integration\TestAssets\Entity\Article';
    
    public function testFilterArticlesCurrentlyPublished()
    {
        $articles = $this->createRepositoryFilter(static::ARTICLE_CLASS);
        $articles->filter(new ArticlesCurrentlyPublished());
        $this->assertCount(1, $articles);
    }
    
    public function testFilterArticlesPublishedSince()
    {
        $publishedSince = new DateTime();
        $publishedSince->sub(DateInterval::createFromDateString('11 days'));
        $articles = $this->createRepositoryFilter(static::ARTICLE_CLASS);
        $articles->filter(new ArticlesPublishedSince(), $publishedSince);
        $this->assertCount(2, $articles);
    }
    
    public function testFilterArticlesAscendingByPublicationDate()
    {
        $articles = $this->createRepositoryFilter(static::ARTICLE_CLASS);
        $articles->filter(new Ascending('publishedAt'));
        $this->assertEquals(3, $articles->current()->getId());
    }
    
    public function testFilterArticlesDescendingById()
    {
        $articles = $this->createRepositoryFilter(static::ARTICLE_CLASS);
        $articles->filter(new Descending('id'));
        $this->assertEquals(3, $articles->current()->getId());
    }
}
