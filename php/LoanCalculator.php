<?php

/**
 * Class LoanCalculator 贷款计算器
 */
class LoanCalculator
{
    const QI_SHU           = 'qi_shu';
    const YUE_GONG_ZONG_ER = 'yue_gong_zong_er';
    const YUE_GONG_BEN_JIN = 'yue_gong_ben_jin';
    const YUE_GONG_LI_XI   = 'yue_gong_li_xi';
    const YI_HUAN_BEN_JIN  = 'yi_huan_ben_jin';
    const YI_HUAN_LI_XI    = 'yi_huan_li_xi';
    const YI_HUAN_ZONG_ER  = 'yi_huan_zong_er';
    const SHENG_YU_BEN_JIN = 'sheng_yu_ben_jin';

    /** @var integer $daiKuanBenJin 贷款本金 */
    protected $daiKuanBenJin;

    /** @var integer $daiKuanNianLiLv 贷款年利率 */
    protected $daiKuanNianLiLv;

    /** @var integer $daiKuanQiShu 贷款期数 */
    protected $daiKuanQiShu;

    /**
     * LoanCalculator constructor.
     *
     * @param integer $daiKuanBenJin 贷款本金
     * @param integer $daiKuanNianLiLv 贷款年利率
     * @param integer $daiKuanQiShu 贷款期数
     */
    public function __construct($daiKuanBenJin, $daiKuanNianLiLv, $daiKuanQiShu)
    {
        $this->daiKuanBenJin   = $daiKuanBenJin;
        $this->daiKuanNianLiLv = $daiKuanNianLiLv;
        $this->daiKuanQiShu    = $daiKuanQiShu;
    }

    /**
     * 等额本金计算器
     *
     * @return array
     */
    public function calculateDengErBenJin()
    {
        $daiKuanBenJin = $this->daiKuanBenJin;
        $nianLiLv      = $this->daiKuanNianLiLv;
        $daiKuanQiShu  = $this->daiKuanQiShu;


        $yueLiLv       = $nianLiLv / 12;
        $yueGongBenJin = $daiKuanBenJin / $daiKuanQiShu;

        $shengYuBenJin = $daiKuanBenJin;
        $yiHuanZongEr  = 0;
        $shengYuQiShu  = $daiKuanQiShu;

        $repayment = [];

        while ($shengYuQiShu) {
            $yueGongLiXi   = $shengYuBenJin * $yueLiLv;
            $yueGongZongEr = $yueGongBenJin + $yueGongLiXi;
            $yiHuanZongEr  = $yiHuanZongEr + $yueGongZongEr;
            $shengYuBenJin = $shengYuBenJin - $yueGongBenJin;
            $yiHuanBenJin  = $daiKuanBenJin - $shengYuBenJin;
            $yiHuanLiXi    = $yiHuanZongEr - $yiHuanBenJin;

            $repayment[] = [
                self::QI_SHU         => $daiKuanQiShu - $shengYuQiShu + 1,
                self::YUE_GONG_ZONG_ER => round($yueGongZongEr, 2),
                self::YUE_GONG_BEN_JIN => round($yueGongBenJin, 2),
                self::YUE_GONG_LI_XI   => round($yueGongLiXi, 2),
                self::YI_HUAN_ZONG_ER  => round($yiHuanZongEr, 2),
                self::YI_HUAN_BEN_JIN  => round($yiHuanBenJin, 2),
                self::YI_HUAN_LI_XI    => round($yiHuanLiXi, 2),
                self::SHENG_YU_BEN_JIN => round($shengYuBenJin, 2),
            ];

            $shengYuQiShu--;
        }

        return $repayment;
    }

