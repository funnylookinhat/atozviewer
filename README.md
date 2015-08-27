# ATOZ Viewer

Load an api.json file from ATOZ into a fairly useful web template.

## Getting it Running

Get the source all setup:

```
git clone https://github.com/funnylookinhat/atozviewer.git
cd atozviewer
curl -sS https://getcomposer.org/installer | php
./composer.phar install
```

Copy in your api.json file to the root of the project.

```
cp ~/some/path/to/atoz.json ./api.json
```

And run it:

```
./run
```

You can find it in your browser at http://localhost:4321