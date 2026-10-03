name: Architecture Tests

on:
push:
branches: [main, develop]
pull_request:
branches: [main, develop]

jobs:
arch-tests:
runs-on: ubuntu-latest

steps:
- name: Checkout code
uses: actions/checkout@v4

- name: Setup PHP
uses: shivammathur/setup-php@v2
with:
php-version: '8.2'
tools: composer:v2
coverage: none

- name: Install dependencies
run: composer install --prefer-dist --no-interaction --no-progress

- name: Run architecture tests
run: vendor/bin/pest tests/Architecture --colors=always
