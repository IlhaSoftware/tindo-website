$(".pricing .full-container .plan .contratar select")
		.change(
				function(e) {
					var option = this.options[e.target.selectedIndex];
					var planId = option.getAttribute("data-plan");
					var amount = $(".pricing .full-container .plan .price .amount[data-plan="
							+ planId + "]");
					var price = option.getAttribute("data-price"); 
					amount[0].innerHTML = price;
				});