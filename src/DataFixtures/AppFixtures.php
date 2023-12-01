<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use http\Client\Curl\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::createMany(20);
        $users[] = UserFactory::createOne([
            'email' => 'bvsmail@ukr.net',
            'isVerified' => true,
            'password' => 'beresa79'
        ]);

        PostFactory::createOne(['author' => $users[0]]);
        PostFactory::createMany(130, function () use ($users) {
            $result = [];
            if ( $users)  {
                $result['author'] = $users[random_int(0, count($users) - 1)];
                if (random_int(1,100) <= 80) {
                    $post = PostFactory::random();
                    if ($post && $post->getId()) {
                        $result['parent'] = $post;
                    }
                }
            }
            return $result;
        });

        $manager->flush();
    }
}
