#!/bin/bash

set -xe

# Update parameters based on environment
composer run-script update-parameters

# Start Apache with the "right permissions"
/app/docker/apache/start_safe_perms -DFOREGROUND
