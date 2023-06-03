<?php

namespace ultron\FixturePref;

enum TestKeyType: string
{
    case TEST_NO_GENERATOR = 'TEST_NO_GENERATOR';
    case TEST_GET_ALL_PREF = 'TEST_GET_ALL_PREF';
    case TEST_ADD_PREF = 'TEST_ADD_PREF';
    case TEST_GET_PREF = 'TEST_GET_PREF';
    case TEST_GET_RANDOM_PREF = 'TEST_GET_RANDOM_PREF';
    case TEST_GET_RANDOM_PREF_OR_NULL = 'TEST_GET_RANDOM_PREF_OR_NULL';
}
