# -*- mode: ruby -*-
# vi: set ft=ruby :


$script = <<SCRIPT
  sudo rm -rf /var/www/current/media && sudo ln -s /var/www/shared/media /var/www/current/media
  sudo rm -rf /var/www/current/var && sudo ln -s /var/www/shared/var /var/www/current/var
  sudo rm -rf /var/www/current/app/etc/local.xml && sudo ln -s /var/www/shared/app/etc/local.xml /var/www/current/app/etc
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.provision :shell, :inline => $script
end

Vagrant.configure("2") do |config|
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.box = "promoutfitters"

  config.vm.box_url = "http://s3-eu-west-1.amazonaws.com/opsway-containers/promoutfitters.box?AWSAccessKeyId=AKIAINARFTCXS32ZW7CQ&Expires=2032455183&Signature=%2FQsRpYYi6h0aiTzQnPM05Rerz5w%3D&x-amz-version-id=CwTw2HuutuHhnfmHu9YuvKnP_dbiO1kt"

  config.vm.network :forwarded_port, guest: 80, host: 80
  config.vm.network :forwarded_port, guest: 443, host: 443

  config.vm.synced_folder ".", "/vagrant"
  config.vm.synced_folder ".", "/var/www/current"

  Vagrant.configure("2") do |config|
    config.vm.provision :shell, :inline => $script
  end

end
