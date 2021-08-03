https://github.com/bradtraversy/vagrant_lamp_traversy

## Состав Vagrantfile

config.vm.box - Operating System  
config.vm.network - How your host sees your box, host:port - in browser in main OS  
config.vm.provider - virtualbox  
config.vm.synced_folder - How your access files from your computer ( Как получить доступ к файлам с вашего компьютера )  
config.vm.provision - what we want setup

## Примеры команд vagrant

```
vagrant init
vagrant init ubuntu/trusty64
vagrant up

vagrant suspend
vagrant resume

vagrant halt

// если перенастраивался состав Vagrantfile
vagrant reload

vagrant ssh

vagrant destroy
```
### Примеры внутри ubuntu

```
sudo apt update
sudo apt install -y apache2
```

