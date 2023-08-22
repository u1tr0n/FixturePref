# General use

<span style="padding: 10px; border: solid 1px #FF4858; display: block; color: #FF4858; margin-bottom: 30px;">Disclaimer: Many aspects in these examples have been intentionally simplified within the context of illustrating the essence of this library, and these examples do not aim to educate about proper code organization in your application.</span>


For example, you need to make ``3 users``
Each ``user`` need to write from ``3`` to ``5`` post and like ``3 posts`` of other authors. 
Let's make it:


### Lets make users fixtures


File: ``UsersFixture.php``

```php
<?php

namespace App\User\DataFixtures;

use ultron\FixturePref\Key\DefaultPrefKey;

class UsersFixture extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('en_US');
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<3; $i++) {
            $user = new User(
                firstName: $this->faker->firstName(),
                lastName: $this->faker->lastName()
            );
            
            $manager->persist($user);
            
            $this->addReference(
                FixturePref::addPref(
                    key: new DefaultPrefKey(
                        group: 'users',
                        name: $user->getId() . ''
                    )
                ), $user
            );
        }
        
        $manager->flush();
    }
}

```

### Lets make posts fixtures


File: ``PostsFixture.php``

```php
<?php

namespace App\Post\DataFixtures;

use ultron\FixturePref\Key\DefaultPrefKey;

class PostsFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('en_US');
    }
    
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
    
    public function load(ObjectManager $manager): void
    {
        $usersId =  FixturePref::getAllPref(new DefaultPrefKey(group: 'users'));
        
        foreach ($usersId as $userPrefId) {
            $user = $this->getReference($userPrefId, User::class);
            $postMaxCount = random_int(3, 5);
            for ($i=0; $i<$postMaxCount; $i++) {
            $post = new Post(
                text: $this->faker->realTextBetween(500, 2_000, 4),
                author: $user,
            );
            
            $manager->persist($post);
            
            $this->addReference(
                FixturePref::addPref(
                    key: new DefaultPrefKey(
                        group: 'posts',
                        name: $post->getId() . ''
                    )
                ), $post
            );
        }
            
       
        
        $manager->flush();
    }
}

```


### Lets make Like's fixtures


File: ``LikesFixture.php``

```php
<?php

namespace App\Like\DataFixtures;

use ultron\FixturePref\Key\DefaultPrefKey;

class LikesFixture extends Fixture
{
    
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
    
    public function load(ObjectManager $manager): void
    {
        $usersId =  FixturePref::getAllPref(new DefaultPrefKey(group: 'users'));
        
        foreach ($usersId as $userPrefId) {
            $user = $this->getReference($userPrefId, User::class);
            
            $likeCount = 0;
            
            while ($likeCount <= 3) {
                $post = FixturePref::getRandomPref(new DefaultPrefKey(group: 'posts'));
                if ($post->getAuthor()->getId() !== $user->getId()) {
                    ++$likeCount;
                    $like = new Like(post: $post, user: $user);
                    
                    $manager->persist($like);
                    
                    $this->addReference(
                        FixturePref::addPref(
                            key: new DefaultPrefKey(
                                group: 'likes',
                                name: $like->getId() . ''
                            )
                        ), $like
                    );
                }
            }
        }
            
       
        
        $manager->flush();
    }
}

```


[⬅️ Back to main README.MD](../../README.md)