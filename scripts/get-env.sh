#!/usr/bin/env bash

# Get the os
unameOut="$(uname -s)"
case "${unameOut}" in
    Linux*)     ENVIRONMENT=Linux;;
    Darwin*)    ENVIRONMENT=Mac;;
    CYGWIN*)    ENVIRONMENT=Cygwin;;
    MINGW*)     ENVIRONMENT=MinGw;;
    *)          ENVIRONMENT="UNKNOWN:${unameOut}"
esac

echo $ENVIRONMENT
