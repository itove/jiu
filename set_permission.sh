#!/bin/bash
#
# vim:ft=sh

############### Variables ###############

############### Functions ###############

############### Main Part ###############
find public/img/ -type d -exec sudo chmod 775 {} \;

find public/img/ -type f -exec sudo chmod 664 {} \;

sudo chmod 775 public/media/
sudo chmod 775 public/media/thumbnail/
sudo chmod 775 qr/
