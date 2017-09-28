$(".pricing .full-container .plan .contratar select")
		.change(
				function(e) {
					var option = this.options[e.target.selectedIndex];
					var planId = option.getAttribute("data-plan");
					var planTotal = $(".pricing .full-container .plan[data-plan=" + planId + "] .plan-detail .contratar .plan-total p");
					if (option.value != "anual") {
						planTotal.css("display", "none");
					} else {
						planTotal.css("display", "block");
					}
					var amount = $(".pricing .full-container .plan[data-plan=" + planId + "] .price .amount");
					var price = option.getAttribute("data-price"); 
					amount[0].innerHTML = price;
				});