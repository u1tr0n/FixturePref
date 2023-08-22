# implement own IdGenerator

By default, this library use ``random_bytes`` function with ``$keySize = 36`` to make unique key for storage your objects.

In some case you may use ``IdGenerator`` with better performance, or you need more unique keys.

in this case you can implement your own ``IdGenerator`` how its work:

## Make you own Id Generator Class:

_For example, based on UuidV4_ 

File: ``MyOwnIdGenerator.php``

```php
<?php

namespace App\Infastructure\Generator;

use ultron\FixturePref\Generators\IdGeneratorInterface;
use Symfony\Component\Uid\UuidV4;

final class MyOwnIdGenerator implements IdGeneratorInterface
{
    public function createId(): string
    {
        return (new UuidV4)->toRfc4122();
    }
}
```


[⬅️ Back to main README.MD](../../README.md)

