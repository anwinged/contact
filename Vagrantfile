# -*- mode: ruby -*-
# vi: set ft=ruby :

ENV["LC_ALL"] = "en_US.UTF-8"

# For installing ansible_local from pip on guest
Vagrant.require_version ">= 1.8.3"


Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/xenial64"

  config.vm.provider "virtualbox" do |vb|
    vb.memory = "1024"
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "ansible/provision.yml"
    ansible.galaxy_role_file = "ansible/requirements.yml"
    ansible.galaxy_roles_path = "ansible/galaxy.roles"
    ansible.sudo = true
  end

  config.vm.network "forwarded_port", guest: 80, host: 8000, auto_correct: true
  config.vm.network "forwarded_port", guest: 8025, host: 8025, auto_correct: true
  config.vm.network "forwarded_port", guest: 9001, host: 9001, auto_correct: true
  config.vm.network "forwarded_port", guest: 27017, host: 27017, auto_correct: true

end
