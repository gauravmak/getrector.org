<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Repository;

use Rector\Website\Blog\ValueObject\Post;
use Rector\Website\Blog\ValueObjectFactory\PostFactory;
use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PostRepository
{
    /**
     * @var string
     */
    private const POST_DIRECTORY = __DIR__ . '/../../data';

    private FinderSanitizer $finderSanitizer;

    private PostFactory $postFactory;

    /**
     * @var Post[]
     */
    private $posts = [];

    public function __construct(FinderSanitizer $finderSanitizer, PostFactory $postFactory)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->postFactory = $postFactory;

        $this->createPosts();
    }

    /**
     * @return Post[]
     */
    public function fetchAll(): array
    {
        return $this->posts;
    }

    public function findBySlug(string $slug): ?Post
    {
        foreach ($this->posts as $post) {
            if ($post->getSlug() === $slug) {
                return $post;
            }
        }

        return null;
    }

    private function createPosts(): void
    {
        $posts = [];
        foreach ($this->findPostMarkdownFileInfos() as $smartFileInfo) {
            $post = $this->postFactory->createFromFileInfo($smartFileInfo);
            $posts[$post->getId()] = $post;
        }

        $this->posts = $this->sortByDateTimeFromNewest($posts);
    }

    /**
     * @return SmartFileInfo[]
     */
    private function findPostMarkdownFileInfos(): array
    {
        $finder = new Finder();
        $finder->files()
            ->in(self::POST_DIRECTORY)
            ->name('*.md');

        return $this->finderSanitizer->sanitize($finder);
    }

    /**
     * @param Post[] $posts
     * @return Post[]
     */
    private function sortByDateTimeFromNewest(array $posts): array
    {
        usort($posts, function (Post $firstPost, Post $secondPost): int {
            return $secondPost->getDateTime() <=> $firstPost->getDateTime();
        });

        return $posts;
    }
}
