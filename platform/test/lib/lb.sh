#!/bin/bash
#
# lb 是本地构建的封装工具
# 
# === 特点：
#   - 从中控机上获取脚本和配置 
#   - 脚本对开发童鞋透明 
#
# by luxuechao@ganji.com
##################################################################
set -x
wget "http://192.168.2.217:8080/localbuild/.localbuild.tar" -O .localbuild.tar
tar xvf .localbuild.tar
chmod 755 .localbuild_test.sh
sh .localbuild_test.sh "$@"
