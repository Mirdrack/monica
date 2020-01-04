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
  if [[ -z $2 ]]; then
    echo "This is a tools that helps to run the different scripts in the project."
    echo "Usage: ./tools.sh <option> "
    echo ""
    echo "Options are:"
    echo "    lint            |     Run Linter"
    echo "    test [option]   |     Run tests"
    echo "    ca              |     Run code analyser"
    echo "    all             |     Run all the options"
    echo "    help [command]  |     Show help about the command"
  fi

  case $2 in
    test )
      echo "Usage: ./tools.sh help [option]"
      echo ""
      echo "Options for \"$1\" command are:"
      echo "    --report  |     Generates phpunit report"
      ;;
    *)
      error "Invalid command: $2"
      exit 1
    ;;
  esac
}

function lint() {
  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest ./vendor/bin/phpcs
}

function test() {
  # Validating report option
  if [[ -n $1 ]]; then
    if [[ "$1" != "--report" ]]; then
      error "Invalid option: $1" 1>&2
      exit 1
    fi
    REPORT="--coverage-html reports/phpunit"
  else
    REPORT=""
  fi

  docker run --rm --interactive --tty \
  --volume $PWD:/usr/src/app \
  --workdir /usr/src/app \
  mirdrack/docker-laravel:latest ./vendor/bin/phpunit $REPORT
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
  lint )
    lint
    ;;
  test )
    REPORT=$2
    test $REPORT
    ;;
  ca )
    code_analyse
    ;;
  fix )
    format_fix
    ;;
  all )
    lint
    test
    code_analyse
    ;;
  help )
    helptext $1 $2
    ;;
  *)
    error "Invalid option: $1" 1>&2
    ;;
  esac
}

set_parameters_and_exec "$@"
