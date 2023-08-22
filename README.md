# PHP Fixture Pref\'s
[![Support Ukraine Badge](https://bit.ly/support-ukraine-now)](https://github.com/support-ukraine/support-ukraine)

This library helps to organize dependencies between different fixtures in your project.

for example, you need to create 10 users
- each user must make 10 posts in post-feed
- each user must make 25 comments under post
- each user must like 100 comments
- each user must report 10 posts
- each user must report 100 comments

## Installation

```
composer require --dev u1tr0n/fixturepref
```

## Examples

- [General use](./doc/en/general_use.md)
- [implement own IdGenerator](./doc/en/implement_own_id_generator.md)
- [implement own PrefKey](./doc/en/implement_own_pref_key.md)

### TODO

- translate documentation to other languages 
- add examples 