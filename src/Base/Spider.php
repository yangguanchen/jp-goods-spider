<?php

namespace Adways\JPGoodsSpide\Base;

use Curl\Curl;
use Sunra\PhpSimple\HtmlDomParser;

abstract class Spider
{
    const ERR_CODE_OK = 0;
    const ERR_MSG_OK = '';

    const ERR_CODE_PARAM_FORMAT_ERROR = 1;
    const ERR_MSG_PARAM_FORMAT_ERROR = 'Param format error!';

    /**
     * The spider url.
     *
     * @var string
     */
    private $spiderUrl = null;

    /**
     * The status code.
     *
     * @var integer
     */
    protected $errCode = self::ERR_CODE_OK;

    /**
     * The status message.
     *
     * @var string
     */
    protected $errMsg = self::ERR_MSG_OK;

    /**
     * The content of spider url.
     *
     * @var string
     */
    protected $content = null;

    /**
     * The parse result(price) of content.
     * Japanes yen is integer.
     *
     * @var integer
     */
    protected $price = null;

    /**
     * The parse result(image list) of content.
     *
     * @var array
     */
    protected $images = [];

    public function __construct($param)
    {
        $this->initSpiderUrl($param);
    }

    /**
     * create spider url by param.
     *
     * @param  string  $param
     * @return boolean
     */
    private function initSpiderUrl($param)
    {
        if (preg_match('/^http[s]?:\/\//', $param)) {
            $this->spiderUrl = $param;
        } elseif ($this->checkGoodsId($param)) {
            $this->spiderUrl = $this->makeSpiderUrlByGoodsId($param);
        } else {
            $this->errCode = self::ERR_CODE_PARAM_FORMAT_ERROR;
            $this->errMsg = self::ERR_MSG_PARAM_FORMAT_ERROR;
            return false;
        }

        return true;
    }

    /**
     * check goods id format.
     *
     * @param  string  $goodsId
     * @return boolean
     */
    abstact protected function checkGoodsId($goodsId);

    /**
     * make spider url by correct goods id.
     *
     * @param  string  $goodsId
     * @return string
     */
    abstact protected function makeSpiderUrlByGoodsId($goodsId);

    /**
     * get and parse content of spider url 
     *
     * @param  void
     * @return void
     */
    public function spider()
    {
        if ($this->errCode !== 0) {
            return;
        }

        //curl go get content
        
        $this->parseContent();
    }

    /**
     * parse content and set price&images
     *
     * @param  void
     * @return void
     */
    abstact protected function parseContent();
}
