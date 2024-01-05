<?php

namespace App\Tests\Repository;

use App\DataFixtures\PostFixture;
use App\DataFixtures\UserFixture;
use App\DataFixtures\UserPypPostFixture;
use App\Entity\User;
use App\Entity\UserPypPost;
use App\Tests\Base;

class UserPypPostRepositoryTest extends Base
{
    public function testGetAllPostsForUser(): void
    {
        $this->loadFixturesParent([
            UserFixture::class,
            PostFixture::class,
            UserPypPostFixture::class,
        ]);

        $em = $this->getEntityManager();

        $count = 0;
        foreach ($em->getRepository(UserPypPost::class)->getAllPostsForUser($em->getRepository(User::class)->find(1)) as $post) {
            ++$count;
        }

        self::assertEquals(2, $count);
    }
}
