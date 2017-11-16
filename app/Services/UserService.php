<?php
/**
 * Created by PhpStorm.
 * User: yangtao
 * Date: 2017/10/18
 * Time: 15:51
 */
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserService
{
    /**
     * 注册用户
     * @param $userName
     * @param $password
     * @param $inviteCode
     * @throws \Exception
     */
    public function registerUser($userName, $password, $inviteCode){
        DB::beginTransaction();
        try{
            //使用邀请码
            if(!(new InviteCode())->useCode($inviteCode)){
                throw new \LogicException("邀请码无效");
            }

            //创建用户
            $isSuccess = User::create([
                'phone' => $userName,
                'password' => bcrypt($password),
                'invite_code' => $inviteCode,
                'reg_time' => date('Y-m-d H:i:s'),
                'reg_ip' => Request::ip(),
            ]);

            if(!$isSuccess){
                throw new \LogicException("注册失败");
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $error = "注册失败";
            if($e instanceof \LogicException){
                $error = $e->getMessage();
            }else{
                if(User::where('phone', $userName)->exists()){
                    $error = '该用户已注册';
                }
            }
            throw new \Exception($error);
        }
    }

    /**
     * 修改密码
     * @param $userName
     * @param $password
     * @throws \Exception
     */
    public function modifyPassword($userName, $password){
        try{
            $user = User::where("phone", $userName)->first();
            if(!$user){
                throw new \LogicException("用户不存在");
            }
            $user['password'] = bcrypt($password);

            $user->save();
        }catch (\Exception $e){
            if($e instanceof \LogicException){
                $error = $e->getMessage();
            }else{
                $error = '修改密码失败';
            }
            throw new \Exception($error);
        }
    }
}
