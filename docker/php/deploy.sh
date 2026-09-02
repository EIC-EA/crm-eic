#!/bin/bash

echo $CLUSTER_ARN $CROSS_ACCOUNT_ROLE_ARN $ORG_NAME $APP_NAME $SERVICE_NAME

read AK SK ST <<<$(aws sts assume-role --role-arn $CROSS_ACCOUNT_ROLE_ARN --role-session-name PipelineDrush --query 'Credentials.[AccessKeyId,SecretAccessKey,SessionToken]' --output text)
export AWS_ACCESS_KEY_ID=$AK AWS_SECRET_ACCESS_KEY=$SK AWS_SESSION_TOKEN=$ST
sudo yum install -y https://s3.amazonaws.com/session-manager-downloads/plugin/latest/linux_64bit/session-manager-plugin.rpm expect
TASK=$(aws ecs list-tasks --cluster $CLUSTER_ARN --service-name $SERVICE_NAME --desired-status RUNNING --max-items 1 --query taskArns --output text | head -n 1); function drush() { unbuffer aws ecs execute-command --interactive --command "./vendor/bin/drush $1 --verbose --debug" --cluster $CLUSTER_ARN --container php --task $TASK; }; function cv() { unbuffer aws ecs execute-command --interactive --command "./vendor/bin/cv $1 --verbose --debug" --cluster $CLUSTER_ARN --container php --task $TASK; }; export DRUSH=$(declare -f drush); export CV=$(declare -f cv)
echo "Getting status of EIC/SRM ..."
eval "$DRUSH"; for cmd in "core:status" "updatedb:status" "config:status"; do drush $cmd; done
eval "$CV"; cv "status -v"
echo "Putting EIC/SRM in maintenance mode ..."
eval "$DRUSH"; drush "state:set system.maintenance_mode 1"
echo "Deploying EIC/SRM ..."
eval "$DRUSH"; drush "deploy"
echo "Deploying EIC/SRM ..."
eval "$CV"; cv "upgrade:db"; cv "flush"
echo "Removing EIC/SRM from maintenance ..."
eval "$DRUSH"; drush "state:set system.maintenance_mode 0"
echo "Getting status of EIC/SRM ..."
eval "$DRUSH"; for cmd in "core:status" "updatedb:status" "config:status"; do drush $cmd; done
eval "$CV"; cv "status -v"
echo "Deployment of EIC/SRM completed ..."

drush status
drush config:status
drush updatedb:status
cv status -v 

drush state:set system.maintenance_mode 1
drush --verbose --debug deploy
cv upgrade:db
cv flush
drush state:set system.maintenance_mode 0

drush status
drush config:status
drush updatedb:status
cv status -v