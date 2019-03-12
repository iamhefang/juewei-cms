<?php

namespace link\hefang\site\users\controllers;


use link\hefang\helpers\HashHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\otp\TOTP;
use link\hefang\site\users\models\LoginModel;

class TokenController extends BaseController
{
    public function check(): BaseView
    {
        $login = $this->_checkLogin(null, false, false, [], false);
        if (!$login instanceof LoginModel) return $this->_apiFailed('');
        $totpEnable = Mvc::getConfig('login|2fa_enable', false);
        if (!$totpEnable) {
            return $this->_apiFailed("系统未启用二步验证");
        }
        if (StringHelper::isNullOrBlank($login->getTotpToken())) {
            return $this->_apiFailed("当前账号未配置二步验证");
        }
        if ($login->isPassedTotp()) {
            return $this->_apiSuccess();
        }
        $token = $this->_request('token', '');
        if (strlen($token) !== 6) {
            $login->setIsPassedTotp(false)
                ->setIsLockedScreen(true)
                ->updateSession($this);
            return $this->_apiFailed('令牌不正确');
        }
        $totp = (new TOTP($login->getTotpToken()))->now();
        Mvc::getLogger()->debug("Check Token", "post:{$token}, should:{$totp}");
        if ($token != $totp) {
            $login->setIsPassedTotp(false)
                ->setIsLockedScreen(true)
                ->updateSession($this);
            return $this->_apiFailed('令牌错误');
        }
        $login->setIsPassedTotp(true)
            ->setIsLockedScreen(false)
            ->updateSession($this);
        if ($login->isAutoLoginNextTime()) {
            $cacheId = RandomHelper::guid();
            $cache = Mvc::getCache();
            $expire = time() + 10 * 24 * 60 * 60;
            $cache->set($cacheId, [
                'loginId' => $login->getId(),
                'loginTime' => time(),
                'userAgent' => $this->_userAgent()
            ], $expire);//10天
            $cookie = HashHelper::desEncrypt($cacheId, Mvc::getProperty('cookie.salt', php_uname()));
            Mvc::getLogger()->debug("cookie", $cookie);
            setcookie("token", $cookie, $expire, '/', '', false, true);
        }
        return $this->_apiSuccess();
    }

    public function bind(): BaseView
    {
        $login = $this->_checkLogin();
        if (!$login instanceof LoginModel) return $this->_apiFailed('');

        $totpEnable = Mvc::getConfig('login|2fa_enable', false);
        if (!$totpEnable) return $this->_apiFailed("后台未启用二步验证");
        $secret = $this->_post('secret');
        $token = $this->_post('token');
        if (StringHelper::isNullOrBlank($secret) || StringHelper::isNullOrBlank($token)) {
            return $this->_apiFailed("参数异常");
        }
        if (strlen($token) !== 6) {
            return $this->_apiFailed("代码不正确");
        }

        try {
            Mvc::getLogger()->debug("secret", '||' . $secret . '||');
            $totp = new TOTP($secret);
            if ($token != $totp->now()) {
                return $this->_apiFailed("代码错误");
            }
            $login->setTotpToken($secret)
                ->update(['totp_token']);
            $login->updateSession($this);
            return $this->_apiSuccess();
        } catch (\Throwable $e) {
            return $this->_exception($e, "绑定身份令牌异常");
        }
    }
}