#!/bin/bash

check_tpl() {
	FPATH="$1"
	FNAME=$(basename "$FPATH")
	#FNAME2=$(echo "$FNAME"| cut -f 1 -d '.' | sed "s/_small//")
	OGREP=$(grep -R -l -F "$FNAME" ../../ --exclude-dir=.svn --exclude-dir=cache)
	if [ -n "$OGREP" ]; then
		#echo "Found match for image $FPATH in files"
		#echo "$OGREP"
		:
	else 
		echo "No match found for template $FPATH"
		# delete from SVN
		#svn delete "$FPATH"
	fi
}

cd ../Smarty/templates/

export -f check_tpl
find -type f -name '*.tpl' -print0 | xargs -0 -I{} bash -c 'check_tpl "{}"'

cd $OLDPWD