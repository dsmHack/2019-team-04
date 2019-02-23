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
