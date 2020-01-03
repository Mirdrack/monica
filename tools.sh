#!/bin/bash

RED="\x1B[1;31m"
GREEN="\x1B[1;32m"
WHITE="\x1B[0m"

function info() {
  echo -e "${GREEN}INFO: ${1}${WHITE}"
}

function error() {
  echo -e "${RED}ERROR: ${1}${WHITE}"
}

function helptext() {
  echo "This is a tools that helps to run the different scripts in the project."
  echo "Usage: ./tools.sh <option> "
  echo ""
  echo "Options are:"
  echo "    lint   |     Run Linter"
  echo "    test   |     Run tests"
  echo "    ca     |     Run code analyser"
  echo "    all    |     Run all the options"
  echo "    help   |     Show this text"
  exit 1
}

function lint() {
  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest ./vendor/bin/phpcs
}

function test() {
  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest ./vendor/bin/phpunit
}

function code_analyse() {
  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest php artisan code:analyse --level=1
}

function format_fix() {
  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest ./vendor/bin/phpcbf
}

function set_parameters_and_exec() {
  [[ $@ ]] || {
    helptext
    exit 0
  }

  case "$1" in
  lint)
    lint
    ;;
  test)
    test
    ;;
  ca)
    code_analyse
    ;;
  fix)
    format_fix
    ;;
  all)
    lint
    test
    code_analyse
    ;;
  help)
    helptext
    ;;
  *)
    error "Invalid option: $1" 1>&2
    ;;
  esac
}

set_parameters_and_exec "$@"
