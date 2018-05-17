#!/bin/bash
if [ $# -ne 2 ]; then
    echo "Usage $0 <test number> <method_regex_pattern>"
    echo "Example:"
    echo "$0 2 a_register_route_exists"
    exit 1
fi
TESTSUITE=$(printf 'UserStory%02dTest' $1)
vendor/bin/phpunit --filter "$TESTSUITE::$2" --testdox
