#!/bin/bash

git_folder=../$1 
git_url=$2
branch=$3

echo $git_folder $git_url $branch
if [ ! -d "$git_folder" ]; then
	echo "clone "$git_url
	git clone $git_url $git_folder
	# echo "mkdir -p ${git_folder}"
	# mkdir -p "$git_folder"
	# if [ -e "$git_folder" -a -d "$git_folder" ]; then
	# 	if [ -r "${git_folder}" -a -w "${git_folder}" ]; then
	# 		echo "cd" $git_folder
	# 		cd $git_folder
	# 		echo "git init"
	# 		git init
	# 		# echo "git config --global user.name apache"
	# 		# git config --global user.name apache
	# 		# echo "git config --global user.email apache"
	# 		# git config --global user.email apache
	# 		echo "git remote add origin " $2
	# 		git remote add origin $2
	# 		echo "git pull origin master"
	# 		git pull origin master
	# 	else
	# 		echo $git_folder "permission deny" && exit 0
	# 	fi
	# else
	# 	echo $git_folder "folder create faild" && exit 0
	# fi
else
	if [ -r "${git_folder}" -a -w "${git_folder}" ]; then
		echo "cd" $git_folder
		cd $git_folder
		git checkout $branch
		git reset --hard origin/$branch
		git clean -f
		git pull origin $branch
	else
		echo $git_folder "permission deny" && exit 0
	fi
fi