    /**
     * 等额本息计算器
     *
     * @return array
     */
    public function calculateDengErBenXi()
    {
        $daiKuanBenJin = $this->daiKuanBenJin;
        $nianLiLv      = $this->daiKuanNianLiLv;
        $daiKuanQiShu  = $this->daiKuanQiShu;

        $yueLiLv = $nianLiLv / 12;

        $yueGongZongEr = $daiKuanBenJin * $yueLiLv * pow(1 + $yueLiLv, $daiKuanQiShu) / (pow(1 + $yueLiLv, $daiKuanQiShu) - 1);
        $shengYuBenJin = $daiKuanBenJin;
        $yiHuanZongEr  = 0;
        $shengYuQiShu  = $daiKuanQiShu;

        $repayment = [];

        while ($shengYuQiShu) {
            $yueGongLiXi   = $shengYuBenJin * $yueLiLv;
            $yueGongBenJin = $yueGongZongEr - $yueGongLiXi;
            $yiHuanZongEr  = $yiHuanZongEr + $yueGongZongEr;
            $shengYuBenJin = $shengYuBenJin - $yueGongBenJin;
            $yiHuanBenJin  = $daiKuanBenJin - $shengYuBenJin;
            $yiHuanLiXi    = $yiHuanZongEr - $yiHuanBenJin;

            $repayment[] = [
                self::QI_SHU           => $daiKuanQiShu - $shengYuQiShu + 1,
                self::YUE_GONG_ZONG_ER => round($yueGongZongEr, 2),
                self::YUE_GONG_BEN_JIN => round($yueGongBenJin, 2),
                self::YUE_GONG_LI_XI   => round($yueGongLiXi, 2),
                self::YI_HUAN_ZONG_ER  => round($yiHuanZongEr, 2),
                self::YI_HUAN_BEN_JIN  => round($yiHuanBenJin, 2),
                self::YI_HUAN_LI_XI    => round($yiHuanLiXi, 2),
                self::SHENG_YU_BEN_JIN => round($shengYuBenJin, 2),
            ];

            $shengYuQiShu--;
        }

        return $repayment;
    }

    /**
     * 计算两种还款方式已还总额相等的期数
     *
     * @return int
     */
    public function calculateQiShuFromDiffYiHuanZongEr()
    {
        $dengErBenXi  = $this->array2Map($this->calculateDengErBenXi(), self::QI_SHU);
        $dengErBenJin = $this->array2Map($this->calculateDengErBenJin(), self::QI_SHU);

        $diffQiShu = 0;
        foreach ($dengErBenXi as $qiShu => $dengErBenXiYue) {
            if ($dengErBenXiYue[self::YI_HUAN_ZONG_ER] >= $dengErBenJin[$qiShu][self::YI_HUAN_ZONG_ER]) {
                $diffQiShu = $qiShu;
                break;
            }
        }

        return $diffQiShu;
    }

    /**
     * 计算两种还款方式剩余本金相等的期数
     *
     * @return int
     */
    public function calculateQiShuFromDiffShengYuBenJin()
    {
        $dengErBenXi  = $this->array2Map($this->calculateDengErBenXi(), self::QI_SHU);
        $dengErBenJin = $this->array2Map($this->calculateDengErBenJin(), self::QI_SHU);

        $diffQiShu = $this->daiKuanQiShu;
        foreach ($dengErBenXi as $qiShu => $dengErBenXiYue) {
            if ($dengErBenXiYue[self::SHENG_YU_BEN_JIN] < $dengErBenJin[$qiShu][self::SHENG_YU_BEN_JIN]) {
                $diffQiShu = $qiShu;
                break;
            }
        }

        return $diffQiShu;
    }

