<?php
namespace app\admin\controller;
use think\Controller;
class Adminlogin extends controller
{
    function index(){
        if (request()->isPost()) {
            $yanzhengma =$_POST['yanzhengma'];
            $name =$_POST['name'];
            $password =$_POST['password'];
            if(!captcha_check($yanzhengma))
            {
                return $this->error("验证码错误",'index');
            }else{
                $result = db('admin')->where(['username'=>$name,"password"=>$password])->find();
                if (empty($result)) {
                    return $this->error("账号或密码错误",'index');
                }else{
                    session("admin-tea",$result['id']);
                    $this->redirect('admin/index/index');
                }
            }
        }
        return $this->fetch();
    }
    function logoff(){
        session(null);
        $this->redirect('admin/adminlogin/index');
    }
}