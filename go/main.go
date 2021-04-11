package main

import (
	"fmt"
)

func main()  {
	loanCalculator := LoanCalculator{
		DaiKuanBenJin:   2000000,
		DaiKuanNianLiLv: 0.0465,
		DaiKuanQiShu:    360,
	}

	fmt.Printf("%v", loanCalculator.GenerateDetailToString())
}