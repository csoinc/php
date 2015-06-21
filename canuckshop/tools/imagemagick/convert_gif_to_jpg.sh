#!/bin/bash
#

dir=$1
for filename in $(ls "$dir")
do
   if [ -d "$filename" ] ; then
      echo "Directory: $filename"
   elif [ -h "$filename" ] ; then
      echo "Symlink: $filename"
   else
      case $filename in
      	 *.pdf)
      	    echo "get file: $filename"
            pdf2swf $filename
      ;;
      	 *.PDF)
      	    echo "Get file: $filename"
            pdf2swf $filename
      ;;
      esac
   fi
done

