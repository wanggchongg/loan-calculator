package main

import (
	"flag"
	"fmt"
)

func main()  {
	pArgBenJin   := flag.Float64("bj", 0, "本金")
	pArgLiLv     := flag.Float64("ll", 0, "年利率")
	pArgQiShu    := flag.Int("qs", 0, "贷款期数")
	pArgCommand  := flag.String("cm", "", "命令")
	pArgQiShuEnd := flag.Int("qse", 0, "提前还款期数")

	flag.Parse()

	loanCalculator := LoanCalculator{
		DaiKuanBenJin:   *pArgBenJin,
		DaiKuanNianLiLv: *pArgLiLv,
		DaiKuanQiShu:    *pArgQiShu,
	}

	switch *pArgCommand {
	case "minTouZiLiLv":
		fmt.Println(loanCalculator.CalculateMinTouZiLiLvFromDiffTwoWay(*pArgQiShuEnd))
	case "zongErQiShu":
		fmt.Println(loanCalculator.CalculateQiShuFromDiffYiHuanZongEr())
	case "benJinQiShu":
		fmt.Println(loanCalculator.calculateQiShuFromDiffShengYuBenJin())
	case "detail2CSV":
		fmt.Println(loanCalculator.GenerateDetailToCSV())
	case "detail2String":
	default:
		fmt.Println(loanCalculator.GenerateDetailToString())
	}
}