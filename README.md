The S3 bucket and user associated with it are created via cloudformation.

First, You'll need to [install the AWS CLI](https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-install.html).

To create a new stack use the `create-stack` command.

```shell
aws --region us-east-2 cloudformation create-stack --stack-name bbbs-resource-bucket --template-body file://bucket_cloudformation.yaml --capabilities=CAPABILITY_IAM
```

To update the existing cloudformation stack
```
aws --region us-east-2 cloudformation update-stack --stack-name bbbs-resource-bucket --template-body file://bucket_cloudformation.yaml --capabilities=CAPABILITY_IAM
```

# To manage letsencrypt for the Lightsail box:


[Install ansible](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html)

Install python in the box
```
sudo apt-get update && sudo apt-get install python
```

Install the certbot dependency

```
ansible-galaxy install -r ansible_requirements.yaml
```

Run the ansible scripts (the site will be temporarily online while it fetches the cert)

```
ansible-playbook -i hosts.ini install_certbot.yaml
```
