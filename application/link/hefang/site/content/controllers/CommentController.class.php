<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\ArticleModel;
use link\hefang\site\content\models\CommentModel;
use link\hefang\site\content\models\ViewCommentModel;
use link\hefang\site\users\models\LoginModel;


class CommentController extends BaseController
{
    public function insert(): BaseView
    {
        $login = $this->_getLogin();
        $contentId = $this->_post("contentId");
        $comment = $this->_post("comment");
        $authorInfo = $this->_post("authorInfo");
        $replayId = $this->_post("replayId");
        $captchaEnable = Mvc::getConfig("comment|captcha_enable", false);
        if ($login instanceof LoginModel) {
            $authorInfo = [
                'nickname' => $login->getNickName(),
                'email' => CollectionHelper::getOrDefault($authorInfo, 'email'),
                'headImgUrl' => $login->getHeadImgUrl()
            ];
            if ($login->isAdmin()) {
                $captchaEnable = false;
            }
        }
        if ($captchaEnable) {
            $captcha = $this->_post("captcha");
            if (StringHelper::isNullOrBlank($captcha)) {
                return $this->_apiFailed('请输入验证码');
            }
            $captchaAnswer = $this->_session(CaptchaController::CAPTCHA_SESSION_KEY);
            if (StringHelper::isNullOrBlank($captchaAnswer)) {
                return $this->_apiFailed("验证码已过期, 请刷新");
            }
            Mvc::getLogger()->debug("验证码答案", $captchaAnswer);
            if (strcasecmp($captchaAnswer, $captcha) !== 0) {
                return $this->_apiFailed("验证码错误");
            }
        }

        if (strlen($contentId) !== 40 ||
            (!StringHelper::isNullOrBlank($replayId) && strlen($replayId) !== 40) ||
            !is_array($authorInfo)) {
            return $this->_apiFailed('参数异常');
        }


        if (StringHelper::isNullOrBlank($comment)) {
            return $this->_apiFailed("请输入评论内容");
        }

        $m = new CommentModel();

        $m->setId(RandomHelper::guid())
            ->setContentId($contentId)
            ->setComment($comment)
            ->setAuthorInfo($authorInfo)
            ->setReplayId($replayId);

        try {
            $article = ArticleModel::get($contentId);
            if (!($article instanceof ArticleModel)
                || !$article->isExist()
                || $article->isDraft()
                || !$article->isEnable()) {
                return $this->_apiFailed("您要评论的内容不存在或已被删除");
            }
            if (strlen($replayId) == 40) {
                $replay = CommentModel::get($replayId);
                if (!($replay instanceof CommentModel)
                    || !$replay->isExist()
                    || !$replay->isEnable()) {
                    return $this->_apiFailed("您要回复的评论不存在或已被删除");
                }
            }
            $config = Mvc::getConfig('comment|block_words', '');
            $config = str_replace("\n", ' ', $config);
            $blockWords = explode(' ', $config);

            if (StringHelper::contains($comment, true, $blockWords)) {
                return $this->_apiFailed('您的评论中包含违禁词, 请重新发表');
            }

            $res = CommentModel::add($m);
            return $res ? $this->_apiSuccess() : $this->_apiFailed('评论失败');
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("添加评论异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function list(): BaseView
    {
        $login = $this->_getLogin();
        $contentId = $this->_request("contentId");
        $search = $this->_request('search');
        $where = "`enable` = TRUE";
        $sort = $this->_sort('post_time', SqlSort::TYPE_DESC);
        if (($login instanceof LoginModel) && $login->isAdmin()) {
            $checked = strtoupper($this->_request("checked", ''));
            if (in_array($checked, ['1', '0', 'TRUE', 'FALSE'])) {
                $where .= " AND `checked` = " . $checked;
            }
        } else {
            $where .= " AND `checked` = TRUE";
            if (strlen($contentId) !== 40) {
                return $this->_apiFailed('参数异常');
            }
        }
        if (strlen($contentId) === 40) {
            $where .= " AND `content_id` = '{$contentId}'";
        }
        try {
            $pager = ViewCommentModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                $search, $where,
                [$sort]
            );
            return $this->_apiSuccess($pager);
        } catch (SqlException $e) {
            return $this->_exception($e);
        }
    }
}