    /**
     * 计算最小投资回报利率
     *
     * @param $qiShuEnd
     * @return float
     */
    public function calculateMinTouZiLiLvFromDiffTwoWay($qiShuEnd)
    {
        if ($qiShuEnd <= 0) {
            $qiShuEnd = $this->daiKuanQiShu;
        }

        $dengErBenXi  = $this->array2Map($this->calculateDengErBenXi(), self::QI_SHU);
        $dengErBenJin = $this->array2Map($this->calculateDengErBenJin(), self::QI_SHU);

        $touZiNianLiLv = 0.15;

        while ($touZiNianLiLv > 0) {
            $touZiYueLiLv = $touZiNianLiLv / 12;
            $shouYiZongEr = 0;

            for ($qiShu = 1; $qiShu <= $qiShuEnd; $qiShu++) {
                $duoHuanZongEr = $dengErBenJin[$qiShu][self::YI_HUAN_ZONG_ER] - $dengErBenXi[$qiShu][self::YI_HUAN_ZONG_ER];
                $shouYiYue     = $duoHuanZongEr * $touZiYueLiLv;
                $shouYiZongEr  = $shouYiZongEr + $shouYiYue;
            }

            $duoHuanLiXi = $dengErBenXi[$qiShuEnd][self::YI_HUAN_LI_XI] - $dengErBenJin[$qiShuEnd][self::YI_HUAN_LI_XI];

            if ($shouYiZongEr < $duoHuanLiXi) {
                break;
            }

            $touZiNianLiLv -= 0.0001;
        }


        return round($touZiNianLiLv, 4);
    }

    /**
     * 生产详情并转字符串
     *
     * @return string
     */
    public function generateDetailToString()
    {
        $dengErBenXi  = $this->array2Map($this->calculateDengErBenXi(), self::QI_SHU);
        $dengErBenJin = $this->array2Map($this->calculateDengErBenJin(), self::QI_SHU);

        $output = implode(',', [
                '期数',
                '月供总额(本息)',
                '月供本金(本息)',
                '月供利息(本息)',
                '已还总额(本息)',
                '已还本金(本息)',
                '已还利息(本息)',
                '剩余本金(本息)',
                '月供总额(本金)',
                '月供本金(本金)',
                '月供利息(本金)',
                '已还总额(本金)',
                '已还本金(本金)',
                '已还利息(本金)',
                '剩余本金(本金)',
                '本金法多还总额',
                '本息法多还利息',
            ]) .PHP_EOL;

        foreach ($dengErBenXi as $qiShu => $item) {
            $output .= (implode(',', [
                    $qiShu,
                    $item[self::YUE_GONG_ZONG_ER],
                    $item[self::YUE_GONG_BEN_JIN],
                    $item[self::YUE_GONG_LI_XI],
                    $item[self::YI_HUAN_ZONG_ER],
                    $item[self::YI_HUAN_BEN_JIN],
                    $item[self::YI_HUAN_LI_XI],
                    $item[self::SHENG_YU_BEN_JIN],
                    $dengErBenJin[$qiShu][self::YUE_GONG_ZONG_ER],
                    $dengErBenJin[$qiShu][self::YUE_GONG_BEN_JIN],
                    $dengErBenJin[$qiShu][self::YUE_GONG_LI_XI],
                    $dengErBenJin[$qiShu][self::YI_HUAN_ZONG_ER],
                    $dengErBenJin[$qiShu][self::YI_HUAN_BEN_JIN],
                    $dengErBenJin[$qiShu][self::YI_HUAN_LI_XI],
                    $dengErBenJin[$qiShu][self::SHENG_YU_BEN_JIN],
                    round($dengErBenJin[$qiShu][self::YI_HUAN_ZONG_ER] - $item[self::YI_HUAN_ZONG_ER], 2),
                    round($item[self::YI_HUAN_LI_XI] - $dengErBenJin[$qiShu][self::YI_HUAN_LI_XI], 2),
                ]) . PHP_EOL);
        }

        return $output;
    }

    /**
     * 生产详情并输出CSV文件
     */
    public function generateDetailToCSV()
    {
        $outputFile = 'loan_calculator_' . date("YmdHis") . '.csv';
        $outputFp   = fopen($outputFile, 'w+');
        fputs($outputFp, iconv('UTF-8', 'GBK', $this->toString()));
    }

    /**
     * 数组转字典
     *
     * @param $array
     * @param $key
     *
     * @return array
     */
    private static function array2Map($array, $key)
    {
        $result = [];
        foreach ($array as $item) {
            $result[$item[$key]] = $item;
        }

        return $result;
    }
}
