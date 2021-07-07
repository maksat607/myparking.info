	
	// $(".lazy").slick({
	// 	lazyLoad: 'ondemand', // ondemand progressive anticipated
	// 	infinite: true,
	// 	dots: true,
	// 	autoplay: false,
	// 	arrows: true
	// });	

	$(".newcart__dd > .newcart__btn").click(function () {
		$(this).parent().find(".newcart__des").slideUp(500);
		if (
			$(this)
				.parent()
				.hasClass("active")
		) {
			$(this).removeClass("active");
			$(this)
				.parent()
				.removeClass("active");
		} else {
			$(this).removeClass("active");
			$(this)
				.next(".newcart__des")
				.slideDown(500);
			$(this)
				.parent()
				.addClass("active");
		}
	});

	$('.newtopbar__mobtitle').append($('.newtopbar__item.active').text().trim());

	
	$(".newtopbar__mob").click(function () {
		$(".newtopbar__nav").slideUp(500);
		if (
			$(this).hasClass("show")
		) {
			$(this).removeClass("show");
		} else {
			$(this)
				.next(".newtopbar__nav")
				.slideDown(500);
			$(this).addClass("show");
		}
	});

	$(".newpopup__data > h3").click(function () {
		let ul = $(this).parent().find('.newpopup__ul');

		$(this).next(".newpopup__ul").slideToggle(500);
		$(this).parent().toggleClass("dsactive");

	});

	$('.newcart__moreinfo').click(function () {
		$('.newpopup').toggleClass('show');
		return false;
	});
	$('.newpopup__close').click(function () {
		$('.newpopup').toggleClass('show');
		return false;
	});

	$(".newcart__btnpop").click(function () {
		$(".newcart__setting").slideUp(500);
		if (
			$(this)
				.parent()
				.hasClass("active")
		) {
			$(".newcart__body td").removeClass("active");
			$(this)
				.parent()
				.removeClass("active");
		} else {
			$(".newcart__body td").removeClass("active");
			$(this)
				.next(".newcart__setting")
				.slideDown(500);
			$(this)
				.parent()
				.addClass("active");
		}
	});
	/*
	const tabs = document.querySelector(".tabform__wrap");
	const tabButton = document.querySelectorAll(".tabform__btn");
	const contents = document.querySelectorAll(".tabform__content");

	tabs.onclick = e => {
		const id = e.target.dataset.id;
		if (id) {
			tabButton.forEach(btn => {
				btn.classList.remove("active");
			});
			e.target.classList.add("active");

			contents.forEach(content => {
				content.classList.remove("active");
			});
			const element = document.getElementById(id);
			element.classList.add("active");
		}
	}

	*/

	$(".tabform__cart > h3").click(function () {
		let ul = $(this).parent().find('.tabform__mob-dd');

		$(this).next(".tabform__mob-dd").slideToggle(500);
		$(this).parent().toggleClass("active");

	});

	$(".newtopbar__mobtab").click(function () {
		$(".buttonWrapper").slideUp(500);
		if (
			$(this).hasClass("show")
		) {
			$(this).removeClass("show");
		} else {
			$(this)
				.next(".buttonWrapper")
				.slideDown(500);
			$(this).addClass("show");
		}
	});
	$('.newtopbar__mobtitletab').html($('.tabform__btn.active').text().trim());

	$(".newtopbar .tabform__btn").click(function () {

		let tabid = $(this).data("id");
		$(".newtopbar .tabform__btn").removeClass("active");
		$(this).addClass("active");
		$('.newtopbar__mobtitletab').html($(this).text().trim());

		$('.tabform__content').removeClass('active');
		$('#' + tabid).addClass('active');
	});

	$(".modal-dd-btn").click(function () {
		$(this).parent().find(".modal-dd").slideDown(500);
		if (
			$(this).hasClass("hide")
		) {
			$(this).removeClass("hide");
			$(this).removeClass("hide");
		} else {
			$(this).removeClass("hide");
			$(this).next(".modal-dd").slideUp(500);
			$(this).addClass("hide");
		}
	});

	$(".mob-menu-btn").click(function () {
		$(this).toggleClass("active");
		$(this).parent().find('.header__nav').toggleClass("active");
		// $(this).parent().parent().find('.header').toggleClass("sticky");
	});

	$(".nav__item.nav__dd").click(function () {
		$(this).toggleClass("active");
	});

	$(".header__user-info.nav__dd").click(function () {
		$(this).toggleClass("active");
	});
