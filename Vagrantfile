project_slug  = "poligon58"
document_root = "public"
vm_ip_address = "127.0.0.1"

Vagrant.configure("2") do |config|
  
  config.vm.box = "hashicorp/bionic64"
  config.vm.hostname = project_slug
    
  config.vm.network :forwarded_port, guest: 80, host: 4558
  
  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
  end
  
  # права на файлы/папки для юзера и группы
  config.vm.synced_folder "./", "/var/www/#{project_slug}", owner: "vagrant", group: "www-data", mount_options: ["dmode=775,fmode=664"]
  #config.vm.synced_folder "./", "/var/www/#{project_slug}", type: "nfs"
  
  config.vm.provision :shell, path: "bootstrap.sh", :args => "Vagrant #{project_slug} #{document_root}"
  #config.vm.provision :shell, inline: ""
  
end
