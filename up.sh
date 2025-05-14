echo "views ... \n"
curr_dir=`pwd`
cd $curr_dir"/frontend/views/"
scp -r * root@114.116.112.55:~/hewa/hewa/frontend/views/
echo "actions ... \n"
cd $curr_dir"/frontend/actions/"
scp -r * root@114.116.112.55:~/hewa/hewa/frontend/actions/
echo "controllers ... \n"
cd $curr_dir"/frontend/controllers/"
scp -r * root@114.116.112.55:~/hewa/hewa/frontend/controllers/
echo "messages ... \n"
cd $curr_dir"/frontend/messages/"
scp -r * root@114.116.112.55:~/hewa/hewa/frontend/messages/
echo "services ... \n"
cd $curr_dir"/common/services"
scp -r * root@114.116.112.55:~/hewa/hewa/common/services/
echo "template ... \n"
cd $curr_dir"/template"
scp -r * root@114.116.112.55:~/hewa/hewa/template/
echo "assets ... \n"
cd $curr_dir"/frontend/assets"
scp -r * root@114.116.112.55:~/hewa/hewa/frontend/assets/
echo "backend views ... \n"
cd $curr_dir"/backend/views/"
scp -r * root@114.116.112.55:~/hewa/hewa/backend/views/
echo "backend actions ... \n"
cd $curr_dir"/backend/actions/"
scp -r * root@114.116.112.55:~/hewa/hewa/backend/actions/
echo "backedn controllers ... \n"
cd $curr_dir"/backend/controllers/"
scp -r * root@114.116.112.55:~/hewa/hewa/backend/controllers/
