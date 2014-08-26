line-api-server
===============


#Development

```sh
git clone https://github.com/up9cloud/line-api-server.git
```

#Update dependencies

##install thrift

[thrift][thrift install]
```sh
#debian
sudo apt-get install libboost-dev libboost-test-dev libboost-program-options-dev libboost-system-dev libboost-filesystem-dev libevent-dev automake libtool flex bison pkg-config g++ libssl-dev
wget 'http://mirror.reverse.net/pub/apache/thrift/0.9.1/thrift-0.9.1.tar.gz'
tar -xf thrift-0.9.1.tar.gz
cd thrift-0.9.1
./configure
make && make install
```

##copy thrift lib
```sh
rm -fr lib
cp -r <thrift folder>/lib/ .
```

##download line thrift spec file

[line unofficial spec][line-protocol]
```sh
rm -fr line-protocol
mv <line-protocol> line-protocol
```

##generate line thrift protocol code

[thrift generate file][thrift tutorial]
```sh
thrift -r --gen <language> line-protocol/line.thrift
thrift -r --gen <language> line-protocol/line_main.thrift
```

fix php namespace
```sh
./gen-php/fixPhpNamespace.sh
```


[thrift install]:http://thrift.apache.org/docs/install/
[thrift tutorial]:http://thrift.apache.org/tutorial/
[line-protocol]:http://altrepo.eu/git/line-protocol.git/