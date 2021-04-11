package main

import (
	"fmt"
	"math"
	"strings"
)

// 贷款计算器
type LoanCalculator struct {
	DaiKuanBenJin   float64
	DaiKuanNianLiLv float64
	DaiKuanQiShu    int
}

// 还款信息
type Repayment []RepaymentMonth

// 月还款信息
type RepaymentMonth struct {
	QiShu         int
	YueGongZongEr float64
	YueGongBenJin float64
	YueGongLiXi   float64
	YiHuanBenJin  float64
	YiHuanLiXi    float64
	YiHuanZongEr  float64
	ShengYuBenJin float64
}

// 等额本金计算器
func (loanCalculator *LoanCalculator) CalculateDengErBenJin() Repayment {
	repayment := Repayment{}

	daiKuanBenJin := loanCalculator.DaiKuanBenJin
	nianLiLv := loanCalculator.DaiKuanNianLiLv
	daiKuanQiShu := loanCalculator.DaiKuanQiShu

	yueLiLv := nianLiLv / 12
	yueGongBenJin := daiKuanBenJin / float64(daiKuanQiShu)
	shengYuBenJin := daiKuanBenJin
	yiHuanZongEr := float64(0)

	for i := 0; i < daiKuanQiShu; i++ {
		qiShu := i + 1
		yueGongLiXi := shengYuBenJin * yueLiLv
		yueGongZongEr := yueGongBenJin + yueGongLiXi
		yiHuanZongEr := yiHuanZongEr + yueGongZongEr
		shengYuBenJin = shengYuBenJin - yueGongBenJin
		yiHuanBenJin := daiKuanBenJin - shengYuBenJin
		yiHuanLiXi := yiHuanZongEr - yiHuanBenJin

		repaymentMonth := RepaymentMonth{
			QiShu:         qiShu,
			YueGongZongEr: yueGongZongEr,
			YueGongBenJin: yueGongBenJin,
			YueGongLiXi:   yueGongLiXi,
			YiHuanBenJin:  yiHuanBenJin,
			YiHuanLiXi:    yiHuanLiXi,
			YiHuanZongEr:  yiHuanZongEr,
			ShengYuBenJin: shengYuBenJin,
		}

		repayment = append(repayment, repaymentMonth)
	}

	return repayment
}

// 等额本息计算器
func (loanCalculator *LoanCalculator) calculateDengErBenXi() Repayment {
	repayment := Repayment{}

	daiKuanBenJin := loanCalculator.DaiKuanBenJin
	nianLiLv := loanCalculator.DaiKuanNianLiLv
	daiKuanQiShu := loanCalculator.DaiKuanQiShu

	yueLiLv := nianLiLv / 12
	yueGongZongEr := daiKuanBenJin * yueLiLv * math.Pow(1+yueLiLv, float64(daiKuanQiShu)) / (math.Pow(1+yueLiLv, float64(daiKuanQiShu)) - 1)
	shengYuBenJin := daiKuanBenJin
	yiHuanZongEr := float64(0)

	for i := 0; i < daiKuanQiShu; i++ {
		qiShu := i + 1
		yueGongLiXi := shengYuBenJin * yueLiLv
		yueGongBenJin := yueGongZongEr - yueGongLiXi
		yiHuanZongEr = yiHuanZongEr + yueGongZongEr
		shengYuBenJin = shengYuBenJin - yueGongBenJin
		yiHuanBenJin := daiKuanBenJin - shengYuBenJin
		yiHuanLiXi := yiHuanZongEr - yiHuanBenJin

		repaymentMonth := RepaymentMonth{
			QiShu:         qiShu,
			YueGongZongEr: yueGongZongEr,
			YueGongBenJin: yueGongBenJin,
			YueGongLiXi:   yueGongLiXi,
			YiHuanBenJin:  yueGongBenJin,
			YiHuanLiXi:    yiHuanLiXi,
			YiHuanZongEr:  yiHuanZongEr,
			ShengYuBenJin: shengYuBenJin,
		}

		repayment = append(repayment, repaymentMonth)
	}

	return repayment
}

// 生产详情并转字符串
func (loanCalculator *LoanCalculator) GenerateDetailToString() string {
	dengErBenXi  := loanCalculator.calculateDengErBenXi()
	dengErBenJin := loanCalculator.CalculateDengErBenJin()

	// 表头
	outputStr := strings.Join([]string{
		"期数",
		"月供总额(本息)",
		"月供本金(本息)",
		"月供利息(本息)",
		"已还总额(本息)",
		"已还本金(本息)",
		"已还利息(本息)",
		"剩余本金(本息)",
		"月供总额(本金)",
		"月供本金(本金)",
		"月供利息(本金)",
		"已还总额(本金)",
		"已还本金(本金)",
		"已还利息(本金)",
		"剩余本金(本金)",
		"本金法多还总额",
		"本息法多还利息",
	}, ",") + "\n"

	// 数据
	for i := 0; i < len(dengErBenXi); i++ {
		outputStr += strings.Join([]string{
			fmt.Sprintf("%d", dengErBenXi[i].QiShu),
			fmt.Sprintf("%.2f", dengErBenXi[i].YueGongZongEr),
			fmt.Sprintf("%.2f", dengErBenXi[i].YueGongBenJin),
			fmt.Sprintf("%.2f", dengErBenXi[i].YueGongLiXi),
			fmt.Sprintf("%.2f", dengErBenXi[i].YiHuanZongEr),
			fmt.Sprintf("%.2f", dengErBenXi[i].YiHuanBenJin),
			fmt.Sprintf("%.2f", dengErBenXi[i].YiHuanLiXi),
			fmt.Sprintf("%.2f", dengErBenJin[i].ShengYuBenJin),
			fmt.Sprintf("%.2f", dengErBenJin[i].YueGongZongEr),
			fmt.Sprintf("%.2f", dengErBenJin[i].YueGongBenJin),
			fmt.Sprintf("%.2f", dengErBenJin[i].YueGongLiXi),
			fmt.Sprintf("%.2f", dengErBenJin[i].YiHuanZongEr),
			fmt.Sprintf("%.2f", dengErBenJin[i].YiHuanBenJin),
			fmt.Sprintf("%.2f", dengErBenJin[i].YiHuanLiXi),
			fmt.Sprintf("%.2f", dengErBenJin[i].ShengYuBenJin),
			fmt.Sprintf("%.2f", dengErBenJin[i].YiHuanZongEr - dengErBenXi[i].YiHuanZongEr),
			fmt.Sprintf("%.2f", dengErBenXi[i].YiHuanLiXi - dengErBenJin[i].YiHuanLiXi),
		}, ",") + "\n"
	}

	return outputStr
}

// 生产详情并输出CSV文件
func (loanCalculator *LoanCalculator) GenerateDetailToCSV()  {

}
