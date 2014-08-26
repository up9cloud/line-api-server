__DIR__="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )";
dirlist=(`ls $__DIR__/gen-php/*`);
#delete empty namespace
for f in ${dirlist[@]};do
    sed -i 's/namespace ;/namespace Line;/g' $f;
done