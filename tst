#!/bin/bash
for F in `ls test/*.php`
do
	echo "Testing $F"
	php $F
	[ $? -ne 0 ] && echo "$F Test Failed" && exit 1
done
