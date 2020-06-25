#!/bin/bash

check_img() {
	FPATH="$1"
	FNAME=$(basename "$FPATH")
	FNAME2=$(echo "$FNAME"| cut -f 1 -d '.' | sed "s/_small//")
	OGREP=$(grep -R -l -F "$FNAME2" ../ --exclude-dir=.svn --exclude-dir=cache)
	if [ -n "$OGREP" ]; then
		#echo "Found match for image $FPATH in files"
		#echo "$OGREP"
		:
	else 
		echo "No match found for image $FPATH"
		# delete from SVN
		#svn delete "$FPATH"
	fi
}

cd ../themes

export -f check_img
for DIR in images */images; do
	find "$DIR" -type f ! -name 'chart_*' -print0 | xargs -0 -I{} bash -c 'check_img "{}"'
done

cd $OLDPWD