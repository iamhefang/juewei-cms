<?php

namespace link\hefang\site\users\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\HashHelper;
use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\otp\TOTP;
use link\hefang\site\admin\models\FunctionModel;
use link\hefang\site\users\models\LoginModel;


class LoginController extends BaseController
{
    public function info(): BaseView
    {
        $login = $this->_getLogin();
        if ($login instanceof LoginModel) return $this->_apiSuccess($login);
        $token = $this->_cookie('token');
        if (StringHelper::isNullOrBlank($token)) {
            return $this->_apiSuccess($this->_checkLogin());
        }
        $cacheId = HashHelper::desDecrypt($token, Mvc::getProperty('cookie.salt', php_uname()));
        if (StringHelper::isNullOrBlank($cacheId)) {
            return $this->_apiSuccess($this->_checkLogin());
        }

        $info = Mvc::getCache()->get($cacheId);
        if (!is_array($info) || !isset($info['loginId'])) {
            return $this->_apiSuccess($this->_checkLogin());
        }
        try {
            $login = LoginModel::get($info['loginId']);
            if (!($login instanceof LoginModel) || !$login->isExist() || !$login->isEnable()) {
                return $this->_apiSuccess($this->_checkLogin());
            }
            $login->login($this)
                ->setIsPassedTotp(true)
                ->setIsLockedScreen(true)
                ->updateSession($this);
            return $this->_apiSuccess($login);
        } catch (\Throwable $e) {
            return $this->_apiSuccess($this->_checkLogin());
        }
    }

