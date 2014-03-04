#!/bin/bash

SOURCE=$(dirname "${BASH_SOURCE[0]}") 
cd $SOURCE
./php -n ./main.php ${@:1}
