# implement own PrefKey

## Make you own ID ``PrefKey`` Class:

For example, we need to use user ID of User, based on UuidV4, and his role to select by users by roles.
Let's make this:

Just for example, we make Enum with user roles:

File: ``UserRole.php``
```php
<?php
namespace App\User\UserRole;

enum UserRole: string
{
    case ROLE_AUTHOR = 'ROLE_AUTHOR';
    case ROLE_ADMIN = 'ROLE_ADMIN';
}
```
## Create user key class:

File: ``UserPrefKey.php``
```php

<?php

namespace App\Infastructure\Generator;

use Cassandra\Uuid;use ultron\FixturePref\Key\PrefKeyInterface;

final class UserPrefKey implements PrefKeyInterface
{
    public function __construct(
        private readonly UuidV4 $userId,
        private readonly UserRole $role,
    ) {
    }

    public function getKey(): string
    {
        return $this->userId->toRfc4122();
    }

    public function getGroup(): string
    {
        return self::class . $role->value;
    }
}
```

## From now, we can use our key class:

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
                lastName: $this->faker->lastName(),
                role: random_int(0, 100) <= 10 : UserRole::ROLE_ADMIN ? UserRole::ROLE_AUTHOR;
            );
            
            $manager->persist($user);
            
            $this->addReference(
                FixturePref::addPref(
                    key: new UserPrefKey(
                        userId: $user->getId(),
                        role: $user->getRole()
                    )
                ), $user
            );
        }
        
        $manager->flush();
    }
}

```

And from now, for example, we can get all authors can write posts:

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
        // From now in $usersId will be only array of users who have an Author role.
        $usersId =  FixturePref::getAllPref(new DefaultPrefKey(role: UserRole::ROLE_AUTHOR));
        
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


[⬅️ Back to main README.MD](../../README.md)