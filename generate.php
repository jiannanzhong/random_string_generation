<?php

$g = new generate();
$ret = $g->generateCode(1);
print $ret;

class generate
{
    private $normalNumSet = '4AM2EVQTHLKRGW6C7YF8NBXJ'; //该进制的所有数字（字符）
    private $randomNumSet = '5DPU3'; //用于混淆的数字（字符）
    private $jinzhiSize; //进制大小
    private $maxEffectiveDigitSize = 4; //最大有效数字位数
    private $minNonEffectiveDigitSize = 2; //最小混淆数字位数

    public function __construct()
    {
        $this->jinzhiSize = strlen($this->normalNumSet);
    }

    public function generateCode($userId)
    {
        //检查是否超出可生成范围
        if ($userId > pow($this->jinzhiSize, $this->maxEffectiveDigitSize)) {
            return '------';
        }

        //把输入的十进制数字转换成自定义进制的数字
        $result = '';
        while (($mod = $userId % $this->jinzhiSize) != 0 | ($userId = (integer)($userId / $this->jinzhiSize)) > 0) {
            $result = $this->convertNum($mod) . $result;
            if ($userId < $this->jinzhiSize) {
                if ($userId != 0) {
                    $result = $this->convertNum($userId) . $result;
                }
                break;
            }
        }

        //加入混淆
        $effectiveDigits = str_split($result);
        $nonEffectiveDigitSize = $this->maxEffectiveDigitSize + $this->minNonEffectiveDigitSize - sizeof($effectiveDigits);
        while ($nonEffectiveDigitSize > 0) {
            array_splice($effectiveDigits, mt_rand(0, sizeof($effectiveDigits)), 0, $this->getRandomNum());
            $nonEffectiveDigitSize--;
        }
        $result = implode('', $effectiveDigits);

        return $result;
    }

    //从混淆数字集合里随机取出一位
    private function getRandomNum()
    {
        $randomNumArray = str_split($this->randomNumSet);
        return $randomNumArray[rand(0, sizeof($randomNumArray) - 1)];
    }

    //把十进制数求余得到的数字转换自定义进制数字
    private function convertNum($decNum)
    {
        $normalNumArray = str_split($this->normalNumSet);

        if ($decNum < 0) {
            return '-';
        } elseif ($decNum < $this->jinzhiSize) {
            return $normalNumArray[$decNum];
        } else {
            return '-';
        }
    }
}