    public function login(): BaseView
    {
        $name = $this->_post("name");
        $pwd = $this->_post("pwd");
        $captcha = $this->_post('captcha');
        $autoLogin = ParseHelper::parseBoolean($this->_post('autoLogin'), false);
        $captchaEnable = Mvc::getConfig("captcha|login_enable", false);
        if ($captchaEnable) {
            $captchaLength = Mvc::getConfig('captcha|login_length', 6);
            if (StringHelper::isNullOrBlank($captcha)) {
                return $this->_apiFailed('请输入验证码');
            }
            if ($captcha === $captchaLength) {
                return $this->_apiFailed("验证码错误");
            }
        }
        if (StringHelper::isNullOrBlank($name)) {
            return $this->_apiFailed("请输入登录名");
        }
        if (strlen($pwd) !== 72) {
            return $this->_apiFailed("密码参数异常");
        }
        $hash = HashHelper::passwordHash($pwd, Mvc::getPasswordSalt());
        $totpEnable = Mvc::getConfig('login|2fa_enable', false);
        try {
            $login = LoginModel::find("(`login_name` = '$name' OR `phone` = '$name' OR `email` = '$name') AND `password` = '$hash'");
            if (!$login->isExist() || !($login instanceof LoginModel)) {
                return $this->_apiFailed('无法使用提供的用户名密码登录(0)');
            }
            if (!$login->isEnable()) {
                return $this->_apiFailed("无法使用提供的用户名密码登录(1)");
            }

            $login->login($this);
            $login->setAutoLoginNextTime($autoLogin);
            if ($totpEnable) {
                Mvc::getLogger()->debug("login", $login->getTotpToken());
                $login->setIsPassedTotp(StringHelper::isNullOrBlank($login->getTotpToken()))
                    ->setIsLockedScreen(!$login->isPassedTotp());
            } else {
                $login->setIsPassedTotp(true)->setIsLockedScreen(false);
            }
            return $this->_apiSuccess($login);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("登录异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function update(string $type = null): BaseView
    {
        $login = $this->_checkLogin();
        if (method_exists($this, $type)) {
            $loginId = $this->_request("loginId");
            if ($login->isSuperAdmin() || ($login->isAdmin() && $loginId !== 'root')) {
                if (StringHelper::isNullOrBlank($loginId) || $loginId === $login->getId()) {
                    $user = $login;
                } else {
                    try {
                        $user = LoginModel::get($loginId);
                        if (!($user instanceof LoginModel) || !$user->isExist()) {
                            return $this->_apiFailed("要修改的用户不存在");
                        }
                        //管理员的用户信息只有超级管理员能改
                        if ($user->isAdmin() && !$login->isSuperAdmin()) {
                            return $this->_needPermission("您无权修改该用户信息");
                        }
                    } catch (\Throwable $e) {
                        Mvc::getLogger()->error("获取用户异常", $e->getMessage(), $e);
                        return $this->_apiFailed($e->getMessage());
                    }
                }
            } else {
                $user = $login;
            }

            return call_user_func([$this, $type], $user);
        }
        return $this->_404();
    }

    /**
     * 修改用户密码
     * @param LoginModel $user
     * @return BaseView
     */
    private function password(LoginModel $user): BaseView
    {
        $old = $this->_post("old");
        $new = $this->_post("pwd");
        $renew = $this->_post("renew");
        if (strlen($old) !== 72) {
            return $this->_apiFailed("参数异常");
        }

        if (StringHelper::isNullOrBlank($new)) {
            return $this->_apiFailed("请输入新密码");
        }

        if (StringHelper::isNullOrBlank($renew)) {
            return $this->_apiFailed("请再输入一次新密码");
        }

        if ($new !== $renew) {
            return $this->_apiFailed("两次输入密码不一致");
        }

        if ($user->getPassword() !== HashHelper::passwordHash($old, Mvc::getPasswordSalt())) {
            return $this->_apiFailed("老密码输入错误");
        }
        try {
            $res = $user->setPassword(HashHelper::passwordHash($renew, Mvc::getPasswordSalt()))
                ->update(['password']);
            return $res ? $this->_apiSuccess() : $this->_apiFailed('修改密码失败');
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("修改密码异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    /**
     * 修改用户其他资料
     * @param LoginModel $user
     * @return BaseView
     */
    private function profile(LoginModel $user): BaseView
    {

        return $this->_text(__FUNCTION__);
    }

    public function functions(): BaseView
    {
        $login = $this->_checkLogin();
        return $this->_apiSuccess(FunctionModel::byLogin($login));
    }

    public function screen(string $type = null): BaseView
    {
        $login = $this->_checkLogin(null, false, false, [], false);

        $type = strtoupper($type ?: '');

        if ($type === 'LOCK') {
            $login->setIsLockedScreen(true)->updateSession($this);
            return $this->_apiSuccess();
        }

        if ($type === 'UNLOCK') {
            $pwd = $this->_post('pwd');
            if (StringHelper::isNullOrBlank($pwd)) {
                return $this->_apiFailed("请输入登录密码解锁");
            }
            if (strlen($pwd) !== 72) {
                return $this->_apiFailed('参数异常');
            }
            $hash = HashHelper::passwordHash($pwd, Mvc::getPasswordSalt());
            $totpEnable = Mvc::getConfig("login|totp_enable", false);

            if ($totpEnable && !StringHelper::isNullOrBlank($login->getTotpToken())) {
                $token = (new TOTP($login->getTotpToken()))->now();
                if ($hash === HashHelper::passwordHash(sha1($token) . md5($token), Mvc::getPasswordSalt())) {
                    $login->unlockTryCount = LoginModel::UNLOCK_MAX_TRY;
                    $login->setIsLockedScreen(false)->updateSession($this);
                    return $this->_apiSuccess();
                }
            }


            if ($hash === $login->getPassword()) {
                $login->unlockTryCount = LoginModel::UNLOCK_MAX_TRY;
                $login->setIsLockedScreen(false)->updateSession($this);
                return $this->_apiSuccess();

            }
            $count = $login->unlockTryCount--;
            if ($count === 0) {
                $login->logout($this);
                return $this->_needLogin('您重试次数过多, 请重新登录');
            }
            return $this->_apiFailed('密码或令牌错误, 您还可以重试' . $count . '次');
        }

        return $this->_404();
    }

    public function logout(): BaseView
    {
        $login = $this->_getLogin();
        $login and $login->logout($this);
        return $this->_apiSuccess();
    }
}
